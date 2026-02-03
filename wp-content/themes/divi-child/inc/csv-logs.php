<?php

/**
 * Displays importer plugin CSV file logs in the backend
 */

defined( 'ABSPATH' ) || die();

function csv_file_log_menu() {
    add_menu_page(
        'CSV File Log',                  // Page title
        'CSV File Log',                  // Menu title
        'manage_options',                // Capability
        'csv-file-log',                  // Menu slug
        'csv_file_callback_function',    // Callback function
        'dashicons-media-spreadsheet',   // Icon
        5                                // Position
    );
}
add_action('admin_menu', 'csv_file_log_menu');

function csv_file_callback_function() {
    $dir = ABSPATH . 'wp-content/uploads/backups';

    // Check if directory exists
    if (!is_dir($dir)) {
        echo '<div style="padding: 30px;"><strong>Directory does not exist.</strong></div>';
        return;
    }

    // Get files in directory
    $files = scandir($dir);

    echo '<div class="csv-file-log" style="padding: 30px;">';
    echo '<ul style="list-style: disc;">';

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $file_url = site_url('/wp-content/uploads/backups/' . esc_attr($file));
            echo '<li style="margin-bottom: 15px;">';
            echo '<a download href="' . esc_url($file_url) . '">' . esc_html($file) . '</a>';
            echo '</li>';
        }
    }

    echo '</ul>';
    echo '</div>';
}