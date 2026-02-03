<?php
/*
	Automotive Button Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/button.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$is_align = !empty($align) && in_array($align, array("left", "center", "right"));

echo ($is_align ? "<div class='button-" . sanitize_html_class($align) . "-align'> " : "");

echo ( ! empty( $link ) ? "<a href='" . esc_url( $link ) . "' " . ( isset( $target ) && ! empty( $target ) ? "target='" . esc_attr( $target ) . "' " : "" ) . ">" : "" );
echo "<button class=' button " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . " " . ( ! empty( $size ) ? " " . sanitize_html_class( $size ) . "-button" : "" ) . "'" . ( $color ? " style='background-color: " . esc_attr( $color ) . "' data-color='" . esc_attr( $color ) . "'" : "" ) . ( $hover_color ? " data-hover='" . esc_attr( $hover_color ) . "'" : "" );
echo ( $modal !== false ? "data-toggle='modal' data-target='#" . esc_attr( $modal ) . "'" : "" );
echo ( $popover !== false ? "data-toggle='popover' data-placement='" . esc_attr( $placement ) . "' data-title='" . esc_attr( $title ) . "' data-content='" . esc_attr( $popover_content ) . "'" : "" );
echo ">" . do_shortcode( $content ) . "</button>";
echo ( ! empty( $link ) ? "</a>" : "" );

echo ($is_align ? "</div>" : "");
