<?php

namespace Brain_Blog\Includes;

defined('ABSPATH') || die();

class AssetsManager {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        if (isset($_GET['et_fb']) && '1' === $_GET['et_fb']) { // phpcs:ignore
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts_vb']);
        }

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts_vb']);
    }

    public function enqueue_scripts() {

        wp_register_script(
            'brbl-slick',
            BRAIN_BLOG_ASSETS . 'vendor/slick/slick.min.js',
            ['jquery'],
            BRAIN_BLOG_VERSION,
            true
        );

        wp_register_script(
            'brbl-flex-menu',
            BRAIN_BLOG_ASSETS . 'js/flex-menu.min.js',
            ['jquery'],
            BRAIN_BLOG_VERSION,
            true
        );


        wp_register_script(
            'brbl-imagesloaded',
            BRAIN_BLOG_ASSETS . 'js/imagesloaded.pkgd.min.js',
            ['jquery'],
            BRAIN_BLOG_VERSION,
            true
        );

        wp_register_script(
            'brbl-isotop',
            BRAIN_BLOG_ASSETS . 'vendor/isotope.pkgd.min.js',
            ['jquery', 'brbl-imagesloaded'],
            BRAIN_BLOG_VERSION,
            true
        );


        wp_register_script(
            'brbl-infinite-scroll',
            BRAIN_BLOG_ASSETS . 'vendor/infinite-scroll.pkgd.min.js',
            ['jquery', 'brbl-imagesloaded'],
            BRAIN_BLOG_VERSION,
            true
        );

        wp_register_style(
            'brbl-slick',
            BRAIN_BLOG_ASSETS . 'vendor/slick/slick.min.css',
            null,
            BRAIN_BLOG_VERSION
        );

        wp_localize_script(
            'jquery',
            'brbl_plugin',
            [
                'nonce'          => wp_create_nonce('brain_blog_nonce'),
                'ajaxurl'        => admin_url('admin-ajax.php'),
                'not_found_text' => esc_html__('Not Found!', 'brain-divi-blog'),
            ]
        );
    }

    public function enqueue_scripts_vb() {
        wp_enqueue_style(
            'brbl-vb',
            BRAIN_BLOG_ASSETS . 'admin/vb.min.css',
            null,
            BRAIN_BLOG_VERSION
        );

        wp_enqueue_script(
            'brbl-slick'
        );

        wp_enqueue_style(
            'brbl-slick'
        );
    }
}
