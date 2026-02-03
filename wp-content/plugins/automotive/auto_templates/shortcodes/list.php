<?php
/*
	Automotive List Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/list.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<ul class='shortcode type-" . sanitize_html_class( $style ) . " " . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>";
echo do_shortcode( $content );
echo "</ul>";