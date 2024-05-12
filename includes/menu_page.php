<?php

function ews_like_submenu()
{
    add_options_page('Ews like button', 'Ews like button', 'manage_options', 'ews-like-button', 'ews_like_button_page_html');
}
add_action('admin_menu', 'ews_like_submenu');

function ews_like_button_page_html()
{
    if (!is_admin()) {
        return;
    }
?>

    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('ews_settings');
            do_settings_sections('ews_settings');
            submit_button('Save Changes');
            ?>
        </form>
    </div>
<?php
}

function ews_plugin_settings()
{
    register_setting('ews_settings', 'ews_like_btn_label');
    register_setting('ews_settings', 'ews_dislike_btn_label');

    add_settings_section('ews_label_settings_section', 'Ews button labels', 'ews_plugin_settings_section_cb', 'ews_settings');

    add_settings_field('ews_like_label_field', 'Like Button Label', 'ews_lilke_label_field_cb', 'ews_settings', 'ews_label_settings_section');

    add_settings_field('ews_dislike_label_field', 'Dislike Button Label', 'ews_dislilke_label_field_cb', 'ews_settings', 'ews_label_settings_section');
}
add_action('admin_init',  'ews_plugin_settings');

function ews_plugin_settings_section_cb()
{
    echo '<p>Define Button Labels</>';
}

function ews_lilke_label_field_cb()
{
    $setting = get_option('ews_like_btn_label');
?>
    <input type="text" name="ews_like_btn_label" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}

function ews_dislilke_label_field_cb()
{
    $setting = get_option('ews_dislike_btn_label');
?>
    <input type="text" name="ews_dislike_btn_label" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}
