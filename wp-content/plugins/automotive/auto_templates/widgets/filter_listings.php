<?php
/*
	Automotive Filter Listings Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/filter_listings.php

	Version: 18.2
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$Automotive_Plugin = Automotive_Plugin();
$filter_showall    = automotive_listing_get_option( 'filter_showall', false );
$load_dependancies = $_GET;

if ( isset( $instance['title'] ) ) {
	unset( $instance['title'] );
}

echo $before_widget;
if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}

if ( isset( $_POST['params'] ) ) {
	$additional_dependancies = json_decode( stripslashes( $_POST['params'] ), true );

	$load_dependancies = array_merge( $load_dependancies, $additional_dependancies );
}

if ( $filter_showall ) {
	$dependancies = $Automotive_Plugin->process_dependancies( array() );
} else {
	$dependancies = $Automotive_Plugin->process_dependancies_plain( $load_dependancies );
}

// generated when switching views
$params = ( ! empty( $_REQUEST['params'] ) ? json_decode( stripslashes( $_REQUEST['params'] ), true ) : $_REQUEST );

$currency_symbol            = automotive_listing_get_option( 'currency_symbol', '' );
$currency_separator         = automotive_listing_get_option( 'currency_separator', '' );
$currency_separator_decimal = automotive_listing_get_option( 'currency_separator_decimal', '' );
$currency_decimals          = automotive_listing_get_option( 'currency_decimals', '' );

echo "<div class='dropdowns select-form'>";
foreach ( $instance as $post_meta => $value ) {
	if ( isset( $value ) && $value == 1 ) {
		$key  = $Automotive_Plugin->get_single_listing_category( $post_meta );
		$slug = ( $key['slug'] === 'year' ? 'yr' : $key['slug'] );

		if ( ! empty( $key['slug'] ) ) {
			$current_option = ( ! empty( $load_dependancies[ $key['slug'] ] ) ? $load_dependancies[ $key['slug'] ] : "" );

			//if ( isset( $params[ $slug ] ) && ! is_array( $params[ $slug ] ) ) {
			if ( isset( $params[ $slug ] ) ) {
				$current_option = $params[ $slug ];
			}

			$other_options = array( "current_option" => $current_option );

			if ( isset( $key['show_amount'] ) && $key['show_amount'] == 1 ) {
				$other_options['show_amount'] = ( ! empty( $dependancies[1][ $key['slug'] ] ) ? $dependancies[1][ $key['slug'] ] : array() );
			}

			if ( isset( $key['range'] ) && $key['range'] == 1 ) {

				if ( ! empty( $key['terms'] ) ) {
					$min = min( $key['terms'] );
					$max = max( $key['terms'] );

					$current_min = ( isset( $_GET[ $key['slug'] ][0] ) && is_array( $_GET[ $key['slug'] ] ) ? absint( $_GET[ $key['slug'] ][0] ) : $min );
					$current_max = ( isset( $_GET[ $key['slug'] ] ) && is_array( $_GET[ $key['slug'] ] ) && isset( $_GET[ $key['slug'] ][1] ) ? absint( $_GET[ $key['slug'] ][1] ) : $max );

					// use params if set
					if ( isset( $params[ $key['slug'] ][0] ) ) {
						$current_min = $params[ $key['slug'] ][0];
					}

					if ( isset( $params[ $key['slug'] ][1] ) ) {
						$current_max = $params[ $key['slug'] ][1];
					}

					echo "<div class=\"listing-range-slider-container\">";
					echo "<div class=\"listing-range-slider\"
					data-type=\"" . $key['slug'] . "\"
					data-min=\"" . $min . "\"
					data-max=\"" . $max . "\"
					data-current-min=\"" . $current_min . "\"
					data-current-max=\"" . $current_max . "\"
					data-increment=\"" . absint( $key['range_increment'] ) . "\"
					data-currency=\"" . ( isset( $key['currency'] ) && $key['currency'] ? 1 : 0 ) . "\"";

					if ( isset( $key['currency'] ) && $key['currency'] ) {
						echo " data-currency-symbol=\"" . $currency_symbol . "\"";
						echo " data-thousand-sep=\"" . $currency_separator . "\"";
						echo " data-decimal-sep=\"" . $currency_separator_decimal . "\"";
						echo " data-decimals=\"" . $currency_decimals . "\"";

						$current_min = $Automotive_Plugin->format_currency( $current_min );
						$current_max = $Automotive_Plugin->format_currency( $current_max );
					}

					echo "></div>";
					echo "<div class=\"listing-range-slider-label\"><span class=\"singular-label\">" . $Automotive_Plugin->display_listing_category( $key['singular'] ) . "</span>: <span class=\"min-label\">" . esc_html( $current_min ) . "</span> &mdash; <span class=\"max-label\">" . esc_html( $current_max ) . "</span> </div>";
					echo "</div>";
				}
			} else {
				echo '<div class="my-dropdown ' . sanitize_html_class( $key['slug'] ) . '-dropdown max-dropdown">';
				$dropdown_options = ( ! empty( $dependancies[0][ $key['slug'] ] ) ? $dependancies[0][ $key['slug'] ] : "" );

				if ( isset( $dropdown_options[''] ) ) {
					unset( $dropdown_options[''] );
				}

				$Automotive_Plugin->listing_dropdown( $key, "", "listing_filter sidebar_widget_filter", $dropdown_options, $other_options );
				echo '</div>';
			}
		}
	}
}

echo "</div>";
echo "<button class='button reset_widget_filter md-button margin-top-10 margin-bottom-none btn-inventory'>" . esc_html__( "Reset Search Filters", "listings" ) . "</button>";
echo "<div class='clearfix'></div>";

echo $after_widget;
