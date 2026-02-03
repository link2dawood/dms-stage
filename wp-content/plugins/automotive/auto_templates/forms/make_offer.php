<?php
/*
	Automotive Make Offer Form Template File
	To overwrite this file copy it to automotive-child/auto_templates/forms/make_offer.php

	Version: 15.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$offer_email                   = automotive_uniqid('offer_email');
$offer_phone                   = automotive_uniqid('offer_phone');
$gdpr_checkbox_offer           = automotive_uniqid('gdpr_checkbox_offer');
$offer_fancybox_form_recaptcha = automotive_uniqid('offer_fancybox_form_recaptcha');
$make_offer_form_shortcode     = automotive_listing_get_option('make_offer_form_shortcode', false);

if ( ! $make_offer_form_shortcode ) {
	$gdpr_form            = automotive_listing_get_option('gdpr_form', false);
	$recaptcha_enabled    = automotive_listing_get_option('recaptcha_enabled', true);
	$recaptcha_public_key = automotive_listing_get_option('recaptcha_public_key', ''); ?>
	<h3><?php _e( "Make an Offer", "listings" ); ?></h3>

	<form name="make_offer" method="post" class="ajax_form" data-nonce="<?php echo wp_create_nonce("automotive-form-offer"); ?>">
		<table class="no_style">
			<tr>
				<td><?php _e( "Name", "listings" ); ?>:</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td><?php _e( "Preferred Contact", "listings" ); ?>:</td>
				<td><span class="styled_input">
					<input type="radio" name="contact_method" value="<?php _e( "email", "listings" ); ?>" id="<?php echo $offer_email; ?>">
					<label for="<?php echo $offer_email; ?>"><?php _e( "Email", "listings" ); ?></label>

					<input type="radio" name="contact_method" value="<?php _e( "phone", "listings" ); ?>" id="<?php echo $offer_phone; ?>">
					<label for="<?php echo $offer_phone; ?>"><?php _e( "Phone", "listings" ); ?></label> </span>
				</td>
			</tr>
			<tr>
				<td><?php _e( "Email", "listings" ); ?>:</td>
				<td><input type="text" name="email"></td>
			</tr>
			<tr>
				<td><?php _e( "Phone", "listings" ); ?>:</td>
				<td><input type="text" name="phone"></td>
			</tr>
			<tr>
				<td><?php _e( "Offered Price", "listings" ); ?>:</td>
				<td><input type="text" name="offered_price"></td>
			</tr>
			<tr>
				<td><?php _e( "Financing Required", "listings" ); ?>:</td>
				<td><select name="financing_required" class="css-dropdowns" data-update="false">
						<option value="<?php _e( "Yes", "listings" ); ?>"><?php _e( "Yes", "listings" ); ?></option>
						<option value="<?php _e( "No", "listings" ); ?>"><?php _e( "No", "listings" ); ?></option>
					</select></td>
			</tr>
			<tr>
				<td colspan="2"><?php _e( "Other Comments/Conditions", "listings" ); ?>:<br>
					<textarea name="other_comments" class="fancybox_textarea"></textarea></td>
			</tr>
			<?php if($gdpr_form){
				$gdpr_form_text = automotive_listing_get_option('gdpr_form_text', ''); ?>
			<tr>
				<td colspan="2">
					<span class="styled_input">
						<input type="checkbox" name="gdpr" id="<?php echo $gdpr_checkbox_offer; ?>"> <label for="<?php echo $gdpr_checkbox_offer; ?>"><?php echo esc_html($gdpr_form_text); ?></label>
					</span>
				</td>
			</tr>
			<?php } ?>
			<?php

			if ( $recaptcha_enabled && $recaptcha_public_key ) {
				echo "<tr><td colspan='2'>" . __( "reCAPTCHA", "listings" ) . ": <br><div id='" . $offer_fancybox_form_recaptcha . "' class='recaptcha_holder'></div></td></tr>";
			}

			?>
			<tr>
				<td colspan="2"><input type="submit" value="<?php _e( "Submit", "listings" ); ?>"> <i
						class="fa fa-refresh fa-spin loading_icon_form"></i></td>
			</tr>
		</table>
	</form>
<?php } else {
	echo do_shortcode( $make_offer_form_shortcode );
} ?>