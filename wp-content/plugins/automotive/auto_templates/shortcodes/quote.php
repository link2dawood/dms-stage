<?php
/*
	Automotive Quote Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/quote.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class='quote'" . ( isset( $color ) && $color != "#c7081b" ? " style='border-color: " . esc_html( $color ) . "'" : "" ) . ">";
echo do_shortcode( $content );
echo "</div>";