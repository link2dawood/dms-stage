<?php
/*
	Automotive WooCommerce Integration Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/woo_integration.php

	Version: 15.0
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

global $post;

$Automotive_Listing = new Automotive_Listing($post->ID);

$woocommerce_integration_id = $Automotive_Listing->get_post_meta('woocommerce_integration_id', false);

if($woocommerce_integration_id){
	$Automotive_Plugin = Automotive_Plugin();

	echo $before_widget;
	echo (!empty($title) ? $before_title . $title . $after_title : "");

	wc_print_notices();

	$Automotive_Plugin->woocommerce_integration($woocommerce_integration_id);

	echo $after_widget;
}