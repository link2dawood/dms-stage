<?php
if(!class_exists("Listing_Template")) {

	/**
	 * Listing Class
	 */
	class Listing_Template {

		public function locate_template($template_file, $parameters = array()) {
			if ( ! empty( $parameters ) ) {
				extract( $parameters );
			}

			$current_template = (function_exists("automotive_get_current_template") ? automotive_get_current_template() : false);

			ob_start();

			if ( file_exists( get_stylesheet_directory() . "/auto_templates/" . $template_file . ".php" ) ) { // check for child theme template file
				include( get_stylesheet_directory() . "/auto_templates/" . $template_file . ".php" );

			} elseif ( file_exists( get_template_directory() . "/auto_templates/" . $template_file . ".php" ) ) { // check for theme template file
				include( get_template_directory() . "/auto_templates/" . $template_file . ".php" );

			} elseif ( $current_template && $current_template['id'] !== 'default' && file_exists( $current_template['path'] . 'templates/plugin/auto_templates/' . $template_file . '.php' ) ){
				include(  $current_template['path'] . 'templates/plugin/auto_templates/' . $template_file . '.php' );

			} elseif ( file_exists( LISTING_HOME . "auto_templates/" . $template_file . ".php" ) ) { // include default template
				include( LISTING_HOME . "auto_templates/" . $template_file . ".php" );

			} else {
				echo "Your Automotive Listings plugin is missing the " . sanitize_text_field($template_file) . " file template, please upload a fresh copy of the plugin to fix this.<br>\n";
			}

			$output = ob_get_clean();

			return $output;
		}

	}
}

$Automotive_Plugin_Listings_Template = null;
if( ! function_exists('Automotive_Plugin_Template') ){
	function Automotive_Plugin_Template(){
		global $Automotive_Plugin_Listings_Template;

		if($Automotive_Plugin_Listings_Template == null){
			$Automotive_Plugin_Listings_Template = new Listing_Template();
		}

		return $Automotive_Plugin_Listings_Template;
	}
}
