<?php
/*
	Automotive Animated Numbers Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/animated_numbers.php

	Version: 15.9.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

wp_enqueue_script( 'inview' );
wp_enqueue_script( 'jquery_ui' );

echo( ! empty( $icon ) ? '<i class="fa ' . sanitize_html_classes( $icon ) . '"></i>' : '' ); ?>
<span class="animate_number margin-vertical-15 <?php echo( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>"
	<?php echo( ! empty( $alignment ) && in_array( $alignment, array(
		"left",
		"right",
		"center"
	) ) ? " style='text-align: " . $alignment . "'" : "" ); ?>>
    <?php
    echo( ! empty( $before_number ) ? esc_html( $before_number ) : "" );
    echo( ! empty( $number ) ? '<span class="number" data-separator="' . esc_attr( $separator_value ) . '">' . esc_html( $number ) . '</span>' : "" );
    echo( ! empty( $after_number ) ? esc_html( $after_number ) : "" );
    ?>
</span>