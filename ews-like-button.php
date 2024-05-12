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
        wp_enqueue_script('ews-like', EWSLIKE_PLUGIN_DIR . 'assets/js/main.js', ['jquery'], '1.0', true);
        wp_enqueue_script('ews-ajax', EWSLIKE_PLUGIN_DIR . 'assets/js/ajax.js', ['jquery'], '1.0', true);

        wp_localize_script('ews-ajax', 'ews_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
    add_action('wp_enqueue_scripts', 'ewslike_enqueue_scripts');
}

// Crearte submenu.
require plugin_dir_path(__FILE__) . 'includes/menu_page.php';

// Crearte Table for our plugin.
require plugin_dir_path(__FILE__) . 'includes/db_settings.php';
// Actication hook
register_activation_hook(__FILE__, 'ewslike_plugin_table');

// Create Like & Dislike Buttons using filter.
require plugin_dir_path(__FILE__) . 'includes/frontend.php';


// Plugin Ajax Function
function ews_like_ajax_action()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_name = $wpdb->prefix . 'like_system';
    if (isset($_POST['pid']) && isset($_POST['uid'])) {
        $user_id = $_POST['uid'];
        $post_id = $_POST['pid'];
        $check_user = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND post_id = $post_id");
        if ($check_user > 0) {
            echo "Sorry, you already liked this post!";
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'post_id' => $_POST['pid'],
                    'user_id' => $_POST['uid'],
                    'like_count' => 1
                ),
                array(
                    '%d',
                    '%d',
                    '%d'
                )
            );
            if ($wpdb->insert_id) {
                echo "Thank you for like";
            }
        }
    }
    wp_die();
}
add_action('wp_ajax_ews_like_ajax_action', 'ews_like_ajax_action');
add_action('wp_ajax_nopriv_ews_like_ajax_action', 'ews_like_ajax_action');

function ews_show_like_count($content)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'like_system';
    $post_id = get_the_ID();
    $like_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id=$post_id AND like_count=1");

    $result_like = '<div class="tottal-like">This post has been liked <strong>' . $like_count . '</strong>, time(s)</div>';

    $content .= $result_like;

    return $content;
}
add_action('the_content', 'ews_show_like_count');
