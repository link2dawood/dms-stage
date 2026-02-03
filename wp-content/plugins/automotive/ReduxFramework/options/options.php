<?php
if ( ! class_exists( "ReduxFramework" ) ) {
	return;
}

if ( ! class_exists( "Redux_Framework_automotive_wp" ) ) {
	class Redux_Framework_automotive_wp {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {
			// This is needed. Bah WordPress bugs.  ;)
			if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
				// $this->initSettings();
			} else {
				add_action( 'admin_init', array( $this, 'loadConfig' ), 10 );
			}

			$this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
		}

		public function loadConfig() {
			global $Listing;

			$theme_options       = get_option( "automotive_wp" );
			$footer_widget_areas = ( isset( $theme_options['footer_widget_spots'] ) && ! empty( $theme_options['footer_widget_spots'] ) ? $theme_options['footer_widget_spots'] : "" );
			$footer_areas        = array();

			$footer_areas['default-footer'] = __( "Default Footer", "listings" );
			$footer_areas['no-footer']      = __( "No Footer", "listings" );

			if ( ! empty( $footer_widget_areas ) ) {
				foreach ( $footer_widget_areas as $area ) {
					$footer_areas[ $area ] = $area;
				}
			}

			// Get Revolution Sliders
			$rev_sliders         = array();
			$rev_sliders['none'] = __( "No Slideshow", "listings" );

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'revslider/revslider.php' ) ) {
				global $wpdb;

				$rev_sliders_query = $wpdb->get_results( "SELECT title, alias FROM " . $wpdb->prefix . "revslider_sliders" );

				if ( ! empty( $rev_sliders_query ) ) {
					foreach ( $rev_sliders_query as $slider ) {
						$rev_sliders[ $slider->alias ] = stripslashes( $slider->title );
					}
				}
			}

			// load all variables that can be used in email forms
			$listing_categories = $Listing->get_listing_categories();
			$email_variables    = '{requestdate}';

			if ( ! empty( $listing_categories ) ) {
				foreach ( $listing_categories as $slug => $category ) {
					$email_variables .= ', {' . $slug . '}';
				}
			}

			$sections = array(
				0   => array(
					'title'  => __( 'Automotive Settings', 'listings' ),
					'icon'   => 'fa fa-car',
					'fields' => array(
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( '"Vehicle" term', 'listings' ),
							'subtitle' => 'Change all instances of the term "Vehicle"',
							'indent'   => true
						),
						array(
							'id'      => 'vehicle_singular_form',
							'type'    => 'text',
							'title'   => __( 'Singular Form', 'listings' ),
							'default' => 'Vehicle',
						),
						array(
							'id'      => 'vehicle_plural_form',
							'type'    => 'text',
							'title'   => __( 'Plural Form', 'listings' ),
							'default' => 'Vehicles',
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'title'   => __( "Hotlink Gallery Images", "listings" ),
							'desc'    => __( 'If enabled this will allow you to hotlink images in the backend and in file imports. Please note you will lose the gallery images on existing listings.<br><br>Using hotlinks requires the website you are hotlinking from to have the appropriate Cross-Origin Resource Sharing header for some elements to function properly.', 'listings' ),
							'id'      => 'hotlink',
							'type'    => 'switch',
							'default' => '0',
						),

						array(
							'id'     => 'vhr-section-start',
							'type'   => 'section',
							'title'  => __( 'Vehicle History Report', 'listings' ),
							'indent' => true
						),
						array(
							'desc'    => __( 'Name of service used for vehicle history reports', 'listings' ),
							'id'      => 'vehicle_history_label',
							'type'    => 'text',
							'title'   => __( 'Vehicle History Report', 'listings' ),
							'default' => 'Carfax',
						),
						array(
							'desc' => __( 'Logo used for vehicle history reports', 'listings' ),
							'id'   => 'vehicle_history',
							'type' => 'media',
							'url'  => true,
						),
						array(
							'desc'    => __( 'Enable this option to have new listings show vehicle history image by default.', 'listings' ),
							'id'      => 'default_vehicle_history',
							'type'    => 'checkbox',
							'options' => array( "on" => "Checked by default" )
						),
						array(
							'desc'    => __( 'Show or hide the Vehicle History Report on the single listing page', 'listings' ),
							'id'      => 'show_vehicle_history_inventory',
							'type'    => 'switch',
							'title'   => __( 'Vehicle History Report on Single Listing Page', 'listings' ),
							'on'      => 'Show',
							'off'     => 'Hide',
							'default' => false
						),
						array(
							'desc'       => __( 'Link the VIN to the carfax (or which ever service you use), where you want the variable to go use {vin}<br>e.g. http://www.carfax.com/cfm/ccc_DisplayHistoryRpt.cfm?partner=WDB_0&vin={vin}', 'listings' ),
							'id'         => 'carfax_linker',
							'type'       => 'carfax_linker',
							'title'      => __( "Vehicle History Report Link", "listings" ),
							'categories' => $Listing->get_listing_categories(),
							'required'   => array( 'autocheck_report', '=', false )
						),


						array(
							'id'      => 'cargurus_badge',
							'type'    => 'switch',
							'title'   => __( 'CarGurus Badge', 'listings' ),
							'desc'    => 'More info here: https://www.cargurus.com/Cars/webhosts/docs/DealRatingBadge.html',
							'on'      => __( 'Enabled', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'default' => false
						),
						array(
							'id'       => 'cargurus_vin_category',
							'type'     => 'select',
							'title'    => __( 'Choose VIN Category', 'listings' ),
							'options'  => $Listing->get_listing_categories_to_redux_select(),
							'required' => array( 'cargurus_badge', '=', true )
						),
						array(
							'id'       => 'cargurus_price_category',
							'type'     => 'select',
							'title'    => __( 'Choose Price Category', 'listings' ),
							'options'  => $Listing->get_listing_categories_to_redux_select(),
							'required' => array( 'cargurus_badge', '=', true )
						),
						array(
							'id'       => 'cargurus_style',
							'type'     => 'select',
							'title'    => __( 'Style', 'listings' ),
							'options'  => array(
								'STYLE1' => __( 'Style 1', 'listings' ),
								'STYLE2' => __( 'Style 2', 'listings' ),
							),
							'default'  => 'STYLE1',
							'required' => array( 'cargurus_badge', '=', true )
						),
						array(
							'id'       => 'cargurus_min_rating',
							'type'     => 'select',
							'title'    => __( 'Minimum Rating', 'listings' ),
							'options'  => array(
								'GREAT_PRICE' => __( 'Great Deal', 'listings' ),
								'GOOD_PRICE'  => __( 'Good Deal', 'listings' ),
								'FAIR_PRICE'  => __( 'Fair Deal', 'listings' ),
							),
							'default'  => 'GOOD_PRICE',
							'required' => array( 'cargurus_badge', '=', true )
						),
						array(
							'id'       => 'cargurus_inventory_page',
							'type'     => 'switch',
							'title'    => __( 'CarGurus Badge on Inventory Page', 'listings' ),
							'on'       => __( 'Enabled', 'listings' ),
							'off'      => __( 'Disabled', 'listings' ),
							'default'  => false,
							'required' => array( 'cargurus_badge', '=', true )
						),
						array(
							'id'       => 'cargurus_single_listing_page',
							'type'     => 'switch',
							'title'    => __( 'CarGurus Badge on Single Listing Page', 'listings' ),
							'on'       => __( 'Enabled', 'listings' ),
							'off'      => __( 'Disabled', 'listings' ),
							'default'  => false,
							'required' => array( 'cargurus_badge', '=', true )
						),


						array(
							'id'      => 'autocheck_report',
							'type'    => 'switch',
							'title'   => __( 'AutoCheck Report', 'listings' ),
							'on'      => __( 'Enabled', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'default' => false
						),
						array(
							'id'       => 'autocheck_vin_category',
							'type'     => 'select',
							'title'    => __( 'Choose VIN Category', 'listings' ),
							'options'  => $Listing->get_listing_categories_to_redux_select(),
							'required' => array( 'autocheck_report', '=', true )
						),
						array(
							'id'       => 'autocheck_page',
							'type'     => 'select',
							'title'    => __( 'AutoCheck Page', 'listings' ),
							'desc'     => __( 'Select the page using the \'AutoCheck\' page template.', 'listings' ),
							'data'     => 'pages',
							'required' => array( 'autocheck_report', '=', true )
						),
						array(
							'id'       => 'autocheck_cid',
							'title'    => __( 'AutoCheck Customer ID', 'listings' ),
							'desc'     => __( '8 characters, alphanumeric', 'listings' ),
							'type'     => 'text',
							'default'  => '',
							'required' => array( 'autocheck_report', '=', true )
						),
						array(
							'id'       => 'autocheck_pwd',
							'title'    => __( 'AutoCheck Password', 'listings' ),
							'desc'     => __( '8 characters, alphanumeric', 'listings' ),
							'type'     => 'text',
							'default'  => '',
							'required' => array( 'autocheck_report', '=', true )
						),
						array(
							'id'       => 'autocheck_sid',
							'title'    => __( 'AutoCheck Customer Secondary ID', 'listings' ),
							'desc'     => __( 'i.e. Dealer Code', 'listings' ),
							'type'     => 'text',
							'default'  => '',
							'required' => array( 'autocheck_report', '=', true )
						),
						array(
							'id'       => 'autocheck_lang',
							'type'     => 'select',
							'title'    => __( 'Language', 'listings' ),
							'options'  => array(
								'EN' => __( 'English', 'listings' ),
								'ES' => __( 'Spanish', 'listings' ),
							),
							'default'  => 'EN',
							'required' => array( 'autocheck_report', '=', true )
						),

						array(
							'id'      => 'carfax_badge_api',
							'type'    => 'switch',
							'title'   => __( 'CARFAX Canada Badge API', 'listings' ),
							'desc'    => __( "Please contact the dealership or apisupport@carfax.ca for their 5-digit CARFAX Canada account number. You will need to provide the full dealership name, address, and dealership contact email.", "listings" ),
							'on'      => __( 'Enabled', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'default' => false
						),
						array(
							'id'       => 'carfax_badge_api_vin_category',
							'type'     => 'select',
							'title'    => __( 'CARFAX Canada Badge VIN Category', 'listings' ),
							'desc'     => __( 'Select your VIN category to be used in requesting the CARFAX Canada badge report.', 'listings' ),
							'options'  => $Listing->get_listing_categories_to_redux_select(),
							'required' => array( 'carfax_badge_api', '=', true )
						),
						array(
							'id'       => 'carfax_badge_api_account_id',
							'title'    => __( 'CARFAX Canada Badge Account Number', 'listings' ),
							'desc'     => __( '5 Digit Account Number', 'listings' ),
							'type'     => 'text',
							'default'  => '',
							'required' => array( 'carfax_badge_api', '=', true )
						),
						array(
							'id'       => 'carfax_badge_api_account_lang',
							'type'     => 'select',
							'title'    => __( 'CARFAX Canada Badge Language', 'listings' ),
							'options'  => array(
								'EN' => __( 'English', 'listings' ),
								'FR' => __( 'French', 'listings' ),
							),
							'default'  => 'EN',
							'required' => array( 'carfax_badge_api', '=', true )
						),
						array(
							'id'       => 'carfax_badge_api_account_token',
							'title'    => __( 'CARFAX Canada Badge Token', 'listings' ),
							'type'     => 'textarea',
							'default'  => '',
							'required' => array( 'carfax_badge_api', '=', true )
						),

						array(
							'id'     => 'vhr-section-end',
							'type'   => 'section',
							'indent' => false,
						),

						array(
							'id'      => 'korean_translation',
							'type'    => 'switch',
							'title'   => __( 'Enable Korean Slugs', 'listings' ),
							'on'      => 'Enabled',
							'off'     => 'Disabled',
							'default' => false
						),
						array(
							'desc'  => __( 'The image used to display when a vehicle doesn\'t have an image', 'listings' ),
							'id'    => 'not_found_image',
							'type'  => 'media',
							'title' => __( 'No Image Found Image', 'listings' )
						),
						array(
							'id'    => 'comparison_page',
							'type'  => 'select',
							'title' => __( 'Comparison Page', 'listings' ),
							'desc'  => __( 'Select the comparison page that will be used', 'listings' ),
							'data'  => 'pages'
						),
						array(
							'id'      => 'car_comparison',
							'title'   => __( 'Comparison functionality', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Enable or disable the comparison functionality', 'listings' ),
							'default' => true,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'      => 'delete_associated',
							'type'    => 'switch',
							'title'   => __( "Delete associated images", "listings" ),
							'desc'    => __( 'When deleting a listing also delete the image associated with it', 'listings' ),
							'default' => true,
						),
						array(
							'id'      => 'delete_sold_listings',
							'title'   => __( "Delete Sold Listings", "listings" ),
							'desc'    => __( 'If enabled you will be able to set how many days a listing should remain on the site as sold before deleted.', 'listings' ),
							'type'    => 'switch',
							'default' => '0'
						),
						array(
							'id'       => 'delete_sold_listings_days',
							'title'    => __( 'Delete Sold Listings After X Days', 'listings' ),
							'type'     => 'text',
							'desc'     => __( 'Set how many days a listing should remain on the website before being deleted.', 'listings' ),
							'default'  => '3',
							'required' => array( 'delete_sold_listings', '=', true )
						),
						array(
							'id'      => 'additional_categories',
							'type'    => 'multi_text_auto',
							'title'   => __( 'Additional Categories', 'listings' ),
							'desc'    => __( 'These categories will show up under the search box widget and are on each listing edit page.<br><br> Check the box beside them to make it automatically checked when adding inventory listings.', 'listings' ),
							'default' => array()
						),
						array(
							'id'    => 'custom_badges',
							'type'  => 'custom_badges',
							'title' => __( 'Custom Badges', 'listings' ),
							'desc'  => __( 'Create custom badges with custom colors and easily select them while creating new listings or editing existing listings.', 'listings' ),
						),
						array(
							'id'      => 'sold_attach_badge',
							'title'   => __( 'Automatically Add Sold Badge', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will automatically add the sold badge to cars marked as sold.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'      => 'vehicle_scroller_badge',
							'title'   => __( 'Vehicle Scroller Badges', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will show the badges in the listing scroller.', 'listings' ),
							'default' => true,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'      => 'featured_vehicle_widget',
							'title'   => __( 'Featured Vehicle Scroller', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will add scroller to the bottom of each page, it will also allow you to mark a listing as featured to display it in the slider.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'featured_vehicle_widget_side',
							'title'    => __( 'Featured Vehicle Scroller Side', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'Control which side of the screen the featured vehicle scroller will appear.', 'listings' ),
							'default'  => true,
							'on'       => __( "Left", "listings" ),
							'off'      => __( "Right", "listings" ),
							'required' => array( 'featured_vehicle_widget', 'equals', 1 )
						),
						array(
							'id'       => 'featured_vehicle_widget_text',
							'title'    => __( 'Featured Vehicle Scroller Text', 'listings' ),
							'type'     => 'text',
							'desc'     => __( 'Set the featured vehicle widget button text.', 'listings' ),
							'default'  => 'Check it out',
							'required' => array( 'featured_vehicle_widget', 'equals', 1 )
						),
						array(
							'id'      => 'keyword_search',
							'title'   => __( 'Keyword Search', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Choose whether the keywords in the search box shortcode will search the title or the listing category values (useful for stock numbers or VINs).', 'listings' ),
							'default' => false,
							'on'      => __( "Listing Categories", "listings" ),
							'off'     => __( "Title", "listings" )
						),
						array(
							'id'      => 'show_image_column',
							'title'   => __( 'Image Column', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Enable or disable an image column when viewing the listings in the backend, if enabled it will display the first gallery image of the listing.', 'listings' ),
							'default' => false
						),
						array(
							'id'      => 'hide_sold_price',
							'title'   => __( 'Hide price on sold listings', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Hide the price on sold listings.', 'listings' ),
							'default' => false
						),
						array(
							'title'   => __( "Hide Admin Hints", "listings" ),
							'desc'    => __( 'When turned off this will hide the admin hints that help with using the backend.', 'listings' ),
							'id'      => 'hide_hints',
							'type'    => 'switch',
							'default' => '0',
						),
						array(
							'id'      => 'file-import-permission',
							'type'    => 'select',
							'title'   => __( 'File Import Permission', 'listings' ),
							'desc'    => __( 'Control which users can use the file import functionality.', 'listings' ),
							'options' => $Listing->get_wp_roles(),
							'default' => 'install_plugins',
						),
						array(
							'id'      => 'vin-import-permission',
							'type'    => 'select',
							'title'   => __( 'VIN Import Permission', 'listings' ),
							'desc'    => __( 'Control which users can use the VIN import functionality.', 'listings' ),
							'options' => $Listing->get_wp_roles(),
							'default' => 'install_plugins',
						),
						array(
							'id'      => 'listing-category-permission',
							'type'    => 'select',
							'title'   => __( 'Listing Category Permission', 'listings' ),
							'desc'    => __( 'Control which users can modify the listing categories.', 'listings' ),
							'options' => $Listing->get_wp_roles(),
							'default' => 'install_plugins',
						),
					),
				),
				10  => array(
					'title'  => __( 'Currency Settings', 'listings' ),
					'icon'   => 'fa fa-usd',
					'fields' => array(
						array(
							'desc'  => __( 'Enter in your symbol used for currency', 'listings' ),
							'type'  => 'text',
							'id'    => 'currency_symbol',
							'title' => __( 'Currency Symbol', 'listings' ),
						),
						array(
							'id'    => 'currency_format',
							'type'  => 'text',
							'title' => __( 'Currency Format', 'listings' ),
							'desc'  => __( 'Currency format code associated with the currency symbol (e.g CAD, USD, etc).', 'listings' ),
						),
						array(
							'desc'    => __( 'Change the position of the currency symbol', 'listings' ),
							'type'    => 'switch',
							'id'      => 'currency_placement',
							'title'   => __( 'Currency Symbol Placement', 'listings' ),
							'off'     => __( 'After Value', 'listings' ),
							'on'      => __( 'Before Value', 'listings' ),
							'default' => true
						),
						array(
							'desc'    => __( 'Disable the use of the currency separators', 'listings' ),
							'type'    => 'switch',
							'id'      => 'separators_functionality',
							'title'   => __( 'Disable Currency Separators', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'on'      => __( 'Enabled', 'listings' ),
							'default' => true
						),
						array(
							'desc'     => __( 'Enter in a separator for large currency amounts', 'listings' ),
							'type'     => 'text',
							'id'       => 'currency_separator',
							'title'    => __( 'Thousand Separator', 'listings' ),
							'default'  => ',',
							'required' => array( 'separators_functionality', 'equals', 1 )
						),
						array(
							'desc'     => __( 'Enter in a separator for large currency amounts', 'listings' ),
							'type'     => 'text',
							'id'       => 'currency_separator_decimal',
							'title'    => __( 'Decimal Separator', 'listings' ),
							'default'  => '.',
							'required' => array( 'separators_functionality', 'equals', 1 )
						),
						array(
							'desc'     => __( 'Number of decimals to be displayed after the price', 'listings' ),
							'type'     => 'text',
							'id'       => 'currency_decimals',
							'title'    => __( 'Number of Decimals', 'listings' ),
							'default'  => '0',
//							'validate' => 'numeric',
							'msg'      => __( "Value must be a number", "listings" ),
							'required' => array( 'separators_functionality', 'equals', 1 )
						),
						array(
							'desc'    => __( 'Change the pricing format to the indian number system.', 'listings' ),
							'type'    => 'switch',
							'id'      => 'indian_number_system',
							'title'   => __( 'Indian Numbering System', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'on'      => __( 'Enabled', 'listings' ),
							'default' => false
						),

						array(
							'id'     => 'section-start',
							'type'   => 'section',
							'title'  => __( 'Listing Taxes', 'listings' ),
							'indent' => true
						),
						array(
							'id'      => 'tax_functionality',
							'type'    => 'switch',
							'title'   => __( 'Tax Functionality', 'listings' ),
							'default' => false
						),
						array(
							'id'       => 'tax_amount',
							'type'     => 'text',
							'title'    => __( 'Tax Rate', 'listings' ),
							'desc'     => __( 'The percentage of tax to be added to a price.', 'listings' ),
							'validate' => 'numeric',
							'value'    => '3',
							'msg'      => __( 'Value must be a number', 'listings' ),
							'required' => array( 'tax_functionality', 'equals', 1 )
						),
						array(
							'id'       => 'default_tax',
							'type'     => 'switch',
							'title'    => __( 'Default Prices Entered', 'listings' ),
							'desc'     => __( 'Choose if the prices include tax or exclude tax', 'listings' ),
							'on'       => __( "Include Tax", "listings" ),
							'off'      => __( "Exclude Tax", "listings" ),
							'default'  => false,
							'required' => array( 'tax_functionality', 'equals', 1 )
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),

						array(
							'id'    => 'original_value',
							'title' => __( 'Original Price Prefix', 'listings' ),
							'type'  => 'text',
							'desc'  => __( 'This text gets prefixed to the "Original Price:" if listing is on sale (i.e. Reduced Price, Sale Price)', 'listings' ),
						),
						array(
							'id'    => 'original_value_suffix',
							'title' => __( 'Original Price Suffix', 'listings' ),
							'type'  => 'text',
							'desc'  => __( 'This text gets added as a suffix to the "Original Price:" if listing is on sale (i.e. Price Reduced)', 'listings' ),
						),
						array(
							'id'    => 'sale_value',
							'title' => __( 'Sale Price Prefix', 'listings' ),
							'type'  => 'text',
							'desc'  => __( 'This text gets prefixed to the "Price:" if listing is on sale (i.e. Reduced Price, Sale Price)', 'listings' ),
						),
						array(
							'id'    => 'sale_value_suffix',
							'title' => __( 'Sale Price Suffix', 'listings' ),
							'type'  => 'text',
							'desc'  => __( 'This text gets added as a suffix to the "Price:" if listing is on sale (i.e. Price Reduced)', 'listings' ),
						),

						array(
							'id'      => 'price_text_replacement',
							'title'   => __( 'Replace Price Text', 'listings' ),
							'type'    => 'text',
							'desc'    => __( 'Replace the price text on each listing with custom text. Leave empty to disable.', 'listings' ),
							'default' => ""
						),
						array(
							'id'      => 'price_text_all_listings',
							'type'    => 'switch',
							'desc'    => __( 'If enabled the "Replace Price Text" option will only show only on listings with an empty price field, otherwise it will appear on every single listing (if all of the listings price fields are populated).', 'listings' ),
							'default' => true,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'      => 'tax_label_box',
							'title'   => __( 'Default Tax Label (below the price) on Inventory Page', 'listings' ),
							'type'    => 'text',
							'desc'    => __( 'The text inside the inventory listing box.', 'listings' ),
							'default' => __( 'Plus Sales Tax', 'listings' )
						),
						array(
							'id'      => 'tax_label_page',
							'title'   => __( 'Default Tax Label (below the price) on Single Listing Page', 'listings' ),
							'type'    => 'text',
							'desc'    => __( 'The text on the listing page under the price.', 'listings' ),
							'default' => __( 'Plus Taxes & Licensing', 'listings' )
						),
					),
				),
				20  => array(
					'title'  => __( 'Email Templates', 'listings' ),
					'icon'   => 'fa fa-envelope-o',
					'fields' => array(
						array(
							'desc'    => __( 'Change the default name WordPress uses to send all emails.', 'listings' ),
							'id'      => 'default_email_name',
							'type'    => 'text',
							'title'   => __( 'Name used on sent emails', 'listings' ),
							'default' => 'WordPress',
						),
						array(
							'desc'    => __( 'Change the default email address WordPress uses to send all emails.', 'listings' ),
							'id'      => 'default_email_address',
							'type'    => 'text',
							'title'   => __( 'Email address used on sent emails', 'listings' ),
							'default' => '',
						),

						array(
							'id'      => 'email_html_content_type',
							'type'    => 'switch',
							'title'   => __( 'Enable HTML in emails', 'listings' ),
							'desc'    => __( 'If enabled this will add the HTML content type for outgoing emails on the single listing page.', 'listings' ),
							'default' => true,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'      => 'forms_on_all_pages',
							'type'    => 'switch',
							'title'   => __( 'Remove forms on pages', 'listings' ),
							'desc'    => __( 'If enabled this will remove the form code from all other pages except for the listing pages.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'desc'    => __( 'Display this message when an email is successfully sent.', 'listings' ),
							'id'      => 'email_success',
							'type'    => 'text',
							'title'   => __( 'Email was sent', 'listings' ),
							'default' => __( 'The email was sent.', 'listings' ),
						),
						array(
							'desc'    => __( 'Display this message when an email isn\'t successfully sent.', 'listings' ),
							'id'      => 'email_failure',
							'type'    => 'text',
							'title'   => __( 'Email failed to send', 'listings' ),
							'default' => __( 'The email was not sent.', 'listings' ),
						),
						array(
							'desc'    => __( 'Display this message if the email is being marked by', 'listings' ) . ' <a href=\'http://akismet.com/\' target=\'_blank\'>Akismet.com</a>.',
							'id'      => 'email_spam',
							'type'    => 'text',
							'title'   => __( 'Email is spam', 'listings' ),
							'default' => __( 'The email you are trying to send is considered spam.', 'listings' ),
						),
						array(
							'id'      => 'gdpr_form',
							'type'    => 'switch',
							'title'   => __( 'GDPR Compliance', 'listings' ),
							'desc'    => __( 'If enabled this will display a GDPR checkbox on the listing forms.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'gdpr_form_text',
							'type'     => 'text',
							'title'    => __( 'GDPR Compliance Text', 'listings' ),
							'desc'     => __( 'This message will be displayed next to a checkbox on the listing forms.', 'listings' ),
							'default'  => 'You agree by submitting this form that you are sending us your data',
							'required' => array( 'gdpr_form', 'equals', 1 )
						),
						array(
							'id'   => 'variables',
							'type' => 'info',
							'desc' => sprintf( __( 'The following variables can be used in the below subject and message fields to display information about the current listing: %s', 'listings' ), '<br><br>' . $email_variables )
						),
						array(
							'desc'    => __( 'Edit the subject of the email that is used to tell friends about vehicles. You can use the variable', 'listings' ) . ' {name}',
							'id'      => 'friend_subject',
							'type'    => 'text',
							'title'   => __( 'Email to a Friend', 'listings' ),
							'default' => '{name} ' . __( 'wants you to check this vehicle out', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the layout of the email. HTML is allowed and some variables you can use are', 'listings' ) . ': <br><br> {name}, {table} ' . __( 'and', 'listings' ) . ' {message}',
							'id'      => 'friend_layout',
							'type'    => 'textarea',
							'default' => __( 'I want you check this vehicle out', 'listings' ) . ' {table} Message: {message}',
						),
						array(
							'desc'  => __( 'Change the email that receives the emails', 'listings' ),
							'id'    => 'drive_to',
							'type'  => 'text',
							'title' => __( 'Schedule a Test Drive', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the subject of the email that is used to schedule test drives.', 'listings' ),
							'id'      => 'drive_subject',
							'type'    => 'text',
							'default' => __( 'Scheduled Test Drive Request', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the layout of the email. HTML is allowed and some variables you can use are', 'listings' ) . ': <br><br> {name}, {contact_method}, {email}, {phone}, {best_day}, {best_time}, {table} ' . __( 'and', 'listings' ) . ' {link}',
							'id'      => 'drive_layout',
							'type'    => 'textarea',
							'default' => 'Information

{table}

Vehicle: {link}',
						),
						array(
							'desc'  => __( 'Change the email that receives the emails', 'listings' ),
							'id'    => 'info_to',
							'type'  => 'text',
							'title' => __( 'Request More Info', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the subject of the email that is used to request more info.', 'listings' ),
							'id'      => 'info_subject',
							'type'    => 'text',
							'default' => __( 'Information Request', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the layout of the email. HTML is allowed and some variables you can use are', 'listings' ) . ': <br><br> {name}, {contact_method}, {email}, {phone}, {table} ' . __( 'and', 'listings' ) . ' {link}',
							'id'      => 'info_layout',
							'type'    => 'textarea',
							'default' => __( 'Request Information', 'listings' ) . '

{table}

Vehicle: {link}',
						),
						array(
							'desc'  => __( 'Change the email that receives the emails', 'listings' ),
							'id'    => 'trade_to',
							'type'  => 'text',
							'title' => __( 'Trade-In Appraisal', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the subject of the email that is used.', 'listings' ),
							'id'      => 'trade_subject',
							'type'    => 'text',
							'default' => __( 'Trade-In Appraisal', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the layout of the email. HTML is allowed and some variables you can use are', 'listings' ) . ': <br><br> {table} ' . __( 'and', 'listings' ) . ' {link}',
							'id'      => 'trade_layout',
							'type'    => 'textarea',
							'default' => '{table}

Vehicle: {link}',
						),
						array(
							'desc'  => __( 'Change the email that receives the emails', 'listings' ),
							'id'    => 'offer_to',
							'type'  => 'text',
							'title' => 'Make an Offer',
						),
						array(
							'desc'    => __( 'Edit the subject of the email that is used.', 'listings' ),
							'id'      => 'offer_subject',
							'type'    => 'text',
							'default' => __( 'Offer', 'listings' ),
						),
						array(
							'desc'    => __( 'Edit the layout of the email. HTML is allowed and some variables you can use are', 'listings' ) . ': <br><br> {name}, {contact_method}, {email}, {phone}, {offered_price}, {financing_required}, {other_comments}, {table} ' . __( 'and', 'listings' ) . ' {link}',
							'id'      => 'offer_layout',
							'type'    => 'textarea',
							'default' => '{table}

Vehicle: {link}',
						),
					),
				),
				30  => array(
					'title'  => __( 'Custom Forms', 'listings' ),
					'icon'   => 'fa fa-bars',
					'fields' => array(
						array(
							'desc'  => 'Using <a href=\'https://wordpress.org/plugins/contact-form-7/\' target=\'_blank\'>Contact Form 7</a> you can replace any of the following
forms by pasting a shortcode of the form in the corresponding text box. If left blank the default form will show.<br><br>You can use the tag [_listing_details] in a contact form
to retrieve which listing the form was sent from.',
							'id'    => 'request_info_form_shortcode',
							'type'  => 'text',
							'title' => __( 'Request More Info Form', 'listings' ),
						),
						array(
							'id'    => 'schedule_test_drive_form_shortcode',
							'type'  => 'text',
							'title' => __( 'Schedule Test Drive Form', 'listings' ),
						),
						array(
							'id'    => 'make_offer_form_shortcode',
							'type'  => 'text',
							'title' => __( 'Make an Offer Form', 'listings' ),
						),
						array(
							'id'    => 'tradein_form_shortcode',
							'type'  => 'text',
							'title' => __( 'Trade-In Appraisal Form', 'listings' ),
						),
						array(
							'id'    => 'email_friend_form_shortcode',
							'type'  => 'text',
							'title' => __( 'Email to a Friend Form', 'listings' ),
						),
					),
				),
				40  => array(
					'title'  => __( "Inventory Page", "listings" ),
					'desc'   => __( 'These settings control elements and functionality on the page that displays all of your inventory.', 'listings' ),
					'icon'   => 'fa fa-file',
					'fields' => array(
						array(
							'id'    => 'inventory_page',
							'type'  => 'select',
							'title' => __( 'Inventory Page', 'listings' ),
							'desc'  => __( 'Select the inventory page that will be highlighted in the menu and displayed in the breadcrumbs', 'listings' ),
							'data'  => 'pages'
						),
						array(
							'id'    => 'inventory_no_sold',
							'type'  => 'switch',
							'title' => __( 'Sold Vehicles', 'listings' ),
							'desc'  => __( 'This will hide the vehicles that are sold, sold vehicles can still be shown by adding &show_sold to the end of the URL', 'listings' ),
							'on'    => __( "Show", "listings" ),
							'off'   => __( "Hide", "listings" )
						),
						array(
							'id'       => 'sold_to_bottom',
							'title'    => __( 'Sold Listings to End', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'Enable this option to move all sold listings to the end of your inventory (only works if sold listings are shown in above option)', 'listings' ),
							'default'  => false,
							'on'       => __( "Enabled", "listings" ),
							'off'      => __( "Disabled", "listings" ),
							'required' => array( 'inventory_no_sold', 'equals', '1' )
						),
						array(
							'id'      => 'listings_amount',
							'type'    => 'slider',
							'title'   => __( 'Number of Listings', 'listings' ),
							'desc'    => __( 'The amount of listings being displayed on the inventory page', 'listings' ),
							'step'    => '1',
							'max'     => '100',
							'default' => '10',
						),


						array(
							'id'       => 'inventory_filter_mobile_alert',
							'type'     => 'info',
							'style'    => 'warning',
							'title'    => __( 'Single Option Select', 'listings' ),
							'desc'     => __( 'If you want users to be able to select more than 1 option when filtering, enable the "Show All Filter Terms" and "Filter Multi-Select" options below.', 'listings' ),
							'required' => array( 'inventory_filter_mobile_slideout', 'equals', '1' ),
						),
						array(
							'id'       => 'slideout-section-start',
							'type'     => 'section',
							'title'    => __( 'Inventory Filters Mobile Slideout', 'listings' ),
							'subtitle' => __( 'Toggle the mobile slideout sidebar functionality. If enabled, you can set the widgets in this sidebar under Appearance >> Widgets >> Listings Mobile Sidebar (Slideout).', 'listings' ),
							'indent'   => true
						),
						array(
							'id'    => 'inventory_filter_mobile_slideout',
							'type'  => 'switch',
							'title' => __( 'Slideout Sidebar', 'listings' ),
							'desc'  => __( 'If disabled this will hide the slideout sidebar.', 'listings' ),
							'on'    => __( "Show", "listings" ),
							'off'   => __( "Hide", "listings" ),
						),
						array(
							'id'       => 'inventory_filter_mobile_slideout_accordion',
							'type'     => 'switch',
							'title'    => __( 'Accordion Widgets', 'listings' ),
							'desc'     => __( 'If enabled this will put all widgets in the slideout bar into accordion elements..', 'listings' ),
							'on'       => __( "Enabled", "listings" ),
							'off'      => __( "Disabled", "listings" ),
							'default'  => true,
							'required' => array( 'inventory_filter_mobile_slideout', 'equals', '1' ),
						),

						array(
							'id'       => 'inventory_filter_mobile_loading_image',
							'type'     => 'media',
							'title'    => __( 'Loading Image', 'listings' ),
							'url'      => true,
							'required' => array( 'inventory_filter_mobile_slideout', 'equals', '1' ),
						),
						array(
							'id'       => 'inventory_filter_mobile_button_badge_color',
							'type'     => 'color_rgba',
							'title'    => __( "Filter Results Button Badge Color", "listings" ),
							'desc'     => __( "This badge appears when a user filters the inventory on mobile and keeps track of the total number of filters.", "listings" ),
							'required' => array( 'inventory_filter_mobile_slideout', 'equals', '1' ),
						),
						array(
							'id'    => 'inventory_filter_mobile_hide_filters',
							'type'  => 'switch',
							'title' => __( 'Hide Fullwidth Filters on Mobile', 'listings' ),
							'desc'  => __( 'If disabled this will hide the filters on mobile so the user can only use the Slideout sidebar to filter.', 'listings' ),
							'on'    => __( "Hide", "listings" ),
							'off'   => __( "Show", "listings" ),
						),
						array(
							'id'     => 'slideout-section-end',
							'type'   => 'section',
							'indent' => false,
						),

						array(
							'id'      => 'filter_prefix',
							'type'    => 'text',
							'title'   => __( 'Filter Prefix', 'listings' ),
							'desc'    => __( 'Change the "All" prefix used on the dropdown filters', 'listings' ),
							'default' => 'All'
						),
						array(
							'id'      => 'filter_showall',
							'title'   => __( 'Show All Filter Terms', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will let users view all dropdown options instead of removing the search term.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'filter_show_current_dropdown_terms',
							'title'    => __( 'Show All Current Filter Terms', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'If enabled this will show all the terms available for the opened filter dropdown (instead of having to remove the selection before viewing other terms).', 'listings' ),
							'default'  => false,
							'on'       => __( "Enabled", "listings" ),
							'off'      => __( "Disabled", "listings" ),
							'required' => array( 'filter_showall', 'equals', 0 )
						),
						array(
							'id'       => 'filter_multiselect',
							'title'    => __( 'Filter Multi-Select', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'Allows users to select multiple values in the same listing category dropdown.', 'listings' ),
							'default'  => false,
							'on'       => __( "Enabled", "listings" ),
							'off'      => __( "Disabled", "listings" ),
							'required' => array( 'filter_showall', 'equals', 1 )
						),
						array(
							'id'      => 'sortby',
							'title'   => __( 'Sort By Functionality', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Enable or disable the sort by functionality', 'listings' ),
							'default' => true,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'sortby_categories',
							'title'    => __( 'Sort By Categories', 'listings' ),
							'type'     => 'sorter',
							'class'    => 'sort_by_option_redux',
							'options'  => $Listing->sort_by_options(),
							'required' => array( 'sortby', 'equals', '1' )
						),
						array(
							'id'       => 'sortby_default',
							'title'    => __( 'Default Sort By', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'Adjust how the sort by defaults to', 'listings' ),
							'default'  => true,
							'on'       => __( "Ascending", "listings" ),
							'off'      => __( "Descending", "listings" ),
							'required' => array( 'sortby', 'equals', '1' )
						),
						array(
							'id'      => 'sortby_default_alpha',
							'title'   => __( 'Alphabetical Sorting', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will sort the above order by alphabetical order within the category.', 'listings' ),
							'default' => false
						),
						array(
							'id'      => 'original_pricing',
							'title'   => __( 'Show Original Pricing', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will show the original pricing on the inventory page.', 'listings' ),
							'default' => false
						),
						array(
							'title'   => __( "Listing Views", "listings" ),
							'desc'    => __( 'Toggle the functionality of the listing views', 'listings' ),
							'id'      => 'inventory_listing_toggle',
							'type'    => 'switch',
							'default' => '1',
						),
						array(
							'title'   => __( "Mobile Sidebar Position", "listings" ),
							'desc'    => __( 'Control the position of the mobile sidebar if it loads above or below the inventory.', 'listings' ),
							'id'      => 'mobile_sidebar_position',
							'type'    => 'switch',
							'default' => '0',
							'on'      => __( "Top", "listings" ),
							'off'     => __( "Bottom", "listings" ),
						),
						array(
							'title'   => __( "Mobile Top Filters", "listings" ),
							'desc'    => __( 'Show the top filters on mobile devices regardless of the shortcode settings', 'listings' ),
							'id'      => 'mobile_inventory_filters',
							'type'    => 'switch',
							'default' => '0',
						),
						array(
							'title'   => __( "Top Pagination", "listings" ),
							'desc'    => __( 'Toggle the functionality of the top pagination, useful for single paged inventories.', 'listings' ),
							'id'      => 'top_pagination',
							'type'    => 'switch',
							'default' => '1',
						),
						array(
							'title'   => __( "Bottom Pagination", "listings" ),
							'desc'    => __( 'Toggle the functionality of the bottom pagination, useful for single paged inventories.', 'listings' ),
							'id'      => 'bottom_pagination',
							'type'    => 'switch',
							'default' => '1',
						),
						array(
							'title'   => __( "Vehicle Overview on Listings", "listings" ),
							'desc'    => __( 'If enabled this will show the vehicle overview on listings instead of the listing categories.', 'listings' ),
							'id'      => 'vehicle_overview_listings',
							'type'    => 'switch',
							'default' => '0',
						),
						array(
							'title'    => __( 'Vehicle Overview Character Limit', 'listings' ),
							'desc'     => __( "Control the amount of characters shown on the exceprt", "listings" ),
							'id'       => 'vehicle_overview_listings_limit',
							'type'     => 'text',
							'validate' => 'numeric',
							'required' => array( 'vehicle_overview_listings', 'equals', 1 ),
							'default'  => 250
						),
						array(
							'title'    => __( 'Vehicle Overview Ellipsis', 'listings' ),
							'desc'     => __( "Customize the text used when a vehicle overview is longer than what is displayed", "listings" ),
							'id'       => 'vehicle_overview_ellipsis',
							'type'     => 'text',
							'required' => array( 'vehicle_overview_listings', 'equals', 1 ),
							'default'  => "[...]"
						),
						array(
							'title'   => __( "Thumbnail Slideshow", "listings" ),
							'desc'    => __( 'Display a slideshow if the user clicks the listing thumbnail', 'listings' ),
							'id'      => 'thumbnail_slideshow',
							'type'    => 'switch',
							'default' => '1',
						),
						array(
							'id'       => 'show_all_photos',
							'title'    => __( 'Thumbnail Slideshow All Photos', 'listings' ),
							'type'     => 'switch',
							'desc'     => __( 'If enabled this will display an "All Photos" slide for the thumbnail slideshow.', 'listings' ),
							'default'  => false,
							'required' => array( 'thumbnail_slideshow', 'equals', '1' )
						),
					),
				),
				50  => array(
					'title'  => __( 'Single Listing Page', 'listings' ),
					'desc'   => __( 'These settings control elements and functionality on the page that displays a single listing.', 'listings' ),
					'icon'   => 'fa fa-file-o',
					'fields' => array(
						array(
							'desc'    => __( 'Customize the slug used for single listings. <br><br>You will need to regenerate the permalink settings after you change this value by going to "Settings" >> "Permalinks" and re-saving the options.' ),
							'id'      => 'listing_slug',
							'type'    => 'text',
							'title'   => __( 'Listing Slug', 'listings' ),
							'default' => 'listings'
						),
						array(
							'id'      => 'inventory_primary_title',
							'type'    => 'text',
							'desc'    => __( 'This title shows up in the header section of all listings posted.', 'listings' ),
							'title'   => __( 'Inventory Listing Titles', 'listings' ),
							'default' => __( 'Inventory Listing', 'listings' ),
						),
						array(
							'id'      => 'inventory_secondary_title',
							'type'    => 'text',
							'desc'    => __( 'This secondary title displays under previous title in the header', 'listings' ),
							'default' => __( 'Powerful Inventory Marketing, Fully Integrated', 'listings' ),
						),
						array(
							'id'      => 'woocommerce_listing_integration',
							'title'   => __( "WooCommerce Listing Integration", "listings" ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will allow you to associate WooCommerce products with your listings allowing users to add items to their carts while browsing listings.', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'woocommerce_integration_border',
							'title'    => __( "WooCommerce Integration Border", "listings" ),
							'type'     => 'border',
							'desc'     => __( "Control the border around the WooCommerce Add to Cart element", "listings" ),
							'all'      => true,
							'default'  => array(
								'border-color'  => '#ccc',
								'border-style'  => 'solid',
								'border-top'    => '4px',
								'border-right'  => '4px',
								'border-bottom' => '4px',
								'border-left'   => '4px'
							),
							'required' => array( 'woocommerce_listing_integration', 'equals', 1 )
						),
						array(
							'id'       => 'woocommerce_integration_padding',
							'title'    => __( "WooCommerce Integration Padding", "listings" ),
							'type'     => 'spacing',
							'desc'     => __( "Control the padding inside the WooCommerce Add to Cart element", "listings" ),
							'mode'     => 'padding',
							'units'    => 'px',
							'default'  => array(
								'padding-left'   => '12px',
								'padding-top'    => '12px',
								'padding-bottom' => '12px',
								'padding-right'  => '12px',
							),
							'required' => array( 'woocommerce_listing_integration', 'equals', 1 )
						),
						array(
							'desc'    => __( 'Show or hide the fuel efficiency box on the inventory page', 'listings' ),
							'id'      => 'fuel_efficiency_show',
							'type'    => 'switch',
							'title'   => __( 'Fuel Efficiency', 'listings' ),
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'desc'     => __( 'The image used to display a vehicles fuel efficiency rating', 'listings' ),
							'id'       => 'fuel_efficiency_image',
							'type'     => 'media',
							'title'    => __( 'Fuel Efficiency Rating Image', 'listings' ),
							'required' => array( 'fuel_efficiency_show', 'equals', 1 )
						),
						array(
							'id'       => 'fuel_efficiency_title',
							'type'     => 'text',
							'title'    => __( 'Fuel Efficiency Rating Title', 'listings' ),
							'desc'     => __( 'This title shows up in the fuel efficiency rating widget.', 'listings' ),
							'default'  => __( 'Fuel Efficiency Rating', 'listings' ),
							'required' => array( 'fuel_efficiency_show', 'equals', 1 )
						),
						array(
							'desc'     => __( 'The text displayed in the fuel efficiency box', 'listings' ),
							'id'       => 'fuel_efficiency_text',
							'type'     => 'textarea',
							'default'  => __( 'Actual rating will vary with options, driving conditions, driving habits and vehicle condition.', 'listings' ),
							'required' => array( 'fuel_efficiency_show', 'equals', 1 )
						),
						array(
							'desc'    => __( 'Show or hide the social icons', 'listings' ),
							'id'      => 'social_icons_show',
							'type'    => 'switch',
							'title'   => __( 'Social Icons', 'listings' ),
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'desc'    => __( 'Show or hide the listing video in the right sidebar', 'listings' ),
							'id'      => 'display_vehicle_video',
							'type'    => 'switch',
							'title'   => __( 'Listing Video', 'listings' ),
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'id'      => 'youtube_video_options',
							'type'    => 'checkbox',
							'title'   => __( 'YouTube Video Options', 'listings' ),
							'desc'    => __( 'Add additional options to the videos used in the listings', 'listings' ),
							'options' => array(
								'rel'             => __( 'Show related videos when the video finishes', 'listings' ),
								'auto_play'       => __( 'Automatically play the video when the page loads', 'listings' ),
								'player_controls' => __( 'Show player controls', 'listings' ),
								'title_actions'   => __( 'Show video title and player actions', 'listings' ),
								'privacy'         => __( 'Enable privacy-enhanced mode', 'listings' ),
							)
						),
						array(
							'id'      => 'listing_badge_slider',
							'title'   => __( 'Listing Badge on Slider', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'Enable or disable the listing badge on the listing slider', 'listings' ),
							'default' => false,
							'on'      => __( "Enabled", "listings" ),
							'off'     => __( "Disabled", "listings" )
						),
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Financing Calculator', 'listings' ),
							'subtitle' => __( 'Control the financing calculator found on the inventory listing page. If you have a sidebar set under Appearance >> Widgets >> Single Listing Sidebar you can control the widget settings from there.', 'listings' ),
							'indent'   => true
						),
						array(
							'desc'    => __( 'Show or hide the financing calculator', 'listings' ),
							'id'      => 'calculator_show',
							'type'    => 'switch',
							'title'   => __( 'Financing Calculator', 'listings' ),
							'on'      => 'Show',
							'off'     => 'Hide',
							'default' => true
						),
						array(
							'desc'     => __( 'Control the down payment for the finance calculator', 'listings' ),
							'id'       => 'calculator_down_payment',
							'type'     => 'text',
							'validate' => 'numeric',
							'title'    => __( 'Financing Calculator Down Payment', 'listings' ),
							'default'  => 1000,
							'required' => array( 'calculator_show', '=', true )
						),
						array(
							'desc'     => __( 'Control the annual interest rate for the finance calculator', 'listings' ),
							'id'       => 'calculator_rate',
							'type'     => 'text',
							'validate' => 'numeric',
							'title'    => __( 'Financing Calculator Rate', 'listings' ),
							'default'  => 7,
							'required' => array( 'calculator_show', '=', true )
						),
						array(
							'title'   => __( 'Default Loan Calculator Frequency', 'listings' ),
							'desc'    => __( 'Choose which option is selected', 'listings' ),
							'type'    => 'button_set',
							'id'      => 'default_frequency',
							'options' => array(
								'1' => __( 'Bi-Weekly', 'listings' ),
								'2' => __( 'Weekly', 'listings' ),
								'3' => __( 'Monthly', 'listings' )
							),
							'default' => 1
						),
						array(
							'desc'     => __( 'Control the text displayed below the finance calculator', 'listings' ),
							'id'       => 'calculator_below_text',
							'type'     => 'text',
							'title'    => __( 'Financing Calculator Text', 'listings' ),
							'required' => array( 'calculator_show', '=', true )
						),
						array(
							'desc'     => __( 'Control the term of loan for the finance calculator', 'listings' ),
							'id'       => 'calculator_loan',
							'type'     => 'text',
							'validate' => 'numeric',
							'title'    => __( 'Financing Calculator Loan', 'listings' ),
							'default'  => 5,
							'required' => array( 'calculator_show', '=', true )
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'      => 'recent_vehicles_show',
							'type'    => 'switch',
							'title'   => __( 'Vehicle Scroller', 'listings' ),
							'desc'    => __( 'Show or hide the vehicles scroller on the single listing page.', 'listings' ),
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'id'       => 'recent_related_vehicles',
							'type'     => 'switch',
							'title'    => __( 'Recent or Related Vehicles', 'listings' ),
							'desc'     => __( 'Change the slider to show either recent or related vehicles', 'listings' ),
							'on'       => __( 'Recent', 'listings' ),
							'off'      => __( 'Related', 'listings' ),
							'required' => array( 'recent_vehicles_show', 'equals', 1 ),
							'default'  => true
						),
						array(
							'title'    => __( 'Related Vehicle Category', 'listings' ),
							'desc'     => __( 'Use this category to select related vehicles.', 'listings' ),
							'type'     => 'select',
							'id'       => 'related_category',
							'required' => array( 'recent_related_vehicles', '!=', 1 ),
							'options'  => $Listing->get_listing_categories_to_redux_select(),
						),
						array(
							'title'    => __( 'Related Vehicle Category Value', 'listings' ),
							'desc'     => __( 'Choose whether the related vehicles should share an exact value or the closest available on your website.', 'listings' ),
							'id'       => 'recent_related_vehicle_value',
							'type'     => 'switch',
							'on'       => __( 'Exact Value', 'listings' ),
							'off'      => __( 'Closest Values', 'listings' ),
							'required' => array( 'recent_related_vehicles', '!=', 1 ),
							'default'  => true
						),
						array(
							'title'    => __( 'Related Vehicle Category Value Order', 'listings' ),
							'desc'     => __( 'Choose whether the closest values should be chosen ascending or descending.', 'listings' ),
							'id'       => 'recent_related_vehicle_value_order',
							'type'     => 'switch',
							'on'       => __( 'Ascending', 'listings' ),
							'off'      => __( 'Descending', 'listings' ),
							'required' => array( 'recent_related_vehicle_value', '!=', 1 ),
							'default'  => true
						),
						array(
							'id'       => 'recent_vehicles_title',
							'type'     => 'text',
							'title'    => __( 'Vehicle Scroller Title', 'listings' ),
							'desc'     => __( 'Edit the recent vehicles slider title', 'listings' ),
							'required' => array( 'recent_vehicles_show', 'equals', 1 ),
							'default'  => __( "Recent Vehicles", "listings" )
						),
						array(
							'id'       => 'recent_vehicles_desc',
							'type'     => 'text',
							'title'    => __( 'Vehicle Scroller Description', 'listings' ),
							'desc'     => __( 'Edit the recent vehicles slider description', 'listings' ),
							'required' => array( 'recent_vehicles_show', 'equals', 1 ),
							'default'  => __( "Browse through the vast selection of vehicles that have recently been added to our inventory.", "listings" )
						),
						array(
							'id'       => 'recent_vehicles_limit',
							'type'     => 'text',
							'title'    => __( 'Number of Vehicles', 'listings' ),
							'desc'     => __( 'Adjust the amount of vehicles shown in the recent vehicles (-1 to display all).', 'listings' ),
							'required' => array( 'recent_vehicles_show', 'equals', 1 ),
							'default'  => "10"
						),
						array(
							'id'       => 'recent_automatic_scrolling',
							'type'     => 'switch',
							'title'    => __( 'Automatic Scrolling', 'listings' ),
							'on'       => __( 'Enable', 'listings' ),
							'off'      => __( 'Disable', 'listings' ),
							'required' => array( 'recent_vehicles_show', 'equals', 1 ),
							'default'  => false
						),
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Top buttons', 'listings' ),
							'subtitle' => __( 'Show or hide the top buttons found on the inventory listing page. Leave link text box blank to revert to default link.', 'listings' ),
							'indent'   => true
						),
						array(
							'title'   => __( 'Previous Vehicle', 'listings' ),
							'id'      => 'previous_vehicle_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Previous Vehicle Label', 'listings' ),
							'id'      => 'previous_vehicle_label',
							'type'    => 'text',
							'default' => "Prev Vehicle"
						),
						array(
							'title'   => __( 'Request More Info', 'listings' ),
							'id'      => 'request_more_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Request More Info Label', 'listings' ),
							'id'      => 'request_more_label',
							'type'    => 'text',
							'default' => "Request More Info"
						),
						array(
							'title'   => __( 'Request More Info Link', 'listings' ),
							'id'      => 'request_more_link',
							'type'    => 'text',
							'default' => ""
						),
						array(
							'title'   => __( 'Request More Info Link Target', 'listings' ),
							'id'      => 'request_more_target',
							'type'    => 'select',
							'options' => array(
								'_blank'  => __( 'New Tab', 'listings' ),
								'_self'   => __( 'Same window', 'listings' ),
								'_parent' => __( 'Parent Frame', 'listings' )
							),
						),
						array(
							'title'   => __( 'Schedule Test Drive', 'listings' ),
							'id'      => 'schedule_test_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Schedule Test Drive Label', 'listings' ),
							'id'      => 'schedule_test_label',
							'type'    => 'text',
							'default' => "Schedule Test Drive"
						),
						array(
							'title'   => __( 'Schedule Test Drive Link', 'listings' ),
							'id'      => 'schedule_test_link',
							'type'    => 'text',
							'default' => ""
						),
						array(
							'title'   => __( 'Schedule Test Drive Link Target', 'listings' ),
							'id'      => 'schedule_test_target',
							'type'    => 'select',
							'options' => array(
								'_blank'  => __( 'New Tab', 'listings' ),
								'_self'   => __( 'Same window', 'listings' ),
								'_parent' => __( 'Parent Frame', 'listings' )
							),
						),
						array(
							'title'   => __( 'Make an Offer', 'listings' ),
							'id'      => 'make_offer_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Make an Offer Label', 'listings' ),
							'id'      => 'make_offer_label',
							'type'    => 'text',
							'default' => "Make an Offer"
						),
						array(
							'title'   => __( 'Make an Offer Link', 'listings' ),
							'id'      => 'make_offer_link',
							'type'    => 'text',
							'default' => ""
						),
						array(
							'title'   => __( 'Make an Offer Link Target', 'listings' ),
							'id'      => 'make_offer_target',
							'type'    => 'select',
							'options' => array(
								'_blank'  => __( 'New Tab', 'listings' ),
								'_self'   => __( 'Same window', 'listings' ),
								'_parent' => __( 'Parent Frame', 'listings' )
							),
						),
						array(
							'title'   => __( 'Trade-In Appraisal', 'listings' ),
							'id'      => 'tradein_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Trade-In Appraisal Label', 'listings' ),
							'id'      => 'tradein_label',
							'type'    => 'text',
							'default' => "Trade-In Appraisal"
						),
						array(
							'title'   => __( 'Trade-In Appraisal Link', 'listings' ),
							'id'      => 'tradein_link',
							'type'    => 'text',
							'default' => ""
						),
						array(
							'title'   => __( 'Trade-In Appraisal Link Target', 'listings' ),
							'id'      => 'tradein_target',
							'type'    => 'select',
							'options' => array(
								'_blank'  => __( 'New Tab', 'listings' ),
								'_self'   => __( 'Same window', 'listings' ),
								'_parent' => __( 'Parent Frame', 'listings' )
							),
						),
						array(
							'title'   => __( 'PDF Brochure', 'listings' ),
							'id'      => 'pdf_brochure_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'PDF Brochure Label', 'listings' ),
							'id'      => 'pdf_brochure_label',
							'type'    => 'text',
							'default' => "PDF Brochure"
						),
						array(
							'title'   => __( 'Print this Vehicle', 'listings' ),
							'id'      => 'print_vehicle_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Print this Vehicle Label', 'listings' ),
							'id'      => 'print_vehicle_label',
							'type'    => 'text',
							'default' => "Print this Vehicle"
						),
						array(
							'title'   => __( 'Email to a Friend', 'listings' ),
							'id'      => 'email_friend_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Email to a Friend Label', 'listings' ),
							'id'      => 'email_friend_label',
							'type'    => 'text',
							'default' => "Email to a Friend"
						),
						array(
							'title'   => __( 'Email to a Friend Link', 'listings' ),
							'id'      => 'email_friend_link',
							'type'    => 'text',
							'default' => ""
						),
						array(
							'title'   => __( 'Email to a Friend Link Target', 'listings' ),
							'id'      => 'email_friend_target',
							'type'    => 'select',
							'options' => array(
								'_blank'  => __( 'New Tab', 'listings' ),
								'_self'   => __( 'Same window', 'listings' ),
								'_parent' => __( 'Parent Frame', 'listings' )
							),
						),
						array(
							'title'   => __( 'Next Vehicle', 'listings' ),
							'id'      => 'next_vehicle_show',
							'type'    => 'switch',
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Next Vehicle Label', 'listings' ),
							'id'      => 'next_vehicle_label',
							'type'    => 'text',
							'default' => "Next Vehicle"
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Content Tabs', 'listings' ),
							'subtitle' => __( 'Edit the names of the tabs displayed under the slideshow. Leave blank remove the tab from the admin and frontend.', 'listings' ),
							'indent'   => true
						),
						array(
							'title'   => __( 'First Tab', 'listings' ),
							'desc'    => __( 'Default text: Vehicle Overview', 'listings' ),
							'id'      => 'first_tab',
							'type'    => 'text',
							'default' => 'Vehicle Overview'
						),
						array(
							'title'   => __( 'Second Tab', 'listings' ),
							'desc'    => __( 'Default text: Features & Options', 'listings' ),
							'id'      => 'second_tab',
							'type'    => 'text',
							'default' => 'Features & Options'
						),
						array(
							'title'   => __( 'Third Tab', 'listings' ),
							'desc'    => __( 'Default text: Technical Specifications', 'listings' ),
							'id'      => 'third_tab',
							'type'    => 'text',
							'default' => 'Technical Specifications'
						),
						array(
							'title'   => __( 'Fourth Tab', 'listings' ),
							'desc'    => __( 'Default text: Vehicle Location', 'listings' ),
							'id'      => 'fourth_tab',
							'type'    => 'text',
							'default' => 'Vehicle Location'
						),
						array(
							'title'   => __( 'Fifth Tab', 'listings' ),
							'desc'    => __( 'Default text: Other Comments', 'listings' ),
							'id'      => 'fifth_tab',
							'type'    => 'text',
							'default' => 'Other Comments'
						),
						array(
							'id'      => 'inventory_custom_tab_order',
							'title'   => __( 'Custom Tab Order', 'listings' ),
							'type'    => 'switch',
							'on'      => __( 'Enabled', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'default' => false
						),
						array(
							'id'       => 'inventory_tab_order',
							'type'     => 'sortable',
							'title'    => __( 'Tab Order', 'listings' ),
							'mode'     => 'text',
							'required' => array( 'inventory_custom_tab_order', 'equals', true ),
							'options'  => wp_list_pluck( Automotive_Plugin()->get_raw_listing_tabs(), 'text' ),
							// For checkbox mode

						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'    => 'single_listing_map_styling',
							'type'  => 'ace_editor',
							'title' => __( 'Map Styling', 'redux-framework-demo' ),
							'mode'  => 'json',
							'theme' => 'chrome',
							'desc'  => sprintf( __( "Style of google map, styles availiable at: %s", "listings" ), "<a href='http://snazzymaps.com/' target='_blank'>http://snazzymaps.com/</a>" ),
						),
						array(
							'desc'  => __( 'Logo used for the generated PDF\'s. Must be using header logo image.', 'listings' ),
							'id'    => 'pdf_logo',
							'type'  => 'media',
							'url'   => true,
							'title' => __( 'PDF Logo', 'listings' )
						),
						array(
							'desc'    => __( 'Enable or disable comments on the single listing page.', 'listings' ),
							'id'      => 'listing_comments',
							'type'    => 'switch',
							'title'   => __( 'Comments on listing pages', 'listings' ),
							'on'      => __( 'Enable', 'listings' ),
							'off'     => __( 'Disable', 'listings' ),
							'default' => false
						),
						array(
							'desc'  => __( 'Add a message under each single listing page.', 'listings' ),
							'id'    => 'listing_comment_footer',
							'type'  => 'editor',
							'title' => __( 'Message under each listing', 'listings' )
						),
						array(
							'desc'  => __( 'Display a comment under the vehicle overview for sold vehicles.', 'listings' ),
							'id'    => 'sold_listing_comment',
							'type'  => 'textarea',
							'title' => __( 'Message under each sold listing', 'listings' )
						),
					),
				),
				60  => array(
					'title'  => __( "Portfolio Page", "listings" ),
					'fields' => array(
						array(
							'id'    => 'portfolio_page',
							'type'  => 'select',
							'title' => __( 'Portfolio Page', 'listings' ),
							'desc'  => __( 'Select the portfolio page that will be highlighted in the menu.', 'listings' ),
							'data'  => 'pages'
						),
						array(
							'desc'    => __( 'Customize the slug used for portfolio items. (Don\'t create a page with the same slug)<br><br>You will need to regenerate the permalink settings after you change this value by going to "Settings" >> "Permalinks" and re-saving the options.' ),
							'id'      => 'portfolio_slug',
							'type'    => 'text',
							'title'   => __( 'Portfolio Slug', 'listings' ),
							'default' => 'listings_portfolio'
						),
						array(
							'desc'    => __( 'Control the amount of portfolio items per page, -1 will display all.' ),
							'id'      => 'portfolios_per_page',
							'type'    => 'text',
							'title'   => __( 'Portfolio Items Per Page', 'listings' ),
							'default' => '-1'
						),
						array(
							'desc'    => __( 'Change the portfolio image to link to the page rather than to fancybox.', 'listings' ),
							'id'      => 'portfolio_image_link',
							'type'    => 'switch',
							'title'   => __( 'Portfolio Image Link', 'listings' ),
							'on'      => __( 'FancyBox', 'listings' ),
							'off'     => __( 'Portfolio Item', 'listings' ),
							'default' => true
						),
						array(
							'title'   => __( 'Job Description Title', 'listings' ),
							'id'      => 'job_description_title',
							'type'    => 'text',
							'default' => 'Job Description'
						),
						array(
							'title'   => __( 'Project Details Title', 'listings' ),
							'id'      => 'project_details_title',
							'type'    => 'text',
							'default' => 'Projects Details'
						),
						array(
							'title'   => __( 'Related Projects Title', 'listings' ),
							'id'      => 'related_projects_title',
							'type'    => 'text',
							'default' => 'Related Projects'
						),
						array(
							'desc'    => __( 'Show or hide the related projects on the portfolio page.', 'listings' ),
							'id'      => 'show_related_projects',
							'type'    => 'switch',
							'title'   => __( 'Show Related Projects', 'listings' ),
							'on'      => __( 'Show', 'listings' ),
							'off'     => __( 'Hide', 'listings' ),
							'default' => true
						),
						array(
							'id'      => 'disable_portfolio_sidebar',
							'type'    => 'switch',
							'type'    => 'switch',
							'title'   => __( 'Disable Portfolio Sidebar', 'listings' ),
							'on'      => __( 'Disable', 'listings' ),
							'off'     => __( 'Enable', 'listings' ),
							'default' => false

						)
					),
					'icon'   => 'fa fa-folder-o'
				),
				70  => array(
					'title'  => __( 'Revolution Slider Shortcode', 'listings' ),
					'fields' => array(
						array(
							'id'    => 'revolution_shortcode',
							'type'  => 'revolution_shortcode',
							'title' => __( 'Generator', 'listings' )
						)
					)
				),
				80  => array(
					'title'  => __( 'Default Values', 'listings' ),
					'fields' => array(
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Default location', 'listings' ),
							'subtitle' => __( 'This location will be the default used while creating new listings', 'listings' ),
							'indent'   => true
						),
						array(
							'title'   => __( 'Latitude', 'listings' ),
							'desc'    => __( 'The default latitude.', 'listings' ),
							'id'      => 'default_value_lat',
							'type'    => 'text',
							'default' => '43.653226'
						),
						array(
							'title'   => __( 'Longitude', 'listings' ),
							'desc'    => __( 'The default longitude.', 'listings' ),
							'id'      => 'default_value_long',
							'type'    => 'text',
							'default' => '-79.3831843'
						),
						array(
							'title'         => __( 'Zoom', 'listings' ),
							'desc'          => __( 'The default zoom level.', 'listings' ),
							'id'            => 'default_value_zoom',
							'type'          => 'slider',
							'default'       => '10',
							'min'           => 0,
							'max'           => 19,
							'step'          => 1,
							'display_value' => 'text'
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Default Details', 'listings' ),
							'subtitle' => __( 'Control the default values for the details of new listings.', 'listings' ),
							'indent'   => true
						),
						array(
							'title'   => __( 'Price Label', 'listings' ),
							'desc'    => __( 'The default label for price.', 'listings' ),
							'id'      => 'default_value_price',
							'type'    => 'text',
							'default' => 'Price'
						),
						array(
							'title'   => __( 'Original Price Label', 'listings' ),
							'desc'    => __( 'The default label for original price.', 'listings' ),
							'id'      => 'default_value_original_price',
							'type'    => 'text',
							'default' => 'Original Price'
						),
						array(
							'title'   => __( 'City MPG Label', 'listings' ),
							'desc'    => __( 'The default label for city MPG.', 'listings' ),
							'id'      => 'default_value_city',
							'type'    => 'text',
							'default' => 'City'
						),
						array(
							'title'   => __( 'Highway MPG Label', 'listings' ),
							'desc'    => __( 'The default label for highway MPG.', 'listings' ),
							'id'      => 'default_value_hwy',
							'type'    => 'text',
							'default' => 'Highway'
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
					),
					'icon'   => 'fa fa-pencil-square-o'
				),
				90  => array(
					'title'  => __( "File Import Settings", "listings" ),
					'class'  => 'file_import',
					'desc'   => __( "Leave these fields for the script to detect which delimiters are being used.", "listings" ),
					'fields' => array(
						array(
							'title'   => __( 'CSV Delimiter', 'listings' ),
							'desc'    => __( 'The delimiter used for the entire file.', 'listings' ),
							'id'      => 'csv_delimiter',
							'type'    => 'text',
							'default' => ','
						),
						array(
							'title' => __( 'Features & Options Delimiter', 'listings' ),
							'desc'  => __( 'The delimiter used for just the features & options.', 'listings' ),
							'id'    => 'features_delimiter',
							'type'  => 'text'
						),
						array(
							'title' => __( 'Gallery Images Delimiter', 'listings' ),
							'desc'  => __( 'The delimiter used for just the gallery images.', 'listings' ),
							'id'    => 'gallery_delimiter',
							'type'  => 'text'
						),
						array(
							'title'   => __( 'Post Status', 'listings' ),
							'desc'    => __( 'Choose what the status will be set to for imported posts.', 'listings' ),
							'type'    => 'select',
							'id'      => 'import_post_status',
							'options' => array(
								'publish' => __( 'Published', 'listings' ),
								'draft'   => __( 'Draft', 'listings' ),
								'pending' => __( 'Pending', 'listings' ),
								'private' => __( 'Private', 'listings' ),
							),
							'default' => 'publish'
						),
						array(
							'title' => __( 'Sold Value', 'listings' ),
							'desc'  => __( 'If a vehicle has this value under "Car Sold" while being imported it will be marked as sold.', 'listings' ),
							'id'    => 'sold_value',
							'type'  => 'text'
						),
						array(
							'id'      => 'remove_query_strings_import',
							'title'   => __( 'Keep image URL query strings', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will keep the image query strings when importing image URLs', 'listings' ),
							'default' => false
						),
						array(
							'id'      => 'prices_include_decimals',
							'title'   => __( 'Price contains decimals', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If your import file includes decimals enable this so the prices import properly.', 'listings' ),
							'default' => false
						),
						array(
							'id'      => 'use_original_price_empty',
							'title'   => __( 'Use Original Price', 'listings' ),
							'type'    => 'switch',
							'desc'    => __( 'If enabled this will use the original price as your price if your "price" field is empty.', 'listings' ),
							'default' => false
						),

						array(
							'id'       => 'section-start',
							'type'     => 'section',
							'title'    => __( 'Page Settings', 'listings' ),
							'subtitle' => __( 'Control the page settings of newly imported settings.', 'listings' ),
							'indent'   => true
						),
						array(
							'desc'  => __( 'Choose the header image used for newly imported listings.', 'listings' ),
							'id'    => 'file_import_header_image',
							'type'  => 'media',
							'title' => __( 'Header Image', 'listings' )
						),
						array(
							'title'   => __( 'Footer Area', 'listings' ),
							'desc'    => __( 'Choose the footer area used for newly imported listings.', 'listings' ),
							'type'    => 'select',
							'id'      => 'file_import_footer_area',
							'options' => $footer_areas
						),
						array(
							'desc'    => __( 'Show or hide the related projects on the portfolio page.', 'listings' ),
							'id'      => 'file_import_call_to_action',
							'type'    => 'switch',
							'title'   => __( 'Call to action', 'listings' ),
							'on'      => __( 'Enabled', 'listings' ),
							'off'     => __( 'Disabled', 'listings' ),
							'default' => false
						),
						array(
							'title'    => __( 'Call to action text', 'listings' ),
							'id'       => 'file_import_call_to_action_text',
							'type'     => 'text',
							'required' => array( 'file_import_call_to_action', 'equals', '1' )
						),
						array(
							'title'    => __( 'Call to action button text', 'listings' ),
							'id'       => 'file_import_call_to_action_button_text',
							'type'     => 'text',
							'required' => array( 'file_import_call_to_action', 'equals', '1' )
						),
						array(
							'title'    => __( 'Call to action button link', 'listings' ),
							'id'       => 'file_import_call_to_action_button_link',
							'type'     => 'text',
							'required' => array( 'file_import_call_to_action', 'equals', '1' )
						),
						array(
							'title'    => __( 'Call to action button class', 'listings' ),
							'id'       => 'file_import_call_to_action_button_class',
							'type'     => 'text',
							'required' => array( 'file_import_call_to_action', 'equals', '1' )
						),
						array(
							'title'   => __( 'Slideshow', 'listings' ),
							'desc'    => __( 'Choose the slideshow used for newly imported listings.', 'listings' ),
							'type'    => 'select',
							'id'      => 'file_import_slideshow',
							'options' => $rev_sliders
						),

						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
					)
				),
				100 => array(
					'title'  => __( "VIN Import Settings", "listings" ),
					'class'  => 'vin_import',
					'desc'   => __( "Settings used for the VIN import.", "listings" ),
					'fields' => array()
				),
				110 => array(
					'title'  => __( "API Keys", "listings" ),
					'class'  => 'api_keys',
					'fields' => array(
						array(
							'id'     => 'section-start',
							'type'   => 'section',
							'title'  => __( 'MailChimp API', 'listings' ),
							'indent' => true
						),
						array(
							'id'    => 'mailchimp_api_key',
							'type'  => 'text',
							'title' => __( 'MailChimp API', 'listings' ),
							'desc'  => __( 'Enter your <a href="https://apidocs.mailchimp.com/" target="_blank">MailChimp</a> API Key to use the MailChimp widget.', 'listings' ),
						),
						array(
							'id'      => 'mailchimp_double_opt_in',
							'type'    => 'switch',
							'title'   => __( 'Double Opt In', 'listings' ),
							'default' => false,
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'     => 'section-start',
							'type'   => 'section',
							'title'  => __( 'Google reCAPTCHA API V2', 'listings' ),
							'indent' => true
						),
						array(
							'id'      => 'recaptcha_enabled',
							'type'    => 'switch',
							'title'   => __( 'ReCAPTCHA enabled', 'listings' ),
							'default' => true,
						),
						array(
							'id'      => 'recaptcha_script_enabled',
							'type'    => 'switch',
							'title'   => __( 'ReCAPTCHA script enabled', 'listings' ),
							'desc'    => __( 'Disable this option if another plugin is already loading the reCAPTCHA V2 script.', 'listings' ),
							'default' => true,
						),
						array(
							'id'    => 'recaptcha_public_key',
							'type'  => 'text',
							'title' => __( 'Site Key', 'listings' ),
						),
						array(
							'id'    => 'recaptcha_private_key',
							'type'  => 'text',
							'title' => __( 'Secret Key', 'listings' ),
							'desc'  => __( 'You can get a Google reCAPTCHA v2 API key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">here</a>', 'listings' ),
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						),
						array(
							'id'     => 'section-start',
							'type'   => 'section',
							'title'  => __( 'Google Maps API', 'listings' ),
							'indent' => true
						),
						array(
							'id'    => 'google_maps_api',
							'type'  => 'text',
							'title' => __( 'Google Maps API Key', 'listings' ),
							'desc'  => __( 'You can get a Google Maps API from <a href="https://developers.google.com/maps/documentation/javascript/" target="_blank">here</a>', 'listings' ),
						),
						array(
							'id'      => 'google_maps_autocomplete',
							'type'    => 'switch',
							'title'   => __( 'Address Autocomplete', 'listings' ),
							'default' => false,
							'desc'    => __( 'Quickly grab the latitude and longitude for listings using an address. Requires the Google Places API to be enabled on your above API Key.', 'listings' )
						),
						array(
							'id'     => 'section-end',
							'type'   => 'section',
							'indent' => false,
						)
					),
					'icon'   => 'fa fa-key'
				),
				120 => array(
					'title'  => __( "Import / Export", "listings" ),
					'class'  => 'custom_import',
					'fields' => array(
						array(
							'id'         => 'opt-import-export',
							'type'       => 'import_export',
							'title'      => __( 'Import Export', 'listings' ),
							'subtitle'   => __( 'Save and restore your Redux options', 'listings' ),
							'full_width' => true,
						),
					),
					'icon'   => 'el-icon-refresh'
				)
			);

			/*
			 * automotive   = 0
			 * currency     = 10
			 * email        = 20
			 * custom_forms = 30
			 * inventory    = 40
			 * single       = 50
			 * portfolio    = 60
			 * revolution   = 70
			 * default      = 80
			 * file_import  = 90
			 * vin_import   = 100
			 * api          = 110
			 * import       = 120
			 */

			global $VIN_Import;

			// vin import settings
			$sections[100]['fields'][] = array(
				'title'   => __( 'VIN Import API', 'listings' ),
				'desc'    => __( 'Choose which API will be used for VIN imports.', 'listings' ),
				'type'    => 'select',
				'id'      => 'vin_import_api',
				'options' => $VIN_Import->get_all_installed_vin_apis(),
				'default' => 'edmunds'
			);

			$vin_import_fields = $VIN_Import->get_all_installed_vin_api_fields();
			if ( ! empty( $vin_import_fields ) ) {
				foreach ( $vin_import_fields as $vin_import_field ) {
					$sections[100]['fields'][] = array(
						'id'       => 'section-start',
						'type'     => 'section',
						'title'    => $vin_import_field['title'],
						'subtitle' => ( isset( $vin_import_field['desc'] ) && ! empty( $vin_import_field['desc'] ) ? $vin_import_field['desc'] : "" ),
						'indent'   => true
					);

					// add the specific fields
					if ( ! empty( $vin_import_field['fields'] ) ) {
						foreach ( $vin_import_field['fields'] as $vin_id => $vin_label ) {
							$sections[100]['fields'][] = array(
								'title' => $vin_label,
								'id'    => $vin_id,
								'type'  => 'text'
							);
						}
					}

					$sections[100]['fields'][] = array(
						'id'     => 'section-end',
						'type'   => 'section',
						'indent' => false
					);
				}
			}

			// import listing categories
			$show_listing_categories = ( get_option( 'show_listing_categories' ) ? get_option( 'show_listing_categories' ) : "show" );

			if ( $show_listing_categories != "hide" ) {
				$sections[120]['fields'][] = array(
					'id'      => 'import-demo-listing-categories',
					'type'    => 'custom_import',
					'title'   => __( 'Import Demo Listing Categories', 'listings' ),
					'desc'    => "<span class='remove_option_categories'>" . __( 'Click here to permanently remove this option from view.', 'listings' ) . "</span>",
					'options' => array(
						'1' => __( 'Import', 'listings' )
					),
					'class'   => 'import_listing_categories',
					'default' => 1
				);
			}

			// Change your opt_name to match where you want the data saved.
			$total_notices = 0;
			$news_data     = automotive_get_all_news();

			if ( isset( $news_data['listing_alerts'] ) && ! empty( $news_data['listing_alerts'] ) ) {
				$hidden_notices = get_option( 'automotive_hide_messages', [] );

				foreach ( $news_data['listing_alerts'] as $listing_alert ) {
					if ( ! in_array( md5( 'listing_alerts' . json_encode( $listing_alert ) ), $hidden_notices ) ) {
						$total_notices ++;
					}
				}
			}

			$args = array(
				"opt_name"        => "listing_wp",
				"menu_title"      => __( "Listing Options", 'listings' ) . ( $total_notices !== 0 ? '<span class="update-plugins"><span class="update-count">' . $total_notices . '</span></span>' : '' ),
				"page_slug"       => "listing_wp",
				"global_variable" => "listing_wp",
				"dev_mode"        => false,
				"use_cdn"         => false,
				"display_name"    => __( "Automotive Listings Plugin", "listings" ),
				"display_version" => AUTOMOTIVE_VERSION,
				"footer_credit"   => "Automotive by Theme Suite",
				"share_icons"     => array(
					array(
						'url'   => 'https://www.facebook.com/themesuite/',
						'title' => __( 'Like us on Facebook', 'listings' ),
						'icon'  => 'fa fa-facebook-official'
					),
					array(
						'url'   => 'https://twitter.com/themesuite',
						'title' => __( 'Follow us on Twitter', 'listings' ),
						'icon'  => 'fa fa-twitter'
					)
				)
			);

			$ReduxFramework = new ReduxFramework( apply_filters( 'automotive_listing_options', $sections ), $args );
		}

	}

	global $automotive_plugin_redux;

	$automotive_plugin_redux = new Redux_Framework_automotive_wp();
}
