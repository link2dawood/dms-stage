<?php

//********************************************
//	Listing Styles
//***********************************************************
function automotive_listing_styles() {
  global $Listing, $post;

  $additional_gfonts = array();
  if ( isset( $post->ID ) ) {
    $page_slideshow = get_post_meta( $post->ID, "page_slideshow", true );

    if ( ! empty( $page_slideshow ) && $page_slideshow != "none" ) {
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

      // make sure rev slider is still active
      if ( is_plugin_active( 'revslider/revslider.php' ) ) {
        global $wpdb;

        // get slider id
        $slider_id = $wpdb->get_var(
          $wpdb->prepare(
            "SELECT id FROM " . $wpdb->prefix . "revslider_sliders WHERE alias=%s", $page_slideshow
          )
        );

        // get slides
        if ( ! empty( $slider_id ) ) {
          $slider_slides = $wpdb->get_results(
            $wpdb->prepare(
              "SELECT layers FROM " . $wpdb->prefix . "revslider_slides WHERE slider_id=%d", $slider_id
            )
          );

          if ( ! empty( $slider_slides ) ) {
            $rev_slider_templates = get_option( 'automotive_rev_slider_templates' );

            foreach ( $slider_slides as $slide ) {
              $slide_layers = json_decode( $slide->layers );

              // find layer shortcode
              if ( ! empty( $slide_layers ) ) {
                foreach ( $slide_layers as $slide_layer ) {
                  if ( isset( $slide_layer->text ) ) {
                    preg_match( "/\[auto_card id\='(.*)' template\='(.*)'\]/", $slide_layer->text, $output_array );

                    if ( isset( $output_array[2] ) && ! empty( $rev_slider_templates[ $output_array[2] ] ) ) {
                      $template = $rev_slider_templates[ $output_array[2] ];

                      // add google font to array
                      if ( isset( $template['options']['rev_font_family'] ) && ! empty( $template['options']['rev_font_family'] ) ) {
                        $additional_gfonts[] = urlencode( $template['options']['rev_font_family'] );
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  // Google Web Fonts
  wp_register_style( 'google-web-font-automotive', ( is_ssl() ? "https" : "http" ) . "://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800,400italic" . ( ! empty( $additional_gfonts ) ? "|" . implode( "|", $additional_gfonts ) : "" ), array(), AUTOMOTIVE_VERSION );
//	wp_enqueue_style( 'google-web-font-automotive' );

  // dequeue Visual Composers
  wp_dequeue_style( "font_awesome" );

  // Font Awesome
  wp_enqueue_style( 'font-awesomemin', CSS_DIR . "all.min.css", array(), AUTOMOTIVE_VERSION );
  wp_enqueue_style( 'font-awesomemin-shims', CSS_DIR . "v4-shims.min.css", array(), AUTOMOTIVE_VERSION );

  // jQuery UI
  wp_enqueue_style( 'jquery', CSS_DIR . "jquery-ui.css", array(), AUTOMOTIVE_VERSION );

  // bootstrap 4
  wp_enqueue_style( 'bootstrap', CSS_DIR . "bootstrap.min.css", array(), AUTOMOTIVE_VERSION );

  // Animate.css
  wp_enqueue_style( 'css-animate', CSS_DIR . "animate.min.css", array(), AUTOMOTIVE_VERSION );

  // Flexslider
  wp_enqueue_style( 'flexslider', CSS_DIR . "flexslider.css", array(), AUTOMOTIVE_VERSION );

  if ( is_rtl() ) {
    wp_enqueue_style( 'flexslider-rtl', CSS_DIR . "flexslider-rtl.css", array(), AUTOMOTIVE_VERSION );
  }

  wp_register_style( 'automotive_photoswipe', CSS_DIR . "photoswipe.css" );
  wp_register_style( 'automotive_photoswipe-default-skin', CSS_DIR . "photoswipe-default-skin.css" );

  wp_enqueue_style( 'tomselect', CSS_DIR . "tom-select.default.css", array(), AUTOMOTIVE_VERSION );

  // Print Style
  if ( is_singular( 'listings' ) ) {
    wp_enqueue_style( 'print-style', CSS_DIR . "print.css", array(), AUTOMOTIVE_VERSION, "print" );

    wp_enqueue_style( 'automotive_photoswipe' );
    wp_enqueue_style( 'automotive_photoswipe-default-skin' );
  }

  $listing_style_deps = [];

  if ( wp_style_is( 'bootstrap' ) ) {
    $listing_style_deps[] = 'bootstrap';
  }

  $listing_style_deps[] = 'tomselect';

  if ( is_rtl() ) {
    wp_enqueue_style( 'listing_style', CSS_DIR . "listing_style_rtl.css", $listing_style_deps, AUTOMOTIVE_VERSION );

    if ( ! function_exists( 'automotive_get_current_template' ) || automotive_get_current_template( 'id' ) === 'default' ) {
      wp_enqueue_style( 'listing_style_default', CSS_DIR . "default-rtl.css", $listing_style_deps, AUTOMOTIVE_VERSION );
    }
  } else {
    wp_enqueue_style( 'listing_style', CSS_DIR . "listing_style.css", $listing_style_deps, AUTOMOTIVE_VERSION );

    if ( ! function_exists( 'automotive_get_current_template' ) || automotive_get_current_template( 'id' ) === 'default' ) {
      wp_enqueue_style( 'listing_style_default', CSS_DIR . "default.css", $listing_style_deps, AUTOMOTIVE_VERSION );
    }
  }

  // custom badge colors
  $custom_css    = "";
  $custom_badges = automotive_listing_get_option( 'custom_badges', false );

  if ( $custom_badges ) {
    // if the last entry is blank we remove it
    $last_name_value = array_values( array_slice( $custom_badges['name'], - 1 ) );

    if ( ! empty( $last_name_value ) ) {
      $last_name_value = $last_name_value[0];
    }

    if ( empty( $last_name_value ) ) {
      end( $custom_badges['name'] );

      $last_name_value = key( $custom_badges['name'] );

      if ( isset( $custom_badges['color'][ $last_name_value ] ) ) {
        unset( $custom_badges['color'][ $last_name_value ] );
      }

      if ( isset( $custom_badges['font'][ $last_name_value ] ) ) {
        unset( $custom_badges['font'][ $last_name_value ] );
      }

      unset( $custom_badges['name'][ $last_name_value ] );
    }

    $custom_badge_names  = $custom_badges['name'];
    $custom_badge_colors = $custom_badges['color'];
    $custom_badge_font   = $custom_badges['font'];

    foreach ( $custom_badge_names as $badge_i => $badge_name ) {
      $badge_name = $Listing->numbers_to_text( $Listing->slugify( $badge_name ) );

      if ( isset( $custom_badge_font[ $badge_i ] ) ) {
        $badge_color = ( isset( $custom_badge_colors[ $badge_i ] ) && ! empty( $custom_badge_colors[ $badge_i ] ) ? $custom_badge_colors[ $badge_i ] : '#c7081b' );
        $font_color  = ( isset( $custom_badge_font[ $badge_i ] ) && ! empty( $custom_badge_font[ $badge_i ] ) ? $custom_badge_font[ $badge_i ] : '#FFFFFF' );

        $badge_css = apply_filters( 'automotive_badge_css', '', $badge_name, $badge_color, $font_color );

        if ( ! empty( $badge_css ) ) {
          $custom_css .= $badge_css;
        }
      }
    }
  }


  $main_image_dimensions = Automotive_Plugin()->get_automotive_image_sizes( 'auto_thumb' );

  $custom_css .= '.slide { max-width: ' . (int) $main_image_dimensions['width'] . 'px; }';

  if ( automotive_listing_get_option( 'inventory_filter_mobile_slideout', false ) && automotive_listing_get_option( 'inventory_filter_mobile_hide_filters', false ) ) {
    $custom_css .= '@media(max-width: 991px){.select-wrapper.listing_select { display: none !important;} }';
  }

  wp_add_inline_style( "listing_style", $custom_css );

  // Social likes
  wp_register_style( 'social-likes', CSS_DIR . "social-likes.css", array(), AUTOMOTIVE_VERSION );

  // Mobile
  $responsiveness = automotive_theme_get_option( 'responsiveness', true );

  if ( $responsiveness ) {
    wp_enqueue_style( 'listing_mobile', CSS_DIR . "mobile" . ( is_rtl() ? "-rtl" : "" ) . ".css", array(), AUTOMOTIVE_VERSION );
  }

  // Form Styles
  wp_register_style( 'form-style', CSS_DIR . "form-style.css", array(), AUTOMOTIVE_VERSION );

  // FancyBox
  wp_enqueue_style( 'jqueryfancybox', CSS_DIR . "jquery.fancybox.css", array(), AUTOMOTIVE_VERSION );

  // Shortcodes style
  wp_enqueue_style( 'listing_shortcodes', CSS_DIR . "shortcodes" . ( is_rtl() ? "-rtl" : "" ) . ".css", array(), AUTOMOTIVE_VERSION );

  // ThemeSuite
  wp_enqueue_style( 'ts', CSS_DIR . "ts" . ( is_rtl() ? "-rtl" : "" ) . ".css", array(), AUTOMOTIVE_VERSION );
}

add_action( 'wp_enqueue_scripts', 'automotive_listing_styles' );

//********************************************
//	Listing Scripts
//***********************************************************
function automotive_listing_scripts() {
  global $awp_options, $post, $Listing;

  $comparison_page = automotive_listing_get_option( 'comparison_page', false );
  $gdpr_enabled    = automotive_listing_get_option( 'gdpr_form', false );
  $google_maps_api = automotive_listing_get_option( 'google_maps_api', false );
  $inventory_page  = automotive_listing_get_option( 'inventory_page', false );

  $recaptcha_enabled = automotive_listing_get_option( 'recaptcha_enabled', false ) && automotive_listing_get_option( 'recaptcha_script_enabled', false );
  $needs_popper      = ( is_single() || is_page() || is_archive() || is_author() || is_category() || is_tag() || is_home() );

  // disable recaptcha for widgets preview
  if ( is_admin() ) {
    $recaptcha_enabled = false;
  }

  $listing_js_deps = array( 'jquery', 'lazy-load', 'jquery-ui-mouse' );
  $bootstrap_deps  = array();

  if ( $needs_popper ) {
    $bootstrap_deps[] = 'popper';
  }

  if ( $recaptcha_enabled ) {
    $listing_js_deps[] = 'recaptcha';
  }

  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'jquery-ui-mouse' );
  wp_register_script( 'jquery_ui', JS_DIR . 'jquery-ui-1.10.3.custom.min.js', array( 'jquery' ), AUTOMOTIVE_VERSION ); // todo: beaver builder

  // filter so extensions can overwrite functionality for custom financing + PDF conditions
  $financing_calculator_script_path = apply_filters( 'automotive_plugin_financing_calculator_path', JS_DIR . "financing-calculator.js" );
  $generate_pdf_script_path         = apply_filters( 'automotive_plugin_generate_pdf_path', JS_DIR . "generate-pdf.js" );

  wp_register_script( 'automotive-listing-financing-calculator', $financing_calculator_script_path, array( 'jquery' ), AUTOMOTIVE_VERSION, true );
  wp_register_script( 'automotive-listing-generate-pdf', $generate_pdf_script_path, array( 'jquery' ), AUTOMOTIVE_VERSION, true );

  $listing_js_deps[] = 'automotive-listing-financing-calculator';
  $listing_js_deps[] = 'automotive-listing-generate-pdf';
  $listing_js_deps[] = 'tomselect';

  wp_enqueue_script( 'listing_js', JS_DIR . "listing.js", $listing_js_deps, AUTOMOTIVE_VERSION, true );

  $listing_vars = array(
    'ajaxurl'       => admin_url( 'admin-ajax.php' ),
    'current_url'   => get_permalink( get_queried_object_id() ),
    'permalink_set' => ( get_option( 'permalink_structure' ) ? 'true' : 'false' )
  );

  if ( $comparison_page ) {
    $listing_vars['compare'] = get_permalink( $comparison_page );
  }

  if ( is_singular( 'listings' ) ) {
    $listing_vars['listing_id'] = $post->ID;

    $listing_vars['pdf']['primary_text']   = ( isset( $awp_options['logo_text'] ) && ! empty( $awp_options['logo_text'] ) ? $awp_options['logo_text'] : "" );
    $listing_vars['pdf']['secondary_text'] = ( isset( $awp_options['logo_text_secondary'] ) && ! empty( $awp_options['logo_text_secondary'] ) ? $awp_options['logo_text_secondary'] : "" );

    if ( $Listing->is_hotlink() ) {
      $listing_vars['is_hotlink'] = true;
    }
  }

  if ( is_single() || is_page() ) {
    $listing_vars['post_id'] = $post->ID;
  }

  // recaptcha public key
  if ( $recaptcha_enabled ) {
    $listing_vars['recaptcha_public'] = automotive_listing_get_option( 'recaptcha_public_key', '' );
    $listing_vars['template_url']     = get_template_directory_uri();
  }

  // vehicle terms
  $listing_vars['singular_vehicles']  = automotive_listing_get_option( 'vehicle_singular_form', __( 'Vehicle', 'listings' ) );
  $listing_vars['plural_vehicles']    = automotive_listing_get_option( 'vehicle_plural_form', __( 'Vehicles', 'listings' ) );
  $listing_vars['compare_vehicles']   = __( "Compare", "listings" );
  $listing_vars['currency_symbol']    = automotive_listing_get_option( 'currency_symbol', "$" );
  $listing_vars['currency_placement'] = automotive_listing_get_option( 'currency_placement', true );
  $listing_vars['currency_separator'] = automotive_listing_get_option( 'currency_separator', "." );
  $listing_vars['google_maps_api']    = automotive_listing_get_option( 'google_maps_api', "" );
  $listing_vars['email_success']      = automotive_listing_get_option( 'email_success', __( "The email was sent.", "listings" ) );
  $listing_vars['show_all_photos']    = automotive_listing_get_option( 'show_all_photos', false );

  if ( $gdpr_enabled ) {
    $listing_vars['gdpr_form'] = true;
  }

  if ( $gdpr_enabled ) {
    $listing_vars['contact_gdpr_form'] = true;
  }

  if ( ! empty( $Listing->current_categories ) ) {
    $current_filters = array();

    foreach ( $Listing->current_categories as $current_slug => $current_value ) {
      $current_slug = ( $current_slug == "yr" ? "year" : $current_slug );
      $label        = '';

      if ( is_array( $current_value ) ) {
        $data = [];

        foreach ( $current_value as $single_key => $single_value ) {
          if ( ! empty( $single_value ) ) {
            $label .= $Listing->get_pretty_listing_term( $current_slug, $single_value ) . ', ';
          }

          $data[ $single_value ] = $Listing->get_pretty_listing_term( $current_slug, $single_value );
        }

        $label = rtrim( $label, ', ' );
      } else {
        $label = $Listing->get_pretty_listing_term( $current_slug, $current_value );

        $data = [
          $current_value => $Listing->get_pretty_listing_term( $current_slug, $current_value )
        ];
      }

      $current_filters[ $current_slug ] = array(
        'label'    => $label,
        'singular' => $Listing->get_single_listing_category( $current_slug, 'singular' ),
        'data'     => $data
        //'value'    => $current_value
      );
    }

    $listing_vars['currentFilters'] = $current_filters;
  }

  if ( automotive_listing_get_option( 'filter_showall', false ) ) {
    $listing_vars['filter_showall'] = true;
  }

  // SSL
  $is_ssl = is_ssl();

  if ( $is_ssl ) {
    $listing_vars['is_ssl'] = true;
  }

  if ( automotive_listing_get_option( 'filter_show_current_dropdown_terms', false ) ) {
    $listing_vars['listing_terms'] = true;
  }

  if ( automotive_listing_get_option( 'filter_multiselect', false ) ) {
    $listing_vars['filter_multiselect'] = true;
  }

  wp_localize_script( 'listing_js', 'listing_ajax', $listing_vars );

  // Cookie
  wp_enqueue_script( 'listing_cookie', JS_DIR . "jquery.cookie.js", array(), AUTOMOTIVE_VERSION, true );

  wp_register_script( 'google-maps', "https://maps.googleapis.com/maps/api/js?key=" . $google_maps_api, array(), false, true );

  if ( is_page_template( 'contact-template.php' ) || ( isset( $post->ID ) && $inventory_page == $post->ID ) ) {
    wp_enqueue_script( 'google-maps' );
  }

  // fancybox
  wp_register_script( 'jqueryfancybox', JS_DIR . "jquery.fancybox.js", array(), AUTOMOTIVE_VERSION, true );

  // Flex Slider
  wp_register_script( 'flex-slider', JS_DIR . "jquery.flexslider-min.js", array(), AUTOMOTIVE_VERSION, true );

  wp_register_script( 'automotive_photoswipe', JS_DIR . "photoswipe.min.js" );
  wp_register_script( 'automotive_photoswipe-default-ui', JS_DIR . "photoswipe-ui-default.min.js" );

  if ( is_singular( 'listings' ) || is_singular( 'listings_portfolio' ) ) {
    wp_dequeue_script( "jqueryflexslider" );
    wp_dequeue_script( "jquery.flexslider" );

    wp_enqueue_script( 'flex-slider' );
    wp_enqueue_script( 'jqueryfancybox' );
    wp_dequeue_script( 'fancybox' );
  }

  if ( is_singular( 'listings' ) ) {
    wp_enqueue_script( 'automotive_photoswipe' );
    wp_enqueue_script( 'automotive_photoswipe-default-ui' );
  }

  wp_register_script( "tether", JS_DIR . 'tether.min.js', array( 'jquery' ), AUTOMOTIVE_VERSION, true );
  wp_enqueue_script( "tether" );

  // popper js
  if ( $needs_popper ) {
    wp_register_script( "popper", JS_DIR . 'popper.min.js', array( 'jquery' ), AUTOMOTIVE_VERSION, true );
    wp_enqueue_script( "popper" );
  }

  // bootstrap tooltip
  wp_register_script( 'bootstrap', JS_DIR . "bootstrap.js", $bootstrap_deps, AUTOMOTIVE_VERSION, true );
  wp_enqueue_script( 'bootstrap' );

  // select box
  //wp_register_script( 'selectbox', JS_DIR . "jquery.selectbox.js", array(), AUTOMOTIVE_VERSION, true );
  //wp_enqueue_script( 'selectbox' );
  wp_enqueue_script( 'tomselect', JS_DIR . "tom-select.complete.min.js", array( 'jquery' ), AUTOMOTIVE_VERSION, true );

  // bxslider
  wp_register_script( 'bxslider', JS_DIR . "jquery.bxslider.min.js", array(), AUTOMOTIVE_VERSION, true );

  // if ( automotive_listing_get_option('featured_vehicle_widget', false) ) {
  wp_enqueue_script( 'bxslider' );
  // }

  // if mixitup
  wp_register_script( 'mixit', JS_DIR . "jquery.mixitup.min.js", array(), AUTOMOTIVE_VERSION, true );

  // Parallax
  // wp_register_script( 'parallax', JS_DIR . "jquery.parallax.js", array(), AUTOMOTIVE_VERSION, true );
  wp_enqueue_script( 'parallax', JS_DIR . "jquery.parallax.js", array(), AUTOMOTIVE_VERSION, true );

  // social-likes
  wp_register_script( 'social-likes', JS_DIR . "social-likes.min.js", array(), AUTOMOTIVE_VERSION, true );

  // jsPDF
  wp_register_script( 'jspdf', JS_DIR . "jspdf.min.js", array(), AUTOMOTIVE_VERSION, true );

  if ( is_singular( 'listings' ) ) {
    wp_enqueue_script( 'social-likes' );

    if ( automotive_listing_get_option( 'pdf_brochure_show', true ) ) {
      wp_enqueue_script( 'jspdf' );

      $path  = LISTING_HOME . '/js/pdf';
      $files = scandir( $path );

      $ignore_files = array( ".", "..", ".DS_Store" );

      foreach ( $files as $file ) {
        if ( ! in_array( $file, $ignore_files ) ) {
          wp_register_script( $file, JS_DIR . "pdf/" . $file, array(), AUTOMOTIVE_VERSION, true );
          wp_enqueue_script( $file );
        }
      }
    }
  }

  // Inview
  wp_register_script( 'lazy-load', JS_DIR . "jquery.lazy.min.js", array(), AUTOMOTIVE_VERSION, true );
  wp_enqueue_script( 'lazy-load' );

  // Inview
  wp_register_script( 'inview', JS_DIR . "jquery.inview.min.js", array(), AUTOMOTIVE_VERSION, true );

  // Twitter
  wp_register_script( 'twitter_tweet', JS_DIR . 'twitter/jquery.tweet.js', array( 'jquery' ), '1.0.0' );
  wp_register_script( 'twitter_feed', JS_DIR . 'twitter/twitter_feed.js', array( 'jquery' ), '1.0.0' );

  // Contact Form
  wp_register_script( 'contact_form', JS_DIR . "contact_form.js", array(), AUTOMOTIVE_VERSION, true );

  // captcha
  if ( $recaptcha_enabled ) {
    wp_register_script( 'recaptcha', "https://www.google.com/recaptcha/api.js?onload=automotiveRecaptchaLoaded&render=explicit", array(), false, true, true );

    wp_add_inline_script( 'recaptcha', "var automotiveRecaptchaLoaded = function(){
		  var $ = jQuery;

		  if($('#contact_form_recaptcha').length){
		    automotiveInitRecaptcha('contact_form_recaptcha');
		  }

			if($('#frontend_form_recaptcha').length){
				automotiveInitRecaptcha('frontend_form_recaptcha');
			}

		  if($('#contact_form_recaptcha').length){
		    automotiveInitRecaptcha('contact_form_recaptcha');
		  }

		  if($('form .recaptcha_holder').length){
		    $('form .recaptcha_holder').each(function(){
		      automotiveInitRecaptcha($(this).prop('id'));
		    });
		  }
		}", 'before' );


    if ( is_singular( 'listings' ) ) {
      wp_enqueue_script( 'recaptcha' );

      wp_dequeue_script( 'google-recaptcha' );
      wp_deregister_script( 'google-recaptcha' );
    }
  }
}

add_action( 'wp_enqueue_scripts', 'automotive_listing_scripts' );

function automotive_listing_unregister_scripts() {
  wp_dequeue_script( 'jqueryselectbox-02' );
}

add_action( 'wp_enqueue_scripts', 'automotive_listing_unregister_scripts', 100 );

//********************************************
//	Admin Listing Styles
//***********************************************************
function automotive_admin_listing_styles() {
  wp_enqueue_style( 'chosen', CSS_DIR . "chosen.css", array(), AUTOMOTIVE_VERSION );

  wp_enqueue_style( 'listing_admin', CSS_DIR . "admin" . ( is_rtl() ? '-rtl' : '' ) . ".css" );


  $currentScreen = get_current_screen();

  if ( isset( $currentScreen->base ) && ! strstr( $currentScreen->base, "revslider" ) ) {
    global $wp_scripts;

    //	wp_enqueue_style( 'listing_jquery', CSS_DIR . "jquery-ui.css" );
    $ui = $wp_scripts->query( 'jquery-ui-core' );

    $url = "//ajax.googleapis.com/ajax/libs/jqueryui/" . $ui->ver . "/themes/smoothness/jquery-ui.min.css";
    wp_enqueue_style( 'jquery-ui-smoothness', $url, false, null );
  }


  // Font Awesome
  wp_enqueue_style( 'font-awesome', CSS_DIR . "all.min.css", array(), AUTOMOTIVE_VERSION );
  wp_enqueue_style( 'font-awesome-shims', CSS_DIR . "v4-shims.min.css", array(), AUTOMOTIVE_VERSION );

  wp_register_style( 'animate', CSS_DIR . "animate.min.css" );
  wp_enqueue_style( 'animate' );

  wp_register_style( 'multi-select', CSS_DIR . "multi-select.css" );
  wp_enqueue_style( 'multi-select' );

  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'wp-color-picker' );
}

//********************************************
//	Admin Listing Scripts
//***********************************************************
function automotive_admin_listing_scripts( $hook_suffix ) {
  global $post;

  if ( is_admin() ) {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-progressbar' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-accordion' );
    wp_enqueue_script( 'wp-color-picker' );

    wp_enqueue_media();

    $listing_vars = array(
      'ajaxurl'       => admin_url( 'admin-ajax.php' ),
      'listing_dir'   => LISTING_DIR,
      'sold_listings' => ( isset( $_GET['sold_listings'] ) && ! empty( $_GET['sold_listings'] ) && in_array( $_GET['sold_listings'], array(
        1,
        2
      ) ) ? $_GET['sold_listings'] : "false" )
    );

    $queried_object = get_queried_object_id();
    if ( isset( $queried_object ) && ! empty( $queried_object ) ) {
      $listing_vars['current_url'] = get_permalink( $queried_object );
    }

    if ( isset( $post->ID ) && ! empty( $post->ID ) ) {
      $listing_vars['post_id'] = $post->ID;
    }

    $allowed_pages = array(
      "index.php",
      "edit.php",
      "post.php",
      "edit-comments.php",
      "widgets.php",
      "upload.php",
      "themes.php",
      "plugins.php",
      "users.php",
      "tools.php",
      "options-general.php",
      "post-new.php",
      "admin.php",
      "toplevel_page_listing_wp",
      "toplevel_page_automotive_wp",
      "visual-composer_page_vc-welcome"
    );

    if ( in_array( $hook_suffix, $allowed_pages ) || strpos( $hook_suffix, 'listings_page' ) !== false ) {
      $google_map_url   = "https://maps.googleapis.com/maps/api/js?key=" . automotive_listing_get_option( 'google_maps_api', '' );
      $has_autocomplete = automotive_listing_get_option( 'google_maps_autocomplete', false );
      $google_map_url   = ( $has_autocomplete ? add_query_arg( "libraries", "places", $google_map_url ) : $google_map_url );

      wp_enqueue_script( 'listing_admin', JS_DIR . "admin.js", array( 'jquery-ui-progressbar' ), AUTOMOTIVE_VERSION );
      wp_localize_script( 'listing_admin', 'myAjax', $listing_vars );

      wp_enqueue_style( 'wp-color-picker' );

      wp_enqueue_script( 'admin_toggle', JS_DIR . "toggle.min.js" );
      wp_enqueue_style( 'admin_toggle_style', CSS_DIR . "toggle.css" );

      wp_register_script( 'google-maps', $google_map_url );
      wp_enqueue_script( 'google-maps' );

      wp_register_script( 'multiselect', JS_DIR . "jquery.multiselect.min.js", array( "jquery-ui-widget" ) );
      wp_enqueue_script( 'multiselect' );

      wp_register_script( 'bootstrap-tooltip', JS_DIR . "bootstrap-tooltip.js" );
      wp_enqueue_script( 'bootstrap-tooltip' );

      wp_register_script( 'chosen-dropdown', JS_DIR . "chosen.jquery.min.js" );
      wp_enqueue_script( 'chosen-dropdown' );

      wp_register_script( 'alphanum', JS_DIR . "jquery.alphanum.js" );
      wp_enqueue_script( 'alphanum' );

      wp_enqueue_script( 'listing_cookie', JS_DIR . "jquery.cookie.js", array(), false, true );
    }
  }
}

add_action( 'admin_enqueue_scripts', 'automotive_admin_listing_styles' );
add_action( 'admin_enqueue_scripts', 'automotive_admin_listing_scripts' );
