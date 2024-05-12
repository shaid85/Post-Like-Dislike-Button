<?php

/**
 * Plugin Name:       Ews Like Button
 * Plugin URI:        https://wpdev.fun/
 * Description:       Like/dislike buttor for post.
 * Version:           1.0.1
 * Requires at least: 6.5.3
 * Requires PHP:      7.4
 * Author:            Shaid Islam
 * Author URI:        https://saidul.wpdev.fun/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ewslike
 */

// don't call the file directly
if (!defined('ABSPATH'))  exit;

if (!defined('EWSLIKE_PLUGIN_VERSION')) {
    define('EWSLIKE_PLUGIN_VERSION', '1.0.0');
}

if (!defined('EWSLIKE_PLUGIN_DIR')) {
    define('EWSLIKE_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

if (!function_exists('ewslike_enqueue_scripts')) {
    function ewslike_enqueue_scripts()
    {
        wp_enqueue_style('ews-like', EWSLIKE_PLUGIN_DIR . 'assets/css/ews-like.css', array(), '1.0', 'all');
        wp_enqueue_style('ews-svg', EWSLIKE_PLUGIN_DIR . 'assets/css/svg.css', array(), '1.0', 'all');
        wp_enqueue_script('ews-like', EWSLIKE_PLUGIN_DIR . 'assets/js/likebtn-ajax.js', ['jquery'], '1.0', true);
    }
    add_action('wp_enqueue_scripts', 'ewslike_enqueue_scripts');
}

// Crearte submenu.
require plugin_dir_path(__FILE__) . 'includes/menu_page.php';

// Crearte Table for our plugin.
require plugin_dir_path(__FILE__) . 'includes/db_settings.php';

register_activation_hook(__FILE__, 'ewslike_plugin_table');

// Create Like & Dislike Buttons using filter.
function ews_like_dislike_buttons($content)
{
    $btn_container = '<div class="like-wrap">';
    $btn_container_end = '</div>';
    $like_btn = '<a href="javascript:;" class="ews-btn ews-like-btn"><span class="gicon-thumb-up"></span> Like</a>';
    $dislike_btn = '<a href="javascript:;" class="ews-btn ews-dislike-btn"><span class="gicon-thumb-down"></span> Dislike</a>';
    $content .= $btn_container;
    $content .= $like_btn;
    $content .= $dislike_btn;
    $content .= $btn_container_end;

    return $content;
}
add_action('the_content', 'ews_like_dislike_buttons');
