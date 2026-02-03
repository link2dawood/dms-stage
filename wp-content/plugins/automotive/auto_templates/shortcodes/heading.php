<?php
/*
	Automotive Heading Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/heading.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$heading = (in_array($heading, array("h1", "h2", "h3", "h4", "h5", "h6")) ? $heading : "h1");

echo "<" . $heading . ">" . $content . "</" . $heading . ">";