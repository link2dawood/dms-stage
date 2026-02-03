<?php
/*
	Automotive Contact Us Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/contact_us.php

	Version: 16.9
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$allowed_html = array(
	'a' => array(
		'href' => array(),
		'title' => array()
	),
	'br' => array(),
	'em' => array(),
	'strong' => array(),
);

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title; ?>

	<div class="footer-contact xs-margin-bottom-60">
		<ul>
			<?php if(!empty($address)){ ?>
			<li><i class="fa fa-map-marker"></i> <strong><?php _e("Address", "listings"); ?>:</strong><?php echo wp_kses( $address, $allowed_html ); ?></li>
			<?php }

			if(!empty($phone)){ ?>
			<li><i class="fa fa-phone"></i> <strong><?php _e("Phone", "listings"); ?>:</strong><a href="tel:<?php echo preg_replace('/\D/', '', $phone); ?>"><?php echo esc_html( $phone ); ?></a></li>
			<?php }

			if(!empty($email)){ ?>
				<li><i class="fa fa-envelope-o"></i> <strong><?php _e("Email", "listings"); ?>:</strong><a href="mailto:<?php echo sanitize_email($email); ?>"><?php echo sanitize_email($email); ?></a></li>
			<?php }

			if(!empty($website)){ ?>
				<li><i class="fa fa-globe"></i> <strong><?php _e("Website", "listings"); ?>:</strong><a href="<?php echo esc_url($website); ?>" target="_blank"><?php echo esc_url($website); ?></a></li>
			<?php } ?>
		</ul>

		<i class="fa fa-location-arrow back_icon"></i>
	</div>
<?php
echo $after_widget;
