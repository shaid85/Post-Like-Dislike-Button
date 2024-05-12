<?php

// Create Like & Dislike Buttons using filter.

function ews_like_dislike_buttons($content)
{
    $ews_like_label = get_option('ews_like_btn_label', 'Like');
    $ews_dislike_label = get_option('ews_dislike_btn_label', 'Dislike');

    $user_id = get_current_user_id();
    $post_id = get_the_ID();

    $btn_container = '<div class="like-wrap">';
    $btn_container_end = '</div>';
    $like_btn = '<a href="javascript:;" onclick="ews_like_ajax(' . $user_id . ',' . $post_id . ')" class="ews-btn ews-like-btn"><span class="gicon-thumb-up"></span> ' . $ews_like_label . '</a>';
    $dislike_btn = '<a href="javascript:;" onclick="ews_dislike_ajax(' . $user_id . ',' . $post_id . ')" class="ews-btn ews-dislike-btn"><span class="gicon-thumb-down"></span> ' . $ews_dislike_label . '</a>';

    $ews_ajax_response = '<div id="ewsAjaxRes" class="ews-response"><span></span></div>';

    $content .= $btn_container;
    $content .= $like_btn;
    $content .= $dislike_btn;
    $content .= $btn_container_end;
    $content .= $ews_ajax_response;

    return $content;
}
add_action('the_content', 'ews_like_dislike_buttons');
