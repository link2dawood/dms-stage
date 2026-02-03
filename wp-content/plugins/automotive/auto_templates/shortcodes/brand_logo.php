<?php
/*
	Automotive Brand Logo Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/brand_logo.php

	Version: 16.4.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo "<div class='slide hoverimg'>";
echo ($extra_class ? '<div class="' . trim( sanitize_html_classes($extra_class) ) . '">' : '');
echo "<a href='" . esc_url( $link ) . "'" . ( isset( $target ) && ! empty( $target ) ? " target='" . esc_attr( $target ) . "'" : "" ) . " style='background-image: url(" . esc_url( wp_get_attachment_url( $img ) ) . ");' data-hoverimg='" . esc_url( wp_get_attachment_url( $hoverimg ) ) . "'></a>";
echo ($extra_class ? '</div>' : '');
echo "</div>";
