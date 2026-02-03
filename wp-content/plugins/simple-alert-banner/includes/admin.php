<?php
if(!defined('ABSPATH')) exit;

function sab_admin_menu() {
    add_menu_page(
        'Alert Banner Settings',
        'Alert Banner',
        'manage_options',
        'simple-alert-banner',
        'sab_admin_page',
        'dashicons-megaphone',
        25.5
    );
}

function sab_admin_page() {
    if (isset($_POST['sab_save'])) {
        check_admin_referer('sab_save_settings');
        
        update_option('sab_message', sanitize_textarea_field($_POST['sab_message']));
        update_option('sab_url', esc_url_raw($_POST['sab_url']));
        update_option('sab_bg_color', sanitize_hex_color($_POST['sab_bg_color']));
        update_option('sab_text_color', sanitize_hex_color($_POST['sab_text_color']));
        update_option('sab_active', isset($_POST['sab_active']) ? 1 : 0);
        update_option('sab_show_mobile', isset($_POST['sab_show_mobile']) ? 1 : 0);
        update_option('sab_show_desktop', isset($_POST['sab_show_desktop']) ? 1 : 0);
        
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $message = get_option('sab_message', 'This is an alert message!');
    $url = get_option('sab_url', '');
    $bg_color = get_option('sab_bg_color', '#C93B2B');
    $text_color = get_option('sab_text_color', '#FFFFFF');
    $active = get_option('sab_active', 1);
    $show_mobile = get_option('sab_show_mobile', 1);
    $show_desktop = get_option('sab_show_desktop', 1);
    ?>
    <div class="wrap">
        <h1>Simple Alert Banner Settings</h1>
        <style>
        .sab-toggle {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .sab-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .sab-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .sab-toggle-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        .sab-toggle input:checked + .sab-toggle-slider {
            background-color: #2271b1;
        }
        .sab-toggle input:checked + .sab-toggle-slider:before {
            transform: translateX(26px);
        }
        .sab-toggle-label {
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            color: #2271b1;
            vertical-align: middle;
        }
        .sab-toggle input:checked ~ .sab-toggle-label {
            color: #2271b1;
        }
        .sab-toggle input:not(:checked) ~ .sab-toggle-label {
            color: #666;
        }
        </style>
        <form method="post" action="">
            <?php wp_nonce_field('sab_save_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label>Status</label>
                    </th>
                    <td>
                        <label class="sab-toggle">
                            <input type="checkbox" id="sab_active" name="sab_active" value="1" <?php checked($active, 1); ?> onchange="this.nextElementSibling.nextElementSibling.textContent = this.checked ? 'ON' : 'OFF'; this.nextElementSibling.nextElementSibling.style.color = this.checked ? '#2271b1' : '#666';">
                            <span class="sab-toggle-slider"></span>
                            <span class="sab-toggle-label"><?php echo $active ? 'ON' : 'OFF'; ?></span>
                        </label>
                        <p class="description">Toggle to activate or deactivate the banner</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sab_message">Message</label>
                    </th>
                    <td>
                        <textarea id="sab_message" name="sab_message" rows="3" style="width:100%;max-width:500px;"><?php echo esc_textarea($message); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sab_url">URL (Optional)</label>
                    </th>
                    <td>
                        <input type="url" id="sab_url" name="sab_url" value="<?php echo esc_url($url); ?>" style="width:100%;max-width:500px;" placeholder="https://example.com">
                        <p class="description">If provided, the message will become a clickable link</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sab_bg_color">Background Color</label>
                    </th>
                    <td>
                        <input type="color" id="sab_bg_color" name="sab_bg_color" value="<?php echo esc_attr($bg_color); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sab_text_color">Text Color</label>
                    </th>
                    <td>
                        <input type="color" id="sab_text_color" name="sab_text_color" value="<?php echo esc_attr($text_color); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>Display Options</label>
                    </th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="sab_show_mobile" value="1" <?php checked($show_mobile, 1); ?>>
                                Show on Mobile Devices
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="sab_show_desktop" value="1" <?php checked($show_desktop, 1); ?>>
                                Show on Desktop/Tablet
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>Shortcode</label>
                    </th>
                    <td>
                        <p>Use this shortcode to display the banner anywhere on your site:</p>
                        <code style="background:#f0f0f0;padding:5px 10px;display:inline-block;border:1px solid #ddd;">[simple_alert_banner]</code>
                        <p class="description">You can place this shortcode in pages, posts, widgets, or theme templates.</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Save Settings', 'primary', 'sab_save'); ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'sab_admin_menu');
