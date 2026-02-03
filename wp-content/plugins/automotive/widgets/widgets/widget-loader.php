<?php 

require_once('recent_posts_scroller.php');
require_once('quote.php');
require_once('animated_numbers.php');
require_once('featured_icon_box.php');
require_once('progress_bar.php');
require_once('list.php');
require_once('list_item.php');
require_once('parallax_section.php');
require_once('testimonials.php');
require_once('testimonial_quote.php');
require_once('faq.php');
require_once('toggle.php');
require_once('pricing_table.php');
require_once('pricing_option.php');
require_once('person.php');
require_once('featured_panel.php');
require_once('detailed_panel.php');
require_once('portfolio.php');
require_once('alert.php');
require_once('search_inventory_box.php');
require_once('button.php');
require_once('dropcaps.php');
require_once('auto_video.php');
require_once('heading.php');
require_once('car_comparison.php');
require_once('auto_contact_form.php');
require_once('hours_table.php');
require_once('automotive_social_icons_shortcode.php');
require_once('auto_contact_information.php');
require_once('auto_google_map.php');
require_once('flipping_card.php');
require_once('icon_title.php');
require_once('tabs.php');
require_once('clearfix.php');
require_once('br.php');
require_once('tab.php');
require_once('automotive_form.php');
require_once('featured_brands.php');
require_once('brand_logo.php');
require_once('inventory_display.php');
require_once('vehicle_scroller.php');


add_action("admin_enqueue_scripts", "mergepress_add_custom_script_style");
add_action("siteorigin_panel_enqueue_admin_scripts", "mergepress_add_custom_script_style");
add_action("wp_enqueue_scripts", "mergepress_add_custom_script_style2");
function mergepress_add_custom_script_style(){
  wp_enqueue_editor();
  wp_enqueue_script( 'wp-color-picker' );
  wp_enqueue_script( 'wplink' );
  wp_enqueue_script( 'widget-codeflask', mergepress_get_file_uri() . 'assets/js/codeflask.min.js' );

  wp_enqueue_script( 'widget-admin-script', mergepress_get_file_uri() . '/widgets/admin.js', array( 'jquery', 'wp-color-picker', 'widget-codeflask', 'wp-util' ) );

  wp_enqueue_style( 'editor-buttons' );
  wp_enqueue_style( 'widget-codeflask', mergepress_get_file_uri() . '/assets/css/codeflask.css' );

  wp_enqueue_style( 'widget-admin-style', mergepress_get_file_uri() . '/widgets/admin.css' );

}

function mergepress_add_custom_script_style2(){
  wp_enqueue_script( 'wp-color-picker' );
  wp_enqueue_script( 'widget-codeflask', mergepress_get_file_uri() . '/assets/js/codeflask.min.js' );

    wp_enqueue_script( 'widget-admin-script', mergepress_get_file_uri() . '/widgets/admin.js', array( 'jquery', 'widget-codeflask' ) );

}

