<?php
/**
 * Filter vehicles based on provided values
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_dmc_load_inventory_vehicles', 'dmc_load_inventory_vehicles_callback' );
add_action( 'wp_ajax_nopriv_dmc_load_inventory_vehicles', 'dmc_load_inventory_vehicles_callback' );
function dmc_load_inventory_vehicles_callback() {

    if( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_send_json_error( 'Invalid Request' );
    }

    $pathname       = isset( $_POST['path'] ) ?
		str_replace('/','', sanitize_text_field( $_POST['path'] ) ) : '';
	$window_width   = isset( $_POST['windowWidth'] ) ? intval( $_POST['windowWidth'] ) : 1799;
    $paged          = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;

    if( empty( $pathname ) ) wp_send_json_error('Pathname cannot be empty');

    $args = [
        'posts_per_page'    => $window_width <= 1800 ? 12 : 15,
        'post_type'         => 'listings',
        'paged'             => $paged,
        'orderby'           => [
            'meta_value'    => 'DESC',
            'date'          => 'DESC'
        ],
        'order'             => 'DESC',
        'meta_query'        => [
            'relation'      => 'AND'
        ]
    ];

    $parameters         = urldecode( $_POST['url'] );
    $parameters_explode = explode( '&', $parameters );
    $keys_to_check      = [ 
        'year',
        'make',
        'model',
        'body_style',
        'type_of_vehicle',
        'doors',
        'cylinders',
        'drivetrain',
        'transmission',
        'certified',
        'fuel_type',
        'exterior_color',
        'interior_color',
        'search',
        'sort',
        'mileage',
        'final_price',
        'certified-pre-owned-toyota',
        'certified-pre-owned-ford',
        'certified-pre-owned-kia',
        'certified-pre-owned',
        'trim',
        'condition',
        'engine',
        'features'
    ];

    $values_array = [];

    foreach( $parameters_explode as $parameter ) {
        $parameter = strtolower($parameter);

        foreach( $keys_to_check as $key ) {
            $equal_position = strpos($parameter, '=');
            if( $equal_position !== false ) {
                $sub_parameter  = substr($parameter, 0, $equal_position);

                if( strpos( $sub_parameter, $keys_to_check ) !== false ) {
                    $values = substr( $parameter, strpos( $parameter, '=' ) + 1 );

                    if( ! isset( $values_array[ $keys_to_check ] ) ) {
                        $values_array[$keys_to_check] = [];
                    }

                    if(
                        $keys_to_check !== 'search'
                        && $keys_to_check !== 'sort'
                        && $keys_to_check !== 'final_price'
                        && $keys_to_check !== 'mileage'
                    ) {
                        $values_array[$keys_to_check][] = $values;
                    } else {
                        $values_array[$keys_to_check] = $values;
                    }
                }
            } else {
                if( $keys_to_check === 'certified-pre-owned-toyota' && $parameter == 'certified-pre-owned-toyota' ) {
                    if( !isset($values_array['make']) ) {
                        $values_array['make'] = array();
                    }
                    if( !isset($values_array['certification']) ) {
                        $values_array['certification'] = array();
                    }
                    array_push($values_array['certification'], 'Toyota Certified Used Vehicles', 'Toyota', 'Toyota Gold Certified');
                    $values_array['make'][] = 'toyota';
                }else if( $keys_to_check === 'certified-pre-owned-ford' && $parameter == 'certified-pre-owned-ford' ) {
                    if( !isset($values_array['make']) ) {
                        $values_array['make'] = array();
                    }
                    if( !isset($values_array['certification']) ) {
                        $values_array['certification'] = array();
                    }

                    array_push($values_array['certification'], 'Ford Gold Certified', 'Ford Blue Advantage: Blue Certified', 'Ford Blue Certified');
                    $values_array['make'][] = 'ford';
                }else if( $keys_to_check === 'certified-pre-owned-kia' && $parameter == 'certified-pre-owned-kia' ) {
                    if( !isset($values_array['make']) ) {
                        $values_array['make'] = array();
                    }
                    if( !isset($values_array['certification']) ) {
                        $values_array['certification'] = array();
                    }
                    array_push($values_array['certification'], 'Kia Certified Pre-Owned');
                    $values_array['make'][] = 'kia';
                }else if( $keys_to_check === 'certified-pre-owned' && $parameter == 'certified-pre-owned' ) {
                    if( !isset($values_array['certified']) ) {
                        $values_array['certified'] = array();
                    }
                    $values_array['certified'][] = 'yes';
                }
            }
        }
    }

    /**
     * Make $values_array unique
     */
    foreach($values_array as $key => $value) {
        if(!in_array(strtolower($key), array('search', 'sort', 'mileage', 'final_price', 'condition'))) {
            $values_array[$key] = array_unique($value);
        }
    }

    /**
     * Load vehicles according to the current page
     */
    if( $pathname === 'new-vehicles-durango-colorado' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'N',
			'compare' => '='
		];
	}
	if( in_array($pathname, ['used-vehicles-durango-colorado', 'kia']) ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'U',
			'compare' => '='
		];
	}
	if( $pathname === 'kia' ) {
		$args['meta_query'][] = [
			'key' => 'make',
			'value' => 'Kia',
			'compare' => '='
		];
	}

	if( $pathname === 'new-vehicles-durango-colorado'
	  && $valuesArray['condition'] === 'new' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'N',
			'compare' => '='
		];
	} else if( $pathname === 'new-vehicles-durango-colorado'
			  && $valuesArray['condition'] === 'pre-owned' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'U',
			'compare' => '='
		];
	} else if( $pathname === 'new-vehicles-durango-colorado'
			  && $valuesArray['condition'] === 'certified' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'N',
			'compare' => '='
		];
		$args['meta_query'][] = [
			'key' => 'certified',
			'value' => 'yes',
			'compare' => '='
		];
	} else if( $pathname === 'used-vehicles-durango-colorado'
			  && $valuesArray['condition'] === 'new' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'N',
			'compare' => '='
		];
	} else if( $pathname === 'used-vehicles-durango-colorado'
			  && $valuesArray['condition'] === 'pre-owned' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'U',
			'compare' => '='
		];
	} else if( $pathname === 'used-vehicles-durango-colorado'
			  && $valuesArray['condition'] === 'certified' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'U',
			'compare' => '='
		];
		$args['meta_query'][] = [
			'key' => 'certified',
			'value' => 'yes',
			'compare' => '='
		];
	}



    wp_die();
}