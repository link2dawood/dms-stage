<?php
/*
	Automotive CarFax Vehicle History Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing/vehicle_history_carfax.php

	Version: 16.5
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$badge_url = $carfax_data[0]->VhrReportUrl;
$badge_src = (isset($carfax_data[0]->BadgesImageUrl) ? $carfax_data[0]->BadgesImageUrl : '#');

if(!empty($badge_src)){ ?>
<a href="<?php echo esc_url($badge_url); ?>" target="_blank">
  <div class="carfax<?php echo (is_singular('listings') ? '_title' : ''); ?>">
    <img src="<?php echo esc_url($badge_src); ?>">
  </div>
</a>
<?php
}
