<?php
/*
	Automotive Vehicle History Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing/vehicle_history.php

	Version: 16.5
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$vehicle_history       = automotive_listing_get_option('vehicle_history', array());
$vehicle_history_image = (isset( $vehicle_history['url'] ) && ! empty( $vehicle_history['url'] ) ? $vehicle_history['url'] : false);

if ( $vehicle_history_image && $Automotive_Listing->is_verified ) {
  $vehicle_history_url = $Automotive_Listing->get_vehicle_history_link();
  $vehicle_history_alt = automotive_listing_get_option('vehicle_history_label', ''); ?>
  <a href="<?php echo esc_url($vehicle_history_url); ?>" target="_blank">
    <img src="<?php echo esc_url($vehicle_history_image); ?>" alt="<?php echo esc_attr($vehicle_history_alt); ?>" class="carfax<?php echo (is_singular('listings') ? '_title' : ''); ?>" />
  </a><?php
} ?>
