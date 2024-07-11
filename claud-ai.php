<?php
/*
Plugin Name: Claud AI
Author: Hussain
Description: A plugin to integrate Claud AI with a settings page and shortcode.
Version: 1.0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Step 1: Create the settings page
function claud_ai_create_menu() {
    add_options_page(
        'Claud Settings', 
        'Claud Settings', 
        'manage_options', 
        'claud-ai-settings', 
        'claud_ai_settings_page'
    );
}
add_action('admin_menu', 'claud_ai_create_menu');

function claud_ai_settings_page() {
    ?>
    <div class="wrap">
        <h1>Claud AI Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('claud-ai-settings-group'); ?>
            <?php do_settings_sections('claud-ai-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Claud API</th>
                    <td><input type="text" name="claud_api" value="<?php echo esc_attr(get_option('claud_api')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function claud_ai_register_settings() {
    register_setting('claud-ai-settings-group', 'claud_api');
}
add_action('admin_init', 'claud_ai_register_settings');

// Step 2: Add settings link on plugin page
function claud_ai_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=claud-ai-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'claud_ai_settings_link');

// Step 3: Enqueue CSS and JS files
function claud_ai_enqueue_scripts() {
    wp_enqueue_style('claud-ai-css', plugin_dir_url(__FILE__) . 'css/claud-ai.css');
    wp_enqueue_script('claud-ai-js', plugin_dir_url(__FILE__) . 'js/claud-ai.js', array('jquery'), null, true);

    wp_localize_script('claud-ai-js', 'claud_ai_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'claud_ai_enqueue_scripts');

// Step 4: Create the shortcode
function claud_ai_shortcode() {
    ob_start();
    ?>
    <div id="claud-ai-form">
        <input type="text" id="claud-ai-input" />
        <button id="claud-ai-submit">Submit</button>
        <div id="claud-ai-response"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('claud_ai_form', 'claud_ai_shortcode');

// Step 5: Handle the AJAX request
function claud_ai_action() {
    if (isset($_POST['input'])) {
        $input = sanitize_text_field($_POST['input']);
        echo 'You entered: ' . $input;
    }
    wp_die();
}
add_action('wp_ajax_claud_ai_action', 'claud_ai_action');
add_action('wp_ajax_nopriv_claud_ai_action', 'claud_ai_action');
