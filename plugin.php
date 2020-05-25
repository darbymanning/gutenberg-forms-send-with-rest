<?php

/**
 * @link              https://darbymanning.com
 * @since             1.0.0
 * @package           Gutenberg_Forms_Send_With_Rest
 *
 * @wordpress-plugin
 * Plugin Name:       Gutenberg Forms send with REST
 * Plugin URI:        https://github.com/darbymanning/gutenberg-forms-send-with-rest
 * Description:       Creates a new endpoint to send mail via REST with the Gutenberg Forms plugin.
 * Version:           1.0.2
 * Author:            Darby Manning
 * Author URI:        https://darbymanning.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gutenberg-forms-send-with-rest
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('GUTENBERG_FORMS_TO_REST_VERSION', '1.0.2');

if (is_plugin_active('forms-gutenberg/plugin.php')) {
    require_once plugin_dir_path(__FILE__) . 'includes/init.php';
} else {
    require_once plugin_dir_path(__FILE__) . 'includes/throw-error.php';
}
