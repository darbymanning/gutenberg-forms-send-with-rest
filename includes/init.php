<?php

require_once WP_PLUGIN_DIR . '/forms-gutenberg/triggers/email.php';

// We extend the Email class in order to expose a validate fields method.
class _Email extends Email
{
    public function validateFields($fields = [])
    {
        $_fields = [];
        $arranged_fields = [];

        foreach ($fields as $field) {
            $_fields[$field['field_id']] = $field['field_value'];
        }

        foreach ($_fields as $field_id => $field_value) {
            $exploded_id = explode("__", $field_id);
            $field_type = end($exploded_id);
            $f_DECODED = $this->validator->decode($field_type);
            $type = array_key_exists('type', $this->validator->decode($field_type)) ? $this->validator->decode($field_type)['type'] : "";
            $is_valid = $this->validator->validate($type, $field_value, $field_type);
            $id = end($f_DECODED);
            $sanitizedValue = $this->validator->sanitizedValue($type, $field_value);
            $sanitized_field_value = null;
            if (is_array($field_value)) {
                $sanitized_field_value = join(",", $field_value);
            } else if ($id === 'upload') {
                $sanitized_field_value = $field_value;
            } else {
                $sanitized_field_value = $sanitizedValue;
            }
            $arranged_data = [
                'field_data_id' => $id,
                'field_value' => $sanitized_field_value,
                'is_valid' => $field_id === "g-recaptcha-response" ? true : $is_valid,
                'field_id' => $field_id,
                'field_type' => $type,
                'decoded_entry' => $this->validator->decode($field_type),
            ];
            if ($type === 'file_upload') {
                $file_to_upload = $_FILES;
                $file_name = $file_to_upload[$field_id]['name'];
                $tmp_name = $file_to_upload[$field_id]['tmp_name'];
                $parsed_alloweds = json_decode($f_DECODED['extra_meta'], false);
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $is_allowed = $this->validator->test_file_formats($ext, $parsed_alloweds);
                if ($is_allowed) {
                    $created_file = Bucket::upload($tmp_name, $ext);
                    $arranged_data['file_name'] = $created_file['filename'];
                    $this->attachments[] = $created_file['path'];
                } else {
                    $arranged_data['is_valid'] = false;
                }
            }
            if ($this->validator->is_hidden_data_field($field_id)) {
                $arranged_data['is_valid'] = true;
            }
            $arranged_fields[] = $arranged_data;
        }
        return $this->is_fields_valid($arranged_fields) ? $arranged_fields : false;
    }
}

add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/cwp_gf_send_mail', [
        'methods' => 'POST',
        'callback' => function ($req) {
            // Get the required params from our request
            $fields = $req->get_param('fields');
            $attrs = $req->get_param('attrs');
            $post_id = $req->get_param('postId');

            $submit = $attrs['id'];
            $form_label = $attrs['formLabel'];
            $form_id = "form-" . explode("-", $submit)[1];
            $post = get_post($post_id);
            $parsed_blocks = parse_blocks(do_shortcode($post->post_content));

            // Instantiate the Email class
            $email = new _Email($parsed_blocks);

            $valid_fields = $email->validateFields($fields);

            // Check for any errors
            $error = new WP_Error();

            if (!$fields) {
                $error->add(400, 'Please provide a fields array. 🙏', 'fields');
            }
            if (!$attrs) {
                $error->add(400, 'Please provide an attrs object. 🙏', 'attrs');
            }
            if (!$post_id) {
                $error->add(400, 'Please provide a postId. 🙏', 'post_id');
            }
            if (!$submit) {
                $error->add(400, 'Please provide a submit identifier. 🙏', 'submit');
            }
            if (!$post) {
                $error->add(400, "Unable to find a post with the ID of $post_id. 😨", 'post_exists');
            }
            if (!$parsed_blocks) {
                $error->add(400, "Unable to parse any blocks for the post ID $post_id. 😨", 'post_exists');
            }
            if (!$valid_fields) {
                $error->add(400, 'Your fields are not valid. 😨', 'valid_fields');
            }
            if (!empty($error->get_error_codes())) {
                return wp_send_json_error($error);
            }

            // Add our form label and form ID to the fields array
            array_push($valid_fields,
                [
                    field_value => $form_label,
                    field_id => 'gf_form_label',
                ],
                [
                    field_value => $form_id,
                    field_id => 'gf_form_id',
                ]);

            // Submit our form data and create the entry using the sendMail method.
            $_POST['submit'] = $submit;
            $email->sendMail($valid_fields);
            ob_end_clean();

            return wp_send_json_success($valid_fields);
        },
    ]);
});
