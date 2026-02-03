<?php
/*
	Automotive List Item Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/list_item.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<li>" . $the_icon . do_shortcode( $content ) . "</li>";