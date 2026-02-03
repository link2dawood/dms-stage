<?php
/*
	Automotive Vehicle Scroller Shortcode Single Item Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/vehicle_scroller.php

	Version: 18.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$Automotive_Plugin  = Automotive_Plugin();
$Automotive_Listing = new Automotive_Listing( get_the_ID() );

$main_image = $Automotive_Listing->get_main_image( false, 'auto_thumb' );

$main_image_dimensions = $Automotive_Plugin->get_automotive_image_sizes( 'auto_thumb' );
$main_image_width      = $main_image_dimensions['width'];
$main_image_height     = $main_image_dimensions['height'];

$hide_sold_price         = automotive_listing_get_option( 'hide_sold_price', false );
$price_text_replacement  = automotive_listing_get_option( 'price_text_replacement', '' );
$price_text_all_listings = automotive_listing_get_option( 'price_text_all_listings', true );

echo "<div class=\"slide\">";
if ( automotive_listing_get_option( 'vehicle_scroller_badge', true ) ) {
	$Automotive_Plugin->get_badge_html( $Automotive_Listing->custom_badge, $Automotive_Listing->is_sold );
}

echo "<a href=\"" . esc_url( $Automotive_Listing->get_permalink() ) . "\"><div class=\"car-block\">";
echo "<div class=\"img-flex\">";

if ( $Automotive_Listing->is_sold ) {
	echo '<span class="sold_text">' . __( 'Sold', 'listings' ) . '</span>';
}

echo "<span class=\"align-center\"><i class=\"fa fa-3x fa-plus-square-o\"></i></span> ";
echo "<img src=\"" . esc_url( $main_image['src'] ) . "\" alt=\"" . esc_attr( $main_image['alt'] ) . "\" class=\"img-responsive no_border\" width=\"" . (int) $main_image_width . "\" height=\"" . (int) $main_image_height . "\"> ";
echo "</div>";
echo "<div class=\"car-block-bottom\">";
echo "<div class='scroller_text'><strong>" . esc_html( $Automotive_Listing->get_title() ) . "</strong></div>";
echo "<div class='scroller_text'>" . esc_html( $Automotive_Listing->get_scroller_desc() ) . "</div>";

if ( ( $hide_sold_price && ! $Automotive_Listing->is_sold ) || ! $hide_sold_price ) {
	if ( $Automotive_Listing->get_price() ) {
		if ( $price_text_replacement && ! $price_text_all_listings ) {
			echo do_shortcode( $price_text_replacement );
		} else {
			echo '<span class="scroller_price">' . $Automotive_Plugin->format_currency( $Automotive_Listing->get_price() ) . '</span>';
		}
	} elseif ( ( empty( $Automotive_Listing->get_price() ) && $price_text_all_listings ) || ( $price_text_replacement && ! $price_text_all_listings ) ) {
		echo do_shortcode( $price_text_replacement );
	}
}

echo "</div>";
echo "</div></a>";
echo "</div>";