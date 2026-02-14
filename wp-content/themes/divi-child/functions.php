<?php
function my_theme_enqueue_styles() { 
    $theme_version = wp_get_theme()->get('Version');

    /**
     * Enqueue external CSS libraries
     */
    wp_enqueue_style( 'slickslidercss', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css');
    wp_enqueue_style( 'slickthemecss', 'https://kenwheeler.github.io/slick/slick/slick-theme.css');
    wp_enqueue_style( 'swiperslidercss', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css');

    /**
     * Enqueue Styles
     */
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'latest-style', get_stylesheet_directory_uri() . '/latest.css?unique='.time());
    wp_enqueue_style( 'main-css-style', get_stylesheet_directory_uri() . '/assets/css/main.css?unique='.time());
    wp_enqueue_style( 'new-css-style', get_stylesheet_directory_uri() . '/assets/new-css/css/style.css?unique='.time());
    
    /**
     * Enqueue Fonts
     */
    wp_enqueue_style('inter-font', 'https://fonts.googleapis.com/css2?family=Inter&display=swap', array(), null);
    wp_enqueue_style('source-sans-pro-font', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap', array(), null);
    
    /**
     * Enqueue external JS
     */
	wp_enqueue_script('jquery');
    wp_enqueue_script('slickscript', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' , array('jquery'), false, true);
    wp_enqueue_script('swiperscript', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js' , array('jquery'), false, true);
// 	wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/e13987972f.js' , array('jquery'), false, true);
	wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/f3a2c6247d.js' , array('jquery'), false, true);
	// wp_enqueue_script( 'complyauto', 'https://cdn.complyauto.com/cookiebanner/banner/3b5141e3-3b58-4358-b8b2-cee4bf99020c/blocker.js', [], false, true );
	// wp_enqueue_script( 'complyautobanner', 'https://cdn.complyauto.com/cookiebanner/banner.js', [], false, true );

	wp_enqueue_script('complyauto', 'https://cdn.complyauto.com/cookiebanner/banner/3b5141e3-3b58-4358-b8b2-cee4bf99020c/blocker.js' , array('jquery'), false, false);

	
    if( is_singular( 'listings' ) 
    || is_page('beyond-value-listing') 
    || is_page('vehicles-for-you') ) {
        wp_enqueue_style( 'select2-style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_script('select2-script', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), false, true);
    }

    /**
     * Enqueue JS
     */
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri().'/assets/js/sliders.js', array('jquery'), false, true);

    if ( is_page('used-vehicles-durango-colorado') 
    || is_page('new-vehicles-durango-colorado')
    || is_page('kia') ) {
        wp_enqueue_script('inventory-js', get_stylesheet_directory_uri() . '/assets/js/inventory.js?unique='.time(), array('jquery', 'slickscript'), false, true);
        wp_localize_script('inventory-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
	
	if( is_singular('listings') ) {
		wp_enqueue_script('vdp-js', get_stylesheet_directory_uri().'/assets/js/vdp.js?unique='.time(), array('jquery'), false, true);
		wp_localize_script('vdp-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
	}
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/custom.js?unique='.time(), array('jquery'), false, true);


    /**
     * Add defer attributes in script
     */
    wp_script_add_data( 'slickscript', 'async', true );
    wp_script_add_data( 'swiperscript', 'async', true );
    wp_script_add_data( 'custom-js', 'defer', true );
    wp_script_add_data( 'custom', 'defer', true );
	// wp_script_add_data( 'complyautobanner', 'data-cacookieconsent-id', '3b5141e3-3b58-4358-b8b2-cee4bf99020c' );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


function custom_enqueue_fontawesome() {
    wp_enqueue_style(
        'fontawesome',
        'https://use.fontawesome.com/releases/v6.0.0/css/all.css',
        array(),
        '6.0.0'
    );
}
add_action('wp_enqueue_scripts', 'custom_enqueue_fontawesome');

/** Footer Scripts */
function dmc_footer_script() { ?>
	<script src="https://cdn.complyauto.com/cookiebanner/banner.js" data-cacookieconsent-id="3b5141e3-3b58-4358-b8b2-cee4bf99020c"></script>
<?php }

add_action( 'wp_footer', 'dmc_footer_script' );

/**
 * Include required files on init
 */
function divi_child_include_extra_files() {
    require_once get_stylesheet_directory() . '/custom-templates/productCard.php';
	require_once get_stylesheet_directory() . '/custom-templates/new-product-card.php';
	// require_once get_stylesheet_directory() . '/custom-templates/kia-inventory.php';
    require_once get_stylesheet_directory() . '/custom-templates/vehicleDetails.php';
    require_once get_stylesheet_directory() . '/custom-templates/inventoryFilters.php';
    require_once get_stylesheet_directory() . '/custom-templates/breadcrumbs.php';
    require_once get_stylesheet_directory() . '/custom-templates/predefinedColors.php';
    require_once get_stylesheet_directory() . '/likedVehiclesInfo.php';
    require_once get_stylesheet_directory() . '/constants.php';
    require_once get_stylesheet_directory() . '/resetRecentViews.php';
    require_once get_stylesheet_directory() . '/inc/compare-popup.php';
    require_once get_stylesheet_directory() . '/inc/csv-logs.php';
    require_once get_stylesheet_directory() . '/inc/misc.php';
    require_once 'template-parts/nav-menu.php';
    require_once 'template-parts/dropdown.php';
    require_once 'template-parts/testimonial-slider.php';
    require_once 'template-parts/featured_inventory.php';
    require_once 'template-parts/kia_inventory.php';
//  require_once 'custom-templates/kia-inventory.php';   
    require_once 'template-parts/car-services.php';
    require_once 'template-parts/browse-inventory.php';
    require_once 'template-parts/dgo-popup-slider.php';
}
add_action( 'init', 'divi_child_include_extra_files', 30 );


/**
 * Load dynamic musthaves for VPD page (My Garage widget)
 */
add_action( 'wp_ajax_get_selected_musthaves', 'get_selected_musthaves_callback' );
add_action( 'wp_ajax_nopriv_get_selected_musthaves', 'get_selected_musthaves_callback' );
function get_selected_musthaves_callback() {
    $selectedVehicleType = isset($_POST['vehicle']) ? array_map('sanitize_text_field',$_POST['vehicle']) : null;
    $isVDP = isset($_POST['isVDP']) ? filter_var($_POST['isVDP'], FILTER_VALIDATE_BOOLEAN) : false; 
   $args = array(
    'post_type' => 'listings',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'type-of-vehicle',
            'value' => $selectedVehicleType,
        ),
    ),
  );

  $musthaveQuery = get_posts($args); // get posts matching args
  $musthaveResponse = [];
  $priceArr = [];
  $minPrice = 0;
  $maxPrice = 0;
  if( !empty($musthaveQuery) ) {
    foreach( $musthaveQuery as $musthave ) {
        $features = get_post_meta($musthave->ID, 'features', true);
        $vehiclePrice = get_post_meta($musthave->ID, 'miscprice-1', true);
        $vehiclePrice = intval($vehiclePrice); // Convert to integer
        if (!empty($vehiclePrice) && is_numeric($vehiclePrice)) {
            $priceArr[] = $vehiclePrice;
        }
        $features = explode('|', $features);
        $features = array_map('trim', $features);
        $musthaveResponse = array_unique(array_merge($musthaveResponse, $features));
        // Remove empty values from $musthaveResponse
        $musthaveResponse = array_filter($musthaveResponse);
    }
    // Min and Max price for My Garage section in VDP page
    $minPrice = min($priceArr);
    $maxPrice = max($priceArr);
    if($isVDP) {
        // Limit the array to 10 items only for the my garage section in vdp page
        $musthaveResponse = array_slice($musthaveResponse, 0, 10);
    }else {
        // Limit the array to 50 items only for beyond value popup in bvdp page
        $musthaveResponse = array_slice($musthaveResponse, 0, 50);
    }
    $musthaveResponse = implode(',', $musthaveResponse);
  }else {
    $musthaveResponse = 'Sorry no options available';
  }

  echo json_encode(array('featureList' => $musthaveResponse, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice));
   
   wp_die();
}

// Remove <p> and <br/> from Contact Form 7
add_filter('wpcf7_autop_or_not', '__return_false');

/**
 * Add option for Durango Panel in dashboard
 */
if(function_exists("register_options_page")) {
	register_options_page('Generel Options');
    register_options_page('Our Services Page');
    register_options_page('Inventory Page');
	register_options_page('New Inventory Page');
    register_options_page('Managers Special');
    register_options_page('EV Specials');
    register_options_page('VDP Page');
	register_options_page('DGO Autogear Page');
	acf_set_options_page_title( __('Durango Panel') );
}

if( function_exists('acf_set_options_page_title') )
{
	acf_set_options_page_title( __('Durango Panel') );
}
//  Inventory Banners Admin Menu (ACF v5)
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title'  => 'Inventory Banners',
        'menu_title'  => 'Inventory Banners',
        'menu_slug'   => 'inventory-banners',
        'capability'  => 'manage_options',
        'redirect'    => false,
        'position'    => 25,
        'icon_url'    => 'dashicons-format-image'
    ]);
}

// Apply vehicle condition filter
function apply_vehicle_condition_filters(&$args, &$filterArgs, $path, $condition) {
    $conditions = [
        'new' => 'N',
        'pre-owned' => 'U',
        'certified' => ['N', 'U'],
    ];

    if (isset($conditions[$condition]) && !empty($conditions[$condition])) {
        $args['meta_query'][] = [
            'key' => 'condition',
            'value' => $conditions[$condition],
            'compare' => '=',
        ];
		
		$filterArgs['meta_query'][] = [
			'key' => 'condition',
            'value' => $conditions[$condition],
            'compare' => '=',
		];

        if ($condition === 'certified') {
            $args['meta_query'][] = [
                'key' => 'certified',
                'value' => 'yes',
                'compare' => '=',
            ];
			$filterArgs['meta_query'][] = [
				'key' => 'certified',
                'value' => 'yes',
                'compare' => '=',
			];
        }
    }
}

// Apply filters to query
function apply_filters_to_query(&$args, &$filterArgs, $filters, $path) {
    $filter_mappings = [
        'year' => ['key' => 'year', 'compare' => 'IN'],
        'make' => ['key' => 'make', 'compare' => 'IN'],
        'model' => ['key' => 'model', 'compare' => 'IN'],
        'trim' => ['key' => 'series', 'compare' => 'IN'],
		'series' => ['key' => 'series', 'compare' => 'IN'],
		'body-style' => ['key' => 'body-style', 'compare' => 'IN'],
		'type-of-vehicle' => ['key' => 'type-of-vehicle', 'compare' => 'IN'],
        'doors' => ['key' => 'doors', 'compare' => 'IN'],
        'cylinders' => ['key' => 'cylinders', 'compare' => 'IN'],
        'drivetrain' => ['key' => 'drivetrain', 'compare' => 'IN'],
        'certified' => ['key' => 'certified', 'compare' => 'IN'],
        'certificationArr' => ['key' => 'certification', 'compare' => 'IN'],
        'fuel-type' => ['key' => 'fuel-type', 'compare' => 'IN'],
'price' => $path === "new-vehicles-durango-colorado" ? ['key' => 'miscprice-1', 'compare' => 'BETWEEN', 'type' => 'NUMERIC'] : ['key' => 'original_price', 'compare' => 'BETWEEN', 'type' => 'NUMERIC'],
		'features'	=> ['key' => 'features', 'compare' => 'IN'],
		'engine'	=> ['key' => 'engine', 'compare' => 'IN']
    ];
	
// 	$args['meta_query'] = ['relation' => 'AND'];
//     $filterArgs['meta_query'] = ['relation' => 'AND'];
	
    foreach ($filter_mappings as $filter_key => $filter) {
        if (!empty($filters[$filter_key])) {
            $args['meta_query'][] = array_merge($filter, ['value' => $filters[$filter_key]]);
			$filterArgs['meta_query'][] = array_merge($filter, ['value' => $filters[$filter_key]]);
        }
    }
	
	if (!empty($filters['search'])) {
        $searchValue = $filters['search'];
        $searchFields = [
            ['key' => 'make', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'model', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'stock-number', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'vin-number', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'year', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'body-style', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'type-of-vehicle', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'doors', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'transmission', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'fuel-type', 'compare' => 'LIKE', 'value' => $searchValue],
            ['key' => 'postName', 'compare' => 'LIKE', 'value' => $searchValue],
        ];
        // Add the search fields to meta_query with OR relationship
        $args['meta_query'][] = [
            'relation' => 'OR',
            $searchFields[0], // First condition
            $searchFields[1], // Second condition
            $searchFields[2], // Third condition
            $searchFields[3], // Fourth condition
            $searchFields[4],
            $searchFields[5],
            $searchFields[6],
            $searchFields[7],
            $searchFields[8],
            $searchFields[9],
            $searchFields[10],
            $searchFields[11],
            $searchFields[12],
        ];
		$filterArgs['meta_query'][] = [
            'relation' => 'OR',
            $searchFields[0], // First condition
            $searchFields[1], // Second condition
            $searchFields[2], // Third condition
            $searchFields[3], // Fourth condition
            $searchFields[4],
            $searchFields[5],
            $searchFields[6],
            $searchFields[7],
            $searchFields[8],
            $searchFields[9],
            $searchFields[10],
            $searchFields[11],
            $searchFields[12],
        ];
    }
	
	/** Add Transmission Filter */
	if( ! empty( $filters['transmission'] ) ) {
		$transmission_value = $filters['transmission'];
		
// 		if( ! is_array( $transmission_value ) ) {
// 			$transmission_value = [ $transmission_value ];
// 		}
		
		$query = ['relation' => 'OR'];
		
		foreach( $transmission_value as $tranmission ) {
			if( 'other' === $tranmission ) {
				$query[] = [
					'relation' => 'AND',
					[
						'key' => 'transmission',
						'value' => 'automatic',
						'compare' => 'NOT LIKE'
					],
					[
						'key' => 'transmission',
						'value' => 'manual',
						'compare' => 'NOT LIKE'
					],
					[
						'key' => 'transmission',
						'value' => 'cvt',
						'compare' => 'NOT LIKE'
					]
				];
			} else {
				$query[] = [
					'key'	=> 'transmission',
					'value'	=> $tranmission,
					'compare'	=> 'LIKE'
				];	
			}
		}
		
		$args['meta_query'][] = $query;
		$filterArgs['meta_query'][] = $query;
	}

    if (!empty($filters['exterior-color'])) {
        $colorValue = $filters['exterior-color'];

        if (!is_array($colorValue)) {
            $colorValue = [$colorValue];
        }

        $query = ['relation' => 'OR'];

        foreach ($colorValue as $color) {
            $query[] = [
                'key' => 'exterior-color',
                'value' => $color,
                'compare' => 'LIKE',
            ];
        }

        $args['meta_query'][] = $query;
		$filterArgs['meta_query'][] = $query;
    }

    if (!empty($filters['interior-color'])) {
        $colorValue = $filters['interior-color'];

        if (!is_array($colorValue)) {
            $colorValue = [$colorValue];
        }

        $query = ['relation' => 'OR'];

        foreach ($colorValue as $color) {
            $query[] = [
                'key' => 'interior-color',
                'value' => $color,
                'compare' => 'LIKE',
            ];
        }

        $args['meta_query'][] = $query;
		$filterArgs['meta_query'][] = $query;
    }
	
	// Handle price range filter
	if( $path === 'new-vehicles-durango-colorado' ) {
	if (!empty($filters['price'])) {
		$price_value = (array) $filters['price'];
		if (count($price_value) === 2) {
			$args['meta_query'][] = [
				'key' => 'miscprice-1',
				'value' => [$price_value[0], $price_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'miscprice-1',
				'value' => [$price_value[0], $price_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
		} elseif (count($price_value) === 1) {
			$args['meta_query'][] = [
				'key' => 'miscprice-1',
				'value' => $price_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'miscprice-1',
				'value' => $price_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
		}
	}	
	} else if( $path === 'used-vehicles-durango-colorado' ) {
	if (!empty($filters['price'])) {
		$price_value = (array) $filters['price'];
		if (count($price_value) === 2) {
			$args['meta_query'][] = [
				'key' => 'original_price',
				'value' => [$price_value[0], $price_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'original_price',
				'value' => [$price_value[0], $price_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
		} elseif (count($price_value) === 1) {
			$args['meta_query'][] = [
				'key' => 'original_price',
				'value' => $price_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'original_price',
				'value' => $price_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
		}
	}	
	}
	
	// Handle mileage filter
	if (!empty($filters['mileage'])) {
		$mileage_value = (array) $filters['mileage'];
		if (count($mileage_value) === 2) {
			$args['meta_query'][] = [
				'key' => 'odometer',
				'value' => [$mileage_value[0], $mileage_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'odometer',
				'value' => [$mileage_value[0], $mileage_value[1]],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
		} elseif (count($mileage_value) === 1) {
			$args['meta_query'][] = [
				'key' => 'odometer',
				'value' => $mileage_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
			$filterArgs['meta_query'][] = [
				'key' => 'odometer',
				'value' => $mileage_value[0],
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
		}
	}
	
	/** Handle sorting filter */
	if (!empty($filters['sort'])) {
		$value = $filters['sort'];

		if ($value === 'low-to-high' && $path === 'new-vehicles-durango-colorado') {
			$args['meta_key'] = 'miscprice-1';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		} elseif($value === 'low-to-high' && in_array($path, ['used-vehicles-durango-colorado', 'kia'])) {
			$args['meta_key'] = 'original_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		} elseif ($value === 'high-to-low' && $path === 'new-vehicles-durango-colorado') {
			$args['meta_key'] = 'miscprice-1';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		} elseif( $value === 'high-to-low' && in_array($path, ['used-vehicles-durango-colorado', 'kia']) ) {
			$args['meta_key'] = 'original_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		} elseif ($value === 'mileage-lowest') {
			$args['meta_key'] = 'odometer';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		} elseif ($value === 'mileage-highest') {
			$args['meta_key'] = 'odometer';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		} elseif ($value === 'year-lowest') {
			$args['meta_key'] = 'year';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		} elseif ($value === 'year-highest') {
			$args['meta_key'] = 'year';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		} elseif ($value === 'listings-a-z') {
			$args['meta_key'] = 'make';
			$args['orderby'] = 'meta_value';
			$args['order'] = 'ASC';
		} elseif ($value === 'listings-z-a') {
			$args['meta_key'] = 'make';
			$args['orderby'] = 'meta_value';
			$args['order'] = 'DESC';
		} elseif ($value === 'listing-date-new' || $value === 'listing-new-to-old') {
			$args['meta_key'] = 'inventory-date';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		} elseif ($value === 'listing-date-old') {
			$args['meta_key'] = 'inventory-date';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		} else {
			$args['orderby'] = [
				'meta_value' => 'DESC',
				'date' => 'DESC'
			];
			$args['order'] = 'DESC';
		}
	}
}

// Generate filter values
function generate_filter_values($query, $applied_filter = '', $path) {
    $filterValues = [];
	if( in_array($path, ['used-vehicles-durango-colorado', 'kia']) ) {
		$meta_keys = [
			'year', 'make', 'model', 'body-style', 'type-of-vehicle', 'doors', 'cylinders',
			'drivetrain', 'transmission', 'certified', 'fuel-type', 'exterior-color', 'interior-color',
			'odometer', 'original_price', 'condition', 'series', 'engine', 'features', 'series'
		];	
	} elseif( $path === 'new-vehicles-durango-colorado' ) {
		$meta_keys = [
			'year', 'make', 'model', 'body-style', 'type-of-vehicle', 'doors', 'cylinders',
			'drivetrain', 'transmission', 'certified', 'fuel-type', 'exterior-color', 'interior-color',
			'odometer', 'miscprice-1', 'condition', 'series', 'engine', 'features', 'series'
		];	
	}
	
    foreach ($query->posts as $post) {
        foreach ($meta_keys as $key) {
			$temp_key = $key;
			if( $temp_key === 'exterior-color' ) {
				$temp_key = 'exterior-color';
			} else if( $temp_key === 'interior-color' ) {
				$temp_key = 'interior-color';
			}
			if( $applied_filter === $temp_key ) continue;
            $value = get_post_meta($post->ID, $key, true);
            if ( $value !== 'None') {
                if (!isset($filterValues[$key])) {
                    $filterValues[$key] = [];
                }
				
				/** Handle Transmission Filter Values */
				if ($key === 'transmission') {
					$lowerValue = strtolower($value);

					if (stripos($lowerValue, 'automatic') !== false) {
						$value = 'automatic';
					} elseif (stripos($lowerValue, 'manual') !== false) {
						$value = 'manual';
					} elseif (stripos($lowerValue, 'cvt') !== false) {
						$value = 'cvt';
					} else {
						$value = 'other';
					}
				}

				if ($key === 'exterior-color' || $key === 'interior-color') {
					$returnedColor = preDefinedColors(strtolower($value));
					$colorKey = $returnedColor['key'] ?? '';
					$color_value = $returnedColor['value'] ?? 'white';
					if ($colorKey === '') {
						$colorArr = preg_split('/[ \/]/', $value);
						foreach ($colorArr as $color) {
							$tempColor = preDefinedColors(strtolower($color));
							if (!is_null($tempColor)) {
								$colorKey = $tempColor['key'];
								$color_value = $tempColor['value'];
								break;
							}
						}
					}
					
					if (!in_array($color_value, array_column($filterValues[$key], 'color'))) {
						$filterValues[$key][] = [
							'color' => $color_value,
							'keycode' => $colorKey
						];
					}
				} else {
					if (!in_array($value, $filterValues[$key])) {
						$filterValues[$key][] = $value;
					}
				}
            }
        }
    }

    // Sort values
    foreach ($filterValues as $key => $values) {
        if (in_array($key, ['year', 'cylinders', 'doors'])) {
            rsort($values);
        } else {
            sort($values);
        }
		$filterValues[$key] = $values;
    }

    return $filterValues;
}

add_action('wp_ajax_Get_Ajax_Filters', 'Get_Ajax_Filters_callback');
add_action('wp_ajax_nopriv_Get_Ajax_Filters', 'Get_Ajax_Filters_callback');

function Get_Ajax_Filters_callback() {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Invalid Request');
    }

    // Sanitize and validate inputs
    $filters = [
        'year' => isset($_POST['year']) ? array_map('sanitize_text_field', $_POST['year']) : [],
        'make' => isset($_POST['make']) ? array_map('sanitize_text_field', $_POST['make']) : [],
        'model' => isset($_POST['model']) ? array_map('sanitize_text_field', $_POST['model']) : [],
        'type-of-vehicle' => isset($_POST['type-of-vehicle']) ? array_map('sanitize_text_field', $_POST['type-of-vehicle']) : [],
        'transmission' => isset($_POST['transmission']) ? array_map('sanitize_text_field', $_POST['transmission']) : [],
        'doors' => isset($_POST['doors']) ? array_map('sanitize_text_field', $_POST['doors']) : [],
        'cylinders' => isset($_POST['cylinders']) ? array_map('sanitize_text_field', $_POST['cylinders']) : [],
        'drivetrain' => isset($_POST['drivetrain']) ? array_map('sanitize_text_field', $_POST['drivetrain']) : [],
        'certified' => isset($_POST['certified']) ? array_map('sanitize_text_field', $_POST['certified']) : [],
        'certificationArr' => isset($_POST['certificationArr']) ? array_map('sanitize_text_field', $_POST['certificationArr']) : [],
        'fuel-type' => isset($_POST['fuel-type']) ? array_map('sanitize_text_field', $_POST['fuel-type']) : [],
        'body-style' => isset($_POST['body-style']) ? array_map('sanitize_text_field', $_POST['body-style']) : [],
        'mileage' => isset($_POST['mileageRange']) ? array_map('trim', explode(',', $_POST['mileageRange'])) : [],
        'price' => isset($_POST['priceRange']) ? array_map('trim', explode(',', $_POST['priceRange'])) : [],
        'exterior-color' => $_POST['exterior-color'] ?? [],
        'interior-color' => $_POST['interior-color'] ?? [],
        'sort' => $_POST['sort'] ?? '',
        'search' => $_POST['search'] ?? '',
        'scroll' => $_POST['scroll'] ?? '',
        'vehicleCondition' => sanitize_text_field($_POST['vehicleCondition'] ?? 'pre-owned'),
        'trim' => isset($_POST['trim']) ? array_map( 'sanitize_text_field', $_POST['trim'] ) : [],
        'path' => isset($_POST['path']) ? str_replace('/', '', sanitize_text_field($_POST['path'])) : '',
        'features' => isset($_POST['features']) ? array_map('sanitize_text_field', $_POST['features']) : [],
        'engine' => isset($_POST['engine']) ? array_map('sanitize_text_field', $_POST['engine']) : [],
        'applied_filter' => isset($_POST['appliedFilter']) ? sanitize_text_field($_POST['appliedFilter']) : ''
    ];

    if (empty($filters['path'])) {
        wp_send_json_error('Path cannot be empty');
    }

    // Kia page: force make=Kia and condition=pre-owned (no URL params, used vehicles design)
    if ($filters['path'] === 'kia') {
        $filters['make'] = ['Kia'];
        $filters['vehicleCondition'] = 'pre-owned';
    }
	
	$window_width 	= isset( $_POST['windowWidth'] ) ? intval( $_POST['windowWidth'] ) : 0;
	$is_scroll_mode = isset($_POST['scroll']) && $_POST['scroll'] === 'true';
	$requested_paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

	$default_posts_per_page = 14;
	if ( $window_width >= 1800 ) {
		$default_posts_per_page = 18;
	} elseif ( $window_width >= 990 && $window_width <= 1440 ) {
		$default_posts_per_page = 16;
	} else {
		$default_posts_per_page = 14;
	}

	// For "Show All"/append mode, always load 6 cards per request.
	$posts_per_page = $is_scroll_mode ? 6 : $default_posts_per_page;

    // Build query arguments
    $args = [
        'posts_per_page' => $posts_per_page,
        'post_type' => 'listings',
        'paged' => $requested_paged,
        'orderby' => [
            'meta_value' => in_array($filters['path'], ['used-vehicles-durango-colorado', 'kia']) ? 'ASC' : 'DESC',
            'date' => in_array($filters['path'], ['used-vehicles-durango-colorado', 'kia']) ? 'ASC' : 'DESC'
        ],
        'order' => in_array($filters['path'], ['used-vehicles-durango-colorado', 'kia']) ? 'ASC' : 'DESC',
        'meta_query' => ['relation' => 'AND'],
    ];

	// Keep append results continuous after initial first page (14/16/18 cards).
	if ($is_scroll_mode && $requested_paged >= 2) {
		$args['offset'] = $default_posts_per_page + (($requested_paged - 2) * $posts_per_page);
		$args['paged'] = 1;
	}

    $filter_args = [
        'posts_per_page' => -1,
        'post_type' => 'listings',
        'paged' => 1,
        'orderby' => ['meta_value' => 'DESC', 'date' => 'DESC'],
        'order' => 'DESC',
        'meta_query' => ['relation' => 'AND'],
    ];

    // Apply filters based on vehicle condition and path
    apply_vehicle_condition_filters(
        $args,
        $filter_args,
        $filters['path'],
        $filters['vehicleCondition']
    );

    // Apply additional filters
    apply_filters_to_query($args, $filter_args, $filters, $filters['path']);

    // Execute the query
    $latestListings = new WP_Query($args);
    $filter_latest_listings = new WP_Query($filter_args);

    if ($latestListings->have_posts()) {
		        $productCards = [];
        $index        = 0;

        // Read banners from ACF (same as initial load)
        $acf_banners      = function_exists('get_field') ? get_field('inline_banners', 'inventory-banners') : [];
        $filtered_banners = [];

        if (!empty($acf_banners) && is_array($acf_banners)) {
            foreach ($acf_banners as $banner) {
                $show_on = isset($banner['show_on']) ? $banner['show_on'] : '';
                if ($show_on === 'both' ||
					($show_on === 'new_inventory' && $filters['path'] === 'new-vehicles-durango-colorado') ||
					($show_on === 'used_inventory' && in_array($filters['path'], ['used-vehicles-durango-colorado', 'kia'])) ||
					empty($show_on)
				) {
					$filtered_banners[] = $banner;
				}
            }
        }

        // Pick exactly 2 banners (if available)
        shuffle($filtered_banners);
        $selectedBanners = array_slice($filtered_banners, 0, 2);

        $totalPosts     = $latestListings->post_count;
        $bannerCount    = 2; // 2 banners per page
        $bannerPositions = [];

        // Positions 0–13 (because you show 14 cards max)
        $availablePositions = range(0, 13);
        shuffle($availablePositions);
        $bannerPositions = array_slice($availablePositions, 0, $bannerCount);

        $listingCards = [];

//         $productCards = [];
        $index = 0;
        $banner_index = isset($_POST['banner_index']) ? intval($_POST['banner_index']) : 0;
//         $banners = [
//             '7-1-25-Ford-Best-Price_300x900.webp',
//             '7-1-25-DMC_Value-My-Trade_300x900.webp',
//             '7-1-25-Kia-Finance.webp',
// //             'ford-explorer-dl-300-900.jpg',
// //             'ford-f-150-lightning-dl-300-900.jpg',
// //             'toyota-tacoma-lease-300-900.jpg',
// //             'toyota-tundra-apr-cc-300-900.jpg',
// //             'kia-sportage-apr-300-900.jpg'
//         ];
//         $has_lincoln = false;
//         $totalPosts = $latestListings->post_count;
//         $bannerCount = 2; // Ensure exactly 2 banners per page
//         $bannerPositions = [];
//         while ($latestListings->have_posts()) {
//             $latestListings->the_post();
//             $make = get_post_meta(get_the_ID(), 'make', true);
//             if (strtolower($make) === 'lincoln') {
//                 $has_lincoln = true;
//                 break;
//             }
//         }
//         $latestListings->rewind_posts();
//         $availablePositions = range(0, 13); // Random positions within 14 listings
//         shuffle($availablePositions);
//         $bannerPositions = array_slice($availablePositions, 0, $bannerCount);
        // Select exactly 2 banners
        // $selectedBanners = [];
        // shuffle($banners); 
        // if ($has_lincoln) {
        //     $selectedBanners = ['lincoln-aviator.webp', $banners[0]];
        // } else {
        //     $selectedBanners = array_slice($banners, 0, 2); 
        // }

//         shuffle($banners);
//         $selectedBanners = array_slice($banners, 0, 2);

//         if ($has_lincoln) {
            
//         }


        $listingCards = [];
        while ($latestListings->have_posts()) {
            $latestListings->the_post();
            if ($filters['path'] === 'new-vehicles-durango-colorado') {
                $listingCards[] = dmc_new_inventory_card();
            } else if (in_array($filters['path'], ['used-vehicles-durango-colorado', 'kia'])) {
                $listingCards[] = productCard();
            }
            $vehicleMake = get_post_meta(get_the_ID(), 'make', true);
            dmc_inline_banners($index, $listingCards, $selectedBanners, $bannerPositions);
            $index++;
        }

		$calculated_max_pages = $latestListings->max_num_pages;
		if ($is_scroll_mode) {
			$remaining = max(0, $latestListings->found_posts - $default_posts_per_page);
			$calculated_max_pages = $remaining > 0 ? 1 + (int)ceil($remaining / $posts_per_page) : 1;
		}

        // Generate pagination using requested page/max page values
        $pagination_html = vehiclesPagination(
            $latestListings->found_posts,
            $latestListings->query_vars['posts_per_page'],
            $calculated_max_pages,
            $requested_paged,
            $filters['scroll']
        );

        $postCount = postCount($latestListings->found_posts);

        // Generate filter HTML
        $filterhtml = inventorySelectedFilters(
            $filters['search'],
            $filters['year'],
            $filters['make'],
            $filters['model'],
            $filters['type-of-vehicle'],
            $filters['transmission'],
            $filters['doors'],
            $filters['cylinders'],
            $filters['drivetrain'],
            $filters['certified'],
            $filters['fuel-type'],
            $filters['body-style'],
            $filters['exterior-color'],
            $filters['interior-color'],
            $filters['sort'],
            $filters['mileage'],
            $filters['price'],
            $filters['certificationArr'],
            $filters['vehicleCondition'],
            $filters['trim'],
            $filters['engine']
        );

        wp_reset_postdata();

        // Prepare response
        $response = [
            'listingContent' => $listingCards,
            'paginationHtml' => $pagination_html,
            'foundposts' => $postCount,
            'filter' => $filterhtml,
            'args' => $args,
            'currentPage' => $requested_paged,
            'filterHTML' => generate_filter_values($filter_latest_listings, $filters['applied_filter'], $filters['path']),
            'noListingsBanner' => '',
            'maxPages' => $calculated_max_pages,
            'yearArr' => $filters['year'],
            'urlQuery' => $filters,
            'scroll' => $filters['scroll']
//             'banner_index' => $filters['scroll'] === 'true' ? $banner_index + 1 : 0
        ];

        echo json_encode($response);
        wp_die();
    } else {
        echo json_encode([
            'noListingsBanner' => dmc_no_vehicles_found($filters['path']),
            'maxPages' => 1,
            'foundposts' => 12,
            'urlQuery' => $filters,
            'filterHTML' => generate_filter_values($filter_latest_listings, $filters['applied_filter'], $filters['path']),
            'currentPage' => $requested_paged,
            'filter' => '',
            'args' => $args,
            'listingContent' => '',
            'paginationHtml' => '',
            'scroll' => false,
            'banner_index' => 0
        ]);
    }

    wp_die();
}
/** Process URL Parameters */
function process_url_parameters( $url = '' ) {
	$parametersExplode	= explode('&', $url);
	$keys_to_check		= [
		'year', 'make', 'model', 'body-style', 'type-of-vehicle', 'doors', 'cylinders', 'drivetrain',
		'transmission', 'certified', 'fuel-type', 'exterior-color', 'interior-color',
		'search', 'sort', 'mileage', 'price', 'certified-pre-owned-toyota',
		'certified-pre-owned-ford', 'certified-pre-owned-kia', 'certified-pre-owned',
		'trim', 'condition', 'engine', 'features'
	];
	$decode_keys = ['engine', 'body-style', 'type-of-vehicle', 'trim', 'model', 'exterior-color', 'interior-color', 'fuel-type', 'search', 'price', 'mileage'];
	$valuesArray		= [];
	
	foreach ($parametersExplode as $parameter) {
        $parameter = strtolower($parameter);
		$equalPosition = strpos($parameter, '=');
		
		if( $equalPosition !== false ) {
			$key	= substr( $parameter, 0, $equalPosition );
			$value	= substr( $parameter, $equalPosition + 1 );
			$key	= str_replace( '[]', '', $key );

			if (in_array($key, $decode_keys)) {
                $value = urldecode($value);
            }
			
			if ($key === 'price[]') {
				if ( ! isset($valuesArray[$key]) || !is_array($valuesArray[$key]) ) {
					$valuesArray[$key] = [];
				}
				if( ! in_array( $value, $valuesArray[$key] ) ) {
					$valuesArray[$key][] = $value;	
				}
			} else if( $key === 'mileage' ) {
				if ( ! isset($valuesArray[$key]) || !is_array($valuesArray[$key]) ) {
					$valuesArray[$key] = [];
				}
				$value = explode( ',', $value );
				foreach( $value as $mil ) {
					if( ! in_array( $mil, $valuesArray[$key] ) ) {
						$valuesArray[$key][] = $mil;	
					}	
				}
			} else if( in_array( $key, $keys_to_check ) ) {
				if( ! isset( $valuesArray[$key] ) ) {
					$valuesArray[$key] = [];
				}
				if (!in_array($key, ['search', 'sort', 'condition'])) {
					$valuesArray[$key][] = $value;
				} else {
					$valuesArray[$key] = $value;
				}
			}  else {
				handle_certified_pre_owned($parameter, $keys_to_check, $valuesArray);
			}
		}
    }
	
	foreach ($valuesArray as $key => $value) {
        if (!in_array(strtolower($key), ['search', 'sort', 'condition'])) {
			if( ! is_array( $value ) ) {
				$value = [ $value ];
			}
            $valuesArray[$key] = array_unique($value);
        }
    }
			
	return $valuesArray;
}

/** Handle certified pre owned */
function handle_certified_pre_owned($parameter, $keys_to_check, &$valuesArray) {
	$certifiedMappings = [
		'certified-pre-owned-toyota' => ['Toyota Certified Used Vehicles', 'Toyota', 'Toyota Gold Certified'],
		'certified-pre-owned-ford' => ['Ford Gold Certified', 'Ford Blue Advantage: Blue Certified', 'Ford Blue Certified'],
		'certified-pre-owned-kia' => ['Kia Certified Pre-Owned'],
	];

	if (isset($certifiedMappings[$parameter])) {
		if (!isset($valuesArray['make'])) {
			$valuesArray['make'] = [];
		}
		if (!isset($valuesArray['certification'])) {
			$valuesArray['certification'] = [];
		}
		$valuesArray['certification'] = array_merge($valuesArray['certification'], $certifiedMappings[$parameter]);
		$valuesArray['make'][] = str_replace('certified-pre-owned-', '', $parameter);
	} elseif ($parameter === 'certified-pre-owned') {
		if (!isset($valuesArray['certified'])) {
			$valuesArray['certified'] = [];
		}
		$valuesArray['certified'][] = 'yes';
	}
}

/** Apply filters based on value */
function apply_filters_based_on_values($args, $valuesArray, $pathname) {
    $filters = [
        'year' => ['key' => 'year', 'compare' => 'IN'],
        'make' => ['key' => 'make', 'compare' => 'IN'],
        'model' => ['key' => 'model', 'compare' => 'IN'],
		'trim' => ['key' => 'series', 'compare' => 'IN'],
        'body-style' => ['key' => 'body-style', 'compare' => 'IN'],
        'type-of-vehicle' => ['key' => 'type-of-vehicle', 'compare' => 'IN'],
        'doors' => ['key' => 'doors', 'compare' => 'IN'],
        'cylinders' => ['key' => 'cylinders', 'compare' => 'IN'],
        'drivetrain' => ['key' => 'drivetrain', 'compare' => 'IN'],
        'transmission' => ['key' => 'transmission', 'compare' => 'LIKE'],
        'certified' => ['key' => 'certified', 'compare' => 'IN'],
        'fuel-type' => ['key' => 'fuel-type', 'compare' => 'IN'],
        'exterior-color' => ['key' => 'exterior-color', 'compare' => 'LIKE'],
        'interior-color' => ['key' => 'interior-color', 'compare' => 'LIKE'],
        'price' => $pathname === 'new-vehicles-durango-colorado' ? ['key' => 'miscprice-1', 'compare' => 'BETWEEN', 'type' => 'NUMERIC'] : ['key' => 'original_price', 'compare' => 'BETWEEN', 'type' => 'NUMERIC'],
		'engine' => ['key' => 'engine', 'compare' => 'IN'],
    ];
	
	if( $pathname === 'new-vehicles-durango-colorado' ) {
		if( ! empty( $valuesArray['price'] ) && is_array( $valuesArray['price'] ) ) {
			if( count( $valuesArray['price'] ) === 1 ) {
				$minPrice = min( $valuesArray['price'] );
				$args['meta_query'][] = [
					'key' => 'miscprice-1',
					'value' => $minPrice,
					'compare' => '>=',
					'type' => 'NUMERIC'
				];
			} else if( count( $valuesArray['price'] ) === 2 ) {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$args['meta_query'][] = [
					'key' => 'miscprice-1',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				];
			} else {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$args['meta_query'][] = [
					'key' => 'miscprice-1',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				];
			}
		}	
	}
	
	if( in_array($pathname, ['used-vehicles-durango-colorado', 'kia']) ) {
		if( ! empty( $valuesArray['price'] ) && is_array( $valuesArray['price'] ) ) {
			if( count( $valuesArray['price'] ) === 1 ) {
				$minPrice = min( $valuesArray['price'] );
				$args['meta_query'][] = [
					'key' => 'original_price',
					'value' => $minPrice,
					'compare' => '>=',
					'type' => 'NUMERIC'
				];
			} else if( count( $valuesArray['price'] ) === 2 ) {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$args['meta_query'][] = [
					'key' => 'original_price',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				];
			} else {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$args['meta_query'][] = [
					'key' => 'original_price',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				];
			}
		}	
	}
	
	if( ! empty( $valuesArray['mileage'] ) && is_array( $valuesArray['mileage'] ) ) {
		if( count( $valuesArray['mileage'] ) === 1 ) {
			$minMileage = min( $valuesArray['mileage'] );
			$args['meta_query'][] = [
				'key' => 'odometer',
				'value' => $minMileage,
				'compare' => '>=',
				'type' => 'NUMERIC'
			];
		} else if( count( $valuesArray['mileage'] ) === 2 ) {
			$minMileage = min($valuesArray['mileage']);
			$maxMileage = max($valuesArray['mileage']);

			$args['meta_query'][] = [
				'key' => 'odometer',
				'value' => [$minMileage, $maxMileage],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
		} else {
			$minMileage = min($valuesArray['odometer']);
			$maxMileage = max($valuesArray['odometer']);

			$args['meta_query'][] = [
				'key' => 'odometer',
				'value' => [$minMileage, $maxMileage],
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			];
		}
	}
	
	if( ! empty( $valuesArray['search'] ) ) {
		$searchValue = $valuesArray['search'];
		
		$searchFields = [
			['key' => 'make', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'model', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'stock-number', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'vin-number', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'year', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'body-style', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'type-of-vehicle', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'doors', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'transmission', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'exterior-color', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'interior-color', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'fuel-type', 'compare' => 'LIKE', 'value' => $searchValue],
			['key' => 'postName', 'compare' => 'LIKE', 'value' => $searchValue],
		];

		// Add the search fields to meta_query with OR relationship
		$args['meta_query'][] = [
			'relation' => 'OR',
			$searchFields[0], // First condition
			$searchFields[1], // Second condition
			$searchFields[2], // Third condition
			$searchFields[3], // Fourth condition
			$searchFields[4],
			$searchFields[5],
			$searchFields[6],
			$searchFields[7],
			$searchFields[8],
			$searchFields[9],
			$searchFields[10],
			$searchFields[11],
			$searchFields[12],
		];
	}
	
	if( isset( $valuesArray['sort'] ) && !empty($valuesArray['sort']) ) {
        if($valuesArray['sort'] == 'low-to-high' && in_array($pathname, ['used-vehicles-durango-colorado', 'kia'])){
            $args['meta_key'] = 'original_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } elseif ($valuesArray['sort'] == 'low-to-high' && $pathname === 'new-vehicles-durango-colorado'){
            $args['meta_key'] = 'miscprice-1';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } else if($valuesArray['sort'] == 'high-to-low' && in_array($pathname, ['used-vehicles-durango-colorado', 'kia'])){
            $args['meta_key'] = 'original_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if($valuesArray['sort'] == 'high-to-low' && $pathname === 'new-vehicles-durango-colorado'){
            $args['meta_key'] = 'miscprice-1';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if( $valuesArray['sort'] == 'mileage-lowest' ) {
            $args['meta_key'] = 'odometer';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }else if( $valuesArray['sort'] == 'mileage-highest' ) {
            $args['meta_key'] = 'odometer';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }else if( $valuesArray['sort'] == 'year-lowest' ) {
            $args['meta_key'] = 'year';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }else if( $valuesArray['sort'] == 'year-highest' ) {
            $args['meta_key'] = 'year';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }else if( $valuesArray['sort'] == 'listings-a-z' ) {
            $args['meta_key'] = 'make';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
        }else if( $valuesArray['sort'] == 'listings-z-a' ) {
            $args['meta_key'] = 'make';
            $args['order'] = 'DESC';
            $args['orderby'] = 'meta_value';
        }else if( $valuesArray['sort'] == 'listing-date-new' ) {
            $args['meta_key'] = 'inventory-date';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }else if( $valuesArray['sort'] == 'listing-date-old' ) {
            $args['meta_key'] = 'inventory-date';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }else if( $valuesArray['sort'] == 'listing-new-to-old' ) {
            $args['meta_key'] = 'inventory-date';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }
    }
	
	// Handle color filters
	if (!empty($valuesArray['exterior-color'])) {
		$colorValue = $valuesArray['exterior-color'];

		if (!is_array($colorValue)) {
			$colorValue = [$colorValue];
		}

		$query = ['relation' => 'OR'];

		foreach ($colorValue as $color) {
			$query[] = [
				'key' => 'exterior-color',
				'value' => $color,
				'compare' => 'LIKE',
			];
		}

		$args['meta_query'][] = $query;
	}
	
	if (!empty($valuesArray['interior-color'])) {
		$colorValue = $valuesArray['interior-color'];

		if (!is_array($colorValue)) {
			$colorValue = [$colorValue];
		}

		$query = ['relation' => 'OR'];

		foreach ($colorValue as $color) {
			$query[] = [
				'key' => 'interior-color',
				'value' => $color,
				'compare' => 'LIKE',
			];
		}

		$args['meta_query'][] = $query;
	}

    foreach ($filters as $key => $filter) {
        if (!empty($valuesArray[$key])) {
			$value = $valuesArray[$key];
			
			// Handle BETWEEN comparison
            if ($filter['compare'] === 'BETWEEN') {
                if (is_array($value) && isset($value['min']) && isset($value['max'])) {
                    $filter['value'] = [$value['min'], $value['max']];
                } else {
                    continue; // Skip if value is not in the correct format
                }
            } elseif ($filter['compare'] === 'LIKE') {
                $filter['value'] = $value;
            } elseif ($filter['compare'] === 'IN') {
                $filter['value'] = is_array($value) ? $value : [$value];
            } else {
                $filter['value'] = $value;
            }
			$args['meta_query'][] = $filter;
//             $args['meta_query'][] = array_merge($filter, ['value' => $valuesArray[$key]]);
        }
    }
	
    return $args;
}

/** Generate filter array */
function generate_filter_array($valuesArray, $pathname) {
    $filters = [
        'year' => print_checkbox_filters('year', 'year', 'year', $valuesArray, $pathname),
        'make' => print_checkbox_filters('make', 'make', 'make', $valuesArray, $pathname),
        'model' => print_checkbox_filters('model', 'model', 'model', $valuesArray, $pathname),
        'trim' => print_checkbox_filters('series', 'trim', 'series', $valuesArray, $pathname),
        'features' => print_checkbox_filters('features', 'features', 'features', $valuesArray, $pathname),
        'engine' => print_checkbox_filters('engine', 'engine', 'engine', $valuesArray, $pathname),
        'body-style' => print_checkbox_filters('body-style', 'body-style', 'body-style', $valuesArray, $pathname),
        'type-of-vehicle' => print_checkbox_filters('type-of-vehicle', 'type-of-vehicle', 'type-of-vehicle', $valuesArray, $pathname),
        'doors' => print_checkbox_filters('doors', 'doors', 'doors', $valuesArray, $pathname),
        'cylinders' => print_checkbox_filters('cylinders', 'cylinders', 'cylinders', $valuesArray, $pathname),
        'drivetrain' => print_checkbox_filters('drivetrain', 'drivetrain', 'drivetrain', $valuesArray, $pathname),
        'transmission' => print_checkbox_filters('transmission', 'transmission', 'transmission', $valuesArray, $pathname),
        'certified' => print_checkbox_filters('certified', 'certified', 'certified', $valuesArray, $pathname),
        'fuel-type' => print_checkbox_filters('fuel-type', 'fuel-type', 'fuel-type', $valuesArray, $pathname),
        'exterior-color' => print_color_filters('exterior-color', 'exterior-color', $valuesArray, $pathname),
        'interior-color' => print_color_filters('interior-color', 'interior-color', $valuesArray, $pathname),
        'price' => $pathname === 'new-vehicles-durango-colorado' ? print_range_filters('miscprice-1', 'price', 'price', $valuesArray, $pathname) : print_range_filters('original_price', 'price', 'price', $valuesArray, $pathname),
        'mileage' => print_range_filters('odometer', 'mileage', 'mileage', $valuesArray, $pathname),
    ];

    return array_map('json_decode', $filters);
}

/** Get user compared vehicles */
function get_user_compared_vehicles() {
    $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
    $comparedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
    $updateResult = accessWPDB()->get_row($comparedQuery, ARRAY_A);
    return $updateResult ? unserialize($updateResult['user_compared_vehicles']) : [];
}

/** Load inventory vehicles on page load */
add_action( 'wp_ajax_loadInventoryVehicles', 'loadInventoryVehicles_callback' );
add_action( 'wp_ajax_nopriv_loadInventoryVehicles', 'loadInventoryVehicles_callback' );
function loadInventoryVehicles_callback() {
	
// 	ini_set('display_errors', 1);
// 	ini_set('display_startup_errors', 1);
// 	error_reporting(E_ALL);
	
    if( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_send_json_error('Invalid request');
    }
    
	$pathname 		= isset( $_POST['path'] ) ? str_replace('/','', sanitize_text_field( $_POST['path'] ) ) : '';
	$window_width 	= isset( $_POST['windowWidth'] ) ? intval( $_POST['windowWidth'] ) : 0;
    $paged			= isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
	$url			= isset( $_POST['url'] ) ? urldecode( $_POST['url'] ) : '';
// 	$url = (isset($_POST['url']) && is_string($_POST['url']))
//     ? urldecode($_POST['url'])
//     : '';

// 	$posts_per_page = 14;
// 	if ( $window_width >= 1800 ) {
// 		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 19 : 18;
// 	} elseif ( $window_width >= 990 && $window_width <= 1399 ) {
// 		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 17 : 16;
// 	} else {
// 		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 15 : 14;
// 	}
	
	$posts_per_page = 14;
	if ( $window_width >= 1800 ) {
		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 18 : 18;
	} elseif ( $window_width >= 990 && $window_width <= 1399 ) {
		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 16 : 16;
	} else {
		$posts_per_page = $pathname === 'new-vehicles-durango-colorado' ? 14 : 14;
	}
	
	if( empty( $pathname ) ) wp_send_json_error('Pathname cannot be empty');

	$args = array(
        'posts_per_page' => $posts_per_page,
        'post_type' 	 => 'listings',
        'paged' 		 => $paged,
        'orderby'        => array(
            'meta_value_num' => 'ASC',
            'date'           => 'ASC',
        ),
        'order'          => 'ASC',
		'meta_query'	 => ['relation' => 'AND']
    );
	
	$valuesArray		= process_url_parameters( $url );
	
    if ($pathname === 'new-vehicles-durango-colorado') {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'N', 'compare' => '='];
    }
    if (in_array($pathname, ['used-vehicles-durango-colorado', 'kia'])) {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'U', 'compare' => '='];
    }
    if ($pathname === 'kia') {
        $args['meta_query'][] = ['key' => 'make', 'value' => 'Kia', 'compare' => '='];
    }
	
	$args = apply_filters_based_on_values($args, $valuesArray, $pathname);
	$filterArr = generate_filter_array($valuesArray, $pathname);
	$listing = new WP_Query($args);
	
	if ($listing->have_posts()) {
        $productCards = [];
		$index		  = 0;
		
// 		$banners = [
// 			'7-1-25-Ford-Best-Price_300x900.webp',
// 			'7-1-25-DMC_Value-My-Trade_300x900.webp',
// 			'7-1-25-Kia-Finance.webp',
// // 			'ford-explorer-dl-300-900.jpg',
// // 			'ford-f-150-lightning-dl-300-900.jpg',
// // 			'toyota-tacoma-lease-300-900.jpg',
// // 			'toyota-tundra-apr-cc-300-900.jpg',
// // 			'kia-sportage-apr-300-900.jpg'
// 		];
	$acf_banners = get_field('inline_banners', 'option');
	$filtered_banners = [];

	if (!empty($acf_banners)) {
		foreach ($acf_banners as $banner) {
			// Optional page filtering if you added "show_on" field
			if (!empty($banner['show_on'])) {
				if (
					$banner['show_on'] === 'both' ||
					($banner['show_on'] === 'new_inventory' && $pathname === 'new-vehicles-durango-colorado') ||
					($banner['show_on'] === 'used_inventory' && in_array($pathname, ['used-vehicles-durango-colorado', 'kia']))
				) {
					$filtered_banners[] = $banner;
				}
			} else {
				// If no "show_on" field exists yet, allow all banners
				$filtered_banners[] = $banner;
			}
		}
	}

	// Randomly pick EXACTLY 2 banners
	shuffle($filtered_banners);
	$selectedBanners = array_slice($filtered_banners, 0, 2);

// 		$has_lincoln = false;
		$totalPosts = $listing->post_count;
		$bannerCount = 2; // Ensure exactly 2 banners per page
		$bannerPositions = [];
		
// 		while ($listing->have_posts()) {
// 			$listing->the_post();
// 			$make = get_post_meta(get_the_ID(), 'make', true);
// 			if (strtolower($make) === 'lincoln') {
// 				$has_lincoln = true;
// 				break;
// 			}
// 		}
		
// 		$listing->rewind_posts();
		
		$availablePositions = range(0, 13); // Random positions within 14 listings
		shuffle($availablePositions);
		$bannerPositions = array_slice($availablePositions, 0, $bannerCount);

		// Select exactly 2 banners
		// $selectedBanners = [];
		// shuffle($banners);
		// if ($has_lincoln) {
		// 	$selectedBanners = ['lincoln-aviator.webp', $banners[0]];
		// } else {
		// 	$selectedBanners = array_slice($banners, 0, 2);
		// }

//         shuffle($banners);
//         $selectedBanners = array_slice($banners, 0, 2);

//         if ($has_lincoln) {

//         }

        
		
        while ($listing->have_posts()) {
            $listing->the_post();
            $productCard = $pathname === 'new-vehicles-durango-colorado' ? dmc_new_inventory_card() : productCard();
            $productCards[] = $productCard;
			$vehicleMake = get_post_meta( get_the_ID(), 'make', true );
			
			dmc_inline_banners($index, $productCards, $selectedBanners, $bannerPositions);
			$index++;
        }

        $pagination = vehiclesPagination($listing->found_posts, $listing->query_vars['posts_per_page'], $listing->max_num_pages, $listing->query_vars['paged'], false);
        $postCount = postCount($listing->found_posts);
        wp_reset_postdata();

        $user_compared_vehicles = get_user_compared_vehicles();

        $filtersHTML = inventorySelectedFilters(
            $valuesArray['search'] ?? '',
            $valuesArray['year'] ?? [],
            $valuesArray['make'] ?? [],
            $valuesArray['model'] ??[],
            $valuesArray['type-of-vehicle'] ?? [],
            $valuesArray['transmission'] ?? [],
            $valuesArray['doors'] ?? [],
            $valuesArray['cylinders'] ?? [],
            $valuesArray['drivetrain'] ?? [],
            $valuesArray['certified'] ?? [],
            $valuesArray['fuel-type'] ?? [],
            $valuesArray['body-style'] ?? [],
            $valuesArray['exterior-color'] ?? [],
            $valuesArray['interior-color'] ?? [],
            $valuesArray['sort'] ?? '',
            $valuesArray['mileage'] ?? [],
            $valuesArray['price'] ?? [],
            $valuesArray['certribution'] ?? [],
            $valuesArray['condition'] ?? '',
			$valuesArray['trim'] ?? '',
			$valuesArray['engine'] ?? []
        );

        $response = [
            'productCards' => $productCards,
            'pagination' => $pagination,
            'postCount' => $postCount,
            'args' => $args,
            'maxPages' => $listing->max_num_pages,
            'values' => $valuesArray,
			'url'	=> $url,
			'noListingsBanner' => ''
        ];

        echo json_encode([
            'response' => $response,
            'filters' => $filterArr,
            'filterHTML' => $filtersHTML,
            'comparedVehiclesCount' => count($user_compared_vehicles),
        ]);
    } else {
		echo json_encode( [
			'response'			=> [ 
				'noListingsBanner' => dmc_no_vehicles_found( $pathname ),
				'maxPages'		   => 1,
				'postCount'		   => 12,
				'values' => $valuesArray,
				'args' => $args
			]
		] );
    }
	
	wp_die();       
}

/** Inventory InlineBanners */
// function dmc_inline_banners($index, &$productCards, $selectedBanners, $bannerPositions) {
// 	if (in_array($index, $bannerPositions)) {
//         $bannerIndex = array_search($index, $bannerPositions);
//         if (isset($selectedBanners[$bannerIndex])) {
//             $bannerImg = $selectedBanners[$bannerIndex];
// 			$bannerLink = 'javascript:void(0)';
			
// 			if( $bannerImg === '7-1-25-Ford-Best-Price_300x900.webp' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?make%5B%5D=ford&model=f-150';
// 			} else if( $bannerImg === '7-1-25-DMC_Value-My-Trade_300x900.webp' ) {
// 				$bannerLink = '/value-trade-in';
// 			} else if( $bannerImg === '7-1-25-Kia-Finance.webp' ) {
// 				$bannerLink = '/value-trade-in';
// 			} else if( $bannerImg === 'ford-explorer-dl-300-900.jpg' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?search=f4650';
// 			} else if( $bannerImg === 'ford-f-150-lightning-dl-300-900.jpg' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?search=F4576';
// 			} else if( $bannerImg === 'toyota-tacoma-lease-300-900.jpg' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?model=tacoma&year%5B%5D=2025&trim=sr5';
// 			} else if( $bannerImg === 'toyota-tundra-apr-cc-300-900.jpg' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?model=tundra&year%5B%5D=2025&drivetrain%5B%5D=4wd';
// 			} else if( $bannerImg === 'kia-sportage-apr-300-900.jpg' ) {
// 				$bannerLink = '/new-vehicles-durango-colorado/?model%5b%5d=sportage+hybrid';
// 			}
			
//             $productCards[] = sprintf(
//                 '<div class="col-12 col-lg-6 col-xl-4 col-xxl-3 mb-30">
//                     <div class="position-relative mb-3 mb-md-0 bg-white listing-card-wrapper p-3 flex-column inline-banner" style="display: flex !important;">
// 						<a href="%s" class="d-flex align-items-start h-100">
// 							<img src="%s/wp-content/themes/divi-child/assets/images/%s" 
// 								 alt="Banner" 
// 								 class="w-100 h-100 object-fit-contain" 
// 								 loading="lazy" />
// 						</a>
// 						<!-- 
// 						<a href="%1$s" class="inline-banner-link mt-3 btn btn-primary">
// 							View Inventory
// 						</a>
// 						-->
//                     </div>
//                 </div>',
// 				esc_url($bannerLink),
//                 site_url(),
//                 $bannerImg
//             );
//         }
//     }
// }
function dmc_inline_banners($index, &$productCards, $selectedBanners, $bannerPositions) {

    if (!in_array($index, $bannerPositions, true)) return;

    $bannerIndex = array_search($index, $bannerPositions, true);
    if ($bannerIndex === false || empty($selectedBanners[$bannerIndex])) return;

    $banner = $selectedBanners[$bannerIndex];

    // ✅ Handle ACF Image Array Safely
    $bannerImageData = $banner['banner_image'] ?? null;
    $bannerLink      = $banner['banner_link'] ?? '#';

    if (empty($bannerImageData) || empty($bannerImageData['url'])) return;

    $bannerImgUrl = esc_url($bannerImageData['url']);
    $bannerAlt    = esc_attr($bannerImageData['alt'] ?? 'Inventory Banner');

    $productCards[] = '
    <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-30">
        <div class="position-relative bg-white listing-card-wrapper p-3 inline-banner d-flex align-items-center justify-content-center">
            <a href="'.$bannerLink.'" class="w-100 h-100 d-flex align-items-center justify-content-center">
                <img src="'.$bannerImgUrl.'" 
                     alt="'.$bannerAlt.'"
                     class="w-100 h-100 object-fit-contain" 
                     loading="lazy" />
            </a>
        </div>
    </div>';
}



// $acf_banners = get_field('inline_banners', 'acf-options-inventory-page');

// $filtered_banners = [];

// if (!empty($acf_banners)) {
//     foreach ($acf_banners as $banner) {
//         if (
//             empty($banner['show_on']) ||
//             $banner['show_on'] === 'both' ||
//             ($banner['show_on'] === 'new_inventory' && $pathname === 'new-vehicles-durango-colorado') ||
//             ($banner['show_on'] === 'used_inventory' && $pathname === 'used-vehicles-durango-colorado')
//         ) {
//             $filtered_banners[] = $banner;
//         }
//     }
// }

// shuffle($filtered_banners);
// $selectedBanners = array_slice($filtered_banners, 0, 2);

// $bannerPositions = range(3, 10);
// shuffle($bannerPositions);
// $bannerPositions = array_slice($bannerPositions, 0, 2);

/** No vehicles found for applied filters */
function dmc_no_vehicles_found( $path = '' ) {
	ob_start(); ?>
<div class="no-listings-found mt-30 d-flex justify-content-center align-items-center p-4 bg-transparent">
	<div class="no-listings-found__inner">
		<h2 class="color_black no-listings-found__text font-weight-bold font-helvetica p-0 m-0 text-center">
			<?php echo esc_html( 'Sorry, no listings found matching your search result.
				Please try with different search or contact sales.' ); ?>
		</h2>
	</div>
</div>
<div class="mt-30">
	<h2 class="color_black font_helvetica text_capitalize text_center p_0 relatedlistingsHeadingtext">
		<?php echo esc_html( 'Here are some other vehicles you may be interested in:' ); ?>
	</h2>
	<a href="<?php echo esc_url( site_url() . '/' . $path ); ?>"
	   class="d_block text_center relatedlistingslink text_uppercase">
		<?php echo esc_html( 'View all used inventory' ); ?>
	</a>
</div>

<?php
	$args = [
		'post_type' => 'listings',
		'posts_per_page' => 12,
		'post_status' => 'publish',
		'orderby'        => array(
			'meta_value' => 'DESC', 
			'date'       => 'DESC',
		),
		'order'          => 'DESC',
	];
	
	$no_listings_query = new WP_Query( $args );
	if( $no_listings_query->have_posts() ) {
		echo '<div class="row">';
		while( $no_listings_query->have_posts() ) {
			$no_listings_query->the_post();
			
			echo $path === 'used-vehicles-durango-colorado' ? productCard() : dmc_new_inventory_card();
		}
		echo '</div>';
		wp_reset_postdata();
	}
	
	
	?>
<?php return ob_get_clean(); }

// Function to load media gallery videos
add_action('wp_ajax_load_media_gallery_data', 'load_media_gallery_data_callback');
add_action('wp_ajax_nopriv_load_media_gallery_data', 'load_media_gallery_data_callback');

function load_media_gallery_data_callback() {
    $mediaGalleryField = get_field('media_gallery_videos_section', 'options');
    $mediaGalleryArray = [];

    if (!empty($mediaGalleryField) && isset($mediaGalleryField)) {
        $mediaMatch = false;
        foreach ($mediaGalleryField as $field) {
            $mediaGallerModel = (!empty($field['media_gallery_vehicle_model']) ? $field['media_gallery_vehicle_model'] : '');

            if (!empty($mediaGallerModel) && strtolower($mediaGallerModel) == $_POST['activePostModel']) {
                $mediaGalleryVideosRow = $field['media_gallery_videos_group']['media_gallery_videos_row'];

                if (isset($mediaGalleryVideosRow) && !empty($mediaGalleryVideosRow)) {
                    foreach ($mediaGalleryVideosRow as $mediaGalleryVideoRow) {
                        $mediaGalleryVideo = $mediaGalleryVideoRow['media_gallery_single_video_group'];
                        $mediaGalleryVideoTitle = $mediaGalleryVideo['media_gallery_video_title'];
                        $mediaGalleryVideoCategory = $mediaGalleryVideo['media_gallery_video_category'];
                        $mediaGalleryVideoDesc = $mediaGalleryVideo['media_gallery_video_description'];
                        $mediaGalleryVideoAttachment = (!empty($mediaGalleryVideo['media_gallery_video_thumbnail']) ? wp_get_attachment_image_src($mediaGalleryVideo['media_gallery_video_thumbnail'], 'full') : '');
                        $mediaGalleryAlt = get_post_meta($mediaGalleryVideo['media_gallery_video_thumbnail'], '_wp_attachment_image_alt', true);
                        $mediaGalleryWidth = $mediaGalleryVideoAttachment[1];
                        $mediaGalleryHeight = $mediaGalleryVideoAttachment[2];
                        $mediaGalleryURL = $mediaGalleryVideoAttachment[0];
                        $mediaGalleryVideoURL = $mediaGalleryVideo['media_gallery_video_url'];
                        $newArray = array($mediaGalleryURL, $mediaGalleryVideoURL, $mediaGalleryVideoCategory, $mediaGalleryVideoTitle, $mediaGalleryVideoDesc);

                        $alreadyExists = false;
                        foreach ($mediaGalleryArray as $arr) {
                            if ($arr == $newArray) {
                                $alreadyExists = true;
                                break;
                            }
                        }

                        if (!$alreadyExists) {
                            $mediaGalleryArray[] = $newArray;
                        }
                    }
                }
                $mediaMatch = true;
            }
        }
        if( !$mediaMatch ) {
            $defaultMedia = get_field('beyond_value_default_media_gallery_row', 'options');
            $mediaGalleryArray = array();
            foreach( $defaultMedia as $media ) {
                $mediaGroup = $media['beyond_value_default_media_gallery_group'];
                $mediaTitle = $mediaGroup['default_media_gallery_video_title'];
                $mediaCategory = $mediaGroup['default_media_gallery_video_category'];
                $mediaDesc = $mediaGroup['default_media_gallery_video_description'];
                $mediaThumbnail = wp_get_attachment_image_src($mediaGroup['default_media_gallery_video_thumbnail'], 'full');
                $mediaThumbURL = $mediaThumbnail[0];
                $mediaThumbnailWidth = $mediaThumbnail[1];
                $mediaThumbnailHeight = $mediaThumbnail[2];
                $mediaThumbnailAlt = get_post_meta($mediaThumbnail, '_wp_attachment_image_alt', true);
                $mediaVideoUrl = $mediaGroup['default_media_gallery_video_url'];
                $mediaGalleryArray[] = array($mediaThumbURL, $mediaVideoUrl, $mediaCategory, $mediaTitle, $mediaDesc);
            }
        }

        $html = '';
        foreach ($mediaGalleryArray as $array) {
            $thumbnail = isset($array[0]) && strlen(trim($array[0])) > 0 ? $array[0] : "http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg";
            $category = isset($array[2]) && strlen(trim($array[2])) > 0 ? "<span class='media-gallery-post-cat-text'>".$array[2]."</span>" : '';
            $title = isset($array[3]) && strlen(trim($array[3])) > 0 ? $array[3] : 'Coming soon';
            $description = isset($array[4]) && strlen(trim($array[4])) > 0 ? "<p class='font-md font-helvetica text-grey-6'>".(count(explode(' ', $array[4])) > 20 ? implode(' ', array_slice(explode(' ', $array[4]), 0, 20))."..." : $array[4])."</p>" : '';
            $videoUrl = isset($array[1]) && strlen(trim($array[1])) > 0 ? $array[1] : 'https://www.youtube.com/embed/uk-mrLV7QXc';

            $html .= "<div class='media-card position-relative shadow-third'>";
            $html .= "<div class='media-card-thumbnail position-relative d-flex align-items-center justify-content-center'>";
            $html .= "<img class='media-gallery-popup-trigger w-100 h-100 object_fit_cover' src='$thumbnail' title='iframe title' data-url='$videoUrl' width='349' height='196' loading='lazy'>";
            $html .= "<span class='media-gallery-popup-trigger position-absolute text-white rounded-circle-px d-flex align-items-center justify-content-center cursor-pointer' data-url='$videoUrl'><i class='fa fa-play text-white'></i></span>";
            $html .= "</div>";
            $html .= "<div class='media-card-content px-15'>";
            $html .= "<p class='mt-30 mb-1 font-sm font-helvetica text-grey-6 text-uppercase p-0'>Category: $category</p>";
            $html .= "<h2 class='m-0 font-weight-bold font-helvetica text-uppercase pb-15 text-grey-6 font-xxl'>$title</h2>";
            $html .= $description;
            $html .= "<div class='d-flex w-100 justify-content-center align-items-center position-absolute media-gallery-cta'>";
            $html .= "<a class='media-gallery-popup-trigger bg-secondary text-white rounded-circle-px font-20 font-helvetica px-20 py-15' data-url='$videoUrl'>WATCH NOW</a>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= '</div>';
        }

        echo $html;
    }

    wp_die();
}
// Function to load photo gallery images
add_action('wp_ajax_load_photo_gallery_data', 'load_photo_gallery_data_callback');
add_action('wp_ajax_nopriv_load_photo_gallery_data', 'load_photo_gallery_data_callback');

function load_photo_gallery_data_callback() {
    $galleryImagesRow = get_field('beyond_value_gallery_images_row', 'options');
    $galleryMatch = false;
        foreach( $galleryImagesRow as $row ) {
            $rowModel = $row['beyond_value_gallery_images_group']['beyond_value_gallery_images_model'];
            if( strtolower($rowModel) == $_POST['activePostModel'] ) {
                $rowImages = $row['beyond_value_gallery_images_group']['beyond_value_gallery_images_image'];
                foreach( $rowImages as $image ) {
                    $imageattachment = $image['beyond_value_gallery_slider_image'];
                    $imageattachmentID = wp_get_attachment_image_src($imageattachment, 'full');
                    if( $imageattachmentID ) {
                        $imageURL = $imageattachmentID[0];
                        $imageWidth = $imageattachmentID[1];
                        $imageHeight = $imageattachmentID[2];
                        $galleryAlt = get_post_meta($imageattachmentID, '_wp_attachment_image_alt', true);
                    }
                    if( $imageattachmentID ) {
                        echo '<img loading="lazy" class="photo-gallery-popup-image img-fluid" src="'.$imageURL.'" alt="'.$imageAlt.'" width="'.$imageWidth.'" height="'.$imageHeight.'" itemprop="image" >';
                    }
                } 
                $galleryMatch = true;
                break;
            }
        }
        if( !$galleryMatch ) {
            $galleryField = get_field('beyond_value_default_photo_gallery_row', 'options');
            if( !empty($galleryField) ) {
                foreach( $galleryField as $gallery ) {
                    $galleryGroup = wp_get_attachment_image_src($gallery['beyond_value_default_photo_gallery_image'], 'full');
                    if( !empty($galleryGroup) ) {
                        $galleryURL = $galleryGroup[0];
                        $galleryWidth = $galleryGroup[1];
                        $galleryHeight = $galleryGroup[2];
                        $galleryAlt = get_post_meta($gallery['beyond_value_default_photo_gallery_image'], '_wp_attachment_image_alt', true);
                        echo '<img loading="lazy" class="photo-gallery-popup-image img-fluid" src="'.$galleryURL.'" alt="'.$galleryAlt.'" width="'.$galleryWidth.'" height="'.$galleryHeight.'" itemprop="image" >';
                    }
                }
            }
        }
    wp_die();
}


/** Car Loans Durango CO */function dmc_car_loans_script() { ?>
<script>
    $(document).ready(function() {
    let tabChanger = $('.multistep-step-change-wrapper a');
    let tabStep = $('.multistep-step');
    let tabCol = $('.multistep-step-col');

    $(tabChanger).on('click', submitMultistep);
    $('.multistep-submit-button input[type="submit"]').on('click', submitMultistep);

    function submitMultistep(e) {
        let isSubmitButton = $(e.target).is('input[type="submit"]');
        if (!isSubmitButton) e.preventDefault(); // Prevent default only for step navigation

        let btnAttr = $(e.target).attr('data-attr');
        let btnFieldAttr = $(e.target).attr('data-field-attr');

        if (btnAttr === undefined || btnFieldAttr === undefined) return;

        let currentStep = tabStep.eq(parseInt(btnFieldAttr));
        let x = currentStep.find('.multistep-required-field');
        let allFieldsHaveValues = true;

        x.each(function() {
            let field = $(this);
            if (!field.val()) {
                if (field.next('.error-message').length == 0) {
                    field.after('<div class="error-message" style="color:red;">Please fill out this field</div>');
                }
                allFieldsHaveValues = false;
            } else {
                field.next('.error-message').remove();
            }
        });

        if (!allFieldsHaveValues) {
            x.filter(function() { return !$(this).val(); }).first().focus();
            return;
        }

            // Allow form submission
            $(e.target).closest('form').submit();
            return;
        }

        // Hide current step and show next step
        tabStep.hide();
        tabCol.removeClass('multistep-step-col-active');

        let newStepTab = tabStep.eq(parseInt(btnAttr));
        let newStepCol = tabCol.eq(parseInt(btnAttr));

        newStepTab.show();
        newStepCol.addClass('multistep-step-col-active');
    }
});
</script>
<?php }
add_action('wp_footer', 'dmc_car_loans_script');


/**
 * Reviews slider at homepage
 * */

function dmc_reviews_slider_shortcode() {
    return include get_stylesheet_directory() . '/dmc-reviews-slider.php';
}
add_shortcode('dmc_reviews_slider', 'dmc_reviews_slider_shortcode');

function dmc_reviews_slider_enqueue_scripts() {
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'dmc_reviews_slider_enqueue_scripts');



// Add mobile view tabs
function dmc_mobile_view_tabs() { ?>
	<div class="mobile-view-tabs-container container py-2 d-lg-none">
		<div class="row">
			<div class="col-3">
				<div class="mobile-view-tab d-flex flex-column align-items-center justify-content-center font-lg">
					<i class="fa-solid fa-location-dot"></i>
					<span>Location</span>
				</div>
				<!-- Tooltip -->
				<div class="mobile-view-tab-tooltip mobile-view-tooltip--directions">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3176.7395392029184!2d-107.86271022437712!3d37.23015757212767!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x873c032d14e59fd9%3A0x89966ebc49631c10!2s1200%20Carbon%20Jct%2C%20Durango%2C%20CO%2081301%2C%20USA!5e0!3m2!1sen!2s!4v1735755784681!5m2!1sen!2s" width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					<h3>
						Get Directions
					</h3>
					<p>
						1200 Carbon Junction, Durango, CO
					</p>
					<form class="get-directions" method="post">
						<label for="get-site-directions">Get Directions</label>
						<div class="get-directions-input">
							<input type="text" id="get-site-directions" placeholder="What's your address?" autocomplete="off">
							<span class="et-pb-icon"></span>
						</div>
					</form>
				</div>
			</div>
			<div class="col-3">
				<div class="mobile-view-tab d-flex flex-column align-items-center justify-content-center font-lg">
					<a href="tel:8444962224" class="d-flex flex-column align-items-center justify-content-center font-lg">
						<i class="fa-solid fa-phone"></i>
						<span>Phone</span>
					</a>
				</div>
			</div>
			<div class="col-3">
				<div class="mobile-view-tab d-flex flex-column align-items-center justify-content-center font-lg">
					<i class="fa-regular fa-clock"></i>
					<span>Sales</span>
				</div>
				<div class="mobile-view-tab-tooltip mobile-view-tooltip--clock">
					<p class="">
					<span class="working-day">monday</span>
					<span class="working-time">8:00 - 6:00 PM</span>
					</p>
					<p class="">
					<span class="working-day">tuesday</span>
					<span class="working-time">8:00 - 6:00 PM</span>
					</p>
					<p class="active">
					<span class="working-day">wednesday</span>
					<span class="working-time">8:00 - 6:00 PM</span>
					</p>
					<p class="">
					<span class="working-day">thrusday</span>
					<span class="working-time">8:00 - 6:00 PM</span>
					</p>
					<p class="">
					<span class="working-day">friday</span>
					<span class="working-time">8:00 - 6:00 PM</span>
					</p>
					<p class="">
					<span class="working-day">saturday</span>
					<span class="working-time">Closed</span>
					</p>
					<p class="">
					<span class="working-day">sunday</span>
					<span class="working-time">Closed</span>
					</p>
				</div>
			</div>
			<div class="col-3">
				<div class="mobile-view-tab d-flex flex-column align-items-center justify-content-center font-lg">
					<i class="fa-regular fa-clock"></i>
					<span>Service</span>
				</div>
				<div class="mobile-view-tab-tooltip mobile-view-tooltip--clock">
					<p class="">
						<span class="working-day">monday</span>
						<span class="working-time">7:00 - 6:00 PM</span>
					</p>
					<p class="">
						<span class="working-day">tuesday</span>
						<span class="working-time">7:00 - 6:00 PM</span>
					</p>
					<p class="active">
						<span class="working-day">wednesday</span>
						<span class="working-time">7:00 - 6:00 PM</span>
					</p>
					<p class="">
						<span class="working-day">thrusday</span>
						<span class="working-time">7:00 - 6:00 PM</span>
					</p>
					<p class="">
						<span class="working-day">friday</span>
						<span class="working-time">7:00 - 6:00 PM</span>
					</p>
					<p class="">
						<span class="working-day">saturday</span>
						<span class="working-time">Closed</span>
					</p>
					<p class="">
						<span class="working-day">sunday</span>
						<span class="working-time">Closed</span>
					</p>
				</div>
			</div>
		</div>
	</div>

	<script>
		(function($) {
			$('body').append($('.mobile-view-tabs-container'))

			let tooltipTimeout;

			$('.mobile-view-tab').hover(
				function () {
					console.log('hovered')
					clearTimeout(tooltipTimeout);
					// Remove 'active' from all tooltips
					$('.mobile-view-tab-tooltip').removeClass('active');
					$(this).next('.mobile-view-tab-tooltip').addClass('active');
				},
				function () {
					console.log('hovered out')
					const $tooltip = $(this).next('.mobile-view-tab-tooltip');
					tooltipTimeout = setTimeout(() => {
						$tooltip.removeClass('active');
					}, 500); // 2 seconds delay
				}
			);

			// Keep tooltip active on hover
			$('.mobile-view-tab-tooltip').hover(
				function () {
					clearTimeout(tooltipTimeout);
				},
				function () {
					const $tooltip = $(this);
					tooltipTimeout = setTimeout(() => {
						$tooltip.removeClass('active');
					}, 500);
				}
			);

		})(jQuery)
	</script>
<?php }

add_action( 'wp_footer', 'dmc_mobile_view_tabs' );















// Hardcoded database credentials
define('DMC_DB_HOST', 'inventory-database-do-user-2599605-0.c.db.ondigitalocean.com');
define('DMC_DB_PORT', '25060');
define('DMC_DB_USER', 'junaid');
define('DMC_DB_PASSWORD', 'AVNS_ufjqHNNhDr_Pxg4FTFN');
define('DMC_DB_NAME', 'dmc_database');

function dmc_fetch_and_store_image_urls() {
    $urls = [];
    try {
        $pdo_do = new PDO(
            "mysql:host=" . DMC_DB_HOST . ";port=" . DMC_DB_PORT . ";dbname=" . DMC_DB_NAME,
            DMC_DB_USER,
            DMC_DB_PASSWORD,
            [PDO::ATTR_TIMEOUT => 10]
        );
        $pdo_do->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT image_location FROM dmc_jellybeans";
        $stmt = $pdo_do->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $urls = array_column($results, 'image_location');
        update_option('jellyB_images', $urls, true);
    } catch (PDOException $e) {
    }
    return $urls;
}

function dmc_get_image_urls() {
    $urls = get_option('jellyB_images', []);
    if (empty($urls)) {
        $urls = dmc_fetch_and_store_image_urls();
    }
    return $urls;
}

add_action('init', 'dmc_fetch_and_store_image_urls');



function home_hero_slider_shortcode() {
    ob_start();
    get_template_part('template-parts/home-hero-slider');
    return ob_get_clean();
}
add_shortcode('home_hero_slider', 'home_hero_slider_shortcode');





function my_delete_expired_slider_rows() {
    $rows = get_field('home_hero_slider', 'option');

    if (!$rows || !is_array($rows)) {
        return;
    }

    $current_time = current_time('timestamp');
    $new_rows = [];

    foreach ($rows as $row) {
        $expire_date_time = $row['expire_date_time'] ?? '';
        $expire_timestamp = $expire_date_time ? strtotime($expire_date_time) : false;

        if (!$expire_timestamp || $expire_timestamp > $current_time) {
            $new_rows[] = $row;
        }
    }

    update_field('home_hero_slider', $new_rows, 'option');
}

if (!wp_next_scheduled('my_cleanup_expired_sliders')) {
    wp_schedule_event(time(), 'hourly', 'my_cleanup_expired_sliders'); 
}

add_action('my_cleanup_expired_sliders', 'my_delete_expired_slider_rows');


// Add CTA popup
function dmc_cta_popup() { ?>
	<div class="global_popup_wrapper custom-lead-capture-form form-cta-popup">
		<div class="form-cta-newsletter-overlay global_popup_wrapper_overlay"></div>	
		<div class="form-cta-popup-wrapper global_popup_wrapper_content-wrapper overflow-auto">
			<div class="form-cta-popup-container">
				<span class="form-cta-popup-close global_popup_wrapper_close d_flex d_flex__justify-center d_flex__align-center cursor-pointer position_absolute border_circle">
					<i class="fa fa-times" aria-hidden="true"></i>
				</span>
				<div class="global-form-wrapper">
					<div class="global-form-form">
						<?php echo do_shortcode('[contact-form-7 id="8941de4" title="Call back Form"]'); ?>
					</div>
					<div class="global-form-success d_none">
						<h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>
						<div class="d-flex justify-content-center">
							<img src="https://durangovalueautos.com/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted"></div>
						<h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>
						<p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>
						<div class="sidebar__ctas d-none">
							<a class="text_uppercase" href="https://durangovalueautos.com/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>
							<a class="text_uppercase" href="https://durangovalueautos.com/inventory">View Inventory</a>
							<a class="text_uppercase" href="https://durangovalueautos.com/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

add_action( 'wp_footer', 'dmc_cta_popup' );

function dmc_cta_popup_script() { ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const popup = document.querySelector('.form-cta-popup');
  if (!popup) return;

  const overlay = popup.querySelector('.form-cta-newsletter-overlay');
  const closeBtn = popup.querySelector('.form-cta-popup-close');

  // Show popup when any .cta-call-btn is clicked
  document.addEventListener('click', function(e) {
	  const btn = e.target.closest('.cta-call-btn');
	  if( window.innerWidth <= 575 ) return;
    if (btn) {
      e.preventDefault();
      popup.style.display = 'flex';
      popup.style.opacity = 0;
      setTimeout(() => {
        popup.style.transition = 'opacity 0.25s ease-in-out';
        popup.style.opacity = 1;
      }, 10);
      document.body.style.overflow = 'hidden'; // prevent scroll
    }
  });

  // Close popup on overlay or close button click
  [overlay, closeBtn].forEach(el => {
    el?.addEventListener('click', function() {
      popup.style.opacity = 0;
      setTimeout(() => {
        popup.style.display = 'none';
        document.body.style.overflow = '';
      }, 250);
    });
  });
});
</script>
<?php }
add_action( 'wp_footer', 'dmc_cta_popup_script', 100 );



add_action('init', function () {
    if (isset($_GET['year']) && is_array($_GET['year'])) {
        // Remove year entirely to avoid rewrite conflicts
        unset($_GET['year']);
        unset($_REQUEST['year']);
    }
}, 1); // MUST run early

add_action( 'wp_footer', 'cf7_thankyou_js' );
function cf7_thankyou_js() {
?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Create the hidden link dynamically once on page load
		if (!document.getElementById('thankyou-link')) {
			const link = document.createElement('a');
			link.id = 'thankyou-link';
			link.href = '/thank-you/';
			link.style.display = 'none';
			document.body.appendChild(link);
		}
		// Create the hidden link dynamically once on page load
		if (!document.getElementById('thankyou-link-dmc')) {
			const link = document.createElement('a');
			link.id = 'thankyou-link-dmc';
			link.href = '/thank-you-dmc/';
			link.style.display = 'none';
			document.body.appendChild(link);
		}
	});

	document.addEventListener('wpcf7mailsent', function (e) {
		const formID = e.detail.contactFormId;

		const link = document.getElementById('thankyou-link');
		const linkDMC = document.getElementById('thankyou-link-dmc');

		if ([1843].includes(formID)) {
			link?.click();
		} else {
			linkDMC?.click();
		}
	});
</script>
    <?php
}

/** Thank you functionality */
add_action('wp_footer', 'vehicle_thankyou_functionality', 999);
function vehicle_thankyou_functionality() { ?>
<script>
jQuery(document).ready(function($) {
    function captureVehicleData() {
        // Extract vehicle data from the current page
        const vehicleData = {
            image: getVehicleImage(),
            title: getVehicleTitle(),
            stock: getVehicleStock(),
            price: getVehiclePrice()
        };
        
        // Store in sessionStorage
        sessionStorage.setItem('thankYouVehicleData', JSON.stringify(vehicleData));
    }
    
    // Helper function to get vehicle image
    function getVehicleImage() {
        return $('.vehicle-thumbnail img').attr('src') || 
               $('.vehicle-thumbnail img').attr('data-src') ||
               $('.vehicle-card-info img').attr('src') ||
               $('.listing-thumbnail-image-slider .slick-active img').attr('src') ||
               $('.sticky-lead-form img').attr('src') ||
               '';
    }
    
    // Helper function to get vehicle title - FIXED
	function getVehicleTitle() {
		let $titleEl = $('.details-box h1');

		if ($titleEl.length === 0) {
			return '';
		}

		let title = $titleEl.text().trim();
		console.log(title)
		return title;
	}
    
    // Helper function to get stock number
    function getVehicleStock() {
        return $('.VDP-content-wrapper').data('stock') ||
               $('.vehicle-stock').text().replace('Stock #:', '').trim() ||
               $('.stock-number').text().trim() ||
               $('[class*="stock"]').text().replace(/Stock.*#?:?\s*/i, '').trim() ||
               '';
    }
    
    // Helper function to get price
    function getVehiclePrice() {
        let price = '';
        
        const priceElement = $('[itemprop="price"]');
        if (priceElement.length) {
            price = priceElement.attr('content') || priceElement.text();
        }
        
        if (!price) {
            price = $('.vehicle-price').text() || 
                    $('.sticky-form-price:last').text() ||
                    $('.sidebarpriceinfo:last .font-lg').text() ||
                    '';
        }
        
        return price.toString().replace(/[^0-9]/g, '');
    }


    
    $(document).on('click', '.sticky-lead-form-cta-btn, button[data-popup="sticky-cta"]', function(e) {
        const buttonText = $(this).text().toLowerCase();
        const buttonClass = $(this).attr('class') || '';
        
        if (buttonText.includes('schedule') || 
            buttonText.includes('test drive') ||
            buttonClass.includes('schedule') ||
            $(this).attr('data-popup') === 'sticky-cta') {
            
            setTimeout(captureVehicleData, 50);
        }
    });


 

    function displayVehicleDataOnThankYouPage() {
        // Check if we're on the thank you page
        const isThankYouPage = window.location.pathname.includes('/thank-you') || 
                               window.location.href.includes('/thank-you');
        
        if (!isThankYouPage) {
            return;
        }
        
        const vehicleDataStr = sessionStorage.getItem('thankYouVehicleData');
        
        if (vehicleDataStr) {
            try {
                const vehicleData = JSON.parse(vehicleDataStr);
                updateThankYouPageElements(vehicleData);
                
                // Clear sessionStorage immediately after displaying
//                 sessionStorage.removeItem('thankYouVehicleData');
                
            } catch (error) {
                sessionStorage.removeItem('thankYouVehicleData');
            }
        }
    }
    
    function updateThankYouPageElements(vehicleData) {
        // Update vehicle image
        if (vehicleData.image && vehicleData.image.trim()) {
            const imgElement = document.querySelector('.sidebar__top-img img');
            if (imgElement) {
                imgElement.src = vehicleData.image;
                imgElement.style.display = 'block';
                imgElement.alt = vehicleData.title || 'Vehicle Image';
                imgElement.title = vehicleData.title || 'Vehicle Image';
                imgElement.parentElement.style.display = 'block';
            }
        }
        
        // Update vehicle title
        if (vehicleData.title && vehicleData.title.trim()) {
            const titleElement = document.querySelector('.vehicle-title');
            if (titleElement) {
                titleElement.textContent = vehicleData.title;
            }
        }
        
        // Update stock number
        if (vehicleData.stock && vehicleData.stock.trim()) {
            const stockElement = document.querySelector('.vehicle-stock');
            if (stockElement) {
                stockElement.textContent = 'Stock #: ' + vehicleData.stock;
            }
        }
        
        // Update price
        if (vehicleData.price && vehicleData.price.trim()) {
            const priceNum = parseInt(vehicleData.price);
            if (!isNaN(priceNum) && priceNum > 0) {
                const formattedPrice = priceNum.toLocaleString();
                
                const priceElement = document.querySelector('[itemprop="price"]');
                if (priceElement) {
                    priceElement.textContent = formattedPrice;
                    priceElement.setAttribute('content', formattedPrice);
                }
                
                const priceDisplay = document.querySelector('.vehicle-price');
                if (priceDisplay) {
                    priceDisplay.innerHTML = '$' + ' ' + formattedPrice;
                }
            }
        }
    }

        const vehicleDataStr = sessionStorage.getItem('thankYouVehicleData');
    if(vehicleDataStr){
 $('.abcclass').parent().css('display','block');
$('.et_pb_column_1').css('width','50%');
      }
        
        // Hide the left column if no vehicle data
        if (!vehicleDataStr) {
             $('.abcclass').parent().hide(); // Hide the left column
$('.et_pb_column_1').css('width','100%');
    $('.et_pb_image_0').css({
        display: 'flex',
        'justify-content': 'center',
        'align-items': 'center' // optional, centers vertically too
    });

            return;
        }
    
    // ============================================
    // 7. INITIALIZE ON PAGE LOAD
    // ============================================
    
    // Check if we're on thank you page and display data
    setTimeout(function() {
        displayVehicleDataOnThankYouPage();
    }, 300);
    
    // Also check when page fully loads
    $(window).on('load', function() {
        displayVehicleDataOnThankYouPage();
    });
    
});
</script>
    <?php
}


