<?php
/**
 * Plugin Name: Divi Modal Popup
 * Plugin URI:  https://diviextended.com/product/divi-modal-popup/
 * Description: Divi Modal Popup plugin allows you to create popups & lightboxes for videos, images, text, & more with multiple trigger types.
 * Version:     1.0.10
 * Author:      Elicus
 * Author URI:  https://elicus.com/
 * Update URI:  https://elegantthemes.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: divi-modal-popup
 * Domain Path: /languages
**/

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'ELICUS_DIVI_MODAL_POPUP_VERSION', '1.0.10' );
define( 'ELICUS_DIVI_MODAL_POPUP_OPTION', 'el-divi-modal-popup' );
define( 'ELICUS_DIVI_MODAL_POPUP_BASENAME', plugin_basename( __FILE__ ) );

if ( ! function_exists( 'el_dmp_initialize_extension' ) ) {
	/**
	 * Creates the extension's main class instance.
	 *
	 * @since 1.0.0
	 */
	function el_dmp_initialize_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/DiviModalPopup.php';
	}
	add_action( 'divi_extensions_init', 'el_dmp_initialize_extension' );
}