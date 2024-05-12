function ews_like_ajax(userId, postId) {

    var user_id = userId;
    var post_id = postId;


    jQuery.ajax({
        url: ews_ajax_obj.ajax_url,
        type: 'post',
        data: {
            action: 'ews_like_ajax_action',
            pid: post_id,
            uid: user_id
        },
        success: function (response) {
            jQuery('#ewsAjaxRes span').html(response)
            jQuery('.ews-dislike-btn').removeClass('active')
            jQuery('.ews-like-btn').addClass('active')
        }
    })


}
function ews_dislike_ajax(userId, postId) {

    var user_id = userId;
    var post_id = postId;


    jQuery.ajax({
        url: ews_ajax_obj.ajax_url,
        type: 'post',
        data: {
            action: 'ews_dislike_ajax_action',
            pid: post_id,
            uid: user_id
        },
        success: function (response) {
            jQuery('#ewsAjaxRes span').html(response)
            jQuery('.ews-like-btn').removeClass('active')
            jQuery('.ews-dislike-btn').addClass('active')
        }
    })


}