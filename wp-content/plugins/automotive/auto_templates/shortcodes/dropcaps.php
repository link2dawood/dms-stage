<?php
/*
	Automotive Dropcaps Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/dropcaps.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<span class='firstcharacter" . (!empty($extra_class) ? " " . sanitize_html_classes( $extra_class ) : "") . "'>" . do_shortcode( $content ) . "</span>";