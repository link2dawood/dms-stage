<?php
/*
	Automotive Contact Information Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/contact_information.php

	Version: 14.2
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$phone_link = preg_replace('/\D/', '', $phone);
$fax_link		= preg_replace('/\D/', '', $fax);

if(!empty($address)){ ?>
<div class="address clearfix margin-right-25 padding-bottom-40 <?php echo( ! empty( $extra_class ) ? $extra_class : "" ); ?>">
	<div class="icon_address">
		<p><i class="fa fa-map-marker"></i><strong><?php _e( "Address", "listings" ); ?>:</strong></p>
	</div>
	<div class="contact_address">
		<p class="margin-bottom-none"><?php echo( ! empty( $company ) ? $company . "<br>" : "" ); ?>
			<?php echo( ! empty( $address ) ? $address : "" ); ?></p>
	</div>
</div>
<?php } ?>
<div class="address clearfix address_details margin-right-25 padding-bottom-40">
	<ul class="margin-bottom-none">
		<?php echo( ! empty( $phone ) ? '<li><i class="fa fa-phone"></i><strong>' . __( 'Phone', 'listings' ) . ':</strong> <span><a href="tel:' . $phone_link . '">' . $phone . '</a></span></li>' : '' ); ?>
		<?php echo( ! empty( $fax ) ? '<li><i class="fa fa-fax"></i><strong>' . __( 'Fax', 'listings' ) . ':</strong> <span><a href="tel:' . $fax_link . '">' . $fax . '</a></span></li>' : '' ); ?>
		<?php echo( ! empty( $email ) ? '<li><i class="fa fa-envelope-o"></i><strong>' . __( 'Email', 'listings' ) . ':</strong> <a href="mailto:' . $email . '">' . $email . '</a></li>' : '' ); ?>
		<?php echo( ! empty( $web ) ? '<li class="padding-bottom-none"><i class="fa fa-laptop"></i><strong>' . __( 'Web', 'listings' ) . ':</strong> <a href="' . $web . '">' . $web . '</a></li>' : '' ); ?>
	</ul>
</div>

<div class="clearfix"></div>