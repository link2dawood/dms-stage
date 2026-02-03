<?php
/*
	Automotive Range Filter Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/range_filter.php

	Version: 16.5
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$Automotive_Plugin          = Automotive_Plugin();
$currency_symbol            = automotive_listing_get_option('currency_symbol', '');
$currency_separator         = automotive_listing_get_option('currency_separator', '');
$currency_separator_decimal = automotive_listing_get_option('currency_separator_decimal', '');
$currency_decimals          = automotive_listing_get_option('currency_decimals', '');

unset($instance['title']);

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;

  $single_category = $Automotive_Plugin->get_single_listing_category($range_category);

	// generated when switching views
	$params = (isset($_REQUEST['params']) && !empty($_REQUEST['params']) ? json_decode(stripslashes($_REQUEST['params']), true) : array());

  if(!empty($single_category['terms'])){
		$range_terms = automotive_listing_remove_non_numerical($single_category['terms']);
    $min         = min($range_terms);
    $max         = max($range_terms);

    $current_min = (isset($_GET[$single_category['slug']]) && is_array($_GET[$single_category['slug']]) && isset($_GET[$single_category['slug']][0]) ? absint($_GET[$single_category['slug']][0]) : $min);
    $current_max = (isset($_GET[$single_category['slug']]) && is_array($_GET[$single_category['slug']]) && isset($_GET[$single_category['slug']][1]) ? absint($_GET[$single_category['slug']][1]) : $max);

		// use params if set
		if(isset($params[$single_category['slug']][0])){
			$current_min = $params[$single_category['slug']][0];
		}

		if(isset($params[$single_category['slug']][1])){
			$current_max = $params[$single_category['slug']][1];
		}

    echo "<div class=\"listing-range-slider-container\">";
    echo "<div class=\"listing-range-slider\"
		data-type=\"" . $single_category['slug'] . "\"
		data-min=\"" . $min . "\"
		data-max=\"" . $max . "\"
		data-current-min=\"" . $current_min . "\"
		data-current-max=\"" . $current_max . "\"
		data-increment=\"" . absint($increment) . "\"
		data-currency=\"" . (isset($single_category['currency']) && $single_category['currency'] ? 1 : 0) . "\"";

		if(isset($single_category['currency']) && $single_category['currency']) {
			echo " data-currency-symbol=\"" . $currency_symbol . "\"";
			echo " data-thousand-sep=\"" . $currency_separator . "\"";
			echo " data-decimal-sep=\"" . $currency_separator_decimal . "\"";
			echo " data-decimals=\"" . $currency_decimals . "\"";

			$current_min = $Automotive_Plugin->format_currency($current_min);
			$current_max = $Automotive_Plugin->format_currency($current_max);
		}

		echo "></div>";
    echo "<div class=\"listing-range-slider-label\"><span class=\"singular-label\">" . $Automotive_Plugin->display_listing_category($single_category['singular']) . "</span>: <span class=\"min-label\">" . $current_min . "</span> &mdash; <span class=\"max-label\">" . $current_max . "</span> </div>";
    echo "</div>";
  }

echo $after_widget;
