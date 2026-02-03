<?php
/*
	Automotive Contact Form Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/contact_form.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo $before_widget;
echo $before_title . $title . $after_title; ?>
	<form method="post" action="" class="form_contact">
		<div class="contact_result"></div>

		<input type="text" value="" name="name" placeholder="<?php echo $name; ?>">
		<input type="text" value="" name="email" placeholder="<?php echo $email; ?>">

		<textarea name="message" placeholder="<?php echo $message; ?>"></textarea>
		<?php if(!empty($gdpr)){ ?>
			<label class='gdpr_label'><input type="checkbox" name="gdpr"> <?php echo esc_html($gdpr); ?></label>
		<?php } ?>
		<input type="submit" value="<?php echo $button; ?>" class="md-button submit_contact_form">
	</form>
<?php
echo $after_widget;