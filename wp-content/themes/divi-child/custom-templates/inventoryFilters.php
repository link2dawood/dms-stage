<?php
function get_unique_meta_values($meta_key, $valuesArray, $path = '') {
	
    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => -1, // Set to -1 to retrieve all posts with the meta key
        'meta_query' => array(
            'relation' => 'AND',
        ),
    );
	
	if( in_array($path, ['new-vehicles-durango-colorado', 'kia']) ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'N',
			'compare' => '='
		];
	}
	if( $path === 'kia' ) {
		$args['meta_query'][] = [
			'key' => 'make',
			'value' => 'Kia',
			'compare' => '='
		];
	}
	if( $path === 'used-vehicles-durango-colorado' ) {
		$args['meta_query'][] = [
			'key' => 'condition',
			'value' => 'U',
			'compare' => '='
		];
	}

    if( !empty($valuesArray['search']) ) {
        $data = array(
            'relation' => 'OR',
            array(
                'key' => 'stock-number',
                'value' => $valuesArray['search'],
                'compare' => 'LIKE'
              ),
              array(
                'key' => 'vin-number',
                'value' => $valuesArray['search'],
                'compare' => 'LIKE'
              ),
              array(
                'key' => 'year',
                'value' => $valuesArray['search'],
                'compare' => 'LIKE'
              ),
              array(
                'key' => 'make',
                'value' => $valuesArray['search'],
                'compare' => 'LIKE'
              ),
              array(
                'key' => 'model',
                'value' => $valuesArray['search'],
                'compare' => 'LIKE'
              ),
              array(
                  'key' => 'body-style',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'type-of-vehicle',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'doors',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'cylinders',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'drivetrain',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'transmission',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'exterior-color',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'interior-color',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'fuel-type',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
              array(
                  'key' => 'postName',
                  'value' => $valuesArray['search'],
                  'compare' => 'LIKE'
              ),
          );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['sort']) ) {
        if($valuesArray['sort'] == 'low-to-high'){
            $args['meta_key'] = 'original_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        } else if($valuesArray['sort'] == 'high-to-low'){
            $args['meta_key'] = 'original_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }else if( $valuesArray['sort'] == 'mileage-lowest' ) {
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
    if (isset($valuesArray['year']) && !empty($valuesArray['year']) && is_array($valuesArray['year'])) {
        $data = array(
            'key' => 'year',
            'value' => $valuesArray['year'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if (isset($valuesArray['make']) && !empty($valuesArray['make']) && is_array($valuesArray['make'])) {
        $data = array(
            'key' => 'make',
            'value' => $valuesArray['make'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if (isset($valuesArray['model']) && !empty($valuesArray['model'])) {
        $data = array(
            'key' => 'model',
            'value' => $valuesArray['model'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
	if (isset($valuesArray['trim']) && !empty($valuesArray['trim'])) {
        $data = array(
            'key' => 'series',
            'value' => $valuesArray['trim'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
	
    if (isset($valuesArray['body-style']) && !empty($valuesArray['body-style']) && is_array($valuesArray['body-style'])) {
        $data = array(
            'key' => 'body-style',
            'value' => $valuesArray['body-style'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['type-of-vehicle']) && is_array($valuesArray['type-of-vehicle']) ) {
        $data = array(
            'key' => 'type-of-vehicle',
            'value' => $valuesArray['type-of-vehicle'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['doors']) && is_array($valuesArray['doors']) ) {
        $data = array(
            'key' => 'doors',
            'value' => $valuesArray['doors'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['cylinders']) && is_array($valuesArray['cylinders']) ) {
        $data = array(
            'key' => 'cylinders',
            'value' => $valuesArray['cylinders'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['drivetrain']) && is_array($valuesArray['drivetrain']) ) {
        $data = array(
            'key' => 'drivetrain',
            'value' => $valuesArray['drivetrain'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if ( !empty($valuesArray['transmission']) && is_array($valuesArray['transmission']) ) {
        $meta_query = array('relation' => 'OR');
        
        // Check if 'other' is included in the transmission values
        $hasOther = in_array('other', $valuesArray['transmission']);
        
        foreach ($valuesArray['transmission'] as $transmission) {
            if ($transmission == 'other') {
                // Exclude posts with automatic, manual, and CVT transmissions
                $x = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'transmission',
                        'value' => 'automatic',
                        'compare' => 'NOT LIKE',
                    ),
                    array(
                        'key' => 'transmission',
                        'value' => 'manual',
                        'compare' => 'NOT LIKE',
                    ),
                    array(
                        'key' => 'transmission',
                        'value' => 'cvt',
                        'compare' => 'NOT LIKE',
                    ),
                );
                
                if (!$hasOther) {
                    // If 'other' is the only selected option, include the 'other' value
                    $x[] = array(
                        'key' => 'transmission',
                        'value' => 'other',
                        'compare' => 'LIKE',
                    );
                }
            } else {
                $x = array(
                    'key' => 'transmission',
                    'value' => $transmission,
                    'compare' => 'LIKE',
                );
            }
            
            array_push($meta_query, $x);
        }
        
        $args['meta_query'][] = $meta_query;
    }
    if( !empty($valuesArray['certified']) && is_array($valuesArray['certified']) ) {
        $data = array(
            'key' => 'certified',
            'value' => $valuesArray['certified'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
	if( !empty($valuesArray['engine']) && is_array($valuesArray['engine']) ) {
        $data = array(
            'key' => 'engine',
            'value' => $valuesArray['engine'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['fuel-type']) && is_array($valuesArray['fuel-type']) ) {
        $data = array(
            'key' => 'fuel-type',
            'value' => $valuesArray['fuel-type'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }
    if( !empty($valuesArray['exterior-color']) && is_array($valuesArray['exterior-color']) ) {
        $meta_query = array('relation' => 'OR');
        foreach( $valuesArray['exterior-color'] as $color ) {
            $meta_query[] = array(
                'key' => 'exterior-color',
                'value' => $color,
                'compare' => 'LIKE',
            );
        }
        $args['meta_query'][] = $meta_query;
    }
    if( !empty($valuesArray['interior-color']) && is_array($valuesArray['interior-color']) ) {
        $meta_query = array('relation' => 'OR');
        foreach( $valuesArray['interior-color'] as $color ) {
            $meta_query[] = array(
                'key' => 'interior-color',
                'value' => $color,
                'compare' => 'LIKE',
            );
        }
        $args['meta_query'][] = $meta_query;
    }
    if( !empty($valuesArray['mileage']) && is_array($valuesArray['mileage']) ) {
        $data = array(
            'key' => 'odometer',
            'value' => $valuesArray['mileage'],
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        );
        $args['meta_query'][] = $data;
    }
	
	if( in_array($path, ['new-vehicles-durango-colorado', 'kia']) ) {
		if( !empty($valuesArray['price']) && is_array($valuesArray['price']) ) {
			if( count($valuesArray['price']) === 1 ) {
				$minprice = min($valuesArray['price']);
				$data = [
					'key' => 'miscprice-1',
					'value' => $minprice,
					'compare' => '>=',
					'type' => 'NUMERIC'
				];
			}else {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$data = array(
					'key' => 'miscprice-1',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
				);
			}
			$args['meta_query'][] = $data;
		}	
	} else if( $path === 'used-vehicles-durango-colorado' ) {
		if( !empty($valuesArray['price']) && is_array($valuesArray['price']) ) {
			if( count($valuesArray['price']) === 1 ) {
				$minprice = min($valuesArray['price']);
				$data = [
					'key' => 'original_price',
					'value' => $minprice,
					'compare' => '>=',
					'type' => 'NUMERIC'
				];
			}else {
				$minPrice = min($valuesArray['price']);
				$maxPrice = max($valuesArray['price']);

				$data = array(
					'key' => 'original_price',
					'value' => [$minPrice, $maxPrice],
					'compare' => 'BETWEEN',
				);
			}
			$args['meta_query'][] = $data;
		}
	}

    if( !empty($valuesArray['certification']) && is_array( $valuesArray['certification'] ) ) {
        $data = array(
            'key' => 'certification',
            'value' => $valuesArray['certification'],
            'compare' => 'IN',
        );
        $args['meta_query'][] = $data;
    }

    $posts = get_posts($args);

    $values = array();
    foreach ($posts as $post) {
        $value = get_post_meta($post->ID, $meta_key, true);
        if (!in_array($value, $values)) {
            $values[] = $value;
        }
    }
    return $values;
}
function print_checkbox_filters($meta_key, $label, $class, $valuesArray, $path = '') {
	
    $checked = array();
    $uniqueValues = array(); // To store unique values
    $meta_values = get_unique_meta_values($meta_key, $valuesArray, $path);
    if($meta_key === 'year' || $meta_key === 'doors' || $meta_key === 'cylinders') {
        rsort($meta_values);
    }else {
        sort($meta_values);
    }

    $checkboxFilter = '<form class="inventory-filterbar__' . $class . '-search-wrapper ' . $class . '">';
    foreach ($meta_values as $meta_value) {
        $meta_value = trim(strtolower($meta_value));
        if (isset($meta_value) && !empty($meta_value) && $meta_value !== 'None') {
            $isChecked = '';
            // Logic for transmission filter
            if( $meta_key == 'transmission' ) {
                if( strpos($meta_value, 'automatic') !== false ) {
                    $meta_value = 'automatic';
                    if( in_array($meta_value, $uniqueValues) ) {
                        continue;
                    }
                }else if( strpos($meta_value, 'manual') !== false ) {
                    $meta_value = 'manual';
                    if( in_array($meta_value, $uniqueValues) ) {
                        continue;
                    }
                }else if( strpos($meta_value, 'cvt') !== false ) {
                    $meta_value = 'cvt';
                    if( in_array($meta_value, $uniqueValues) ) {
                        continue;
                    }
                }else {
                    $meta_value = 'other';
                    if( in_array($meta_value, $uniqueValues) ) {
                        continue;
                    }
                }
            }
            if (isset($valuesArray[$label])) {
                $isChecked = in_array($meta_value, $valuesArray[$label]) ? 'checked' : '';
                if (!isset($checked[$label])) {
                    $checked[$label] = array();
                }
                if ($isChecked && !in_array($meta_value, $checked[$label])) {
                    $checked[$label][] = $meta_value;
                }
            }
            
            if (isset($valuesArray[$label]) && in_array($meta_value, $valuesArray[$label])) {
                $isChecked = 'checked';
                if (!isset($checked[$label])) {
                    $checked[$label] = array();
                }
                if (!in_array($meta_value, $checked[$label])) {
                    $checked[$label][] = $meta_value;
                }
            }

            $checkboxFilter .= '<div class="'.$label.' '.$meta_value.' inventory-filterbar__' . $class . ' ' . $class . ' inventory-filterbar__year"><input class="checkbox-filters ' . $class . '-filter-input" data-type="' . $label . '" type="checkbox" name="listing_' . $class . '[]" id="inventory-filter-' . $class . '-checkbox_' . $meta_value . '" value="' . $meta_value . '" ' . $isChecked . '><label for="inventory-filter-' . $class . '-checkbox_' . $meta_value . '" class="inventory-filterbar">' . $meta_value . '</label></div>';
            if( !in_array($meta_value, $uniqueValues) ) {
                $uniqueValues[] = $meta_value;
            }
        }
    }
    $checkboxFilter .= '</form>';
    $response = array(
        'checkboxFilter' => $checkboxFilter,
        'checkedValues' => $checked,
    );
    return json_encode($response);
}

function dmc_print_dropdown_filters( $meta_key, $label, $class, $valuesArray, $path = '' ) {
    $selected = '';
    $checked = array();
    $unique_values = [];
    $meta_values = get_unique_meta_values( $meta_key, $valuesArray, $path );

    if( $meta_key === 'year' || $meta_key === 'doors' || $meta_key === 'cylinders' ) {
        rsort($meta_values);
    } else {
        sort($meta_values);
    }

    $filter_html = '<form class="inventory-filterbar__' . esc_attr($class) . '-search-wrapper ' . esc_attr($class) . '">';
    $filter_html .= '<div class="custom-select-wrapper" style="position: relative;">';
    $filter_html .= '<select class="form-controls w-100 p-3 rounded border font-weight-bold dropdown-filters text-capitalize"
        data-type="'. esc_attr( $meta_key ) .'" name="'. esc_attr( $meta_key ) .'">';
    $filter_html .= '<option class="'. esc_attr( $meta_key ) .'-disabled">'. esc_html( 'Select a value' ) .'</option>';

    foreach( $meta_values as $value ) {
        $meta_value = trim(strtolower($value));
        if (!empty($meta_value) && $meta_value !== 'None') {
            $is_selected = '';

            if (isset($valuesArray[$label]) && $meta_value === strtolower($valuesArray[$label])) {
                $is_selected = 'selected';
                if (!isset($checked[$label])) {
                    $checked[$label] = array();
                }
                if (!in_array($meta_value, $checked[$label])) {
                    $checked[$label][] = $meta_value;
                }
            }

            $filter_html .= '<option class="text-capitalize ' . esc_attr($label) . ' ' . esc_attr($meta_value) . '" value="' . esc_attr($meta_value) . '" ' . $is_selected . '>' . esc_html($meta_value) . '</option>';

            if (!in_array($meta_value, $unique_values)) {
                $unique_values[] = $meta_value;
            }
        }
    }

    $filter_html .= '</select>';

    $filter_html .= '</div>'; // Close .custom-select-wrapper
    $filter_html .= '</form>';

    $response = array(
        'checkboxFilter' => $filter_html,
        'checkedValues' => $checked,
    );

    return json_encode($response);
}


function print_color_filters($name, $label, $valuesArray, $path = '') {
    $checked = array();
    $trackDuplicate = array();
    $meta_values = get_unique_meta_values($name, $valuesArray, $path);
    $colorFilter = '';
    foreach ($meta_values as $value) {
        $valueExploded = explode(' ', $value);
        foreach ($valueExploded as $colorValue) {
            $colorValue = strtolower($colorValue);
            $returnedColor = preDefinedColors($colorValue); // an object with key value pair
            $color = isset($returnedColor['value']) ? trim(strtolower($returnedColor['value'])) : '';
            $isChecked = '';

            if (!empty($returnedColor) && !is_null($returnedColor)) {
                if (!isset($checked[$label])) {
                    $checked[$label] = array();
                }
                if( !isset($trackDuplicate[$label]) ) {
                    $trackDuplicate[$label] = array();
                }
				// push color value in $checked if color is present in $color == $valuesarray[$label]
// 				if( isset($valuesArray[$label])
// 				   && is_array($valuesArray[$label])
// 				   && isset($checked[$label]) 
// 				   && is_array($checked[$label])
// 				   && in_array($color, $valuesArray[$label]) && !in_array($color, $checked[$label]) ) {
//                     $checked[$label][] = $color;
//                     $isChecked = 'checked';
//                 }

				if (isset($valuesArray[$label]) && is_array($valuesArray[$label])) {
					if (!isset($checked[$label]) || !is_array($checked[$label])) {
						$checked[$label] = []; // Ensure it's an array
					}

					if (in_array($color, $valuesArray[$label]) && !in_array($color, $checked[$label])) {
						$checked[$label][] = $color;
						$isChecked = 'checked';
					}
				}

                if( !in_array($color, $trackDuplicate[$label]) ) {
                    $trackDuplicate[$label][] = $color;
                    $colorFilter .= '<div class="inventory-filterbar__' . $label . ' ' . $label . ' inventory-filterbar__year col-4 col-lg-3">' .
                                    '<input type="checkbox" class="d-none checkbox-filters ' . $label . '-filter-input" data-type="' . $label . '" name="' . $label . '[]" id="inventory-filter-' . $label . '_' . $color . '" value="' . $color . '" '.$isChecked.'>' .
                                    '<label for="inventory-filter-' . $label . '_' . $color . '"><span class="d-inline-block color-filter-pills rounded-circle-px cursor-pointer" data-color="' . $color . '" data-color-code="'.$returnedColor['key'].'" data-toggle="tooltip" data-placement="top" title="'.$color.'"></span></label>' .
                                    '</div>';
                }
            }
        }
    }
    return json_encode(
        array(
            'color_filter' => $colorFilter,
            'checkedValues' => $checked,
        )
    );
}

// Define print_range_filters function
function print_range_filters($metaKey, $filterName, $filterType, $valuesArray, $path = '') {
    $rangeValues = get_unique_meta_values($metaKey, $valuesArray, $path);
    $numericValues = array_map('intval', $rangeValues);
	
    if (empty($numericValues)) {
        $numericValues = [];
    }
//     $rangeMin = min($numericValues);
//     $rangeMax = max($numericValues);
//     $rangeValue = $rangeMax / 2;
	
	if (!empty($numericValues)) {
		$rangeMin = min($numericValues);
		$rangeMax = max($numericValues);
		$rangeValue = $rangeMax / 2;
	} else {
		$rangeMin = $rangeMax = $rangeValue = 0; // or some default
	}

    
    // Calculate the step size based on the range of values and the desired number of steps
    $numSteps = 10; // Change this to the desired number of steps
    $stepSize = floor(($rangeMax - $rangeMin) / $numSteps);
    
    $filter = '<div class="'.$rangeMin.' '.$rangeMax.' range-container position-relative">'.
              '<div class="range-slider position-absolute w-100 "><div class="range-highlight highlighted-area"></div></div>'.
              '<input type="range"
			  name="'.$filterType.'"
			  id="'.$filterType.'-min"
			  class="range-filters filter-min-field position-absolute w-100 m-0 p-0 bg-transparent h-auto"
			  min="'.$rangeMin.'"
			  value="'.$rangeMin.'"
			  max="'.($rangeMax !== $rangeMin ? $rangeMax : null ).'"
			  step="'.$stepSize.'" 
			  data-filter="'.$filterType.'"
			  data-type="'. esc_attr( $filterType ) .'">'.
              '<input type="range"
			  name="'.$filterType.'"
			  id="'.$filterType.'-max"
			  class="range-filters position-absolute w-100 m-0 p-0 bg-transparent h-auto"
			  min="'.$rangeMin.'"
			  value="'.($rangeMax !== $rangeMin ? $rangeMax : null).'"
			  max="'.($rangeMax !== $rangeMin ? $rangeMax : null).'"
			  step="'.$stepSize.'"
			  data-filter="'.$filterType.'"
			  data-type="'. esc_attr( $filterType ) .'">'.
              '<input type="text"
			  name="range-value"
			  class="range-value range-value-'.$filterType.'-value d-none"
			  data-include="false"
			  data-type="'. esc_attr( $filterType ) .'"></div>';

    return json_encode($filter);
}
