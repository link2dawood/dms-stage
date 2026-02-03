<?php
if(!defined('ABSPATH')) exit;

function sab_scripts() {
    wp_enqueue_style('sab_css', plugin_dir_url(__FILE__) . '../assets/style.css');
    wp_enqueue_script('sab_js', plugin_dir_url(__FILE__) . '../assets/script.js', [], false, true);
}

add_action('wp_enqueue_scripts', 'sab_scripts');
