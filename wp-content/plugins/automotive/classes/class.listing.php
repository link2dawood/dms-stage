<?php

if ( ! class_exists( "Listing" ) ) {

	/**
	 * Listing Class
	 */
	#[AllowDynamicProperties]
	class Listing {
		public $listing_categories = array();
		public $current_categories = array();
		public $current_query_info = array();

		public $listing_category_option = "listing_categories";

		public $current_min_max = array();

		/**
		 * Generates the listing categories option name
		 *
		 * @return string
		 */
		public function get_listing_categories_option_name() {
			return $this->listing_category_option;
		}

		/**
		 * Gets the stored listing categories
		 *
		 * @return array|mixed
		 */
		public function get_listing_categories_option() {
			$option = get_option( $this->get_listing_categories_option_name() );

			return $option;
		}

		public function refresh_listing_categories() {
			$this->listing_categories = $this->get_listing_categories_option();
		}

		public function get_listing_wp() {
			$this->lwp_options = get_option( "listing_wp" );
		}

		public function __construct() {
			$this->listing_categories = $this->get_listing_categories_option();

			// actions
			add_action( 'plugins_loaded', array( $this, 'import_start_session' ) );
			add_action( 'plugins_loaded', array( $this, 'load_redux' ) );
			add_action( 'init', array( $this, 'register_listing_post_type' ), 0 );
			add_action( 'init', array( $this, 'current_listing_categories' ) );
			add_action( 'pre_get_posts', array( $this, 'custom_listings_orderby' ) );
			add_action( 'manage_listings_posts_custom_column', array( $this, 'manage_listings_columns' ), 10, 2 );

			add_action( "wp_trash_post", array( $this, "listing_sent_trash" ) );
			add_action( "untrash_post", array( $this, "listing_restore_trash" ) );

			// in case WPML is active, re-load the listing categories
			add_action( "init", array( $this, "refresh_listing_categories" ) );
			add_action( "init", array( $this, "get_listing_wp" ) );

			// ajax actions
			add_action( "wp_ajax_search_box_shortcode_update_options", array(
				$this,
				"search_box_shortcode_update_options"
			) );
			add_action( "wp_ajax_nopriv_search_box_shortcode_update_options", array(
				$this,
				"search_box_shortcode_update_options"
			) );
			add_action( "wp_ajax_hide_automotive_message", array( $this, "hide_automotive_message" ) );
			add_action( "wp_ajax_edit_listing_category_value", array( $this, "edit_listing_category_value" ) );
			add_action( "wp_ajax_nopriv_edit_listing_category_value", array( $this, "deny_access_to_guest" ) );
			add_action( "wp_ajax_regenerate_listing_category_terms", array(
				$this,
				"regenerate_listing_category_terms"
			) );
			add_action( "wp_ajax_nopriv_regenerate_listing_category_terms", array( $this, "deny_access_to_guest" ) );
			add_action( "wp_ajax_nopriv_edit_listing_category_value", array( $this, "deny_access_to_guest" ) );
			add_action( "wp_ajax_regenerate_listing_wpml_terms", array(
				$this,
				"regenerate_listing_wpml_terms"
			) );
			add_action( "wp_ajax_nopriv_regenerate_listing_wpml_terms", array( $this, "deny_access_to_guest" ) );
			add_action( "wp_ajax_delete_unused_listing_terms", array( $this, "delete_unused_listing_terms" ) );
			add_action( "wp_ajax_nopriv_delete_unused_listing_terms", array( $this, "deny_access_to_guest" ) );

			add_action( 'wp_ajax_toggle_listing_features', array( $this, 'toggle_listing_features' ) );
			add_action( 'wp_ajax_nopriv_toggle_listing_features', array( $this, 'deny_access_to_guest' ) );

			add_action( 'wp_ajax_add_new_listing_badge', array( $this, 'add_new_listing_badge' ) );
			add_action( 'wp_ajax_nopriv_add_new_listing_badge', array( $this, 'deny_access_to_guest' ) );

			add_action( 'wp_ajax_delete_listing_category_terms', array( $this, 'delete_listing_category_terms' ) );
			add_action( 'wp_ajax_nopriv_delete_listing_category_terms', array( $this, 'deny_access_to_guest' ) );

			// sold view on listing post type
			add_action( "views_edit-listings", array( $this, "add_sold_view" ) );
			add_action( "views_edit-listings", array( $this, "add_for_sale_view" ) );
			add_action( "pre_get_posts", array( $this, "add_sold_view_query" ) );

			// remove cache loading on comparison page
			add_action( "init", array( $this, "compare_no_cache" ) );

			add_action( "wp_ajax_add_name", array( $this, "ajax_add_listing_category_term" ) );
			add_action( "wp_ajax_nopriv_add_name", array( $this, "deny_access_to_guest" ) );

			add_action( "wp_ajax_delete_name", array( $this, "ajax_delete_listing_category_term" ) );
			add_action( "wp_ajax_nopriv_delete_name", array( $this, "deny_access_to_guest" ) );

			add_action( "wp_ajax_autocheck_get_report", array( $this, "autocheck_get_report" ) );
			add_action( "wp_ajax_nopriv_autocheck_get_report", array( $this, "autocheck_get_report" ) );

			// filters
			add_filter( 'manage_edit-listings_columns', array( $this, 'add_new_listings_columns' ) );
			add_filter( 'manage_edit-listings_sortable_columns', array( $this, 'order_column_register_sortable' ) );

			add_filter( "wp_mail_from", array( $this, "auto_filter_email_address" ) );
			add_filter( "wp_mail_from_name", array( $this, "auto_filter_email_name" ) );

			add_filter( 'image_size_names_choose', array( $this, 'automotive_image_size_select' ) );
		}

		/**
		 * Generic function to deny access to guests (or hackers)
		 */
		public function deny_access_to_guest() {
			die( "Access Denied" );
		}

		/**
		 * Used to grab listing categories from the public var
		 *
		 * @param bool|false $multi_options
		 *
		 * @return array|mixed
		 */
		public function get_listing_categories( $multi_options = false ) {
			$option = $this->listing_categories;

			if ( $multi_options == false && isset( $option['options'] ) && ! is_string( $option['options'] ) ) {
				unset( $option['options'] );
			}

			return $option;
		}

		/**
		 * Grabs a single listing category
		 *
		 * @param $category
		 *
		 * @return array
		 */
		public function get_single_listing_category( $category, $category_key = false ) {
			$current_categories = $this->get_listing_categories( true );

			if ( ! isset( $current_categories[ $category ] ) && empty( $current_categories[ $category ] ) ) {
				$return = array();
			} else {
				$return = $current_categories[ $category ];

				if ( $category_key && isset( $return[ $category_key ] ) ) {
					$return = $return[ $category_key ];
				}
			}

			return $return;
		}

		public function set_single_listing_category( $category, $is_options = false ) {
			$current_categories = $this->get_listing_categories( true );

			$current_categories[ ( $is_options == true ? "options" : $category['slug'] ) ] = $category;

			$this->update_listing_categories( $current_categories );
		}

		public function get_category_terms( $category_name ) {
			$terms    = array();
			$category = $this->get_single_listing_category( $category_name );

			if ( ! empty( $category['terms'] ) ) {
				$terms = $category['terms'];
			}

			return $terms;
		}

		/**
		 * Gets only the filterable listing categories
		 *
		 * @return array
		 */
		public function get_filterable_listing_categories() {
			$current_categories    = $this->get_listing_categories();
			$filterable_categories = array();

			if ( $current_categories != false ) {
				if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
					foreach ( $current_categories as $key => $category ) {
						if ( isset( $category['filterable'] ) && $category['filterable'] == 1 ) {
							$filterable_categories[ $key ] = $category;
						}
					}
				}
			}

			return $filterable_categories;
		}

		/**
		 * Gets only the amount listing categories
		 *
		 * @return array
		 */
		public function get_amount_listing_categories() {
			$current_categories = $this->get_listing_categories();
			$amount_categories  = array();

			if ( $current_categories != false ) {
				if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
					foreach ( $current_categories as $key => $category ) {
						if ( isset( $category['show_amount'] ) && $category['show_amount'] == 1 ) {
							$amount_categories[ $key ] = $category;
						}
					}
				}
			}

			return $amount_categories;
		}

		/**
		 * Get location specific categories
		 *
		 * @return string
		 */
		public function get_location_email_category() {
			$current_categories = $this->get_listing_categories();
			$return             = "";

			if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['location_email'] ) && $category['location_email'] == 1 ) {
						$return = $category['slug'];
					}
				}
			}

			return $return;
		}

		/**
		 * Gets categories that are used as columns
		 *
		 * @return array|string
		 */
		public function get_column_categories() {
			$current_categories = $this->get_listing_categories();
			$return             = array();

			if ( is_array( $current_categories ) && ! empty( $current_categories ) ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['column'] ) && $category['column'] == 1 ) {
						$return[] = $category;
					}
				}
			}

			return $return;
		}

		/**
		 * Gets the categories to be used in the listing preview area
		 *
		 * @return array
		 */
		public function get_use_on_listing_categories() {
			$use_on_categories  = array();
			$current_categories = $this->get_listing_categories();

			if ( $current_categories != false ) {
				foreach ( $current_categories as $category ) {
					if ( isset( $category['use_on_listing'] ) && $category['use_on_listing'] == 1 ) {
						$use_on_categories[ $category['singular'] ] = $category;
					}
				}
			}

			return $use_on_categories;
		}

		public function get_listing_categories_to_redux_select() {
			$return             = array();
			$listing_categories = $this->get_listing_categories( false );

			if ( ! empty( $listing_categories ) ) {
				foreach ( $listing_categories as $key => $category ) {
					if ( is_array( $category ) ) {
						$return[ $key ] = $category['singular'];
					}
				}
			}

			return $return;
		}

		public function get_pretty_listing_term( $category, $term_slug ) {
			$return = '';
			$terms  = $this->get_single_listing_category( $category, 'terms' );

			if ( $terms && is_array( $terms ) && isset( $terms[ $term_slug ] ) ) {
				$return = $terms[ $term_slug ];
			}

			return $return;
		}

		public function current_listing_categories( $get = array() ) {
			if ( empty( $get ) ) {
				$get = $_GET;
			}

			$filterable_categories = $this->get_filterable_listing_categories();
			$filter_multiselect    = automotive_listing_get_option( 'filter_showall', false ) && automotive_listing_get_option( 'filter_multiselect', false );

			if ( ! empty( $filterable_categories ) ) {
				foreach ( $filterable_categories as $key => $category ) {
					$slug = ( $category['slug'] == "year" ? "yr" : $category['slug'] );

					if ( ! empty( $slug ) ) {
						$is_min_max = isset( $get[ $slug ] ) && isset( $category['is_number'] ) && $category['is_number'] && ( ( ! is_array( $get[ $slug ] ) && strpos( $get[ $slug ], "," ) !== false ) || ( isset( $get[ $slug ] ) && is_array( $get[ $slug ] ) ) );//isset( $get[ $slug ] ) && is_array( $get[ $slug ] );
						$terms      = ( isset( $category['terms'] ) && ! empty( $category['terms'] ) ? $category['terms'] : array() );

						if ( $is_min_max ) {
							$this->min_max_categories[ $slug ] = $slug;

							if ( is_string( $get[ $slug ] ) ) {
								$get[ $slug ] = explode( ",", $get[ $slug ] );
							}
						}

						if ( isset( $get[ $slug ] ) && ! empty( $get[ $slug ] ) ) {
							if ( $is_min_max && ! empty( $get[ $slug ][0] ) && ! empty( $get[ $slug ][1] ) /*&& isset( $terms[ $get[ $slug ][0] ] ) && isset( $terms[ $get[ $slug ][1] ] )*/ ) {
								$this->current_categories[ $slug ] = $get[ $slug ];

							} elseif ( $is_min_max && ( ! empty( $get[ $slug ][0] ) && empty( $get[ $slug ][1] ) ) && isset( $terms[ $get[ $slug ][0] ] ) ) {
								$this->current_categories[ $slug ] = array(
									$get[ $slug ][0],
									null
								);

							} elseif ( $is_min_max && ( ! empty( $get[ $slug ][1] ) && empty( $get[ $slug ][0] ) ) && isset( $terms[ $get[ $slug ][1] ] ) ) {
								$this->current_categories[ $slug ] = array(
									null,
									$get[ $slug ][1]
								);

							} elseif ( isset( $get[ $slug ] ) &&
							           ! empty( $get[ $slug ] ) &&
							           ! is_array( $get[ $slug ] ) &&
							           isset( $terms[ $get[ $slug ] ] ) &&
							           ! empty( $terms[ $get[ $slug ] ] )
							) {
								$this->current_categories[ $slug ] = $get[ $slug ];

							} elseif ( $filter_multiselect && isset( $get[ $slug ] ) && ! empty( $get[ $slug ] ) ) {
								if ( is_string( $get[ $slug ] ) ) {
									$all_values = explode( ',', $get[ $slug ] );
								} else {
									$all_values = $get[ $slug ];
								}

								$all_values = array_values( array_filter( $all_values ) );

								if ( ! empty( $all_values ) && count( $all_values ) == 1 ) {
									$this->current_categories[ $slug ] = $all_values[0];
								} elseif ( ! empty( $all_values ) ) {
									$this->current_categories[ $slug ] = $all_values;
								}
							}
						}
					}
				}
			}

			// check for features and options
			$features     = ( isset( $get['features'] ) && ! empty( $get['features'] ) ? $get['features'] : false );
			$all_features = $this->get_category_terms( 'options' );

			if ( ! empty( $features ) ) {
				if ( ! is_array( $features ) ) {
					$features = array( $features );
				}

				$this->current_categories['features'] = array();

				foreach ( $features as $feature ) {
					if ( isset( $all_features[ $feature ] ) ) {
						$this->current_categories['features'][ $feature ] = $all_features[ $feature ];
					}
				}
			}
		}

		public function get_listing_meta( $post_id ) {
			$all_post_meta = get_post_meta_all( $post_id );

			if ( isset( $all_post_meta['listing_options'] ) && ! empty( $all_post_meta['listing_options'] ) ) {
				$all_post_meta['listing_options'] = @unserialize( unserialize( $all_post_meta['listing_options'] ) );
			}

			if ( isset( $all_post_meta['location_map'] ) && ! empty( $all_post_meta['location_map'] ) ) {
				$all_post_meta['location_map'] = @unserialize( $all_post_meta['location_map'] );
			}

			if ( isset( $all_post_meta['gallery_images'] ) && ! empty( $all_post_meta['gallery_images'] ) ) {
				$all_post_meta['gallery_images'] = @unserialize( $all_post_meta['gallery_images'] );
			}

			if ( isset( $all_post_meta['multi_options'] ) && ! empty( $all_post_meta['multi_options'] ) ) {
				$all_post_meta['multi_options'] = @unserialize( $all_post_meta['multi_options'] );
			}

			return $all_post_meta;
		}

		/**
		 * Starts the session for import pages
		 */
		function import_start_session() {
			$current_page = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? $_GET['page'] : "" );

			if ( ! empty( $current_page ) && ( $current_page == "file-import" || $current_page == "vin-import" ) && ! session_id() ) {
				session_start();
			}
		}

		/**
		 * Generates a URL safe version of any string
		 *
		 * @param $text
		 *
		 * @return mixed|string
		 */
		static public function slugify( $text ) {
			if ( ! empty( $text ) && is_string( $text ) ) {
				// korean translation
				if ( automotive_listing_get_option( 'korean_translation' ) ) {
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/RomanizeInterface.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Dictionary/Dictionary.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Dictionary/DictionaryEntry.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/UnicodeChar.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Jamo.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/EndConsonant.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Exception.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/IniConsonant.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/InvalidArgumentException.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/JamoList.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Romanizer.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/SpecialRule.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/SpecialRuleContainer.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/SpecialRuleFactory.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Syllabe.php' );
					require_once( LISTING_HOME . 'classes/KoreanRomanizer/Vowel.php' );

					$translate = new KoreanRomanizer\Romanizer( $text );

					$text = $translate->romanize();
				} else {
					$char_map = array(
						// Latin
						'À'    => 'A',
						'Á'    => 'A',
						'Â'    => 'A',
						'Ã'    => 'A',
						'Ä'    => 'A',
						'Å'    => 'A',
						'Æ'    => 'AE',
						'Ç'    => 'C',
						'È'    => 'E',
						'É'    => 'E',
						'Ê'    => 'E',
						'Ë'    => 'E',
						'Ì'    => 'I',
						'Í'    => 'I',
						'Î'    => 'I',
						'Ï'    => 'I',
						'Ð'    => 'D',
						'Ñ'    => 'N',
						'Ò'    => 'O',
						'Ó'    => 'O',
						'Ô'    => 'O',
						'Õ'    => 'O',
						'Ö'    => 'O',
						'Ő'    => 'O',
						'Ø'    => 'O',
						'Ù'    => 'U',
						'Ú'    => 'U',
						'Û'    => 'U',
						'Ü'    => 'U',
						'Ű'    => 'U',
						'Ý'    => 'Y',
						'Þ'    => 'TH',
						'ß'    => 'ss',
						'à'    => 'a',
						'á'    => 'a',
						'â'    => 'a',
						'ã'    => 'a',
						'ä'    => 'a',
						'å'    => 'a',
						'æ'    => 'ae',
						'ç'    => 'c',
						'è'    => 'e',
						'é'    => 'e',
						'ê'    => 'e',
						'ë'    => 'e',
						'ì'    => 'i',
						'í'    => 'i',
						'î'    => 'i',
						'ï'    => 'i',
						'ð'    => 'd',
						'ñ'    => 'n',
						'ò'    => 'o',
						'ó'    => 'o',
						'ô'    => 'o',
						'õ'    => 'o',
						'ö'    => 'o',
						'ő'    => 'o',
						'ø'    => 'o',
						'ù'    => 'u',
						'ú'    => 'u',
						'û'    => 'u',
						'ü'    => 'u',
						'ű'    => 'u',
						'ý'    => 'y',
						'þ'    => 'th',
						'ÿ'    => 'y',

						// Greek
						'Α'    => 'A',
						'Β'    => 'B',
						'Γ'    => 'G',
						'Δ'    => 'D',
						'Ε'    => 'E',
						'Ζ'    => 'Z',
						'Η'    => 'H',
						'Θ'    => '8',
						'Ι'    => 'I',
						'Κ'    => 'K',
						'Λ'    => 'L',
						'Μ'    => 'M',
						'Ν'    => 'N',
						'Ξ'    => '3',
						'Ο'    => 'O',
						'Π'    => 'P',
						'Ρ'    => 'R',
						'Σ'    => 'S',
						'Τ'    => 'T',
						'Υ'    => 'Y',
						'Φ'    => 'F',
						'Χ'    => 'X',
						'Ψ'    => 'PS',
						'Ω'    => 'W',
						'Ά'    => 'A',
						'Έ'    => 'E',
						'Ί'    => 'I',
						'Ό'    => 'O',
						'Ύ'    => 'Y',
						'Ή'    => 'H',
						'Ώ'    => 'W',
						'Ϊ'    => 'I',
						'Ϋ'    => 'Y',
						'α'    => 'a',
						'β'    => 'b',
						'γ'    => 'g',
						'δ'    => 'd',
						'ε'    => 'e',
						'ζ'    => 'z',
						'η'    => 'h',
						'θ'    => '8',
						'ι'    => 'i',
						'κ'    => 'k',
						'λ'    => 'l',
						'μ'    => 'm',
						'ν'    => 'n',
						'ξ'    => '3',
						'ο'    => 'o',
						'π'    => 'p',
						'ρ'    => 'r',
						'σ'    => 's',
						'τ'    => 't',
						'υ'    => 'y',
						'φ'    => 'f',
						'χ'    => 'x',
						'ψ'    => 'ps',
						'ω'    => 'w',
						'ά'    => 'a',
						'έ'    => 'e',
						'ί'    => 'i',
						'ό'    => 'o',
						'ύ'    => 'y',
						'ή'    => 'h',
						'ώ'    => 'w',
						'ς'    => 's',
						'ϊ'    => 'i',
						'ΰ'    => 'y',
						'ϋ'    => 'y',
						'ΐ'    => 'i',

						// Turkish
						'Ş'    => 'S',
						'İ'    => 'I',
						'Ç'    => 'C',
						'Ü'    => 'U',
						'Ö'    => 'O',
						'Ğ'    => 'G',
						'ş'    => 's',
						'ı'    => 'i',
						'ç'    => 'c',
						'ü'    => 'u',
						'ö'    => 'o',
						'ğ'    => 'g',

						// Russian
						'А'    => 'A',
						'Б'    => 'B',
						'В'    => 'V',
						'Г'    => 'G',
						'Д'    => 'D',
						'Е'    => 'E',
						'Ё'    => 'Yo',
						'Ж'    => 'Zh',
						'З'    => 'Z',
						'И'    => 'I',
						'Й'    => 'J',
						'К'    => 'K',
						'Л'    => 'L',
						'М'    => 'M',
						'Н'    => 'N',
						'О'    => 'O',
						'П'    => 'P',
						'Р'    => 'R',
						'С'    => 'S',
						'Т'    => 'T',
						'У'    => 'U',
						'Ф'    => 'F',
						'Х'    => 'H',
						'Ц'    => 'C',
						'Ч'    => 'Ch',
						'Ш'    => 'Sh',
						'Щ'    => 'Sh',
						'Ъ'    => '',
						'Ы'    => 'Y',
						'Ь'    => '',
						'Э'    => 'E',
						'Ю'    => 'Yu',
						'Я'    => 'Ya',
						'а'    => 'a',
						'б'    => 'b',
						'в'    => 'v',
						'г'    => 'g',
						'д'    => 'd',
						'е'    => 'e',
						'ё'    => 'yo',
						'ж'    => 'zh',
						'з'    => 'z',
						'и'    => 'i',
						'й'    => 'j',
						'к'    => 'k',
						'л'    => 'l',
						'м'    => 'm',
						'н'    => 'n',
						'о'    => 'o',
						'п'    => 'p',
						'р'    => 'r',
						'с'    => 's',
						'т'    => 't',
						'у'    => 'u',
						'ф'    => 'f',
						'х'    => 'h',
						'ц'    => 'c',
						'ч'    => 'ch',
						'ш'    => 'sh',
						'щ'    => 'sh',
						'ъ'    => '',
						'ы'    => 'y',
						'ь'    => '',
						'э'    => 'e',
						'ю'    => 'yu',
						'я'    => 'ya',

						// Ukrainian
						'Є'    => 'Ye',
						'І'    => 'I',
						'Ї'    => 'Yi',
						'Ґ'    => 'G',
						'є'    => 'ye',
						'і'    => 'i',
						'ї'    => 'yi',
						'ґ'    => 'g',

						// Czech
						'Č'    => 'C',
						'Ď'    => 'D',
						'Ě'    => 'E',
						'Ň'    => 'N',
						'Ř'    => 'R',
						'Š'    => 'S',
						'Ť'    => 'T',
						'Ů'    => 'U',
						'Ž'    => 'Z',
						'č'    => 'c',
						'ď'    => 'd',
						'ě'    => 'e',
						'ň'    => 'n',
						'ř'    => 'r',
						'š'    => 's',
						'ť'    => 't',
						'ů'    => 'u',
						'ž'    => 'z',

						// Polish
						'Ą'    => 'A',
						'Ć'    => 'C',
						'Ę'    => 'e',
						'Ł'    => 'L',
						'Ń'    => 'N',
						'Ó'    => 'o',
						'Ś'    => 'S',
						'Ź'    => 'Z',
						'Ż'    => 'Z',
						'ą'    => 'a',
						'ć'    => 'c',
						'ę'    => 'e',
						'ł'    => 'l',
						'ń'    => 'n',
						'ó'    => 'o',
						'ś'    => 's',
						'ź'    => 'z',
						'ż'    => 'z',

						// Latvian
						'Ā'    => 'A',
						'Č'    => 'C',
						'Ē'    => 'E',
						'Ģ'    => 'G',
						'Ī'    => 'i',
						'Ķ'    => 'k',
						'Ļ'    => 'L',
						'Ņ'    => 'N',
						'Š'    => 'S',
						'Ū'    => 'u',
						'Ž'    => 'Z',
						'ā'    => 'a',
						'č'    => 'c',
						'ē'    => 'e',
						'ģ'    => 'g',
						'ī'    => 'i',
						'ķ'    => 'k',
						'ļ'    => 'l',
						'ņ'    => 'n',
						'š'    => 's',
						'ū'    => 'u',
						'ž'    => 'z',

						// Vietnamese
						'ớ'    => 'o',
						'ặ'    => 'a',
						'ư'    => 'u',
						'ẹ'    => 'e',
						'ắ'    => 'a',
						'đ'    => 'd',
						'ử'    => 'u',
						'ả'    => 'a',
						'đ'    => 'd',
						'ồ'    => 'o',
						'ổ'    => 'o',

						// Hebrew
						'ב'    => 'b',
						'ג'    => 'g',
						'ד'    => 'd',
						'ה'    => 'h',
						'ו'    => 'v',
						'ז'    => 'z',
						'ח'    => 'h',
						'ט'    => 't',
						'י'    => 'y',
						'כ'    => 'k',
						'כּ'   => 'k',
						'ך'    => 'kh',
						'ל'    => 'l',
						'מ'    => 'm',
						'ם'    => 'm',
						'נ'    => 'n',
						'ן'    => 'n',
						'ס'    => 's',
						'פ'    => 'ph',
						'ף'    => 'p',
						'פּ'   => 'p',
						'צ'    => 'ts',
						'ץ'    => 'ts',
						'ק'    => 'q',
						'ר'    => 'r',
						'ש'    => 'sh',
						'שׂ'   => 'sh',
						'שׁ'   => 'sh',
						'ת'    => 't',
						'תּ'   => 't',
						'א'    => 'x',
						'ה'    => 'n',
						'ח'    => 'n',
						'פ'    => 'g',
						'מ'    => 'a',

						// arabic
						'ـا'   => 'a',
						'ـب'   => 'b',
						'ـبـ'  => 'b',
						'بـ'   => 'b',
						'ب'    => 'b',
						'ـت'   => 't',
						'ـتـ'  => 't',
						'تـ'   => 't',
						'ت'    => 't',
						'ـث'   => 'th',
						'ـثـ'  => 'th',
						'ثـ'   => 'th',
						'ث'    => 'th',
						'ـج'   => 'g',
						'ـجـ'  => 'g',
						'جـ'   => 'g',
						'ج'    => 'g',
						'ـح	' => 'h',
						'ـحـ'  => 'h',
						'حـ'   => 'h',
						'ح'    => 'h',
						'ـخ'   => 'x',
						'ـخـ'  => 'x',
						'	خـ' => 'x',
						'خ'    => 'x',
						'ـد'   => 'd',
						'د'    => 'd',
						'ـذ'   => 'd',
						'ذ'    => 'd',
						'ـر'   => 'r',
						'ر'    => 'r',
						'ـز'   => 'z',
						'ز'    => 'z',
						'ـس'   => 's',
						'ـسـ'  => 's',
						'سـ'   => 's',
						'س'    => 's',
						'ـش'   => 'sh',
						'ـشـ'  => 'sh',
						'شـ'   => 'sh',
						'ش'    => 'sh',
						'ـص'   => 's',
						'ـصـ'  => 's',
						'صـ'   => 's',
						'ص'    => 's',
						'ـض'   => 'd',
						'ـضـ'  => 'd',
						'ضـ'   => 'd',
						'ض'    => 'd',
						'ـط'   => 't',
						'ـطـ'  => 't',
						'طـ'   => 't',
						'ط'    => 't',
						'ـظ'   => 'z',
						'ـظـ'  => 'z',
						'ظـ'   => 'z',
						'ظ'    => 'z',
						'ـع'   => 'a',
						'ـعـ'  => 'a',
						'عـ'   => 'a',
						'ع'    => 'a',
						'	ـغ' => 'g',
						'ـغـ'  => 'g',
						'غـ'   => 'g',
						'غ'    => 'g',
						'ـف'   => 'f',
						'ـفـ'  => 'f',
						'فـ'   => 'f',
						'ف'    => 'f',
						'ـق'   => 'q',
						'ـقـ'  => 'q',
						'قـ'   => 'q',
						'ق'    => 'q',
						'ـك'   => 'k',
						'ـكـ'  => 'k',
						'كـ'   => 'k',
						'ك'    => 'k',
						'ـل'   => 'l',
						'ـلـ'  => 'l',
						'لـ'   => 'l',
						'ل'    => 'l',
						'ـم'   => 'm',
						'ـمـ'  => 'm',
						'مـ'   => 'm',
						'م'    => 'm',
						'ـن'   => 'n',
						'ـنـ'  => 'n',
						'نـ'   => 'n',
						'ن'    => 'n',
						'ـه'   => 'h',
						'ـهـ'  => 'h',
						'هـ'   => 'h',
						'ه'    => 'h',
						'ـو'   => 'w',
						'و'    => 'w',
						'ـي'   => 'y',
						'ـيـ'  => 'y',
						'يـ'   => 'y',
						'ي'    => 'y',

						// Symbols
						'©'    => 'c',
						'®'    => 'r',

						// Korean
						'ㄱ'    => 'g',
						'ㄲ'    => 'kk',
						'ㄴ'    => 'n',
						'ㄷ'    => 'd',
						'ㄸ'    => 'tt',
						'ㄹ'    => 'r',
						'ㅁ'    => 'm',
						'ㅂ'    => 'b',
						'ㅃ'    => 'pp',
						'ㅅ'    => 's',
						'ㅆ'    => 'ss',
						'ㅇ'    => 'ng',
						'ㅈ'    => 'j',
						'ㅉ'    => 'jj',
						'ㅊ'    => 'ch',
						'ㅋ'    => 'k',
						'ㅌ'    => 't',
						'ㅍ'    => 'p',
						'ㅎ'    => 'h'
					);

					$text = str_replace( array_keys( $char_map ), $char_map, $text );
				}

				// replace non letter or digits by -
				$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

				// trim
				$text = trim( $text, '-' );

				// transliterate
				$text = automotive_iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', iso8859_1_to_utf8( $text ) );

				// lowercase
				$text = automotive_strtolower( $text );

				// remove unwanted characters
				$text = preg_replace( '~[^-\w]+~', '', $text );

				if ( empty( $text ) ) {
					return 'n-a';
				}
			}

			return $text;
		}

		/**
		 * Used for changing the option name when the plugin is used with WPML
		 *
		 * @param $option
		 *
		 * @return string
		 */
		public function option_name_suffix( $option ) {
			return $option;
		}

		public function get_automotive_image_sizes( $size = false ) {
			$sizes = apply_filters( 'automotive_image_sizes',
				array(
					'width'   => 167,
					'height'  => 119,
					'slider'  => array(
						'width'  => 762,
						'height' => 456
					),
					'listing' => array(
						'width'  => 200,
						'height' => 150
					)
				)
			);

			if ( $size ) {
				if ( $size == 'auto_thumb' ) {
					return array(
						'width'  => $sizes['width'],
						'height' => $sizes['height'],
					);
				} elseif ( $size == 'auto_slider' ) {
					return array(
						'width'  => $sizes['slider']['width'],
						'height' => $sizes['slider']['height'],
					);
				} elseif ( $size == 'auto_listing' ) {
					return array(
						'width'  => $sizes['listing']['width'],
						'height' => $sizes['listing']['height'],
					);
				}
			}

			return $sizes;
		}

		/**
		 * Add image sizes for plugin
		 */
		public function automotive_image_sizes() {
			$slider_thumbnails = $this->get_automotive_image_sizes();

			add_image_size( "related_portfolio", 270, 140, true );
			add_image_size( "auto_thumb", $slider_thumbnails['width'], $slider_thumbnails['height'], true );
			add_image_size( "auto_slider", $slider_thumbnails['slider']['width'], $slider_thumbnails['slider']['height'], true );
			add_image_size( "auto_listing", $slider_thumbnails['listing']['width'], $slider_thumbnails['listing']['height'], true );
			add_image_size( "auto_portfolio", 770, 450, true );
		}


		/**
		 * Add the custom image sizes into the media picker dropdown
		 *
		 * @param $sizes
		 *
		 * @return array
		 */
		function automotive_image_size_select( $sizes ) {
			return array_merge( $sizes, array(
				'auto_thumb'     => __( 'Automotive Thumb', 'listings' ),
				'auto_slider'    => __( 'Automotive Slider', 'listings' ),
				'auto_listing'   => __( 'Automotive Listing', 'listings' ),
				'auto_portfolio' => __( 'Automotive Portfolio', 'listings' ),
			) );
		}

		/**
		 * Load the Redux Framework
		 */
		public function load_redux() {
			if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'Salient' ) !== false ) {
				return false;
			}

			$listing_features = get_option( "listing_features" );

			if ( isset( $listing_features ) && $listing_features != "disabled" ) {
				include( LISTING_HOME . "ReduxFramework/loader.php" );

				// Redux Admin Panel
				if ( ! class_exists( 'ReduxFramework' ) && file_exists( LISTING_HOME . 'ReduxFramework/ReduxCore/framework.php' ) ) {
					require_once( LISTING_HOME . 'ReduxFramework/ReduxCore/framework.php' );
				}
				if ( file_exists( LISTING_HOME . 'ReduxFramework/options/options.php' ) ) {
					require_once( LISTING_HOME . 'ReduxFramework/options/options.php' );
				}
			}
		}

		/**
		 * Verify ThemeForest credentials
		 *
		 * @return bool
		 */
		public function validate_themeforest_creds() {
			$option = get_option( "envato-market-ts" );
			$valid  = ( isset( $option['theme']['id'] ) ? true : false );

			return $valid;
		}

		/**
		 * Converts listing data to new, more usable system
		 */
		public function convert_listing_data() {
			$slug_generated = get_option( "auto_slugs_generated" );

			if ( ! $slug_generated ) {

				$listing_categories = $this->get_listing_categories( true );

				if ( ! empty( $listing_categories ) ) {
					$new_listing_categories = array();

					foreach ( $listing_categories as $key => $category ) {
						// not options
						if ( $key != "options" ) {
							$slug = $this->slugify( $category['singular'] );

							$new_listing_categories[ $slug ]         = $listing_categories[ $key ];
							$new_listing_categories[ $slug ]['slug'] = $slug;
						} else {
							$new_listing_categories['options'] = $category;
							$slug                              = "options";
						}

						// update terms with new slugs in the key
						if ( ! empty( $category['terms'] ) ) {
							foreach ( $category['terms'] as $term_key => $term_value ) {
								$new_listing_categories[ $slug ]['terms'][ $this->slugify( $term_value ) ] = $term_value;
								unset( $new_listing_categories[ $slug ]['terms'][ $term_key ] );
							}
						}
					}

					// now convert listing terms
					$all_listings = get_posts( array( "post_type" => "listings", "posts_per_page" => - 1 ) );

					if ( ! empty( $all_listings ) ) {
						// D($all_listings);
						foreach ( $all_listings as $key => $listing ) {
							// foreach listing categories
							foreach ( $new_listing_categories as $category_key => $category_value ) {
								$category_post_key  = strtolower( str_replace( array(
									" ",
									"."
								), "_", $category_value['singular'] ) );
								$category_post_meta = get_post_meta( $listing->ID, $category_post_key, true );

								// now update with new meta
								update_post_meta( $listing->ID, $category_value['slug'], $category_post_meta );
							}
						}
					}

					// now convert the orderby
					$listing_orderby = get_option( "listing_orderby" );

					if ( ! empty( $listing_orderby ) ) {
						$new_orderby = array();

						foreach ( $listing_orderby as $order_category => $order_type ) {
							$new_orderby[ $this->slugify( $order_category ) ] = $order_type;
						}

						update_option( "listing_orderby", $new_orderby );
					}

					// store a backup of listing categories, just in case
					update_option( "auto_backup_listing_categories", $listing_categories );
					update_option( "listing_categories", $new_listing_categories );
				}

				update_option( "auto_slugs_generated", true );
			}
		}

		/**
		 * Used to generated the dependancy option for existing listings since automatic
		 * dependancies were only introduced in version 6.0
		 *
		 */
		public function generate_dependancy_option( $force_regenerate = false ) {
			$dependancies_generated = get_option( $this->option_name_suffix( "dependancies_generated" ) );

			if ( ! $dependancies_generated || $force_regenerate ) {
				$all_listings = get_posts( array(
					"post_type"        => "listings",
					"posts_per_page"   => - 1,
					"suppress_filters" => 0
				) );

				$dependancies      = array();
				$sold_dependancies = array();

				// additional categories
				$additional_categories = $this->get_additional_categories();

				// if they are showing all sold listings on the site then have those categories in the main ones
				$show_sold_listings = automotive_listing_get_option( 'inventory_no_sold', true );

				if ( ! empty( $all_listings ) ) {
					foreach ( $all_listings as $key => $listing ) {
						$listing_categories = $this->get_listing_categories( false );

						// don't include sold terms
						$car_sold = get_post_meta( $listing->ID, "car_sold", true );

						if ( ! empty( $listing_categories ) ) {
							foreach ( $listing_categories as $category_key => $category ) {
								if ( isset( $category['slug'] ) && ! empty( $category['slug'] ) ) {
									$post_meta = get_post_meta( $listing->ID, $category['slug'], true );

									if ( $car_sold == 2 ) {
										$dependancies[ $listing->ID ][ $category['slug'] ] = array( $this->slugify( $post_meta ) => $post_meta );
									} else {
										$sold_dependancies[ $listing->ID ][ $category['slug'] ] = array( $this->slugify( $post_meta ) => $post_meta );

										// you know the drill
										if ( $show_sold_listings ) {
											$dependancies[ $listing->ID ][ $category['slug'] ] = array( $this->slugify( $post_meta ) => $post_meta );
										}
									}
								}
							}
						}

						if ( ! empty( $additional_categories ) ) {
							foreach ( $additional_categories as $additional_category ) {
								$check_handle = str_replace( " ", "_", automotive_strtolower( $additional_category ) );
								$post_meta    = get_post_meta( $listing->ID, $check_handle, true );

								if ( ! empty( $check_handle ) && ! empty( $post_meta ) ) {
									if ( $car_sold == 2 ) {
										$dependancies[ $listing->ID ][ $check_handle ] = array( 1 => 1 );
									} else {
										$sold_dependancies[ $listing->ID ][ $check_handle ] = array( 1 => 1 );

										if ( $show_sold_listings ) {
											$dependancies[ $listing->ID ][ $check_handle ] = array( 1 => 1 );
										}
									}
								}
							}
						}

					}
				}

				update_option( $this->option_name_suffix( "dependancies_generated" ), $dependancies );
				update_option( $this->option_name_suffix( "sold_dependancies_generated" ), $sold_dependancies );
			}
		}

		/**
		 * Used to update the dependancy option in the database after a user updates a listing
		 *
		 * @param $listing_id
		 * @param $listing_categories
		 *
		 */
		public function update_dependancy_option( $listing_id, $listing_categories, $car_sold = false ) {
			if ( $car_sold == false ) {
				$car_sold = get_post_meta( $listing_id, "car_sold", true );
			}

			$dependancy_option      = ( isset( $car_sold ) && $car_sold == 1 ? "sold_" : "" ) . "dependancies_generated";
			$dependancies_generated = get_option( $this->option_name_suffix( $dependancy_option ) );

			if ( is_string( $listing_categories ) && $listing_categories == "delete" ) {
				unset( $dependancies_generated[ $listing_id ] );
			} else {
				if ( $dependancies_generated ) {
					$dependancies_generated[ $listing_id ] = $listing_categories;
				}
			}

			update_option( $this->option_name_suffix( $dependancy_option ), $dependancies_generated );


			// if car sold then remove it from other array and vice versa
			$other_dependancies_name = ( isset( $car_sold ) && $car_sold == 1 ? "" : "sold_" ) . "dependancies_generated";
			$other_dependancies      = get_option( $this->option_name_suffix( $other_dependancies_name ) );

			if ( isset( $other_dependancies[ $listing_id ] ) ) {
				unset( $other_dependancies[ $listing_id ] );
			}

			update_option( $this->option_name_suffix( $other_dependancies_name ), $other_dependancies );
		}


		/**
		 * Used for updating the listing category dropdowns with terms that are used
		 *
		 * @param array $current_categories
		 *
		 * @return array
		 */
		public function process_dependancies( $current_categories = array(), $is_sold = false, $min_max = array(), $still_count_terms = false ) {

			if ( apply_filters( 'automotive_force_plain_processing', false ) ) {
				return $this->process_dependancies_plain( $current_categories, $is_sold, $min_max );
			}

			$dependancy_option         = ( $is_sold ? "sold_dependancies_generated" : "dependancies_generated" );
			$dependancies_generated    = get_option( $this->option_name_suffix( $dependancy_option ) );
			$return                    = array();
			$number_categories         = $this->get_amount_listing_categories();
			$count_terms               = array();
			$listing_category_settings = $this->get_filterable_listing_categories();

			if ( ! empty( $current_categories ) ) {
				// year workaround
				if ( isset( $current_categories['yr'] ) && ! empty( $current_categories['yr'] ) ) {
					$current_categories['year'] = $current_categories['yr'];
					unset( $current_categories['yr'] );
				}

				// remove unnecessary vars
				// Only sort through listing category vars
				$valid_current_categories = array();
				foreach ( $this->get_listing_categories() as $category_key => $category_value ) {
					// min and max empty val check
					if ( isset( $current_categories[ $category_value['slug'] ] ) && is_array( $current_categories[ $category_value['slug'] ] ) &&
					     ! empty( $current_categories[ $category_value['slug'] ][0] ) && ! empty( $current_categories[ $category_value['slug'] ][1] )
					) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					} elseif ( isset( $current_categories[ $category_value['slug'] ] ) && ! is_array( $current_categories[ $category_value['slug'] ] ) && ! empty( $current_categories[ $category_value['slug'] ] ) ) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					}
				}

				// additional categories
				$additional_categories = $this->get_additional_categories();

				if ( ! empty( $additional_categories ) ) {
					foreach ( $additional_categories as $additional_category ) {
						$check_handle = str_replace( " ", "_", automotive_strtolower( $additional_category ) );

						if ( isset( $current_categories[ $check_handle ] ) && $current_categories[ $check_handle ] ) {
							$valid_current_categories[ $check_handle ] = 1;
						}
					}
				}

				// sold vehicles only?
				if ( isset( $current_categories['sold_only'] ) && $current_categories['sold_only'] ) {
					$valid_current_categories['car_sold'] = 1;
				}

				$current_categories = $valid_current_categories;
			}

			$matched_listings   = array();
			$no_match_listings  = array();
			$formatted_listings = array();

			$matching_data = array();

			// loop through all listings and store result if matches filters
			////////////////////////
			if ( ! empty( $dependancies_generated ) ) {
				foreach ( $dependancies_generated as $listing_id => $categories ) {
					$formatted_listings[ $listing_id ] = array();

					foreach ( $categories as $category_name => $terms ) {
						if ( ! isset( $all_values[ $category_name ] ) ) {
							$all_values[ $category_name ] = array();
						}

						if ( $category_name == "features" && ! isset( $formatted_listings[ $listing_id ][ $category_name ] ) ) {
							$formatted_listings[ $listing_id ][ $category_name ] = array();
						}

						foreach ( $terms as $term_key => $term_label ) {
							$all_values[ $category_name ][ $term_key ] = $term_label;

							if ( $category_name == "features" ) {
								$formatted_listings[ $listing_id ][ $category_name ][ $term_key ] = $term_key;
							} else {
								$formatted_listings[ $listing_id ][ $category_name ] = $term_key;
							}
						}
					}
				}
			}

			$temp_current_categories = $this->current_categories;

			// D($formatted_listings);
			// D($temp_current_categories);
			// D($all_values);

			if ( isset( $temp_current_categories['yr'] ) ) {
				$temp_current_categories['year'] = $temp_current_categories['yr'];

				unset( $temp_current_categories['yr'] );
			}

			if ( isset( $temp_current_categories['features'] ) ) {
				foreach ( $temp_current_categories['features'] as $feature_slug => $feature_value ) {
					$temp_current_categories['features'][ $feature_slug ] = $feature_slug;
				}
			}

			$temp_current_categories2 = $temp_current_categories;

			foreach ( $all_values as $all_category_name => $all_terms ) {
				$temp_current_category = $all_category_name;

				foreach ( $all_terms as $all_term_slug => $all_term_label ) {
					$temp_current_slug = $all_term_slug;

					if ( ! isset( $count_terms[ $temp_current_category ][ $temp_current_slug ] ) ) {
						$count_terms[ $temp_current_category ][ $temp_current_slug ] = 0;
					}

					$temp_current_categories = $temp_current_categories2;

					if ( ! isset( $temp_current_categories[ $temp_current_category ] ) ) {
						$temp_current_categories[ $temp_current_category ] = $temp_current_slug;
					} else {
						$temp_current_categories[ $temp_current_category ] = array();

						$temp_current_categories[ $temp_current_category ][ $temp_current_slug ] = $temp_current_slug;
					}

					// echo "<h2>testing filters</h2>";
					// D($temp_current_categories);

					foreach ( $formatted_listings as $listing_id => $listing_values ) {
						$listing_matches_conditions = false;

						if ( ! isset( $matching_data[ $listing_id ] ) ) {
							$matching_data[ $listing_id ] = array();
						}


						if ( ! empty( $temp_current_categories ) ) {
							foreach ( $temp_current_categories as $filter_category => $filter_values ) {
								if ( empty( $filter_values ) ) {
									// echo "<h1>skipping: " . $filter_category . "</h1>";
									continue;
								}

								if ( ! is_array( $filter_values ) ) {
									$filter_values = array( $filter_values );
								}

								$matches_one_value = false;

								if ( ! isset( $matching_data[ $listing_id ][ $filter_category ] ) ) {
									$matching_data[ $listing_id ][ $filter_category ] = array();
								}


								foreach ( $filter_values as $filter_value ) {
									if ( ! isset( $formatted_listings[ $listing_id ][ $filter_category ] ) ) {
										continue;
									}

									$is_array = is_array( $formatted_listings[ $listing_id ][ $filter_category ] );

									if ( $is_array && isset( $formatted_listings[ $listing_id ][ $filter_category ][ $filter_value ] ) ) {
										$matches_one_value = true;

										$matching_data[ $listing_id ][ $filter_category ][ $filter_value ] = true;

										break;
									} elseif ( ! $is_array && $filter_value == $formatted_listings[ $listing_id ][ $filter_category ] ) {
										$matches_one_value = true;

										$matching_data[ $listing_id ][ $filter_category ][ $filter_value ] = true;
										break;
									} else {
										$matches_one_value = false;

										$matching_data[ $listing_id ][ $filter_category ][ $filter_value ] = false;
									}
								}

								if ( $matches_one_value ) {
									$listing_matches_conditions = true;

									$matching_data[ $listing_id ][ $filter_category ][ $filter_value ] = true;
								} else {
									$listing_matches_conditions = false;

									$matching_data[ $listing_id ][ $filter_category ][ $filter_value ] = false;
									break;
								}

							}
						} else {
							$listing_matches_conditions = true;
						}

						if ( $listing_matches_conditions ) {
							$matched_listings[ $listing_id ] = $listing_id;

							// if(isset($listing_category_settings[$temp_current_category]['show_amount'])){
							$count_terms[ $temp_current_category ][ $temp_current_slug ] ++;
							// }
						} else {
							$no_match_listings[ $listing_id ] = $listing_id;
						}
					}

					$temp_current_category_key = ( $temp_current_category == "year" ? "yr" : $temp_current_category );
				}
			}

			// go through all filters and for each one check against non-matched listings to see if it now matches, if so then +1 to count terms
			////////////////////////

			if ( automotive_listing_get_option( 'filter_show_current_dropdown_terms', false ) == false ) {

				// D($count_terms);

				foreach ( $count_terms as $count_category => $count_values ) {

					if ( ! isset( $listing_category_settings[ $count_category ]['show_amount'] ) && $count_category !== 'features' ) {
						unset( $count_terms[ $count_category ] );
						continue;
					}

					if ( ! empty( $count_values ) ) {
						foreach ( $count_values as $count_value => $count_amount ) {
							// echo "unsetting: " . $count_category . ": " . $count_value . " " . $count_amount . "<br>";
							if ( (int) $count_amount == 0 && isset( $all_values[ $count_category ][ $count_value ] ) ) {
								unset( $all_values[ $count_category ][ $count_value ] );
							}
						}
					}
				}

			}


			// sort terms
			if ( ! empty( $all_values ) ) {
				foreach ( $all_values as $category_key => $category_value ) {
					// if compare value is present, use the terms specified by user
					if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && isset( $listing_category_settings[ $category_key ]['terms'] ) ) {
						$category_value = $listing_category_settings[ $category_key ]['terms'];

						if ( ! empty( $category_value ) ) {
							foreach ( $category_value as $term_key => $term_value ) {
								// and re-format
								if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
									$category_value[ $term_key ] = $this->format_currency( $term_value );
								}

								if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && ! in_array( $category_key, $min_max ) ) {
									$category_value[ $term_key ] = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $category_value[ $term_key ];
								}
							}
						}
					}


					if ( isset( $listing_category_settings[ $category_key ]['sort_terms'] ) && $listing_category_settings[ $category_key ]['sort_terms'] == "desc" && is_array( $category_value ) ) {
						natsort( $category_value );

						if ( isset( $listing_category_settings[ $category_key ]['link_value'] ) && $listing_category_settings[ $category_key ]['link_value'] == "price" ) {
							uksort( $category_value, 'strnatcasecmp' );
						}

						$category_value = array_reverse( $category_value, true );
					} elseif ( is_array( $category_value ) ) {
						natsort( $category_value );

						if ( isset( $listing_category_settings[ $category_key ]['link_value'] ) && $listing_category_settings[ $category_key ]['link_value'] == "price" ) {
							uksort( $category_value, 'strnatcasecmp' );
						}
					}

					$all_values[ $category_key ] = $category_value;

					// tell js this is to be desc
					if ( isset( $listing_category_settings[ $category_key ]['sort_terms'] ) && $listing_category_settings[ $category_key ]['sort_terms'] == "desc" ) {
						$all_values[ $category_key ]['auto_term_order'] = "desc";
					}
				}
			}


			return array( $all_values, $count_terms );
		}

		public function process_dependancies_plain( $current_categories = array(), $is_sold = false, $min_max = array() ) {
			$dependancy_option      = ( $is_sold ? "sold_dependancies_generated" : "dependancies_generated" );
			$dependancies_generated = get_option( $this->option_name_suffix( $dependancy_option ) );
			$return                 = array();
			$number_categories      = $this->get_amount_listing_categories();
			$count_terms            = array();

//			D( $number_categories );
//			D( $current_categories );

			if ( ! empty( $current_categories ) ) {
				// year workaround
				if ( isset( $current_categories['yr'] ) && ! empty( $current_categories['yr'] ) ) {
					$current_categories['year'] = $current_categories['yr'];
					unset( $current_categories['yr'] );
				}

				// remove unnecessary vars
				// Only sort through listing category vars
				$valid_current_categories = array();
				foreach ( $this->get_listing_categories() as $category_key => $category_value ) {
					// min and max empty val check
					if ( isset( $current_categories[ $category_value['slug'] ] ) && is_array( $current_categories[ $category_value['slug'] ] ) &&
					     ! empty( $current_categories[ $category_value['slug'] ][0] ) && ! empty( $current_categories[ $category_value['slug'] ][1] )
					) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					} elseif ( isset( $current_categories[ $category_value['slug'] ] ) && is_string( $current_categories[ $category_value['slug'] ] ) && strpos( $current_categories[ $category_value['slug'] ], "," ) !== false ) {
						$valid_current_categories[ $category_value['slug'] ] = explode( ",", $current_categories[ $category_value['slug'] ] );
					} elseif ( isset( $current_categories[ $category_value['slug'] ] ) && ! is_array( $current_categories[ $category_value['slug'] ] ) && ! empty( $current_categories[ $category_value['slug'] ] ) ) {
						$valid_current_categories[ $category_value['slug'] ] = $current_categories[ $category_value['slug'] ];
					}
				}

				// additional categories
				$additional_categories = $this->get_additional_categories();

				if ( ! empty( $additional_categories ) ) {
					foreach ( $additional_categories as $additional_category ) {
						$check_handle = str_replace( " ", "_", automotive_strtolower( $additional_category ) );

						if ( isset( $current_categories[ $check_handle ] ) && $current_categories[ $check_handle ] ) {
							$valid_current_categories[ $check_handle ] = 1;
						}
					}
				}

				// sold vehicles only?
				if ( isset( $current_categories['sold_only'] ) && $current_categories['sold_only'] ) {
					$valid_current_categories['car_sold'] = 1;
				}

				$current_categories = $valid_current_categories;
			}

			if ( ! empty( $dependancies_generated ) ) {
				$listing_category_settings = $this->get_filterable_listing_categories();

				foreach ( $dependancies_generated as $listing_id => $categories ) {

					if ( ! empty( $current_categories ) ) {
						$has_required_values = false;

						foreach ( $current_categories as $current_key => $current_value ) {

							if (
								isset( $listing_category_settings[ $current_key ]['compare_value'] ) &&
								$listing_category_settings[ $current_key ]['compare_value'] != "=" &&
								(int) $listing_category_settings[ $current_key ]['range'] !== 1
							) {
								$has_required_values = true;
								break;
							}

							if ( ! empty( $categories ) && is_array( $categories ) && isset( $categories[ $current_key ] ) ) {

								// make sure min/max value is in between
								if ( is_array( $current_value ) ) {
									reset( $categories[ $current_key ] );
									$key = key( $categories[ $current_key ] );

									if ( isset( $listing_category_settings[ $current_key ]['is_number'] ) && $listing_category_settings[ $current_key ]['is_number'] ) {
										$min = ( isset( $current_value[0] ) && ! empty( $current_value[0] ) ? $current_value[0] : "" );
										$max = ( isset( $current_value[1] ) && ! empty( $current_value[1] ) ? $current_value[1] : "" );

										// keep existing min/max values available in dropdown
										$return[ $current_key ][ $min ] = $min;
										$return[ $current_key ][ $max ] = $max;

										if ( ! empty( $min ) && ! empty( $max ) && ( ( $min <= $key ) && ( $key <= $max ) ) ) {
											$has_required_values = true;
										} else {
											$has_required_values = false;
											break;
										}
									} else {
										$option_1 = ( isset( $current_value[0] ) && ! empty( $current_value[0] ) ? $current_value[0] : "" );
										$option_2 = ( isset( $current_value[1] ) && ! empty( $current_value[1] ) ? $current_value[1] : "" );

										if ( $key === $option_1 || $key === $option_2 ) {
											$has_required_values = true;
										}
									}
								} elseif ( is_array( $categories[ $current_key ] ) ) {
									reset( $categories[ $current_key ] );
									$key = key( $categories[ $current_key ] );

									if ( $key != $current_value ) {
										$has_required_values = false;
										break;
									} else {
										$has_required_values = true;
									}
								}
							}

							// if car sold
							if ( $has_required_values && $current_key == "car_sold" && $current_value == 1 ) {
								$car_sold = get_post_meta( $listing_id, "car_sold", true );

								if ( $car_sold == 1 ) {
									$has_required_values = true;
								} else {
									$has_required_values = false;
								}
							}
						}

//						D( [
//							'has_required',
//							get_the_title( $listing_id ),
//							$has_required_values,
//						] );
//						echo "<br><br><br>";

						// current listing has all required dependancies
						if ( $has_required_values ) {
							foreach ( $categories as $category_key => $category_value ) {

								// if not array, declare
								if ( ! isset( $return[ $category_key ] ) || ! is_array( $return[ $category_key ] ) ) {
									$return[ $category_key ] = ( isset( $return[ $category_key ] ) ? $return[ $category_key ] : array() );

									if ( isset( $count_terms[ $category_key ] ) ) {
										$count_terms[ $category_key ] = ( isset( $count_terms[ $category_key ] ) ? $count_terms[ $category_key ] : array() );
									}
								}

								// make sure no empty values make it into available terms
								reset( $category_value );
								$key = key( $category_value );

								$select_label = $this->display_listing_category_term( $category_value[ $key ], $category_key );

								// apply currency or compare values to value
								if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
									$select_label = $this->format_currency( $select_label );
								}

								if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && ! in_array( $category_key, $min_max ) ) {
									$select_label = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $select_label;
								}

								if ( isset( $category_value[ $key ] ) && ! empty( $category_value[ $key ] ) && $category_value[ $key ] != "None" /*&& ! in_array( $category_value[ $key ], $return[ $category_key ] )*/ ) {

									if ( isset( $number_categories[ $category_key ] ) ) {

										if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] !== '=' ) {
											$terms         = ( isset( $listing_category_settings[ $category_key ]['terms'] ) && ! empty( $listing_category_settings[ $category_key ]['terms'] ) ? $listing_category_settings[ $category_key ]['terms'] : array() );
											$compare_value = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] );

											if ( ! empty( $terms ) ) {
												foreach ( $terms as $term_key => $term ) {
													// if the counter hasn't been set
													if ( ! isset( $count_terms[ $category_key ][ $term_key ] ) ) {
														$count_terms[ $category_key ][ $term_key ] = 0;
													}

													if (
														( $compare_value == "<" && $key < $term_key ) ||
														( $compare_value == "<=" && $key <= $term_key ) ||
														( $compare_value == ">" && $key > $term_key ) ||
														( $compare_value == ">=" && $key >= $term_key )
													) {
														$count_terms[ $category_key ][ $term_key ] ++;
													}
												}
											}
										} else {
											// if the counter hasn't been set
											if ( ! isset( $count_terms[ $category_key ][ $key ] ) ) {
												$count_terms[ $category_key ][ $key ] = 0;
											}

											$count_terms[ $category_key ][ $key ] ++;
										}
									}

									$return[ $category_key ][ $key ] = $select_label;
								}

							}
						}

					} else {
						// this is ran if no categories are set in the request
						foreach ( $categories as $category_key => $category_value ) {

							// if not array, declare
							if ( ! isset( $return[ $category_key ] ) || ! is_array( $return[ $category_key ] ) ) {
								$return[ $category_key ] = array();
							}

							// make sure no empty values make it into available terms
							reset( $category_value );
							$key = key( $category_value );

							$select_label = $this->display_listing_category_term( $category_value[ $key ], $category_key );

							// apply currency or compare values to value
							if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
								$select_label = $this->format_currency( $select_label );
							}

							if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && ! in_array( $category_key, $min_max ) ) {
								$select_label = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $select_label;
							}

							$category_value[ $key ] = addslashes( $category_value[ $key ] );

							if ( $this->is_category_no_term_special_case( $category_key ) ) {
								if ( ! isset( $listing_category_settings[ $category_key ]['terms'] ) || ! is_array( $listing_category_settings[ $category_key ]['terms'] ) ) {
									$listing_category_settings[ $category_key ]['terms'] = array();
								}

								$listing_category_settings[ $category_key ]['terms'][ $key ] = $category_value[ $key ];
							}

							if ( $category_key == "features" && ! empty( $category_value ) ) {
								// D($category_value);
								// var_dump($features_options);
								if ( ! isset( $count_terms['features'] ) ) {
									$count_terms['features'] = array();
								}

								foreach ( $category_value as $single_category_value => $single_category_label ) {
									if ( ! isset( $count_terms['features'][ $single_category_value ] ) ) {
										$count_terms['features'][ $single_category_value ] = 0;
									}

									$count_terms['features'][ $single_category_value ] ++;

								}
							}

							// checks and make sure value exists in backend
							if (
								isset( $category_value[ $key ] ) && ! empty( $category_value[ $key ] ) && $category_value[ $key ] != "None" &&
								isset( $listing_category_settings[ $category_key ]['terms'] ) && is_array( $listing_category_settings[ $category_key ]['terms'] )
							) {
								// var_dump($category_key);
								// var_dump(isset($number_categories[$category_key]));
								if ( isset( $number_categories[ $category_key ] ) ) {

									if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] !== '=' ) {
										$terms         = ( isset( $listing_category_settings[ $category_key ]['terms'] ) && ! empty( $listing_category_settings[ $category_key ]['terms'] ) ? $listing_category_settings[ $category_key ]['terms'] : array() );
										$compare_value = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] );

										if ( ! empty( $terms ) ) {
											foreach ( $terms as $term_key => $term ) {
												// if the counter hasn't been set
												if ( ! isset( $count_terms[ $category_key ][ $term_key ] ) ) {
													$count_terms[ $category_key ][ $term_key ] = 0;
												}

												if (
													( $compare_value == "<" && $key < $term_key ) ||
													( $compare_value == "<=" && $key <= $term_key ) ||
													( $compare_value == ">" && $key > $term_key ) ||
													( $compare_value == ">=" && $key >= $term_key )
												) {
													$count_terms[ $category_key ][ $term_key ] ++;
												}
											}
										}
									} else {
										// if the counter hasn't been set
										if ( ! isset( $count_terms[ $category_key ][ $key ] ) ) {
											$count_terms[ $category_key ][ $key ] = 0;
										}

										$count_terms[ $category_key ][ $key ] ++;
									}
								}

								$return[ $category_key ][ $key ] = $select_label;
							}
						}
					}
				}
			}

			// set any unset number counts to 0
			foreach ( $number_categories as $category_slug => $category ) {
				if ( ! isset( $count_terms[ $category_slug ] ) && ! empty( $category['terms'] ) ) {
					foreach ( $category['terms'] as $term_key => $term ) {
						$count_terms[ $category_slug ][ $term_key ] = 0;
					}
				}
			}

			// sort terms
			if ( ! empty( $return ) ) {
				foreach ( $return as $category_key => $category_value ) {
					// if compare value is present, use the terms specified by user
					if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && isset( $listing_category_settings[ $category_key ]['terms'] ) ) {
						$category_value = $listing_category_settings[ $category_key ]['terms'];

						if ( ! empty( $category_value ) ) {
							foreach ( $category_value as $term_key => $term_value ) {
								// and re-format
								if ( isset( $listing_category_settings[ $category_key ]['currency'] ) && $listing_category_settings[ $category_key ]['currency'] == 1 ) {
									$category_value[ $term_key ] = $this->format_currency( $term_value );
								}

								if ( isset( $listing_category_settings[ $category_key ]['compare_value'] ) && $listing_category_settings[ $category_key ]['compare_value'] != "=" && ! in_array( $category_key, $min_max ) ) {
									$category_value[ $term_key ] = html_entity_decode( $listing_category_settings[ $category_key ]['compare_value'] ) . " " . $category_value[ $term_key ];
								}
							}
						}
					}


					if ( isset( $listing_category_settings[ $category_key ]['sort_terms'] ) && $listing_category_settings[ $category_key ]['sort_terms'] == "desc" && is_array( $category_value ) ) {
						natsort( $category_value );

						if ( isset( $listing_category_settings[ $category_key ]['link_value'] ) && $listing_category_settings[ $category_key ]['link_value'] == "price" ) {
							uksort( $category_value, 'strnatcasecmp' );
						}

						$category_value = array_reverse( $category_value, true );
					} elseif ( is_array( $category_value ) ) {
						natsort( $category_value );

						if ( isset( $listing_category_settings[ $category_key ]['link_value'] ) && $listing_category_settings[ $category_key ]['link_value'] == "price" ) {
							uksort( $category_value, 'strnatcasecmp' );
						}
					}

					$return[ $category_key ] = $category_value;

					// tell js this is to be desc
					if ( isset( $listing_category_settings[ $category_key ]['sort_terms'] ) && $listing_category_settings[ $category_key ]['sort_terms'] == "desc" ) {
						$return[ $category_key ]['auto_term_order'] = "desc";
					}
				}
			}

			// var_dump(array($return, $count_terms));die;

			return array( $return, $count_terms );
		}

		/**
		 * Used to generate the listing dropdowns for the search shortcode, inventory dropdowns and widget dropdowns
		 *
		 * @param $category
		 * @param $prefix_text
		 * @param $select_class
		 * @param $options
		 * @param $options
		 * @param array $other_options
		 */
		public function listing_dropdown( $category, $prefix_text, $select_class, $options, $other_options = array() ) {
			$get_select = ( $category['slug'] == "year" ? "yr" : $category['slug'] );

			// variables altered by the $other_options
			$default_other_options = array(
				'current_option' => '',
				'select_name'    => $get_select,
				'select_label'   => $prefix_text . " " . $this->display_listing_category( $category['plural'] ),
				'show_amount'    => array()
			);

			$default_other_options['is_multiselect'] = automotive_listing_get_option( 'filter_showall', false ) && automotive_listing_get_option( 'filter_multiselect', false );


			// extract the options to be used in the dropdown rendering
			extract( array_merge( $default_other_options, $other_options ) );

			$no_options = __( "No options", "listings" );

			$select_attrs = array(
				'name'                => ( $select_name == "year" ? "yr" : $select_name ),
				'class'               => $select_class,
				'data-sort'           => $category['slug'],
				'data-prefix'         => $prefix_text,
				'data-label-singular' => $this->display_listing_category( $category['singular'] ),
				'data-label-plural'   => $this->display_listing_category( $category['plural'] ),
				'data-no-options'     => $no_options
			);

			if ( $category['compare_value'] != "" ) {
				$select_attrs['data-compare-value'] = htmlspecialchars( $category['compare_value'], ENT_QUOTES, "UTF-8" );
			}

			if ( $is_multiselect ) {
				$select_attrs['multiple'] = 'true';
			}

			if ( $is_multiselect && is_string( $current_option ) ) {
				$select_attrs['multiple'] = 'true';
				// $select_attrs['name']    .= '[]';

				$current_option = explode( ",", $current_option );

				if ( ! empty( $current_option ) ) {
					$current_option = array_filter( $current_option );
				}
			}

			echo "<select " . automotive_generate_attrs( $select_attrs ) . ">\n";
			echo "<option value=''>" . $select_label . "</option>\n";

			if ( ! empty( $options ) ) {

				foreach ( $options as $term_key => $term_value ) {
					$on_select = $this->display_listing_category_term( $term_value, $category['slug'] );

					if ( $term_key != "auto_term_order" ) {
						$term_value_safe = htmlentities( $term_value, ENT_QUOTES, 'UTF-8' );

						$is_selected = (
							( is_array( $current_option ) && in_array( $term_key, $current_option ) ) ||
							( ! is_array( $current_option ) && $current_option == $term_key )
						);

						echo "\t<option value='" . $term_value_safe . "'" . ( $is_selected ? "selected='selected'" : "" ) . " data-key='" . $term_key . "'>";
						echo htmlentities( $on_select, false, 'UTF-8' );

						if ( ! empty( $show_amount ) ) {
							echo " (" . ( isset( $show_amount[ $term_key ] ) && ! empty( $show_amount[ $term_key ] ) ? $show_amount[ $term_key ] : 0 ) . ")";
						}

						echo "</option>\n";
					}
				}
			} else {
				echo "<option value=''>" . $no_options . "</option>\n";
			}

			echo "</select>\n\n";
		}

		public function get_attachment_id_from_url( $attachment_url = '' ) {
			global $wpdb;

			$attachment_id = false;

			// If there is no url, return.
			if ( '' == $attachment_url ) {
				return;
			}

			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

				// Remove the upload path base directory from the attachment URL
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

			}

			return $attachment_id;
		}

		public function get_video_id( $url ) {
			// determine if youtube or vimeo
			$parsed_url = parse_url( $url );

			$youtube_urls = array( "www.youtube.com", "youtube.com", "www.youtu.be", "youtu.be" );
			$vimeo_urls   = array( "www.vimeo.com", "vimeo.com" );
			$rumble_urls  = array( "www.rumble.com", "rumble.com" );

			if ( isset( $parsed_url['host'] ) && in_array( $parsed_url['host'], $youtube_urls ) ) {
//				preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches );
				preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $url, $matches );

				$return = ( isset( $matches[1] ) && ! empty( $matches[1] ) ? array( "youtube", $matches[1] ) : false );
			} elseif ( isset( $parsed_url['host'] ) && in_array( $parsed_url['host'], $vimeo_urls ) ) {
				preg_match( "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $matches );

				$return = ( isset( $matches[5] ) && ! empty( $matches[5] ) ? array( "vimeo", $matches[5] ) : false );
			} elseif ( isset( $parsed_url['host'] ) && in_array( $parsed_url['host'], $rumble_urls ) ) {
				preg_match( '/https:\/\/rumble.com\/embed\/(.*)\//', $url, $matches );

				$return = ( isset( $matches[1] ) && ! empty( $matches[1] ) ? array( "rumble", $matches[1] ) : false );
			} elseif ( ( $is_attachment = $this->get_attachment_id_from_url( $url ) ) != false ) {
				$return = array( 'self_hosted', $url );
			} else {
				$return = false;
			}

			return $return;
		}

		/**
		 * Generates the YouTube URL based on user options.
		 *
		 * @param $youtube_url
		 *
		 * @return mixed|string
		 */
		public function apply_youtube_options( $youtube_url ) {
			$youtube_options = automotive_listing_get_option( 'youtube_video_options', array() );

			if ( isset( $youtube_options['rel'] ) && $youtube_options['rel'] == 0 ) {
				$youtube_url = add_query_arg( "rel", "0", $youtube_url );
			}
			if ( isset( $youtube_options['player_controls'] ) && $youtube_options['player_controls'] == 0 ) {
				$youtube_url = add_query_arg( "showinfo", "0", $youtube_url );
			}
			if ( isset( $youtube_options['title_actions'] ) && $youtube_options['title_actions'] == 0 ) {
				$youtube_url = add_query_arg( "controls", "0", $youtube_url );
			}
			if ( isset( $youtube_options['privacy'] ) && $youtube_options['privacy'] == 1 ) {
				$youtube_url = str_replace( "youtube.com", "youtube-nocookie.com", $youtube_url );
			}

			return $youtube_url;
		}

		/**
		 * Formats the price based on the user options
		 *
		 * @param $amount
		 *
		 * @return bool|string
		 */
		public function format_currency( $amount, $with_format = false ) {
			if ( empty( $amount ) || is_array( $amount ) ) {
				return false;
			}

			$original_amount = $amount;

			$currency_format    = automotive_listing_get_option( 'currency_format', false );
			$currency_symbol    = automotive_listing_get_option( 'currency_symbol', '' );
			$decimal_amount     = automotive_listing_get_option( 'currency_decimals', 0 );
			$decimal_separator  = automotive_listing_get_option( 'currency_separator_decimal', "." );
			$thousand_separator = automotive_listing_get_option( 'currency_separator', "," );

			if ( $with_format && $currency_format ) {
				$currency_symbol = '<span itemprop="priceCurrency" content="' . esc_attr( $currency_format ) . '">' . $currency_symbol . '</span>';
			}

			if ( automotive_listing_get_option( 'indian_number_system' ) ) {
				$explrestunits = "";
				$amount        = preg_replace( '/,+/', '', $amount );
				$words         = explode( ".", $amount );
				$des           = "00";

				if ( count( $words ) <= 2 ) {
					$amount = $words[0];
					if ( count( $words ) >= 2 ) {
						$des = $words[1];
					}
					if ( strlen( $des ) < 2 ) {
						$des .= 0;
					} else {
						$des = substr( $des, 0, 2 );
					}
				}
				if ( strlen( $amount ) > 3 ) {
					$lastthree = substr( $amount, strlen( $amount ) - 3, strlen( $amount ) );
					$restunits = substr( $amount, 0, strlen( $amount ) - 3 ); // extracts the last three digits
					$restunits = ( strlen( $restunits ) % 2 == 1 ) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
					$expunit   = str_split( $restunits, 2 );
					for ( $i = 0; $i < sizeof( $expunit ); $i ++ ) {
						// creates each of the 2's group and adds a comma to the end
						if ( $i == 0 ) {
							$explrestunits .= (int) $expunit[ $i ] . ","; // if is first value , convert into integer
						} else {
							$explrestunits .= $expunit[ $i ] . ",";
						}
					}
					$thecash = $explrestunits . $lastthree;
				} else {
					$thecash = $amount;
				}

				$amount = $thecash;


			} elseif ( ( automotive_listing_get_option( 'separators_functionality', true ) ) ) {
				$amount = number_format( (float) $amount, $decimal_amount, $decimal_separator, $thousand_separator );
			}

			if ( $with_format ) {
				$amount = '<span itemprop="price" content="' . esc_attr( number_format( (float) $original_amount, $decimal_amount, $decimal_separator, '' ) ) . '">' . $amount . '</span>';
			}

			$currency_placement = automotive_listing_get_option( 'currency_placement', true );

			$amount = ( $currency_placement ? $currency_symbol . $amount : $amount . $currency_symbol );

			return $amount;
		}

		/**
		 * Determines if tax is enabled or not
		 *
		 * @return bool
		 */
		public function is_tax_active() {
			return automotive_listing_get_option( 'tax_functionality', false );
		}

		/**
		 * Detects if WPML is active
		 *
		 * @return bool
		 */
		public function is_wpml_active() {
			return ( defined( "ICL_LANGUAGE_CODE" ) ? true : false );
		}

		/**
		 * Detects if Yoast SEO is active
		 *
		 * @return bool
		 */
		public function is_yoast_active() {
			return ( defined( "WPSEO_VERSION" ) ? true : false );
		}

		//********************************************
		//	Post Type Functions
		//***********************************************************

		/**
		 * Register the Listings Post Type
		 */
		public function register_listing_post_type() {
			$this->get_listing_wp();

			$listing_features = get_option( "listing_features" );

			if ( ! isset( $listing_features ) || $listing_features != "disabled" ) {
				$labels = array(
					'name'               => __( 'Listings', 'listings' ),
					'singular_name'      => __( 'Listing', 'listings' ),
					'add_new'            => __( 'Add New', 'listings' ),
					'add_new_item'       => __( 'Add New Listing', 'listings' ),
					'edit_item'          => __( 'Edit Listing', 'listings' ),
					'new_item'           => __( 'New Listing', 'listings' ),
					'all_items'          => __( 'All Listings', 'listings' ),
					'view_item'          => __( 'View Listing', 'listings' ),
					'search_items'       => __( 'Search Listings', 'listings' ),
					'not_found'          => __( 'No listings found', 'listings' ),
					'not_found_in_trash' => __( 'No listings found in Trash', 'listings' ),
					'menu_name'          => __( 'Listings', 'listings' )
				);

				$args = array(
					'labels'             => $labels,
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array(
						'slug'       => automotive_listing_get_option( 'listing_slug', 'listings' ),
						'with_front' => false
					),
					'capability_type'    => 'post',
					'hierarchical'       => false,
					'menu_position'      => null,
					'supports'           => array( 'title', 'editor', 'comments', 'excerpt', 'author' ),
					'has_archive'        => false
				);

				register_post_type( 'listings', $args );
			}
		}

		/**
		 * Adjust the Listings Post Type columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		public function add_new_listings_columns( $columns ) {
			$new_columns = array();

			if ( automotive_listing_get_option( 'show_image_column' ) ) {
				$columns = array( "auto_image" => __( 'Image', 'listings' ) ) + $columns;
			}

			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) ) {
				foreach ( $column_categories as $column ) {
					$new_columns[ $column['slug'] ] = $column['singular'];
				}
			}

			$new_columns['date'] = __( 'Date', 'listings' );

			return array_merge( $columns, $new_columns );
		}

		/**
		 * Gets the value for the custom columns
		 *
		 * @param $column_name
		 * @param $id
		 */
		public function manage_listings_columns( $column_name, $id ) {
			$return = "";

			if ( $column_name == "auto_image" ) {
				$images = get_post_meta( $id, "gallery_images", true );

				if ( isset( $images[0] ) && ! empty( $images[0] ) ) {
					$return = $this->auto_image( $images[0], "auto_thumb" );
				}
			} else {
				$return   = get_post_meta( $id, $column_name, true );
				$category = $this->get_single_listing_category( $column_name );

				if ( isset( $category['currency'] ) && $category['currency'] ) {
					$return = $this->format_currency( $return );
				}
			}

			echo $return;
		}

		/**
		 * Registers the orderby for the custom columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		public function order_column_register_sortable( $columns ) {
			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) ) {
				foreach ( $column_categories as $column ) {
					$slug = $column['slug'];

					$columns[ $slug ] = $slug;
				}
			}

			return $columns;
		}

		/**
		 * Orders the values of the custom categories
		 *
		 * @param $query
		 */
		public function custom_listings_orderby( $query ) {
			if ( ! is_admin() ) {
				return;
			}

			$orderby = $query->get( 'orderby' );

			$column_categories = $this->get_column_categories();

			if ( ! empty( $column_categories ) && isset( $_GET['post_type'] ) && $_GET['post_type'] == "listings" ) {
				foreach ( $column_categories as $column ) {
					if ( isset( $column['slug'] ) && ! empty( $column['slug'] ) ) {
						$safe = $column['slug'];

						if ( $safe == $orderby ) {
							$query->set( 'meta_key', $safe );
							$query->set( 'orderby', ( $column['compare_value'] != "=" ? 'meta_value_num' : 'meta_value' ) );
						}

						$columns[ $safe ] = $safe;
					}
				}
			}
		}

		//********************************************
		//  Filter emails with customized name & address
		//***********************************************************

		/**
		 * Change the name on outgoing emails in WordPress
		 *
		 * @param $from_name
		 *
		 * @return mixed
		 */
		public function auto_filter_email_name( $from_name ) {
			return automotive_listing_get_option( 'default_email_name', $from_name );
		}

		/**
		 * Change the email on outgoing emails in WordPress
		 *
		 * @param $email
		 *
		 * @return mixed
		 */
		public function auto_filter_email_address( $email ) {
			return automotive_listing_get_option( 'default_email_address', $email );
		}

		/**
		 * Generates the listing arguments
		 *
		 * @param $get_or_post
		 * @param bool|false $all
		 * @param bool|false $ajax_array
		 *
		 * @return array
		 */
		public function listing_args( $get_or_post, $ajax_array = false ) {
			if ( is_array( $ajax_array ) ) {
				$get_or_post = array_merge( $get_or_post, $ajax_array );

				foreach ( $get_or_post as $key => $value ) {
					if ( $key == "paged" ) {
						$_REQUEST['paged'] = $value;
					}
				}
			}

			$filter_multiselect = automotive_listing_get_option( 'filter_showall', false ) && automotive_listing_get_option( 'filter_multiselect', false );
			$paged              = ( isset( $_REQUEST['paged'] ) && ! empty( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : ( get_query_var( "paged" ) ? get_query_var( "paged" ) : 1 ) );
			$listings_amount    = automotive_listing_get_option( 'listings_amount', 10 );

			$args = array(
				'post_type'        => 'listings',
				'meta_query'       => array(),
				'paged'            => ( isset( $paged ) && ! empty( $paged ) ? $paged : get_query_var( 'paged' ) ),
				'posts_per_page'   => $listings_amount,
				'suppress_filters' => false,
				'has_password'     => false,
				'post_status'      => 'publish'
			);

			// sold to bottom
			$data = array(
				'car_sold' => array(
					'key'     => 'car_sold',
					'compare' => 'EXISTS'
				)
			);

			if ( isset( $get_or_post['zip_code'] ) ) {
				$args['zip_code'] = $get_or_post['zip_code'];
			}

			if ( isset( $get_or_post['zip_radius'] ) ) {
				$args['radius'] = $get_or_post['zip_radius'];
			}

			$filterable_categories = $this->get_filterable_listing_categories();

			foreach ( $filterable_categories as $filter ) {
				$get_singular    = $slug = $filter['slug'];
				$filter['terms'] = ( isset( $filter['terms'] ) && is_array( $filter['terms'] ) ? $filter['terms'] : array() );

				// year workaround, bad wordpress >:| ...
				if ( strtolower( $filter['slug'] ) == "year" && isset( $get_or_post["yr"] ) && ! empty( $get_or_post["yr"] ) ) {
					$get_singular = "yr";
				} elseif ( strtolower( $filter['slug'] ) == "year" && isset( $get_or_post["year"] ) && ! empty( $get_or_post["year"] ) ) {
					$get_singular = "year";
				}

				$get_data = ( isset( $get_or_post[ $get_singular ] ) && ! empty( $get_or_post[ $get_singular ] ) ? $get_or_post[ $get_singular ] : "" );

				if ( ! empty( $get_data ) ) {
					$is_min_max = isset( $filter['is_number'] ) && $filter['is_number'] && ( ( is_string( $get_data ) && strpos( $get_data, "," ) !== false ) || is_array( $get_data ) );//false;//is_array( $get_data );

					if ( $is_min_max && is_string( $get_data ) ) {
						$get_data = explode( ",", $get_data );
					}

					// min max values
					if ( $is_min_max &&
					     isset( $get_data[0] ) &&
					     ! empty( $get_data[0] ) &&
					     isset( $get_data[1] ) &&
					     ! empty( $get_data[1] )
					) {
						$min = $get_data[0];
						$max = $get_data[1];

						if ( isset( $filter['is_number'] ) && $filter['is_number'] && (
								$filter['compare_value'] !== "=" || ( count( $get_data ) === 2 )
							)
						) {
							$data[] = array(
								'key'     => $slug,
								'value'   => array( $min, $max ),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							);
						} else {
							$data[] = array(
								'key'     => $slug,
								'value'   => $get_data,
								'compare' => 'IN'
							);
						}

						// also needs to exists for greater | less than
						$data[] = array(
							"key"     => $slug,
							"compare" => "NOT IN",
							"value"   => array( '', 'None', 'none' )
						);
					} elseif ( $is_min_max &&
					           (
						           ( empty( $get_data[0] ) && ! empty( $get_data[1] ) ) ||
						           ( empty( $get_data[1] ) && ! empty( $get_data[0] ) )
					           )
					) {

						$value   = ( empty( $get_data[0] ) ? $get_data[1] : $get_data[0] );
						$compare = ( empty( $get_data[0] ) ? "<=" : ">=" );

						if ( $filter['compare_value'] === '=' ) {
							$data[] = array(
								"key"     => $slug,
								"compare" => '=',
								"value"   => $value,
								"type"    => ( isset( $filter['is_number'] ) && $filter['is_number'] ? "NUMERIC" : "CHAR" )
							);
						} else {
							$data[] = array(
								"key"     => $slug,
								"compare" => $compare,
								"value"   => $value,
								"type"    => ( isset( $filter['is_number'] ) && $filter['is_number'] ? "NUMERIC" : "CHAR" )
							);
						}

					} else {
						$multi_or_data = array( 'relation' => 'OR' );

						// convert to array so we can loop through all values (for multi-select)
						if ( $filter_multiselect ) {
							if ( empty( $get_data ) ) {
								$get_data = array();
							} elseif ( is_array( $get_data ) ) {
								$get_data = $get_data;
							} else {
								$get_data = explode( ',', $get_data );
							}
						} else {
							$get_data = array( $get_data );
						}

						foreach ( $get_data as $single_got_data ) {
							if ( is_string( $single_got_data ) && isset( $filter['terms'][ $single_got_data ] ) ) {

								$current_data = array(
									"key"   => $slug,
									"value" => stripslashes( $filter['terms'][ $single_got_data ] )
								);

								if ( isset( $filter['compare_value'] ) && $filter['compare_value'] != "=" ) {
									$current_data['compare'] = html_entity_decode( $filter['compare_value'] );
									$current_data['type']    = "numeric";

									// also needs to exists for greater | less than
									$data[] = array(
										"key"     => $slug,
										"compare" => "NOT IN",
										"value"   => array( '', 'None', 'none' )
									);
								}

								if ( ! $filter_multiselect ) {
									$data[] = $current_data;
								} else {
									$multi_or_data[] = $current_data;
								}
							} elseif ( $this->is_category_no_term_special_case( $filter['slug'] ) ) {

								$current_data = array(
									"key"   => $slug,
									"value" => stripslashes( $single_got_data )
								);

								if ( ! $filter_multiselect ) {
									$data[] = $current_data;
								} else {
									$multi_or_data[] = $current_data;
								}
							}
						}

						if ( $filter_multiselect && count( $multi_or_data ) > 1 ) {
							$data[] = $multi_or_data;
						}

					}
				}
			}

			// additional categories
			$additional_categories = $this->get_additional_categories();

			if ( ! empty( $additional_categories ) ) {
				foreach ( $additional_categories as $additional_category ) {
					$check_handle = str_replace( " ", "_", automotive_strtolower( $additional_category ) );

					// in url
					if ( isset( $get_or_post[ $check_handle ] ) && ! empty( $get_or_post[ $check_handle ] ) ) {
						$data[] = array( "key" => $check_handle, "value" => 1 );
					}
				}
			}

			// hide sold vehicles
			if ( isset( $_REQUEST['show_only_sold'] ) || ( isset( $get_or_post['sold_only'] ) && $get_or_post['sold_only'] == "true" ) ) {
				$data['car_sold'] = array(
					"key"   => "car_sold",
					"value" => "1"
				);
			} elseif ( ! automotive_listing_get_option( 'inventory_no_sold', true ) && ! isset( $get_or_post['show_sold'] ) ) {
				$data['car_sold'] = array(
					"key"   => "car_sold",
					"value" => "2"
				);
			}

			// newest arrivals
			if ( isset( $get_or_post['arrivals'] ) && ! empty( $get_or_post['arrivals'] ) ) {
				$amount_days = (int) $get_or_post['arrivals'];
				$after       = date( "F d, Y", strtotime( '-' . $amount_days . ' day', time() ) );

				$args['date_query'] = array(
					array(
						"after" => $after
					)
				);
			}

			if ( ! empty( $data ) ) {
				$args['meta_query'] = $data;
			}

			// keywords
			if ( isset( $get_or_post['keywords'] ) && ! empty( $get_or_post['keywords'] ) ) {

				if ( ! automotive_listing_get_option( 'keyword_search', false ) ) {
					$args['s'] = sanitize_text_field( $get_or_post['keywords'] );

				} else {
					$keywords_args = array(
						'relation' => 'OR'
					);

					$filterable_categories = $this->get_listing_categories();

					if ( ! empty( $filterable_categories ) ) {
						foreach ( $filterable_categories as $category ) {
							$keywords_args[] = array(
								"key"     => $category['slug'],
								"value"   => sanitize_text_field( $get_or_post['keywords'] ),
								"compare" => "LIKE"
							);
						}
					}

					$args['meta_query'][] = $keywords_args;
				}
			}

			// features and options
			if ( isset( $get_or_post['features'] ) && ! empty( $get_or_post['features'] ) ) {
				$all_features = $this->get_category_terms( 'options' );
				$features     = $get_or_post['features'];

				// convert to array
				if ( ! is_array( $features ) ) {
					$features = array( $features );
				}

				// go through features
				foreach ( $features as $feature ) {
					// make sure the feature exists
					if ( isset( $all_features[ $feature ] ) ) {
						$args['meta_query'][] = array(
							'key'   => 'automotive_multi_option',
							'value' => $all_features[ $feature ]
						);
					}
				}
			}

			// order by
			$sort_by_options = $this->sort_by();

			$listing_order   = ( isset( $_REQUEST['listing_order'] ) && ! empty( $_REQUEST['listing_order'] ) ? $this->slugify( $_REQUEST['listing_order'] ) : "" );
			$listing_orderby = ( isset( $_REQUEST['listing_orderby'] ) && ! empty( $_REQUEST['listing_orderby'] ) ? strtoupper( $_REQUEST['listing_orderby'] ) : "" );
			$listing_orderby = ( in_array( $listing_orderby, array(
				"ASC",
				"DESC"
			) ) ? $listing_orderby : ( automotive_listing_get_option( 'sortby_default' ) ? "ASC" : "DESC" ) );

			if ( empty( $listing_order ) ) {

				reset( $sort_by_options );

				$default_sort_by = key( $sort_by_options );
				$listing_order   = $default_sort_by;
			}

			if ( $listing_order == "random" ) {
				$args['orderby'] = "rand";
			} else {
				$args['orderby'] = array();

				// move sold listings to bottom
				if ( automotive_listing_get_option( 'sold_to_bottom' ) ) {
					$args['orderby']['car_sold'] = 'DESC';
				}

				$args['orderby'][ $listing_order ] = $listing_orderby;

				// need to add a meta_value for custom sorting
				if ( $listing_order != "title" && $listing_order != "date" ) {
					// used for alphabetical sorting vs numerical
					$sortby_default_alpha = automotive_listing_get_option( 'sortby_default_alpha', false );
					$orderby_key          = ( isset( $sort_by_options[ $listing_order ] ) ? $sort_by_options[ $listing_order ] : "meta_value" );

					$args['meta_key']                = $listing_order;
					$args['orderby'][ $orderby_key ] = $listing_orderby;

					if ( $sortby_default_alpha ) {
						$args['orderby']['title'] = 'ASC';
					}
				}
			}

			$args['order'] = $listing_orderby;

			// setting the same value twice causes issues with numerical sorting so we unset it
			if ( isset( $args['meta_key'] ) && isset( $args['orderby'][ $args['meta_key'] ] ) ) {
				unset( $args['orderby'][ $args['meta_key'] ] );
			}

			$args = apply_filters( "listing_args", $args );

			return array( $args );
		}

		public function is_category_no_term_special_case( $category_slug ) {
			$return   = false;
			$category = $this->get_single_listing_category( $category_slug );

			// from ticket #436020, a unique case that could happen again.
			if (
				isset( $category['is_number'] ) && $category['is_number'] &&
				isset( $category['compare_value'] ) && $category['compare_value'] == "=" &&
				( ! isset( $category['terms'] ) || empty( $category['terms'] ) )
			) {
				$return = true;
			}

			return $return;
		}

		/**
		 * Generates the options for the search box shortcode when user chooses an option
		 */
		public function search_box_shortcode_update_options() {
			$current_options    = ( isset( $_POST['current'] ) ? $_POST['current'] : array() );
			$min_max_categories = ( isset( $_POST['min_max'] ) ? $_POST['min_max'] : array() );

			if ( ! empty( $current_options ) ) {
				foreach ( $current_options as $slug => $value ) {
					if ( empty( $value ) ) {
						unset( $current_options[ $slug ] );
					} elseif ( is_array( $value ) && count( $value ) === 1 ) {
						$current_options[ $slug ] = $value[0];
					}
				}
			}

			// year workaround
			if ( is_array( $min_max_categories ) && ! empty( $min_max_categories ) && in_array( "yr", $min_max_categories ) ) {
				$min_max_categories[] = 'year';
			}

			$dependancies = $this->process_dependancies_plain( $current_options, false, $min_max_categories );

			$dependancies = apply_filters('automotive_search_box_dependancies', $dependancies, $current_options);

			echo wp_json_encode( $dependancies );

			die;
		}

		/**
		 * Determines if the page is a newly created page or editing a previous one
		 *
		 * @param null $new_edit
		 *
		 * @return bool
		 */
		public function is_edit_page( $new_edit = null ) {
			global $pagenow;

			if ( ! is_admin() ) {
				return false;
			}

			if ( $new_edit == "edit" ) {
				return in_array( $pagenow, array( 'post.php', ) );
			} elseif ( $new_edit == "new" ) {
				return in_array( $pagenow, array( 'post-new.php' ) );
			} else {
				return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
			}
		}

		/**
		 * Used to load the options from the old sort to the new way
		 *
		 * @return array
		 */
		public function sort_by_options() {
			$enabled         = array();
			$disabled        = array();
			$listing_orderby = get_auto_orderby();
			$all_categories  = $this->get_listing_categories( false );

			if ( ! empty( $listing_orderby ) ) {
				foreach ( $listing_orderby as $key => $value ) {
					$single = $this->get_single_listing_category( $key );

					if ( isset( $single ) && is_array( $single ) && ! empty( $single['slug'] ) ) {
						$enabled[ $key ] = $single['singular'];

						// remove it from all categories so no duplicates
						if ( isset( $all_categories[ $key ] ) ) {
							unset( $all_categories[ $key ] );
						}
					}
				}
			}

			if ( ! empty( $all_categories ) && is_array( $all_categories ) ) {
				foreach ( $all_categories as $key => $category ) {
					if ( is_array( $category ) ) {
						$disabled[ $key ] = $category['singular'];
					}
				}
			}

			// other non-category ways of sorting
			$disabled['random'] = __( "Random", "listings" );
			$disabled['date']   = __( "Date Added", "listings" );
			$disabled['title']  = __( "Title", "listings" );

			return array( 'enabled' => $enabled, 'disabled' => $disabled );
		}

		/**
		 * Generate an array of options to use for sorting the inventory by
		 *
		 * @return array
		 */
		public function sort_by() {
			$option = 'sortby_categories';

			$sortby = automotive_listing_get_option( $option );
			$return = array();

			$special_sort = array( "title", "date" );

			if ( ! empty( $sortby ) ) {
				unset( $sortby['enabled']['placebo'] );

				if ( isset( $sortby['enabled'] ) ) {
					foreach ( $sortby['enabled'] as $key => $label ) {
						if ( $key != "random" ) {
							if ( in_array( $key, $special_sort ) ) {
								$category['compare_value'] = "=";
							} else {
								$category = $this->get_single_listing_category( $key );
							}

							// this preserves the old way of detecting a number category (aka compare_value);
							$is_number_category = ( ( isset( $category['compare_value'] ) && $category['compare_value'] != "=" ) || ( isset( $category['is_number'] ) && $category['is_number'] ) );

							$return[ $key ] = ( $is_number_category ? "meta_value_num" : "meta_value" );
						} else {
							$return[ $key ] = "random";
						}
					}
				}
			}

			return $return;
		}

		/**
		 * Display a message to the user
		 *
		 * @param $title
		 * @param $message
		 * @param $type
		 * @param $id
		 */
		public function automotive_message( $title, $message, $type, $id, $has_p = false, $old_style = false ) {
			$hidden_notices = get_option( "automotive_hide_messages" );
			$hidden_notices = ( empty( $hidden_notices ) ? array() : $hidden_notices );

			if ( ! in_array( $id, $hidden_notices ) ) {
				$notice_class = '';

				if ( ! empty( $type ) ) {
					if ( strpos( $type, '-alt' ) !== false ) {
						$notice_class = 'notice-' . str_replace( '-alt', '', $type ) . ' notice-alt';
					} else {
						$notice_class = 'notice-' . $type;
					}
				}

				if ( $old_style ) { ?>
          <div class="automotive-custom-notice notice <?php echo $notice_class; ?>" data-id="<?php echo $id; ?>">
            <span class="hide_message"><button type="button" class="notice-dismiss"><span
                  class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'listings' ); ?></span></button></span>

            <h3><?php echo $title; ?></h3>

						<?php echo ( $has_p === false ? '<p>' : '' ) . $message . ( $has_p === false ? '</p>' : '' ); ?>
          </div>
				<?php } else { ?>
          <div class="<?php echo $type; ?> auto_message" data-id="<?php echo $id; ?>">
            <span class="hide_message"><i class="fa fa-close"></i></span>

            <h3><?php echo $title; ?></h3>

						<?php echo ( $has_p === false ? '<p>' : '' ) . $message . ( $has_p === false ? '</p>' : '' ); ?>
          </div>
					<?php

				}
			}
		}

		public function automotive_admin_help_message( $message ) {
			return ( automotive_listing_get_option( 'hide_hints', false ) ? '' : '<div class="help_area">
					<div class="message_area">
						' . $message . '
					</div>

					<div class="triangle"><i class="fa fa-info-circle"></i></div>
				</div>' );
		}

		/**
		 * Lets the user hide the message forever!
		 */
		public function hide_automotive_message() {
			$hidden_notices = get_option( "automotive_hide_messages" );
			$hidden_notices = ( empty( $hidden_notices ) ? array() : $hidden_notices );

			$hidden_notices[] = sanitize_text_field( $_POST['id'] );

			update_option( "automotive_hide_messages", $hidden_notices );
		}

		/**
		 * Edits a listing category value
		 */
		public function edit_listing_category_value() {
			$current_value = sanitize_text_field( $_POST['current_value'] );
			$new_value     = sanitize_text_field( $_POST['new_value'] );
			$category      = sanitize_text_field( $_POST['category'] );

			$current_category = $this->get_single_listing_category( $category );

			$current_terms = $current_category['terms'];
			$return        = array(
				"message" => __( "Successfully changed the value", "listings" ),
				"status"  => "true"
			);

			if ( ! empty( $current_category ) ) {

				if ( is_array( $current_terms ) && in_array( $current_value, $current_terms ) ) {
					$term_key = array_search( $current_value, $current_terms );
					$new_slug = $this->slugify( $new_value );

					// remove old value
					unset( $current_category['terms'][ $term_key ] );

					// add new value
					$current_category['terms'][ $new_slug ] = $new_value;

					// update category
					if ( $category == "options" ) {
						$this->set_single_listing_category( $current_category, true );
					} else {
						$this->set_single_listing_category( $current_category, false );
					}
					// update value in existing listings
					$existing_listing_ids = new WP_Query( array(
						"post_type"      => "listings",
						"posts_per_page" => - 1,
						"fields"         => "ids"
					) );

					if ( $existing_listing_ids->have_posts() ) {
						global $wpdb;

						foreach ( $existing_listing_ids->posts as $id ) {
							// different method for updating options (since its serialized)
							if ( $category == "options" ) {
								$multi_options = get_post_meta( $id, "multi_options", true );

								if ( ! empty( $multi_options ) && is_array( $multi_options ) && in_array( $current_value, $multi_options ) ) {
									$option_key = array_search( $current_value, $multi_options );
									unset( $multi_options[ $option_key ] );

									$multi_options[] = $new_value;

									update_post_meta( $id, "multi_options", $multi_options );
								}
							} else {
								$wpdb->update( $wpdb->prefix . "postmeta", array( "meta_value" => $new_value ), array(
									"post_id"    => $id,
									"meta_key"   => $category,
									"meta_value" => $current_value
								) );
							}
						}
					}

					// update dependancies
					$this->generate_dependancy_option( true );

					$return['slug'] = $new_slug;

				} else {
					$return = array( "message" => __( "Term not found", "listings" ), "status" => "false" );
				}
			} else {
				$return = array( "message" => __( "Category not found", "listings" ), "status" => "false" );
			}

			echo wp_json_encode( $return );

			die;
		}

		/**
		 * Generates the WooCommerce Integration Shortcode
		 *
		 * @param $woocommerce_product_id
		 */
		public function woocommerce_integration( $woocommerce_product_id ) {
			$style = "";

			$woocommerce_integration_border  = automotive_listing_get_option( 'woocommerce_integration_border', false );
			$woocommerce_integration_padding = automotive_listing_get_option( 'woocommerce_integration_padding', false );

			if ( $woocommerce_integration_border ) {
				$border_width = ( isset( $woocommerce_integration_border['border-top'] ) && ! empty( $woocommerce_integration_border['border-top'] ) ? $woocommerce_integration_border['border-top'] : "" );
				$border_style = ( isset( $woocommerce_integration_border['border-style'] ) && ! empty( $woocommerce_integration_border['border-style'] ) ? $woocommerce_integration_border['border-style'] : "" );
				$border_color = ( isset( $woocommerce_integration_border['border-color'] ) && ! empty( $woocommerce_integration_border['border-color'] ) ? $woocommerce_integration_border['border-color'] : "" );

				// generate border
				$style .= 'border: ';
				$style .= ( ! empty( $border_width ) ? $border_width : "0px" ) . " ";
				$style .= ( ! empty( $border_style ) ? $border_style : "none" ) . " ";
				$style .= ( ! empty( $border_color ) ? $border_color : "transparent" ) . "; ";
			}

			if ( $woocommerce_integration_padding ) {
				$padding_top    = ( isset( $woocommerce_integration_padding['padding-top'] ) && ! empty( $woocommerce_integration_padding['padding-top'] ) ? $woocommerce_integration_padding['padding-top'] : "" );
				$padding_right  = ( isset( $woocommerce_integration_padding['padding-left'] ) && ! empty( $woocommerce_integration_padding['padding-left'] ) ? $woocommerce_integration_padding['padding-left'] : "" );
				$padding_bottom = ( isset( $woocommerce_integration_padding['padding-bottom'] ) && ! empty( $woocommerce_integration_padding['padding-bottom'] ) ? $woocommerce_integration_padding['padding-bottom'] : "" );
				$padding_left   = ( isset( $woocommerce_integration_padding['padding-left'] ) && ! empty( $woocommerce_integration_padding['padding-left'] ) ? $woocommerce_integration_padding['padding-left'] : "" );


				// generate padding
				$style .= "padding: ";

				$style .= ( ! empty( $padding_top ) ? $padding_top : "0px" ) . " ";
				$style .= ( ! empty( $padding_right ) ? $padding_right : "0px" ) . " ";
				$style .= ( ! empty( $padding_bottom ) ? $padding_bottom : "0px" ) . " ";
				$style .= ( ! empty( $padding_left ) ? $padding_left : "0px" ) . ";";
			}


			echo do_shortcode( '[add_to_cart id="' . $woocommerce_product_id . '" style="' . $style . '"]' );
		}

		/**
		 * Toggles Listing Features
		 */
		public function toggle_listing_features() {
			$listing_features_current = get_option( "listing_features" );

			if ( empty( $listing_features_current ) ) {
				$new_value = "disabled";
			} elseif ( $listing_features_current == "disabled" ) {
				$new_value = "enabled";
			} else {
				$new_value = "disabled";
			}

			update_option( "listing_features", $new_value );

			echo $new_value;

			die;
		}

		/**
		 * Updates the listing categories that are used when a user sends a listing to the trash
		 *
		 * @param $post_id
		 */
		public function listing_sent_trash( $post_id ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type == "listings" ) {
				$this->update_dependancy_option( $post_id, "delete" );
			}
		}

		/**
		 * Updates the listing categories that are used when a user restores a listing from the trash
		 *
		 * @param $post_id
		 */
		public function listing_restore_trash( $post_id ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type == "listings" ) {
				$new_listing_categories_values = array();
				$listing_categories            = $this->get_listing_categories();

				if ( ! empty( $listing_categories ) ) {
					foreach ( $listing_categories as $key => $category ) {
						$value = get_post_meta( $post_id, $category['slug'], true );

						$new_listing_categories_values[ $category['slug'] ] = array( $this->slugify( $value ) => $value );
					}
				}

				$this->update_dependancy_option( $post_id, $new_listing_categories_values );
			}
		}

		public function regenerate_listing_category_terms() {
			$this->generate_dependancy_option( true );

			echo wp_json_encode( array( "success" ) );

			die;
		}

		public $wpml_string = array(
			"category" => array(
				"context" => "Listing Categories",
				"name"    => "Listing Category: "
			),
			"term"     => array(
				"context" => "Listing Category Terms",
				"name"    => "Listing Category Term: "
			),
			"badge"    => array(
				"context" => "Listing Badges",
				"name"    => "Listing Badge: "
			)
		);

		public function regenerate_listing_wpml_terms() {

			if ( ! empty( $this->listing_categories ) ) {
				foreach ( $this->listing_categories as $slug => $category ) {
					// register plural & singular
					do_action( 'wpml_register_single_string', $this->wpml_string['category']['context'], $this->wpml_string['category']['name'] . $category['singular'], $category['singular'] );
					do_action( 'wpml_register_single_string', $this->wpml_string['category']['context'], $this->wpml_string['category']['name'] . $category['plural'], $category['plural'] );

					if ( ! empty( $category['terms'] ) ) {
						foreach ( $category['terms'] as $term_id => $term_value ) {

							do_action( 'wpml_register_single_string', $this->wpml_string['term']['context'], $this->wpml_string['term']['name'] . $term_value, $term_value );
						}
					}
				}
			}

			$custom_badges = automotive_listing_get_option( 'custom_badges', false );

			if ( ! empty( $custom_badges['name'] ) ) {

				foreach ( $custom_badges['name'] as $badge ) {

					if ( ! empty( $badge ) ) {

						do_action( 'wpml_register_single_string', $this->wpml_string['badge']['context'], $this->wpml_string['badge']['name'] . $badge, $badge );

					}
				}

			}

			echo wp_json_encode( array( "success" ) );

			die;
		}

		public function wpml_convert() {

		}

		public function delete_unused_listing_terms() {
			$original_listing_categories = $this->listing_categories;
			$new_listing_categories      = $this->listing_categories;

			if ( ! empty( $this->listing_categories ) ) {
				foreach ( $this->listing_categories as $slug => $category ) {
					$is_options = ( $slug == "options" );

					if ( isset( $category['terms'] ) && ! empty( $category['terms'] ) ) {
						foreach ( $category['terms'] as $term_slug => $term ) {
							$count = (int) get_total_meta( $slug, stripslashes( $term ), $is_options );

							if ( ! $count ) {
								unset( $new_listing_categories[ $slug ]['terms'][ $term_slug ] );
							}
						}
					}

				}

				if ( $original_listing_categories !== $new_listing_categories ) {
					$this->update_listing_categories( $new_listing_categories );
				}
			}

			wp_die( wp_json_encode( array(
				'success'
			) ) );
		}

		public function display_listing_category_term( $term, $category = false ) {

			if ( $category ) {
				$term = apply_filters( 'automotive_listing_' . $category . '_term', $term );
			}

			if ( $this->is_wpml_active() ) {
				$term = apply_filters( 'wpml_translate_single_string', $term, $this->wpml_string['term']['context'], $this->wpml_string['term']['name'] . $term );
			}

			return $term;
		}

		public function display_listing_category( $category ) {

			if ( $this->is_wpml_active() ) {
				$category = apply_filters( 'wpml_translate_single_string', $category, $this->wpml_string['category']['context'], $this->wpml_string['category']['name'] . $category );
			}

			return $category;
		}

		public function display_listing_badge( $badge ) {

			if ( $this->is_wpml_active() ) {
				$badge = apply_filters( 'wpml_translate_single_string', $badge, $this->wpml_string['badge']['context'], $this->wpml_string['badge']['name'] . $badge );
			}

			return $badge;
		}

		public function auto_image( $id, $size, $url = false ) {
			if ( $this->is_hotlink() ) {
				$slider_thumbnails = $this->get_automotive_image_sizes();

				// determine size
				if ( $size == "related_portfolio" ) {
					$width  = 270;
					$height = 140;
				} elseif ( $size == "auto_thumb" ) {
					$width  = $slider_thumbnails['width'];
					$height = $slider_thumbnails['height'];
				} elseif ( $size == "auto_slider" ) {
					$width  = $slider_thumbnails['slider']['width'];
					$height = $slider_thumbnails['slider']['height'];
				} elseif ( $size == "auto_listing" ) {
					$width  = $slider_thumbnails['listing']['width'];
					$height = $slider_thumbnails['listing']['height'];
				} elseif ( $size == "auto_portfolio" ) {
					$width  = 770;
					$height = 450;
				}

				$not_found_image = automotive_listing_get_option( 'not_found_image', false );

				// determine image
				if ( ! filter_var( $id, FILTER_VALIDATE_URL ) && $not_found_image && ! empty( $not_found_image['url'] ) ) {
					$id = $not_found_image['url'];
				} elseif ( ! filter_var( $id, FILTER_VALIDATE_URL ) && empty( $not_found_image['url'] ) ) {
					$id = LISTING_DIR . "images/pixel.gif";
				}

				$return = ( $url == true ? $id : "<img src='" . $id . "'" . ( isset( $height ) && isset( $width ) ? "style='height: " . $height . "px; width: " . $width . "px'" : "" ) . ">" );

				return $return;
			} else {
				$return = ( $url == true ? wp_get_attachment_image_src( $id, $size ) : wp_get_attachment_image( $id, $size ) );

				return ( $url == true ? $return[0] : $return );
			}
		}

		public function is_hotlink() {
			return automotive_listing_get_option( 'hotlink', false );
		}

		public function get_listing_badge( $badge, $is_sold = false ) {
			$custom_badges = automotive_listing_get_option( 'custom_badges', array() );

			$badge       = ( $is_sold == true ? $custom_badges['name']['sold'] : $badge );
			$current_key = array_search( $badge, $custom_badges['name'] );

			return array(
				"name"  => $badge,
				"css"   => "custom_badge_" . $this->numbers_to_text( $this->slugify( $badge ) ),
				"color" => $custom_badges['color'][ $current_key ]
			);
		}

		public function get_listing_badge_html( $listing_options, $post_meta ) {
			// if sold auto add badge
			$sold_attach_badge = automotive_listing_get_option( 'sold_attach_badge', false );
			$auto_sold_badge   = ( $sold_attach_badge && isset( $post_meta['car_sold'] ) && ! empty( $post_meta['car_sold'] ) && $post_meta['car_sold'] == 1 ? true : false );

			if ( $auto_sold_badge ) {
				$listing_options['custom_badge'] = "sold";
			}

			if ( isset( $listing_options['custom_badge'] ) && ! empty( $listing_options['custom_badge'] ) ) {
				$listing_badge = $this->get_listing_badge( $listing_options['custom_badge'], $auto_sold_badge ); ?>
        <div class="angled_badge <?php echo $listing_badge['css']; ?>">
          <span<?php echo( strlen( $listing_badge['name'] ) >= 7 ? " class='smaller'" : "" ); ?>><?php echo $this->display_listing_badge( $listing_badge['name'] ); ?></span>
        </div>
			<?php }
		}

		/**
		 * Grabs badge HTML since version 14.6
		 *
		 * @param string $custom_badge Badge name for current listing
		 * @param boolean $is_sold Is current listing sold
		 *
		 * @return string                Badge HTML
		 */
		public function get_badge_html( $custom_badge, $is_sold ) {
			// if sold auto add badge
			$auto_sold_badge = ( automotive_listing_get_option( 'sold_attach_badge', false ) && $is_sold );

			if ( $auto_sold_badge ) {
				$custom_badge = "sold";
			}

			if ( isset( $custom_badge ) && ! empty( $custom_badge ) ) {
				$listing_badge = $this->get_listing_badge( $custom_badge, $auto_sold_badge ); ?>
        <div class="angled_badge <?php echo $listing_badge['css']; ?>">
          <span<?php echo( strlen( $listing_badge['name'] ) >= 7 ? " class='smaller'" : "" ); ?>>
						<?php echo $this->display_listing_badge( $listing_badge['name'] ); ?>
					</span>
        </div>
				<?php
			}
		}

		public function add_new_listing_badge() {
			$name  = sanitize_text_field( $_POST['name'] );
			$color = sanitize_text_field( $_POST['color'] );
			$font  = sanitize_text_field( $_POST['font'] );

			$listing_options = get_option( "listing_wp" );
			$current_badges  = ( isset( $listing_options['custom_badges'] ) && ! empty( $listing_options['custom_badges'] ) ? $listing_options['custom_badges'] : array() );

			$current_badges['name'][]  = $name;
			$current_badges['color'][] = $color;
			$current_badges['font'][]  = $font;

			$listing_options['custom_badges'] = $current_badges;

			update_option( "listing_wp", $listing_options );

			die;
		}

		public function numbers_to_text( $value ) {
			$replace = array(
				0 => "zero",
				1 => "one",
				2 => "two",
				3 => "three",
				4 => "four",
				5 => "five",
				6 => "six",
				7 => "seven",
				8 => "eight",
				9 => "nine"
			);

			return str_replace( array_keys( $replace ), array_values( $replace ), $value );
		}

		public function get_wp_roles() {
			// for now return static roles, get_editable_roles() seems to be derping
			return array(
				"read"                 => __( "Subscriber", "listings" ),
				"edit_posts"           => __( "Contributor", "listings" ),
				"edit_published_posts" => __( "Author", "listings" ),
				"edit_pages"           => __( "Editor", "listings" ),
				"install_plugins"      => __( "Administrator", "listings" )
			);
		}

		public function add_sold_view( $views ) {
			global $wpdb;

			$query = "SELECT * FROM " . $wpdb->prefix . "posts as p, " . $wpdb->prefix . "postmeta as m WHERE p.ID = m.post_id AND (p.post_status = 'publish' OR p.post_status = 'pending' OR p.post_status = 'draft') AND p.post_type = 'listings' AND m.meta_key = 'car_sold' AND m.meta_value = '1'";

			$total = count( $wpdb->get_results( $query ) );

			$views = array_slice( $views, 0, 1, true ) +
			         array(
				         'sold' => "<a href='" . add_query_arg( array(
						         "post_type"     => "listings",
						         "sold_listings" => 1
					         ), admin_url( 'edit.php' ) ) . "'" . ( isset( $_GET['sold_listings'] ) && $_GET['sold_listings'] == 1 ? " class='current'" : "" ) . ">" . __( "Sold", "listings" ) . " <span class=\"count\">(" . $total . ")</span></a>"
			         ) +
			         array_slice( $views, 1, null, true );

			return $views;
		}

		public function add_for_sale_view( $views ) {
			global $wpdb;

			$query = "SELECT * FROM " . $wpdb->prefix . "posts as p, " . $wpdb->prefix . "postmeta as m WHERE p.ID = m.post_id AND (p.post_status = 'publish' OR p.post_status = 'pending' OR p.post_status = 'draft') AND p.post_type = 'listings' AND m.meta_key = 'car_sold' AND m.meta_value = '2'";

			$total = count( $wpdb->get_results( $query ) );

			$views = array_slice( $views, 0, 1, true ) +
			         array(
				         'for_sale' => "<a href='" . add_query_arg( array(
						         "post_type"     => "listings",
						         "sold_listings" => 2
					         ), admin_url( 'edit.php' ) ) . "'" . ( isset( $_GET['sold_listings'] ) && $_GET['sold_listings'] == 2 ? " class='current'" : "" ) . ">" . __( "For Sale", "listings" ) . " <span class=\"count\">(" . $total . ")</span></a>"
			         ) +
			         array_slice( $views, 1, null, true );

			return $views;
		}


		public function add_sold_view_query( $query ) {
			if ( isset( $_GET['sold_listings'] ) && $_GET['sold_listings'] == 1 ) {
				$query->set( 'meta_key', 'car_sold' );
				$query->set( 'meta_value', '1' );
			} elseif ( isset( $_GET['sold_listings'] ) && $_GET['sold_listings'] == 2 ) {
				$query->set( 'meta_key', 'car_sold' );
				$query->set( 'meta_value', '2' );
			}

			return $query;
		}

		/*public function current_filter_json(){
			if(!empty($this->current_categories)){
				$current_filters = array();

				foreach($this->current_categories as $current_slug => $current_value){
					$current_slug = ($current_slug == "year" ? "yr" : $current_slug);
					$label  			= '';

					// D([$current_slug, $current_value]);

					if(is_array($current_value)){
						foreach($current_value as $single_key => $single_value){
							if($current_slug == "features"){
								$label .= $single_value . ", ";
							} else {
								if(!empty($single_value)){
									$label .= $this->get_pretty_listing_term($current_slug, $single_value) . ', ';
								}
							}
						}

						$label = rtrim($label, ', ');
					} else {
						$label = $this->get_pretty_listing_term($current_slug, $current_value);
					}

					if($current_slug == 'features'){
						$singular = __('Options', 'listings');
					} else {
						$singular = $this->get_single_listing_category($current_slug, 'singular');
					}

					$current_filters[$current_slug] = array(
						'label'    => $label,
						'singular' => $singular,
						'value'    => $current_value
					);
				}

				?><script>var inventoryCurrentFilters = <?php echo wp_json_encode($current_filters); ?>;</script><?php
			}
		}*/

		public function current_filter_json() {
			if ( ! empty( $this->current_categories ) ) {
				$current_filters = array();

//				 D($this->current_categories);

				foreach ( $this->current_categories as $current_slug => $current_value ) {
					$current_slug = ( $current_slug == "year" ? "yr" : $current_slug );

					if ( $current_slug == 'features' ) {
						$singular = __( 'Options', 'listings' );
					} else {
						$singular = $this->get_single_listing_category( $current_slug, 'singular' );
					}

					$current_filters[ $current_slug ] = array(
						'singular' => $singular,
						'data'     => array()
					);

					if ( isset( $this->min_max_categories[ $current_slug ] ) ) {
						$current_filters[ $current_slug ]['min_max'] = true;

						// D($current_value);
						foreach ( $current_value as $single_current_key => $single_current_value ) {
							$current_filters[ $current_slug ]['data'][ $single_current_value ] = $single_current_value;
						}

					} else {
						if ( is_array( $current_value ) ) {
							foreach ( $current_value as $single_current_key => $single_current_value ) {
								if ( $current_slug == 'features' ) {
									$current_filters[ $current_slug ]['data'][ $single_current_key ] = $single_current_value;
								} else {
									$current_filters[ $current_slug ]['data'][ $single_current_value ] = $this->get_pretty_listing_term( $current_slug, $single_current_value );
								}
							}
						} else {
							if ( $current_slug == 'features' ) {
								$current_filters[ $current_slug ]['data'][ $current_slug ] = $current_value;
							} else {
								$current_filters[ $current_slug ]['data'][ $current_value ] = $this->get_pretty_listing_term( $current_slug, $current_value );
							}
						}
					}


					// $label  			= '';

					// // D([$current_slug, $current_value]);

					// if(is_array($current_value)){
					// 	foreach($current_value as $single_key => $single_value){
					// 		if($current_slug == "features"){
					// 			$label .= $single_value . ", ";
					// 		} else {
					// 			if(!empty($single_value)){
					// 				$label .= $this->get_pretty_listing_term($current_slug, $single_value) . ', ';
					// 			}
					// 		}
					// 	}

					// 	$label = rtrim($label, ', ');
					// } else {
					// 	$label = $this->get_pretty_listing_term($current_slug, $current_value);
					// }

					// $current_filters[$current_slug] = array(
					// 	'label'    => $label,
					// 	'singular' => $singular,
					// 	'value'    => $current_value
					// );
				}

				// D($current_filters);

				?>
        <script>var inventoryCurrentFilters = <?php echo wp_json_encode( $current_filters ); ?>;</script><?php
			}
		}

		public function compare_no_cache() {
			global $post;

			$comparison_page = automotive_listing_get_option( 'comparison_page', false );
			$inventory_page  = automotive_listing_get_option( 'inventory_page', false );

			if ( ( $comparison_page || $inventory_page ) && isset( $post ) && in_array( $post->ID, array(
					(int) $comparison_page,
					(int) $inventory_page
				) )
			) {
				nocache_headers();
			}
		}

		public function get_public_query_vars() {
			return array(
				'm',
				'p',
				'posts',
				'w',
				'cat',
				'withcomments',
				'withoutcomments',
				's',
				'search',
				'exact',
				'sentence',
				'debug',
				'calendar',
				'page',
				'paged',
				'more',
				'tb',
				'pb',
				'author',
				'order',
				'orderby',
				'monthnum',
				'day',
				'hour',
				'minute',
				'second',
				'name',
				'category_name',
				'tag',
				'feed',
				'author_name',
				'static',
				'pagename',
				'page_id',
				'error',
				'comments_popup',
				'attachment',
				'attachment_id',
				'subpost',
				'subpost_id',
				'preview',
				'robots',
				'taxonomy',
				'term',
				'cpage',
				'post_type',
				'order',
				'action',
				'layout',
				'paged',
				'options',
				'rand',
				'random',
				'date',
				'title'
			);
		}

		public function update_listing_categories( $listing_categories ) {
			update_option( $this->get_listing_categories_option_name(), $listing_categories );
		}

		public function convert_quotes( $str ) {
			$chr_map = array(
				// Windows codepage 1252
				"\xC2\x82"     => "'", // U+0082⇒U+201A single low-9 quotation mark
				"\xC2\x84"     => '"', // U+0084⇒U+201E double low-9 quotation mark
				"\xC2\x8B"     => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
				"\xC2\x91"     => "'", // U+0091⇒U+2018 left single quotation mark
				"\xC2\x92"     => "'", // U+0092⇒U+2019 right single quotation mark
				"\xC2\x93"     => '"', // U+0093⇒U+201C left double quotation mark
				"\xC2\x94"     => '"', // U+0094⇒U+201D right double quotation mark
				"\xC2\x9B"     => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
				"″"            => '"',
				"′"            => "'",
				"“"            => '"',
				"‟"            => '"',
				"”"            => '"',
				"’"            => "'",
				"˝"            => '"',
				"ʺ"            => '"',
				"˶"            => '"',
				"‶"            => '"',
				"״"            => '"',
				"ˮ"            => 'ˮ',
				"〃"            => '"',
				"＂"            => '"',

				// Regular Unicode     // U+0022 quotation mark (")
				// U+0027 apostrophe     (')
				"\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
				"\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
				"\xE2\x80\x98" => "'", // U+2018 left single quotation mark
				"\xE2\x80\x99" => "'", // U+2019 right single quotation mark
				"\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
				"\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
				"\xE2\x80\x9C" => '"', // U+201C left double quotation mark
				"\xE2\x80\x9D" => '"', // U+201D right double quotation mark
				"\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
				"\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
				"\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
				"\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
			);
			$chr     = array_keys( $chr_map ); // but: for efficiency you should
			$rpl     = array_values( $chr_map ); // pre-calculate these two arrays
			$str     = str_replace( $chr, $rpl, html_entity_decode( $str, ENT_QUOTES, "UTF-8" ) );

			return $str;
		}

		public function add_listing_category_term( $category_slug, $term ) {
			$return = false;


			if ( ! isset( $this->listing_categories[ $category_slug ] ) ) {
				$this->listing_categories[ $category_slug ] = array(
					'terms' => array()
				);
			}

			if ( isset( $this->listing_categories[ $category_slug ] ) ) {
				// sanitize
				$term             = sanitize_text_field( $this->convert_quotes( $term ) );
				$slug             = $this->slugify( $term );
				$current_category = &$this->listing_categories[ $category_slug ]; // & it's modifiable

				// if terms don't exist, make em
				if ( empty( $current_category['terms'] ) || ! isset( $current_category['terms'] ) || ! is_array( $current_category['terms'] ) ) {
					$current_category['terms'] = array();
				}

				// add the term if it doesn't exist
				if ( ! isset( $current_category['terms'][ $slug ] ) ) {
					$current_category['terms'][ $slug ] = $term;

					// only update since a new option has been added
					$this->update_listing_categories( $this->listing_categories );

					// return the terms
					$return = array(
						"name" => $term,
						"slug" => $slug
					);
				}
			}

			return $return;
		}


		public function ajax_add_listing_category_term() {
			$nonce = $_REQUEST['nonce'];
			$name  = sanitize_text_field( $_POST['value'] );
			$exact = sanitize_text_field( $_POST['exact'] );

			// add the term
			if ( wp_verify_nonce( $nonce, 'add_listing_value_' . $exact ) ) {
				$add = $this->add_listing_category_term( $exact, $name );

				if ( $add ) {
					echo wp_json_encode( $add );
				} else {
					echo wp_json_encode(
						array(
							"error" => __( "Couldn't add the term", "listings" )
						)
					);
				}
			}

			die;
		}

		/**
		 * Delete a listing category term
		 */
		public function ajax_delete_listing_category_term() {
			$slug_term = $_POST['id'];
			$category  = $_POST['type'];

			$this->remove_listing_category_term( $category, $slug_term );

			die;
		}

		public function remove_listing_category_term( $category_slug, $term ) {
			$current_category = &$this->listing_categories[ $category_slug ];

			unset( $current_category['terms'][ $term ] );

			$this->update_listing_categories( $this->listing_categories );
		}

		/**
		 * Sets the current query variable
		 *
		 * @param $args
		 * @param bool $ajax_array
		 */
		public function set_current_query_info( $args, $ajax_array = false ) {
			$listing_args                             = $this->listing_args( $args, ( isset( $ajax_array ) && ! empty( $ajax_array ) ? $ajax_array : false ) );
			$this->current_query_info['listing_args'] = $listing_args[0];

			if ( defined( "AUTOMOTIVE_CUSTOM_QUERIES" ) && AUTOMOTIVE_CUSTOM_QUERIES ) {
				global $Listing_Query;

				$_wp_query_listings = $Listing_Query->custom_query( $listing_args[0] );
			} elseif ( defined( "AUTO_ZIP_HOME" ) && class_exists( "WP_GeoQuery" ) ) {
				$_wp_query_listings = new WP_GeoQuery( $listing_args[0] );
			} else {
				$_wp_query_listings = new WP_Query( $listing_args[0] );
			}

			$this->current_query_info['listings'] = $_wp_query_listings->posts;

			// now get total number for this set of filters (need to find a more efficient way, caching maybe?)
			$listing_args[0]['posts_per_page'] = - 1;

			if ( defined( "AUTOMOTIVE_CUSTOM_QUERIES" ) && AUTOMOTIVE_CUSTOM_QUERIES ) {
				global $Listing_Query;

				$this->current_query_info['total'] = $Listing_Query->custom_query( $listing_args[0], true );
			} elseif ( defined( "AUTO_ZIP_HOME" ) && class_exists( "WP_GeoQuery" ) ) {
				$_wp_query_listings = new WP_GeoQuery( $listing_args[0] );

				$this->current_query_info['total'] = count( $_wp_query_listings->posts );
			} else {
				$_wp_query_listings = new WP_Query( $listing_args[0] );

				$this->current_query_info['total'] = count( $_wp_query_listings->posts );
			}
		}

		/**
		 * Gets the additional categories on the site
		 *
		 * @return array
		 */
		public function get_additional_categories() {
			$return                = array();
			$additional_categories = "additional_categories";
			$add_categories        = automotive_listing_get_option( $additional_categories, false );

			if ( isset( $add_categories['value'] ) && ! empty( $add_categories['value'] ) ) {
				$return = $add_categories['value'];
			}

			return $return;
		}

		/**
		 * Grab the first image for the listing
		 *
		 * @param $listing_id
		 * @param string $size
		 *
		 * @return array|false|string
		 */
		public function get_single_listing_image( $listing_id, $size = "auto_listing" ) {
			$gallery         = get_post_meta( $listing_id, "gallery_images", true );
			$not_found_image = automotive_listing_get_option( 'not_found_image', false );

			if ( isset( $gallery ) && ! empty( $gallery ) && isset( $gallery[0] ) && ! empty( $gallery[0] ) ) {
				$return = $this->auto_image( $gallery[0], $size, true );
			} elseif ( empty( $gallery[0] ) && isset( $not_found_image['url'] ) && ! empty( $not_found_image['url'] ) ) {
				$return = $not_found_image['url'];
			} else {
				$return = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
			}

			return $return;
		}

		public function convert_old_data() {
			// is number upgrade convert
			$is_number_convert = get_option( "is_number_convert" );

			if ( ! $is_number_convert ) {
				$listing_categories = $this->get_listing_categories();

				if ( ! empty( $listing_categories ) ) {
					foreach ( $listing_categories as $category_key => $category ) {
						if ( $category_key != "options" && ( isset( $category['compare_value'] ) && $category['compare_value'] != "=" ) ) {
							$listing_categories[ $category_key ]['is_number'] = 1;
						}
					}
				}

				update_option( 'listing_categories', $listing_categories );
				update_option( 'is_number_convert', true );
			} else {
				$listing_categories = $this->get_listing_categories();
				$options_return     = get_option( "is_options_returned" );

				if ( ( ( ! isset( $listing_categories['options'] ) || empty( $listing_categories['options'] ) ) && ! $options_return ) || ( isset( $_GET['force_terms_refresh'] ) && is_admin() ) ) {
					$new_options = array();

					$all_listings = get_posts(
						array(
							"post_type"      => "listings",
							"posts_per_page" => - 1
						)
					);

					if ( ! empty( $all_listings ) ) {
						foreach ( $all_listings as $listing ) {
							$multi_options = get_post_meta( $listing->ID, "multi_options", true );

							if ( ! empty( $multi_options ) ) {
								foreach ( $multi_options as $option ) {
									$new_options[ $this->slugify( $option ) ] = $option;
								}
							}
						}
					}

					$listing_categories['options']['terms'] = $new_options;

					update_option( 'listing_categories', $listing_categories );
					update_option( 'is_options_returned', true );
				}
			}

			/* Yoast SEO _thumbnail_id convert */
			$yoast_thumbnail_id_convert = get_option( "listings_yoast_thumbnail_convert" );

			if ( ! $yoast_thumbnail_id_convert ) {
				$all_listings = get_posts(
					array(
						"post_type"      => "listings",
						"posts_per_page" => - 1
					)
				);

				if ( ! empty( $all_listings ) ) {
					foreach ( $all_listings as $listing ) {
						$gallery_images = get_post_meta( $listing->ID, "gallery_images", true );

						if ( ! empty( $gallery_images ) ) {
							update_post_meta( $listing->ID, "_thumbnail_id", $gallery_images[0] );
						}
					}
				}

				update_option( "listings_yoast_thumbnail_convert", true );
			}
		}

		public function delete_listing_category_terms() {
			$listing_categories = $this->get_listing_categories();

			if ( ! empty( $listing_categories ) ) {
				foreach ( $listing_categories as &$category ) {
					$category['terms'] = array();
				}
			}

			$this->update_listing_categories( $listing_categories );

			echo json_encode( array(
				"text" => __( "Successfully removed all listing terms", "listings" )
			) );

			die;
		}

		public function mailchimp() {
			$return            = false;
			$mailchimp_api_key = automotive_listing_get_option( 'mailchimp_api_key', false );

			if ( ! empty( $mailchimp_api_key ) ) {
				require_once( LISTING_HOME . "/classes/mailchimp/MCAPI.class.php" );

				$return = new Auto_MailChimp( $mailchimp_api_key );

				if ( ! is_ssl() ) {
					$return->verify_ssl = false;
				}
			}

			return $return;
		}

		public function get_raw_listing_tabs() {
			return array(
				'vehicle_overview'         => array(
					'text'        => __( "Vehicle Overview" ),
					'type'        => 'wp_editor',
					'name'        => 'content',
					'icon'        => 'fa fa-list-alt',
					'html_tab_id' => 'vehicle',
					'active_tab'  => true
				),
				'multi_options'            => array(
					'text'        => __( "Features & Options" ),
					'type'        => 'multi_options',
					'name'        => 'multi_options',
					'icon'        => 'fa fa-list-ul',
					'html_tab_id' => 'features'
				),
				'technical_specifications' => array(
					'text'        => __( "Technical Specifications" ),
					'type'        => 'wp_editor',
					'name'        => 'technical_specifications',
					'icon'        => 'fa fa-cogs',
					'html_tab_id' => 'technical'
				),
				'vehicle_location'         => array(
					'text'        => __( "Vehicle Location" ),
					'type'        => 'map',
					'name'        => 'location_map',
					'icon'        => 'fa fa-map-marker',
					'hint'        => __( "Right click on the google map to store the coordinates of a location", "listings" ),
					'html_tab_id' => 'location'
				),
				'other_comments'           => array(
					'text'        => __( "Other Comments" ),
					'type'        => 'wp_editor',
					'name'        => 'other_comments',
					'icon'        => 'fa fa-comments-o',
					'html_tab_id' => 'comments'
				)
			);
		}

		public function get_listing_tabs() {
			return apply_filters( 'automotive_listing_tabs', $this->get_raw_listing_tabs() );
		}

		public function get_vehicle_history_link( $id, $post_meta ) {
			$history_link     = '#';
			$autocheck_report = automotive_listing_get_option( 'autocheck_report', false );
			$carfax_linker    = automotive_listing_get_option( 'carfax_linker', false );
			$is_autocheck     = ( $autocheck_report ? true : false );

			if ( ! $is_autocheck ) {
				$user_link    = ( isset( $carfax_linker['url'] ) && ! empty( $carfax_linker['url'] ) ? $carfax_linker['url'] : '' );
				$vin_category = ( isset( $carfax_linker['category'] ) && ! empty( $carfax_linker['category'] ) ? $carfax_linker['category'] : '' );
				$vin_value    = ( isset( $post_meta[ $vin_category ] ) && ! empty( $post_meta[ $vin_category ] ) ? $post_meta[ $vin_category ] : '' );

				$history_link = str_replace( '{vin}', $vin_value, $user_link );
			} else {
				$autocheck_page = automotive_listing_get_option( 'autocheck_page', false );

				if ( ! empty( $autocheck_page ) ) {
					$history_link = add_query_arg( 'listing_id', $id, get_permalink( $autocheck_page ) );
				}
			}

			return trim( $history_link );
		}

		public function autocheck_get_report() {
			$listing_id = ( isset( $_GET['listing_id'] ) && ! empty( $_GET['listing_id'] ) ? $_GET['listing_id'] : false );
			$return     = __( "No listing selected", "listings" );

			if ( ! empty( $listing_id ) ) {
				$listing = get_post( $listing_id );

				if ( ! empty( $listing ) ) {
					$autocheck_vin_category = automotive_listing_get_option( 'autocheck_vin_category', false );
					$listing_vin_value      = get_post_meta( $listing_id, $autocheck_vin_category, true );

					$return = $this->autocheck_report( $listing_vin_value );

				} else {
					$return = __( "Listing doesn't exist", "listings" );
				}
			}

			echo $return;
		}

		public function autocheck_report( $vin ) {
			$cid  = automotive_listing_get_option( 'autocheck_cid', '' );
			$pwd  = automotive_listing_get_option( 'autocheck_pwd', '' );
			$sid  = automotive_listing_get_option( 'autocheck_sid', '' );
			$lang = automotive_listing_get_option( 'autocheck_lang', '' );

			$post_data = array(
				'VIN' => $vin,
				'CID' => $cid,
				'PWD' => $pwd,
				'SID' => $sid
			);

			if ( ! empty( $lang ) && $lang == "ES" ) {
				$post_data['LANG'] = $lang;
			}

			//build the post string
			$poststring = http_build_query( $post_data );

			ob_start();

			$ch = curl_init();

			// set URL and other appropriate options
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
			curl_setopt( $ch, CURLOPT_URL, "https://www.autocheck.com/DealerWebLink.jsp" );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $poststring );

			// grab URL and pass it to the browser
			curl_exec( $ch );

			// close CURL resource, and free up system resources
			curl_close( $ch );

			$return = ob_get_clean();

			return $return;
		}

		public function photoswipe_js_element() {
			$show_all_photos = automotive_listing_get_option( 'show_all_photos', false );

			?>
      <!-- Root element of PhotoSwipe. Must have class pswp. -->
      <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
						 It's a separate element, as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

          <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
          <div class="pswp__container">
            <!-- don't modify these 3 pswp__item elements, data is added later on -->
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
          </div>

          <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
          <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">
							<?php if ( $show_all_photos ) { ?>
                <a class="photoswipe-all-photos" href='#'>
                  All Photos (<span class="total-photos"></span>)
                </a>
							<?php } ?>

              <div class="pswp__counter<?php echo( $show_all_photos ? ' show-all-offset' : '' ); ?>"></div>

              <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

              <button class="pswp__button pswp__button--share" title="Share"></button>

              <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

              <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

              <!-- element will get class pswp__preloader--active when preloader is running -->
              <div class="pswp__preloader">
                <div class="pswp__preloader__icn">
                  <div class="pswp__preloader__cut">
                    <div class="pswp__preloader__donut"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
              <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
              <div class="pswp__caption__center"></div>
            </div>

          </div>

        </div>

      </div>
			<?php
		}

		public function carfax_badge_api_enabled() {
			return automotive_listing_get_option( 'carfax_badge_api', false );
		}

		public function get_carfax_badge( $listing ) {
			$badge_data = $this->get_carfax_badge_data( $listing );

			return $badge_data;
		}

		public function get_carfax_badge_data( $listing ) {
			$return     = array( false, __( "CarFax Badge API Not Enabled", "listings" ) );
			$carfax_api = $this->carfax_badge_api_enabled();

			if ( $carfax_api ) {

				$carfax_account_number = automotive_listing_get_option( 'carfax_badge_api_account_id', '' );
				$carfax_lang           = automotive_listing_get_option( 'carfax_badge_api_account_lang', 'EN' );
				$carfax_vin_category   = automotive_listing_get_option( 'carfax_badge_api_vin_category', '' );
				$carfax_token          = automotive_listing_get_option( 'carfax_badge_api_account_token', '' );
				$vin                   = $listing->get_term( $carfax_vin_category );
				$carfax_cache_key      = 'carfax_badge_' . $vin;

				// cache carfax results in db for 24hrs
				if ( false === ( $badge_result = get_transient( $carfax_cache_key ) ) ) {
					$carfax_result = wp_remote_get( 'https://badgingapi.carfax.ca/badges/' . $vin . '?accountNumber=' . $carfax_account_number . '&language=' . $carfax_lang, array(
						'headers' => array(
							'WebServiceToken' => $carfax_token
						)
					) );

					if ( ! is_wp_error( $carfax_result ) ) {
						$response_body = wp_remote_retrieve_body( $carfax_result );
						$badge_result  = json_decode( $response_body );
					} else {
						$return[1] = $carfax_result->get_error_message();
					}

					set_transient( $carfax_cache_key, $badge_result, DAY_IN_SECONDS );
				}

				if ( ! is_wp_error( $badge_result ) ) {
					if ( isset( $badge_result->ResultCode ) && $badge_result->ResultCode !== 1 ) {
						$return[1] = $badge_result->ResultMessage;
					} elseif ( isset( $badge_result->BadgesList ) ) {
						$return = array( true, $badge_result->BadgesList );
					} elseif ( isset( $badge_result->badgesList ) ) {
						$return = array( true, $badge_result->badgesList );
					}
				}
			}

			return $return;
		}
	}
}

$Automotive_Plugin_Listings = null;
if ( ! function_exists( 'Automotive_Plugin' ) ) {
	function Automotive_Plugin() {
		global $Automotive_Plugin_Listings;

		if ( $Automotive_Plugin_Listings == null ) {
			$Automotive_Plugin_Listings = new Listing();
		}

		return $Automotive_Plugin_Listings;
	}
}
