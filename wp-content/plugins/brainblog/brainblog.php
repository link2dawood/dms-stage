<?php

/**
 * Plugin Name: Divi Blog Pro
 * Plugin URI:  https://divipeople.com
 * Description: Divi Blog Pro plugin allows customizing blog pages and enriching them with attractive modules.
 * Version:     1.2.24
 * Author:      DiviPeople
 * Author URI:  https://divipeople.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: brain-divi-blog
 * Domain Path: /languages
 *
 * Divi Blog Pro is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Divi Blog Pro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Divi Blog Pro. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * @package brainblog
 */

defined('ABSPATH') || die();

define('BRAIN_BLOG_VERSION', '1.2.24');
define('BRAIN_BLOG_DIR', plugin_dir_path(__FILE__));
define('BRAIN_BLOG_DIR_URL', plugin_dir_url(__FILE__));
define('BRAIN_BLOG_ASSETS', trailingslashit(BRAIN_BLOG_DIR_URL . 'assets'));
define('BRAIN_BLOG_FILE', __FILE__);
define('BRAIN_BLOG_PLUGIN_BASE', plugin_basename(__FILE__));

// phpcs:disable

#ET_START_REPLACE #ET_END_REPLACE

// phpcs:enable

if (!class_exists('BRAIN_BLOG_PLUGIN')) :

    final class BRAIN_BLOG_PLUGIN
    {
        private static $instance;

        private function __construct()
        {
            register_activation_hook(BRAIN_BLOG_FILE, [$this, 'activate']);
            add_action('plugins_loaded', [$this, 'init_plugin']);
        }

        public function init_plugin()
        {
            new Brain_Blog\Includes\AssetsManager();
        }

        public function activate()
        {
            update_option('brain_blog_version', BRAIN_BLOG_VERSION);
        }

        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof BRAIN_BLOG_PLUGIN)) {
                self::$instance = new BRAIN_BLOG_PLUGIN();
                self::$instance->init();
                self::$instance->includes();
            }

            return self::$instance;
        }

        private function init()
        {
            add_action('divi_extensions_init', [$this, 'initialize_extension']);
        }

        private function includes()
        {
            require_once BRAIN_BLOG_DIR . 'includes/assets-manager.php';
            require_once BRAIN_BLOG_DIR . 'includes/functions.php';
            require_once BRAIN_BLOG_DIR . 'includes/traits/smart-post-list.php';
            require_once BRAIN_BLOG_DIR . 'includes/modules/ModulesCore/PostHelper.php';
            require_once BRAIN_BLOG_DIR . 'includes/post-handler.php';
        }

        public function initialize_extension()
        {
            require_once BRAIN_BLOG_DIR . 'includes/divi-extension.php';
        }
    }

endif;

function brbl_init_plugin()
{
    return BRAIN_BLOG_PLUGIN::instance();
}

brbl_init_plugin();
