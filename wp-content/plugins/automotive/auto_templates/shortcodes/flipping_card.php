<?php
/*
	Automotive Flipping Card Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/flipping_card.php

	Version: 16.7
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
?>
<div class="card-container <?php echo ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ); ?>" data-image="<?php echo esc_url( $larger_img ); ?>">
	<?php echo ( ! empty( $card_link ) ? '<a href="' . esc_url( $card_link ) . '"' . (!empty($card_link_target) ? ' target="' . esc_attr($card_link_target) . '"' : '') . '>' : '' ); ?>
    <div class="flipping-card">
        <div class="side front"><img class="img-responsive no_border disable-lazy-load" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( $alt ); ?>" srcset="<?php echo esc_attr($srcset); ?>" width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>"></div>
        <div class="side back"><div class='hover_title'><?php echo esc_html( $title ); ?></div>

	        <?php echo ( empty($card_link) && ! empty( $link ) ? '<a href="' . esc_url( $link ) . '" ' . ( isset( $target ) && ! empty( $target ) ? "target='" . esc_attr( $target ) . "' " : "" ) . 'class="link_button"><i class="fa fa-link button_icon"></i></a> ' : '' ); ?>
	        <?php echo ( empty($card_link) && ! empty( $larger_img ) ? '<a href="' . esc_url( $larger_img ) . '" class="fancybox"><i class="fa fa-arrows-alt button_icon"></i></a>' : '' ); ?></div>
    </div>
	<?php echo ( ! empty( $card_link ) ? '</a>' : '' ); ?>
</div>
