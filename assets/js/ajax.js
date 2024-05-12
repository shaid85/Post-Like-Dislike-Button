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
        }
    })


}