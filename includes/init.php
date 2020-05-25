<?php

require_once WP_PLUGIN_DIR . '/forms-gutenberg/triggers/email.php';

function create_gf_extra_endpoint()
{
    register_rest_route('wp/v2', '/cwp_gf_send_mail', [
        'methods' => 'POST',
        'callback' => 'send_mail',
    ]);
}

function validate_request($fields, $attrs, $post_id, $submit, $post, $parsed_blocks)
{
    $error = new WP_Error();

    if (!$fields) {
        $error->add(400, 'Please provide a fields array. 🙏', 'fields');
    }

    if (!$attrs) {
        $error->add(400, 'Please provide an attrs object. 🙏', 'attrs');
    }

    if (!$post_id) {
        $error->add(400, 'Please provide a post_id. 🙏', 'post_id');
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

    return $error;
}

function send_mail($req)
{
    $fields = $req->get_param('fields');
    $attrs = $req->get_param('attrs');
    $submit = $attrs['id'];
    $post_id = $req->get_param('post_id');
    $post = get_post($post_id);
    $parsed_blocks = parse_blocks(do_shortcode($post->post_content));
    $form_label = $attrs['formLabel'];
    $form_id = "form-" . explode("-", $submit)[1];

    $error = validate_request($fields, $attrs, $post_id, $submit, $post, $parsed_blocks);

    if (!empty($error->get_error_codes())) {
        return wp_send_json_error($error);
    }

    array_push($fields, [
        field_data_id => false,
        field_value => $form_label,
        is_valid => true,
        field_id => 'gf_form_label',
        field_type => '',
        decoded_entry => [],
    ],
    [
        field_data_id => false,
        field_value => $form_id,
        is_valid => true,
        field_id => 'gf_form_id',
        field_type => '',
        decoded_entry => [],
    ]);

    $_POST['submit'] = $submit;

    $email = new Email($parsed_blocks);
    $email->init();
    $email->sendMail($fields);

    ob_end_clean();
    return wp_send_json_success('Passed on that data to Gutenberg Forms to process like a boss. 😎');

}

add_action('rest_api_init', 'create_gf_extra_endpoint');
