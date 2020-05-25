<?php

function show_admin_error($string)
{
    echo '<div class="error notice">
        <p>Please enable <a target="_blank" href="https://wordpress.org/plugins/forms-gutenberg/" rel="noopener noreferrer">Gutenberg Forms</a> to use this plugin.</p>
    </div>';
}

add_action('admin_notices', 'show_admin_error');
deactivate_plugins(plugin_basename(__FILE__));
