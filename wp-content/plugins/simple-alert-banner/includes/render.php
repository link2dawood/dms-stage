<?php
if(!defined('ABSPATH')) exit;

function sab_get_banner_html($force_show = false) {
    if (!$force_show) {
        if (isset($_COOKIE['sab_closed'])) return '';
        
        $active = get_option('sab_active', 1);
        if (!$active) return '';
    }

    // Check device display options
    $show_mobile = get_option('sab_show_mobile', 1);
    $show_desktop = get_option('sab_show_desktop', 1);
    
    $is_mobile = wp_is_mobile();
    
    if ($is_mobile && !$show_mobile) return '';
    if (!$is_mobile && !$show_desktop) return '';

    $message = get_option('sab_message', 'This is an alert message!');
    $url = get_option('sab_url', '');
    $bg = get_option('sab_bg_color', '#C93B2B');
    $color = get_option('sab_text_color', '#FFFFFF');
    
    $device_class = $is_mobile ? 'sab-mobile' : 'sab-desktop';
    
    // If URL is provided, make message a link
    $message_html = $message;
    if (!empty($url)) {
        $message_html = '<a href="' . esc_url($url) . '" style="color:' . esc_attr($color) . ';text-decoration:underline;">' . esc_html($message) . '</a>';
    } else {
        $message_html = esc_html($message);
    }

    return '<div class="sab-banner ' . esc_attr($device_class) . '" style="background:' . esc_attr($bg) . ';color:' . esc_attr($color) . ';">
            <span class="sab-message">' . $message_html . '</span>
            <span class="sab-close" aria-label="Close">&times;</span>
          </div>';
}

function sab_render_banner() {
    static $once = false;
    if ($once) return;
    $once = true;
    
    $banner = sab_get_banner_html();
    if ($banner) echo $banner;
}

// Shortcode function
function sab_shortcode($atts) {
    return sab_get_banner_html(true);
}
add_shortcode('simple_alert_banner', 'sab_shortcode');

// Auto-display in body (if enabled)
add_action('wp_body_open', 'sab_render_banner');
