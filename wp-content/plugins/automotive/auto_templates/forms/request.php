<?php
/*
	Automotive Request More Information Form Template File
	To overwrite this file copy it to automotive-child/auto_templates/forms/request.php

	Version: 15.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$request_more_email              = automotive_uniqid('request_more_email');
$request_more_phone              = automotive_uniqid('request_more_phone');
$gdpr_checkbox_request           = automotive_uniqid('gdpr_checkbox_request');
$request_fancybox_form_recaptcha = automotive_uniqid('request_fancybox_form_recaptcha');
$request_info_form_shortcode     = automotive_listing_get_option('request_info_form_shortcode', false);

if ( ! $request_info_form_shortcode ) {
	$gdpr_form            = automotive_listing_get_option('gdpr_form', false);
	$recaptcha_enabled    = automotive_listing_get_option('recaptcha_enabled', true);
	$recaptcha_public_key = automotive_listing_get_option('recaptcha_public_key', '');
	?>
	<h3><?php _e( "Request More Info", "listings" ); ?></h3>

	<form name="request_info" method="post" class="ajax_form" data-nonce="<?php echo wp_create_nonce("automotive-form-request"); ?>">
		<table class="no_style">
			<tr>
				<td><?php _e( "Name", "listings" ); ?>:</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td><?php _e( "Preferred Contact", "listings" ); ?>:</td>
				<td>
					<span class="styled_input">
						<input type="radio" name="contact_method" value="<?php _e( "Email", "listings" ); ?>" id="<?php echo $request_more_email; ?>">
						<label for="<?php echo $request_more_email; ?>"><?php _e( "Email", "listings" ); ?></label>
						<input type="radio" name="contact_method" value="<?php _e( "Phone", "listings" ); ?>" id="<?php echo $request_more_phone; ?>">
						<label for="<?php echo $request_more_phone; ?>"><?php _e( "Phone", "listings" ); ?></label>
					</span>
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
				<td colspan="2"><?php _e("Questions/Comments", "listings"); ?>:<br>
					<textarea name="comments" class="fancybox_textarea"></textarea></td>
			</tr>
			<?php if($gdpr_form){
				$gdpr_form_text = automotive_listing_get_option('gdpr_form_text', ''); ?>
			<tr>
				<td colspan="2">
					<span class="styled_input">
						<input type="checkbox" name="gdpr" id="<?php echo $gdpr_checkbox_request; ?>"> <label for="<?php echo $gdpr_checkbox_request; ?>"><?php echo ($gdpr_form_text ? esc_html($gdpr_form_text) : ""); ?></label>
					</span>
				</td>
			</tr>
			<?php } ?>
			<?php
			if ( $recaptcha_enabled && $recaptcha_public_key ) {
				echo "<tr><td colspan='2'>" . __( "reCAPTCHA", "listings" ) . ": <br><div id='" . $request_fancybox_form_recaptcha . "' class='recaptcha_holder'></div></td></tr>";
			}
			?>
			<tr>
				<td colspan="2"><input type="submit" value="<?php _e( "Submit", "listings" ); ?>"> <i
						class="fa fa-refresh fa-spin loading_icon_form"></i></td>
			</tr>
		</table>
	</form>
<?php } else {
	echo do_shortcode( $request_info_form_shortcode );
} ?>