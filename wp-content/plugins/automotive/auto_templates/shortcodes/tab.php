<?php
/*
	Automotive Tab Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/tab.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$x = $GLOBALS['auto_tab_count'];

$GLOBALS['auto_tabs'][ $x ] = array( 'title' => sprintf( $title, $GLOBALS['auto_tab_count'] ), 'content' => $content );
$GLOBALS['auto_tab_count']++;