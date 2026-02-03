<?php
/*
	Automotive Parallax Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/parallax.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */ ?>
<div class="row parallax_parent <?php
echo( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>"<?php echo( ! empty( $temp_height ) ? " style='height: " . preg_replace( '/\D/', '', $temp_height ) . "px;'" : "" ); ?>>
	<div class="parallax_scroll lazy-loading-background-image clearfix" data-velocity="<?php echo esc_attr( $velocity ); ?>"
	     data-offset="<?php echo esc_attr( $offset ); ?>" data-src="<?php echo (isset($image[0]) ? esc_url( $image[0] ) : ''); ?>">
		<div class="overlay" style="background-color: <?php echo $overlay_color; ?>; color: <?php echo $text_color; ?>;">
			<div class="padding-vertical-10">

				<?php echo( ! empty( $title ) ? "<h1>" . esc_html( $title ) . "</h1>" : "" ); ?>

				<div class="container<?php echo( empty( $title ) ? " margin-top-60" : "" ); ?>">

					<?php echo do_shortcode( $content ); ?>

				</div>
			</div>
		</div>
	</div>
</div>
