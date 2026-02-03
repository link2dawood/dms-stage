<?php
/*
	Automotive Featured Panel Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/featured_panel.php

	Version: 16.4
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class='automotive-featured-panel margin-top-25 " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
echo "<h5>" . esc_html( $title ) . "</h5>";

echo( ! empty( $image_link ) ? "<a href='" . esc_url( $image_link ) . "'" . ($target != "_self" ? " target='" . $target . "'" : "") . ">" : "" );
echo "<img src='" . esc_url( $icon[0] ) . "' data-hoverimg='" . esc_url( $hover_icon[0] ) . "' width=\"" . esc_attr($icon[1]) . "\" height=\"" . esc_attr($icon[2]) . "\" alt=\"" . esc_html( $alt ) . "\" class=\"no_border\">";
echo( ! empty( $image_link ) ? "</a>" : "" );

echo "<p>" . do_shortcode( $content ) . "</p>";
echo "</div>";
