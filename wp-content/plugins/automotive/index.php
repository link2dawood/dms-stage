<?php
/**
 * Plugin Name: Automotive Listings
 * Plugin URI: https://demo.themesuite.com/index.php?plugin=Automotive
 * Description: A well-designed inventory management system that is a breeze to setup and customize for your vehicle inventory. It also includes a completely customizable, filterable, and sortable Inventory Search to search your Vehicle Listings, as well as a complete Inventory Management System and Loan Calculator. Guaranteed compatibility with the <a href='https://themeforest.net/item/automotive-car-dealership-business-wordpress-theme/9210971?ref=themesuite' target='_blank'>Automotive Theme</a>
 * Version: 18.4.1
 * Author: Theme Suite
 * Author URI: https://www.themesuite.com
 * Text Domain: listings
 * Domain Path: /languages/
 */

define("AUTOMOTIVE_VERSION", "18.4.1");

if ( ! defined( 'ABSPATH' ) ) exit("<!-- " . AUTOMOTIVE_VERSION . " -->"); // Exit if accessed directly

// translation my (friend||péngyou||vriend||ven||ami||freund||dost||amico||jingu||prijátel||amigo||arkadas)
function automotive_load_textdomain() {
  load_plugin_textdomain('listings', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'automotive_load_textdomain' );

$lwp_options = get_option("listing_wp");
$icons       = array("facebook", "twitter", "youtube", "vimeo", "linkedin", "rss", "flickr", "skype", "google", "pinterest", "instagram", "yelp");

// disable redux notices.
$GLOBALS['redux_notice_check'] = true;


// bootstrap 4 notice if plugin is down level
function automotive_theme_down_level__notice() {
  $current_theme = wp_get_theme('automotive-wp');

    // test for theme function
	if(isset($current_theme->version) && !empty($current_theme->version) && function_exists("automotive_styles") && version_compare($current_theme->version, "8.0", "<")) { ?>
		<div class="notice notice-warning">
			<p><?php _e( 'We recently upgraded our Automotive Theme & Plugin to use Bootstrap 4 but it looks like the Automotive Theme is behind a version, please update this to at least version 8.0 for maximum compatibility.', 'automotive' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'automotive_theme_down_level__notice' );

//********************************************
//  Constant Paths
//***********************************************************
if( !defined("LISTING_HOME") ){
    define("LISTING_HOME", plugin_dir_path( __FILE__ ));
}

if( !defined('LISTING_DIR') ){
    define( 'LISTING_DIR', plugins_url() . '/automotive/' );
}

if( !defined('LISTING_ADMIN_DIR') ){
    define( 'LISTING_ADMIN_DIR', LISTING_DIR . 'admin/' );
}

if( !defined("ICON_DIR") ){
    define("ICON_DIR", LISTING_DIR . "images/icons/");
}

if( !defined("JS_DIR") ){
    define("JS_DIR", LISTING_DIR . "js/");
}

if( !defined("CSS_DIR") ){
    define("CSS_DIR", LISTING_DIR . "css/");
}

if( !defined("THUMBNAIL_URL") ){
    define("THUMBNAIL_URL", LISTING_DIR . "images/thumbnails/");
}

// overwrite hooks
$plugin_dir = trailingslashit(dirname(dirname(__FILE__)));
if(file_exists($plugin_dir . "auto_overwrite.php")){
	include_once($plugin_dir . "auto_overwrite.php");
}

//********************************************
//	Include files
//***********************************************************
include_once("classes/class.automotive_listing.php");
include_once("classes/class.listing.php");
include_once("classes/class.listing_template.php");
include_once("classes/class.import.php");

include_once("classes/class.vin_import_edmunds.php");
include_once("classes/class.vin_import_vinfreecheck.php");
include_once("classes/class.vin.php");

include_once("plugin_functions.php");
include_once("the_widgets.php");
include_once("styling.php");
include_once("shortcodes.php");
include_once("mobile-slideout-sidebar.php");

$listing_features = get_option("listing_features");
if(isset($listing_features) && $listing_features != "disabled"){
    include_once("portfolio.php");
    include_once("listing_categories.php");
    include_once("file_import.php");
    include_once("vin_import.php");
    include_once("meta_boxes.php");
    include_once("save.php");
    include_once("page-templates.php");
    include_once("status-report.php");
}

//********************************************
//	Plugin Updater
//***********************************************************
include_once('plugin_updater/plugin-update-checker.php');
$MyUpdateChecker = PucFactory::buildUpdateChecker(
    'https://files.themesuite.com/automotive-wp/secure/plugin.php',
    __FILE__,
    'automotive'
);

global $pagenow;
if( $pagenow == "plugins.php" ){

    function automotive_update_message( $plugin_data, $r ){
      $Automotive_Plugin = Automotive_Plugin();

	    $valid_themeforest_creds = $Automotive_Plugin->validate_themeforest_creds();

        if($valid_themeforest_creds){
            $major_release_check = wp_remote_get( "https://files.themesuite.com/automotive-wp/major.txt" );

            if(!empty($major_release_check) && !is_wp_error($major_release_check) && !empty($major_release_check['body'])){
                if(version_compare(AUTOMOTIVE_VERSION, $major_release_check['body'], "<")){
                    echo "<div class='automotive_update_message'>";
                    echo "<i class='fa fa-exclamation-triangle'></i> " . sprintf( __("This update of the plugin is a major release and will need some additional configuring in order to get your site back in working order, please read <a href='%s' target='_blank'>this</a> article before updating.", "listings"), "http://support.themesuite.com/?p=2806" );
                    echo "</div>";
                }
            }

        } elseif(!$valid_themeforest_creds){
            echo "<div class='automotive_update_message'>";
            echo "<i class='fa fa-exclamation-triangle'></i> " . sprintf( __("It doesn't look like you have entered a valid ThemeForest Username & API Key. To receive updates for the plugin you must validate this information in the <a href='%s'>Update Settings</a> section.", "listings"), admin_url( "admin.php?page=automotive_wp&tab=7" ) );
            echo "</div>";
        }
    }
    add_action( "in_plugin_update_message-automotive/index.php", "automotive_update_message", 20, 2);
}

$MyUpdateChecker->addQueryArgFilter('auto_filter_update_checks');

function auto_filter_update_checks($queryArgs) {
    global $awp_options;

    $themeforest_name = (isset($awp_options['themeforest_name']) && !empty($awp_options['themeforest_name']) ? $awp_options['themeforest_name'] : "");
    $themeforest_api  = (isset($awp_options['themeforest_api']) && !empty($awp_options['themeforest_api']) ? $awp_options['themeforest_api'] : "");

    if ( !empty($themeforest_name) && !empty($themeforest_api) ) {
        $queryArgs['themeforest_name'] = $themeforest_name;
        $queryArgs['themeforest_api']  = $themeforest_api;
    }

    // this class sometimes doesn't exist during site updates
    if ( ! class_exists( 'Automotive_Envato_Market_API' ) && file_exists( trailingslashit(get_template_directory()) . 'classes/envato_api.class.php') ) {
      require_once(trailingslashit(get_template_directory()) . 'classes/envato_api.class.php');
    }

    if(class_exists('Automotive_Envato_Market_API')){
      Automotive_Envato_Market_API::instance();

      $theme_details = get_option('envato-market-ts');

      if(isset($theme_details['theme']['purchase_code'])){
        $queryArgs['purchase_code'] = $theme_details['theme']['purchase_code'];
        $queryArgs['bearer']        = $theme_details['oauth']['themesuite']['access_token'];
      }
    }

    return $queryArgs;
}

if(isset($_GET['action']) && $_GET['action'] == "upgrade-plugin" && isset($_GET['plugin']) && $_GET['plugin'] == "automotive/index.php"){
	delete_option('_site_transient_update_plugins');
}

//********************************************
//  init automotive classes
//***********************************************************
global $Listing, $VIN_Import;

$Listing          = Automotive_Plugin();
// $Listing          = new Listing();
$Listing_Template = new Listing_Template();
$VIN_Import       = new VIN_Import();

function listing_fix_init(){
	global $lwp_options;

	$Automotive_Plugin = Automotive_Plugin();

	$lwp_options = get_option("listing_wp");
  $listing_features = get_option( "listing_features" );

  // schedule tasks if required
  if(isset($lwp_options['delete_sold_listings']) && $lwp_options['delete_sold_listings']){
    if (! wp_next_scheduled ( 'automotive_listings_tasks' )) {
      wp_schedule_event( time(), 'daily', 'automotive_listings_tasks' );
    }
  } else {
    wp_clear_scheduled_hook( 'automotive_listings_tasks' );
  }

	/* image sizes */
  if ( ! isset( $listing_features ) || $listing_features != "disabled" ) {
    $Automotive_Plugin->automotive_image_sizes();
  }

	/* convert old data to new system */
	$Automotive_Plugin->convert_old_data();

  /* Generate Slugs For Listing Categories */
  $Automotive_Plugin->convert_listing_data();

	/* Generate dependancies for existing users */
	$Automotive_Plugin->generate_dependancy_option();
}
add_action("init", "listing_fix_init", 1);

// need to wait a little longer than 'init' for post info is available
function automotive_plugin_conditional_actions(){
  if(is_singular('listings')){
    add_action( "wp_footer", array(Automotive_Plugin(), "photoswipe_js_element") );
  }
}
add_action('wp', 'automotive_plugin_conditional_actions');


function automotive_listings_deactivation() {
    wp_clear_scheduled_hook( 'automotive_listings_tasks' );
}
register_deactivation_hook( __FILE__, 'automotive_listings_deactivation' );

function automotive_listings_daily_tasks() {
  // find any sold vehicles without a date set
  $all_listings = new WP_Query(array(
    'post_type'      => 'listings',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'   => 'car_sold',
        'value' => '1'
      ),
      array(
        'key'     => 'car_sold_date',
        'compare' => 'NOT EXISTS'
      )
    )
  ));

  $all_listings = $all_listings->get_posts();

  if(!empty($all_listings)){
    $days = (int)automotive_listing_get_option('delete_sold_listings_days', 3);

    foreach( $all_listings as $listing ) {
      update_post_meta($listing->ID, 'car_sold_date', time() + ($days * DAY_IN_SECONDS));
    }
  }

  // search for listings with expired date times
  $all_listings_args = array(
    'post_type'      => 'listings',
    'posts_per_page' => -1,
    'meta_query'     => array(
      'relation' => 'AND',
      array(
        'key'   => 'car_sold',
        'value' => '1'
      ),
      array(
        'key'     => 'car_sold_date',
        'compare' => '<=',
        'value'   => time()
      )
    )
  );
  $all_listings = new WP_Query($all_listings_args);
  $all_listings = $all_listings->get_posts();

  $is_wpml_active = Automotive_Plugin()->is_wpml_active();
  $languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );

  foreach( $all_listings as $listing ) {
    if($is_wpml_active){
      if ( ! empty( $languages ) ) {
        foreach ( $languages as $lang_code => $lang_info ) {
          $translated_id = apply_filters( 'wpml_object_id', $listing->ID, 'listings', false, $lang_code );

          wp_trash_post($listing->ID);
        }
      } else {
        wp_trash_post($listing->ID);
      }
    } else {
      wp_trash_post($listing->ID);
    }
  }

}
add_action( 'automotive_listings_tasks', 'automotive_listings_daily_tasks', 10, 2 );
