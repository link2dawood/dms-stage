<?php
/*
	Automotive Featured Icon Box Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/featured_icon_box.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<span class='align-center featured_icon_box " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'><i class='" . sanitize_html_classes( $icon ) . " fa fa-6x'></i></span><h4>" . esc_html( $title ) . "</h4><p>" . do_shortcode( $content ) . "</p>";
