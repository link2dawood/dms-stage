<?php

//********************************************
//  Display Custom Mobile Sidebar
//***********************************************************
class Automotive_Mobile_Sidebar_Display {
	private static $instance = null;
	private $is_accordion;
	private $page_has_inventory_shortcode = false;

	public function __construct() {
		$this->is_accordion = automotive_listing_get_option( 'inventory_filter_mobile_slideout_accordion', true );

		add_filter( "body_class", [ $this, "add_custom_body_class" ] );
		add_action( "automotive_before_inventory", [ $this, "add_filter_button" ] );
		add_action( "automotive_after_inventory", [ $this, "set_inventory_page" ] );
		add_action( "wp_footer", [ $this, "sidebar_output" ] );

		// If we are displaying accordion elements we need to alter widget setting output
		if ( $this->is_accordion ) {
			add_action( 'in_widget_form', [ $this, 'alter_widget_form' ], 5, 3 );
			add_filter( 'widget_update_callback', [ $this, 'custom_widget_form_update' ], 5, 3 );
			add_action( 'admin_head', [ $this, 'hide_widget_options' ] );
		}

		add_action( 'widgets_init', [ $this, 'register_before_accordion_sidebar' ] );
	}

	function add_filter_button() {
		echo '<div class="mobile-slideout-button">';
		echo '<button class="refine-search automotive-custom-button"><i class="fa fa-filter"></i> ' . esc_html__('Filter Search', 'listings') . '</button>';
		echo '</div>';
	}


	/**
	 * Hide custom widget options from all other sidebars except our listing sidebar
	 *
	 * @return void
	 */
	public function hide_widget_options() {
		echo "<style>.automotive-custom-accordion-option { display: none; } #listing_sidebar_accordion.widgets-sortables .automotive-custom-accordion-option { display: block; }</style>";
	}

	public function add_custom_body_class( $classes ) {
		$classes[] = 'automotive-msb-active';

		return $classes;
	}

	/**
	 * Register the sidebars
	 *
	 * @return void
	 */
	public function register_before_accordion_sidebar() {
		if ( $this->is_accordion ) {
			register_sidebar( array(
				'name'          => __( 'Listings Mobile Sidebar (Before Accordion)', 'listings' ),
				'id'            => 'listing_sidebar_before_accordion',
				'description'   => __( 'Widgets in this area will be shown before the accordion widgets in the Listings Sidebar.', 'listings' ),
				'before_widget' => apply_filters( 'automotive_inventory_sidebar_before_widget', '' ),
				'after_widget'  => apply_filters( 'automotive_inventory_sidebar_after_widget', '' ),
				'before_title'  => apply_filters( 'automotive_inventory_sidebar_before_title', '' ),
				'after_title'   => apply_filters( 'automotive_inventory_sidebar_after_title', '' )
			) );
		}

		register_sidebar( array(
			'name'          => __( 'Listings Mobile Sidebar (Slideout)', 'listings' ),
			'id'            => 'listing_sidebar_accordion',
			'description'   => __( 'Widgets in this area will be shown as the Listings Sidebar on mobile, if left empty the widgets inside the Listings Sidebar will be used.', 'listings' ),
			'before_widget' => apply_filters( 'automotive_inventory_sidebar_before_widget', '' ),
			'after_widget'  => apply_filters( 'automotive_inventory_sidebar_after_widget', '' ),
			'before_title'  => apply_filters( 'automotive_inventory_sidebar_before_title', '' ),
			'after_title'   => apply_filters( 'automotive_inventory_sidebar_after_title', '' )
		) );
	}

	/**
	 * Set the inventory shortcode flag
	 *
	 * @return void
	 */
	public function set_inventory_page() {
		$this->page_has_inventory_shortcode = true;
	}

	/**
	 * Get the singleton instance
	 *
	 * @return self|null
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
   * Sidebar output
   *
	 * @return void
	 */
	public function sidebar_output() {
		if ( $this->page_has_inventory_shortcode ) {
			if ( $this->is_accordion ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}

			$loading_img = automotive_listing_get_option( 'inventory_filter_mobile_loading_image', false );
			$badge_color = automotive_listing_get_option( 'inventory_filter_mobile_button_badge_color', false );

			echo "<div class='automotive-mobile-filter-sidebar'>";

			echo "<div class='automotive-sidebar-header'>";
			echo "<button class='automotive-mobile-sidebar-close'>" . esc_html__( "View Results", "listings" ) . "</button>";

			echo "<div class='automotive-sidebar-header-title'>" . esc_html__( "Refine Search", "listings" ) . "</div>";
			echo "<div class='clearfix'></div>";
			echo "</div>";

			dynamic_sidebar( 'listing_sidebar_before_accordion' );

			$this->apply_accordion_filters();

			if ( is_active_sidebar( "listing_sidebar_accordion" ) ) {
				dynamic_sidebar( "listing_sidebar_accordion" );
			} else {
				dynamic_sidebar( "listing_sidebar" );
			}

			$this->remove_accordion_filters();

			echo "</div>";

			echo "<div class='automotive-mobile-filter-sidebar-loading'><div class='loading-img'>";
			if ( ! empty( $loading_img['id'] ) ) {
				$loading_img_src = wp_get_attachment_image_src( $loading_img['id'], 'full' );

				if ( $loading_img_src[0] ) {
					echo "<img src='" . esc_url( $loading_img_src[0] ) . "'>";
				}
			}
			echo "</div></div>";

			echo "<div class='automotive-mobile-filter-sidebar-bg'></div>";

			if ( $badge_color ) {
				echo "<style>.automotive-custom-button .total-filter-count {background-color: " . ( isset( $badge_color['rgba'] ) ? $badge_color['rgba'] : $badge_color['color'] ) . ";}</style>";
			}
		}
	}

	/**
	 * Add our hooks to alter specific widget functionality
	 *
	 * @return void
	 */
	public function apply_accordion_filters() {
		if ( $this->is_accordion ) {
			add_action( 'dynamic_sidebar_before', [ $this, 'add_sidebar_before_args' ], 10, 2 );
			add_action( 'dynamic_sidebar_after', [ $this, 'add_sidebar_after_args' ], 10, 2 );
			add_action( 'dynamic_sidebar_params', [ $this, 'add_sidebar_accordion_args' ] );
		}
	}

	/**
   * Remove the widget hooks
   *
	 * @return void
	 */
	public function remove_accordion_filters() {
		if ( $this->is_accordion ) {
			remove_action( 'dynamic_sidebar_before', [ $this, 'add_sidebar_before_args' ], 10, 2 );
			remove_action( 'dynamic_sidebar_after', [ $this, 'add_sidebar_after_args' ], 10, 2 );
			remove_action( 'dynamic_sidebar_params', [ $this, 'add_sidebar_accordion_args' ] );
		}
	}

	/**
   * Add our accordion args to the widget sidebar settings
   *
	 * @param $params
	 *
	 * @return array
	 */
	public function add_sidebar_accordion_args( $params ) {
		global $wp_registered_widgets;

		$widget_id  = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[ $widget_id ];
		$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
		$widget_num = $widget_obj['params'][0]['number'];

		$accordion_title = $widget_opt[ $widget_num ]['accordion_title'];


		$params[0]['before_widget'] = '<h3>' . esc_html( $accordion_title ) . '</h3><div>' . $params[0]['before_widget'];
		$params[0]['after_widget']  = $params[0]['after_widget'] . '</div>';

		return $params;
	}

	/**
   * Add our sidebar before arg for accordions
   *
	 * @param $index
	 * @param $has_widgets
	 *
	 * @return void
	 */
	public function add_sidebar_before_args( $index, $has_widgets ) {
		echo "<div class='automotive-mobile-widget-accordion'>";
	}

	/**
   * ADd our sidebar after args for accordions
   *
	 * @param $index
	 * @param $has_widgets
	 *
	 * @return void
	 */
	public function add_sidebar_after_args( $index, $has_widgets ) {
		echo "</div>";
	}

	/**
   * Alter the widget form
   *
	 * @param $t
	 * @param $return
	 * @param $instance
	 *
	 * @return array
	 */
	public function alter_widget_form( $t, $return, $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'accordion_title' => '' ) );

		if ( ! isset( $instance['accordion_title'] ) ) {
			$instance['accordion_title'] = null;
		}
		?>
    <p class="automotive-custom-accordion-option">
      <label
        for="<?php echo $t->get_field_id( 'accordion_title' ); ?>"><?php echo __( "Accordion Title", "listings" ); ?>
        :</label>
      <input type="text" name="<?php echo $t->get_field_name( 'accordion_title' ); ?>"
             id="<?php echo $t->get_field_id( 'accordion_title' ); ?>"
             value="<?php echo $instance['accordion_title']; ?>" class="widefat"/>
    </p>

		<?php
		$retrun = null;

		return array( $t, $return, $instance );
	}

	/**
   * Save our custom fields
   *
	 * @param $instance
	 * @param $new_instance
	 * @param $old_instance
	 *
	 * @return mixed
	 */
	public function custom_widget_form_update( $instance, $new_instance, $old_instance ) {
		$instance['accordion_title'] = ( isset( $new_instance['accordion_title'] ) ? $new_instance['accordion_title'] : '' );

		return $instance;
	}
}

if ( automotive_listing_get_option( 'inventory_filter_mobile_slideout', false ) ) {
	Automotive_Mobile_Sidebar_Display::get_instance();
}

