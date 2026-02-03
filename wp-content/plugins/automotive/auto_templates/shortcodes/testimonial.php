<?php
/*
	Automotive Testimonial Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/testimonial.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class='" . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . "'>" . testimonial_slider( $content ) . "<div class='clearfix'></div></div>";