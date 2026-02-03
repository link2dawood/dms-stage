<?php
/*
	Automotive Tooltip Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/tooltip.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<span data-toggle='tooltip' data-title='" . $title . "' data-placement='" . $placement . "' data-html='" . $html . "' class='tooltip_js'>";
echo do_shortcode( $content );
echo "</span>";