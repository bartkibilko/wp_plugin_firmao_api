<?php

function add_settings_page()
{
    add_options_page('Firmao API', 'Firmao API', 'manage_options', 'settings - firmao - api', 'render_plugin_settings_page');
}


function render_plugin_settings_page()
{
    ?>
    <h2>Firmao API Settings</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields('firmao_plugin_options');
        do_settings_sections('firmao_plugin_page'); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>"/>
    </form>
    <?php
}

function register_settings()
{
    register_setting('firmao_plugin_options', 'firmao_plugin_options');
    add_settings_section('api_settings', 'API Settings', 'plugin_section_text', 'firmao_plugin_page');

    add_settings_field('firmao_setting_api_url', 'API Url', 'firmao_setting_api_url', 'firmao_plugin_page', 'api_settings');
    add_settings_field('firmao_setting_api_login', 'API Login', 'firmao_setting_api_login', 'firmao_plugin_page', 'api_settings');
    add_settings_field('firmao_setting_api_pass', 'API Password', 'firmao_setting_api_pass', 'firmao_plugin_page', 'api_settings');
}

function plugin_section_text()
{
    echo '<p>Here you can set all the options for using the API</p>';
}

function firmao_setting_api_url()
{
    $options = get_option('firmao_plugin_options');
    echo "<input id='firmao_setting_api_url' name='firmao_plugin_options[api_url]' type='text' value='{$options['api_url']}' />";
}

function firmao_setting_api_login()
{
    $options = get_option('firmao_plugin_options');
    echo "<input id='firmao_setting_api_login' name='firmao_plugin_options[api_login]' type='text' value='{$options['api_login']}' />";
}

function firmao_setting_api_pass()
{
    $options = get_option('firmao_plugin_options');
    echo "<input id='firmao_setting_api_pass' name='firmao_plugin_options[api_pass]' type='password' value='{$options['api_pass']}' />";
}

function getCustomersEndpoint()
{
    // https://system.firmao.pl/fundacjamlodemamy/svc/v1/customers
    $apiUrl = get_option('firmao_plugin_options')['api_url'];
    if (empty($apiUrl)) {
        throw new \Exception('API Url is not set');
    }

    return $apiUrl . '/customers';
}

function getAuth()
{
    // base64_encode("fundacjamlodemamy.api@firmao.pl:c05574e788a942a2");
    $options = get_option('firmao_plugin_options');
    $apiLogin = $options['api_login'];
    $apiPassword = $options['api_pass'];
    if (empty($apiLogin) || empty($apiPassword)) {
        throw new \Exception('API login or password are not set');
    }

    return base64_encode($apiLogin . ':' . $apiPassword);
}


add_action('admin_menu', 'add_settings_page');
add_action('admin_init', 'register_settings');
