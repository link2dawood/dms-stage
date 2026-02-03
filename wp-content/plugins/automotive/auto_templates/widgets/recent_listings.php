<?php
/*
	Automotive Recent Listings Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/recent_listings.php

	Version: 15.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$Automotive_Plugin = Automotive_Plugin();

echo $before_widget;
echo $before_title . $title . $after_title;


$listings = get_posts(
	array(
		"post_type"         => "listings",
		"posts_per_page"    => $number,
		"orderby"           => "post_date",
		"order"             => "DESC"
	)
);

if(!empty($listings)){
	foreach($listings as $listing){
		$options   = unserialize(get_post_meta($listing->ID, "listing_options", true));
		$image_src = $Automotive_Plugin->get_single_listing_image($listing->ID);

		echo '<div class="car-block recent_car">
            <div class="car-block-bottom">
            	<div class="img-flex">
	            	<a href="' . esc_url( get_permalink($listing->ID) ) . '">
	            		<span class="align-center"><i class="fa fa-2x fa-plus-square-o"></i></span>
	            	</a>
	            	<img src="' . $image_src . '" alt="" class="img-responsive">
	            </div>
                <h6><strong>' . $listing->post_title . '</strong></h6>
                ' . (!empty($options['short_desc']) ? '<h6>' . esc_html( $options['short_desc'] ) . '</h6>' : '') . '
                <h5>' . esc_html( $Automotive_Plugin->format_currency($options['price']['value']) ) . '</h5>
            </div>
        </div>';
	}
}

echo $after_widget;