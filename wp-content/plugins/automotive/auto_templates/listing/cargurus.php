<?php
/*
	Automotive CarGurus Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing/vehicle_history_carfax.php

	Version: 17.6
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$vin_category   = automotive_listing_get_option('cargurus_vin_category', false);
$price_category = automotive_listing_get_option('cargurus_price_category', false);
$cargurus_style = automotive_listing_get_option('cargurus_style', 'STYLE1');

$vin_value      = $Automotive_Listing->get_term($vin_category);
$price_value    = $Automotive_Listing->get_term($price_category);
$cargurus_class = 'style-1';

if($cargurus_style == "STYLE2"){
  $cargurus_class = "style-2";
}

if($vin_value && $price_value){
?>
<span class="cargurus-badge badge-<?php echo $cargurus_class; ?>" data-cg-vin="<?php echo esc_attr($vin_value); ?>" data-cg-price="<?php echo esc_attr($price_value); ?>"></span>
<?php } ?>
