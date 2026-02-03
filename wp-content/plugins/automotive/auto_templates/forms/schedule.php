<?php
/*
	Automotive Schedule Test Drive Form Template File
	To overwrite this file copy it to automotive-child/auto_templates/forms/schedule.php

	Version: 15.3.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$schedule_email 									= automotive_uniqid('schedule_email');
$schedule_phone 									= automotive_uniqid('schedule_phone');
$gdpr_checkbox_schedule 					= automotive_uniqid('gdpr_checkbox_schedule');
$schedule_fancybox_form_recaptcha = automotive_uniqid('schedule_fancybox_form_recaptcha');
$schedule_test_drive_form_shortcode = automotive_listing_get_option('schedule_test_drive_form_shortcode', false);

if ( ! $schedule_test_drive_form_shortcode ) {
	$gdpr_form            = automotive_listing_get_option('gdpr_form', false);
	$recaptcha_enabled    = automotive_listing_get_option('recaptcha_enabled', true);
	$recaptcha_public_key = automotive_listing_get_option('recaptcha_public_key', ''); ?>
	<h3><?php _e( "Schedule Test Drive", "listings" ); ?></h3>

	<form name="schedule" method="post" class="ajax_form" data-nonce="<?php echo wp_create_nonce("automotive-form-schedule"); ?>">
		<table class="no_style">
			<tr>
				<td><?php _e( "Name", "listings" ); ?>:</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td><?php _e( "Preferred Contact", "listings" ); ?>:</td>
				<td><span class="styled_input">
					<input type="radio" name="contact_method" value="<?php _e( "Email", "listings" ); ?>" id="<?php echo $schedule_email; ?>">
					<label for="<?php echo $schedule_email; ?>"><?php _e( "Email", "listings" ); ?></label>
					<input type="radio" name="contact_method" value="<?php _e( "Phone", "listings" ); ?>" id="<?php echo $schedule_phone; ?>">
					<label for="<?php echo $schedule_phone; ?>"><?php _e( "Phone", "listings" ); ?></label> </span>
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
				<td><?php _e( "Best Day", "listings" ); ?>:</td>
				<td><input type="text" name="best_day"></td>
			</tr>
			<tr>
				<td><?php _e( "Best Time", "listings" ); ?>:</td>
				<td><input type="text" name="best_time"></td>
			</tr>
			<?php if($gdpr_form){
				$gdpr_form_text = automotive_listing_get_option('gdpr_form_text', ''); ?>
			<tr>
				<td colspan="2">
					<span class="styled_input">
						<input type="checkbox" name="gdpr" id="<?php echo $gdpr_checkbox_schedule; ?>"> <label for="<?php echo $gdpr_checkbox_schedule; ?>"><?php echo esc_html($gdpr_form_text); ?></label>
					</span>
				</td>
			</tr>
			<?php } ?>
			<?php

			if ( $recaptcha_enabled && $recaptcha_public_key ) {
				echo "<tr><td colspan='2'>" . __( "reCAPTCHA", "listings" ) . ": <br><div id='" . $schedule_fancybox_form_recaptcha . "' class='recaptcha_holder'></div></td></tr>";
			}

			?>
			<tr>
				<td colspan="2"><input type="submit" value="<?php _e( "Submit", "listings" ); ?>">
					<i class="fa fa-refresh fa-spin loading_icon_form"></i>
				</td>
			</tr>
		</table>
	</form>
<?php } else {
	echo do_shortcode( $schedule_test_drive_form_shortcode );
} ?>