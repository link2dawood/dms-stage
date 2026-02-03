<?php
/*
	Automotive Listing Content Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing_content.php

	Version: 18.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
global $post;

$Automotive_Plugin   = Automotive_Plugin();
$Automotive_Template = Automotive_Plugin_Template();
$Automotive_Listing  = new Automotive_Listing($post->ID);

wp_enqueue_script( 'bxslider' );
wp_enqueue_style( 'social-likes' );

//********************************************
//	Language Variables
//***********************************************************
$sold_text              = __("Sold", "listings");
$no_location_text       = __("No location available", "listings");
$none_text              = __("None", "listings");
$share_facebook_text    = __("Share link on Facebook", "listings");
$share_google_text      = __("Share link on Google+", "listings");
$share_pinterest_text   = __("Share link on Pinterest", "listings");
$share_twitter_text     = __("Share link on Twitter", "listings");

$main_image      = $Automotive_Listing->get_main_image(false);
$secondary_title = $Automotive_Listing->get_secondary_title();

// buttons
$next_link       = (get_permalink(get_adjacent_post(false,'',false)) == get_permalink() ? "#" : get_permalink(get_adjacent_post(false,'',false)));
$prev_link       = (get_permalink(get_adjacent_post(false,'',true)) == get_permalink() ? "#" : get_permalink(get_adjacent_post(false,'',true)));
$request_target  = automotive_listing_get_option('request_more_target', '');
$schedule_target = automotive_listing_get_option('schedule_test_target', '');
$offer_target    = automotive_listing_get_option('make_offer_target', '');
$trade_target    = automotive_listing_get_option('tradein_target', '');
$friend_target   = automotive_listing_get_option('email_friend_target', '');
$pdf_link        = $Automotive_Listing->get_pdf_link();

// tabs
$listing_tabs        = $Automotive_Plugin->get_listing_tabs();
$listing_tab_display = apply_filters('automotive_listing_tab_display', array(
	automotive_listing_get_option('first_tab', ''),
	automotive_listing_get_option('second_tab', ''),
	automotive_listing_get_option('third_tab', ''),
	automotive_listing_get_option('fourth_tab', ''),
	automotive_listing_get_option('fifth_tab', '')
));

$tabs_are_custom_by_filter = $listing_tabs !== $Automotive_Plugin->get_raw_listing_tabs();

$sold_listing_comment    = automotive_listing_get_option('sold_listing_comment', '');
$hide_sold_price 				 = automotive_listing_get_option('hide_sold_price', false);
$listing_comment_footer  = automotive_listing_get_option('listing_comment_footer', false);
$listing_comments        = automotive_listing_get_option('listing_comments', false);
$tax_label_page          = automotive_listing_get_option('tax_label_page', false);
$price_text_replacement  = automotive_listing_get_option('price_text_replacement', false);
$price_text_all_listings = automotive_listing_get_option('price_text_all_listings', true);
$show_all_photos				 = automotive_listing_get_option('show_all_photos', false);
$carfax_badge_api				 = $Automotive_Plugin->carfax_badge_api_enabled($Automotive_Listing);

$carfax_badge_data = array(false, false);
if($carfax_badge_api){
	$carfax_badge_data = $Automotive_Plugin->get_carfax_badge($Automotive_Listing);

	if($carfax_badge_data[0]){
		$inventory_classes[] = "carfax-badge-api";
	}
}

$cargurus_badge          = automotive_listing_get_option('cargurus_badge', false);
$cargurus_single_listing = automotive_listing_get_option('cargurus_single_listing_page', false);

// if using Gmap
if(!empty($listing_tab_display[3])){
	wp_enqueue_script( 'google-maps' );
}

do_action('automotive_before_listing_content', $Automotive_Listing);

$slider_thumbnails = $Automotive_Plugin->get_automotive_image_sizes(); ?>
<div class="inner-page inventory-listing" itemscope itemtype="http://schema.org/Vehicle">
	<?php
	if ( post_password_required( $post ) ) {
	  echo get_the_password_form( $post );
	} else { ?>
	<meta itemprop="image" content="<?php echo esc_url($main_image['src']); ?>"></meta>
	<div class="inventory-heading margin-bottom-10 clearfix container">
		<div class="row">
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 xs-padding-none">
				<h2 itemprop="name"><?php echo $Automotive_Listing->get_title(); ?></h2>
				<?php echo ($secondary_title ? "<span class='margin-top-10'>" . $secondary_title . "</span>" : ""); ?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 text-right xs-padding-none" <?php echo (!$Automotive_Listing->is_sold ? 'itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : ''); ?>>

				<?php
				// display original VHR
				if(!$Automotive_Plugin->carfax_badge_api_enabled() || !$carfax_badge_data[0]){
					echo $Automotive_Template->locate_template( "listing/vehicle_history", array(
						"Automotive_Listing" => $Automotive_Listing
					) );
				}

				// car gurus badge
				if($cargurus_badge && $cargurus_single_listing){
					echo $Automotive_Template->locate_template( "listing/cargurus", array(
						"Automotive_Listing" => $Automotive_Listing
					) );
				}

				if(!empty($Automotive_Listing->get_price())){
					echo ($Automotive_Listing->has_sale_price ? "<h2 class='strikeout original_price'>" . $Automotive_Plugin->format_currency( $Automotive_Listing->get_original_price() ) . "</h2>" : "");

					if($price_text_replacement && !$price_text_all_listings){
						echo do_shortcode($price_text_replacement);
					} else {

						if(($Automotive_Listing->is_sold) && (!$hide_sold_price) || (!$Automotive_Listing->is_sold)) {
							echo '<h2>' . $Automotive_Plugin->format_currency( $Automotive_Listing->get_price(), true ) . '</h2>';

							if(!empty($Automotive_Listing->get_tax_label(false))){
								echo do_shortcode($Automotive_Listing->get_tax_label(false));
							} elseif($tax_label_page) {
								echo '<em>' . $tax_label_page . '</em>';
							}
						}
					}
				} elseif( (empty($Automotive_Listing->get_price()) && $price_text_replacement ) || ($price_text_replacement && $price_text_all_listings) ){
					echo do_shortcode($price_text_replacement);
				}

				if($Automotive_Listing->is_sold){
					echo '<span class="sold_text' . ( empty( $Automotive_Listing->get_price() ) || ($hide_sold_price) ? ' no_price' : '' ) . '">' . $sold_text . '</span>';
					echo '<link itemprop="availability" href="http://schema.org/SoldOut" />';
				} else {
					echo '<link itemprop="availability" href="http://schema.org/InStock" />';
				}

				// display carfax VHR
				if($Automotive_Plugin->carfax_badge_api_enabled() && $carfax_badge_data[0]){
					echo $Automotive_Template->locate_template( "listing/vehicle_history_carfax", array(
						"Automotive_Listing" => $Automotive_Listing,
						"carfax_data"        => $carfax_badge_data[1]
					) );
				}
				?>
			</div>
		</div>
	</div>
    <div class="container content-nav-buttons">
        <div class="row">
            <div class="col-lg-12 content-nav margin-bottom-30">
                <ul>
                    <?php if( automotive_listing_get_option('previous_vehicle_show', false) ){ ?>
                        <li class="prev1 gradient_button<?php echo ($prev_link == '#' ? ' transparent' : ''); ?>"><a href="<?php echo esc_url($prev_link); ?>"><?php echo esc_html(automotive_listing_get_option('previous_vehicle_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('request_more_show', false) ){ ?>
                        <li class="request gradient_button"><a href="<?php echo esc_url(automotive_listing_get_option('request_more_link', '#request_fancybox_form')); ?>"<?php echo (!empty($request_target) ? " target=\"" . esc_attr($request_target) . "\"" : ""); ?> class="fancybox_div"><?php echo esc_html(automotive_listing_get_option('request_more_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('schedule_test_show', false) ){ ?>
                        <li class="schedule gradient_button"><a href="<?php echo esc_url(automotive_listing_get_option('schedule_test_link', '#schedule_fancybox_form')); ?>"<?php echo (!empty($schedule_target) ? " target=\"" . esc_attr($schedule_target) . "\"" : ""); ?> class="fancybox_div"><?php echo esc_html(automotive_listing_get_option('schedule_test_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('make_offer_show', false) ){ ?>
                        <li class="offer gradient_button"><a href="<?php echo esc_url(automotive_listing_get_option('make_offer_link', '#offer_fancybox_form')); ?>"<?php echo (!empty($offer_target) ? " target=\"" . esc_attr($offer_target) . "\"" : ""); ?> class="fancybox_div"><?php echo esc_html(automotive_listing_get_option('make_offer_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('tradein_show', false) ){ ?>
                        <li class="trade gradient_button"><a href="<?php echo esc_url(automotive_listing_get_option('tradein_link', '#trade_fancybox_form')); ?>"<?php echo (!empty($trade_target) ? " target=\"" . esc_attr($trade_target) . "\"" : ""); ?> class="fancybox_div"><?php echo esc_html(automotive_listing_get_option('tradein_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('pdf_brochure_show', false) ){ ?>
                        <li class="pdf gradient_button"><a href="<?php echo ($pdf_link ? esc_url($pdf_link) : ''); ?>" class="<?php echo ($pdf_link ? '' : 'generate_pdf'); ?>" <?php echo ($pdf_link ? "target='_blank'" : ''); ?>><?php echo esc_html(automotive_listing_get_option('pdf_brochure_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('print_vehicle_show', false) ){ ?>
                        <li class="print gradient_button"><a class="print_page"><?php echo esc_html(automotive_listing_get_option('print_vehicle_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('email_friend_show', false) ){ ?>
                        <li class="email gradient_button"><a href="<?php echo esc_url(automotive_listing_get_option('email_friend_link', '#email_fancybox_form')); ?>"<?php echo (!empty($friend_target) ? " target=\"" . esc_attr($friend_target) . "\"" : ""); ?> class="fancybox_div"><?php echo esc_html(automotive_listing_get_option('email_friend_label', '')); ?></a></li>
                    <?php } ?>

                    <?php if( automotive_listing_get_option('next_vehicle_show', false) ){ ?>
                        <li class="next1 gradient_button<?php echo ($next_link == '#' ? ' transparent' : ''); ?>"><a href="<?php echo esc_url($next_link); ?>"><?php echo esc_html(automotive_listing_get_option('next_vehicle_label', '')); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12 left-content">
			<!--OPEN OF SLIDER-->
			<?php
			$full_images  = '';
			$thumb_images = '';

			$full_images_array    = $Automotive_Listing->get_gallery_images(false, 'full');
			$gallery_thumb_array  = $Automotive_Listing->get_gallery_images(false, 'auto_thumb');
			$gallery_slider_array = $Automotive_Listing->get_gallery_images(false, 'auto_slider');
			$total_images 				= count($full_images_array);

			$thumb_img_sizes = $Automotive_Plugin->get_automotive_image_sizes('auto_thumb');

			if($total_images){
				$li_close = "</li>\n";

				for ($image_i=0; $image_i < $total_images; $image_i++) {
					$alt     = $full_images_array[$image_i]['alt'];
					$li_open = "<li data-thumb=\"" . $gallery_thumb_array[$image_i]['src'] . "\">";

					$full_images  .= $li_open . "<img src=\"" . $gallery_slider_array[$image_i]['src'] . "\" alt=\"" . esc_attr($alt) . "\" data-full-image=\"" . esc_url($full_images_array[$image_i]['src']) . "\" data-full-image-width=\"" . $full_images_array[$image_i]['width'] . "\" data-full-image-height=\"" . $full_images_array[$image_i]['height'] . "\" width=\"" . $slider_thumbnails['slider']['width'] . "\" height=\"" . $slider_thumbnails['slider']['height'] . "\" /> " . $li_close;
					$thumb_images .= $li_open . "<a href=\"#\"><img src=\"" . $gallery_thumb_array[$image_i]['src'] . "\" alt=\"" . esc_attr($alt) . "\" width=\"" . (int)$thumb_img_sizes['width'] . "\" height=\"" . (int)$thumb_img_sizes['height'] . "\" /></a> " . $li_close;
				}
			}	?>
			<div class="listing-slider">
				<?php $Automotive_Plugin->get_badge_html($Automotive_Listing->get_badge_text(), $Automotive_Listing->is_sold); ?>
				<section class="slider home-banner">
					<div class="flexslider loading" id="home-slider-canvas">
						<ul class="slides">
							<?php echo (!empty($full_images) ? $full_images : ""); ?>
						</ul>
					</div>
				</section>
				<section class="home-slider-thumbs">
					<div class="flexslider" id="home-slider-thumbs" data-item-width="<?php echo (int)$thumb_img_sizes['width']+4; ?>">
						<ul class="slides">
							<?php echo (!empty($thumb_images) ? $thumb_images : ""); ?>
						</ul>
					</div>
					<?php
					if($show_all_photos){
						echo '<div class="show-all-photos">Show All Photos</div>';
					} ?>
				</section>
			</div>
			<!--CLOSE OF SLIDER-->
			<!--Slider End-->
			<div class="clearfix"></div>
			<div class="single-listing-tabs margin-top-50">
				<ul id="myTab" class="nav nav-tabs" role="tablist">
					<?php

						if(!empty($listing_tabs)){
							$i = 0;

							foreach($listing_tabs as $tab_id => $tab){
								if((isset($listing_tab_display[$i]) && $listing_tab_display[$i]) || !isset($listing_tab_display[$i])){
									$html_tab_id = (isset($tab['html_tab_id']) && !empty($tab['html_tab_id']) ? $tab['html_tab_id'] : $tab_id);
									$tab_text    = (isset($tab['text']) ? $tab['text'] : '');

									// if(($tab_text !== $listing_tab_display[$i]) && !$tabs_are_custom_by_filter){
										$tab_text = $listing_tab_display[$i];
									// }

									echo "<li class=\"nav-item\"><a href=\"#" . esc_attr($html_tab_id) . "\" class=\"nav-link\" role=\"tab\" data-toggle=\"tab\">" . esc_html($tab_text) . "</a></li>";
								}

								$i++;
							}
						}

					?>
				</ul>
				<div id="myTabContent" class="tab-content margin-top-15 margin-bottom-20">
					<?php
					if(!empty($listing_tabs)){
						$i = 0;

						foreach($listing_tabs as $tab_id => $tab){
							if((isset($listing_tab_display[$i]) && $listing_tab_display[$i]) || !isset($listing_tab_display[$i])){
								$html_tab_id = (isset($tab['html_tab_id']) && !empty($tab['html_tab_id']) ? $tab['html_tab_id'] : $tab_id);
								$active_tab  = (isset($tab['active_tab']) && $tab['active_tab']);
								$name        = (isset($tab['name']) && !empty($tab['name']) ? $tab['name'] : false);

								echo "<div class=\"tab-pane fade" . ($active_tab ? " in active" : "") . "\" id=\"" .$html_tab_id . "\" " . ($name == 'content' ? " itemprop=\"description\"" : "") . ">";

								if($name == 'content'){
									echo $Automotive_Listing->get_vehicle_overview();
								} elseif($name == 'multi_options'){
									echo "<ul class=\"fa-ul\" data-list=\"" . $Automotive_Listing->get_features_and_options('pdf') . "\">";
									echo $Automotive_Listing->get_features_and_options('html');
									echo "</ul>";
								} elseif($name == 'technical_specifications'){
									echo $Automotive_Listing->get_technical_specifications();
								} elseif($name == 'other_comments'){
									echo $Automotive_Listing->get_other_comments();
								} elseif($name == 'location_map'){
									$location  = $Automotive_Listing->get_location();
									$latitude  = $location['latitude'];
									$longitude = $location['longitude'];
									$zoom      = $location['zoom'];

									if(!empty($latitude) && !empty($longitude)){
										$has_map_style = automotive_listing_get_option('single_listing_map_styling', false); ?>
										<div class='google_map_init contact' data-longitude='<?php echo esc_attr($longitude); ?>' data-latitude='<?php echo esc_attr($latitude); ?>' data-zoom='<?php echo esc_attr($zoom); ?>' data-scroll="false" style="height: 350px;" data-parallax="false"
											<?php echo ($has_map_style ? "data-style='" . $has_map_style . "'" : ""); ?>></div>
									<?php } else { ?>
										<?php echo $no_location_text; ?>
									<?php }
								} else {
									echo apply_filters('automotive_listing_custom_frontend_tab-' . $tab_id, '',	$Automotive_Listing);
								}

								echo "</div>";
							}

							$i++;
						}
					} ?>
				</div>
			</div>

			<?php
			if($Automotive_Listing->is_sold) {
				echo wpautop( do_shortcode( $sold_listing_comment ) );
			} ?>
		</div>
		<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 right-content">
      <div class="side-content">
			    <?php
					$current_sidebar = $Automotive_Listing->get_post_meta('sidebar_area', false);
          $sidebar_used    = ($current_sidebar && $current_sidebar !== "none" ? $current_sidebar : "single_listing_sidebar");

          if( is_active_sidebar( $sidebar_used ) ) {
            dynamic_sidebar( $sidebar_used );
          } else {
						$fuel_efficiency_show  = automotive_listing_get_option('fuel_efficiency_show', false);
						$display_vehicle_video = automotive_listing_get_option('display_vehicle_video', false);
						$social_icons_show     = automotive_listing_get_option('social_icons_show', false);

						$sidebar_instance = array(
							'before_widget' => apply_filters('automotive_listing_sidebar_before_widget', ''),
							'after_widget'  => apply_filters('automotive_listing_sidebar_after_widget', ''),
							'before_title'  => apply_filters('automotive_listing_sidebar_before_title', ''),
							'after_title'   => apply_filters('automotive_listing_sidebar_after_title', '')
						);

						the_widget('Listing_Info_Table', array(), $sidebar_instance);

            if($Automotive_Listing->get_post_meta('woocommerce_integration_id')){
              the_widget('Listing_Woo_Integration', array(), $sidebar_instance);
            }

						if($fuel_efficiency_show){
							the_widget('Listing_Fuel_Efficiency', array(), $sidebar_instance);
						}

						if($display_vehicle_video && !empty($Automotive_Listing->get_video())){
							the_widget('Listing_Video', array(), $sidebar_instance);
						}

						if($social_icons_show){
							the_widget('Listing_Social_Icons', array(), $sidebar_instance);
						}

						echo '<div class="clearfix"></div>';

						if(automotive_listing_get_option('calculator_show', true)){
							$loan_calculator_instance = $sidebar_instance;
							$loan_calculator_instance['before_widget'] = '<div class="widget loan_calculator margin-top-40">';

							$calculator_below_text   = automotive_listing_get_option('calculator_below_text', '');
							$calculator_rate         = automotive_listing_get_option('calculator_rate', '');
							$calculator_down_payment = automotive_listing_get_option('calculator_down_payment', '');
							$calculator_loan         = automotive_listing_get_option('calculator_loan', '');

              the_widget(
								"Loan_Calculator",
								array(
									"text_below"   => $calculator_below_text,
									"rate"         => $calculator_rate,
									"down_payment" => $calculator_down_payment,
									"loan_years"   => $calculator_loan,
									"price"        => $Automotive_Listing->get_price()
								),
								$loan_calculator_instance
							);
            }
					} ?>
      </div>

			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>

		<div class="col-xl-12 col-lg-12">
			<?php if($listing_comment_footer && !$Automotive_Listing->is_sold){ ?>
				<div class="listing_bottom_message margin-top-30">
					<?php echo do_shortcode(wpautop($listing_comment_footer)); ?>
				</div>
			<?php } ?>

			<?php
			// recent vehicle scroller
			if(automotive_listing_get_option('recent_vehicles_show', false)){

				$related_category = automotive_listing_get_option( 'related_category' . (defined("ICL_LANGUAGE_CODE") ? '_' . ICL_LANGUAGE_CODE : '') );
				$other_options 		= ($related_category ? array("related_val" => $Automotive_Listing->get_term($related_category), "current_id" => $Automotive_Listing->get_id()) : array());

				if(automotive_listing_get_option('recent_automatic_scrolling', false)){
					$other_options['autoscroll'] = "true";
				}

				$recent_related_vehicles = automotive_listing_get_option('recent_related_vehicles', false);

				echo vehicle_scroller(
					automotive_listing_get_option('recent_vehicles_title', ''),
					automotive_listing_get_option('recent_vehicles_desc', ''),
					automotive_listing_get_option('recent_vehicles_limit', ''),
					(!$recent_related_vehicles ? "related" : "newest"),
					null,
					$other_options
				);
			} ?>
		</div>
	</div>

	<?php
	if( $listing_comments ){
		echo '<div class="comments page-content margin-top-30 margin-bottom-40">';
		comments_template();
		echo '</div>';
	}

} ?>
</div>

<?php do_action('automotive_after_listing_content', $Automotive_Listing); ?>
