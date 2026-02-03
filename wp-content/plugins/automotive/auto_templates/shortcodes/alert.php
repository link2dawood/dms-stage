<?php
/*
	Automotive Alert Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/alert.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class=\"alert alert-" . sanitize_html_class( $type ) . " " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "\">";
echo( strtolower( $close ) != "no" ? "<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">" . esc_html__( "Close", "listings" ) . "</span></button>" : "" );
echo do_shortcode( $content );
echo "</div>";