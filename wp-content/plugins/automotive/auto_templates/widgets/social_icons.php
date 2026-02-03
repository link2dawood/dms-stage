<?php
/*
	Automotive Social Icons Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/social_icons.php

	Version: 15.0
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
global $post;

$share_facebook_text    = __("Share link on Facebook", "listings");
$share_pinterest_text   = __("Share link on Pinterest", "listings");
$share_twitter_text     = __("Share link on Twitter", "listings");

$social_icons_show = automotive_listing_get_option('social_icons_show', true);

$Automotive_Listing = new Automotive_Listing($post->ID);

echo $before_widget;
echo (!empty($title) ? $before_title . $title . $after_title : "");

if($social_icons_show){
	$main_image = $Automotive_Listing->get_main_image(false, 'full');
	$main_image_src = (isset($main_image['src']) && !empty($main_image['src']) ? $main_image['src'] : ''); ?>
	<ul class="social-likes pull-right listing_share" data-url="<?php echo esc_url($Automotive_Listing->get_permalink()); ?>" data-title="<?php echo esc_attr($Automotive_Listing->get_title()); ?>">
		<li class="facebook" title="<?php echo $share_facebook_text; ?>"></li>
		<li class="pinterest" title="<?php echo $share_pinterest_text; ?>" data-media="<?php echo esc_url($main_image_src); ?>"></li>
		<li class="twitter" title="<?php echo $share_twitter_text; ?>"></li>
	</ul>
<?php }

echo $after_widget;