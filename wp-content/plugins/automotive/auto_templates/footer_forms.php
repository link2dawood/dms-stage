<?php
/*
	Automotive Footer Forms Template File
	To overwrite this file copy it to automotive-child/auto_templates/footer_forms.php

	Version: 15.0
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$forms_on_all_pages = automotive_listing_get_option('forms_on_all_pages', true);

if((!$forms_on_all_pages) || is_singular('listings')){
	$Listing_Template   = Automotive_Plugin_Template();
	$email_friend_show  = automotive_listing_get_option('email_friend_show', true);
	$tradein_show       = automotive_listing_get_option('tradein_show', true);
	$make_offer_show    = automotive_listing_get_option('make_offer_show', true);
	$schedule_test_show = automotive_listing_get_option('schedule_test_show', true);
	$request_more_show  = automotive_listing_get_option('request_more_show', true);

	if($email_friend_show){ ?>
		<div id="email_fancybox_form" class="" style="display: none">
			<?php echo $Listing_Template->locate_template("forms/email_friend"); ?>
		</div><?php
	}

	if($tradein_show){ ?>
		<div id="trade_fancybox_form" class="" style="display: none">
			<?php echo $Listing_Template->locate_template("forms/trade_in"); ?>
		</div><?php
	}

 	if($make_offer_show){ ?>
		<div id="offer_fancybox_form" class="" style="display: none">
			<?php echo $Listing_Template->locate_template("forms/make_offer"); ?>
		</div><?php
	}

	if($schedule_test_show){ ?>
		<div id="schedule_fancybox_form" class="" style="display: none">
			<?php echo $Listing_Template->locate_template("forms/schedule"); ?>
		</div><?php
	}

	if($request_more_show){ ?>
		<div id="request_fancybox_form" class="" style="display: none">
			<?php echo $Listing_Template->locate_template("forms/request"); ?>
		</div><?php
	}
} ?>