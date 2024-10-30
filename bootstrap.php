<?php
/*
Plugin Name: Linvo - Time your marketing
Plugin URI: https://linvo.io
description: Engage your customers when it matters most, easily recovers customers in any niche.
Version: 1.0
Author: Linvo
License: GPL2
*/

add_action( 'admin_menu', 'linvo_settings');

function linvo_settings() {
    add_menu_page(
        'Linvo Configuration',
        'Linvo',
        'administrator',
        'linvo-edit',
        'linvo_plugin_page',
        plugins_url('/images/linvo.svg', __FILE__) ,
        6
    );

    add_action( 'admin_init', 'register_linvo_field' );
}

function register_linvo_field() {
    //register our settings
    register_setting( 'my-cool-plugin-settings-group', 'api-key' );
}

function linvo_plugin_page() {
    $apiKey = esc_attr( get_option('api-key'));

    ob_start();
        settings_fields( 'my-cool-plugin-settings-group' );
    $settings = ob_get_clean();

    ob_start();
        do_settings_sections('my-cool-plugin-settings-group');
    $doSettings = ob_get_clean();

    ob_start();
        submit_button();
    $submit = ob_get_clean();


    $button = !empty($apiKey) ? "<div style='border: 1px solid green; border-radius: 10px; background: lightgreen; padding: 10px'>Connected</div>" : "<div style='border: 1px solid red; border-radius: 10px; background: #f1a4a4; padding: 10px'>Not Connected</div>";
    $html = <<<HTML
<div class="wrap">
    <h1>Linvo</h1>
    <p>Open your chrome extension, and click "Connect Plugin"</p>
    
    <form method="post" action="options.php">
        {$settings}
        {$doSettings}
        <table>
            <tr>
                <td><strong>API-KEY</strong></td>
                <td>
                    {$button}
                    <input type="hidden" id="api-key" name="api-key" value="{$apiKey}" />
                </td>
            </tr>
        </table>
        <div style="visibility: hidden">
            {$submit}
        </div>
    </form>
</div>
HTML;

    echo $html;
}

add_action('wp_footer', 'linvo_footer_script');
function linvo_footer_script(){
    $apiKey = esc_attr( get_option('api-key'));
    if (empty($apiKey)) {
        return ;
    }

    $script = <<<HTML
<script async src="https://api.linvo.io/js/plugin.bundle.js?id={$apiKey}"></script>
HTML;

    echo $script;
}
