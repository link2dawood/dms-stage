<?php
/*
	Automotive Fuel Efficiency Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/fuel_efficiency.php

	Version: 17.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : "");

global $lwp_options;

$fuel_efficiency_show = automotive_listing_get_option('fuel_efficiency_show', true);

if($fuel_efficiency_show){
	global $post;

	$Automotive_Listing = new Automotive_Listing($post->ID);

	$fuel_efficiency_image = automotive_listing_get_option('fuel_efficiency_image', array('url' => ICON_DIR . "fuel_pump.png") );
	$default_value_city    = automotive_listing_get_option('default_value_city', 'City');
	$default_value_hwy     = automotive_listing_get_option('default_value_hwy', 'Highway');
	$fuel_efficiency_title = automotive_listing_get_option('fuel_efficiency_title', 'Fuel Efficiency Rating');
	$fuel_efficiency_text  = automotive_listing_get_option('fuel_efficiency_text', '');

	$listing_mpg = $Automotive_Listing->get_mpg();
	$city_mpg    = $listing_mpg['city'];
	$highway_mpg = $listing_mpg['highway'];
?>
	<div class="efficiency-rating text-center padding-vertical-15">
		<h3><?php echo esc_html($fuel_efficiency_title); ?></h3>
		<ul>
			<li class="city_mpg"><small><?php echo esc_html($default_value_city); ?>:</small> <strong><?php echo (!empty($city_mpg) ? esc_html($city_mpg) : __("N/A", "listings")); ?></strong></li>
			<li class="fuel"><?php echo (!empty($fuel_efficiency_image) ? '<img src="'.$fuel_efficiency_image['url'].'" alt="" class="aligncenter">' : ""); ?></li>
			<li class="hwy_mpg"><small><?php echo esc_html($default_value_hwy); ?>:</small> <strong><?php echo (!empty($highway_mpg) ? $highway_mpg : __("N/A", "listings")); ?></strong></li>
		</ul>
		<p><?php echo esc_html($fuel_efficiency_text); ?></p>
	</div>
<?php
}

echo $after_widget;
