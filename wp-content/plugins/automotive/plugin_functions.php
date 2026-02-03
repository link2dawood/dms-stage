<?php

// orderby WPML get_auto_listing_categories_option
if(!function_exists("get_auto_orderby_option")){
	function get_auto_orderby_option(){
		$option = "listing_orderby";

		return $option;
	}
}

if(!function_exists('automotive_listing_get_option')){
	function automotive_listing_get_option($option, $default_value = null){
		global $lwp_options;

		return (isset($lwp_options[$option]) && (!empty($lwp_options[$option]) || $lwp_options[$option] === false || $lwp_options[$option] == "0") ? $lwp_options[$option] : $default_value);
	}
}

if(!function_exists('automotive_theme_get_option')){
	function automotive_theme_get_option($option, $default_value = null){
		global $awp_options;

		return (isset($awp_options[$option]) && (!empty($awp_options[$option]) || $awp_options[$option] === false || $awp_options[$option] == "0") ? $awp_options[$option] : $default_value);
	}
}

if(!function_exists("get_auto_orderby")){
	function get_auto_orderby(){
		$option = get_auto_orderby_option();

		return get_option($option);
	}
}

// backwards compatibility for extensions
function get_listing_categories_to_redux_select(){
    Automotive_Plugin()->get_listing_categories_to_redux_select();
}


function automotive_listing_sidebar_before_widget($value){ return '<div class="side-widget padding-bottom-50">'; }
function automotive_listing_sidebar_after_widget($value){ return '<div class="clearfix"></div></div>'; }
function automotive_listing_sidebar_before_title($value){	return '<h3 class="side-widget-title margin-bottom-25">'; }
function automotive_listing_sidebar_after_title($value){ return '</h3>'; }

add_filter('automotive_listing_sidebar_before_widget', 'automotive_listing_sidebar_before_widget');
add_filter('automotive_listing_sidebar_after_widget', 'automotive_listing_sidebar_after_widget');
add_filter('automotive_listing_sidebar_before_title', 'automotive_listing_sidebar_before_title');
add_filter('automotive_listing_sidebar_after_title', 'automotive_listing_sidebar_after_title');

function automotive_inventory_sidebar_before_widget($value) { return '<div class="side-widget padding-bottom-50">'; }
function automotive_inventory_sidebar_after_widget($value) { return '<div class="clearfix"></div></div>'; }
function automotive_inventory_sidebar_before_title($value) { return '<h3 class="side-widget-title margin-bottom-25">'; }
function automotive_inventory_sidebar_after_title($value) { return '</h3>'; }

add_filter('automotive_inventory_sidebar_before_widget', 'automotive_inventory_sidebar_before_widget');
add_filter('automotive_inventory_sidebar_after_widget', 'automotive_inventory_sidebar_after_widget');
add_filter('automotive_inventory_sidebar_before_title', 'automotive_inventory_sidebar_before_title');
add_filter('automotive_inventory_sidebar_after_title', 'automotive_inventory_sidebar_after_title');


//********************************************
//	Load Elementor
//***********************************************************
require_once('widgets/general-functions.php');

add_action( 'elementor/editor/before_enqueue_scripts',  function(){
  //wp_enqueue_script( 'selectbox', JS_DIR . "jquery.selectbox.js", array('jquery'), AUTOMOTIVE_VERSION, true );
  wp_enqueue_script( 'tomselect', JS_DIR . "tom-select.complete.min.js", array('jquery'), AUTOMOTIVE_VERSION, true );
	wp_enqueue_script( 'elementor-test', LISTING_DIR . 'elementor-test.js', array('jquery', 'tomselect') );
});

function automotive_projects_loop_option(){
	$options = array();

	$project_terms = get_terms( 'project-type' );
	$projects      = array();

	if ( ! empty( $project_terms ) ) {
	  foreach ( $project_terms as $key => $value ) {
	    if ( isset( $value->name ) ) {
	      $projects[ $value->name ] = $value->name;
	    }
	  }
	}

	if(!empty($projects)){
		$options[] = array(
			'id'      => 'categories',
			'type'    => 'checkbox',
			'label'   => __('Categories'),
			'desc'    => 'Display these sortable categories',
			'options' => $projects
		);
	}

	return $options;
}

function automotive_portfolios_loop_option(){
	$options = array();

	$portfolio_terms = get_terms( 'portfolio_in' );
	$portfolios      = array();

	if ( ! empty( $portfolio_terms ) ) {
	  foreach ( $portfolio_terms as $key => $value ) {
	    if ( isset( $value->name ) && isset( $value->term_id ) ) {
	      $portfolios[ $value->term_id ] = $value->name;
	    }
	  }
	}

	if(!empty($portfolios)){
		$options[] = array(
			'id'      => 'portfolio',
			'type'    => 'select',
			'label'   => __('Portfolio'),
			'desc'    => 'Which portfolio to display',
			'options' => $portfolios
		);
	}

	return $options;
}

function automotive_categories_loop_options(){
	$Listing = Automotive_Plugin();

	$options    = array();
	$categories = $Listing->get_listing_categories();

	$use_categories = $min_categories = array();
	if ( ! empty( $categories ) ) {
	  foreach ( $categories as $category ) {
	    $use_categories[ $category['slug'] ] = $category['singular'];
	    $min_categories[ $category['slug'] ] = $category['singular'];
	  }
	}

	// add search box
	$use_categories["Search"] = "Search Box";

	if(!empty($use_categories)){
		$options[] = array(
			'id'      => 'column_1',
			'type'    => 'checkbox',
			'label'   => __('Column 1'),
			'desc'    => 'Dropdowns that will be shown in the first column (leave column 2 blank to extend this column to fullwidth)',
			'options' => $use_categories
		);

		$options[] = array(
			'id'      => 'column_2',
			'type'    => 'checkbox',
			'label'   => __('Column 2'),
			'desc'    => 'Dropdowns that will be shown in the second column',
			'options' => $use_categories
		);

		$options[] = array(
			'id'      => 'min_max',
			'type'    => 'checkbox',
			'label'   => __('Min/Max Values'),
			'desc'    => 'Only choose values you checked in the previous options and only for categories that use numbers as values',
			'options' => $use_categories
		);
	}

	return $options;
}

function automotive_inventory_loop_options(){
	$Listing = Automotive_Plugin();

	$inventory_vc = array();
	$categories   = $Listing->get_listing_categories();

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {

			if ( isset( $category['slug'] ) && ! empty( $category['slug'] ) ) {
				$options = array("None" => 'none');
				$safe    = $category['slug'];

				if ( ! empty( $category['terms'] ) && is_array($category['terms']) ) {
					foreach ( $category['terms'] as $key => $term ) {
						$options[ ( isset( $category['compare_value'] ) && $category['compare_value'] != "=" ? html_entity_decode( $category['compare_value'] ) . " " : "" ) . $term ] = $key;
					}
				}

				// $options = array_merge( $options, array( __( "None", "listings" ) => "none" ) );

	      $param_name = ( $safe == "year" ? "yr" : $safe );

	      $inventory_vc[$param_name] = [
	        'id'          => $param_name,
	        'type'        => 'select',
	        'label'       => $category['plural'],
	        'options'     => array_flip($options),
	      ];
			}
		}
	}

	return $inventory_vc;
}

function mergepress_option_type_normalize($type, $pagebuilder){
	if($pagebuilder === 'elementor'){
		if($type === 'text'){
			return \Elementor\Controls_Manager::TEXT;
		} elseif($type === 'select'){
			return \Elementor\Controls_Manager::SELECT;
		} elseif($type === 'color'){
			return \Elementor\Controls_Manager::COLOR;
		} elseif($type === 'textarea'){
			return \Elementor\Controls_Manager::TEXTAREA;
		} elseif($type === 'number'){
			return \Elementor\Controls_Manager::NUMBER;
		} elseif($type === 'wysiwyg'){
			return \Elementor\Controls_Manager::WYSIWYG;
		} elseif($type === 'url'){
			return \Elementor\Controls_Manager::URL;
		} elseif($type === 'image'){
			return \Elementor\Controls_Manager::MEDIA;
		} elseif($type === 'html'){
			return \Elementor\Controls_Manager::CODE;
		} elseif($type === 'checkbox'){
			return \Elementor\Controls_Manager::SELECT2;
		}
	}

	return $type;
}


//********************************************
//	Register Sidebar
//***********************************************************
function automotive_init_sidebar(){
	$listing_args = array(
		'name'          => __( 'Listings Sidebar', 'listings' ),
		'id'            => 'listing_sidebar',
		'description'   => '',
	  'class'         => '',
		'before_widget' => apply_filters('automotive_inventory_sidebar_before_widget', ''),
		'after_widget'  => apply_filters('automotive_inventory_sidebar_after_widget', ''),
		'before_title'  => apply_filters('automotive_inventory_sidebar_before_title', ''),
		'after_title'   => apply_filters('automotive_inventory_sidebar_after_title', '')
	);

	$single_listing_args = array(
		'name'          => __( 'Single Listing Sidebar', 'listings' ),
		'id'            => 'single_listing_sidebar',
		'description'   => '',
	  'class'         => '',
		'before_widget' => apply_filters('automotive_listing_sidebar_before_widget', ''),
		'after_widget'  => apply_filters('automotive_listing_sidebar_after_widget', ''),
		'before_title'  => apply_filters('automotive_listing_sidebar_before_title', ''),
		'after_title'   => apply_filters('automotive_listing_sidebar_after_title', '')
	);

	register_sidebar( $listing_args );
	register_sidebar( $single_listing_args );
}
add_action('widgets_init', 'automotive_init_sidebar');

function automotive_get_all_news(){
  $auto_news_transient_key = 'automotive_news_check';

	if ( false === ( $news_data = get_transient( $auto_news_transient_key ) ) ) {
		$news_url     = 'ht' . 'tps:' . '//' . 'th' . 'em' . 'esu' . 'ite' . '.co' . 'm/ve' . 'rs' . 'ion/' . 'auto' . 'mot' . 'ive-' . 'wp/' . 'new' . 's.p' . 'hp';
		$news_request = wp_remote_get( $news_url );

		$news_data = wp_remote_retrieve_body( $news_request );

		if ( ! empty( $news_data ) ) {
			$news_data = json_decode( $news_data, true );
		}

		set_transient( $auto_news_transient_key, $news_data, DAY_IN_SECONDS );
	}

  return $news_data;
}

function automotive_plugin_news_detection() {
	$news_data = automotive_get_all_news();

	if ( is_array( $news_data ) ) {
    $automotive_plugin_alert = '';
    $is_listing_option_page = (isset($_GET['page']) && $_GET['page'] === 'listing_wp');
    $alert_prefix = ($is_listing_option_page ? 'listing_alerts' : 'alerts');

    if(!empty($news_data[$alert_prefix])){
      foreach($news_data[$alert_prefix] as $alert){
        $message_key_id = md5( $alert_prefix . json_encode( $alert ) );

        ob_start();
        Automotive_Plugin()->automotive_message(
          $alert['title'],
          $alert['text'],
          $alert['type'],
          $message_key_id,
          true,
          true
        );
        $automotive_plugin_alert .= ob_get_clean();

      }
    }

    if(!empty($automotive_plugin_alert)) {
      echo $automotive_plugin_alert;
    }
	}
}

add_action( 'admin_notices', 'automotive_plugin_news_detection' );
//********************************************
//  Delete associated images
//***********************************************************
function delete_auto_images( $pid ) {
	$post_type   = get_post_type($pid);
	$original_id = apply_filters('wpml_object_id', $pid, 'page', true);

	if($post_type == "listings" && automotive_listing_get_option('delete_associated', false)){
		$gallery_images = get_post_meta( $pid, "gallery_images", true );
		$is_wpml_active = Automotive_Plugin()->is_wpml_active();

		if(!empty($gallery_images) && (!$is_wpml_active || ($is_wpml_active && $original_id === $pid))){
			foreach($gallery_images as $gid){
				wp_delete_attachment( $gid, true );
			}
		}
	}
}
add_action( 'before_delete_post', 'delete_auto_images' );

function car_listing_container($layout, $mobile_sidebar = false){
	$return = array();

	if($layout == "boxed_fullwidth"){
		$return['start'] = '<div class="inventory_box car_listings boxed boxed_full row">';
		$return['end']   = '</div>';
	} elseif($layout == "wide_fullwidth"){
		$return['start'] = '<div class="content-wrap car_listings row">';
		$return['end']   = '</div>';
	} elseif($layout == "boxed_left"){
		$return['start'] = '<div class="car_listings boxed boxed_left col-lg-9 col-md-12 ' . (!$mobile_sidebar && !is_rtl() ? 'col-lg-push-3 ' : '') . 'row">';
		$return['end']   = '</div>';
	} elseif($layout == "boxed_right"){
		$return['start'] = '<div class="car_listings boxed boxed_right col-lg-9 col-md-12 row">';
		$return['end']   = '</div>';
	} elseif($layout == "wide_left"){
		$return['start'] = '<div class="inventory-wide-sidebar-left col-lg-9 col-md-12 ' . (!$mobile_sidebar && !is_rtl() ? 'col-lg-push-3 ' : '') . 'car_listings"><div class="sidebar">';
		$return['end']   = '</div></div>';
	} elseif($layout == "wide_right"){
		$return['start'] = '<div class="inventory-wide-sidebar-right car_listings col-lg-9 col-md-12"><div class="sidebar">';
		$return['end']   = '</div></div>';
	} else {
		$return['start'] = '<div class="inventory_box car_listings">';
		$return['end']   = '</div>';
	}

	return $return;
}

if(!function_exists("listing_youtube_video")){
	function listing_youtube_video(){
		return '<div id="youtube_video">
			<iframe width="560" height="315" src="about:blank" allowfullscreen style="width: 560px; height: 315px; border: 0;"></iframe>
		</div>';
	}
}

if(!function_exists("automotive_listing_remove_non_numerical")){
	function automotive_listing_remove_non_numerical($array){
		if(!empty($array)){
			foreach ($array as $key => $value) {
			  if (!is_int($key)) {
			    unset($array[$key]);
			  }
			}
		}

		return $array;
	}
}

if(!function_exists("listing_template")){
	function listing_template($layout, $is_ajax = false, $ajax_array = false){
		// global $Listing, $Listing_Template;
		$Automotive_Plugin          = Automotive_Plugin();
		$Automotive_Plugin_Template = Automotive_Plugin_Template();

		if($is_ajax == false) { ?>
			<div class="inner-page row">
				<div>
				<?php
		}

        $Automotive_Plugin->set_current_query_info($_GET, $ajax_array);
        $listings = $Automotive_Plugin->current_query_info['listings'];

				do_action('automotive_before_inventory');

        if($is_ajax == false){
            echo $Automotive_Plugin_Template->locate_template("listing_view", array("layout" => $layout));
            echo $Automotive_Plugin_Template->locate_template("listing_filter_sort");
        }

        $container = car_listing_container($layout);

        echo (!$is_ajax ? "<div class='row generate_new'>" : "") . $container['start'];

        if(!empty($listings)){
            foreach($listings as $listing){
                echo $Automotive_Plugin_Template->locate_template("inventory_listing", array("id" => $listing->ID, "layout" => $layout));
            }
        } else {
            echo do_shortcode('[alert type="2" close="No"]' . __("No listings found", "listings") . '[/alert]') . "<div class='clearfix'></div>";
        }

        echo "<div class=\"clearfix\"></div>";
        echo $container['end'];

        if($layout == "boxed_left"){
            echo "<div class=\"col-xl-3 col-lg-3 " . (!is_rtl() ? "order-xl-first order-lg-first " : "") . "col-md-12 col-sm-12 left-sidebar side-content listing-sidebar\">";
            dynamic_sidebar("listing_sidebar");
            echo "</div>";
        } elseif($layout == "boxed_right"){
            echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
            dynamic_sidebar("listing_sidebar");
            echo "</div>";
        } elseif($layout == "wide_left"){
            echo "<div class=\"col-xl-3 col-lg-3 " . (!is_rtl() ? "order-xl-first order-lg-first " : "") . "col-md-12 left-sidebar side-content listing-sidebar\">";
            dynamic_sidebar("listing_sidebar");
            echo "</div>";
        } elseif($layout == "wide_right"){
            echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
            dynamic_sidebar("listing_sidebar");
            echo "</div>";
        }

        if($is_ajax == false){
            echo bottom_page_box($layout);
            echo "</div>";
        }

        echo (!$is_ajax ? "</div></div>" . listing_youtube_video() : "");

				do_action('automotive_after_inventory');
	}
}

// create new inventory listings for select view buttons
function generate_new_view(){
	$Automotive_Plugin = Automotive_Plugin();
	$layout            = sanitize_text_field($_POST['layout']);
	$page              = (isset($_POST['page']) && !empty($_POST['page']) ? (int)$_POST['page'] : 1);
	$params            = json_decode(stripslashes($_POST['params']), true);

	// paged fix
	if(isset($page) && !empty($page)){
		$params['paged'] = $page;
	}

	$Automotive_Plugin->set_current_query_info($params);

	ob_start();
	listing_template($layout, true, $params);
	$html = ob_get_clean();

	echo wp_json_encode( (array(
		"html"        => $html,
    "top_page"    => page_of_box($page),
    "bottom_page" => bottom_page_box(false, $page),
	)));

	die;
}
add_action("wp_ajax_generate_new_view", "generate_new_view");
add_action("wp_ajax_nopriv_generate_new_view", "generate_new_view");

if(!function_exists("automotive_forms_footer")){
	function automotive_forms_footer(){
		$Automotive_Plugin_Template = Automotive_Plugin_Template();

		echo $Automotive_Plugin_Template->locate_template("footer_forms");
	}
}
add_action("wp_footer", "automotive_forms_footer");

if(!function_exists('automotive_strtolower')){
	function automotive_strtolower($str){
		return (function_exists('mb_strtolower') ? mb_strtolower($str) : strtolower($str));
	}
}

if(!function_exists('automotive_iconv')){
	function automotive_iconv($in_charset, $out_charset, $str){
    // GoDaddy fix because of their custom iconv implementation which breaks iconv() and these params...
		if((defined('ICONV_IMPL') ? ICONV_IMPL : false) === 'unknown'){
			$out_charset = str_replace("//TRANSLIT//IGNORE", "", $out_charset);
		}

		return (function_exists('iconv') ? iconv($in_charset, $out_charset, $str) : $str);
	}
}

if(!function_exists("D")){
	function D($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}
}

function automotive_reorder_listing_tabs_options($tabs){
	$inventory_custom_tab_order = automotive_listing_get_option('inventory_custom_tab_order', false);

	if($inventory_custom_tab_order){
		$inventory_tab_order            = automotive_listing_get_option('inventory_tab_order', false);
		$new_tabs                       = array();

		if(!empty($inventory_tab_order)){
			foreach($inventory_tab_order as $tab_key => $tab_label){
					$new_tabs[$tab_key] = $tabs[$tab_key];
			}

			return $new_tabs;
		}
	}

	return $tabs;
}
add_filter('automotive_listing_tabs', 'automotive_reorder_listing_tabs_options');

function automotive_listing_tab_display_options($display){
	$inventory_custom_tab_order = automotive_listing_get_option('inventory_custom_tab_order', false);

	if($inventory_custom_tab_order){
		$inventory_tab_order           = automotive_listing_get_option('inventory_tab_order', false);
		$automotive_listing_tab_display = array();

		$tab_mapping = array(
			'vehicle_overview'         => 'first_tab' ,
			'multi_options'            => 'second_tab',
			'technical_specifications' => 'third_tab' ,
			'vehicle_location'         => 'fourth_tab',
			'other_comments'           => 'fifth_tab',
		);

		if(!empty($inventory_tab_order)){
			foreach($inventory_tab_order as $tab_key => $tab_label){
					$automotive_listing_tab_display[] = automotive_listing_get_option($tab_mapping[$tab_key], '');
			}

			return $automotive_listing_tab_display;
		}
	}

	return $display;
}
add_filter('automotive_listing_tab_display', 'automotive_listing_tab_display_options');

//********************************************
//	Get Font Awesome Icons
//***********************************************************
if(!function_exists("get_fontawesome_icons")){
	function get_fontawesome_icons() {

    return array (
        'fab fa-500px'                               => __( '500px', 'buildr' ),
        'fab fa-accessible-icon'                     => __( 'accessible-icon', 'buildr' ),
        'fab fa-accusoft'                            => __( 'accusoft', 'buildr' ),
        'fas fa-address-book'                        => __( 'address-book', 'buildr' ),
        'far fa-address-book'                        => __( 'address-book', 'buildr' ),
        'fas fa-address-card'                        => __( 'address-card', 'buildr' ),
        'far fa-address-card'                        => __( 'address-card', 'buildr' ),
        'fas fa-adjust'                              => __( 'adjust', 'buildr' ),
        'fab fa-adn'                                 => __( 'adn', 'buildr' ),
        'fab fa-adversal'                            => __( 'adversal', 'buildr' ),
        'fab fa-affiliatetheme'                      => __( 'affiliatetheme', 'buildr' ),
        'fab fa-algolia'                             => __( 'algolia', 'buildr' ),
        'fas fa-align-center'                        => __( 'align-center', 'buildr' ),
        'fas fa-align-justify'                       => __( 'align-justify', 'buildr' ),
        'fas fa-align-left'                          => __( 'align-left', 'buildr' ),
        'fas fa-align-right'                         => __( 'align-right', 'buildr' ),
        'fas fa-allergies'                           => __( 'allergies', 'buildr' ),
        'fab fa-amazon'                              => __( 'amazon', 'buildr' ),
        'fab fa-amazon-pay'                          => __( 'amazon-pay', 'buildr' ),
        'fas fa-ambulance'                           => __( 'ambulance', 'buildr' ),
        'fas fa-american-sign-language-interpreting' => __( 'american-sign-language-interpreting', 'buildr' ),
        'fab fa-amilia'                              => __( 'amilia', 'buildr' ),
        'fas fa-anchor'                              => __( 'anchor', 'buildr' ),
        'fab fa-android'                             => __( 'android', 'buildr' ),
        'fab fa-angellist'                           => __( 'angellist', 'buildr' ),
        'fas fa-angle-double-down'                   => __( 'angle-double-down', 'buildr' ),
        'fas fa-angle-double-left'                   => __( 'angle-double-left', 'buildr' ),
        'fas fa-angle-double-right'                  => __( 'angle-double-right', 'buildr' ),
        'fas fa-angle-double-up'                     => __( 'angle-double-up', 'buildr' ),
        'fas fa-angle-down'                          => __( 'angle-down', 'buildr' ),
        'fas fa-angle-left'                          => __( 'angle-left', 'buildr' ),
        'fas fa-angle-right'                         => __( 'angle-right', 'buildr' ),
        'fas fa-angle-up'                            => __( 'angle-up', 'buildr' ),
        'fab fa-angrycreative'                       => __( 'angrycreative', 'buildr' ),
        'fab fa-angular'                             => __( 'angular', 'buildr' ),
        'fab fa-app-store'                           => __( 'app-store', 'buildr' ),
        'fab fa-app-store-ios'                       => __( 'app-store-ios', 'buildr' ),
        'fab fa-apper'                               => __( 'apper', 'buildr' ),
        'fab fa-apple'                               => __( 'apple', 'buildr' ),
        'fab fa-apple-pay'                           => __( 'apple-pay', 'buildr' ),
        'fas fa-archive'                             => __( 'archive', 'buildr' ),
        'fas fa-arrow-alt-circle-down'               => __( 'arrow-alt-circle-down', 'buildr' ),
        'far fa-arrow-alt-circle-down'               => __( 'arrow-alt-circle-down', 'buildr' ),
        'fas fa-arrow-alt-circle-left'               => __( 'arrow-alt-circle-left', 'buildr' ),
        'far fa-arrow-alt-circle-left'               => __( 'arrow-alt-circle-left', 'buildr' ),
        'fas fa-arrow-alt-circle-right'              => __( 'arrow-alt-circle-right', 'buildr' ),
        'far fa-arrow-alt-circle-right'              => __( 'arrow-alt-circle-right', 'buildr' ),
        'fas fa-arrow-alt-circle-up'                 => __( 'arrow-alt-circle-up', 'buildr' ),
        'far fa-arrow-alt-circle-up'                 => __( 'arrow-alt-circle-up', 'buildr' ),
        'fas fa-arrow-circle-down'                   => __( 'arrow-circle-down', 'buildr' ),
        'fas fa-arrow-circle-left'                   => __( 'arrow-circle-left', 'buildr' ),
        'fas fa-arrow-circle-right'                  => __( 'arrow-circle-right', 'buildr' ),
        'fas fa-arrow-circle-up'                     => __( 'arrow-circle-up', 'buildr' ),
        'fas fa-arrow-down'                          => __( 'arrow-down', 'buildr' ),
        'fas fa-arrow-left'                          => __( 'arrow-left', 'buildr' ),
        'fas fa-arrow-right'                         => __( 'arrow-right', 'buildr' ),
        'fas fa-arrow-up'                            => __( 'arrow-up', 'buildr' ),
        'fas fa-arrows-alt'                          => __( 'arrows-alt', 'buildr' ),
        'fas fa-arrows-alt-h'                        => __( 'arrows-alt-h', 'buildr' ),
        'fas fa-arrows-alt-v'                        => __( 'arrows-alt-v', 'buildr' ),
        'fas fa-assistive-listening-systems'         => __( 'assistive-listening-systems', 'buildr' ),
        'fas fa-asterisk'                            => __( 'asterisk', 'buildr' ),
        'fab fa-asymmetrik'                          => __( 'asymmetrik', 'buildr' ),
        'fas fa-at'                                  => __( 'at', 'buildr' ),
        'fab fa-audible'                             => __( 'audible', 'buildr' ),
        'fas fa-audio-description'                   => __( 'audio-description', 'buildr' ),
        'fab fa-autoprefixer'                        => __( 'autoprefixer', 'buildr' ),
        'fab fa-avianex'                             => __( 'avianex', 'buildr' ),
        'fab fa-aviato'                              => __( 'aviato', 'buildr' ),
        'fab fa-aws'                                 => __( 'aws', 'buildr' ),
        'fas fa-backward'                            => __( 'backward', 'buildr' ),
        'fas fa-balance-scale'                       => __( 'balance-scale', 'buildr' ),
        'fas fa-ban'                                 => __( 'ban', 'buildr' ),
        'fas fa-band-aid'                            => __( 'band-aid', 'buildr' ),
        'fab fa-bandcamp'                            => __( 'bandcamp', 'buildr' ),
        'fas fa-barcode'                             => __( 'barcode', 'buildr' ),
        'fas fa-bars'                                => __( 'bars', 'buildr' ),
        'fas fa-baseball-ball'                       => __( 'baseball-ball', 'buildr' ),
        'fas fa-basketball-ball'                     => __( 'basketball-ball', 'buildr' ),
        'fas fa-bath'                                => __( 'bath', 'buildr' ),
        'fas fa-battery-empty'                       => __( 'battery-empty', 'buildr' ),
        'fas fa-battery-full'                        => __( 'battery-full', 'buildr' ),
        'fas fa-battery-half'                        => __( 'battery-half', 'buildr' ),
        'fas fa-battery-quarter'                     => __( 'battery-quarter', 'buildr' ),
        'fas fa-battery-three-quarters'              => __( 'battery-three-quarters', 'buildr' ),
        'fas fa-bed'                                 => __( 'bed', 'buildr' ),
        'fas fa-beer'                                => __( 'beer', 'buildr' ),
        'fab fa-behance'                             => __( 'behance', 'buildr' ),
        'fab fa-behance-square'                      => __( 'behance-square', 'buildr' ),
        'fas fa-bell'                                => __( 'bell', 'buildr' ),
        'far fa-bell'                                => __( 'bell', 'buildr' ),
        'fas fa-bell-slash'                          => __( 'bell-slash', 'buildr' ),
        'far fa-bell-slash'                          => __( 'bell-slash', 'buildr' ),
        'fas fa-bicycle'                             => __( 'bicycle', 'buildr' ),
        'fab fa-bimobject'                           => __( 'bimobject', 'buildr' ),
        'fas fa-binoculars'                          => __( 'binoculars', 'buildr' ),
        'fas fa-birthday-cake'                       => __( 'birthday-cake', 'buildr' ),
        'fab fa-bitbucket'                           => __( 'bitbucket', 'buildr' ),
        'fab fa-bitcoin'                             => __( 'bitcoin', 'buildr' ),
        'fab fa-bity'                                => __( 'bity', 'buildr' ),
        'fab fa-black-tie'                           => __( 'black-tie', 'buildr' ),
        'fab fa-blackberry'                          => __( 'blackberry', 'buildr' ),
        'fas fa-blind'                               => __( 'blind', 'buildr' ),
        'fab fa-blogger'                             => __( 'blogger', 'buildr' ),
        'fab fa-blogger-b'                           => __( 'blogger-b', 'buildr' ),
        'fab fa-bluetooth'                           => __( 'bluetooth', 'buildr' ),
        'fab fa-bluetooth-b'                         => __( 'bluetooth-b', 'buildr' ),
        'fas fa-bold'                                => __( 'bold', 'buildr' ),
        'fas fa-bolt'                                => __( 'bolt', 'buildr' ),
        'fas fa-bomb'                                => __( 'bomb', 'buildr' ),
        'fas fa-book'                                => __( 'book', 'buildr' ),
        'fas fa-bookmark'                            => __( 'bookmark', 'buildr' ),
        'far fa-bookmark'                            => __( 'bookmark', 'buildr' ),
        'fas fa-bowling-ball'                        => __( 'bowling-ball', 'buildr' ),
        'fas fa-box'                                 => __( 'box', 'buildr' ),
        'fas fa-box-open'                            => __( 'box-open', 'buildr' ),
        'fas fa-boxes'                               => __( 'boxes', 'buildr' ),
        'fas fa-braille'                             => __( 'braille', 'buildr' ),
        'fas fa-briefcase'                           => __( 'briefcase', 'buildr' ),
        'fas fa-briefcase-medical'                   => __( 'briefcase-medical', 'buildr' ),
        'fab fa-btc'                                 => __( 'btc', 'buildr' ),
        'fas fa-bug'                                 => __( 'bug', 'buildr' ),
        'fas fa-building'                            => __( 'building', 'buildr' ),
        'far fa-building'                            => __( 'building', 'buildr' ),
        'fas fa-bullhorn'                            => __( 'bullhorn', 'buildr' ),
        'fas fa-bullseye'                            => __( 'bullseye', 'buildr' ),
        'fas fa-burn'                                => __( 'burn', 'buildr' ),
        'fab fa-buromobelexperte'                    => __( 'buromobelexperte', 'buildr' ),
        'fas fa-bus'                                 => __( 'bus', 'buildr' ),
        'fab fa-buysellads'                          => __( 'buysellads', 'buildr' ),
        'fas fa-calculator'                          => __( 'calculator', 'buildr' ),
        'fas fa-calendar'                            => __( 'calendar', 'buildr' ),
        'far fa-calendar'                            => __( 'calendar', 'buildr' ),
        'fas fa-calendar-alt'                        => __( 'calendar-alt', 'buildr' ),
        'far fa-calendar-alt'                        => __( 'calendar-alt', 'buildr' ),
        'fas fa-calendar-check'                      => __( 'calendar-check', 'buildr' ),
        'far fa-calendar-check'                      => __( 'calendar-check', 'buildr' ),
        'fas fa-calendar-minus'                      => __( 'calendar-minus', 'buildr' ),
        'far fa-calendar-minus'                      => __( 'calendar-minus', 'buildr' ),
        'fas fa-calendar-plus'                       => __( 'calendar-plus', 'buildr' ),
        'far fa-calendar-plus'                       => __( 'calendar-plus', 'buildr' ),
        'fas fa-calendar-times'                      => __( 'calendar-times', 'buildr' ),
        'far fa-calendar-times'                      => __( 'calendar-times', 'buildr' ),
        'fas fa-camera'                              => __( 'camera', 'buildr' ),
        'fas fa-camera-retro'                        => __( 'camera-retro', 'buildr' ),
        'fas fa-capsules'                            => __( 'capsules', 'buildr' ),
        'fas fa-car'                                 => __( 'car', 'buildr' ),
        'fas fa-caret-down'                          => __( 'caret-down', 'buildr' ),
        'fas fa-caret-left'                          => __( 'caret-left', 'buildr' ),
        'fas fa-caret-right'                         => __( 'caret-right', 'buildr' ),
        'fas fa-caret-square-down'                   => __( 'caret-square-down', 'buildr' ),
        'far fa-caret-square-down'                   => __( 'caret-square-down', 'buildr' ),
        'fas fa-caret-square-left'                   => __( 'caret-square-left', 'buildr' ),
        'far fa-caret-square-left'                   => __( 'caret-square-left', 'buildr' ),
        'fas fa-caret-square-right'                  => __( 'caret-square-right', 'buildr' ),
        'far fa-caret-square-right'                  => __( 'caret-square-right', 'buildr' ),
        'fas fa-caret-square-up'                     => __( 'caret-square-up', 'buildr' ),
        'far fa-caret-square-up'                     => __( 'caret-square-up', 'buildr' ),
        'fas fa-caret-up'                            => __( 'caret-up', 'buildr' ),
        'fas fa-cart-arrow-down'                     => __( 'cart-arrow-down', 'buildr' ),
        'fas fa-cart-plus'                           => __( 'cart-plus', 'buildr' ),
        'fab fa-cc-amazon-pay'                       => __( 'cc-amazon-pay', 'buildr' ),
        'fab fa-cc-amex'                             => __( 'cc-amex', 'buildr' ),
        'fab fa-cc-apple-pay'                        => __( 'cc-apple-pay', 'buildr' ),
        'fab fa-cc-diners-club'                      => __( 'cc-diners-club', 'buildr' ),
        'fab fa-cc-discover'                         => __( 'cc-discover', 'buildr' ),
        'fab fa-cc-jcb'                              => __( 'cc-jcb', 'buildr' ),
        'fab fa-cc-mastercard'                       => __( 'cc-mastercard', 'buildr' ),
        'fab fa-cc-paypal'                           => __( 'cc-paypal', 'buildr' ),
        'fab fa-cc-stripe'                           => __( 'cc-stripe', 'buildr' ),
        'fab fa-cc-visa'                             => __( 'cc-visa', 'buildr' ),
        'fab fa-centercode'                          => __( 'centercode', 'buildr' ),
        'fas fa-certificate'                         => __( 'certificate', 'buildr' ),
        'fas fa-chart-area'                          => __( 'chart-area', 'buildr' ),
        'fas fa-chart-bar'                           => __( 'chart-bar', 'buildr' ),
        'far fa-chart-bar'                           => __( 'chart-bar', 'buildr' ),
        'fas fa-chart-line'                          => __( 'chart-line', 'buildr' ),
        'fas fa-chart-pie'                           => __( 'chart-pie', 'buildr' ),
        'fas fa-check'                               => __( 'check', 'buildr' ),
        'fas fa-check-circle'                        => __( 'check-circle', 'buildr' ),
        'far fa-check-circle'                        => __( 'check-circle', 'buildr' ),
        'fas fa-check-square'                        => __( 'check-square', 'buildr' ),
        'far fa-check-square'                        => __( 'check-square', 'buildr' ),
        'fas fa-chess'                               => __( 'chess', 'buildr' ),
        'fas fa-chess-bishop'                        => __( 'chess-bishop', 'buildr' ),
        'fas fa-chess-board'                         => __( 'chess-board', 'buildr' ),
        'fas fa-chess-king'                          => __( 'chess-king', 'buildr' ),
        'fas fa-chess-knight'                        => __( 'chess-knight', 'buildr' ),
        'fas fa-chess-pawn'                          => __( 'chess-pawn', 'buildr' ),
        'fas fa-chess-queen'                         => __( 'chess-queen', 'buildr' ),
        'fas fa-chess-rook'                          => __( 'chess-rook', 'buildr' ),
        'fas fa-chevron-circle-down'                 => __( 'chevron-circle-down', 'buildr' ),
        'fas fa-chevron-circle-left'                 => __( 'chevron-circle-left', 'buildr' ),
        'fas fa-chevron-circle-right'                => __( 'chevron-circle-right', 'buildr' ),
        'fas fa-chevron-circle-up'                   => __( 'chevron-circle-up', 'buildr' ),
        'fas fa-chevron-down'                        => __( 'chevron-down', 'buildr' ),
        'fas fa-chevron-left'                        => __( 'chevron-left', 'buildr' ),
        'fas fa-chevron-right'                       => __( 'chevron-right', 'buildr' ),
        'fas fa-chevron-up'                          => __( 'chevron-up', 'buildr' ),
        'fas fa-child'                               => __( 'child', 'buildr' ),
        'fab fa-chrome'                              => __( 'chrome', 'buildr' ),
        'fas fa-circle'                              => __( 'circle', 'buildr' ),
        'far fa-circle'                              => __( 'circle', 'buildr' ),
        'fas fa-circle-notch'                        => __( 'circle-notch', 'buildr' ),
        'fas fa-clipboard'                           => __( 'clipboard', 'buildr' ),
        'far fa-clipboard'                           => __( 'clipboard', 'buildr' ),
        'fas fa-clipboard-check'                     => __( 'clipboard-check', 'buildr' ),
        'fas fa-clipboard-list'                      => __( 'clipboard-list', 'buildr' ),
        'fas fa-clock'                               => __( 'clock', 'buildr' ),
        'far fa-clock'                               => __( 'clock', 'buildr' ),
        'fas fa-clone'                               => __( 'clone', 'buildr' ),
        'far fa-clone'                               => __( 'clone', 'buildr' ),
        'fas fa-closed-captioning'                   => __( 'closed-captioning', 'buildr' ),
        'far fa-closed-captioning'                   => __( 'closed-captioning', 'buildr' ),
        'fas fa-cloud'                               => __( 'cloud', 'buildr' ),
        'fas fa-cloud-download-alt'                  => __( 'cloud-download-alt', 'buildr' ),
        'fas fa-cloud-upload-alt'                    => __( 'cloud-upload-alt', 'buildr' ),
        'fab fa-cloudscale'                          => __( 'cloudscale', 'buildr' ),
        'fab fa-cloudsmith'                          => __( 'cloudsmith', 'buildr' ),
        'fab fa-cloudversify'                        => __( 'cloudversify', 'buildr' ),
        'fas fa-code'                                => __( 'code', 'buildr' ),
        'fas fa-code-branch'                         => __( 'code-branch', 'buildr' ),
        'fab fa-codepen'                             => __( 'codepen', 'buildr' ),
        'fab fa-codiepie'                            => __( 'codiepie', 'buildr' ),
        'fas fa-coffee'                              => __( 'coffee', 'buildr' ),
        'fas fa-cog'                                 => __( 'cog', 'buildr' ),
        'fas fa-cogs'                                => __( 'cogs', 'buildr' ),
        'fas fa-columns'                             => __( 'columns', 'buildr' ),
        'fas fa-comment'                             => __( 'comment', 'buildr' ),
        'far fa-comment'                             => __( 'comment', 'buildr' ),
        'fas fa-comment-alt'                         => __( 'comment-alt', 'buildr' ),
        'far fa-comment-alt'                         => __( 'comment-alt', 'buildr' ),
        'fas fa-comment-dots'                        => __( 'comment-dots', 'buildr' ),
        'fas fa-comment-slash'                       => __( 'comment-slash', 'buildr' ),
        'fas fa-comments'                            => __( 'comments', 'buildr' ),
        'far fa-comments'                            => __( 'comments', 'buildr' ),
        'fas fa-compass'                             => __( 'compass', 'buildr' ),
        'far fa-compass'                             => __( 'compass', 'buildr' ),
        'fas fa-compress'                            => __( 'compress', 'buildr' ),
        'fab fa-connectdevelop'                      => __( 'connectdevelop', 'buildr' ),
        'fab fa-contao'                              => __( 'contao', 'buildr' ),
        'fas fa-copy'                                => __( 'copy', 'buildr' ),
        'far fa-copy'                                => __( 'copy', 'buildr' ),
        'fas fa-copyright'                           => __( 'copyright', 'buildr' ),
        'far fa-copyright'                           => __( 'copyright', 'buildr' ),
        'fas fa-couch'                               => __( 'couch', 'buildr' ),
        'fab fa-cpanel'                              => __( 'cpanel', 'buildr' ),
        'fab fa-creative-commons'                    => __( 'creative-commons', 'buildr' ),
        'fas fa-credit-card'                         => __( 'credit-card', 'buildr' ),
        'far fa-credit-card'                         => __( 'credit-card', 'buildr' ),
        'fas fa-crop'                                => __( 'crop', 'buildr' ),
        'fas fa-crosshairs'                          => __( 'crosshairs', 'buildr' ),
        'fab fa-css3'                                => __( 'css3', 'buildr' ),
        'fab fa-css3-alt'                            => __( 'css3-alt', 'buildr' ),
        'fas fa-cube'                                => __( 'cube', 'buildr' ),
        'fas fa-cubes'                               => __( 'cubes', 'buildr' ),
        'fas fa-cut'                                 => __( 'cut', 'buildr' ),
        'fab fa-cuttlefish'                          => __( 'cuttlefish', 'buildr' ),
        'fab fa-d-and-d'                             => __( 'd-and-d', 'buildr' ),
        'fab fa-dashcube'                            => __( 'dashcube', 'buildr' ),
        'fas fa-database'                            => __( 'database', 'buildr' ),
        'fas fa-deaf'                                => __( 'deaf', 'buildr' ),
        'fab fa-delicious'                           => __( 'delicious', 'buildr' ),
        'fab fa-deploydog'                           => __( 'deploydog', 'buildr' ),
        'fab fa-deskpro'                             => __( 'deskpro', 'buildr' ),
        'fas fa-desktop'                             => __( 'desktop', 'buildr' ),
        'fab fa-deviantart'                          => __( 'deviantart', 'buildr' ),
        'fas fa-diagnoses'                           => __( 'diagnoses', 'buildr' ),
        'fab fa-digg'                                => __( 'digg', 'buildr' ),
        'fab fa-digital-ocean'                       => __( 'digital-ocean', 'buildr' ),
        'fab fa-discord'                             => __( 'discord', 'buildr' ),
        'fab fa-discourse'                           => __( 'discourse', 'buildr' ),
        'fas fa-dna'                                 => __( 'dna', 'buildr' ),
        'fab fa-dochub'                              => __( 'dochub', 'buildr' ),
        'fab fa-docker'                              => __( 'docker', 'buildr' ),
        'fas fa-dollar-sign'                         => __( 'dollar-sign', 'buildr' ),
        'fas fa-dolly'                               => __( 'dolly', 'buildr' ),
        'fas fa-dolly-flatbed'                       => __( 'dolly-flatbed', 'buildr' ),
        'fas fa-donate'                              => __( 'donate', 'buildr' ),
        'fas fa-dot-circle'                          => __( 'dot-circle', 'buildr' ),
        'far fa-dot-circle'                          => __( 'dot-circle', 'buildr' ),
        'fas fa-dove'                                => __( 'dove', 'buildr' ),
        'fas fa-download'                            => __( 'download', 'buildr' ),
        'fab fa-draft2digital'                       => __( 'draft2digital', 'buildr' ),
        'fab fa-dribbble'                            => __( 'dribbble', 'buildr' ),
        'fab fa-dribbble-square'                     => __( 'dribbble-square', 'buildr' ),
        'fab fa-dropbox'                             => __( 'dropbox', 'buildr' ),
        'fab fa-drupal'                              => __( 'drupal', 'buildr' ),
        'fab fa-dyalog'                              => __( 'dyalog', 'buildr' ),
        'fab fa-earlybirds'                          => __( 'earlybirds', 'buildr' ),
        'fab fa-edge'                                => __( 'edge', 'buildr' ),
        'fas fa-edit'                                => __( 'edit', 'buildr' ),
        'far fa-edit'                                => __( 'edit', 'buildr' ),
        'fas fa-eject'                               => __( 'eject', 'buildr' ),
        'fab fa-elementor'                           => __( 'elementor', 'buildr' ),
        'fas fa-ellipsis-h'                          => __( 'ellipsis-h', 'buildr' ),
        'fas fa-ellipsis-v'                          => __( 'ellipsis-v', 'buildr' ),
        'fab fa-ember'                               => __( 'ember', 'buildr' ),
        'fab fa-empire'                              => __( 'empire', 'buildr' ),
        'fas fa-envelope'                            => __( 'envelope', 'buildr' ),
        'far fa-envelope'                            => __( 'envelope', 'buildr' ),
        'fas fa-envelope-open'                       => __( 'envelope-open', 'buildr' ),
        'far fa-envelope-open'                       => __( 'envelope-open', 'buildr' ),
        'fas fa-envelope-square'                     => __( 'envelope-square', 'buildr' ),
        'fab fa-envira'                              => __( 'envira', 'buildr' ),
        'fas fa-eraser'                              => __( 'eraser', 'buildr' ),
        'fab fa-erlang'                              => __( 'erlang', 'buildr' ),
        'fab fa-ethereum'                            => __( 'ethereum', 'buildr' ),
        'fab fa-etsy'                                => __( 'etsy', 'buildr' ),
        'fas fa-euro-sign'                           => __( 'euro-sign', 'buildr' ),
        'fas fa-exchange-alt'                        => __( 'exchange-alt', 'buildr' ),
        'fas fa-exclamation'                         => __( 'exclamation', 'buildr' ),
        'fas fa-exclamation-circle'                  => __( 'exclamation-circle', 'buildr' ),
        'fas fa-exclamation-triangle'                => __( 'exclamation-triangle', 'buildr' ),
        'fas fa-expand'                              => __( 'expand', 'buildr' ),
        'fas fa-expand-arrows-alt'                   => __( 'expand-arrows-alt', 'buildr' ),
        'fab fa-expeditedssl'                        => __( 'expeditedssl', 'buildr' ),
        'fas fa-external-link-alt'                   => __( 'external-link-alt', 'buildr' ),
        'fas fa-external-link-square-alt'            => __( 'external-link-square-alt', 'buildr' ),
        'fas fa-eye'                                 => __( 'eye', 'buildr' ),
        'fas fa-eye-dropper'                         => __( 'eye-dropper', 'buildr' ),
        'fas fa-eye-slash'                           => __( 'eye-slash', 'buildr' ),
        'far fa-eye-slash'                           => __( 'eye-slash', 'buildr' ),
        'fab fa-facebook'                            => __( 'facebook', 'buildr' ),
        'fab fa-facebook-f'                          => __( 'facebook-f', 'buildr' ),
        'fab fa-facebook-messenger'                  => __( 'facebook-messenger', 'buildr' ),
        'fab fa-facebook-square'                     => __( 'facebook-square', 'buildr' ),
        'fas fa-fast-backward'                       => __( 'fast-backward', 'buildr' ),
        'fas fa-fast-forward'                        => __( 'fast-forward', 'buildr' ),
        'fas fa-fax'                                 => __( 'fax', 'buildr' ),
        'fas fa-female'                              => __( 'female', 'buildr' ),
        'fas fa-fighter-jet'                         => __( 'fighter-jet', 'buildr' ),
        'fas fa-file'                                => __( 'file', 'buildr' ),
        'far fa-file'                                => __( 'file', 'buildr' ),
        'fas fa-file-alt'                            => __( 'file-alt', 'buildr' ),
        'far fa-file-alt'                            => __( 'file-alt', 'buildr' ),
        'fas fa-file-archive'                        => __( 'file-archive', 'buildr' ),
        'far fa-file-archive'                        => __( 'file-archive', 'buildr' ),
        'fas fa-file-audio'                          => __( 'file-audio', 'buildr' ),
        'far fa-file-audio'                          => __( 'file-audio', 'buildr' ),
        'fas fa-file-code'                           => __( 'file-code', 'buildr' ),
        'far fa-file-code'                           => __( 'file-code', 'buildr' ),
        'fas fa-file-excel'                          => __( 'file-excel', 'buildr' ),
        'far fa-file-excel'                          => __( 'file-excel', 'buildr' ),
        'fas fa-file-image'                          => __( 'file-image', 'buildr' ),
        'far fa-file-image'                          => __( 'file-image', 'buildr' ),
        'fas fa-file-medical'                        => __( 'file-medical', 'buildr' ),
        'fas fa-file-medical-alt'                    => __( 'file-medical-alt', 'buildr' ),
        'fas fa-file-pdf'                            => __( 'file-pdf', 'buildr' ),
        'far fa-file-pdf'                            => __( 'file-pdf', 'buildr' ),
        'fas fa-file-powerpoint'                     => __( 'file-powerpoint', 'buildr' ),
        'far fa-file-powerpoint'                     => __( 'file-powerpoint', 'buildr' ),
        'fas fa-file-video'                          => __( 'file-video', 'buildr' ),
        'far fa-file-video'                          => __( 'file-video', 'buildr' ),
        'fas fa-file-word'                           => __( 'file-word', 'buildr' ),
        'far fa-file-word'                           => __( 'file-word', 'buildr' ),
        'fas fa-film'                                => __( 'film', 'buildr' ),
        'fas fa-filter'                              => __( 'filter', 'buildr' ),
        'fas fa-fire'                                => __( 'fire', 'buildr' ),
        'fas fa-fire-extinguisher'                   => __( 'fire-extinguisher', 'buildr' ),
        'fab fa-firefox'                             => __( 'firefox', 'buildr' ),
        'fas fa-first-aid'                           => __( 'first-aid', 'buildr' ),
        'fab fa-first-order'                         => __( 'first-order', 'buildr' ),
        'fab fa-firstdraft'                          => __( 'firstdraft', 'buildr' ),
        'fas fa-flag'                                => __( 'flag', 'buildr' ),
        'far fa-flag'                                => __( 'flag', 'buildr' ),
        'fas fa-flag-checkered'                      => __( 'flag-checkered', 'buildr' ),
        'fas fa-flask'                               => __( 'flask', 'buildr' ),
        'fab fa-flickr'                              => __( 'flickr', 'buildr' ),
        'fab fa-flipboard'                           => __( 'flipboard', 'buildr' ),
        'fab fa-fly'                                 => __( 'fly', 'buildr' ),
        'fas fa-folder'                              => __( 'folder', 'buildr' ),
        'far fa-folder'                              => __( 'folder', 'buildr' ),
        'fas fa-folder-open'                         => __( 'folder-open', 'buildr' ),
        'far fa-folder-open'                         => __( 'folder-open', 'buildr' ),
        'fas fa-font'                                => __( 'font', 'buildr' ),
        'fab fa-font-awesome'                        => __( 'font-awesome', 'buildr' ),
        'fab fa-font-awesome-alt'                    => __( 'font-awesome-alt', 'buildr' ),
        'fab fa-font-awesome-flag'                   => __( 'font-awesome-flag', 'buildr' ),
        'fab fa-fonticons'                           => __( 'fonticons', 'buildr' ),
        'fab fa-fonticons-fi'                        => __( 'fonticons-fi', 'buildr' ),
        'fas fa-football-ball'                       => __( 'football-ball', 'buildr' ),
        'fab fa-fort-awesome'                        => __( 'fort-awesome', 'buildr' ),
        'fab fa-fort-awesome-alt'                    => __( 'fort-awesome-alt', 'buildr' ),
        'fab fa-forumbee'                            => __( 'forumbee', 'buildr' ),
        'fas fa-forward'                             => __( 'forward', 'buildr' ),
        'fab fa-foursquare'                          => __( 'foursquare', 'buildr' ),
        'fab fa-free-code-camp'                      => __( 'free-code-camp', 'buildr' ),
        'fab fa-freebsd'                             => __( 'freebsd', 'buildr' ),
        'fas fa-frown'                               => __( 'frown', 'buildr' ),
        'far fa-frown'                               => __( 'frown', 'buildr' ),
        'fas fa-futbol'                              => __( 'futbol', 'buildr' ),
        'far fa-futbol'                              => __( 'futbol', 'buildr' ),
        'fas fa-gamepad'                             => __( 'gamepad', 'buildr' ),
        'fas fa-gavel'                               => __( 'gavel', 'buildr' ),
        'fas fa-gem'                                 => __( 'gem', 'buildr' ),
        'far fa-gem'                                 => __( 'gem', 'buildr' ),
        'fas fa-genderless'                          => __( 'genderless', 'buildr' ),
        'fab fa-get-pocket'                          => __( 'get-pocket', 'buildr' ),
        'fab fa-gg'                                  => __( 'gg', 'buildr' ),
        'fab fa-gg-circle'                           => __( 'gg-circle', 'buildr' ),
        'fas fa-gift'                                => __( 'gift', 'buildr' ),
        'fab fa-git'                                 => __( 'git', 'buildr' ),
        'fab fa-git-square'                          => __( 'git-square', 'buildr' ),
        'fab fa-github'                              => __( 'github', 'buildr' ),
        'fab fa-github-alt'                          => __( 'github-alt', 'buildr' ),
        'fab fa-github-square'                       => __( 'github-square', 'buildr' ),
        'fab fa-gitkraken'                           => __( 'gitkraken', 'buildr' ),
        'fab fa-gitlab'                              => __( 'gitlab', 'buildr' ),
        'fab fa-gitter'                              => __( 'gitter', 'buildr' ),
        'fas fa-glass-martini'                       => __( 'glass-martini', 'buildr' ),
        'fab fa-glide'                               => __( 'glide', 'buildr' ),
        'fab fa-glide-g'                             => __( 'glide-g', 'buildr' ),
        'fas fa-globe'                               => __( 'globe', 'buildr' ),
        'fab fa-gofore'                              => __( 'gofore', 'buildr' ),
        'fas fa-golf-ball'                           => __( 'golf-ball', 'buildr' ),
        'fab fa-goodreads'                           => __( 'goodreads', 'buildr' ),
        'fab fa-goodreads-g'                         => __( 'goodreads-g', 'buildr' ),
        'fab fa-google'                              => __( 'google', 'buildr' ),
        'fab fa-google-drive'                        => __( 'google-drive', 'buildr' ),
        'fab fa-google-play'                         => __( 'google-play', 'buildr' ),
        'fab fa-google-plus'                         => __( 'google-plus', 'buildr' ),
        'fab fa-google-plus-g'                       => __( 'google-plus-g', 'buildr' ),
        'fab fa-google-plus-square'                  => __( 'google-plus-square', 'buildr' ),
        'fab fa-google-wallet'                       => __( 'google-wallet', 'buildr' ),
        'fas fa-graduation-cap'                      => __( 'graduation-cap', 'buildr' ),
        'fab fa-gratipay'                            => __( 'gratipay', 'buildr' ),
        'fab fa-grav'                                => __( 'grav', 'buildr' ),
        'fab fa-gripfire'                            => __( 'gripfire', 'buildr' ),
        'fab fa-grunt'                               => __( 'grunt', 'buildr' ),
        'fab fa-gulp'                                => __( 'gulp', 'buildr' ),
        'fas fa-h-square'                            => __( 'h-square', 'buildr' ),
        'fab fa-hacker-news'                         => __( 'hacker-news', 'buildr' ),
        'fab fa-hacker-news-square'                  => __( 'hacker-news-square', 'buildr' ),
        'fas fa-hand-holding'                        => __( 'hand-holding', 'buildr' ),
        'fas fa-hand-holding-heart'                  => __( 'hand-holding-heart', 'buildr' ),
        'fas fa-hand-holding-usd'                    => __( 'hand-holding-usd', 'buildr' ),
        'fas fa-hand-lizard'                         => __( 'hand-lizard', 'buildr' ),
        'far fa-hand-lizard'                         => __( 'hand-lizard', 'buildr' ),
        'fas fa-hand-paper'                          => __( 'hand-paper', 'buildr' ),
        'far fa-hand-paper'                          => __( 'hand-paper', 'buildr' ),
        'fas fa-hand-peace'                          => __( 'hand-peace', 'buildr' ),
        'far fa-hand-peace'                          => __( 'hand-peace', 'buildr' ),
        'fas fa-hand-point-down'                     => __( 'hand-point-down', 'buildr' ),
        'far fa-hand-point-down'                     => __( 'hand-point-down', 'buildr' ),
        'fas fa-hand-point-left'                     => __( 'hand-point-left', 'buildr' ),
        'far fa-hand-point-left'                     => __( 'hand-point-left', 'buildr' ),
        'fas fa-hand-point-right'                    => __( 'hand-point-right', 'buildr' ),
        'far fa-hand-point-right'                    => __( 'hand-point-right', 'buildr' ),
        'fas fa-hand-point-up'                       => __( 'hand-point-up', 'buildr' ),
        'far fa-hand-point-up'                       => __( 'hand-point-up', 'buildr' ),
        'fas fa-hand-pointer'                        => __( 'hand-pointer', 'buildr' ),
        'far fa-hand-pointer'                        => __( 'hand-pointer', 'buildr' ),
        'fas fa-hand-rock'                           => __( 'hand-rock', 'buildr' ),
        'far fa-hand-rock'                           => __( 'hand-rock', 'buildr' ),
        'fas fa-hand-scissors'                       => __( 'hand-scissors', 'buildr' ),
        'far fa-hand-scissors'                       => __( 'hand-scissors', 'buildr' ),
        'fas fa-hand-spock'                          => __( 'hand-spock', 'buildr' ),
        'far fa-hand-spock'                          => __( 'hand-spock', 'buildr' ),
        'fas fa-hands'                               => __( 'hands', 'buildr' ),
        'fas fa-hands-helping'                       => __( 'hands-helping', 'buildr' ),
        'fas fa-handshake'                           => __( 'handshake', 'buildr' ),
        'far fa-handshake'                           => __( 'handshake', 'buildr' ),
        'fas fa-hashtag'                             => __( 'hashtag', 'buildr' ),
        'fas fa-hdd'                                 => __( 'hdd', 'buildr' ),
        'far fa-hdd'                                 => __( 'hdd', 'buildr' ),
        'fas fa-heading'                             => __( 'heading', 'buildr' ),
        'fas fa-headphones'                          => __( 'headphones', 'buildr' ),
        'fas fa-heart'                               => __( 'heart', 'buildr' ),
        'far fa-heart'                               => __( 'heart', 'buildr' ),
        'fas fa-heartbeat'                           => __( 'heartbeat', 'buildr' ),
        'fab fa-hips'                                => __( 'hips', 'buildr' ),
        'fab fa-hire-a-helper'                       => __( 'hire-a-helper', 'buildr' ),
        'fas fa-history'                             => __( 'history', 'buildr' ),
        'fas fa-hockey-puck'                         => __( 'hockey-puck', 'buildr' ),
        'fas fa-home'                                => __( 'home', 'buildr' ),
        'fab fa-hooli'                               => __( 'hooli', 'buildr' ),
        'fas fa-hospital'                            => __( 'hospital', 'buildr' ),
        'far fa-hospital'                            => __( 'hospital', 'buildr' ),
        'fas fa-hospital-alt'                        => __( 'hospital-alt', 'buildr' ),
        'fas fa-hospital-symbol'                     => __( 'hospital-symbol', 'buildr' ),
        'fab fa-hotjar'                              => __( 'hotjar', 'buildr' ),
        'fas fa-hourglass'                           => __( 'hourglass', 'buildr' ),
        'far fa-hourglass'                           => __( 'hourglass', 'buildr' ),
        'fas fa-hourglass-end'                       => __( 'hourglass-end', 'buildr' ),
        'fas fa-hourglass-half'                      => __( 'hourglass-half', 'buildr' ),
        'fas fa-hourglass-start'                     => __( 'hourglass-start', 'buildr' ),
        'fab fa-houzz'                               => __( 'houzz', 'buildr' ),
        'fab fa-html5'                               => __( 'html5', 'buildr' ),
        'fab fa-hubspot'                             => __( 'hubspot', 'buildr' ),
        'fas fa-i-cursor'                            => __( 'i-cursor', 'buildr' ),
        'fas fa-id-badge'                            => __( 'id-badge', 'buildr' ),
        'far fa-id-badge'                            => __( 'id-badge', 'buildr' ),
        'fas fa-id-card'                             => __( 'id-card', 'buildr' ),
        'far fa-id-card'                             => __( 'id-card', 'buildr' ),
        'fas fa-id-card-alt'                         => __( 'id-card-alt', 'buildr' ),
        'fas fa-image'                               => __( 'image', 'buildr' ),
        'far fa-image'                               => __( 'image', 'buildr' ),
        'fas fa-images'                              => __( 'images', 'buildr' ),
        'far fa-images'                              => __( 'images', 'buildr' ),
        'fab fa-imdb'                                => __( 'imdb', 'buildr' ),
        'fas fa-inbox'                               => __( 'inbox', 'buildr' ),
        'fas fa-indent'                              => __( 'indent', 'buildr' ),
        'fas fa-industry'                            => __( 'industry', 'buildr' ),
        'fas fa-info'                                => __( 'info', 'buildr' ),
        'fas fa-info-circle'                         => __( 'info-circle', 'buildr' ),
        'fab fa-instagram'                           => __( 'instagram', 'buildr' ),
        'fab fa-internet-explorer'                   => __( 'internet-explorer', 'buildr' ),
        'fab fa-ioxhost'                             => __( 'ioxhost', 'buildr' ),
        'fas fa-italic'                              => __( 'italic', 'buildr' ),
        'fab fa-itunes'                              => __( 'itunes', 'buildr' ),
        'fab fa-itunes-note'                         => __( 'itunes-note', 'buildr' ),
        'fab fa-java'                                => __( 'java', 'buildr' ),
        'fab fa-jenkins'                             => __( 'jenkins', 'buildr' ),
        'fab fa-joget'                               => __( 'joget', 'buildr' ),
        'fab fa-joomla'                              => __( 'joomla', 'buildr' ),
        'fab fa-js'                                  => __( 'js', 'buildr' ),
        'fab fa-js-square'                           => __( 'js-square', 'buildr' ),
        'fab fa-jsfiddle'                            => __( 'jsfiddle', 'buildr' ),
        'fas fa-key'                                 => __( 'key', 'buildr' ),
        'fas fa-keyboard'                            => __( 'keyboard', 'buildr' ),
        'far fa-keyboard'                            => __( 'keyboard', 'buildr' ),
        'fab fa-keycdn'                              => __( 'keycdn', 'buildr' ),
        'fab fa-kickstarter'                         => __( 'kickstarter', 'buildr' ),
        'fab fa-kickstarter-k'                       => __( 'kickstarter-k', 'buildr' ),
        'fab fa-korvue'                              => __( 'korvue', 'buildr' ),
        'fas fa-language'                            => __( 'language', 'buildr' ),
        'fas fa-laptop'                              => __( 'laptop', 'buildr' ),
        'fab fa-laravel'                             => __( 'laravel', 'buildr' ),
        'fab fa-lastfm'                              => __( 'lastfm', 'buildr' ),
        'fab fa-lastfm-square'                       => __( 'lastfm-square', 'buildr' ),
        'fas fa-leaf'                                => __( 'leaf', 'buildr' ),
        'fab fa-leanpub'                             => __( 'leanpub', 'buildr' ),
        'fas fa-lemon'                               => __( 'lemon', 'buildr' ),
        'far fa-lemon'                               => __( 'lemon', 'buildr' ),
        'fab fa-less'                                => __( 'less', 'buildr' ),
        'fas fa-level-down-alt'                      => __( 'level-down-alt', 'buildr' ),
        'fas fa-level-up-alt'                        => __( 'level-up-alt', 'buildr' ),
        'fas fa-life-ring'                           => __( 'life-ring', 'buildr' ),
        'far fa-life-ring'                           => __( 'life-ring', 'buildr' ),
        'fas fa-lightbulb'                           => __( 'lightbulb', 'buildr' ),
        'far fa-lightbulb'                           => __( 'lightbulb', 'buildr' ),
        'fab fa-line'                                => __( 'line', 'buildr' ),
        'fas fa-link'                                => __( 'link', 'buildr' ),
        'fab fa-linkedin'                            => __( 'linkedin', 'buildr' ),
        'fab fa-linkedin-in'                         => __( 'linkedin-in', 'buildr' ),
        'fab fa-linode'                              => __( 'linode', 'buildr' ),
        'fab fa-linux'                               => __( 'linux', 'buildr' ),
        'fas fa-lira-sign'                           => __( 'lira-sign', 'buildr' ),
        'fas fa-list'                                => __( 'list', 'buildr' ),
        'fas fa-list-alt'                            => __( 'list-alt', 'buildr' ),
        'far fa-list-alt'                            => __( 'list-alt', 'buildr' ),
        'fas fa-list-ol'                             => __( 'list-ol', 'buildr' ),
        'fas fa-list-ul'                             => __( 'list-ul', 'buildr' ),
        'fas fa-location-arrow'                      => __( 'location-arrow', 'buildr' ),
        'fas fa-lock'                                => __( 'lock', 'buildr' ),
        'fas fa-lock-open'                           => __( 'lock-open', 'buildr' ),
        'fas fa-long-arrow-alt-down'                 => __( 'long-arrow-alt-down', 'buildr' ),
        'fas fa-long-arrow-alt-left'                 => __( 'long-arrow-alt-left', 'buildr' ),
        'fas fa-long-arrow-alt-right'                => __( 'long-arrow-alt-right', 'buildr' ),
        'fas fa-long-arrow-alt-up'                   => __( 'long-arrow-alt-up', 'buildr' ),
        'fas fa-low-vision'                          => __( 'low-vision', 'buildr' ),
        'fab fa-lyft'                                => __( 'lyft', 'buildr' ),
        'fab fa-magento'                             => __( 'magento', 'buildr' ),
        'fas fa-magic'                               => __( 'magic', 'buildr' ),
        'fas fa-magnet'                              => __( 'magnet', 'buildr' ),
        'fas fa-male'                                => __( 'male', 'buildr' ),
        'fas fa-map'                                 => __( 'map', 'buildr' ),
        'far fa-map'                                 => __( 'map', 'buildr' ),
        'fas fa-map-marker'                          => __( 'map-marker', 'buildr' ),
        'fas fa-map-marker-alt'                      => __( 'map-marker-alt', 'buildr' ),
        'fas fa-map-pin'                             => __( 'map-pin', 'buildr' ),
        'fas fa-map-signs'                           => __( 'map-signs', 'buildr' ),
        'fas fa-mars'                                => __( 'mars', 'buildr' ),
        'fas fa-mars-double'                         => __( 'mars-double', 'buildr' ),
        'fas fa-mars-stroke'                         => __( 'mars-stroke', 'buildr' ),
        'fas fa-mars-stroke-h'                       => __( 'mars-stroke-h', 'buildr' ),
        'fas fa-mars-stroke-v'                       => __( 'mars-stroke-v', 'buildr' ),
        'fab fa-maxcdn'                              => __( 'maxcdn', 'buildr' ),
        'fab fa-medapps'                             => __( 'medapps', 'buildr' ),
        'fab fa-medium'                              => __( 'medium', 'buildr' ),
        'fab fa-medium-m'                            => __( 'medium-m', 'buildr' ),
        'fas fa-medkit'                              => __( 'medkit', 'buildr' ),
        'fab fa-medrt'                               => __( 'medrt', 'buildr' ),
        'fab fa-meetup'                              => __( 'meetup', 'buildr' ),
        'fas fa-meh'                                 => __( 'meh', 'buildr' ),
        'far fa-meh'                                 => __( 'meh', 'buildr' ),
        'fas fa-mercury'                             => __( 'mercury', 'buildr' ),
        'fas fa-microchip'                           => __( 'microchip', 'buildr' ),
        'fas fa-microphone'                          => __( 'microphone', 'buildr' ),
        'fas fa-microphone-slash'                    => __( 'microphone-slash', 'buildr' ),
        'fab fa-microsoft'                           => __( 'microsoft', 'buildr' ),
        'fas fa-minus'                               => __( 'minus', 'buildr' ),
        'fas fa-minus-circle'                        => __( 'minus-circle', 'buildr' ),
        'fas fa-minus-square'                        => __( 'minus-square', 'buildr' ),
        'far fa-minus-square'                        => __( 'minus-square', 'buildr' ),
        'fab fa-mix'                                 => __( 'mix', 'buildr' ),
        'fab fa-mixcloud'                            => __( 'mixcloud', 'buildr' ),
        'fab fa-mizuni'                              => __( 'mizuni', 'buildr' ),
        'fas fa-mobile'                              => __( 'mobile', 'buildr' ),
        'fas fa-mobile-alt'                          => __( 'mobile-alt', 'buildr' ),
        'fab fa-modx'                                => __( 'modx', 'buildr' ),
        'fab fa-monero'                              => __( 'monero', 'buildr' ),
        'fas fa-money-bill-alt'                      => __( 'money-bill-alt', 'buildr' ),
        'far fa-money-bill-alt'                      => __( 'money-bill-alt', 'buildr' ),
        'fas fa-moon'                                => __( 'moon', 'buildr' ),
        'far fa-moon'                                => __( 'moon', 'buildr' ),
        'fas fa-motorcycle'                          => __( 'motorcycle', 'buildr' ),
        'fas fa-mouse-pointer'                       => __( 'mouse-pointer', 'buildr' ),
        'fas fa-music'                               => __( 'music', 'buildr' ),
        'fab fa-napster'                             => __( 'napster', 'buildr' ),
        'fas fa-neuter'                              => __( 'neuter', 'buildr' ),
        'fas fa-newspaper'                           => __( 'newspaper', 'buildr' ),
        'far fa-newspaper'                           => __( 'newspaper', 'buildr' ),
        'fab fa-nintendo-switch'                     => __( 'nintendo-switch', 'buildr' ),
        'fab fa-node'                                => __( 'node', 'buildr' ),
        'fab fa-node-js'                             => __( 'node-js', 'buildr' ),
        'fas fa-notes-medical'                       => __( 'notes-medical', 'buildr' ),
        'fab fa-npm'                                 => __( 'npm', 'buildr' ),
        'fab fa-ns8'                                 => __( 'ns8', 'buildr' ),
        'fab fa-nutritionix'                         => __( 'nutritionix', 'buildr' ),
        'fas fa-object-group'                        => __( 'object-group', 'buildr' ),
        'far fa-object-group'                        => __( 'object-group', 'buildr' ),
        'fas fa-object-ungroup'                      => __( 'object-ungroup', 'buildr' ),
        'far fa-object-ungroup'                      => __( 'object-ungroup', 'buildr' ),
        'fab fa-odnoklassniki'                       => __( 'odnoklassniki', 'buildr' ),
        'fab fa-odnoklassniki-square'                => __( 'odnoklassniki-square', 'buildr' ),
        'fab fa-opencart'                            => __( 'opencart', 'buildr' ),
        'fab fa-openid'                              => __( 'openid', 'buildr' ),
        'fab fa-opera'                               => __( 'opera', 'buildr' ),
        'fab fa-optin-monster'                       => __( 'optin-monster', 'buildr' ),
        'fab fa-osi'                                 => __( 'osi', 'buildr' ),
        'fas fa-outdent'                             => __( 'outdent', 'buildr' ),
        'fab fa-page4'                               => __( 'page4', 'buildr' ),
        'fab fa-pagelines'                           => __( 'pagelines', 'buildr' ),
        'fas fa-paint-brush'                         => __( 'paint-brush', 'buildr' ),
        'fab fa-palfed'                              => __( 'palfed', 'buildr' ),
        'fas fa-pallet'                              => __( 'pallet', 'buildr' ),
        'fas fa-paper-plane'                         => __( 'paper-plane', 'buildr' ),
        'far fa-paper-plane'                         => __( 'paper-plane', 'buildr' ),
        'fas fa-paperclip'                           => __( 'paperclip', 'buildr' ),
        'fas fa-parachute-box'                       => __( 'parachute-box', 'buildr' ),
        'fas fa-paragraph'                           => __( 'paragraph', 'buildr' ),
        'fas fa-paste'                               => __( 'paste', 'buildr' ),
        'fab fa-patreon'                             => __( 'patreon', 'buildr' ),
        'fas fa-pause'                               => __( 'pause', 'buildr' ),
        'fas fa-pause-circle'                        => __( 'pause-circle', 'buildr' ),
        'far fa-pause-circle'                        => __( 'pause-circle', 'buildr' ),
        'fas fa-paw'                                 => __( 'paw', 'buildr' ),
        'fab fa-paypal'                              => __( 'paypal', 'buildr' ),
        'fas fa-pen-square'                          => __( 'pen-square', 'buildr' ),
        'fas fa-pencil-alt'                          => __( 'pencil-alt', 'buildr' ),
        'fas fa-people-carry'                        => __( 'people-carry', 'buildr' ),
        'fas fa-percent'                             => __( 'percent', 'buildr' ),
        'fab fa-periscope'                           => __( 'periscope', 'buildr' ),
        'fab fa-phabricator'                         => __( 'phabricator', 'buildr' ),
        'fab fa-phoenix-framework'                   => __( 'phoenix-framework', 'buildr' ),
        'fas fa-phone'                               => __( 'phone', 'buildr' ),
        'fas fa-phone-slash'                         => __( 'phone-slash', 'buildr' ),
        'fas fa-phone-square'                        => __( 'phone-square', 'buildr' ),
        'fas fa-phone-volume'                        => __( 'phone-volume', 'buildr' ),
        'fab fa-php'                                 => __( 'php', 'buildr' ),
        'fab fa-pied-piper'                          => __( 'pied-piper', 'buildr' ),
        'fab fa-pied-piper-alt'                      => __( 'pied-piper-alt', 'buildr' ),
        'fab fa-pied-piper-hat'                      => __( 'pied-piper-hat', 'buildr' ),
        'fab fa-pied-piper-pp'                       => __( 'pied-piper-pp', 'buildr' ),
        'fas fa-piggy-bank'                          => __( 'piggy-bank', 'buildr' ),
        'fas fa-pills'                               => __( 'pills', 'buildr' ),
        'fab fa-pinterest'                           => __( 'pinterest', 'buildr' ),
        'fab fa-pinterest-p'                         => __( 'pinterest-p', 'buildr' ),
        'fab fa-pinterest-square'                    => __( 'pinterest-square', 'buildr' ),
        'fas fa-plane'                               => __( 'plane', 'buildr' ),
        'fas fa-play'                                => __( 'play', 'buildr' ),
        'fas fa-play-circle'                         => __( 'play-circle', 'buildr' ),
        'far fa-play-circle'                         => __( 'play-circle', 'buildr' ),
        'fab fa-playstation'                         => __( 'playstation', 'buildr' ),
        'fas fa-plug'                                => __( 'plug', 'buildr' ),
        'fas fa-plus'                                => __( 'plus', 'buildr' ),
        'fas fa-plus-circle'                         => __( 'plus-circle', 'buildr' ),
        'fas fa-plus-square'                         => __( 'plus-square', 'buildr' ),
        'far fa-plus-square'                         => __( 'plus-square', 'buildr' ),
        'fas fa-podcast'                             => __( 'podcast', 'buildr' ),
        'fas fa-poo'                                 => __( 'poo', 'buildr' ),
        'fas fa-pound-sign'                          => __( 'pound-sign', 'buildr' ),
        'fas fa-power-off'                           => __( 'power-off', 'buildr' ),
        'fas fa-prescription-bottle'                 => __( 'prescription-bottle', 'buildr' ),
        'fas fa-prescription-bottle-alt'             => __( 'prescription-bottle-alt', 'buildr' ),
        'fas fa-print'                               => __( 'print', 'buildr' ),
        'fas fa-procedures'                          => __( 'procedures', 'buildr' ),
        'fab fa-product-hunt'                        => __( 'product-hunt', 'buildr' ),
        'fab fa-pushed'                              => __( 'pushed', 'buildr' ),
        'fas fa-puzzle-piece'                        => __( 'puzzle-piece', 'buildr' ),
        'fab fa-python'                              => __( 'python', 'buildr' ),
        'fab fa-qq'                                  => __( 'qq', 'buildr' ),
        'fas fa-qrcode'                              => __( 'qrcode', 'buildr' ),
        'fas fa-question'                            => __( 'question', 'buildr' ),
        'fas fa-question-circle'                     => __( 'question-circle', 'buildr' ),
        'far fa-question-circle'                     => __( 'question-circle', 'buildr' ),
        'fas fa-quidditch'                           => __( 'quidditch', 'buildr' ),
        'fab fa-quinscape'                           => __( 'quinscape', 'buildr' ),
        'fab fa-quora'                               => __( 'quora', 'buildr' ),
        'fas fa-quote-left'                          => __( 'quote-left', 'buildr' ),
        'fas fa-quote-right'                         => __( 'quote-right', 'buildr' ),
        'fas fa-random'                              => __( 'random', 'buildr' ),
        'fab fa-ravelry'                             => __( 'ravelry', 'buildr' ),
        'fab fa-react'                               => __( 'react', 'buildr' ),
        'fab fa-readme'                              => __( 'readme', 'buildr' ),
        'fab fa-rebel'                               => __( 'rebel', 'buildr' ),
        'fas fa-recycle'                             => __( 'recycle', 'buildr' ),
        'fab fa-red-river'                           => __( 'red-river', 'buildr' ),
        'fab fa-reddit'                              => __( 'reddit', 'buildr' ),
        'fab fa-reddit-alien'                        => __( 'reddit-alien', 'buildr' ),
        'fab fa-reddit-square'                       => __( 'reddit-square', 'buildr' ),
        'fas fa-redo'                                => __( 'redo', 'buildr' ),
        'fas fa-redo-alt'                            => __( 'redo-alt', 'buildr' ),
        'fas fa-registered'                          => __( 'registered', 'buildr' ),
        'far fa-registered'                          => __( 'registered', 'buildr' ),
        'fab fa-rendact'                             => __( 'rendact', 'buildr' ),
        'fab fa-renren'                              => __( 'renren', 'buildr' ),
        'fas fa-reply'                               => __( 'reply', 'buildr' ),
        'fas fa-reply-all'                           => __( 'reply-all', 'buildr' ),
        'fab fa-replyd'                              => __( 'replyd', 'buildr' ),
        'fab fa-resolving'                           => __( 'resolving', 'buildr' ),
        'fas fa-retweet'                             => __( 'retweet', 'buildr' ),
        'fas fa-ribbon'                              => __( 'ribbon', 'buildr' ),
        'fas fa-road'                                => __( 'road', 'buildr' ),
        'fas fa-rocket'                              => __( 'rocket', 'buildr' ),
        'fab fa-rocketchat'                          => __( 'rocketchat', 'buildr' ),
        'fab fa-rockrms'                             => __( 'rockrms', 'buildr' ),
        'fas fa-rss'                                 => __( 'rss', 'buildr' ),
        'fas fa-rss-square'                          => __( 'rss-square', 'buildr' ),
        'fas fa-ruble-sign'                          => __( 'ruble-sign', 'buildr' ),
        'fas fa-rupee-sign'                          => __( 'rupee-sign', 'buildr' ),
        'fab fa-safari'                              => __( 'safari', 'buildr' ),
        'fab fa-sass'                                => __( 'sass', 'buildr' ),
        'fas fa-save'                                => __( 'save', 'buildr' ),
        'far fa-save'                                => __( 'save', 'buildr' ),
        'fab fa-schlix'                              => __( 'schlix', 'buildr' ),
        'fab fa-scribd'                              => __( 'scribd', 'buildr' ),
        'fas fa-search'                              => __( 'search', 'buildr' ),
        'fas fa-search-minus'                        => __( 'search-minus', 'buildr' ),
        'fas fa-search-plus'                         => __( 'search-plus', 'buildr' ),
        'fab fa-searchengin'                         => __( 'searchengin', 'buildr' ),
        'fas fa-seedling'                            => __( 'seedling', 'buildr' ),
        'fab fa-sellcast'                            => __( 'sellcast', 'buildr' ),
        'fab fa-sellsy'                              => __( 'sellsy', 'buildr' ),
        'fas fa-server'                              => __( 'server', 'buildr' ),
        'fab fa-servicestack'                        => __( 'servicestack', 'buildr' ),
        'fas fa-share'                               => __( 'share', 'buildr' ),
        'fas fa-share-alt'                           => __( 'share-alt', 'buildr' ),
        'fas fa-share-alt-square'                    => __( 'share-alt-square', 'buildr' ),
        'fas fa-share-square'                        => __( 'share-square', 'buildr' ),
        'far fa-share-square'                        => __( 'share-square', 'buildr' ),
        'fas fa-shekel-sign'                         => __( 'shekel-sign', 'buildr' ),
        'fas fa-shield-alt'                          => __( 'shield-alt', 'buildr' ),
        'fas fa-ship'                                => __( 'ship', 'buildr' ),
        'fas fa-shipping-fast'                       => __( 'shipping-fast', 'buildr' ),
        'fab fa-shirtsinbulk'                        => __( 'shirtsinbulk', 'buildr' ),
        'fas fa-shopping-bag'                        => __( 'shopping-bag', 'buildr' ),
        'fas fa-shopping-basket'                     => __( 'shopping-basket', 'buildr' ),
        'fas fa-shopping-cart'                       => __( 'shopping-cart', 'buildr' ),
        'fas fa-shower'                              => __( 'shower', 'buildr' ),
        'fas fa-sign'                                => __( 'sign', 'buildr' ),
        'fas fa-sign-in-alt'                         => __( 'sign-in-alt', 'buildr' ),
        'fas fa-sign-language'                       => __( 'sign-language', 'buildr' ),
        'fas fa-sign-out-alt'                        => __( 'sign-out-alt', 'buildr' ),
        'fas fa-signal'                              => __( 'signal', 'buildr' ),
        'fab fa-simplybuilt'                         => __( 'simplybuilt', 'buildr' ),
        'fab fa-sistrix'                             => __( 'sistrix', 'buildr' ),
        'fas fa-sitemap'                             => __( 'sitemap', 'buildr' ),
        'fab fa-skyatlas'                            => __( 'skyatlas', 'buildr' ),
        'fab fa-skype'                               => __( 'skype', 'buildr' ),
        'fab fa-slack'                               => __( 'slack', 'buildr' ),
        'fab fa-slack-hash'                          => __( 'slack-hash', 'buildr' ),
        'fas fa-sliders-h'                           => __( 'sliders-h', 'buildr' ),
        'fab fa-slideshare'                          => __( 'slideshare', 'buildr' ),
        'fas fa-smile'                               => __( 'smile', 'buildr' ),
        'far fa-smile'                               => __( 'smile', 'buildr' ),
        'fas fa-smoking'                             => __( 'smoking', 'buildr' ),
        'fab fa-snapchat'                            => __( 'snapchat', 'buildr' ),
        'fab fa-snapchat-ghost'                      => __( 'snapchat-ghost', 'buildr' ),
        'fab fa-snapchat-square'                     => __( 'snapchat-square', 'buildr' ),
        'fas fa-snowflake'                           => __( 'snowflake', 'buildr' ),
        'far fa-snowflake'                           => __( 'snowflake', 'buildr' ),
        'fas fa-sort'                                => __( 'sort', 'buildr' ),
        'fas fa-sort-alpha-down'                     => __( 'sort-alpha-down', 'buildr' ),
        'fas fa-sort-alpha-up'                       => __( 'sort-alpha-up', 'buildr' ),
        'fas fa-sort-amount-down'                    => __( 'sort-amount-down', 'buildr' ),
        'fas fa-sort-amount-up'                      => __( 'sort-amount-up', 'buildr' ),
        'fas fa-sort-down'                           => __( 'sort-down', 'buildr' ),
        'fas fa-sort-numeric-down'                   => __( 'sort-numeric-down', 'buildr' ),
        'fas fa-sort-numeric-up'                     => __( 'sort-numeric-up', 'buildr' ),
        'fas fa-sort-up'                             => __( 'sort-up', 'buildr' ),
        'fab fa-soundcloud'                          => __( 'soundcloud', 'buildr' ),
        'fas fa-space-shuttle'                       => __( 'space-shuttle', 'buildr' ),
        'fab fa-speakap'                             => __( 'speakap', 'buildr' ),
        'fas fa-spinner'                             => __( 'spinner', 'buildr' ),
        'fab fa-spotify'                             => __( 'spotify', 'buildr' ),
        'fas fa-square'                              => __( 'square', 'buildr' ),
        'far fa-square'                              => __( 'square', 'buildr' ),
        'fas fa-square-full'                         => __( 'square-full', 'buildr' ),
        'fab fa-stack-exchange'                      => __( 'stack-exchange', 'buildr' ),
        'fab fa-stack-overflow'                      => __( 'stack-overflow', 'buildr' ),
        'fas fa-star'                                => __( 'star', 'buildr' ),
        'far fa-star'                                => __( 'star', 'buildr' ),
        'fas fa-star-half'                           => __( 'star-half', 'buildr' ),
        'far fa-star-half'                           => __( 'star-half', 'buildr' ),
        'fab fa-staylinked'                          => __( 'staylinked', 'buildr' ),
        'fab fa-steam'                               => __( 'steam', 'buildr' ),
        'fab fa-steam-square'                        => __( 'steam-square', 'buildr' ),
        'fab fa-steam-symbol'                        => __( 'steam-symbol', 'buildr' ),
        'fas fa-step-backward'                       => __( 'step-backward', 'buildr' ),
        'fas fa-step-forward'                        => __( 'step-forward', 'buildr' ),
        'fas fa-stethoscope'                         => __( 'stethoscope', 'buildr' ),
        'fab fa-sticker-mule'                        => __( 'sticker-mule', 'buildr' ),
        'fas fa-sticky-note'                         => __( 'sticky-note', 'buildr' ),
        'far fa-sticky-note'                         => __( 'sticky-note', 'buildr' ),
        'fas fa-stop'                                => __( 'stop', 'buildr' ),
        'fas fa-stop-circle'                         => __( 'stop-circle', 'buildr' ),
        'far fa-stop-circle'                         => __( 'stop-circle', 'buildr' ),
        'fas fa-stopwatch'                           => __( 'stopwatch', 'buildr' ),
        'fab fa-strava'                              => __( 'strava', 'buildr' ),
        'fas fa-street-view'                         => __( 'street-view', 'buildr' ),
        'fas fa-strikethrough'                       => __( 'strikethrough', 'buildr' ),
        'fab fa-stripe'                              => __( 'stripe', 'buildr' ),
        'fab fa-stripe-s'                            => __( 'stripe-s', 'buildr' ),
        'fab fa-studiovinari'                        => __( 'studiovinari', 'buildr' ),
        'fab fa-stumbleupon'                         => __( 'stumbleupon', 'buildr' ),
        'fab fa-stumbleupon-circle'                  => __( 'stumbleupon-circle', 'buildr' ),
        'fas fa-subscript'                           => __( 'subscript', 'buildr' ),
        'fas fa-subway'                              => __( 'subway', 'buildr' ),
        'fas fa-suitcase'                            => __( 'suitcase', 'buildr' ),
        'fas fa-sun'                                 => __( 'sun', 'buildr' ),
        'far fa-sun'                                 => __( 'sun', 'buildr' ),
        'fab fa-superpowers'                         => __( 'superpowers', 'buildr' ),
        'fas fa-superscript'                         => __( 'superscript', 'buildr' ),
        'fab fa-supple'                              => __( 'supple', 'buildr' ),
        'fas fa-sync'                                => __( 'sync', 'buildr' ),
        'fas fa-sync-alt'                            => __( 'sync-alt', 'buildr' ),
        'fas fa-syringe'                             => __( 'syringe', 'buildr' ),
        'fas fa-table'                               => __( 'table', 'buildr' ),
        'fas fa-table-tennis'                        => __( 'table-tennis', 'buildr' ),
        'fas fa-tablet'                              => __( 'tablet', 'buildr' ),
        'fas fa-tablet-alt'                          => __( 'tablet-alt', 'buildr' ),
        'fas fa-tablets'                             => __( 'tablets', 'buildr' ),
        'fas fa-tachometer-alt'                      => __( 'tachometer-alt', 'buildr' ),
        'fas fa-tag'                                 => __( 'tag', 'buildr' ),
        'fas fa-tags'                                => __( 'tags', 'buildr' ),
        'fas fa-tape'                                => __( 'tape', 'buildr' ),
        'fas fa-tasks'                               => __( 'tasks', 'buildr' ),
        'fas fa-taxi'                                => __( 'taxi', 'buildr' ),
        'fab fa-telegram'                            => __( 'telegram', 'buildr' ),
        'fab fa-telegram-plane'                      => __( 'telegram-plane', 'buildr' ),
        'fab fa-tencent-weibo'                       => __( 'tencent-weibo', 'buildr' ),
        'fas fa-terminal'                            => __( 'terminal', 'buildr' ),
        'fas fa-text-height'                         => __( 'text-height', 'buildr' ),
        'fas fa-text-width'                          => __( 'text-width', 'buildr' ),
        'fas fa-th'                                  => __( 'th', 'buildr' ),
        'fas fa-th-large'                            => __( 'th-large', 'buildr' ),
        'fas fa-th-list'                             => __( 'th-list', 'buildr' ),
        'fab fa-themeisle'                           => __( 'themeisle', 'buildr' ),
        'fas fa-thermometer'                         => __( 'thermometer', 'buildr' ),
        'fas fa-thermometer-empty'                   => __( 'thermometer-empty', 'buildr' ),
        'fas fa-thermometer-full'                    => __( 'thermometer-full', 'buildr' ),
        'fas fa-thermometer-half'                    => __( 'thermometer-half', 'buildr' ),
        'fas fa-thermometer-quarter'                 => __( 'thermometer-quarter', 'buildr' ),
        'fas fa-thermometer-three-quarters'          => __( 'thermometer-three-quarters', 'buildr' ),
        'fas fa-thumbs-down'                         => __( 'thumbs-down', 'buildr' ),
        'far fa-thumbs-down'                         => __( 'thumbs-down', 'buildr' ),
        'fas fa-thumbs-up'                           => __( 'thumbs-up', 'buildr' ),
        'far fa-thumbs-up'                           => __( 'thumbs-up', 'buildr' ),
        'fas fa-thumbtack'                           => __( 'thumbtack', 'buildr' ),
        'fas fa-ticket-alt'                          => __( 'ticket-alt', 'buildr' ),
        'fas fa-times'                               => __( 'times', 'buildr' ),
        'fas fa-times-circle'                        => __( 'times-circle', 'buildr' ),
        'far fa-times-circle'                        => __( 'times-circle', 'buildr' ),
        'fas fa-tint'                                => __( 'tint', 'buildr' ),
        'fas fa-toggle-off'                          => __( 'toggle-off', 'buildr' ),
        'fas fa-toggle-on'                           => __( 'toggle-on', 'buildr' ),
        'fas fa-trademark'                           => __( 'trademark', 'buildr' ),
        'fas fa-train'                               => __( 'train', 'buildr' ),
        'fas fa-transgender'                         => __( 'transgender', 'buildr' ),
        'fas fa-transgender-alt'                     => __( 'transgender-alt', 'buildr' ),
        'fas fa-trash'                               => __( 'trash', 'buildr' ),
        'fas fa-trash-alt'                           => __( 'trash-alt', 'buildr' ),
        'far fa-trash-alt'                           => __( 'trash-alt', 'buildr' ),
        'fas fa-tree'                                => __( 'tree', 'buildr' ),
        'fab fa-trello'                              => __( 'trello', 'buildr' ),
        'fab fa-tripadvisor'                         => __( 'tripadvisor', 'buildr' ),
        'fas fa-trophy'                              => __( 'trophy', 'buildr' ),
        'fas fa-truck'                               => __( 'truck', 'buildr' ),
        'fas fa-truck-loading'                       => __( 'truck-loading', 'buildr' ),
        'fas fa-truck-moving'                        => __( 'truck-moving', 'buildr' ),
        'fas fa-tty'                                 => __( 'tty', 'buildr' ),
        'fab fa-tumblr'                              => __( 'tumblr', 'buildr' ),
        'fab fa-tumblr-square'                       => __( 'tumblr-square', 'buildr' ),
        'fas fa-tv'                                  => __( 'tv', 'buildr' ),
        'fab fa-twitch'                              => __( 'twitch', 'buildr' ),
        'fab fa-twitter'                             => __( 'twitter', 'buildr' ),
        'fab fa-twitter-square'                      => __( 'twitter-square', 'buildr' ),
        'fab fa-typo3'                               => __( 'typo3', 'buildr' ),
        'fab fa-uber'                                => __( 'uber', 'buildr' ),
        'fab fa-uikit'                               => __( 'uikit', 'buildr' ),
        'fas fa-umbrella'                            => __( 'umbrella', 'buildr' ),
        'fas fa-underline'                           => __( 'underline', 'buildr' ),
        'fas fa-undo'                                => __( 'undo', 'buildr' ),
        'fas fa-undo-alt'                            => __( 'undo-alt', 'buildr' ),
        'fab fa-uniregistry'                         => __( 'uniregistry', 'buildr' ),
        'fas fa-universal-access'                    => __( 'universal-access', 'buildr' ),
        'fas fa-university'                          => __( 'university', 'buildr' ),
        'fas fa-unlink'                              => __( 'unlink', 'buildr' ),
        'fas fa-unlock'                              => __( 'unlock', 'buildr' ),
        'fas fa-unlock-alt'                          => __( 'unlock-alt', 'buildr' ),
        'fab fa-untappd'                             => __( 'untappd', 'buildr' ),
        'fas fa-upload'                              => __( 'upload', 'buildr' ),
        'fab fa-usb'                                 => __( 'usb', 'buildr' ),
        'fas fa-user'                                => __( 'user', 'buildr' ),
        'far fa-user'                                => __( 'user', 'buildr' ),
        'fas fa-user-circle'                         => __( 'user-circle', 'buildr' ),
        'far fa-user-circle'                         => __( 'user-circle', 'buildr' ),
        'fas fa-user-md'                             => __( 'user-md', 'buildr' ),
        'fas fa-user-plus'                           => __( 'user-plus', 'buildr' ),
        'fas fa-user-secret'                         => __( 'user-secret', 'buildr' ),
        'fas fa-user-times'                          => __( 'user-times', 'buildr' ),
        'fas fa-users'                               => __( 'users', 'buildr' ),
        'fab fa-ussunnah'                            => __( 'ussunnah', 'buildr' ),
        'fas fa-utensil-spoon'                       => __( 'utensil-spoon', 'buildr' ),
        'fas fa-utensils'                            => __( 'utensils', 'buildr' ),
        'fab fa-vaadin'                              => __( 'vaadin', 'buildr' ),
        'fas fa-venus'                               => __( 'venus', 'buildr' ),
        'fas fa-venus-double'                        => __( 'venus-double', 'buildr' ),
        'fas fa-venus-mars'                          => __( 'venus-mars', 'buildr' ),
        'fab fa-viacoin'                             => __( 'viacoin', 'buildr' ),
        'fab fa-viadeo'                              => __( 'viadeo', 'buildr' ),
        'fab fa-viadeo-square'                       => __( 'viadeo-square', 'buildr' ),
        'fas fa-vial'                                => __( 'vial', 'buildr' ),
        'fas fa-vials'                               => __( 'vials', 'buildr' ),
        'fab fa-viber'                               => __( 'viber', 'buildr' ),
        'fas fa-video'                               => __( 'video', 'buildr' ),
        'fas fa-video-slash'                         => __( 'video-slash', 'buildr' ),
        'fab fa-vimeo'                               => __( 'vimeo', 'buildr' ),
        'fab fa-vimeo-square'                        => __( 'vimeo-square', 'buildr' ),
        'fab fa-vimeo-v'                             => __( 'vimeo-v', 'buildr' ),
        'fab fa-vine'                                => __( 'vine', 'buildr' ),
        'fab fa-vk'                                  => __( 'vk', 'buildr' ),
        'fab fa-vnv'                                 => __( 'vnv', 'buildr' ),
        'fas fa-volleyball-ball'                     => __( 'volleyball-ball', 'buildr' ),
        'fas fa-volume-down'                         => __( 'volume-down', 'buildr' ),
        'fas fa-volume-off'                          => __( 'volume-off', 'buildr' ),
        'fas fa-volume-up'                           => __( 'volume-up', 'buildr' ),
        'fab fa-vuejs'                               => __( 'vuejs', 'buildr' ),
        'fas fa-warehouse'                           => __( 'warehouse', 'buildr' ),
        'fab fa-weibo'                               => __( 'weibo', 'buildr' ),
        'fas fa-weight'                              => __( 'weight', 'buildr' ),
        'fab fa-weixin'                              => __( 'weixin', 'buildr' ),
        'fab fa-whatsapp'                            => __( 'whatsapp', 'buildr' ),
        'fab fa-whatsapp-square'                     => __( 'whatsapp-square', 'buildr' ),
        'fas fa-wheelchair'                          => __( 'wheelchair', 'buildr' ),
        'fab fa-whmcs'                               => __( 'whmcs', 'buildr' ),
        'fas fa-wifi'                                => __( 'wifi', 'buildr' ),
        'fab fa-wikipedia-w'                         => __( 'wikipedia-w', 'buildr' ),
        'fas fa-window-close'                        => __( 'window-close', 'buildr' ),
        'far fa-window-close'                        => __( 'window-close', 'buildr' ),
        'fas fa-window-maximize'                     => __( 'window-maximize', 'buildr' ),
        'far fa-window-maximize'                     => __( 'window-maximize', 'buildr' ),
        'fas fa-window-minimize'                     => __( 'window-minimize', 'buildr' ),
        'far fa-window-minimize'                     => __( 'window-minimize', 'buildr' ),
        'fas fa-window-restore'                      => __( 'window-restore', 'buildr' ),
        'far fa-window-restore'                      => __( 'window-restore', 'buildr' ),
        'fab fa-windows'                             => __( 'windows', 'buildr' ),
        'fas fa-wine-glass'                          => __( 'wine-glass', 'buildr' ),
        'fas fa-won-sign'                            => __( 'won-sign', 'buildr' ),
        'fab fa-wordpress'                           => __( 'wordpress', 'buildr' ),
        'fab fa-wordpress-simple'                    => __( 'wordpress-simple', 'buildr' ),
        'fab fa-wpbeginner'                          => __( 'wpbeginner', 'buildr' ),
        'fab fa-wpexplorer'                          => __( 'wpexplorer', 'buildr' ),
        'fab fa-wpforms'                             => __( 'wpforms', 'buildr' ),
        'fas fa-wrench'                              => __( 'wrench', 'buildr' ),
        'fas fa-x-ray'                               => __( 'x-ray', 'buildr' ),
        'fab fa-xbox'                                => __( 'xbox', 'buildr' ),
        'fab fa-xing'                                => __( 'xing', 'buildr' ),
        'fab fa-xing-square'                         => __( 'xing-square', 'buildr' ),
        'fab fa-y-combinator'                        => __( 'y-combinator', 'buildr' ),
        'fab fa-yahoo'                               => __( 'yahoo', 'buildr' ),
        'fab fa-yandex'                              => __( 'yandex', 'buildr' ),
        'fab fa-yandex-international'                => __( 'yandex-international', 'buildr' ),
        'fab fa-yelp'                                => __( 'yelp', 'buildr' ),
        'fas fa-yen-sign'                            => __( 'yen-sign', 'buildr' ),
        'fab fa-yoast'                               => __( 'yoast', 'buildr' ),
        'fab fa-youtube'                             => __( 'youtube', 'buildr' ),
        'fab fa-youtube-square'                      => __( 'youtube-square', 'buildr' ),
    );
	}
}

function automotive_listing_pagination_info($load, $get_holder){
	$Automotive_Plugin = Automotive_Plugin();
	$listings_amount   = automotive_listing_get_option('listings_amount', 1);

	if($load != false && !empty($load)){
		$paged        = absint($load);
		$load_number  = $Automotive_Plugin->current_query_info['total'];
	} else {
		$paged_var 	  = (isset($get_holder['paged']) && !empty($get_holder['paged']) ? $get_holder['paged'] : "");
		$paged     	  = (isset($paged_var) && !empty($paged_var) ? absint($paged_var) : (get_query_var("paged") ? get_query_var("paged") : 1));
		$load_number  = $Automotive_Plugin->current_query_info['total'];
	}

	$number = $load_number;
	$total  = ceil($number / $listings_amount);

	return array(
		'total' => $total,
		'paged' => $paged
	);
}

//********************************************
//	Pagination Boxes
//***********************************************************
if(!function_exists("page_of_box")){
	function page_of_box($load = false, $fake_get = null){
		$Automotive_Plugin = Automotive_Plugin();
		$listings_amount   = automotive_listing_get_option('listings_amount', 1);
		$top_pagination    = automotive_listing_get_option('top_pagination', 1);

		$return = "";

		$get_holder = (!is_null($fake_get) && !empty($fake_get) ? $fake_get : $_REQUEST);

		if($load != false && !empty($load)){
			$paged        = absint($load);
		} else {
			$paged_var 	  = (isset($get_holder['paged']) && !empty($get_holder['paged']) ? $get_holder['paged'] : "");
			$paged     	  = (isset($paged_var) && !empty($paged_var) ? absint($paged_var) : (get_query_var("paged") ? get_query_var("paged") : 1));
		}

		$number = $Automotive_Plugin->current_query_info['total'];
		$total  = ceil($number / $listings_amount);

		$style  = ($top_pagination ? "" : " style='display:none;'");

    $return .= '<a href="#" class="left-arrow' . ($paged == 1 ? " disabled" : "") . '"><i class="fa fa-angle-left"></i></a>';
    $return .= '<span>' . __("Page", "listings") . ' <span class="current_page">' . ($paged ? $paged : 1) . '</span> ' . __('of', 'listings') . ' <span class="total_pages">' . ($total == 0 ? 1 : $total) . '</span></span>';
    $return .= '<a href="#" class="right-arrow'. ($paged == $total || empty($listings_amount) ? " disabled" : "") . '"><i class="fa fa-angle-right"></i></a>';

		$filtered_return = apply_filters('automotive_inventory_top_pagination', array(
			'return'          => $return,
			'top_pagination'  => $top_pagination,
			'listings_amount' => $listings_amount,
			'total'           => $total,
			'before'          => '<div class="controls full page_of" data-page="' . ($paged ? $paged : 1) . '"' . $style . '>',
			'after'           => '</div>'
		));

		return $filtered_return['before'] . $filtered_return['return'] . $filtered_return['after'];
	}
}

if(!function_exists("bottom_page_box")){
	function bottom_page_box($layout = false, $load = false, $fake_get = null){
		$Automotive_Plugin = Automotive_Plugin();
		$bottom_pagination = automotive_listing_get_option('bottom_pagination', true);
		$listings_amount   = automotive_listing_get_option('listings_amount', 10);

		$return = "";

		if($bottom_pagination){
			$get_holder = (!is_null($fake_get) && !empty($fake_get) ? $fake_get : $_REQUEST);

			if($load != false && !empty($load)){
				$paged     = $load;
				$paged_var = get_query_var('paged');

				if(!isset($_REQUEST['action']) && $_REQUEST['action'] != "generate_new_view"){
					$paged   = (isset($paged_var) && !empty($paged_var) ? $paged_var : 1);
				}

				$load_number = $Automotive_Plugin->current_query_info['total'];
			} else {
				$paged_var   = (isset($get_holder['paged']) && !empty($get_holder['paged']) ? $get_holder['paged'] : "");
				$paged       = (isset($paged_var) && !empty($paged_var) ? $paged_var : (get_query_var("paged") ? get_query_var("paged") : 1));
				$load_number = $Automotive_Plugin->current_query_info['total'];

				// if any special layouts
				if($layout == "wide_left" || $layout == "boxed_left"){
					$additional_classes = "col-lg-offset-3";
					$cols = 9;
				} else {
					$cols = 12;
				}

				$return .= '<div class="col-lg-' . $cols . ' col-md-' . $cols . ' col-sm-12 col-xs-12 pagination_container' . (isset($additional_classes) && !empty($additional_classes) ? " " . $additional_classes : "") . '">';
			}

			$number = $load_number;
			$total = ceil($number / $listings_amount);

			$pagination_return = "\n
			<ul class=\"" . apply_filters("automotive_bottom_pagination_classes", "pagination margin-bottom-none margin-top-25 md-margin-bottom-none bottom_pagination") . "\">\n";

			$pagination_return .= "\t<li data-page='previous' class='" . ($paged > 1 ? "" : "disabled") . " previous'><a href='#'><i class='fa fa-angle-left'></i></a></li>\n";

			if($total == 0 || empty($listings_amount)){
				$pagination_return .= "\t<li data-page='1' class='disabled number'><a href='#'>1</a></li>\n";
			} else {
				$each_side = 3;

				if($total > (($each_side * 2) + 1)){

					// before numbers
					if($paged > ($each_side)){
						$before_start = ($paged - $each_side);
						$before_pages = (($before_start + $each_side) - 1);
						// echo "3 after";
					} else {
						$before_start = 1;
						$before_pages = (($paged - $each_side) + 2);
						// echo "less than 3 after";
					}

					// after numbers
					if($total < ($each_side + $paged)){
						$after_start = ($paged + 1);
						$after_pages = $total;
						// echo "less than 3 after";
					} else {
						$after_start = ($paged + 1);
						$after_pages = (($after_start + $each_side) - 1);
						// echo "3 after";
					}

					for($i = $before_start; $i <= $before_pages; $i++){
						$pagination_return .= "\t<li data-page='" . $i . "' class='number'><a href='#'>" . $i . "</a></li>\n";
					}

					$pagination_return .= "\t<li data-page='" . $paged . "' class='disabled number active'><a href='#'>" . $paged . "</a></li>\n";

					for($i = $after_start; $i <= $after_pages; $i++){
						$pagination_return .= "\t<li data-page='" . $i . "' class='number'><a href='#'>" . $i . "</a></li>\n";
					}
				} else {
					for($i = 1; $i <= $total; $i++){
						$active 						= ($paged == $i ? ' active' : '');
						$pagination_return .= "\t<li data-page='" . $i . "' class='" . ($paged != $i ? "" : "disabled") . " number" . $active . "'><a href='#'>" . $i . "</a></li>\n";
					}
				}
			}

			$pagination_return .= "\t<li data-page='next' class='" . ($paged < $total && !empty($listings_amount) ? "" : "disabled") . " next'><a href='#'><i class='fa fa-angle-right'></i></a></li>\n";

			$pagination_return .= "</ul>\n";

			$filtered_return = apply_filters('automotive_inventory_bottom_pagination', array(
				'total'           => $total,
				'number'          => $number,
				'listings_amount' => $listings_amount,
				'paged'           => $paged,
				'return'          => $pagination_return
			));

			$return .= $filtered_return['return'];
			$return .= "</div>\n";
		}

		return $return;
	}
}



add_action('wp_ajax_load_bottom_page_box', 'bottom_page_box');
add_action('wp_ajax_nopriv_load_bottom_page_box', 'bottom_page_box');

if(!function_exists("get_total_meta")){
	function get_total_meta($meta_key, $meta_value, $is_options = false, $show_sold = false){
		global $wpdb;

		if(!$is_options){
			if(Automotive_Plugin()->is_wpml_active()){
				$lang_var = ICL_LANGUAGE_CODE;
				$sql = $wpdb->prepare("SELECT count(DISTINCT pm.post_id)
					FROM $wpdb->postmeta pm
					JOIN $wpdb->posts p ON (p.ID = pm.post_id)
					JOIN {$wpdb->prefix}icl_translations wicl_translations
					WHERE pm.meta_key = %s
					AND wicl_translations.element_id = p.ID
					AND wicl_translations.language_code = %s
					AND pm.meta_value = %s
					AND p.post_type = 'listings'
					AND p.post_status = 'publish'
	        		" . ($show_sold == false ? "AND (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'car_sold' AND post_id = pm.post_id LIMIT 1) = 2" : "") . "
				", $meta_key, $lang_var, $meta_value);
			} else {
				$sql = $wpdb->prepare("SELECT count(DISTINCT pm.post_id)
					FROM $wpdb->postmeta pm
					JOIN $wpdb->posts p ON (p.ID = pm.post_id)
					WHERE pm.meta_key = %s
					AND pm.meta_value = %s
					AND p.post_type = 'listings'
					AND p.post_status = 'publish'
	        		" . ($show_sold == false ? "AND (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'car_sold' AND post_id = pm.post_id LIMIT 1) = 2" : "") . "
				", $meta_key, $meta_value);
			}
		} else {
			$sql = $wpdb->prepare("SELECT count(DISTINCT pm.post_id)
				FROM $wpdb->postmeta pm
				JOIN $wpdb->posts p ON (p.ID = pm.post_id)
				WHERE pm.meta_key = 'multi_options'
				AND pm.meta_value LIKE '%%%s%%'
				AND p.post_type = 'listings'
				AND p.post_status = 'publish'
			", $meta_value);
		}

		$count = $wpdb->get_var($sql);

		return $count;
	}
}

if(!function_exists("get_all_meta_values")){
	function get_all_meta_values( $key = '', $type = 'post', $status = 'publish', $show_sold = false ) {
    global $wpdb;

		$Automotive_Plugin = Automotive_Plugin();

    if( empty( $key ) ){
        return false;
    }

    $current_categories = $Automotive_Plugin->current_categories;
		$additional_queries = "";

		if(!empty($current_categories)){
			foreach($current_categories as $category_key => $category_value){
				$category_key = ($category_key == "yr" ? "year" : $category_key);

			    $additional_queries .= "AND (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '" . $category_key . "' AND post_id = pm.post_id LIMIT 1) = '" . $category_value . "' ";
			}

			$additional_queries = rtrim($additional_queries, " ");
		}

		if($Automotive_Plugin->is_wpml_active()){
			$lang_var = ICL_LANGUAGE_CODE;
	    $r = $wpdb->get_col( $wpdb->prepare( "
	        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
	        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			JOIN {$wpdb->prefix}icl_translations wicl_translations
	        WHERE pm.meta_key = '%s'
	        AND p.post_status = '%s'
	        AND p.post_type = '%s'
			AND wicl_translations.element_id = p.ID
			AND wicl_translations.language_code = %s
	        " . ($show_sold == false ? "AND (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'car_sold' AND post_id = pm.post_id LIMIT 1) = 2" : "") . "
	        " . (isset($additional_queries) ? $additional_queries : "") . "
	    ", $key, $status, $type, $lang_var ) );
		} else {
	    $r = $wpdb->get_col( $wpdb->prepare( "
	        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
	        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	        WHERE pm.meta_key = '%s'
	        AND p.post_status = '%s'
	        AND p.post_type = '%s'
	        " . ($show_sold == false ? "AND (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'car_sold' AND post_id = pm.post_id LIMIT 1) = 2" : "") . "
	        " . (isset($additional_queries) ? $additional_queries : "") . "
	    ", $key, $status, $type ) );
    }

	    return $r;
	}
}

function remove_shortcode_extras($code){
	$return = preg_replace( '%<p>\s*</p>%', '', $code );
	$old    = array( '<br />', '<br>' );
	$new    = array( '', '' );
	$return = str_replace( $old, $new, $return );

	return $return;
}

//********************************************
//	Plugin Modifications
//***********************************************************
if(!function_exists("ksort_deep")){
	function ksort_deep(&$array){
		ksort($array);
		foreach($array as $value)
			{if(is_array($value))
				{ksort_deep($value);}}
	}
}

//********************************************
//	Get All Post Meta
//***********************************************************
if( !function_exists("get_post_meta_all") ){
	function get_post_meta_all($post_id){
		global $wpdb;
		$data = array();
		$wpdb->query( "
			SELECT `meta_key`, `meta_value`
			FROM $wpdb->postmeta
			WHERE `post_id` = $post_id");

		foreach($wpdb->last_result as $k => $v){
			$data[$v->meta_key] =   $v->meta_value;
		};
		return $data;
	}
}

//********************************************
//	Shortcode / Widget Functions
//***********************************************************
if(!function_exists("testimonial_slider")){
	function testimonial_slider($content, $widget = array()){
		// remove br
		$content = str_replace("<br />", "", $content);

		$return  = "<div class='testimonial'>\n";
		$return .= "<ul class=\"testimonial_slider\">\n";
			if(empty($widget)){
				$return .= do_shortcode($content) . "\n";
			} else {

				foreach($widget as $fields){
					$return .= testimonial_slider_quote($fields['name'], $fields['content']) . "\n";
				}
			}
		$return .= "</ul>\n";
		$return .= "</div>\n";

		$return = remove_shortcode_extras($return);

		return $return;
	}
}

if(!function_exists("testimonial_slider_quote")){
	function testimonial_slider_quote($name, $content){
		$return  = "<li><blockquote class='style1'><span>" . wp_kses( $content, wp_kses_allowed_html('post'));
		$return .= "</span><strong>" . esc_html( $name ) . "</strong> ";
		$return .= "</blockquote></li>";

	    return $return;
	}
}

if(!function_exists("random_string")){
	function random_string($length = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}

function automotive_scroller_args($default_args){
	$q_args = array_merge(array(
		'limit'         => 25,
		'sort'          => 'newest',
		'listings'      => array(),
		'other_options' => array()
	), $default_args);

	$Automotive_Plugin = Automotive_Plugin();
	$Listing_Template  = Automotive_Plugin_Template();

	$data 												= array();
	$inventory_no_sold            = automotive_listing_get_option('inventory_no_sold', true);
	$sold_to_bottom               = automotive_listing_get_option('sold_to_bottom');

	if($sold_to_bottom){
		$data['car_sold'] = array(
				'key'     => 'car_sold',
				'compare' => 'EXISTS'
			);
	}

	$args = array(
		"post_type"      => "listings",
		"posts_per_page" => (int) $q_args['limit'],
		"post_status"    => "publish",
		'has_password'   => false,
		"orderby" 			 => array()
	);

	// sort be descending
	if($q_args['sort'] == "newest" || $q_args['sort'] == "related"){
		$args["order"] = "DESC";
	}

	// order by date
	if($q_args['sort'] == "newest" || $q_args['sort'] == "oldest"){
		$args["orderby"] = "date";
	}

	// sort by descending
	if($q_args['sort'] == "oldest"){
		$args["order"]   = "ASC";
	}

	// move sold listings to bottom
	if($sold_to_bottom){
		if(!is_array($args['orderby'])){
			$args['orderby'] = array(
				'car_sold' => 'DESC',
				'date'     => $args['order']
			);
		} else {
			$args['orderby']['car_sold'] = 'DESC';
		}
	}

	$related_category = 'related_category' . (defined("ICL_LANGUAGE_CODE") ? '_' . ICL_LANGUAGE_CODE : ''); // wpml compatible
	$related_option   = automotive_listing_get_option($related_category, false);

	// related listings
	if($q_args['sort'] == "related" && $related_option){
		$data['relation'] = "AND";
		$data[]           = array(
			"key" 	=> $related_option,
			"value" => $q_args['other_options']['related_val'],
		);

		unset($q_args['other_options']['related_val']);

		// don't include the current listing in the scroller
		if(isset($q_args['other_options']['current_id']) && !empty($q_args['other_options']['current_id'])){
			$args['post__not_in'] = array( $q_args['other_options']['current_id'] );
		}
	}

	// show only sold
	if(!$inventory_no_sold){
		$data['car_sold'] = array(
			"key"   => "car_sold",
			"value" => "2"
		);
	}

	// show only certain listings
	if(!empty($q_args['listings'])){
		$listing_ids      = explode(",", $q_args['listings']);
		$args['post__in'] = $listing_ids;
	}

	// filter with categories
	if(isset($q_args['other_options']['categories']) && !empty($q_args['other_options']['categories'])){
		$listing_categories = $Automotive_Plugin->get_listing_categories();

		foreach($q_args['other_options']['categories'] as $category => $value){
			$category = ($category == "yr" ? "year" : $category);
			$category_details = $Automotive_Plugin->get_single_listing_category($category);

			if(isset($listing_categories[$category]['terms'][$value]) && !empty($listing_categories[$category]['terms'][$value])){
				$data[] = array(
					"key"   => $category,
					"value" => $listing_categories[$category]['terms'][$value],
					"compare"     => html_entity_decode( $category_details['compare_value'] ),
					"type"        => ( isset( $category_details['is_number'] ) && $category_details['is_number'] ? "NUMERIC" : "CHAR" )
				);

				$data[] = array(
					"key"     => $category,
					"compare" => 'NOT IN',
					"value"   => array( '', 'None', 'none' )
				);
			}
		}

		unset($q_args['other_options']['categories']);
	}

	if(!empty($data)){
		$args['meta_query'] = $data;
	}

	$args = apply_filters('listing_scroller_args', $args);

	return $args;
}

if(!function_exists("vehicle_scroller")){
	function vehicle_scroller($title = "Recent Vehicles", $description = "Browse through the vast selection of vehicles that have been added to our inventory", $limit = -1, $sort = null, $listings = null, $other_options = array()){
		wp_enqueue_script( 'bxslider' );

		$Automotive_Plugin = Automotive_Plugin();
		$Listing_Template  = Automotive_Plugin_Template();

		$recent_related_vehicle_value = automotive_listing_get_option('recent_related_vehicle_value', true);

		$args = automotive_scroller_args(array(
			'limit'         => $limit,
			'sort'          => $sort,
			'listings'      => $listings,
			'other_options' => $other_options
		));

		$query = new WP_Query( $args );

    $main_image_dimensions = $Automotive_Plugin->get_automotive_image_sizes( 'auto_thumb' );
    $main_image_width      = $main_image_dimensions['width'];
    $main_image_height     = $main_image_dimensions['height'];

		// allow to customize width
		$other_options['slide-width'] = $main_image_width;

		$has_description = ( !empty($title) && !empty($description) );

		ob_start(); ?>
	    <div class="recent-vehicles-wrap">
			<div class="row">
				<?php if($has_description){ ?>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 recent-vehicles xs-padding-bottom-20">
	    			<div class="scroller_title margin-top-none"><?php echo esc_html( $title ); ?></div>
            <p><?php echo esc_html( $description ); ?></p>

            <div class="arrow3 clearfix" id="slideControls3"><span class="prev-btn"></span><span class="next-btn"></span></div>
	    		</div>
				<?php } ?>
	   			<div class="<?php echo ($has_description ? 'col-lg-10 col-md-10 col-sm-8' : 'col-lg-12 col-md-12 col-sm-2'); ?>">
	   				<?php
	   				$additional_attr = "";

						if(isset($other_options['categories'])){
							unset($other_options['categories']);
						}

	   				if(!empty($other_options)){
	   					foreach($other_options as $key => $option){
	   						$additional_attr .= "data-" . $key . "='" . esc_attr( $option ) . "' ";
	   					}
	   				}

	   				?>
					<div class="carousel-slider3" <?php echo (!empty($additional_attr) ? $additional_attr : ""); ?>>
						<?php
            while ( $query->have_posts() ) : $query->the_post();

							echo $Listing_Template->locate_template('shortcodes/vehicle_scroller_item');

							$args['post__not_in'][] = get_the_ID();

            endwhile;

						if(!$recent_related_vehicle_value && ($limit != $query->post_count)){
							$recent_related_vehicle_value_order = automotive_listing_get_option('recent_related_vehicle_value_order', true);

							$current_category       = $Automotive_Plugin->get_single_listing_category($related_option);
							$value_order            = ($recent_related_vehicle_value_order ? "ASC" : "DESC");

							if(isset($current_category['is_number']) && $current_category['is_number']){
								$data = $args['meta_query'];

                $temp_key   = $data[0]['key'];
                $temp_value = $data[0]['value'];

                $data[0] = array(
                    'relation' => 'OR',
                    array(
                        'key'       => $temp_key,
                        'value'     => $temp_value,
                        'compare'   => "<",
                        'type'      => 'numeric'
                    ),
                    array(
                        'key'       => $temp_key,
                        'value'     => $temp_value,
                        'compare'   => ">",
                        'type'      => 'numeric'
                    )
                );

								$args['meta_key'] = $related_option;
								$args['orderby']  = "meta_value_num";
							} else {
								$category_terms = (isset($current_category['terms']) && !empty($current_category['terms']) ? $current_category['terms'] : array());

								if(!empty($category_terms)){
									$use_values     = array();
									$category_terms = array_filter( $category_terms, 'is_not_null' );
									array_multisort( array_map( 'strtolower', $category_terms), $category_terms );

									$record_terms = ($value_order == "ASC" ? true : false);
									foreach($category_terms as $term){
										if($record_terms){
											$use_values[] = $term;

											if(isset($data[0]) && $term == $data[0]['value'] && $record_terms == true) {
												break;
											}
										} elseif($term == $data[0]['value'] && $record_terms == false) {
											$record_terms = true;
										}
									}

									$data[0]['compare'] = "IN";
									$data[0]['value']   = $use_values;
								}


								$args['meta_key'] = $related_option;
								$args['orderby']  = "meta_value";
							}

							$args['order']          = $value_order;

              $args['meta_query']     = $data;
              $args['posts_per_page'] = ($limit - $query->post_count);

              $query = new WP_Query( $args );

              while ( $query->have_posts() ) : $query->the_post();
								echo $Listing_Template->locate_template('shortcodes/vehicle_scroller_item');
              endwhile;
						}

	                    ?>
	                </div>

									<?php if(!$has_description){ ?>
										<div class="arrow3 clearfix" id="slideControls3"><span class="prev-btn"></span><span class="next-btn"></span></div>
									<?php } ?>
	    		</div>

	            <div class="clear"></div>
			</div>
	    </div>
	<?php

	wp_reset_query();

	return ob_get_clean();

	}
}

if( ! function_exists("vehicle_slide_item") ) {
  function vehicle_slide_item(){
		global $Listing_Template;

		echo $Listing_Template->locate_template('shortcodes/vehicle_scroller_item');
	}
}
//********************************************
//	Filter Listings
//***********************************************************
function filter_listing_results($var) {
	$Automotive_Plugin          = Automotive_Plugin();
	$Automotive_Plugin_Template = Automotive_Plugin_Template();

	$inventory_layout = (isset($_REQUEST['layout']) && !empty($_REQUEST['layout']) ? $_REQUEST['layout'] : false);
	$filter_showall   = automotive_listing_get_option('filter_showall', false);
	$listings_amount  = automotive_listing_get_option('listings_amount', 10);
	$paged            = (get_query_var('paged') ? get_query_var('paged') : false);

	// this is to replaced paged soon, just don't want to break anything yet.
	if(!$paged){
		$paged = (isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1);
	}

	// set current query info so we can retreive it elsewhere withou re-querying
	$Automotive_Plugin->set_current_query_info($_POST);

	// meta query with dashes
	if(!empty($args['meta_query'])){
		foreach($args['meta_query'] as $key => $meta){
			if(isset($args['meta_query'][$key]['value']) && !empty($args['meta_query'][$key]['value'])){
				$args['meta_query'][$key]['value'] = str_replace("%2D", "-", (isset($meta['value']) && !empty($meta['value']) ? $meta['value'] : ""));
			}
		}
	}
	$posts = $Automotive_Plugin->current_query_info['listings'];
	$total = $Automotive_Plugin->current_query_info['total'];

	$pagination = array(
		'current' => (int)(!$paged ? 1 : $paged),
		'total'   => (int)ceil($total / $listings_amount)
	);

	$return = '';

	if($total == 0){
		 $return .= do_shortcode('[alert type="2" close="No"]' . __("No listings found", "listings") . '[/alert]') . "<div class='clearfix'></div>";
	} else {
		foreach($posts as $post){
			$return .= $Automotive_Plugin_Template->locate_template("inventory_listing", array("id" => $post->ID, "layout" => $inventory_layout));
		}
	}

	$Automotive_Plugin->current_listing_categories($_POST);

	if($filter_showall){
		$dependancies = $Automotive_Plugin->process_dependancies( array(), false, array(), true);
	} else {
		$dependancies = $Automotive_Plugin->process_dependancies_plain( $_POST, false, array(), true);
	}

	$return_array = array(
    "content"      => $return,
    "number"       => $total,
    // "top_page"     => page_of_box($paged, false),
    "bottom_page"  => bottom_page_box(false, $paged, null),
    //"dependancies" => $Automotive_Plugin->process_dependancies($_POST),
    "dependancies" => $dependancies,
		"pagination"   => $pagination
	);

	// just some debug info
	if(isset($_REQUEST['debug'])){
    $return_array["args"] = $Automotive_Plugin->current_query_info['listing_args'];
	}

	// we will show all terms so no need for this
	if($filter_showall && !apply_filters('automotive_force_plain_processing', false)){
		unset($return_array['dependancies']);
	}

	// filter
	if(isset($filter) && !empty($filter)){
		$return_array['filter'] = $filter;
	}

	if($var === true){
		return wp_json_encode( $return_array );
	} else {
		echo wp_json_encode( $return_array );
	}

 	die();
}

add_action("wp_ajax_filter_listing", "filter_listing_results");
add_action("wp_ajax_nopriv_filter_listing", "filter_listing_results");


if(!function_exists("is_ajax_request")){
	function is_ajax_request(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		} else {
			return false;
		}
	}
}

function column_maker(){ ?>
	<div id='full_column' class='column_display_container' data-number='0'>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
        <div class='empty one'></div>
    </div>

    <br />

    <div class='generate_columns button'><?php _e("Generate Columns", "listings"); ?></div>

    <?php
	$i     = 1;
	$width = 31;

	while($i <= 12){
		echo "<div class='column_display_container insert' data-number='" . $i . "'><span class='label'>" . $i . ($i != 1 ? " / 12" : "") . "</span> <div class='full twelve' style='width: " . absint($i * $width) . "px;'></div></div><br />";
		$i++;
	}

	die;
}
add_action('wp_ajax_column_maker', 'column_maker');
add_action('wp_ajax_nopriv_column_maker', 'column_maker');

if(!function_exists("remove_editor")){
	function remove_editor() {
        // Visual Composer Frontend Editor Fix...
        if(!isset($_GET['vc_action'])){
            remove_post_type_support('listings', 'editor');
        }
	}
}
add_action('admin_init', 'remove_editor');

if(!function_exists("get_all_media_images")){
	function get_all_media_images(){
		$query_images_args = array(
			'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
		);

		$query_images = new WP_Query( $query_images_args );
		$images = array();

		foreach ( $query_images->posts as $image) {
			$images[]= wp_get_attachment_url( $image->ID );
		}

		return $images;
	}
}

if ( ! function_exists( "automotive_array_insert_after" ) ) {
	function automotive_array_insert_after( $key, &$array, $new_key, $new_value ) {
		if ( array_key_exists( $key, $array ) ) {
			$new = array();
			foreach ( $array as $k => $value ) {
				$new[ $k ] = $value;
				if ( $k === $key ) {
					$new[ $new_key ] = $new_value;
				}
			}

			return $new;
		}

		return false;
	}
}

//********************************************
//	Single Listing Template
//***********************************************************
add_filter( 'template_include', 'my_plugin_templates' );
function my_plugin_templates( $template ) {
    if ( is_singular( 'listings' ) && ! file_exists( get_stylesheet_directory() . '/single-listings.php' ) ){
        $template = LISTING_HOME . 'single-listings.php';
	} elseif( is_singular( 'listings_portfolio' ) ){
		if(file_exists( get_stylesheet_directory() . '/single-portfolio.php' )){
			$template = get_stylesheet_directory() . '/single-portfolio.php';
		} else {
			$template = LISTING_HOME . 'single-portfolio.php';
		}
	}

    return $template;
}

if(!function_exists("listing_form_replace_variables")){
	function listing_form_replace_variables($listing_id, $message){
		$Automotive_Plugin = Automotive_Plugin();

		$listing_categories = $Automotive_Plugin->get_listing_categories();

		if(!empty($listing_categories)){
			$listing_meta = $Automotive_Plugin->get_listing_meta($listing_id);

			foreach($listing_categories as $slug => $category){
				$message = str_replace("{" . $slug . "}", $listing_meta[$slug], $message);
			}
		}

		$message = str_replace('{requestdate}', date('Y-m-d H:i:s'), $message);

		return $message;
	}
}

/* Form */
if(!function_exists("listing_form")){
	function listing_form(){
		$Automotive_Plugin = Automotive_Plugin();

		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$form   = $_POST['form'];

		$errors  = array();
		$headers = array();
		$mail    = false;

		// email headers
		$headers[] = "Reply-To: ". $_POST['email'];
		$headers[] = "MIME-Version: 1.0";

		if(automotive_listing_get_option('email_html_content_type', true)){
			$headers[] = "Content-Type: text/html; charset=UTF-8";
		}

		$headers = apply_filters('automotive_listing_form_headers', $headers);

		$subject  = ucwords(str_replace("_", " ", $_POST['form']));

		if($form == "email_friend"){
			$nonce = wp_verify_nonce($_POST['nonce'], 'automotive-form-friend');

			// validate email
			if(!filter_var($_POST['friends_email'], FILTER_VALIDATE_EMAIL) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				$errors[] = __("Not a valid email", "listings");
			} else {
				$listing_id          = (isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : false);
				$post_meta           = $Automotive_Plugin->get_listing_meta($listing_id);
				$not_found_image 		 = automotive_listing_get_option('not_found_image', array());
				$default_value_price = automotive_listing_get_option('default_value_price', 'Price');
				$friend_subject		   = automotive_listing_get_option('friend_subject', '');
				$friend_layout		   = automotive_listing_get_option('friend_layout', '');

				$listing_options = (isset($post_meta['listing_options']) && !empty($post_meta['listing_options']) ? $post_meta['listing_options'] : array());
				$gallery_images  = (isset($post_meta['gallery_images']) && !empty($post_meta['gallery_images']) ? $post_meta['gallery_images'] : array());

				$name    = (isset($_POST['name']) && !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : "");
				$friend  = (isset($_POST['friends_email']) && !empty($_POST['friends_email']) ? sanitize_text_field($_POST['friends_email']) : "");
				$message = (isset($_POST['message']) && !empty($_POST['message']) ? sanitize_text_field($_POST['message']) : "");

				$gallery_image = (isset($gallery_images[0]) && !empty($gallery_images[0]) ? $gallery_images[0] : "");

				if(empty($gallery_images[0]) && isset($not_found_image['id']) && !empty($not_found_image['id'])){
					$gallery_image = $Automotive_Plugin->auto_image($not_found_image['id'], "auto_thumb", true);
				}

				if(!empty($gallery_image)){
					$thumbnail  = $Automotive_Plugin->auto_image($gallery_image, "auto_thumb", true);
				}

				$categories = $Automotive_Plugin->get_listing_categories();

				$table   = "<table width='100%' border='0' cellspacing='0' cellpadding='2'><tbody>";

				$table  .= "<tr>
					" . (!empty($gallery_image) ? '<td><img src="' . $thumbnail . '" style="max-width: 500px;"></td>' : "") . "
					<td style='font-weight:bold;color:#000;'>" . get_the_title($listing_id) . "</td>
					<td></td>
					<td>" . $default_value_price . ": " . $Automotive_Plugin->format_currency((isset($listing_options['price']['value']) ? $listing_options['price']['value'] : '')) . "</td>
				</tr>";

				foreach($categories as $category){
					$slug   = $category['slug'];
					$table .= "<tr><td>" . $Automotive_Plugin->display_listing_category($category['singular']) . ": </td><td> " . (isset($post_meta[$slug]) && !empty($post_meta[$slug]) ? $Automotive_Plugin->display_listing_category_term($post_meta[$slug], $slug) : __("N/A", "listings")) . "</td></tr>";
				}

				$table  .= "<tr>
								<td>&nbsp;</td>
								<td align='center' style='background-color:#000;font-weight:bold'><a href='" . get_permalink($listing_id) . "' style='color:#fff;text-decoration:none' target='_blank'>" . __('Click for more details', 'listings') . "</a></td>
							</tr>";

				$table  .= "</tbody></table>";

				$search  = array('{table}', '{message}', '{name}');
				$replace = array($table, $message, $name);

				$subject      = str_replace("{name}", $name, $friend_subject);
				$send_message = str_replace($search, $replace, $friend_layout);

				// validate nonce if enabled
				$recaptcha_enabled     = automotive_listing_get_option('recaptcha_enabled', false);
				$recaptcha_private_key = automotive_listing_get_option('recaptcha_private_key', false);

				if($recaptcha_enabled && $recaptcha_private_key){
					$recaptcha_check = automotive_recaptcha_check_request(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] ? $_POST['g-recaptcha-response'] : '');

					if(!isset($recaptcha_check->success) || !$recaptcha_check->success){
						$errors[] = __("reCAPTCHA was not valid, refresh the page and try again.", "listings");
					}
				}

				if(!empty($errors)){
					$mail     = false;
				} elseif($nonce){
					$mail         = wp_mail($friend, $subject, $send_message, $headers);

					do_action('automotive_email_friend', array(
						'name'         => $name,
						'friend_email' => $friend,
						'message'      => $message,
						'listing_id'   => $listing_id
					));
				} else {
					$mail     = false;
					$errors[] = __("Nonce was not valid, refresh the page and try again.", "listings");
				}
			}
		} else {

			// validate email
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				$errors[] = __("Not a valid email", "listings");
			} else {
				$link = (isset($_POST['id']) ? get_permalink($_POST['id']) : '#');

				switch ($form) {
					case 'request_info':
						$to          = automotive_listing_get_option('info_to', get_bloginfo('admin_email'));
						$subject     = automotive_listing_get_option('info_subject', '');
						$info_layout = automotive_listing_get_option('info_layout', '');

						$nonce = wp_verify_nonce($_POST['nonce'], 'automotive-form-request');

						$name           = (isset($_POST['name']) && !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : "");
						$contact_method = (isset($_POST['contact_method']) && !empty($_POST['contact_method']) ? sanitize_text_field($_POST['contact_method']) : "");
						$email          = (isset($_POST['email']) && !empty($_POST['email']) ? sanitize_text_field($_POST['email']) : "");
						$phone          = (isset($_POST['phone']) && !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : "");
						$comment        = (isset($_POST['comments']) && !empty($_POST['comments']) ? sanitize_text_field($_POST['comments']) : "");

						$table   = "<table border='0'>";
						$table  .= "<tr><td>" . __("First Name", "listings") . ": </td><td> " . $name . "</td></tr>";
						$table  .= "<tr><td>" . __("Contact Method", "listings") . ": </td><td> " . $contact_method . "</td></tr>";
						$table  .= "<tr><td>" . __("Phone", "listings") . ": </td><td> " . $phone . "</td></tr>";
						$table  .= "<tr><td>" . __("Email", "listings") . ": </td><td> " . $email . "</td></tr>";
						$table  .= "<tr><td>" . __("Question/Comment", "listings") . ": </td><td> " . $comment . "</td></tr>";
						$table  .= "</table>";

						$search  = array("{name}", "{contact_method}", "{email}", "{phone}", "{table}", "{link}", "{comment}");
						$replace = array($name, $contact_method, $email, $phone, $table, $link, $comment);

						$message = str_replace($search, $replace, $info_layout);
						$subject = str_replace($search, $replace, $subject);
					break;

					case 'schedule':
						$to           = automotive_listing_get_option('drive_to', get_bloginfo('admin_email'));
						$subject      = automotive_listing_get_option('drive_subject', '');
						$drive_layout = automotive_listing_get_option('drive_layout', '');

						$nonce = wp_verify_nonce($_POST['nonce'], 'automotive-form-schedule');

						$name           = (isset($_POST['name']) && !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : "");
						$contact_method = (isset($_POST['contact_method']) && !empty($_POST['contact_method']) ? sanitize_text_field($_POST['contact_method']) : "");
						$email          = (isset($_POST['email']) && !empty($_POST['email']) ? sanitize_text_field($_POST['email']) : "");
						$phone          = (isset($_POST['phone']) && !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : "");
						$best_day       = (isset($_POST['best_day']) && !empty($_POST['best_day']) ? sanitize_text_field($_POST['best_day']) : "");
						$best_time      = (isset($_POST['best_time']) && !empty($_POST['best_time']) ? sanitize_text_field($_POST['best_time']) : "");

						$table   = "<table border='0'>";
						$table  .= "<tr><td>" . __("Name", "listings") . ": </td><td> " . $name . "</td></tr>";
						$table  .= "<tr><td>" . __("Contact Method", "listings") . ": </td><td> " . $contact_method . "</td></tr>";
						$table  .= "<tr><td>" . __("Phone", "listings") . ": </td><td> " . $phone . "</td></tr>";
						$table  .= "<tr><td>" . __("Email", "listings") . ": </td><td> " . $email . "</td></tr>";
						$table  .= "<tr><td>" . __("Best Date", "listings") . ": </td><td> " . $best_day . " " . $best_time . "</td></tr>";
						$table  .= "</table>";

						$search  = array("{name}", "{contact_method}", "{email}", "{phone}", "{best_day}", "{best_time}", "{table}", "{link}");
						$replace = array($name, $contact_method, $email, $phone, $best_day, $best_time, $table, $link);

						$message = str_replace($search, $replace, $drive_layout);
						$subject = str_replace($search, $replace, $subject);
					break;

					case 'make_offer':
						$to           = automotive_listing_get_option('offer_to', get_bloginfo('admin_email'));
						$subject      = automotive_listing_get_option('offer_subject', '');
						$offer_layout = automotive_listing_get_option('offer_layout', '');

						$nonce = wp_verify_nonce($_POST['nonce'], 'automotive-form-offer');

						$name               = (isset($_POST['name']) && !empty($_POST['name']) ? sanitize_text_field($_POST['name']) : "");
						$contact_method     = (isset($_POST['contact_method']) && !empty($_POST['contact_method']) ? sanitize_text_field($_POST['contact_method']) : "");
						$email              = (isset($_POST['email']) && !empty($_POST['email']) ? sanitize_text_field($_POST['email']) : "");
						$phone              = (isset($_POST['phone']) && !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : "");
						$offered_price      = (isset($_POST['offered_price']) && !empty($_POST['offered_price']) ? sanitize_text_field($_POST['offered_price']) : "");
						$financing_required = (isset($_POST['financing_required']) && !empty($_POST['financing_required']) ? sanitize_text_field($_POST['financing_required']) : "");
						$other_comments     = (isset($_POST['other_comments']) && !empty($_POST['other_comments']) ? sanitize_text_field($_POST['other_comments']) : "");


						$table   = "<table border='0'>";
						$table  .= "<tr><td>" . __("Name", "listings") . ": </td><td> " . $name . "</td></tr>";
						$table  .= "<tr><td>" . __("Contact Method", "listings") . ": </td><td> " . $contact_method . "</td></tr>";
						$table  .= "<tr><td>" . __("Phone", "listings") . ": </td><td> " . $phone . "</td></tr>";
						$table  .= "<tr><td>" . __("Email", "listings") . ": </td><td> " . $email . "</td></tr>";
						$table  .= "<tr><td>" . __("Offered Price", "listings") . ": </td><td> " . $offered_price . "</td></tr>";
						$table  .= "<tr><td>" . __("Financing Required", "listings") . ": </td><td> " . $financing_required . "</td></tr>";
						$table  .= "<tr><td>" . __("Other Comments", "listings") . ": </td><td> " . $other_comments . "</td></tr>";
						$table  .= "</table>";

						$search  = array("{name}", "{contact_method}", "{email}", "{phone}", "{offered_price}", "{financing_required}", "{other_comments}", "{table}", "{link}");
						$replace = array($name, $contact_method, $email, $phone, $offered_price, $financing_required, $other_comments, $table, $link);

						$message = str_replace($search, $replace, $offer_layout);
						$subject = str_replace($search, $replace, $subject);
					break;

					case 'trade_in':
						$to           = automotive_listing_get_option('trade_to', get_bloginfo('admin_email'));
						$subject      = automotive_listing_get_option('trade_subject', '');
						$trade_layout = automotive_listing_get_option('trade_layout', '');

						$nonce = wp_verify_nonce($_POST['nonce'], 'automotive-form-tradein');

						$form_items = array(
							__("First Name", "listings") => "first_name",
							__("Last Name", "listings") => "last_name",
							__("Work Phone", "listings") => "work_phone",
							__("Phone", "listings") => "phone",
							__("Email", "listings") => "email",
							__("Contact Method", "listings") => "contact_method",
							__("Comments", "listings") => "comments",
							__("Options", "listings") => "options",
							__("Year", "listings") => "year",
							__("Make", "listings") => "make",
							__("Model", "listings") => "model",
							__("Exterior Colour", "listings") => "exterior_colour",
							__("VIN", "listings") => "vin",
							__("Kilometres", "listings") => "kilometres",
							__("Engine", "listings") => "engine",
							__("Doors", "listings") => "doors",
							__("Transmission", "listings") => "transmission",
							__("Drivetrain", "listings") => "drivetrain",
							__("Body Rating", "listings") => "body_rating",
							__("Tire Rating", "listings") => "tire_rating",
							__("Engine Rating", "listings") => "engine_rating",
							__("Transmission Rating", "listings") => "transmission_rating",
							__("Glass Rating", "listings") => "glass_rating",
							__("Interior Rating", "listings") => "interior_rating",
							__("Exhaust Rating", "listings") => "exhaust_rating",
							__("Rental Rating", "listings") => "rental_rating",
							__("Odometer Accurate", "listings") => "odometer_accurate",
							__("Service Records", "listings") => "service_records",
							__("Lienholder", "listings") => "lienholder",
							__("Titleholder", "listings") => "titleholder",
							__("Equipment", "listings") => "equipment",
							__("Vehiclenew", "listings") => "vehiclenew",
							__("Accidents", "listings") => "accidents",
							__("Damage", "listings") => "damage",
							__("Paint", "listings") => "paint",
							__("Salvage", "listings") => "salvage"
						);

						$table  = "<table border='0'>";
						foreach($form_items as $key => $single){
							$table .= "<tr><td>" . $key . ": </td><td> ";
							if($single == "options" && is_array($_POST[$single]) && isset($_POST[$single]) && !empty($_POST[$single])){
								$table .= rtrim(implode(", ", $_POST[$single]), ", ");
							} else {
								$table .= (isset($_POST[$single]) && !empty($_POST[$single]) ? $_POST[$single] : "");
							}

							$table .= "</td></tr>";
						}
						$table .= "</table>";

						$search   = array("{table}", "{link}");
						$replace  = array($table, $link);

						$message  = str_replace($search, $replace, $trade_layout);
						$subject  = str_replace($search, $replace, $subject);
					break;
				}

				// replace any listing variables
				if(isset($_POST['id'])){
					$subject = listing_form_replace_variables($_POST['id'], $subject);
					$message = listing_form_replace_variables($_POST['id'], $message);

					// if location email
					$location_email    = get_option("location_email");
					$location_category = $Automotive_Plugin->get_location_email_category();

					if(isset($location_email) && !empty($location_email) && isset($location_category) && !empty($location_category)){
				    $location_meta = get_post_meta( (int)$_POST['id'], $location_category, true );

						$to = (isset($location_email[$location_meta]) && !empty($location_email[$location_meta]) ? $location_email[$location_meta] : $to);
					}


					$send_user_email = get_post_meta((int)$_POST['id'], "frontend_send_email", true);
					$frontend_active = automotive_is_plugin_active('auto_frontend/index.php');

					if($send_user_email && $frontend_active){
						$listing_author = get_post_field("post_author", (int)$_POST['id']);
						$to             = get_the_author_meta( 'user_email', $listing_author );
					}
				}

				// validate nonce if enabled
				$recaptcha_enabled     = automotive_listing_get_option('recaptcha_enabled', false);
				$recaptcha_private_key = automotive_listing_get_option('recaptcha_private_key', false);

				if($recaptcha_enabled && $recaptcha_private_key){
					$recaptcha_check = automotive_recaptcha_check_request(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] ? $_POST['g-recaptcha-response'] : '');

					if(!isset($recaptcha_check->success) || !$recaptcha_check->success){
						$errors[] = __("reCAPTCHA was not valid, refresh the page and try again.", "listings");
					}
				}

				// nonce check
				if(!$nonce){
					$errors[] = __("Nonce was not valid, refresh the page and try again.", "listings");
				}

				if(empty($errors)){
					$mail = wp_mail($to, $subject, $message, $headers);

					// grab the listing tab forms data
					do_action('automotive_listing_single_listing_form_submit', array(
						'form'      => $form,
						'form_data' => $_POST
					));
				}
			}
		}

		if($mail && empty($errors)){
			echo wp_json_encode(
				array(
					"message" => __("Sent Successfully", "listings"),
					"status"  => "success"
				)
			);
		} else {
			$return_message  = "<ul class='error_list'>";
			$return_message .= "<li>" . automotive_listing_get_option('email_failure', "The email was not sent.") . "</li>";

			foreach($errors as $error){
				$return_message .= "<li>" . $error . "</li>";

			}
			$return_message .= "</ul>";

			echo wp_json_encode(
				array(
					"message" => $return_message,
					"status"  => "error"
				)
			);
		}

		die;
	}
}
add_action("wp_ajax_listing_form", "listing_form");
add_action("wp_ajax_nopriv_listing_form", "listing_form");

function automotive_is_plugin_active($plugin){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	return is_plugin_active($plugin);
}

function get_first_post_image($post) {
	preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = (isset($matches[1][0]) && !empty($matches[1][0]) ? $matches[1][0] : false);

	return $first_img;
}

function automotive_recaptcha_check_request($recaptcha_response){
	global $lwp_options;

	$return 			= array();
	$private_key 	= (isset($lwp_options['recaptcha_private_key']) && !empty($lwp_options['recaptcha_private_key']) ? $lwp_options['recaptcha_private_key'] : '');

	$recaptcha_request = wp_remote_post('https://www.google.com/recaptcha/api/siteverify',
		array(
			'method' 			=> 'POST',
			'timeout' 		=> 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' 		=> true,
			'headers' 		=> array(),
			'body' 				=> array(
				'secret' 	 => $private_key,
				'response' => $recaptcha_response,
				'remoteip' => $_SERVER["REMOTE_ADDR"]
			),
			'cookies' 		=> array()
    )
	);

	if ( is_wp_error( $recaptcha_request ) ) {
	   $error_message = $recaptcha_request->get_error_message();

	   echo "Something went wrong: " . esc_html($error_message);
	} else {
		$return = json_decode( wp_remote_retrieve_body($recaptcha_request) );
	}

	return $return;
}

function automotive_recaptcha_check(){
	$lookup = automotive_recaptcha_check_request($_POST["recaptcha_challenge_field"]);

	if(isset($lookup->success) && $lookup->success){
		esc_html_e("success", "listings");
	} else {
  	esc_html_e("The reCAPTCHA wasn't entered correctly. Go back and try it again.", "listings");// ."(reCAPTCHA said: " . $resp->error . ")");
	}

	die;
}
add_action("wp_ajax_recaptcha_check", "automotive_recaptcha_check");
add_action("wp_ajax_nopriv_recaptcha_check", "automotive_recaptcha_check");

function get_menu_parent_ID($menu_name, $post_id){
    if(!isset($menu_name)){
      return 0;
    }

    $menu_slug      = $menu_name;
    $locations      = get_nav_menu_locations();
    $menu_id        = $locations[$menu_slug];
    $menu_items     = wp_get_nav_menu_items($menu_id);
    $parent_item_id = wp_filter_object_list($menu_items,array('object_id'=>$post_id),'and','menu_item_parent');
    $parent_item_id = array_shift( $parent_item_id );

	if(!function_exists("automotive_check_parent_item")){
    function automotive_check_parent_item($parent_item_id,$menu_items){
      $parent_post_id = wp_filter_object_list( $menu_items, array( 'ID' => $parent_item_id ), 'and', 'object_id' );
      $parent_item_id = wp_filter_object_list($menu_items,array('ID'=>$parent_item_id),'and','menu_item_parent');
      $parent_item_id = array_shift( $parent_item_id );

      if($parent_item_id=="0"){
        $parent_post_id = array_shift($parent_post_id);

        return $parent_post_id;
      } else {
        return automotive_check_parent_item($parent_item_id,$menu_items);
      }
    }
    }
    if(!empty($parent_item_id)){
      return automotive_check_parent_item($parent_item_id,$menu_items);
    }else{
      return $post_id;
    }
}

add_filter('nav_menu_css_class', 'add_active_class', 10, 2 );

function add_active_class($classes, $item) {
    $inventory_parent_page = apply_filters('wpml_object_id', get_option("inventory_parent_page"), 'page');
    $portfolio_parent_page = apply_filters('wpml_object_id', get_option("portfolio_parent_page"), 'page');

    if($item->object_id == $inventory_parent_page && is_singular("listings")){
        $classes[] = "active";
    }

    if($item->object_id == $portfolio_parent_page && is_singular("listings_portfolio")){
        $classes[] = "active";
    }

    if($item->object_id == get_option( 'page_for_posts' ) && is_singular("listings") && ($key = array_search("current_page_parent", $classes)) !== false){
        unset($classes[$key]);
    }

    // add active class if needed
    if(in_array("current_page_parent", $classes)){
        $classes[] = "active";
    }

    return $classes;
}


function get_inventory_page_parent_id($options){
	if(isset($options['inventory_page']) && !empty($options['inventory_page'])){
		$parent_item = get_menu_parent_ID("header-menu", $options['inventory_page']);

	    update_option("inventory_parent_page", $parent_item);
	}

	if(isset($options['portfolio_page']) && !empty($options['portfolio_page'])){
		$parent_item = get_menu_parent_ID("header-menu", $options['portfolio_page']);

	    update_option("portfolio_parent_page", $parent_item);
	}
}
add_action("redux/options/listing_wp/saved", "get_inventory_page_parent_id");

// listing categories import
function import_listing_categories(){
    $demo_content = unserialize('a:18:{s:4:"year";a:7:{s:8:"singular";s:4:"Year";s:6:"plural";s:5:"Years";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:6:{i:2014;s:4:"2014";i:2013;s:4:"2013";i:2012;s:4:"2012";i:2010;s:4:"2010";i:2009;s:4:"2009";i:2015;s:4:"2015";}s:4:"slug";s:4:"year";}s:4:"make";a:7:{s:8:"singular";s:4:"Make";s:6:"plural";s:5:"Makes";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:1:{s:7:"porsche";s:7:"Porsche";}s:4:"slug";s:4:"make";}s:5:"model";a:7:{s:8:"singular";s:5:"Model";s:6:"plural";s:6:"Models";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:5:{s:7:"carrera";s:7:"Carrera";s:3:"gts";s:3:"GTS";s:7:"cayenne";s:7:"Cayenne";s:7:"boxster";s:7:"Boxster";s:5:"macan";s:5:"Macan";}s:4:"slug";s:5:"model";}s:10:"body-style";a:7:{s:8:"singular";s:10:"Body Style";s:6:"plural";s:11:"Body Styles";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:3:{s:11:"convertible";s:11:"Convertible";s:5:"sedan";s:5:"Sedan";s:22:"sports-utility-vehicle";s:22:"Sports Utility Vehicle";}s:4:"slug";s:10:"body-style";}s:7:"mileage";a:7:{s:8:"singular";s:7:"Mileage";s:6:"plural";s:8:"Mileages";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:4:"&lt;";s:5:"terms";a:10:{i:10000;s:5:"10000";i:20000;s:5:"20000";i:30000;s:5:"30000";i:40000;s:5:"40000";i:50000;s:5:"50000";i:60000;s:5:"60000";i:70000;s:5:"70000";i:80000;s:5:"80000";i:90000;s:5:"90000";i:100000;s:6:"100000";}s:4:"slug";s:7:"mileage";}s:12:"transmission";a:7:{s:8:"singular";s:12:"Transmission";s:6:"plural";s:13:"Transmissions";s:10:"filterable";s:1:"1";s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:8:{s:14:"6-speed-manual";s:14:"6-Speed Manual";s:17:"5-speed-automatic";s:17:"5-Speed Automatic";s:17:"8-speed-automatic";s:17:"8-Speed Automatic";s:17:"6-speed-semi-auto";s:17:"6-Speed Semi-Auto";s:17:"6-speed-automatic";s:17:"6-Speed Automatic";s:14:"5-speed-manual";s:14:"5-Speed Manual";s:17:"8-speed-tiptronic";s:17:"8-Speed Tiptronic";s:11:"7-speed-pdk";s:11:"7-Speed PDK";}s:4:"slug";s:12:"transmission";}s:12:"fuel-economy";a:7:{s:8:"singular";s:12:"Fuel Economy";s:6:"plural";s:14:"Fuel Economies";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:4:"&lt;";s:5:"terms";a:5:{i:10;s:2:"10";i:20;s:2:"20";i:30;s:2:"30";i:40;s:2:"40";i:50;s:2:"50";}s:4:"slug";s:12:"fuel-economy";}s:9:"condition";a:7:{s:8:"singular";s:9:"Condition";s:6:"plural";s:10:"Conditions";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:3:{s:9:"brand-new";s:9:"Brand New";s:13:"slightly-used";s:13:"Slightly Used";s:4:"used";s:4:"Used";}s:4:"slug";s:9:"condition";}s:8:"location";a:7:{s:8:"singular";s:8:"Location";s:6:"plural";s:9:"Locations";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:1:"=";s:5:"terms";a:1:{s:7:"toronto";s:7:"Toronto";}s:4:"slug";s:8:"location";}s:5:"price";a:9:{s:8:"singular";s:5:"Price";s:6:"plural";s:6:"Prices";s:10:"filterable";s:1:"1";s:14:"use_on_listing";i:0;s:13:"compare_value";s:4:"&lt;";s:8:"currency";s:1:"1";s:10:"link_value";s:5:"price";s:5:"terms";a:10:{i:10000;s:5:"10000";i:20000;s:5:"20000";i:30000;s:5:"30000";i:40000;s:5:"40000";i:50000;s:5:"50000";i:60000;s:5:"60000";i:70000;s:5:"70000";i:80000;s:5:"80000";i:90000;s:5:"90000";i:100000;s:6:"100000";}s:4:"slug";s:5:"price";}s:10:"drivetrain";a:7:{s:8:"singular";s:10:"Drivetrain";s:6:"plural";s:11:"Drivetrains";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:4:{s:3:"awd";s:3:"AWD";s:3:"rwd";s:3:"RWD";s:3:"4wd";s:3:"4WD";s:14:"drivetrain-rwd";s:14:"Drivetrain RWD";}s:4:"slug";s:10:"drivetrain";}s:6:"engine";a:7:{s:8:"singular";s:6:"Engine";s:6:"plural";s:7:"Engines";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:9:{s:7:"3-6l-v6";s:7:"3.6L V6";s:17:"4-8l-v8-automatic";s:17:"4.8L V8 Automatic";s:13:"4-8l-v8-turbo";s:13:"4.8L V8 Turbo";s:7:"4-8l-v8";s:7:"4.8L V8";s:7:"3-8l-v6";s:7:"3.8L V6";s:18:"2-9l-mid-engine-v6";s:18:"2.9L Mid-Engine V6";s:18:"3-4l-mid-engine-v6";s:18:"3.4L Mid-Engine V6";s:14:"3-0l-v6-diesel";s:14:"3.0L V6 Diesel";s:13:"3-0l-v6-turbo";s:13:"3.0L V6 Turbo";}s:4:"slug";s:6:"engine";}s:14:"exterior-color";a:7:{s:8:"singular";s:14:"Exterior Color";s:6:"plural";s:15:"Exterior Colors";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:10:{s:13:"racing-yellow";s:13:"Racing Yellow";s:23:"rhodium-silver-metallic";s:23:"Rhodium Silver Metallic";s:16:"peridot-metallic";s:16:"Peridot Metallic";s:17:"ruby-red-metallic";s:17:"Ruby Red Metallic";s:5:"white";s:5:"White";s:18:"aqua-blue-metallic";s:18:"Aqua Blue Metallic";s:23:"chestnut-brown-metallic";s:23:"Chestnut Brown Metallic";s:10:"guards-red";s:10:"Guards Red";s:18:"dark-blue-metallic";s:18:"Dark Blue Metallic";s:18:"lime-gold-metallic";s:18:"Lime Gold Metallic";}s:4:"slug";s:14:"exterior-color";}s:14:"interior-color";a:7:{s:8:"singular";s:14:"Interior Color";s:6:"plural";s:15:"Interior Colors";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:8:{s:14:"interior-color";s:14:"Interior Color";s:10:"agate-grey";s:10:"Agate Grey";s:15:"alcantara-black";s:15:"Alcantara Black";s:11:"marsala-red";s:11:"Marsala Red";s:5:"black";s:5:"Black";s:13:"platinum-grey";s:13:"Platinum Grey";s:11:"luxor-beige";s:11:"Luxor Beige";s:19:"black-titanium-blue";s:21:"Black / Titanium Blue";}s:4:"slug";s:14:"interior-color";}s:3:"mpg";a:8:{s:8:"singular";s:3:"MPG";s:6:"plural";s:3:"MPG";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:10:"link_value";s:3:"mpg";s:5:"terms";a:7:{s:14:"19-city-27-hwy";s:16:"19 city / 27 hwy";s:14:"16-city-24-hwy";s:16:"16 city / 24 hwy";s:14:"15-city-21-hwy";s:16:"15 city / 21 hwy";s:14:"18-city-26-hwy";s:16:"18 city / 26 hwy";s:14:"20-city-30-hwy";s:16:"20 city / 30 hwy";s:14:"20-city-28-hwy";s:16:"20 City / 28 Hwy";s:14:"19-city-29-hwy";s:16:"19 city / 29 hwy";}s:4:"slug";s:3:"mpg";}s:12:"stock-number";a:7:{s:8:"singular";s:12:"Stock Number";s:6:"plural";s:13:"Stock Numbers";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:12:{i:590388;s:6:"590388";i:590524;s:6:"590524";i:590512;s:6:"590512";i:590499;s:6:"590499";i:590435;s:6:"590435";i:590421;s:6:"590421";i:590476;s:6:"590476";i:590271;s:6:"590271";i:590497;s:6:"590497";i:16115;s:5:"16115";i:590124;s:6:"590124";i:590562;s:6:"590562";}s:4:"slug";s:12:"stock-number";}s:10:"vin-number";a:7:{s:8:"singular";s:10:"VIN Number";s:6:"plural";s:11:"VIN Numbers";s:10:"filterable";i:0;s:14:"use_on_listing";s:1:"1";s:13:"compare_value";s:1:"=";s:5:"terms";a:11:{s:17:"wp0cb2a92cs376450";s:17:"WP0CB2A92CS376450";s:17:"wp0ab2a74al092462";s:17:"WP0AB2A74AL092462";s:17:"wp1ad29p09la73659";s:17:"WP1AD29P09LA73659";s:17:"wp0ab2a74al079264";s:17:"WP0AB2A74AL079264";s:17:"wp0cb2a92cs754706";s:17:"WP0CB2A92CS754706";s:17:"wp0ca2a96as740274";s:17:"WP0CA2A96AS740274";s:17:"wp0ab2a74al060306";s:17:"WP0AB2A74AL060306";s:17:"wp1ad29p09la65818";s:17:"WP1AD29P09LA65818";s:17:"wp0ab2e81ek190171";s:17:"WP0AB2E81EK190171";s:17:"wp0cb2a92cs377324";s:17:"WP0CB2A92CS377324";s:17:"wp0ct2a92cs326491";s:17:"WP0CT2A92CS326491";}s:4:"slug";s:10:"vin-number";}s:7:"options";a:1:{s:5:"terms";a:40:{s:23:"adaptive-cruise-control";s:23:"Adaptive Cruise Control";s:7:"airbags";s:7:"Airbags";s:16:"air-conditioning";s:16:"Air Conditioning";s:12:"alarm-system";s:12:"Alarm System";s:21:"anti-theft-protection";s:21:"Anti-theft Protection";s:15:"audio-interface";s:15:"Audio Interface";s:25:"automatic-climate-control";s:25:"Automatic Climate Control";s:20:"automatic-headlights";s:20:"Automatic Headlights";s:15:"auto-start-stop";s:15:"Auto Start/Stop";s:19:"bi-xenon-headlights";s:19:"Bi-Xenon Headlights";s:18:"bluetoothr-handset";s:19:"Bluetooth® Handset";s:20:"boser-surround-sound";s:21:"BOSE® Surround Sound";s:25:"burmesterr-surround-sound";s:26:"Burmester® Surround Sound";s:18:"cd-dvd-autochanger";s:18:"CD/DVD Autochanger";s:9:"cdr-audio";s:9:"CDR Audio";s:14:"cruise-control";s:14:"Cruise Control";s:21:"direct-fuel-injection";s:21:"Direct Fuel Injection";s:22:"electric-parking-brake";s:22:"Electric Parking Brake";s:10:"floor-mats";s:10:"Floor Mats";s:18:"garage-door-opener";s:18:"Garage Door Opener";s:15:"leather-package";s:15:"Leather Package";s:25:"locking-rear-differential";s:25:"Locking Rear Differential";s:20:"luggage-compartments";s:20:"Luggage Compartments";s:19:"manual-transmission";s:19:"Manual Transmission";s:17:"navigation-module";s:17:"Navigation Module";s:15:"online-services";s:15:"Online Services";s:10:"parkassist";s:10:"ParkAssist";s:21:"porsche-communication";s:21:"Porsche Communication";s:14:"power-steering";s:14:"Power Steering";s:16:"reversing-camera";s:16:"Reversing Camera";s:20:"roll-over-protection";s:20:"Roll-over Protection";s:12:"seat-heating";s:12:"Seat Heating";s:16:"seat-ventilation";s:16:"Seat Ventilation";s:18:"sound-package-plus";s:18:"Sound Package Plus";s:20:"sport-chrono-package";s:20:"Sport Chrono Package";s:22:"steering-wheel-heating";s:22:"Steering Wheel Heating";s:24:"tire-pressure-monitoring";s:24:"Tire Pressure Monitoring";s:25:"universal-audio-interface";s:25:"Universal Audio Interface";s:20:"voice-control-system";s:20:"Voice Control System";s:14:"wind-deflector";s:14:"Wind Deflector";}}}');
	$update = update_option("listing_categories", $demo_content);

	if($update){
		update_option("show_listing_categories", "hide");
		_e("The listing categories have been imported.", "listings");
	} else {
		_e("There was an error importing the listing categories, please try again later.", "listings");
	}

	die;
}

add_action("wp_ajax_import_listing_categories", "import_listing_categories");


function convert_seo_string($string){
	global $post, $Listing;

	$categories = $Listing->get_listing_categories();
	$post_meta  = $Listing->get_listing_meta($post->ID);

	foreach($categories as $category){
	    $safe   = str_replace(" ", "_", $category['slug']);
	    $string = str_replace("%" . $safe . "%", (isset($post_meta[$safe]) && !empty($post_meta[$safe]) ? $post_meta[$safe] : ""), $string);
	}

	return $string;
}



function listing_main_sitemap_image($image, $post){

	if (isset($post) && get_post_type($post) == "listings") {
		$gallery_images = get_post_meta($post, "gallery_images", true);

		if(isset($gallery_images[0]) && !empty($gallery_images[0])){
			unset($gallery_images[0]);
		}

		if(!empty($gallery_images)){
			foreach($gallery_images as $gallery_image){
				$gallery_image_src = wp_get_attachment_image_src($gallery_image);

				$image[] = array(
					"src"   => $gallery_image_src[0],
					"title" => get_the_title($gallery_image)
				);
			}
		}
	}

    return $image;

}
add_action('wpseo_sitemap_urlimages', 'listing_main_sitemap_image', 10, 2);

// https://developer.yoast.com/customization/apis/metadata-api/
function automotive_yoast_custom_image_presenter(){
	if ( is_plugin_active( 'wordpress-seo/wp-seo.php' )){

		class Automotive_Custom_Image_Presenter extends Yoast\WP\SEO\Presenters\Abstract_Indexable_Tag_Presenter {
			protected $tag_format = '<meta property="og:image" content="%s" />';

			public function get() {
				global $post;

				$image          = "";
				$gallery_images = get_post_meta($post->ID, "gallery_images", true);

				if(isset($gallery_images[0])){
					$Automotive_Plugin   = Automotive_Plugin();

					if($Automotive_Plugin->is_hotlink()){
						$image = $gallery_images[0];
					} else {
						$gallery_image_src = wp_get_attachment_image_src($gallery_images[0], 'full');
						$image             = $gallery_image_src[0];
					}
				}


				return $image;
			}
		}

		function automotive_add_custom_presenter( $presenters ) {

			if(is_singular('listings')){
				foreach($presenters as $index => $presenter) {
					if ($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Image_Presenter) {
						$presenters[$index] = new Automotive_Custom_Image_Presenter();
					}
				}
			}

			return $presenters;
		}

		add_filter( 'wpseo_frontend_presenters', 'automotive_add_custom_presenter' );

	}
}
add_action('wp', 'automotive_yoast_custom_image_presenter');

function automotive_search_and_replace_seo_text($text){
	global $post;

	$listing_id = false;

	// determine the listing ID
	if(isset($post->ID) && get_post_type($post) == 'listings'){
		$listing_id = $post->ID;
	}

	if($listing_id){
		$Automotive_Plugin  = Automotive_Plugin();
		$listing_categories = $Automotive_Plugin->get_listing_categories();

		if(!empty($listing_categories)){
			foreach($listing_categories as $slug => $category){
				$text = str_replace("[" . $slug . "]", get_post_meta($listing_id, $slug, true), $text);
			}
		}
	}

	return $text;
}

function automotive_s_and_r_wpseo_metadesc($desc){
	return automotive_search_and_replace_seo_text($desc);
}
add_filter('wpseo_metadesc', 'automotive_s_and_r_wpseo_metadesc');
add_filter('wpseo_opengraph_desc', 'automotive_s_and_r_wpseo_metadesc');


//********************************************
//	RankMath SEO
//***********************************************************
function automotive_s_and_r_rankmath_desc($desc){
	return automotive_search_and_replace_seo_text($desc);
}
add_filter( 'rank_math/frontend/description', 'automotive_s_and_r_rankmath_desc');

function automotive_s_and_r_rankmath_title($desc){
	return automotive_search_and_replace_seo_text($desc);
}
add_filter( 'rank_math/frontend/title', 'automotive_s_and_r_rankmath_title');

// function automotive_rank_custom_image($attachment_url){
// 	global $post;
//
// 	$gallery_images = get_post_meta($post->ID, "gallery_images", true);
//
// 	if(isset($gallery_images[0])){
// 		$Automotive_Plugin = Automotive_Plugin();
//
// 		if($Automotive_Plugin->is_hotlink()){
// 			$attachment_url = $gallery_images[0];
// 		} else {
// 			$gallery_image_src = wp_get_attachment_image_src($gallery_images[0], 'full');
// 			$attachment_url    = $gallery_image_src[0];
// 		}
// 	}
//
// 	return $attachment_url;
// }
// add_filter( "rank_math/opengraph/facebook/image", "automotive_rank_custom_image");
// add_filter( "rank_math/opengraph/twitter/image", "automotive_rank_custom_image");


//********************************************
//	CarGurus Footer Script
//***********************************************************
function automotive_cargurus_footer_code(){
	$cargurus_min_rating = automotive_listing_get_option('cargurus_min_rating', 'GOOD_PRICE');
	$cargurus_style      = automotive_listing_get_option('cargurus_style', 'STYLE1');
	$cargurus_enabled    = automotive_listing_get_option('cargurus_badge', false);

	if($cargurus_enabled){ ?>
		<script>
		  var CarGurus = window.CarGurus || {}; window.CarGurus = CarGurus;
		  CarGurus.DealRatingBadge = window.CarGurus.DealRatingBadge || {};
			CarGurus.DealRatingBadge.options = {
			 "style": "<?php echo $cargurus_style; ?>",
			 "minRating": "<?php echo $cargurus_min_rating; ?>",
			 "defaultHeight": "60"
			};

			(function() {
		    var script = document.createElement('script');
		    script.src = "https://static.cargurus.com/js/api/en_US/1.0/dealratingbadge.js";
		    script.async = true;
		    var entry = document.getElementsByTagName('script')[0];
		    entry.parentNode.insertBefore(script, entry);
			})();
		</script>
	<?php
	}
}
add_action('wp_footer', 'automotive_cargurus_footer_code');


function hide_import_listing_categories(){
	update_option("show_listing_categories", "hide");

	die;
}

add_action("wp_ajax_hide_import_listing_categories", "hide_import_listing_categories");


function remove_parent_classes($class) {
	return ($class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor'  || $class == 'current-menu-item') ? false : true;
}

function add_class_to_wp_nav_menu($classes){
     switch (get_post_type()){
     	case 'listings_portfolio':
     		// we're viewing a custom post type, so remove the 'current_page_xxx and current-menu-item' from all menu items.
     		$classes = array_filter($classes, "remove_parent_classes");

     		break;
    }
	return $classes;
}
add_filter('nav_menu_css_class', 'add_class_to_wp_nav_menu');


function is_not_null ($var) { return !is_null($var); }

//********************************************
//	Add subscriber to mail chimp (WP-AJAX)
//***********************************************************
function automotive_add_mailchimp(){
	$email = wp_filter_nohtml_kses( $_POST['email'] );
	$nonce = (isset($_POST['nonce']) && !empty($_POST['nonce']) ? $_POST['nonce'] : "");

	if(wp_verify_nonce($nonce, 'automotive_add_mailchimp')){

        if(isset($email)){

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                _e("Not a valid email!", "listings");
                die;
            }

            global $lwp_options, $Listing;

						$api 							= $Listing->mailchimp();
						$is_double_opt_in = (isset($lwp_options['mailchimp_double_opt_in']) && !empty($lwp_options['mailchimp_double_opt_in']));
						$status 					= ($is_double_opt_in ? 'pending' : 'subscribed');

						if($api){
							$subscribe = $api->post("lists/" . $_POST['list'] . "/members", array(
								'email_address' => $email,
								'status'        => $status,
							));

	            if ($subscribe['status'] !== $status){
	                if($subscribe['status'] == 400){
	                    _e("Already subscribed.", "listings");
	                } else {
	                    _e("Unable to load listSubscribe()! Make sure the widget settings have been saved.\n", "listings");
	                    echo "\t<!--Code=".$api->getLastError()."-->\n";
	                }
	            } else {
									if($is_double_opt_in){
		                  _e("Subscribed - look for the confirmation email!\n", "listings");
									} else {
											_e("Subscribed!\n", "listings");
									}
	            }
						}
        } else {
            _e("Enter an email!", "listings");
        }
	} else {
	    _e("Nonce not valid, try again later", "listings");
	}

	die;
}

add_action('wp_ajax_add_mailchimp', 'automotive_add_mailchimp');
add_action('wp_ajax_nopriv_add_mailchimp', 'automotive_add_mailchimp');


if(!function_exists("get_page_by_slug")){
	function get_page_by_slug($page_slug, $output = OBJECT ) {
	  	global $wpdb;

	  	$post_type = 'listings';

   		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) );

	    if ( $page )
		    {return get_post($page, $output);}

	    return null;
	}
}

function wpcf7_update_email_body($contact_form) {
  $submission = WPCF7_Submission::get_instance();

  if ( $submission ) {
    $mail = $contact_form->prop('mail');
    $mail_2 = $contact_form->prop('mail_2');
    $additional_settings = $contact_form->prop('additional_settings');

    $data       = $submission->get_posted_data();
    // $listing_id = (isset($data['_listing_id']) && !empty($data['_listing_id']) ? $data['_listing_id'] : "");
		$listing_id = (isset($_REQUEST['_listing_id']) && !empty($_REQUEST['_listing_id']) ? $_REQUEST['_listing_id'] : "");


    if(!empty($listing_id)){
      $Automotive_Plugin      = Automotive_Plugin();
	    $listing_object         = get_post($listing_id);
	    $listing_search_replace = array();

	    if(isset($listing_object) && !empty($listing_object)){
	        $listing_details  = "Listing URL: " . get_permalink($listing_object->ID);
	        $listing_details .= "\nListing Title: " . $listing_object->post_title;

	        // listing categories
	        $listing_categories = $Automotive_Plugin->get_listing_categories(false);

	        if(!empty($listing_categories)){
	            foreach($listing_categories as $category){
	                $slug_tag = "[_" . $category['slug'] . "]";

                    $slug           = $category['slug'];
                    $category_value = get_post_meta($listing_id, $slug, true);

                    if(empty($category_value) && isset($category['is_number']) && $category['is_number'] == 1){
                        $post_meta[$slug] = 0;
                    } elseif(empty($post_meta[$slug])) {
                        $post_meta[$slug] = __("None", "listings");
                    }

                    // price
                    if(isset($category['currency']) && $category['currency'] == 1){
                        $category_value = $Automotive_Plugin->format_currency($category_value);
                    }

					$listing_search_replace[$slug_tag] = $category_value;
	            }
	        }

		    $listing_search_replace["[_listing_details]"] = $listing_details;

	        $listing_search_keys        = array_keys($listing_search_replace);
	        $listing_replace_values     = array_values($listing_search_replace);

	        // replace instances
	        $mail['subject']            = str_replace($listing_search_keys, $listing_replace_values, $mail['subject']);
	        $mail['sender']             = str_replace($listing_search_keys, $listing_replace_values, $mail['sender']);
	        $mail['body']               = str_replace($listing_search_keys, $listing_replace_values, $mail['body']);
	        $mail['recipient']          = str_replace($listing_search_keys, $listing_replace_values, $mail['recipient']);
	        $mail['additional_headers'] = str_replace($listing_search_keys, $listing_replace_values, $mail['additional_headers']);

	        // if mail_2
	        if($mail_2){
                $mail_2['subject']            = str_replace($listing_search_keys, $listing_replace_values, $mail_2['subject']);
                $mail_2['sender']             = str_replace($listing_search_keys, $listing_replace_values, $mail_2['sender']);
                $mail_2['body']               = str_replace($listing_search_keys, $listing_replace_values, $mail_2['body']);
                $mail_2['recipient']          = str_replace($listing_search_keys, $listing_replace_values, $mail_2['recipient']);
                $mail_2['additional_headers'] = str_replace($listing_search_keys, $listing_replace_values, $mail_2['additional_headers']);
	        }

	        if(strstr($additional_settings, "on_sent_ok") === false){
		        $additional_settings = 'on_sent_ok: "setTimeout(function(){ $.fancybox.close(); }, 2000);"';
		    }
		}

    // if location email
    $location_email    = get_option("location_email");
    $location_category = $Automotive_Plugin->get_location_email_category();
    $to = $mail['recipient'];

    if(isset($location_email) && !empty($location_email) && isset($location_category) && !empty($location_category)){
      $location_meta = get_post_meta( (int)$listing_id, $location_category, true );

      $to = (isset($location_email[$location_meta]) && !empty($location_email[$location_meta]) ? $location_email[$location_meta] : $to);
    }


    $send_user_email = get_post_meta((int)$listing_id, "frontend_send_email", true);
    $frontend_active = automotive_is_plugin_active('auto_frontend/index.php');

    if($send_user_email && $frontend_active){
      $listing_author = get_post_field("post_author", (int)$listing_id);
      $to             = get_the_author_meta( 'user_email', $listing_author );
    }

    $mail['recipient'] = $to;
	}

	$contact_form_properties = array('mail' => $mail, 'additional_settings' => $additional_settings);

    if($mail_2){
        $contact_form_properties['mail_2'] = $mail_2;
    }

    $contact_form->set_properties($contact_form_properties);
  }
}
add_action('wpcf7_before_send_mail', 'wpcf7_update_email_body');

//********************************************
//	Add CF7 Tags
//***********************************************************
function cf7_add_automotive_tags( $hook ) {
    if ( in_array($hook, array('toplevel_page_wpcf7', 'contact_page_wpcf7-new'))) {
        global $Listing;

        wp_enqueue_script( 'cf7_additional_tags', JS_DIR . 'cf7.js', array(), '1.0' );

        $listing_categories_slugs = wp_list_pluck($Listing->get_listing_categories(false), "slug");

        wp_localize_script("cf7_additional_tags", "listing_categories", $listing_categories_slugs);
    }
}
add_action( 'admin_enqueue_scripts', 'cf7_add_automotive_tags' );

//********************************************
//	Featured Vehicle Widget
//***********************************************************
function featured_vehicle_widget(){
	global $Listing, $lwp_options;

	if(is_admin()){
		return;
	}

	$side        = (isset($lwp_options['featured_vehicle_widget_side']) && $lwp_options['featured_vehicle_widget_side'] == 1 || !isset($lwp_options['featured_vehicle_widget_side']) ? "left" : "right");
	$button_text = automotive_listing_get_option('featured_vehicle_widget_text', 'Check it out');

	$get_listings = get_posts(
		array(
			"post_type"      => "listings",
			"posts_per_page" => -1,
			"meta_key"       => "car_featured",
			"meta_value"     => "1"
		)
	); ?>
	<div id="featured_vehicles_widget" class="<?php echo $side; ?>">
		<?php
		if(!empty($get_listings)){
			echo "<ul class='listings'>";
			foreach($get_listings as $listing){
				$post_meta       = $Listing->get_listing_meta($listing->ID);

				$gallery_images  = (isset($post_meta['gallery_images']) && !empty($post_meta['gallery_images']) ? $post_meta['gallery_images'] : "");
				$price           = (isset($post_meta['listing_options']['price']['value']) && !empty($post_meta['listing_options']['price']['value']) ? $post_meta['listing_options']['price']['value'] : "");

				if(isset($gallery_images) && !empty($gallery_images) && !empty($gallery_images[0])){
	                $thumbnail 		 = $Listing->auto_image($gallery_images[0], "auto_thumb", true);
	            } elseif(empty($gallery_images[0]) && isset($lwp_options['not_found_image']['url']) && !empty($lwp_options['not_found_image']['url'])){
	                $thumbnail 		 = $lwp_options['not_found_image']['url'];
	            } else {
	                $thumbnail 		 = LISTING_DIR . "images/pixel.gif";
	            }

				echo "<li><img src='" . $thumbnail . "' class='listing_thumb'> ";
				echo "<div class='listing_title'>" . $listing->post_title . "</div>";
				echo "<div class='listing_price'>" . $Listing->format_currency($price) . "</div>";

				echo "<a href='" . get_permalink($listing->ID) . "'><button class='listing_button'>" . esc_html($button_text) . "</button></a>";

				echo "</li>";
			}
			echo "</ul>";
		} else {
			_e("No Listings", "listings");
		}

		?>

		<div class="next" data-next-text="<?php _e("More Listings", "listings"); ?>"></div>
		<div class="hover_hint"><?php _e("Hover here for more", "listings"); ?></div>
	</div>
<?php
}

if(isset($lwp_options['featured_vehicle_widget']) && $lwp_options['featured_vehicle_widget'] == 1){
	add_action("wp_footer", "featured_vehicle_widget");
}

if( ! function_exists("sanitize_html_classes") ){
    function sanitize_html_classes($classes, $sep = " "){
        $return = "";

        if(!is_array($classes)) {
            $classes = explode($sep, $classes);
        }

        if(!empty($classes)){
            foreach($classes as $class){
                $return .= sanitize_html_class($class) . " ";
            }
        }

        return $return;
    }
}

// this fixes the issue for when WordPress redirects the user to /page/2 instead of keeping page=2
function selective_inventory_page_disable_canonical_redirect( $query ) {
    global $lwp_options;

    $permalinkSettings = get_option('permalink_structure');

    if(!empty($permalinkSettings) && !empty($lwp_options['inventory_page'])){
        $inventoryID = abs($lwp_options['inventory_page']);

        if($query->queried_object_id == $inventoryID){
            remove_filter( 'template_redirect', 'redirect_canonical' );
        }
    }
}
add_action( 'parse_query', 'selective_inventory_page_disable_canonical_redirect' );

if ( ! function_exists( "automotive_listing_generate_search_dropdown" ) ) {
	function automotive_listing_generate_search_dropdown( $items, $min_max, $options = array() ) {
		$Automotive_Plugin = Automotive_Plugin();

		$return = "";

		// is dropdown in the min/max part
		$min_max = explode( ",", $min_max );

		$min_text = __( "Min", "listings" );
		$max_text = __( "Max", "listings" );

		$dependancies = $Automotive_Plugin->process_dependancies_plain();

		$is_multiselect = automotive_listing_get_option('fiautomotive_image_sizesshowall', false) && automotive_listing_get_option('filter_multiselect', false);

		if ( ! empty( $items ) ) {
			foreach ( $items as $column_item ) {
				$column_item = trim( $column_item );
				$safe_name   = $column_item;

				$current_category = $Automotive_Plugin->get_single_listing_category( $safe_name );

				if((isset($current_category['slug']) && !empty($current_category['slug'])) || strtolower( $column_item ) == "search") {
					if ( in_array( $column_item, $min_max ) ) {
						//$return .= "min/max";
						//$

						$other_options_min = array(
							"select_name"    => ( $safe_name == "year" ? "yr" : $safe_name ) . "[]",
							"select_label"   => $min_text . " " . $Automotive_Plugin->display_listing_category($current_category['singular']),
							"is_multiselect" => false
						);

						$other_options_max = array(
							"select_name"    => ( $safe_name == "year" ? "yr" : $safe_name ) . "[]",
							"select_label"   => $max_text . " " . $Automotive_Plugin->display_listing_category($current_category['singular']),
							"is_multiselect" => false
						);

						if(isset($current_category['show_amount']) && $current_category['show_amount']){
							$other_options['show_amount'] = (isset($dependancies[1][$current_category['slug']]) && !empty($dependancies[1][$current_category['slug']]) ? $dependancies[1][$current_category['slug']] : array());
						}

						$return .= "<div class='multiple_dropdowns'>";
						$return .= "<div class=\"my-dropdown make-dropdown\">";
						ob_start();
						$Automotive_Plugin->listing_dropdown( $current_category, "", "css-dropdowns", ( isset( $dependancies[0][ $column_item ] ) && ! empty( $dependancies[0][ $column_item ] ) ? $dependancies[0][ $column_item ] : array() ), $other_options_min );
						$return .= ob_get_clean();
						$return .= "</div>";

						$return .= '<span class="my-dropdown-between">' . __( 'to', 'listings' ) . '</span>';

						$return .= "<div class=\"my-dropdown make-dropdown\">";
						ob_start();
						$Automotive_Plugin->listing_dropdown( $current_category, "", "css-dropdowns", ( isset( $dependancies[0][ $column_item ] ) && ! empty( $dependancies[0][ $column_item ] ) ? $dependancies[0][ $column_item ] : array() ), $other_options_max );
						$return .= ob_get_clean();
						$return .= "</div>";
						$return .= "</div>";
					} else {
						if ( strtolower( $column_item ) != "search" ) {
							$current_category = $Automotive_Plugin->get_single_listing_category( $safe_name );

							$term_form   = (isset($options['term_form']) && !empty($options['term_form']) && $options['term_form'] == "plural" ? $Automotive_Plugin->display_listing_category($current_category['plural']) : $Automotive_Plugin->display_listing_category($current_category['singular']) );
							$prefix_text = (isset($options['prefix_text']) && !empty($options['prefix_text']) ? $options['prefix_text'] : "");
							$prefix_term = (!empty($prefix_text) ? $prefix_text . " " : "") . $term_form;

							$return .= '<div class="my-dropdown ' . $safe_name . '-dropdown">';

							$other_options = array( "select_label" => $prefix_term );

							if($is_multiselect){
								$other_options['select_name'] = ($current_category['slug']  === "year" ? "yr" : $current_category['slug'] )  . "[]";
							}

							if(isset($current_category['show_amount']) && $current_category['show_amount']){
								$other_options['show_amount'] = (isset($dependancies[1][$current_category['slug']]) && !empty($dependancies[1][$current_category['slug']]) ? $dependancies[1][$current_category['slug']] : array());
							}

							ob_start();
							$Automotive_Plugin->listing_dropdown( $current_category, $prefix_text, "css-dropdowns", ( isset( $dependancies[0][ $safe_name ] ) && ! empty( $dependancies[0][ $safe_name ] ) ? $dependancies[0][ $safe_name ] : array() ), $other_options );
							$return .= ob_get_clean();
							$return .= '</div>';
						} else {
							$return .= "<input class='full-width' type='search' name='keywords' value='' placeholder='" . __( "Refine with keywords", "listings" ) . "'>";
						}
					}
				}
			}
		}

		return $return;
	}
}

function automotive_uniqid($id){
	return esc_attr($id . "_" . uniqid());
}

//********************************************
//	Save Revolution Slider Template
//***********************************************************
function save_rev_template(){
	$automotive_rev_slider_templates = get_option("automotive_rev_slider_templates");
	$template_name                   = sanitize_text_field($_POST['template_name']);
	$options                         = $_POST['options'];

	if(!empty($template_name) && !empty($options)){
		if(empty($automotive_rev_slider_templates)){
			$automotive_rev_slider_templates = array();
		}

		$automotive_rev_slider_templates[] = array(
			"name"    => $template_name,
			"options" => $options
		);

		update_option("automotive_rev_slider_templates", $automotive_rev_slider_templates);

		_e("Successfully added the new template.", "listings");
	} else {
		_e("There was an error adding the template, try again later.", "listings");
	}

	die;
}
add_action("wp_ajax_save_rev_template", "save_rev_template");

//********************************************
//	Update Revolution Slider Template
//***********************************************************
function update_rev_template(){
	$automotive_rev_slider_templates = get_option("automotive_rev_slider_templates");
	$template_id                     = sanitize_text_field($_POST['template_id']);
	$options                         = $_POST['options'];

	if(isset($automotive_rev_slider_templates[$template_id])){
		$automotive_rev_slider_templates[$template_id]['options'] = $options;

		update_option("automotive_rev_slider_templates", $automotive_rev_slider_templates);

		_e("Successfully updating the existing template.", "listings");
	} else {
		_e("There was an error updating the existing template., try again later", "listings");
	}

	die;
}
add_action("wp_ajax_update_rev_template", "update_rev_template");

//********************************************
//	Generate Revolution Slider Styling
//***********************************************************
function rev_generate_styling($options){
	$styling = "";

	$attr_associations = array(
		"rev_background_color"      => array(
			"attr" => "background-color",
			"type" => "color"
		),
		"rev_width"                 => array(
			"attr" => "width",
			"type" => "px"
		),
		"rev_padding_vertical"      => array(
			"attr" => array(
				"padding-top",
				"padding-bottom"
			),
			"type" => "px"
		),
		"rev_padding_horizontal"    => array(
			"attr" => array(
				"padding-left",
				"padding-right"
			),
			"type" => "px"
		),
		"rev_border_radius"         => array(
			"attr" => "border-radius",
			"type" => "px"
		),
		"rev_border_width"          => array(
			"attr" => "border-width",
			"type" => "px"
		),
		"rev_border_style"          => array(
			"attr" => "border-style",
			"type" => "normal"
		),
		"rev_border_color"          => array(
			"attr" => "border-color",
			"type" => "color"
		),
		"rev_text_color"            => array(
			"attr" => "color",
			"type" => "color"
		),
		"rev_font_size"             => array(
			"attr" => "font-size",
			"type" => "px"
		),
		"rev_font_family"           => array(
			"attr" => "font-family",
			"type" => "normal"
		)
	);

	if(!empty($options)){
		foreach($options as $option => $value){
			$suffix = "";
			$prefix = "";

			if(isset($attr_associations[$option]['type']) && $attr_associations[$option]['type'] == "px"){
				$suffix = "px";
			}

			if(isset($attr_associations[$option]['attr']) && is_array($attr_associations[$option]['attr']) && !empty($value)){
				foreach($attr_associations[$option]['attr'] as $single_attr){
					$styling .= $single_attr . ": " . $prefix . $value . $suffix . " !important; ";
				}
			} elseif(isset($attr_associations[$option]['attr']) && !empty($value)) {
				$styling .= $attr_associations[$option]['attr'] . ": " . $prefix . $value . $suffix . "" . ($option !== 'rev_width' ? ' !important' : '') . "; ";
			}
		}
	}

	$styling .= " box-sizing: content-box; white-space: normal;";

	return $styling;
}

if(!function_exists('automotive_generate_attrs')){
	function automotive_generate_attrs($attrs){
		$attr_string = '';

		if(!empty($attrs)){
			foreach($attrs as $attr => $attr_value){
				$attr_string .= sanitize_title_with_dashes($attr) . '="' . esc_attr($attr_value) . '" ';
			}

			$attr_string = rtrim($attr_string, ' ');
		}

		return $attr_string;
	}
}

//********************************************
//	Revolution Slider Shortcode
//***********************************************************
function rev_slider_listing_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'id'        => '',
		'template'  => ''
	), $atts ) );

	global $Listing, $lwp_options;

	ob_start();

	$automotive_rev_slider_templates = get_option("automotive_rev_slider_templates");

	$id         = (int)$id;
	$template   = (int)(isset($template) && !empty($template) ? $template : 0);

	if(!empty($id)){
		$options    = (isset($automotive_rev_slider_templates[$template]['options']) ? $automotive_rev_slider_templates[$template]['options'] : []);
		$type       = (isset($options['type']) && !empty($options['type']) ? $options['type'] : "text");

        $listing_categories    = $Listing->get_listing_categories();
        $listing_post_meta     = $Listing->get_listing_meta($id);
        $listing_title         = get_post_field("post_title", $id);

		if($type == "text"){
			$text = (isset($options['text']) ? $options['text'] : '');

			if(!empty($listing_categories)){
				foreach($listing_categories as $slug => $category){
					$category_value = (isset($listing_post_meta[$slug]) && !empty($listing_post_meta[$slug]) ? $listing_post_meta[$slug] : "");

					if(isset($category['link_value']) && $category['link_value'] == "price"){
						$category_value = $Listing->format_currency($category_value);
					}

					$text = str_replace("%%" . $slug . "%%", $category_value, $text);
				}
			}

			echo "<a href='" . get_permalink($id) . "'><div style='" . rev_generate_styling($options) . "'>" . $text . "</div></a>";
		} else {
			$listing_details = $Listing->get_use_on_listing_categories();

	        if ( count( $listing_details ) > 5 ) {
		        $listing_data_categories = array_slice( $listing_details, 0, 5, true );
	        } else {
		        $listing_data_categories = $listing_details;
	        }

			echo '<div style="' . rev_generate_styling($options) . '"><div class="inventory clearfix">

				<a class="inventory" href="' . get_permalink($id) . '" style="color: ' . $options['rev_text_color'] . ';">
					<div class="title">' . $listing_title . '</div>

					<table class="options-primary">
						<tbody>';

						if(!empty($listing_data_categories)){
							foreach($listing_data_categories as $category){
								echo "<tr>";
								echo "<td class='option primary'>" . $category['singular'] . "</td>";
								echo "<td class='spec'>" . $listing_post_meta[$category['slug']] . "</td>";
								echo "</tr>";
							}
						}

						echo '</tbody>
					</table>

					<div class="view-details gradient_button">
						<i class="fa fa-plus-circle"></i> ' . __('View Details', 'listings') . '
					</div>

					<div class="clearfix"></div>
				</a>


				<div class="price">
					<b>' . (isset($lwp_options['default_value_price']) && !empty($lwp_options['default_value_price']) ? $lwp_options['default_value_price'] : "Price") . ':</b><br>
					<div class="figure">' . (isset($listing_post_meta['listing_options']['price']['value']) && !empty($listing_post_meta['listing_options']['price']['value']) ? $Listing->format_currency($listing_post_meta['listing_options']['price']['value']) : "") . '<br></div>
					<div class="tax">' . (isset($lwp_options['tax_label_box']) && !empty($lwp_options['tax_label_box']) ? $lwp_options['tax_label_box'] : "") . '</div>
				</div>


			</div></div>';
		}
	}

	return ob_get_clean();
}
add_shortcode("auto_card", "rev_slider_listing_shortcode");

function automotive_default_badge_css($css, $badge_name, $badge_color, $font_color){
	$Listing = Automotive_Plugin();

	$borderCSS        = (is_rtl() ? $badge_color . " transparent transparent transparent" : "transparent " . $badge_color . " transparent");
	$listingBorderCSS = (is_rtl() ? "transparent " . $badge_color . " transparent transparent" : $badge_color . " transparent");

	$custom_css  = ".angled_badge.custom_badge_" . $Listing->slugify( $badge_name ) . ":before { border-color: " . $borderCSS . "; }\n";
	$custom_css .= ".listing-slider .angled_badge.custom_badge_" . $Listing->slugify( $badge_name ) . ":before { border-color: " . $listingBorderCSS . "; }\n";
	$custom_css .= ".listing-slider .angled_badge.custom_badge_" . $Listing->slugify( $badge_name ) . " span, .angled_badge.custom_badge_" . $Listing->slugify( $badge_name ) . " span { color: " . $font_color . "; }\n\n";

	return $custom_css;
}
add_filter('automotive_badge_css', 'automotive_default_badge_css', 10, 4);



function esc_shortcode_attr($value){
  return str_replace(array("[", "]"), array("&#91;", "&#93;"), esc_attr($value));
}

function parse_shortcode_attr($value){
  return str_replace(array("&#91;", "&#93;"), array("[", "]"), $value);
}

function iso8859_1_to_utf8($s) {
    $s .= $s;
    $len = strlen($s);

    for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
        switch (true) {
            case $s[$i] < "\x80": $s[$j] = $s[$i]; break;
            case $s[$i] < "\xC0": $s[$j] = "\xC2"; $s[++$j] = $s[$i]; break;
            default: $s[$j] = "\xC3"; $s[++$j] = chr(ord($s[$i]) - 64); break;
        }
    }

    return substr($s, 0, $j);
}
