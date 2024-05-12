<?php

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
