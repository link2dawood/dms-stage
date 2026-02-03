<?php

function mergepress_elementor_register_widgets(){
require_once('elementor-widgets.php');

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_recent_posts_scroller() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_quote() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_animated_numbers() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_featured_icon_box() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_progress_bar() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_list() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_list_item() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_parallax_section() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_testimonials() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_testimonial_quote() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_faq() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_toggle() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_pricing_table() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_pricing_option() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_person() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_featured_panel() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_detailed_panel() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_portfolio() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_alert() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_search_inventory_box() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_button() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_dropcaps() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_auto_video() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_heading() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_car_comparison() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_auto_contact_form() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_hours_table() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_automotive_social_icons_shortcode() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_auto_contact_information() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_auto_google_map() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_flipping_card() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_icon_title() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_tabs() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_clearfix() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_br() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_tab() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_automotive_form() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_featured_brands() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_brand_logo() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_inventory_display() );
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor_vehicle_scroller() );
}
add_action('elementor/widgets/widgets_registered', 'mergepress_elementor_register_widgets');

function merge_add_elementor_widget_categories( $elements_manager ) {
$elements_manager->add_category(
  'Automotive',
  [
   'title' => __( 'Automotive', 'plugin-name' ),
   'icon' => 'fa fa-car',
  ]
);
}
add_action( 'elementor/elements/categories_registered', 'merge_add_elementor_widget_categories' );
