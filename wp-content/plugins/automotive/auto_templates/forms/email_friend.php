<?php
/*
	Automotive Email Friend Form Template File
	To overwrite this file copy it to automotive-child/auto_templates/forms/email_friend.php

	Version: 15.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$friend_form_name 						 = automotive_uniqid('friend_form_name');
$friend_form_email 					   = automotive_uniqid('friend_form_email');
$friend_form_friend_email 		 = automotive_uniqid('friend_form_friend_email');
$friend_form_message 					 = automotive_uniqid('friend_form_message');
$email_fancybox_form_recaptcha = automotive_uniqid('email_fancybox_form_recaptcha');
$email_friend_form_shortcode   = automotive_listing_get_option('email_friend_form_shortcode', false);

if ( ! $email_friend_form_shortcode ) {
	$recaptcha_enabled    = automotive_listing_get_option('recaptcha_enabled', true);
	$recaptcha_public_key = automotive_listing_get_option('recaptcha_public_key', ''); ?>
	<h3><?php _e( "Email to a Friend", "listings" ); ?></h3>

	<form name="email_friend" method="post" class="ajax_form" data-nonce="<?php echo wp_create_nonce("automotive-form-friend"); ?>">
		<table class="no_style">
			<tr>
				<td><label for="<?php echo $friend_form_name; ?>"><?php _e( "Name", "listings" ); ?></label>:</td>
				<td><input type="text" name="name" id="<?php echo $friend_form_name; ?>"></td>
			</tr>
			<tr>
				<td><label for="<?php echo $friend_form_email; ?>"><?php _e( "Email", "listings" ); ?></label>:</td>
				<td><input type="text" name="email" id="<?php echo $friend_form_email; ?>"></td>
			</tr>
			<tr>
				<td><label for="<?php echo $friend_form_friend_email; ?>"><?php _e( "Friends Email", "listings" ); ?></label>:</td>
				<td><input type="text" name="friends_email" id="<?php echo $friend_form_friend_email; ?>"></td>
			</tr>
			<tr>
				<td colspan="2"><label for="<?php echo $friend_form_message; ?>"><?php _e( "Message", "listings" ); ?></label>:<br>
					<textarea name="message" class="fancybox_textarea" id="<?php echo $friend_form_message; ?>"></textarea></td>
			</tr>
			<?php
			if ( $recaptcha_enabled && $recaptcha_public_key ) {
				echo "<tr><td colspan='2'>" . __( "reCAPTCHA", "listings" ) . ": <br><div id='" . $email_fancybox_form_recaptcha . "' class='recaptcha_holder'></div></td></tr>";
			} ?>
			<tr>
				<td colspan="2"><input type="submit" value="<?php _e( "Submit", "listings" ); ?>"> <i class="fa fa-refresh fa-spin loading_icon_form"></i></td>
			</tr>
		</table>
	</form>
<?php } else {
	echo do_shortcode( $email_friend_form_shortcode );
} ?>