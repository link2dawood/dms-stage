<?php
/*
	Automotive Featured Brands Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/featured_brands.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class=\"featured-brand " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
echo ( ! empty( $title ) ? "<h3 class='margin-bottom-25'>" . esc_html( $title ) . "</h3>" : "" );
echo "<div class=\"arrow2 pull-right clearfix slideControls\"><span class=\"next-btn\"></span><span class=\"prev-btn\"></span></div>";
echo "<div class=\"carousel-slider featured_slider\">";
echo do_shortcode( $content );
echo "</div>";
echo "</div>";