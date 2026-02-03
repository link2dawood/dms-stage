<?php
/*
	Automotive Trade-In Form Template File
	To overwrite this file copy it to automotive-child/auto_templates/forms/trade_in.php

	Version: 18.4
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$email                         = automotive_uniqid('email');
$phone                         = automotive_uniqid('phone');
$gdpr_checkbox_trade           = automotive_uniqid('gdpr_checkbox_trade');
$trade_fancybox_form_recaptcha = automotive_uniqid('trade_fancybox_form_recaptcha');
$tradein_form_shortcode        = automotive_listing_get_option('tradein_form_shortcode');

if ( ! $tradein_form_shortcode ) {
	$Automotive_Plugin    = Automotive_Plugin();
	$gdpr_form            = automotive_listing_get_option('gdpr_form', false);
	$recaptcha_enabled    = automotive_listing_get_option('recaptcha_enabled', true);
	$recaptcha_public_key = automotive_listing_get_option('recaptcha_public_key', '');

	$all_options = $Automotive_Plugin->get_single_listing_category( "options" );
	$options     = ( isset( $all_options['terms'] ) && ! empty( $all_options['terms'] ) ? $all_options['terms'] : array() );	?>
	<h3><?php _e( "Trade-In", "listings" ); ?></h3>

	<form name="trade_in" method="post" class="ajax_form" data-nonce="<?php echo wp_create_nonce("automotive-form-tradein"); ?>">
		<div class="container">
			<div class="row">

				<div class="col-md-6">
					<h4><?php _e( "Contact Information", "listings" ); ?></h4>

					<div class="row">
						<div class="col-md-6">
							<?php _e( "First Name", "listings" ); ?><br><input type="text" name="first_name"><br>
							<?php _e( "Work Phone", "listings" ); ?><br><input type="text" name="work_phone"><br>
							<?php _e( "Email", "listings" ); ?><br><input type="text" name="email">
						</div>

						<div class="col-md-6">
							<?php _e( "Last Name", "listings" ); ?><br><input type="text" name="last_name"><br>
							<?php _e( "Phone", "listings" ); ?><br><input type="text" name="phone"><br>
							<?php _e( "Preferred Contact", "listings" ); ?><br> <span class="styled_input"> <input
									type="radio" name="contact_method" value="<?php _e( 'email', 'listings' ); ?>"
									id="<?php echo $email; ?>"> <label for="<?php echo $email; ?>"><?php _e( "Email", "listings" ); ?></label>  <input
									type="radio" name="contact_method" value="<?php _e( 'phone', 'listings' ); ?>"
									id="<?php echo $phone; ?>"> <label for="<?php echo $phone; ?>"><?php _e( "Phone", "listings" ); ?></label> </span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<?php _e( "Comments", "listings" ); ?><br><textarea name="comments" style="width: 89%;" rows="5"></textarea>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<h4><?php _e( "Options", "listings" ); ?></h4>

					<select name="options[]" multiple data-update="false" class="options_multi">
						<?php

						if ( empty( $options ) ) {
							echo "<option value='" . __( "Not available", "listings" ) . "'>N/A</option>";
						} else {

							if($Automotive_Plugin->is_wpml_active()){
								$wpml_options = array();

								if(!empty($options)){
									foreach($options as $option){
										$translated_term = $Automotive_Plugin->display_listing_category_term($option, 'option');

										$wpml_options[$translated_term] = $translated_term;
									}
								}

								$options = $wpml_options;
							}

							array_multisort( array_map( 'strtolower', $options ), $options );

							foreach ( $options as $option ) {
								echo "<option value='" . $option . "'>" . $Automotive_Plugin->display_listing_category_term($option, 'option') . "</option>";
							}
						}

						?>
					</select>
				</div>

			</div>
		</div>

		<div style="clear:both;"></div>

		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h4><?php _e( "Vehicle Information", "listings" ); ?></h4>

					<div class="">
						<div class="row">

							<div class="col-md-6">
								<?php _e( "Year", "listings" ); ?><br><input type="text" name="year"><br>
								<?php _e( "Model", "listings" ); ?><br><input type="text" name="model"><br>
								<?php _e( "VIN", "listings" ); ?><br><input type="text" name="vin"><br>
								<?php _e( "Engine", "listings" ); ?><br><input type="text" name="engine"><br>
								<?php _e( "Transmission", "listings" ); ?><br><select name="transmission" class="css-dropdowns trade-in-dropdown"
								                                                      data-update="false">
									<option
										value="<?php _e( "Automatic", "listings" ); ?>"><?php _e( "Automatic", "listings" ); ?></option>
									<option
										value="<?php _e( "Manual", "listings" ); ?>"><?php _e( "Manual", "listings" ); ?></option>
								</select>
							</div>

							<div class="col-md-6">
								<?php _e( "Make", "listings" ); ?><br><input type="text" name="make"><br>
								<?php _e( "Exterior Colour", "listings" ); ?><br><input type="text" name="exterior_colour"><br>
								<?php _e( "Kilometres", "listings" ); ?><br><input type="text" name="kilometres"><br>
								<?php _e( "Doors", "listings" ); ?><br><select name="doors" class="css-dropdowns trade-in-dropdown"
								                                               data-update="false">
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select><br>
								<?php _e( "Drivetrain", "listings" ); ?><br><select name="drivetrain" class="css-dropdowns trade-in-dropdown"
								                                                    data-update="false">
									<option value="<?php _e( "2WD", "listings" ); ?>"><?php _e( "2WD", "listings" ); ?></option>
									<option value="<?php _e( "4WD", "listings" ); ?>"><?php _e( "4WD", "listings" ); ?></option>
									<option value="<?php _e( "AWD", "listings" ); ?>"><?php _e( "AWD", "listings" ); ?></option>
								</select>
							</div>

						</div>
					</div>
				</div>

				<div class="col-md-6">
					<h4><?php _e( "Vehicle Rating", "listings" ); ?></h4>

					<div class="">
						<div class="row">

							<div class="col-md-6">
								<?php _e( "Body (dents, dings, rust, rot, damage)", "listings" ); ?><br><select
									name="body_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select><br>
								<?php _e( "Engine (running condition, burns oil, knocking)", "listings" ); ?><br><select
									name="engine_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select><br>
								<?php _e( "Glass (chips, scratches, cracks, pitted)", "listings" ); ?><br><select
									name="glass_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select><br>
								<?php _e( "Exhaust (rusted, leaking, noisy)", "listings" ); ?><br><select
									name="exhaust_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select>
							</div>

							<div class="col-md-6">
								<?php _e( "Tires (tread wear, mismatched)", "listings" ); ?><br><select name="tire_rating"
								                                                                        class="css-dropdowns trade-in-dropdown"
								                                                                        data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select><br>
								<?php _e( "Transmission / Clutch (slipping, hard shift, grinds)", "listings" ); ?><br><select
									name="transmission_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select><br>
								<?php _e( "Interior (rips, tears, burns, faded/worn, stains)", "listings" ); ?><br><select
									name="interior_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
									<option value="10">10 - <?php _e( "best", "listings" ); ?></option>
									<option value="9">9</option>
									<option value="8">8</option>
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1 - <?php _e( "worst", "listings" ); ?></option>
								</select>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h4><?php _e( "Vehicle History", "listings" ); ?></h4>

					<?php _e( "Was it ever a lease or rental return?", "listings" ); ?> <br><select
						name="rental_rating" class="css-dropdowns trade-in-dropdown" data-update="false">
						<option value="<?php _e( "Yes", "listings" ); ?>"><?php _e( "Yes", "listings" ); ?></option>
						<option value="<?php _e( "No", "listings" ); ?>"><?php _e( "No", "listings" ); ?></option>
					</select><br>

					<?php _e( "Is the odometer operational and accurate?", "listings" ); ?> <br><select
						name="odometer_accurate" class="css-dropdowns trade-in-dropdown" data-update="false">
						<option value="<?php _e( "Yes", "listings" ); ?>"><?php _e( "Yes", "listings" ); ?></option>
						<option value="<?php _e( "No", "listings" ); ?>"><?php _e( "No", "listings" ); ?></option>
					</select><br>

					<?php _e( "Detailed service records available?", "listings" ); ?> <br><select
						name="service_records" class="css-dropdowns trade-in-dropdown" data-update="false">
						<option value="<?php _e( "Yes", "listings" ); ?>"><?php _e( "Yes", "listings" ); ?></option>
						<option value="<?php _e( "No", "listings" ); ?>"><?php _e( "No", "listings" ); ?></option>
					</select><br>
				</div>
				<div class="col-md-6">
					<h4><?php _e( "Title History", "listings" ); ?></h4>

					<?php _e( "Is there a lienholder?", "listings" ); ?> <br><input type="text" name="lienholder"><br>

					<?php _e( "Who holds this title?", "listings" ); ?> <br><input type="text" name="titleholder">
				</div>
			</div>
		</div>


		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h4><?php _e( "Vehicle Assessment", "listings" ); ?></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">

					<?php _e( "Does all equipment and accessories work correctly?", "listings" ); ?><br><textarea
						name="equipment" rows="5" style="width: 89%;"></textarea><br>

					<?php _e( "Did you buy the vehicle new?", "listings" ); ?><br><textarea name="vehiclenew"
					                                                                        rows="5"
					                                                                        style="width: 89%;"></textarea><br>

					<?php _e( "Has the vehicle ever been in any accidents? Cost of repairs?", "listings" ); ?>
					<br><textarea name="accidents" rows="5" style="width: 89%;"></textarea>
				</div>

				<div class="col-md-6">
					<?php _e( "Is there existing damage on the vehicle? Where?", "listings" ); ?><br><textarea
						name="damage" rows="5" style="width: 89%;"></textarea><br>

					<?php _e( "Has the vehicle ever had paint work performed?", "listings" ); ?><br><textarea
						name="paint" rows="5" style="width: 89%;"></textarea><br>

					<?php _e( "Is the title designated 'Salvage' or 'Reconstructed'? Any other?", "listings" ); ?>
					<br><textarea name="salvage" rows="5" style="width: 89%;"></textarea>
				</div>
			</div>
		</div>
		<?php if($gdpr_form){
				$gdpr_form_text = automotive_listing_get_option('gdpr_form_text', ''); ?>
		<div>
			<span class="styled_input">
				<input type="checkbox" name="gdpr" id="<?php echo $gdpr_checkbox_trade; ?>"> <label for="<?php echo $gdpr_checkbox_trade; ?>"><?php echo esc_html($gdpr_form_text); ?></label>
			</span>
		</div>
		<?php } ?>
		<?php

		if ( $recaptcha_enabled && $recaptcha_public_key ) {
			echo __( "reCAPTCHA", "listings" ) . ": <br><div id='" . $trade_fancybox_form_recaptcha . "' class='recaptcha_holder'></div>";
		}

		?>

		<input type="submit" value="<?php _e( "Submit", "listings" ); ?>"> <i
			class="fa fa-refresh fa-spin loading_icon_form"></i>

	</form>
<?php } else {
	echo do_shortcode( $tradein_form_shortcode );
} ?>
