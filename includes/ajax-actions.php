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

        $dislike_check = $wpdb->get_var("SELECT id FROM $table_name WHERE user_id = $user_id AND post_id=$post_id AND dislike_count=1");

        if ($dislike_check > 0) {
            // Dislike to Like update
            $updated = $wpdb->update(
                $table_name,
                array(
                    'post_id' => $_POST['pid'],
                    'user_id' => $_POST['uid'],
                    'like_count' => 1,
                    'dislike_count' => 0
                ),
                array(
                    'id' => $dislike_check
                )

            );
            if (false === $updated) {
                echo "failed";
            } else {
                echo "Thank you for change your mind!";
            }
        } else {
            $like_check = $wpdb->get_var("SELECT id FROM $table_name WHERE user_id = $user_id AND post_id=$post_id AND like_count=1");
            if ($like_check > 0) {
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
    }
    wp_die();
}
add_action('wp_ajax_ews_like_ajax_action', 'ews_like_ajax_action');
add_action('wp_ajax_nopriv_ews_like_ajax_action', 'ews_like_ajax_action');


// Plugin Ajax Function
function ews_dislike_ajax_action()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_name = $wpdb->prefix . 'like_system';
    if (isset($_POST['pid']) && isset($_POST['uid'])) {
        $user_id = $_POST['uid'];
        $post_id = $_POST['pid'];

        $like_check = $wpdb->get_var("SELECT id FROM $table_name WHERE user_id = $user_id AND post_id=$post_id AND like_count=1");

        if ($like_check > 0) {
            // Like to Dislike update
            $updated = $wpdb->update(
                $table_name,
                array(
                    'post_id' => $_POST['pid'],
                    'user_id' => $_POST['uid'],
                    'like_count' => 0,
                    'dislike_count' => 1
                ),
                array(
                    'id' => $like_check
                )

            );
            if (false === $updated) {
                echo "failed";
            } else {
                echo "Sorry for bad experience.";
            }
        } else {
            $dislike_check = $wpdb->get_var("SELECT id FROM $table_name WHERE user_id = $user_id AND post_id=$post_id AND dislike_count=1");
            if ($dislike_check > 0) {
                echo "Sorry, you already disliked this post!";
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'post_id' => $_POST['pid'],
                        'user_id' => $_POST['uid'],
                        'dislike_count' => 1
                    ),
                    array(
                        '%d',
                        '%d',
                        '%d'
                    )
                );
                if ($wpdb->insert_id) {
                    echo "Thank you for your reaction!";
                }
            }
        }
    }
    wp_die();
}
add_action('wp_ajax_ews_dislike_ajax_action', 'ews_dislike_ajax_action');
add_action('wp_ajax_nopriv_ews_dislike_ajax_action', 'ews_dislike_ajax_action');




function ews_show_like_count($content)
{
    if (is_page()) {
        return $content;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'like_system';
    $user_id = get_current_user_id();
    $post_id = get_the_ID();
    $like_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id=$post_id AND like_count=1");
    $dislike_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id=$post_id AND dislike_count=1");

    $result_like = '<div class="tottal-like">This post has been liked <strong>' . $like_count . '</strong>, time(s) and dislike: ' . $dislike_count . '</div>';

    $content .= $result_like;

    return $content;
}
add_filter('the_content', 'ews_show_like_count');
