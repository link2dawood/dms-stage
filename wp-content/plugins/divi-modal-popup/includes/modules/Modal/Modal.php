<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.0.9
 */
class EL_DMP_Modal extends ET_Builder_Module {

	public $slug       = 'el_modal_popup';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-modal-popup/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name             = esc_html__( 'Divi Modal Popup', 'divi-modal-popup' );
		$this->main_css_element = '%%order_class%%';
		add_filter( 'et_late_global_assets_list', array( $this, 'el_dmp_late_assets' ), 10, 3 );
	}

	public function el_dmp_late_assets( $assets_list, $assets_args, $dynamic_assets ) {
		if ( function_exists( 'et_get_dynamic_assets_path' ) && function_exists( 'et_is_cpt' ) ) {
			$cpt_suffix = et_is_cpt() ? '_cpt' : '';
			$assets_list['et_icons_all'] = array(
				'css' => $assets_args['assets_prefix'] . "/css/icons_all.css",
			);
			$assets_list['et_overlay'] = array(
				'css' => $assets_args['assets_prefix'] . "/css/overlay{$cpt_suffix}.css",
			);
			$assets_list['et_icons_fa'] = array(
				'css' => $assets_args['assets_prefix'] . "/css/icons_fa_all.css",
			);
		}
		return $assets_list;
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' 		=> esc_html__( 'Configuration', 'divi-modal-popup' ),
					'modal_header' 		=> esc_html__( 'Modal Header', 'divi-modal-popup' ),
					'modal_body'   		=> esc_html__( 'Modal Body', 'divi-modal-popup' ),
					'modal_footer' 		=> esc_html__( 'Modal Footer', 'divi-modal-popup' ),
					'scroll_settings'  	=> esc_html__( 'Scroll Settings', 'divi-modal-popup' ),
					'background'   		=> esc_html__( 'Background', 'divi-modal-popup' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text'               => esc_html__( 'Alignment', 'divi-modal-popup' ),
					'trigger_button'     => esc_html__( 'Trigger Button', 'divi-modal-popup' ),
					'trigger_text'       => esc_html__( 'Trigger Text', 'divi-modal-popup' ),
					'trigger_icon'       => esc_html__( 'Trigger Icon', 'divi-modal-popup' ),
					'trigger_image'      => esc_html__( 'Trigger Image', 'divi-modal-popup' ),
					'modal_title_text'   => esc_html__( 'Modal Title Text', 'divi-modal-popup' ),
					'modal_body_text'    => esc_html__( 'Modal Body Text', 'divi-modal-popup' ),
					'modal_close_icon'   => esc_html__( 'Modal Close Icon', 'divi-modal-popup' ),
					'modal_close_button' => esc_html__( 'Modal Close Button', 'divi-modal-popup' ),
					'box_shadow'         => esc_html__( 'Box Shadow', 'divi-modal-popup' ),
					'border'             => esc_html__( 'Border', 'divi-modal-popup' ),
					'modal_sizing'       => esc_html__( 'Modal Sizing & Alignment', 'divi-modal-popup' ),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'                => array(
				'modal_title_text'   => array(
					'label'          => esc_html__( 'Modal Title', 'divi-modal-popup' ),
					'font_size'      => array(
						'default'        => '20px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'header_level'   => array(
						'default' => 'h2',
					),
					'text_align'     => array(
						'default' => 'left',
					),
					'css'            => array(
						'main'       => "{$this->main_css_element}_module .el_modal_popup_header_title",
						'hover'      => "{$this->main_css_element}_module .el_modal_popup_header_title:hover",
						'text_align' => "{$this->main_css_element}_module .el_modal_popup_header_title_container",
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'modal_title_text',
				),
				'modal_body_text'    => array(
					'label'          => esc_html__( 'Modal Body', 'divi-modal-popup' ),
					'font_size'      => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'     => array(
						'default' => 'left',
					),
					'css'            => array(
						'main'  => "{$this->main_css_element}_module .el_modal_popup_body",
						'hover' => "{$this->main_css_element}_module .el_modal_popup_body:hover",
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'modal_body_text',
				),
				'modal_trigger_text' => array(
					'label'           => esc_html__( 'Trigger Text', 'divi-modal-popup' ),
					'font_size'       => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} .el_modal_popup_trigger_text",
						'hover' => "{$this->main_css_element} .el_modal_popup_trigger_text:hover",
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'trigger_text',
				),
			),
			'button'               => array(
				'trigger_button' => array(
					'label'          => esc_html__( 'Trigger Button', 'divi-modal-popup' ),
					'css'            => array(
						'main'      => "{$this->main_css_element} .el_modal_popup_trigger_button",
						'alignment' => "{$this->main_css_element} .el_modal_popup_trigger_element_wrapper .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .el_modal_popup_trigger_element_wrapper .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element} .el_modal_popup_trigger_button, {$this->main_css_element} .el_modal_popup_trigger_button:hover",
							'important' => 'all',
						),
					),
					'box_shadow'     => array(
						'css' => array(
							'main' => "{$this->main_css_element} .el_modal_popup_trigger_button",
							'important' => 'all',
						),
					),
					'use_alignment'  => false,
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'trigger_button',
				),
				'close_button'   => array(
					'label'          => esc_html__( 'Close Button', 'divi-modal-popup' ),
					'css'            => array(
						'main'      	=> "{$this->main_css_element}_module .el_modal_popup_close_button",
						'alignment' 	=> "{$this->main_css_element}_module .el_modal_popup_footer .et_pb_button_wrapper",
						'important' 	=> 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element}_module .el_modal_popup_footer .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element}_module .el_modal_popup_close_button, {$this->main_css_element}_module .el_modal_popup_close_button:hover",
							'important' => 'all',
						),
					),
					'box_shadow'     => array(
						'css' => array(
							'main' => "{$this->main_css_element}_module .el_modal_popup_close_button",
							'important' => 'all',
						),
					),
					'use_alignment'  => true,
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'modal_close_button',
				),
			),
			'borders'              => array(
				'default'         => false,
				'trigger_element' => array(
					'label_prefix' => 'Trigger Element',
					'css'          => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .el_modal_popup_trigger_element_wrapper .el_modal_popup_trigger_element",
							'border_styles' => "{$this->main_css_element} .el_modal_popup_trigger_element_wrapper .el_modal_popup_trigger_element",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'border',
				),
				'modal'           => array(
					'label_prefix' => 'Modal',
					'css'          => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
							'border_styles' => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'border',
				),
			),
			'box_shadow'           => array(
				'default'         => false,
				'trigger_element' => array(
					'label'       => esc_html__( 'Trigger Element Box Shadow', 'divi-modal-popup' ),
					'css'         => array(
						'main' => "{$this->main_css_element} .el_modal_popup_trigger_element",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
				'modal'           => array(
					'label'       => esc_html__( 'Modal Box Shadow', 'divi-modal-popup' ),
					'css'         => array(
						'main' => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
				'modal_header'    => array(
					'label'       => esc_html__( 'Modal Header Box Shadow', 'divi-modal-popup' ),
					'css'         => array(
						'main' => "{$this->main_css_element}_module .el_modal_popup_header",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
				'modal_footer'    => array(
					'label'       => esc_html__( 'Modal Footer Box Shadow', 'divi-modal-popup' ),
					'css'         => array(
						'main' => "{$this->main_css_element}_module .el_modal_popup_footer",
					),
					'priority'    => 0,
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
			),
			'max_width' => array(
				'extra' => array(
					'modal'         => array(
						'options'              => array(
							'width' => array(
								'label'          => esc_html__( 'Modal Width', 'divi-modal-popup' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => '%',
								'default'		 => '50%',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'modal_sizing',
							),
						),
						'use_max_width'        => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
						),
					),
					'trigger_image' => array(
						'options'              => array(
							'width' => array(
								'label'          => esc_html__( 'Image Width', 'divi-modal-popup' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => '%',
								'default_tablet' => '',
								'default_phone'  => '',
								'show_if'        => array(
									'trigger_element_type' => 'image',
								),
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'trigger_image',
							),
						),
						'use_max_width'        => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => "{$this->main_css_element} .el_modal_popup_trigger_image",
						),
					),
				),
			),
			'height' => array(
				'extra' => array(
					'modal' => array(
						'options'        => array(
							'height' => array(
								'label'          => esc_html__( 'Modal Height', 'divi-modal-popup' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => '%',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'modal_sizing',
							),
						),
						'use_max_height' => false,
						'use_min_height' => false,
						'css'            => array(
							'main' => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
						),
					),
				),
			),
			'text' => array(
				'text_orientation' => array(
					'exclude_options' => array( 'justified' ),
				),
				'options'          => array(
					'text_orientation' => array(
						'label' => esc_html__( 'Trigger Element Alignment', 'divi-modal-popup' ),
					),
				),
				'css'              => array(
					'text_orientation' => $this->main_css_element,
				),
			),
			'modal_margin_padding' => array(
				'modal'        => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
							'padding'   => "{$this->main_css_element}_module .el_modal_popup_inner_wrap",
							'important' => 'all',
						),
					),
				),
				'modal_header' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element}_module .el_modal_popup_header",
							'padding'   => "{$this->main_css_element}_module .el_modal_popup_header",
							'important' => 'all',
						),
					),
				),
				'modal_body' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element}_module .el_modal_popup_body",
							'padding'   => "{$this->main_css_element}_module .el_modal_popup_body",
							'important' => 'all',
						),
					),
				),
				'modal_footer' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element}_module .el_modal_popup_footer",
							'padding'   => "{$this->main_css_element}_module .el_modal_popup_footer",
							'important' => 'all',
						),
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'text_shadow'          	=> false,
			'link_options'         	=> false,
			'filters'              	=> false,
			'background'           	=> false,
		);
	}

	public function get_fields() {
		$query_args     = array(
			'post_type'      => 'et_pb_layout',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_et_pb_predefined_layout',
					'value'   => 'on',
					'compare' => 'NOT EXISTS',
				),
			),
		);
		$layout_list    = array();
		$layout_list[0] = esc_html__( 'Select', 'divi-modal-popup' );
		$query          = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$layout_list[ intval( get_the_ID() ) ] = ucwords( esc_html( get_the_title() ) );
			}
			wp_reset_postdata();
		}
		
		$et_accent_color = et_builder_accent_color();
		return array_merge(
			array(
				'modal_id_notice' => array(
					'label'           => '',
					'type'            => 'warning',
					'option_category' => 'configuration',
					'value'           => true,
					'display_if'      => true,
					'message'         => esc_html__( 'In order to make the modal work perfectly, please enter a unique ID for it.', 'divi-modal-popup' ),
					'show_if'     	  => array(
						'modal_id' => array( '' ),
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
				),
				'modal_id' => array(
					'label'            => esc_html__( 'Unique Modal ID', 'divi-modal-popup' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Enter a unique ID for the modal to make it work perfectly.', 'divi-modal-popup' ),
				),
				'trigger_type' => array(
					'label'            => esc_html__( 'Trigger Type', 'divi-modal-popup' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'element'      => esc_html__( 'Element', 'divi-modal-popup' ),
						'on_page_load' => esc_html__( 'On Page Load', 'divi-modal-popup' ),
						'exit_intent'  => esc_html__( 'Exit Intent', 'divi-modal-popup' ),
						'on_scroll'    => esc_html__( 'On Scroll', 'divi-modal-popup' ),
					),
					'default'          => 'element',
					'default_on_front' => 'element',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can select how you want to activate modal on the page.', 'divi-modal-popup' ),
				),
				'trigger_delay' => array(
					'label'            => esc_html__( 'Trigger Delay', 'divi-modal-popup' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '100',
						'max'  => '15000',
						'step' => '100',
					),
					'validate_unit'    => true,
					'fixed_unit'       => 'ms',
					'fixed_range'      => true,
					'default'          => '3000ms',
					'default_on_front' => '3000ms',
					'show_if'          => array(
						'trigger_type' => 'on_page_load',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Move the slider or input the value to apply delay in activating the modal.', 'divi-modal-popup'),
				),
				're_rendering' => array(
					'label'            => esc_html__( 'Re-render modal on reloading/revisiting the page', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'show_if'          => array(
						'trigger_type' => 'on_page_load',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether to re-render modal on reload or revisiting the page after user closed the modal.', 'divi-modal-popup'),
				),
				'scroll_position' => array(
					'label'            => esc_html__( 'Scroll Position', 'divi-modal-popup' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '100',
						'step' => '1',
					),
					'validate_unit'    => true,
					'allowed_units'    => array( '%', 'px' ),
					'fixed_range'      => true,
					'default'          => '50%',
					'show_if'          => array(
						'trigger_type' => 'on_scroll',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Move the slider or input the value to trigger the modal after user crossed the desired position of scroll.', 'divi-modal-popup'),
				),
				'trigger_element_type' => array(
					'label'            => esc_html__( 'Trigger Element Type', 'divi-modal-popup' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'button'     	=> esc_html__( 'Button', 'divi-modal-popup' ),
						'image'      	=> esc_html__( 'Image', 'divi-modal-popup' ),
						'icon'       	=> esc_html__( 'Icon', 'divi-modal-popup' ),
						'text'       	=> esc_html__( 'Text', 'divi-modal-popup' ),
						'element_id' 	=> esc_html__( 'Element CSS ID', 'divi-modal-popup' ),
						'element_class' => esc_html__( 'Element CSS Class', 'divi-modal-popup' ),
					),
					'default'          => 'button',
					'default_on_front' => 'button',
					'show_if'          => array(
						'trigger_type' => 'element',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can select the trigger element you want to use for activating the modal.', 'divi-modal-popup'),
				),
				'trigger_element_id' => array(
					'label'           => esc_html__( 'Element CSS ID', 'divi-modal-popup' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'element_id',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the CSS ID(without #) of the element to be used as a trigger for activating the modal.', 'divi-modal-popup'),
				),
				'trigger_element_class' => array(
					'label'           => esc_html__( 'Element CSS Class', 'divi-modal-popup' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'element_class',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the CSS Class(without .) of the element to be used as a trigger for activating the modal.', 'divi-modal-popup'),
				),
				'trigger_button_text' => array(
					'label'            => esc_html__( 'Button Text', 'divi-modal-popup' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => 'Click Me',
					'default_on_front' => 'Click Me',
					'show_if'          => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'button',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'        => esc_html__( 'Here you can input the text to be used for the button of modal trigger.', 'divi-modal-popup'),
				),
				'trigger_icon' => array(
					'label'            => esc_html__( 'Icon', 'divi-modal-popup' ),
					'type'             => 'select_icon',
					'option_category'  => 'button',
					'class'            => array( 'et-pb-font-icon' ),
					'default'          => ET_BUILDER_PRODUCT_VERSION < '4.13.0' ? '%%40%%' : '&#x49;||divi||400',
					'show_if'          => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'icon',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can select an icon to be used for the modal trigger.', 'divi-modal-popup'),
				),
				'trigger_image' => array(
					'label'              => esc_html__( 'Image', 'divi-modal-popup' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-modal-popup' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-modal-popup' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-modal-popup' ),
					'show_if'            => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'image',
					),
					'tab_slug'           => 'general',
					'toggle_slug'        => 'main_content',
					'description'        => esc_html__( 'Here you can add an image to be used for the modal trigger.', 'divi-modal-popup'),
				),
				'trigger_image_alt' => array(
					'label'           => esc_html__( 'Image Alternative Text', 'divi-modal-popup' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'image',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the text to be used for the image of modal trigger as alternative text.', 'divi-modal-popup'),
				),
				'trigger_text' => array(
					'label'            => esc_html__( 'Text', 'divi-modal-popup' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => 'Enter modal trigger text here',
					'default_on_front' => 'Enter modal trigger text here',
					'show_if'          => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'text',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can input the text to be used as modal trigger.', 'divi-modal-popup'),
				),
				'close_on_esc' => array(
					'label'            => esc_html__( 'Close Modal on ESC', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'off',
					'default_on_front' => 'off',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether or not to close the modal on ESC key.', 'divi-modal-popup'),
				),
				'close_on_background_click' => array(
					'label'            => esc_html__( 'Close Modal on background click', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether or not to close the modal on clicking outside the modal content.', 'divi-modal-popup'),
				),
				'show_header' => array(
					'label'            => esc_html__( 'Show Header', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_header',
					'description'      => esc_html__( 'Here you can choose whether or not to display header with modal.', 'divi-modal-popup'),
				),
				'show_modal_title' => array(
					'label'            => esc_html__( 'Show Modal Title', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'show_if'          => array(
						'show_header' => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_header',
					'description'      => esc_html__( 'Here you can choose whether or not to display title on the modal.', 'divi-modal-popup')
				),
				'modal_title' => array(
					'label'           => esc_html__( 'Modal Title', 'divi-modal-popup' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'show_header'      => 'on',
						'show_modal_title' => 'on',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'modal_header',
					'description'     => esc_html__( 'Here you can input the text to be used for the modal title.', 'divi-modal-popup')
				),
				'show_close_icon' => array(
					'label'            => esc_html__( 'Show Close Icon', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'show_if'          => array(
						'show_header' => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_header',
					'description'      => esc_html__( 'Here you can choose whether or not to display close icon on the modal.', 'divi-modal-popup')
				),
				'show_footer' => array(
					'label'            => esc_html__( 'Show Footer', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_footer',
					'description'      => esc_html__( 'Here you can choose whether or not to display footer with modal.', 'divi-modal-popup'),
				),
				'close_button_text' => array(
					'label'            => esc_html__( 'Close Button Text', 'divi-modal-popup' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => 'Close',
					'default_on_front' => 'Close',
					'show_if'          => array(
						'show_footer' => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_footer',
					'description'      => esc_html__( 'Here you can input the text to be used for the modal close button.', 'divi-modal-popup'),
				),
				'modal_content_type' => array(
					'label'            => esc_html__( 'Modal Content Type', 'divi-modal-popup' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'text'   => esc_html__( 'Text', 'divi-modal-popup' ),
						'image'  => esc_html__( 'Image', 'divi-modal-popup' ),
						'video'  => esc_html__( 'Video', 'divi-modal-popup' ),
						'layout' => esc_html__( 'Divi Library Layout', 'divi-modal-popup' ),
					),
					'default'          => 'text',
					'default_on_front' => 'text',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_body',
					'description'      => esc_html__( 'Here you can choose what type of content you want to display inside modal body.', 'divi-modal-popup'),
				),
				'content' => array(
					'label'           => esc_html__( 'Text', 'divi-modal-popup' ),
					'type'            => 'tiny_mce',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'modal_content_type' => 'text',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'modal_body',
					'description'     => esc_html__( 'Here you can input the text to be used for the modal content.', 'divi-modal-popup'),
				),
				'modal_content_image' => array(
					'label'              => esc_html__( 'Image', 'divi-modal-popup' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-modal-popup' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-modal-popup' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-modal-popup' ),
					'show_if'            => array(
						'modal_content_type' => 'image',
					),
					'tab_slug'           => 'general',
					'toggle_slug'        => 'modal_body',
					'description'        => esc_html__( 'Here you can add an image to be used for the modal content.', 'divi-modal-popup'),
				),
				'modal_content_image_alt' => array(
					'label'           => esc_html__( 'Image Alternative Text', 'divi-modal-popup' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'modal_content_type' => 'image',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'modal_body',
					'description'     => esc_html__( 'Here you can input the text to be used for the modal content image as alternative text.', 'divi-modal-popup'),
				),
				'modal_content_image_link' => array(
					'label'              => esc_html__( 'Link', 'divi-modal-popup' ),
					'type'             	 => 'text',
	                'option_category'  	 => 'basic_option',
	                'dynamic_content'  	 => 'url',
					'show_if'            => array(
						'modal_content_type' => 'image',
					),
					'tab_slug'           => 'general',
					'toggle_slug'        => 'modal_body',
					'description'        => esc_html__( 'Here you can add an image link.', 'divi-modal-popup'),
				),
				'modal_content_video' => array(
					'label'              => esc_html__( 'Video MP4 File Or URL', 'divi-modal-popup' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'data_type'          => 'video',
					'upload_button_text' => esc_attr__( 'Upload a video', 'divi-modal-popup' ),
					'choose_text'        => esc_attr__( 'Choose a Video MP4 File', 'divi-modal-popup' ),
					'update_text'        => esc_attr__( 'Set As Video', 'divi-modal-popup' ),
					'show_if'            => array(
						'modal_content_type' => 'video',
					),
					'tab_slug'           => 'general',
					'toggle_slug'        => 'modal_body',
					'description'        => esc_html__( 'Upload your desired video in .MP4 format, or enter in the URL of the video you would like to display inside modal content.', 'divi-modal-popup' ),
				),
				'autoplay_video' => array(
					'label'            => esc_html__( 'Autoplay Video', 'divi-modal-popup' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'          => 'off',
					'default_on_front' => 'off',
					'show_if'          => array(
						'modal_content_type' => 'video',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_body',
					'description'      => esc_html__( 'Here you can choose whether or not to autoplay video. We can not guarantee autoplay for all platforms video. Videos which support \'autoplay=1\' in their url structure can be autoplayed eg. YouTube, Vimeo. Videos uploaded in media library can be supported.', 'divi-modal-popup'),
				),
				'modal_content_layout' => array(
					'label'            => esc_html__( 'Divi Library Layout', 'divi-modal-popup' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => $layout_list,
					'show_if'          => array(
						'modal_content_type' => 'layout',
					),
					'default'          => '0',
					'default_on_front' => '0',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_body',
					'description'      => esc_html__( 'Here you can select the layouts saved in your Divi library to be used for the modal content.', 'divi-modal-popup' ),
				),
				'disable_website_scroll' => array(
					'label'             => esc_html__( 'Disable Website scroll', 'divi-modal-popup' ),
	                'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( 'No', 'divi-modal-popup' ),
						'on'  => esc_html__( 'Yes', 'divi-modal-popup' ),
					),
					'default'           => 'on',
					'default_on_front'  => 'on',
					'tab_slug'          => 'general',
	                'toggle_slug'       => 'scroll_settings',
	                'description'       => esc_html__( 'Here you can select whether to disable webpage scroll when the modal pops up.', 'divi-modal-popup' ),
				),
				'modal_body_scrollbar' => array(
					'label'             => esc_html__( 'Modal Scrollbar', 'divi-modal-popup' ),
	                'type'              => 'select',
	                'option_category'   => 'configuration',
	                'options'           => array(
	                    'default'  	=> esc_html__( 'Show', 'divi-modal-popup' ),
	                    'hide' 		=> esc_html__( 'Hide', 'divi-modal-popup' ),
	                ),
	                'default'           => 'default',
	                'tab_slug'          => 'general',
	                'toggle_slug'       => 'scroll_settings',
	                'description'       => esc_html__( 'Here you can select whether to show or hide scrollbar. This is totally depends on the browser, we can not guarantee the result of it.', 'divi-modal-popup' ),
				),
				'trigger_icon_font_size' => array(
					'label'            => esc_html__( 'Trigger Icon Font Size', 'divi-modal-popup' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '120',
						'step' => '1',
					),
					'default'          => '52px',
					'default_on_front' => '52px',
					'mobile_options'   => true,
					'show_if'          => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'icon',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_icon',
					'description'        => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-modal-popup' ),
				),
				'trigger_icon_color' => array(
					'label'            => esc_html__( 'Trigger Icon Color', 'divi-modal-popup' ),
					'type'             => 'color-alpha',
					'default'          => $et_accent_color,
					'default_on_front' => $et_accent_color,
					'show_if'          => array(
						'trigger_type'         => 'element',
						'trigger_element_type' => 'icon',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_icon',
					'description'      => esc_html__( 'Here you can define a custom color to be used for the trigger icon.', 'divi-modal-popup' ),
				),
				'modal_custom_margin' => array(
					'label'            => esc_html__( 'Modal Margin', 'divi-modal-popup' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-modal-popup' ),
				),
				'modal_custom_padding' => array(
					'label'            => esc_html__( 'Modal Padding', 'divi-modal-popup' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-modal-popup' ),
				),
				'modal_header_custom_margin'       => array(
					'label'            => esc_html__( 'Modal Header Margin', 'divi-modal-popup' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'show_if'          => array(
						'show_header' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-modal-popup' ),
				),
				'modal_header_custom_padding'      => array(
					'label'            => esc_html__( 'Modal Header Padding', 'divi-modal-popup' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '20px|20px|20px|20px|true|true',
					'default_on_front' => '20px|20px|20px|20px|true|true',
					'show_if'          => array(
						'show_header' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-modal-popup' ),
				),
				'modal_body_custom_margin'         => array(
					'label'            => esc_html__( 'Modal Body Margin', 'divi-modal-popup' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-modal-popup' ),
				),
				'modal_body_custom_padding'        => array(
					'label'            => esc_html__( 'Modal Body Padding', 'divi-modal-popup' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '20px|20px|20px|20px|true|true',
					'default_on_front' => '20px|20px|20px|20px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-modal-popup' ),
				),
				'modal_footer_custom_margin'       => array(
					'label'            => esc_html__( 'Modal Footer Margin', 'divi-modal-popup' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'show_if'          => array(
						'show_footer' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-modal-popup' ),
				),
				'modal_footer_custom_padding'      => array(
					'label'            => esc_html__( 'Modal Footer Padding', 'divi-modal-popup' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '20px|20px|20px|20px|true|true',
					'default_on_front' => '20px|20px|20px|20px|true|true',
					'show_if'          => array(
						'show_footer' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-modal-popup' ),
				),
				'trigger_element_bg_color'         => array(
					'label'             => esc_html__( 'Trigger Element Background', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'trigger_element_bg',
					'context'           => 'trigger_element_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'trigger_element_bg', 'button', 'general', 'background', 'trigger_element_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'show_if_not'       => array(
						'trigger_element_type' => 'element_id',
						'trigger_type'         => 'on_page_load',
					),
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the Trigger Element by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_overlay_bg_color'                   => array(
					'label'             => esc_html__( 'Modal Background Overlay', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'modal_overlay_bg',
					'context'           => 'modal_overlay_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'modal_overlay_bg', 'button', 'general', 'background', 'modal_overlay_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the modal background overlay by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_bg_color'                   => array(
					'label'             => esc_html__( 'Modal Background', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'modal_bg',
					'context'           => 'modal_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'modal_bg', 'button', 'general', 'background', 'modal_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the modal by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_header_bg_color'            => array(
					'label'             => esc_html__( 'Modal Header Background', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'modal_header_bg',
					'context'           => 'modal_header_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'modal_header_bg', 'button', 'general', 'background', 'modal_header_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'show_if'           => array(
						'show_header' => 'on',
					),
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the modal header by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_body_bg_color'              => array(
					'label'             => esc_html__( 'Modal Body Background', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'modal_body_bg',
					'context'           => 'modal_body_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'modal_body_bg', 'button', 'general', 'background', 'modal_body_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the modal body by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_footer_bg_color'            => array(
					'label'             => esc_html__( 'Modal Footer Background', 'divi-modal-popup' ),
					'type'              => 'background-field',
					'base_name'         => 'modal_footer_bg',
					'context'           => 'modal_footer_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'modal_footer_bg', 'button', 'general', 'background', 'modal_footer_bg_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'show_if'           => array(
						'show_footer' => 'on',
					),
					'tab_slug'          => 'general',
					'toggle_slug'       => 'background',
					'description'       => esc_html__( 'Customize the background of the modal footer by adjusting background color, gradient, and image.', 'divi-modal-popup' ),
				),
				'modal_close_icon' => array(
					'type'             => 'select_icon',
					'option_category'  => 'button',
					'class'            => array( 'et-pb-font-icon' ),
					'default'          => ET_BUILDER_PRODUCT_VERSION < '4.13.0' ? '%%48%%' : '&#x51;||divi||400',
					'show_if'          => array(
						'show_header'     => 'on',
						'show_close_icon' => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'modal_header',
				),
				'modal_close_icon_font_size'       => array(
					'label'            => esc_html__( 'Close Icon Font Size', 'divi-modal-popup' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '120',
						'step' => '1',
					),
					'default'          => '32px',
					'default_on_front' => '32px',
					'mobile_options'   => true,
					'show_if'          => array(
						'show_header'     => 'on',
						'show_close_icon' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'modal_close_icon',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease close icon size.', 'divi-modal-popup' ),
				),
				'modal_close_icon_color'           => array(
					'label'            => esc_html__( 'Close Icon Color', 'divi-modal-popup' ),
					'type'             => 'color-alpha',
					'default'          => '#000',
					'default_on_front' => '#000',
					'show_if'          => array(
						'show_header'     => 'on',
						'show_close_icon' => 'on',
					),
					'hover'			   => 'tabs',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'modal_close_icon',
					'description'      => esc_html__( 'Here you can define a custom color to be used for the close icon.', 'divi-modal-popup' ),
				),
				'modal_position'                   => array(
					'label'            => esc_html__( 'Modal Position', 'divi-modal-popup' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'top_left'      => esc_html__( 'Top Left', 'divi-modal-popup' ),
						'top_right'     => esc_html__( 'Top Right', 'divi-modal-popup' ),
						'top_center'    => esc_html__( 'Top Center', 'divi-modal-popup' ),
						'bottom_left'   => esc_html__( 'Bottom Left', 'divi-modal-popup' ),
						'bottom_right'  => esc_html__( 'Bottom Right', 'divi-modal-popup' ),
						'bottom_center' => esc_html__( 'Bottom Center', 'divi-modal-popup' ),
						'center'        => esc_html__( 'Center', 'divi-modal-popup' ),
					),
					'default'          => 'center',
					'default_on_front' => 'center',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'modal_sizing',
					'description'      => esc_html__( 'Here you can select where you want to place the modal on the page once triggered.', 'divi-modal-popup' ),
				),
				'modal_animation_style'            => array(
					'label'           => esc_html__( 'Modal Animation Style', 'divi-modal-popup' ),
					'type'            => 'select_animation',
					'option_category' => 'configuration',
					'options'         => array(
						'none'   => esc_html__( 'None', 'divi-modal-popup' ),
						'fade'   => esc_html__( 'Fade', 'divi-modal-popup' ),
						'slide'  => esc_html__( 'Slide', 'et_builder' ),
						'bounce' => esc_html__( 'Bounce', 'et_builder' ),
						'zoom'   => esc_html__( 'Zoom', 'et_builder' ),
						'flip'   => esc_html__( 'Flip', 'et_builder' ),
						'fold'   => esc_html__( 'Fold', 'et_builder' ),
						'roll'   => esc_html__( 'Roll', 'et_builder' ),
					),
					'default'         => 'fade',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'Pick an animation style to enable animations for the modal. Once enabled, you will be able to customize your animation style further.', 'divi-modal-popup' ),
				),
				'modal_animation_direction'        => array(
					'label'           => esc_html__( 'Modal Animation Direction', 'divi-modal-popup' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'center' => esc_html__( 'Center', 'et_builder' ),
						'right'  => esc_html__( 'Right', 'et_builder' ),
						'left'   => esc_html__( 'Left', 'et_builder' ),
						'up'     => esc_html__( 'Up', 'et_builder' ),
						'down'   => esc_html__( 'Down', 'et_builder' ),
					),
					'default'         => 'center',
					'show_if_not'     => array(
						'modal_animation_style' => array( 'none', 'fade' ),
					),
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'Pick from up to five different animation directions, each of which will adjust the starting and ending position of your animated element.', 'divi-modal-popup' ),
				),
				'modal_animation_duration'         => array(
					'label'           => esc_html__( 'Modal Animation Duration', 'divi-modal-popup' ),
					'type'            => 'range',
					'option_category' => 'configuration',
					'range_settings'  => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 50,
					),
					'default'         => '1000ms',
					'validate_unit'   => true,
					'fixed_unit'      => 'ms',
					'fixed_range'     => true,
					'show_if_not'     => array(
						'modal_animation_style' => 'none',
					),
					'reset_animation' => true,
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'divi-modal-popup' ),
				),
				'modal_animation_intensity'        => array(
					'label'           => esc_html__( 'Modal Animation Intensity', 'divi-modal-popup' ),
					'type'            => 'range',
					'option_category' => 'configuration',
					'range_settings'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'default'         => '50%',
					'validate_unit'   => true,
					'fixed_unit'      => '%',
					'fixed_range'     => true,
					'show_if'         => array(
						'modal_animation_style' => array( 'slide', 'zoom', 'flip', 'fold', 'roll' ),
					),
					'reset_animation' => true,
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'Intensity effects how subtle or aggressive your animation will be. Lowering the intensity will create a smoother and more subtle animation while increasing the intensity will create a snappier more aggressive animation.', 'divi-modal-popup' ),
				),
				'modal_animation_starting_opacity' => array(
					'label'           => esc_html__( 'Modal Animation Starting Opacity', 'divi-modal-popup' ),
					'type'            => 'range',
					'option_category' => 'configuration',
					'range_settings'  => array(
						'min'       => 0,
						'max'       => 100,
						'step'      => 1,
						'min_limit' => 0,
						'max_limit' => 100,
					),
					'default'         => '0%',
					'validate_unit'   => true,
					'fixed_unit'      => '%',
					'fixed_range'     => true,
					'reset_animation' => true,
					'mobile_options'  => true,
					'show_if_not'     => array(
						'modal_animation_style' => 'none',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'By increasing the starting opacity, you can reduce or remove the fade effect that is applied to all animation styles.', 'divi-modal-popup' ),
				),
				'modal_animation_speed_curve'      => array(
					'label'           => esc_html__( 'Modal Animation Speed Curve', 'divi-modal-popup' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'ease-in-out' => esc_html__( 'Ease-In-Out', 'divi-modal-popup' ),
						'ease'        => esc_html__( 'Ease', 'divi-modal-popup' ),
						'ease-in'     => esc_html__( 'Ease-In', 'divi-modal-popup' ),
						'ease-out'    => esc_html__( 'Ease-Out', 'divi-modal-popup' ),
						'linear'      => esc_html__( 'Linear', 'divi-modal-popup' ),
					),
					'mobile_options'  => true,
					'default'         => 'ease-in-out',
					'show_if_not'     => array(
						'modal_animation_style' => 'none',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'animation',
					'description'     => esc_html__( 'Here you can adjust the easing method of your animation. Easing your animation in and out will create a smoother effect when compared to a linear speed curve.', 'divi-modal-popup' ),
				),
			),
			$this->generate_background_options( 'trigger_element_bg', 'skip', 'general', 'background', 'trigger_element_bg_color' ),
			$this->generate_background_options( 'modal_overlay_bg', 'skip', 'general', 'background', 'modal_overlay_bg_color' ),
			$this->generate_background_options( 'modal_bg', 'skip', 'general', 'background', 'modal_bg_color' ),
			$this->generate_background_options( 'modal_header_bg', 'skip', 'general', 'background', 'modal_header_bg_color' ),
			$this->generate_background_options( 'modal_body_bg', 'skip', 'general', 'background', 'modal_body_bg_color' ),
			$this->generate_background_options( 'modal_footer_bg', 'skip', 'general', 'background', 'modal_footer_bg_color' )
		);
	}

	public static function get_video( $args = array() ) {
		$defaults = array(
			'modal_content_video' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['modal_content_video'] ) ) {
			return '';
		}

		$video_src = '';

		if ( false !== et_pb_check_oembed_provider( esc_url( $args['modal_content_video'] ) ) ) {
			$video_src = wp_oembed_get( esc_url( $args['modal_content_video'] ) );
		} else {
			$video_src = sprintf(
				'
                <video controls>
                    %1$s
                </video>',
				( '' !== $args['modal_content_video'] ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $args['modal_content_video'] ) ) : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		return $video_src;
	}

	public static function get_library_layout( $args = array() ) {
		$defaults = array(
			'modal_content_layout' => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( 0 === intval( $args['modal_content_layout'] ) ) {
			return '';
		}

		$layout = do_shortcode( el_modal_popup_remove_shortcode( get_the_content( null, false, intval( $args['modal_content_layout'] ) ) ) );

		return $layout;
	}

	public function render( $attrs, $content, $render_slug ) {
		$multi_view                  = et_pb_multi_view_options( $this );
		$modal_id 				     = $this->props['modal_id'];
		$trigger_type                = $this->props['trigger_type'];
		$trigger_delay               = $this->props['trigger_delay'];
		$re_rendering				 = $this->props['re_rendering'];
		$scroll_position			 = $this->props['scroll_position'];
		$trigger_element_type        = $this->props['trigger_element_type'];
		$trigger_button_text         = $this->props['trigger_button_text'];
		$trigger_image_alt           = $this->props['trigger_image_alt'];
		$show_header                 = $this->props['show_header'];
		$show_footer                 = $this->props['show_footer'];
		$close_button_text           = $this->props['close_button_text'];
		$modal_content_type          = $this->props['modal_content_type'];
		$modal_content_image_alt     = $this->props['modal_content_image_alt'];
		$modal_content_layout        = $this->props['modal_content_layout'];
		$autoplay_video				 = $this->props['autoplay_video'];
		$trigger_element_id          = $this->props['trigger_element_id'];
		$trigger_element_class 	     = $this->props['trigger_element_class'];
		$custom_trigger_button       = $this->props['custom_trigger_button'];
		$trigger_button_icon         = $this->props['trigger_button_icon'];
		$custom_close_button         = $this->props['custom_close_button'];
		$close_button_icon           = $this->props['close_button_icon'];
		$modal_body_scrollbar		 = $this->props['modal_body_scrollbar'];
		$disable_website_scroll		 = $this->props['disable_website_scroll'];
		$modal_title_level           = $this->props['modal_title_text_level'];
		$modal_position              = $this->props['modal_position'];
		$animation_style             = $this->props['modal_animation_style'];
		$processed_modal_title_level = et_pb_process_header_level( $modal_title_level, 'h2' );
		$animation_durations         = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_duration' );

		$trigger_element_wrapper = '';
		if ( 'element' === $trigger_type && 'element_id' !== $trigger_element_type && 'element_class' !== $trigger_element_type ) {
			switch ( $trigger_element_type ) {
				case 'button':
					$trigger_element = $this->render_button(
						array(
							'button_text'         => esc_html( $trigger_button_text ),
							'button_text_escaped' => true,
							'button_url'          => '#',
							'button_classname'    => array( 'el_modal_popup_trigger_element el_modal_popup_trigger_button' ),
							'button_custom'       => isset( $custom_trigger_button ) ? $custom_trigger_button : 'off',
							'custom_icon'         => isset( $trigger_button_icon ) ? $trigger_button_icon : '',
							'has_wrapper'         => true,
							'button_rel'          => esc_html( $this->props['trigger_button_rel'] ),
						)
					);
					break;

				case 'image':
					$trigger_element = $multi_view->render_element(
						array(
							'tag'      => 'img',
							'attrs'    => array(
								'src'   => '{{trigger_image}}',
								'class' => 'el_modal_popup_trigger_element el_modal_popup_trigger_image',
								'alt'   => esc_html( $trigger_image_alt ),
							),
							'required' => 'trigger_image',
						)
					);
					break;

				case 'icon':
					$trigger_element = $multi_view->render_element(
						array(
							'content'  => '{{trigger_icon}}',
							'attrs'    => array(
								'class' => 'el_modal_popup_trigger_element el_modal_popup_trigger_icon et-pb-icon',
							),
							'required' => 'trigger_icon',
						)
					);
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
		                $this->generate_styles(
		                    array(
		                        'utility_arg'    => 'icon_font_family',
		                        'render_slug'    => $render_slug,
		                        'base_attr_name' => 'trigger_icon',
		                        'important'      => true,
		                        'selector'       => '%%order_class%% .el_modal_popup_trigger_icon',
		                        'processor'      => array(
		                            'ET_Builder_Module_Helper_Style_Processor',
		                            'process_extended_icon',
		                        ),
		                    )
		                );
		            }
					break;

				case 'text':
					$trigger_element = $multi_view->render_element(
						array(
							'tag'      => 'div',
							'content'  => '{{trigger_text}}',
							'attrs'    => array(
								'class' => 'el_modal_popup_trigger_element el_modal_popup_trigger_text',
							),
							'required' => 'trigger_text',
						)
					);
					break;

				default:
					break;
			}
			if ( isset( $trigger_element ) && $trigger_element && '' !== $trigger_element ) {
				$trigger_element_wrapper = sprintf(
					'<div class="el_modal_popup_trigger_element_wrapper">%1$s</div>',
					et_core_intentionally_unescaped( $trigger_element, 'html' )
				);
			}
		}

		$modal_title = $multi_view->render_element(
			array(
				'tag'      => esc_html( $processed_modal_title_level ),
				'content'  => '{{modal_title}}',
				'attrs'    => array(
					'class' => 'el_modal_popup_header_title',
				),
				'required' => array(
					'show_header'      => 'on',
					'show_modal_title' => 'on',
					'modal_title',
				),
			)
		);

		$modal_close_icon = $multi_view->render_element(
			array(
				'content'  => '{{modal_close_icon}}',
				'attrs'    => array(
					'class' => 'el_modal_popup_close el_modal_popup_close_icon et-pb-icon',
				),
				'required' => array(
					'show_header'     => 'on',
					'show_close_icon' => 'on',
				),
			)
		);

		if ( '' !== $modal_title ) {
			$modal_title = sprintf(
				'<div class="el_modal_popup_header_title_container">%1$s</div>',
				$modal_title
			);
		}

		if ( '' !== $modal_title || '' !== $modal_close_icon ) {
			$modal_header = sprintf(
				'<div class="el_modal_popup_header">%1$s%2$s</div>',
				'' !== $modal_close_icon ? $modal_close_icon : '',
				'' !== $modal_title ? $modal_title : ''
			);
		}

		if ( '' !== $modal_close_icon ) {
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
                $this->generate_styles(
                    array(
                        'utility_arg'    => 'icon_font_family',
                        'render_slug'    => $render_slug,
                        'base_attr_name' => 'modal_close_icon',
                        'important'      => true,
                        'selector'       => '%%order_class%%_module .el_modal_popup_close_icon',
                        'processor'      => array(
                            'ET_Builder_Module_Helper_Style_Processor',
                            'process_extended_icon',
                        ),
                    )
                );
            }
		}

		if ( 'on' === $show_footer ) {
			$close_button_text  = '' !== $close_button_text ? $close_button_text : esc_html__( 'Close', 'divi-modal-popup' );
			$modal_close_button = $this->render_button(
				array(
					'button_text'         => esc_html( $close_button_text ),
					'button_text_escaped' => true,
					'button_url'          => '#',
					'button_classname'    => array( 'el_modal_popup_close el_modal_popup_close_button' ),
					'button_custom'       => isset( $custom_close_button ) ? $custom_close_button : 'off',
					'custom_icon'         => isset( $close_button_icon ) ? $close_button_icon : '',
					'has_wrapper'         => true,
					'button_rel'          => esc_html( $this->props['close_button_rel'] ),
				)
			);

			if ( '' !== $modal_close_button ) {
				$modal_footer = sprintf(
					'<div class="el_modal_popup_footer">%1$s</div>',
					$modal_close_button
				);
			}
		}

		switch ( $modal_content_type ) {
			case 'text':
				$modal_body_content = $multi_view->render_element(
					array(
						'tag'      => 'div',
						'content'  => '{{content}}',
						'attrs'    => array(
							'class' => 'el_modal_popup_content_text',
						),
						'required' => 'content',
					)
				);
				break;

			case 'image':
				$modal_body_content = $multi_view->render_element(
					array(
						'tag'      => 'img',
						'attrs'    => array(
							'src'   => '{{modal_content_image}}',
							'class' => 'el_modal_popup_content_image',
							'alt'   => esc_html( $modal_content_image_alt ),
						),
						'required' => 'modal_content_image',
					)
				);

				if ( '' !== $this->props['modal_content_image_link'] ) {
					$modal_body_content = sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( $this->props['modal_content_image_link'] ),
						et_core_intentionally_unescaped( $modal_body_content, 'html' )
					);
				}
				break;

			case 'video':
				$video_srcs = self::get_video(
					array(
						'modal_content_video' => $multi_view->get_inherit_value( 'modal_content_video', 'desktop' ),
					)
				);
				$multi_view->set_custom_prop( 'video_srcs', $video_srcs );
				$modal_body_content = $multi_view->render_element(
					array(
						'tag'      => 'div',
						'content'  => '{{video_srcs}}',
						'attrs'    => array(
							'class' => 'el_modal_popup_content_video et_pb_video_box',
						),
						'required' => 'modal_content_video',
					)
				);
				break;

			case 'layout':
				if ( 0 !== intval( $modal_content_layout ) ) {
					$modal_body_content = self::get_library_layout(
						array(
							'modal_content_layout' => intval( $modal_content_layout ),
						)
					);
				}
				break;

			default:
				break;
		}

		if ( isset( $modal_body_content ) && '' !== $modal_body_content ) {
			$modal_body = sprintf(
				'<div class="el_modal_popup_body">%1$s</div>',
				$modal_body_content
			);
		}

		if ( 'none' !== $animation_style ) {
			$data_animation_duration        = sprintf(
				' data-animation-duration="%1$s"',
				esc_attr( $animation_durations['desktop'] )
			);
			$data_animation_duration_tablet = sprintf(
				' data-animation-duration-tablet="%1$s"',
				esc_attr( $animation_durations['tablet'] )
			);
			$data_animation_duration_phone  = sprintf(
				' data-animation-duration-phone="%1$s"',
				esc_attr( $animation_durations['phone'] )
			);
		}

		if ( 'element' === $trigger_type && 'element_id' === $trigger_element_type ) {
			$data_trigger_element_id = sprintf(
				' data-trigger-element-id="%1$s"',
				esc_attr( $trigger_element_id )
			);
		}

		if ( 'element' === $trigger_type && 'element_class' === $trigger_element_type ) {
			$data_trigger_element_class = sprintf(
				' data-trigger-element-class="%1$s"',
				esc_attr( $trigger_element_class )
			);
		}

		$modal_wrapper_classes = array( 'el_modal_popup_' . $modal_position );

		if ( 'on' === $this->props['close_on_esc'] ) {
			array_push( $modal_wrapper_classes, 'el_modal_popup_close_on_esc' );
		}

		if ( 'on' === $this->props['close_on_background_click'] ) {
			array_push( $modal_wrapper_classes, 'el_modal_popup_close_on_background_click' );	
		}

		$modal_wrapper_classes = implode( ' ', $modal_wrapper_classes );

		$modal_wrapper = sprintf(
			'<div class="el_modal_popup_wrapper %4$s" data-disable-website-scroll="%16$s" data-id="%12$s" data-re-render="%13$s" data-autoplay-video="%14$s" data-trigger-type="%6$s" data-trigger-delay="%7$s" %8$s %9$s %10$s %11$s %15$s %17$s>
                <div class="el_modal_popup_inner_wrap%5$s">
                    %1$s
                    %2$s
                    %3$s
                </div>
            </div>',
			isset( $modal_header ) ? $modal_header : '',
			isset( $modal_body ) ? $modal_body : '',
			isset( $modal_footer ) ? $modal_footer : '',
			esc_attr( $modal_wrapper_classes ),
			'none' !== $animation_style ? ' el_modal_popup_animated' : '',
			esc_attr( $trigger_type ),
			esc_attr( $trigger_delay ),
			isset( $data_animation_duration ) ? $data_animation_duration : '',
			isset( $data_animation_duration_tablet ) ? $data_animation_duration_tablet : '',
			isset( $data_animation_duration_phone ) ? $data_animation_duration_phone : '',
			isset( $data_trigger_element_id ) ? $data_trigger_element_id : '',
			esc_attr( $modal_id ),
			esc_attr( $re_rendering ),
			'video' === $modal_content_type && 'on' === $autoplay_video ? 'on' : 'off',
			isset( $data_trigger_element_class ) ? $data_trigger_element_class : '',
			esc_attr( $disable_website_scroll ),
			'on_scroll' === $trigger_type ? sprintf( ' data-scroll-position="%1$s"', esc_attr( $scroll_position ) ) : ''
		);

		if ( 'element' === $trigger_type && 'element_id' !== $trigger_element_type && 'element_class' !== $trigger_element_type ) {
			$output = $trigger_element_wrapper . $modal_wrapper;
		} else {
			$output = $modal_wrapper;
		}

		if (
			'on_page_load' === $trigger_type ||
			'exit_intent' === $trigger_type ||
			( 'element' === $trigger_type && 'element_id' === $trigger_element_type ) ||
			( 'element' === $trigger_type && 'element_class' === $trigger_element_type )
		) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => $this->main_css_element,
					'declaration' => 'margin-bottom: 0 !important;',
				)
			);
		}

		if ( 'element' === $trigger_type && 'icon' === $trigger_element_type ) {
			$trigger_icon_font_sizes = et_pb_responsive_options()->get_property_values( $this->props, 'trigger_icon_font_size' );
			$trigger_icon_colors     = et_pb_responsive_options()->get_property_values( $this->props, 'trigger_icon_color' );
			et_pb_responsive_options()->generate_responsive_css( $trigger_icon_font_sizes, '%%order_class%% .el_modal_popup_trigger_icon', 'font-size', $render_slug, '', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $trigger_icon_colors, '%%order_class%% .el_modal_popup_trigger_icon', 'color', $render_slug, '', 'type' );
		}

		$modal_close_icon_color = et_pb_responsive_options()->get_property_values( $this->props, 'modal_close_icon_color' );
		et_pb_responsive_options()->generate_responsive_css( $modal_close_icon_color, '%%order_class%%_module .el_modal_popup_close_icon', 'color', $render_slug, '', 'type' );

		$modal_close_icon_color_hover    = $this->get_hover_value( 'modal_close_icon_color' );
        if ( $modal_close_icon_color_hover ) {
            self::set_style( $render_slug, array(
                'selector'    => '%%order_class%%_module .el_modal_popup_close_icon:hover',
                'declaration' => sprintf(
                    'color: %1$s !important;',
                    esc_attr( $modal_close_icon_color_hover )
                ),
            ) );
        }

		$modal_close_icon_font_sizes = et_pb_responsive_options()->get_property_values( $this->props, 'modal_close_icon_font_size' );
		et_pb_responsive_options()->generate_responsive_css( $modal_close_icon_font_sizes, '%%order_class%%_module .el_modal_popup_close_icon', 'font-size', $render_slug, '!important', 'range' );

		if ( isset( $this->props['disabled_on'] ) && $this->props['disabled_on']  ) {
			$disabled_on = str_replace( array( 'on', 'off' ), array( 'none', 'block' ), $this->props['disabled_on'] );
			$disabled_on = array_combine( array( 'phone', 'tablet', 'desktop' ), explode( '|', $disabled_on ) );
			$disabled_on = array_reverse( $disabled_on );
			et_pb_responsive_options()->generate_responsive_css( $disabled_on, '%%order_class%%_module', 'display', $render_slug, '', 'display' );
		}

		/* Scrollbar CSS */
        if ( 'hide' === $modal_body_scrollbar ) {
        	self::set_style( $render_slug, array(
                'selector'    => '%%order_class%%_module .el_modal_popup_body::-webkit-scrollbar',
                'declaration' => 'display: none;',
            ) );
            self::set_style( $render_slug, array(
                'selector'    => '%%order_class%%_module .el_modal_popup_body',
                'declaration' => 'scrollbar-width: none;',
            ) );
        }

		$backgrounds = array(
			'trigger_element_bg' => array(
				'normal' => "{$this->main_css_element} .el_modal_popup_trigger_element",
				'hover' => "{$this->main_css_element} .el_modal_popup_trigger_element:hover",
 			),
 			'modal_overlay_bg' => array(
 				'normal' => "{$this->main_css_element}_module .el_modal_popup_wrapper",
 				'hover' => "{$this->main_css_element}_module .el_modal_popup_wrapper:hover",
 			),
 			'modal_bg' => array(
				'normal' => "{$this->main_css_element}_module .el_modal_popup_wrapper .el_modal_popup_inner_wrap",
				'hover' => "{$this->main_css_element}_module .el_modal_popup_wrapper .el_modal_popup_inner_wrap:hover",
 			),
 			'modal_header_bg' => array(
				'normal' => "{$this->main_css_element}_module .el_modal_popup_header",
				'hover' => "{$this->main_css_element}_module .el_modal_popup_header:hover",
 			),
 			'modal_body_bg' => array(
				'normal' => "{$this->main_css_element}_module .el_modal_popup_body",
				'hover' => "{$this->main_css_element}_module .el_modal_popup_body:hover",
 			),
 			'modal_footer_bg' => array(
				'normal' => "{$this->main_css_element}_module .el_modal_popup_footer",
				'hover' => "{$this->main_css_element}_module .el_modal_popup_footer:hover",
 			),
		);
		
		if ( ET_BUILDER_PRODUCT_VERSION >= '4.16.0' ) {
			foreach( $backgrounds as $basename => $css_element ) {
	            $bg_args = array(
	                'base_prop_name'                => $basename,
	                'props'                         => $this->props,
	                'important'                     => ' !important',
	                'fields_definition'             => $this->fields_unprocessed,
	                'selector'                      => $css_element['normal'],
	                'selector_hover'                => $css_element['hover'],
	                'function_name'                 => $render_slug,
	                'has_background_color_toggle'   => false,
	                'use_background_color'          => true,
	                'use_background_image'          => true,
	                'use_background_color_reset'    => true,
	                'use_background_video'          => false,
	                'use_background_pattern'        => false,
	                'use_background_mask'           => false,
	                'use_background_image_parallax' => false,
	                'prop_name_aliases'             => array(
	                    "use_{$basename}_color_gradient" => "{$basename}_use_color_gradient",
	                    "{$basename}" => "{$basename}_color",
	                ),
	            );

	            // Process background style.
	            et_pb_background_options()->get_background_style( $bg_args );
	        }
	    } else {
	    	$this->process_custom_background( $backgrounds, $render_slug );
	    }

		$this->process_modal_animation_css( $animation_style, $render_slug );
		$this->process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding );
		
		return et_core_intentionally_unescaped( $output, 'html' ); 
	}

	public function process_modal_animation_css( $animation_style, $render_slug ) {
		// Animation Styles.
		// Check if this is an AJAX request since this is how VB loads the initial module data
		// et_core_is_fb_enabled() always returns `false` here
		if ( $animation_style && 'none' !== $animation_style ) {
			$animation_styles = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_style' );
			if ( 'fade' !== $animation_style ) {
				$directions_list     = array( 'center', 'up', 'right', 'down', 'left' );
				$animation_direction = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_direction' );
				// Desktop animation style.
				if ( ! empty( $animation_direction['desktop'] ) ) {
					$animation_style_desktop = in_array( $animation_direction['desktop'], $directions_list, true ) ? $animation_direction['desktop'] : '';
					if ( '' !== $animation_style_desktop ) {
						$animation_styles['desktop'] = $animation_style . '_' . $animation_style_desktop;
					}
				}

				if ( et_pb_responsive_options()->is_responsive_enabled( $this->props, 'modal_animation_direction' ) ) {
					// Tablet animation style.
					if ( ! empty( $animation_direction['tablet'] ) ) {
						$animation_style_tablet = in_array( $animation_direction['tablet'], $directions_list, true ) ? $animation_direction['tablet'] : '';
						if ( '' !== $animation_style_tablet ) {
							$animation_styles['tablet'] = $animation_style . '_' . $animation_style_tablet;
						}
					}
					// Phone animation style.
					if ( ! empty( $animation_direction['phone'] ) ) {
						$animation_style_phone = in_array( $animation_direction['phone'], $directions_list, true ) ? $animation_direction['phone'] : '';
						if ( '' !== $animation_style_phone ) {
							$animation_styles['phone'] = $animation_style . '_' . $animation_style_phone;
						}
					} elseif ( ! empty( $animation_styles['tablet'] ) ) {
						$animation_styles['phone'] = $animation_styles['tablet'];
					}
				}
			}

			$starting_opacities     = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_starting_opacity' );
			$animation_durations    = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_duration' );
			$animation_speed_curves = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_speed_curve' );

			et_pb_responsive_options()->generate_responsive_css( $starting_opacities, '%%order_class%%_module .el_modal_popup_animated', 'opacity', $render_slug, '', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $animation_durations, '%%order_class%%_module .el_modal_popup_animated', 'animation-duration', $render_slug, '', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $animation_speed_curves, '%%order_class%%_module .el_modal_popup_animated', 'animation-timing-function', $render_slug, '', 'type' );

			if ( in_array( $animation_style, array( 'slide', 'zoom', 'flip', 'fold', 'roll' ), true ) ) {
				$animation_intensities = et_pb_responsive_options()->get_property_values( $this->props, 'modal_animation_intensity' );
				if ( empty( $animation_intensities['tablet'] ) ) {
					$animation_intensities['tablet'] = $animation_intensities['desktop'];
				}
				if ( empty( $animation_intensities['phone'] ) ) {
					$animation_intensities['phone'] = $animation_intensities['tablet'];
				}
				$translate_values = array();
				foreach ( $animation_intensities as $device => $animation_intensity ) {
					if ( '' !== $animation_intensity ) {
						$animation_values = explode( '_', $animation_styles[ $device ] );
						if ( ! empty( array_filter( $animation_values ) ) ) {
							$anim_style       = isset( $animation_values[0] ) ? $animation_values[0] : 'fade';
							$anim_direction   = isset( $animation_values[1] ) ? $animation_values[1] : 'center';
							switch ( $anim_style ) {
								case 'slide':
									$translate_value = floatVal( $animation_intensity ) * 2 . '%';
									switch ( $anim_direction ) {
										case 'center':
											$translate_value             = floatval( ( 100 - floatVal( $animation_intensity ) ) / 100 );
											$translate_values[ $device ] = "scale3d( {$translate_value}, {$translate_value}, {$translate_value} )";
											break;

										case 'up':
											$translate_values[ $device ] = "translate3d( 0, {$translate_value}, 0 )";
											break;

										case 'down':
											$translate_values[ $device ] = "translate3d( 0, -{$translate_value}, 0 )";
											break;

										case 'left':
											$translate_values[ $device ] = "translate3d( {$translate_value}, 0, 0 )";
											break;

										case 'right':
											$translate_values[ $device ] = "translate3d( -{$translate_value}, 0, 0 )";
											break;

										default:
											break;
									}
									break;

								case 'flip':
									$translate_value = ceil( ( floatval( $animation_intensity ) * 90 ) / 100 ) . 'deg';
									switch ( $anim_direction ) {
										case 'center':
											$translate_values[ $device ] = "perspective(2000px)rotateX({$translate_value})";
											break;

										case 'up':
											$translate_values[ $device ] = "perspective(2000px)rotateX(-{$translate_value})";
											break;

										case 'down':
											$translate_values[ $device ] = "perspective(2000px)rotateX({$translate_value})";
											break;

										case 'left':
											$translate_values[ $device ] = "perspective(2000px)rotateY({$translate_value})";
											break;

										case 'right':
											$translate_values[ $device ] = "perspective(2000px)rotateY(-{$translate_value})";
											break;

										default:
											break;
									}
									break;

								case 'fold':
									$translate_value = ceil( ( floatval( $animation_intensity ) * 90 ) / 100 ) . 'deg';
									switch ( $anim_direction ) {
										case 'center':
											$translate_values[ $device ] = "perspective(2000px)rotateY({$translate_value})";
											break;

										case 'up':
											$translate_values[ $device ] = "perspective(2000px)rotateX({$translate_value})";
											break;

										case 'down':
											$translate_values[ $device ] = "perspective(2000px)rotateX(-{$translate_value})";
											break;

										case 'left':
											$translate_values[ $device ] = "perspective(2000px)rotateY(-{$translate_value})";
											break;

										case 'right':
											$translate_values[ $device ] = "perspective(2000px)rotateY({$translate_value})";
											break;

										default:
											break;
									}
									break;

								case 'roll':
									$translate_value = ceil( ( floatval( $animation_intensity ) * 360 ) / 100 ) . 'deg';
									switch ( $anim_direction ) {
										case 'center':
											$translate_values[ $device ] = "perspective(2000px)rotateZ({$translate_value})";
											break;

										case 'up':
											$translate_values[ $device ] = "perspective(2000px)rotateZ(-{$translate_value})";
											break;

										case 'down':
											$translate_values[ $device ] = "perspective(2000px)rotateZ({$translate_value})";
											break;

										case 'left':
											$translate_values[ $device ] = "perspective(2000px)rotateZ(-{$translate_value})";
											break;

										case 'right':
											$translate_values[ $device ] = "perspective(2000px)rotateZ({$translate_value})";
											break;

										default:
											break;
									}
									break;

								case 'zoom':
									$translate_value             = floatval( ( 100 - floatVal( $animation_intensity ) ) / 100 );
									$translate_values[ $device ] = "scale3d( {$translate_value}, {$translate_value}, {$translate_value} )";
									break;

								default:
									break;
							}
						}
					}
				}

				et_pb_responsive_options()->generate_responsive_css( $translate_values, '%%order_class%%_module .el_modal_popup_animated', 'transform', $render_slug, '', 'type' );
			}

			$animation_names = preg_filter( '/^/', 'el_modal_popup_animate_', array_filter( $animation_styles ) );
			et_pb_responsive_options()->generate_responsive_css( $animation_names, '%%order_class%%_module.el_modal_popup_active_modal .el_modal_popup_animated', 'animation-name', $render_slug, '', 'type' );
			et_pb_responsive_options()->generate_responsive_css( $animation_names, '%%order_class%%_module.el_modal_popup_animate_reverse .el_modal_popup_animated', 'animation-name', $render_slug, '', 'type' );
		}
	}

	public function process_advanced_margin_padding_css( $module, $function_name, $margin_padding ) {
		$utils           = ET_Core_Data_Utils::instance();
		$all_values      = $module->props;
		$advanced_fields = $module->advanced_fields;

		// Disable if module doesn't set advanced_fields property and has no VB support.
		if ( ! $module->has_vb_support() && ! $module->has_advanced_fields ) {
			return;
		}

		$allowed_advanced_fields = array( 'modal_margin_padding' );
		foreach ( $allowed_advanced_fields as $advanced_field ) {
			if ( ! empty( $advanced_fields[ $advanced_field ] ) ) {
				foreach ( $advanced_fields[ $advanced_field ] as $label => $form_field ) {
					$margin_key  = "{$label}_custom_margin";
					$padding_key = "{$label}_custom_padding";
					if ( '' !== $utils->array_get( $all_values, $margin_key, '' ) || '' !== $utils->array_get( $all_values, $padding_key, '' ) ) {
						$settings = $utils->array_get( $form_field, 'margin_padding', array() );
						// Ensure main selector exists.
						$form_field_margin_padding_css = $utils->array_get( $settings, 'css.main', '' );
						if ( empty( $form_field_margin_padding_css ) ) {
							$utils->array_set( $settings, 'css.main', $utils->array_get( $form_field, 'css.main', '' ) );
						}

						$margin_padding->update_styles( $module, $label, $settings, $function_name, $advanced_field );
					}
				}
			}
		}
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed                                     $raw_value Props raw value.
	 * @param array                                     $args {
	 *                                         Context data.
	 *
	 *     @type string $context      Context param: content, attrs, visibility, classes.
	 *     @type string $name         Module options props name.
	 *     @type string $mode         Current data mode: desktop, hover, tablet, phone.
	 *     @type string $attr_key     Attribute key for attrs context data. Example: src, class, etc.
	 *     @type string $attr_sub_key Attribute sub key that availabe when passing attrs value as array such as styes. Example: padding-top, margin-botton, etc.
	 * }
	 * @param ET_Builder_Module_Helper_MultiViewOptions $multi_view Multiview object instance.
	 *
	 * @return mixed
	 */
	public function multi_view_filter_value( $raw_value, $args, $multi_view ) {
		$name = isset( $args['name'] ) ? $args['name'] : '';
		$mode = isset( $args['mode'] ) ? $args['mode'] : '';

		if ( ( $raw_value && 'trigger_icon' === $name ) || ( $raw_value && 'modal_close_icon' === $name ) ) {
			$processed_value = html_entity_decode( et_pb_process_font_icon( $raw_value ) );
			if ( '%%1%%' === $raw_value ) {
				$processed_value = '"';
			}

			return $processed_value;
		}

		return $raw_value;
	}

	public function process_custom_background( $backgrounds, $function_name ) {

		foreach ( $backgrounds as $basename => $css_element ) {

			// Place to store processed background. It will be compared with the smaller device
			// background processed value to avoid rendering the same styles.
			$processed_background_color = '';
			$processed_background_image = '';
			$processed_background_blend = '';

			// Store background images status because the process is extensive.
			$background_image_status = array(
				'desktop' => false,
				'tablet'  => false,
				'phone'   => false,
			);

			// Background Options Styling.
			foreach ( et_pb_responsive_options()->get_modes() as $device ) {
				$background_base_name = $basename;
				$background_prefix    = "{$basename}_";
				$background_style     = '';
				$is_desktop           = 'desktop' === $device;
				$suffix               = ! $is_desktop ? "_{$device}" : '';

				$background_color_style = '';
				$background_image_style = '';
				$background_images      = array();

				$has_background_color_gradient         = false;
				$has_background_image                  = false;
				$has_background_gradient_and_image     = false;
				$is_background_color_gradient_disabled = false;
				$is_background_image_disabled          = false;

				$background_color_gradient_overlays_image = 'off';

				// Ensure responsive is active.
				if ( ! $is_desktop && ! et_pb_responsive_options()->is_responsive_enabled( $this->props, "{$basename}_color" ) ) {
					continue;
				}

				// A. Background Gradient.
				$use_background_color_gradient = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}use_color_gradient", $device, $background_base_name, $this->fields_unprocessed );

				if ( 'on' === $use_background_color_gradient ) {
					$background_color_gradient_overlays_image = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_overlays_image{$suffix}", '', true );

					$gradient_properties = array(
						'type'             => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_type{$suffix}", '', true ),
						'direction'        => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_direction{$suffix}", '', true ),
						'radial_direction' => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_direction_radial{$suffix}", '', true ),
						'color_start'      => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_start{$suffix}", '', true ),
						'color_end'        => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_end{$suffix}", '', true ),
						'start_position'   => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_start_position{$suffix}", '', true ),
						'end_position'     => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_end_position{$suffix}", '', true ),
					);

					// Save background gradient into background images list.
					$background_images[] = $this->get_gradient( $gradient_properties );

					// Flag to inform BG Color if current module has Gradient.
					$has_background_color_gradient = true;
				} elseif ( 'off' === $use_background_color_gradient ) {
					$is_background_color_gradient_disabled = true;
				}

				// B. Background Image.
				$background_image = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}image", $device, $background_base_name, $this->fields_unprocessed );
				$parallax         = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}parallax{$suffix}", 'off' );

				// BG image and parallax status.
				$is_background_image_active         = '' !== $background_image && 'on' !== $parallax;
				$background_image_status[ $device ] = $is_background_image_active;

				if ( $is_background_image_active ) {
					// Flag to inform BG Color if current module has Image.
					$has_background_image = true;

					// Check previous BG image status. Needed to get the correct value.
					$is_prev_background_image_active = true;
					if ( ! $is_desktop ) {
						$is_prev_background_image_active = 'tablet' === $device ? $background_image_status['desktop'] : $background_image_status['tablet'];
					}

					// Size.
					$background_size_default = ET_Builder_Element::$_->array_get( $this->fields_unprocessed, "{$background_prefix}size.default", '' );
					$background_size         = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}size{$suffix}", $background_size_default, ! $is_prev_background_image_active );

					if ( '' !== $background_size ) {
						$background_style .= sprintf(
							'background-size: %1$s; ',
							esc_html( $background_size )
						);
					}

					// Position.
					$background_position_default = ET_Builder_Element::$_->array_get( $this->fields_unprocessed, "{$background_prefix}position.default", '' );
					$background_position         = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}position{$suffix}", $background_position_default, ! $is_prev_background_image_active );

					if ( '' !== $background_position ) {
						$background_style .= sprintf(
							'background-position: %1$s; ',
							esc_html( str_replace( '_', ' ', $background_position ) )
						);
					}

					// Repeat.
					$background_repeat_default = ET_Builder_Element::$_->array_get( $this->fields_unprocessed, "{$background_prefix}repeat.default", '' );
					$background_repeat         = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}repeat{$suffix}", $background_repeat_default, ! $is_prev_background_image_active );

					if ( '' !== $background_repeat ) {
						$background_style .= sprintf(
							'background-repeat: %1$s; ',
							esc_html( $background_repeat )
						);
					}

					// Blend.
					$background_blend_default = ET_Builder_Element::$_->array_get( $this->fields_unprocessed, "{$background_prefix}blend.default", '' );
					$background_blend         = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}blend{$suffix}", $background_blend_default, ! $is_prev_background_image_active );
					$background_blend_inherit = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}blend{$suffix}", '', true );

					if ( '' !== $background_blend_inherit ) {
						// Don't print the same image blend style.
						if ( '' !== $background_blend ) {
							$background_style .= sprintf(
								'background-blend-mode: %1$s; ',
								esc_html( $background_blend )
							);
						}

						// Reset - If background has image and gradient, force background-color: initial.
						if ( $has_background_color_gradient && $has_background_image && $background_blend_inherit !== $background_blend_default ) {
							$has_background_gradient_and_image = true;
							$background_color_style            = 'initial';
							$background_style                 .= 'background-color: initial; ';
						}

						$processed_background_blend = $background_blend;
					}

					// Only append background image when the image is exist.
					$background_images[] = sprintf( 'url(%1$s)', esc_html( $background_image ) );
				} elseif ( '' === $background_image ) {
					// Reset - If background image is disabled, ensure we reset prev background blend mode.
					if ( '' !== $processed_background_blend ) {
						$background_style          .= 'background-blend-mode: normal; ';
						$processed_background_blend = '';
					}

					$is_background_image_disabled = true;
				}

				if ( ! empty( $background_images ) ) {
					// The browsers stack the images in the opposite order to what you'd expect.
					if ( 'on' !== $background_color_gradient_overlays_image ) {
						$background_images = array_reverse( $background_images );
					}

					// Set background image styles only it's different compared to the larger device.
					$background_image_style = join( ', ', $background_images );
					if ( $processed_background_image !== $background_image_style ) {
						$background_style .= sprintf(
							'background-image: %1$s !important;',
							esc_html( $background_image_style )
						);
					}
				} elseif ( ! $is_desktop && $is_background_color_gradient_disabled && $is_background_image_disabled ) {
					// Reset - If background image and gradient are disabled, reset current background image.
					$background_image_style = 'initial';
					$background_style      .= 'background-image: initial !important;';
				}

				// Save processed background images.
				$processed_background_image = $background_image_style;

				// C. Background Color.
				if ( ! $has_background_gradient_and_image ) {
					// Background color `initial` was added by default to reset button background
					// color when user disable it on mobile preview mode. However, it should
					// be applied only when the background color is really disabled because user
					// may use theme customizer to setup global button background color. We also
					// need to ensure user still able to disable background color on mobile.
					$background_color_enable  = ET_Builder_Element::$_->array_get( $this->props, "{$background_prefix}enable_color{$suffix}", '' );
					$background_color_initial = 'off' === $background_color_enable && ! $is_desktop ? 'initial' : '';

					$background_color       = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}color", $device, $background_base_name, $this->fields_unprocessed );
					$background_color       = '' !== $background_color ? $background_color : $background_color_initial;
					$background_color_style = $background_color;

					if ( '' !== $background_color && $processed_background_color !== $background_color ) {
						$background_style .= sprintf(
							'background-color: %1$s; ',
							esc_html( $background_color )
						);
					}
				}

				// Save processed background color.
				$processed_background_color = $background_color_style;

				// Print background gradient and image styles.
				if ( '' !== $background_style ) {
					$background_style_attrs = array(
						'selector'    => $css_element['normal'],
						'declaration' => rtrim( $background_style ),
						'priority'    => $this->_style_priority,
					);

					// Add media query attribute to background style attrs.
					if ( 'desktop' !== $device ) {
						$current_media_query                   = 'tablet' === $device ? 'max_width_980' : 'max_width_767';
						$background_style_attrs['media_query'] = ET_Builder_Element::get_media_query( $current_media_query );
					}

					ET_Builder_Element::set_style( $function_name, $background_style_attrs );
				}
			}

			// Background Hover.
			if ( et_builder_is_hover_enabled( "{$basename}_color", $this->props ) ) {

				$background_base_name    = $basename;
				$background_prefix       = "{$basename}_";
				$background_images_hover = array();
				$background_hover_style  = '';

				$has_background_color_gradient_hover         = false;
				$has_background_image_hover                  = false;
				$has_background_gradient_and_image_hover     = false;
				$is_background_color_gradient_hover_disabled = false;
				$is_background_image_hover_disabled          = false;

				$background_color_gradient_overlays_image_desktop = et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_overlays_image", 'off', true );

				$gradient_properties_desktop = array(
					'type'             => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_type", '', true ),
					'direction'        => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_direction", '', true ),
					'radial_direction' => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_direction_radial", '', true ),
					'color_start'      => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_start", '', true ),
					'color_end'        => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_end", '', true ),
					'start_position'   => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_start_position", '', true ),
					'end_position'     => et_pb_responsive_options()->get_any_value( $this->props, "{$background_prefix}color_gradient_end_position", '', true ),
				);

				$background_color_gradient_overlays_image_hover = 'off';

				// Background Gradient Hover.
				// This part is little bit different compared to other hover implementation. In
				// this case, hover is enabled on the background field, not on the each of those
				// fields. So, built in function get_value() doesn't work in this case.
				// Temporarily, we need to fetch the the value from get_raw_value().
				$use_background_color_gradient_hover = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}use_color_gradient", 'hover', $background_base_name, $this->fields_unprocessed );

				if ( 'on' === $use_background_color_gradient_hover ) {
					// Desktop value as default.
					$background_color_gradient_type_desktop             = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'type', '' );
					$background_color_gradient_direction_desktop        = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'direction', '' );
					$background_color_gradient_radial_direction_desktop = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'radial_direction', '' );
					$background_color_gradient_color_start_desktop      = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'color_start', '' );
					$background_color_gradient_color_end_desktop        = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'color_end', '' );
					$background_color_gradient_start_position_desktop   = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'start_position', '' );
					$background_color_gradient_end_position_desktop     = ET_Builder_Element::$_->array_get( $gradient_properties_desktop, 'end_position', '' );

					// Hover value.
					$background_color_gradient_type_hover             = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_type", $this->props, $background_color_gradient_type_desktop );
					$background_color_gradient_direction_hover        = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_direction", $this->props, $background_color_gradient_direction_desktop );
					$background_color_gradient_direction_radial_hover = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_direction_radial", $this->props, $background_color_gradient_radial_direction_desktop );
					$background_color_gradient_start_hover            = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_start", $this->props, $background_color_gradient_color_start_desktop );
					$background_color_gradient_end_hover              = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_end", $this->props, $background_color_gradient_color_end_desktop );
					$background_color_gradient_start_position_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_start_position", $this->props, $background_color_gradient_start_position_desktop );
					$background_color_gradient_end_position_hover     = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_end_position", $this->props, $background_color_gradient_end_position_desktop );
					$background_color_gradient_overlays_image_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}color_gradient_overlays_image", $this->props, $background_color_gradient_overlays_image_desktop );

					$has_background_color_gradient_hover = true;

					$gradient_values_hover = array(
						'type'             => '' !== $background_color_gradient_type_hover ? $background_color_gradient_type_hover : $background_color_gradient_type_desktop,
						'direction'        => '' !== $background_color_gradient_direction_hover ? $background_color_gradient_direction_hover : $background_color_gradient_direction_desktop,
						'radial_direction' => '' !== $background_color_gradient_direction_radial_hover ? $background_color_gradient_direction_radial_hover : $background_color_gradient_radial_direction_desktop,
						'color_start'      => '' !== $background_color_gradient_start_hover ? $background_color_gradient_start_hover : $background_color_gradient_color_start_desktop,
						'color_end'        => '' !== $background_color_gradient_end_hover ? $background_color_gradient_end_hover : $background_color_gradient_color_end_desktop,
						'start_position'   => '' !== $background_color_gradient_start_position_hover ? $background_color_gradient_start_position_hover : $background_color_gradient_start_position_desktop,
						'end_position'     => '' !== $background_color_gradient_end_position_hover ? $background_color_gradient_end_position_hover : $background_color_gradient_end_position_desktop,
					);

					$background_images_hover[] = $this->get_gradient( $gradient_values_hover );
				} elseif ( 'off' === $use_background_color_gradient_hover ) {
					$is_background_color_gradient_hover_disabled = true;
				}

				// Background Image Hover.
				// This part is little bit different compared to other hover implementation. In
				// this case, hover is enabled on the background field, not on the each of those
				// fields. So, built in function get_value() doesn't work in this case.
				// Temporarily, we need to fetch the the value from get_raw_value().
				$background_image_hover = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}image", 'hover', $background_base_name, $this->fields_unprocessed );
				$parallax_hover         = et_pb_hover_options()->get_raw_value( "{$background_prefix}parallax", $this->props );

				if ( '' !== $background_image_hover && null !== $background_image_hover && 'on' !== $parallax_hover ) {
					// Flag to inform BG Color if current module has Image.
					$has_background_image_hover = true;

					// Size.
					$background_size_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}size", $this->props );
					$background_size_desktop = ET_Builder_Element::$_->array_get( $this->props, "{$background_prefix}size", '' );
					$is_same_background_size = $background_size_hover === $background_size_desktop;
					if ( empty( $background_size_hover ) && ! empty( $background_size_desktop ) ) {
						$background_size_hover = $background_size_desktop;
					}

					if ( ! empty( $background_size_hover ) && ! $is_same_background_size ) {
						$background_hover_style .= sprintf(
							'background-size: %1$s; ',
							esc_html( $background_size_hover )
						);
					}

					// Position.
					$background_position_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}position", $this->props );
					$background_position_desktop = ET_Builder_Element::$_->array_get( $this->props, "{$background_prefix}position", '' );
					$is_same_background_position = $background_position_hover === $background_position_desktop;
					if ( empty( $background_position_hover ) && ! empty( $background_position_desktop ) ) {
						$background_position_hover = $background_position_desktop;
					}

					if ( ! empty( $background_position_hover ) && ! $is_same_background_position ) {
						$background_hover_style .= sprintf(
							'background-position: %1$s; ',
							esc_html( str_replace( '_', ' ', $background_position_hover ) )
						);
					}

					// Repeat.
					$background_repeat_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}repeat", $this->props );
					$background_repeat_desktop = ET_Builder_Element::$_->array_get( $this->props, "{$background_prefix}repeat", '' );
					$is_same_background_repeat = $background_repeat_hover === $background_repeat_desktop;
					if ( empty( $background_repeat_hover ) && ! empty( $background_repeat_desktop ) ) {
						$background_repeat_hover = $background_repeat_desktop;
					}

					if ( ! empty( $background_repeat_hover ) && ! $is_same_background_repeat ) {
						$background_hover_style .= sprintf(
							'background-repeat: %1$s; ',
							esc_html( $background_repeat_hover )
						);
					}

					// Blend.
					$background_blend_hover   = et_pb_hover_options()->get_raw_value( "{$background_prefix}blend", $this->props );
					$background_blend_default = ET_Builder_Element::$_->array_get( $this->fields_unprocessed, "{$background_prefix}blend.default", '' );
					$background_blend_desktop = ET_Builder_Element::$_->array_get( $this->props, "{$background_prefix}blend", '' );
					$is_same_background_blend = $background_blend_hover === $background_blend_desktop;
					if ( empty( $background_blend_hover ) && ! empty( $background_blend_desktop ) ) {
						$background_blend_hover = $background_blend_desktop;
					}

					if ( ! empty( $background_blend_hover ) ) {
						if ( ! $is_same_background_blend ) {
							$background_hover_style .= sprintf(
								'background-blend-mode: %1$s; ',
								esc_html( $background_blend_hover )
							);
						}

						// Force background-color: initial.
						if ( $has_background_color_gradient_hover && $has_background_image_hover && $background_blend_hover !== $background_blend_default ) {
							$has_background_gradient_and_image_hover = true;
							$background_hover_style                 .= 'background-color: initial !important;';
						}
					}

					// Only append background image when the image exists.
					$background_images_hover[] = sprintf( 'url(%1$s)', esc_html( $background_image_hover ) );
				} elseif ( '' === $background_image_hover ) {
					$is_background_image_hover_disabled = true;
				}

				if ( ! empty( $background_images_hover ) ) {
					// The browsers stack the images in the opposite order to what you'd expect.
					if ( 'on' !== $background_color_gradient_overlays_image_hover ) {
						$background_images_hover = array_reverse( $background_images_hover );
					}

					$background_hover_style .= sprintf(
						'background-image: %1$s !important;',
						esc_html( join( ', ', $background_images_hover ) )
					);
				} elseif ( $is_background_color_gradient_hover_disabled && $is_background_image_hover_disabled ) {
					$background_hover_style .= 'background-image: initial !important;';
				}

				// Background Color Hover.
				if ( ! $has_background_gradient_and_image_hover ) {
					$background_color_hover = et_pb_responsive_options()->get_inheritance_background_value( $this->props, "{$background_prefix}color", 'hover', $background_base_name, $this->fields_unprocessed );
					$background_color_hover = '' !== $background_color_hover ? $background_color_hover : 'transparent';

					if ( '' !== $background_color_hover ) {
						$background_hover_style .= sprintf(
							'background-color: %1$s !important; ',
							esc_html( $background_color_hover )
						);
					}
				}

				// Print background hover gradient and image styles.
				if ( '' !== $background_hover_style ) {
					$background_hover_style_attrs = array(
						'selector'    => $css_element['hover'],
						'declaration' => rtrim( $background_hover_style ),
						'priority'    => $this->_style_priority,
					);

					ET_Builder_Element::set_style( $function_name, $background_hover_style_attrs );
				}
			}
		}
	}

}
new EL_DMP_Modal();
