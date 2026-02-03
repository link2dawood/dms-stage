<?php
/*
	Automotive WooCommerce Product Scroller Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/woocommerce_product_scroller.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
?>
<div class="woocommerce woocommerce-featured-scroller">
	<?php
	if ( $loop->have_posts() ) {
		echo "<ul class=\"products\">";

		while ( $loop->have_posts() ) : $loop->the_post();
			wc_get_template_part( 'content', 'product' );
		endwhile;

		echo "<div class=\"clearfix\"></div>";
		echo "</ul><!--/.products-->";
	} else {
		echo __( 'No products found', 'listings' );
	}

	wp_reset_postdata();
	?>

	<div class="arrow3 slide_controls">
		<span class="prev-btn"></span>
		<span class="next-btn"></span>

		<div class="clearfix"></div>
	</div>
</div>