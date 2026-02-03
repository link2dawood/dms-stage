<?php
/*
	Automotive Contact Form Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/contact_form.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
global $awp_options, $lwp_options;

echo '<fieldset id="contact_form" class="form_contact ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '">';
echo '<div class="contact_result"></div>';
echo '<input type="text" name="name" class="form-control margin-bottom-25" placeholder="' . esc_attr( $name ) . '" />';
echo '<input type="email" name="email" class="form-control margin-bottom-25" placeholder="' . esc_attr( $email ) . '" />';
echo '<textarea name="message" class="form-control margin-bottom-25 contact_textarea" placeholder="' . esc_attr( $message ) . '" rows="7"></textarea>';

if(isset($awp_options['gdpr_form']) && !empty($awp_options['gdpr_form'])){
  echo '<div class="form-control gdpr-control margin-bottom-25">';
  echo '<label><input type="checkbox" name="gdpr"> ' . (isset($awp_options['gdpr_form_text']) && !empty($awp_options['gdpr_form_text']) ? esc_html($awp_options['gdpr_form_text']) : "") . '</label>';
  echo '</div>';
}

if(isset($lwp_options['recaptcha_enabled']) && !empty($lwp_options['recaptcha_enabled']) && !empty($lwp_options['recaptcha_public_key'])){
  echo "<div id=\"contact_form_recaptcha\" class=\"recaptcha_holder\"></div>";
}

echo '<input id="submit_btn" class="submit_contact_form" type="submit" value="' . esc_attr( $button ) . '">';
echo '</fieldset>';
