<?php
//********************************************
//	Custom meta boxes
//***********************************************************
function automotive_listing_add_custom_boxes() {
	add_meta_box( "listing", __( "Listing Tabs", "listings" ), "automotive_listing_listing_tabs", "listings", "normal", "core", null );
	add_meta_box( "gallery", __( "Listing Options", "listings" ), "automotive_listing_gallery_images", "listings", "normal", "core", null );
}

function automotive_listing_listing_tabs() {
	global $post, $Listing;

	$Automotive_Listing = new Automotive_Listing($post->ID);
	$listing_tabs       = $Listing->get_listing_tabs();

	$tabs_are_custom_by_filter = $listing_tabs !== $Listing->get_raw_listing_tabs();

	?>
	<div id="listing_tabs" class="automotive_meta_tabs loading_status_tabs">
		<div class="loading_tabs_overlay">
			<span class="spinner is-active"></span>
		</div>

		<div style="display:none;" class="hidden_content">
			<ul>
				<?php
				// we'll keep these options here for now so existing users don't lose this functionality
				$listing_tab_display = apply_filters('automotive_listing_tab_display', array(
					automotive_listing_get_option('first_tab', false),
					automotive_listing_get_option('second_tab', false),
					automotive_listing_get_option('third_tab', false),
					automotive_listing_get_option('fourth_tab', false),
					automotive_listing_get_option('fifth_tab', false)
				) );

				if(!empty($listing_tabs)){
					$i = 0;

					foreach($listing_tabs as $tab_id => $tab){
						if((isset($listing_tab_display[$i]) && $listing_tab_display[$i]) || !isset($listing_tab_display[$i])){	// !isset for backwards compat
							$tab_text = $tab['text'];

							if(($tab_text !== $listing_tab_display[$i])){
								$tab_text = $listing_tab_display[$i];
							}

							echo "<li" . ($tab['type'] == 'map' ? ' data-action="map"' : '') . ">";
							echo "<a href='#tabs-" . (int)($i+1) . "'>";
							echo (isset($tab['icon']) && !empty($tab['icon']) ? "<i class='" . esc_attr($tab['icon']) . "'></i>" : "");
							echo "<span>" . esc_html($tab_text) . "</span>";
							echo "</a>";
							echo "</li>\n";
						}

						$i++;
					}
				} ?>
			</ul>

			<?php

			if(!empty($listing_tabs)){
				$i = 0;

				foreach($listing_tabs as $tab_id => $tab){
					if((isset($listing_tab_display[$i]) && $listing_tab_display[$i]) || !isset($listing_tab_display[$i])){
						$type = (isset($tab['type']) && !empty($tab['type']) ? $tab['type'] : 'wp_editor');
						$name = (isset($tab['name']) && !empty($tab['name']) ? $tab['name'] : false);
						$hint = (isset($tab['hint']) && !empty($tab['hint']) ? $tab['hint'] : false);

						echo "<div id=\"tabs-" . (int)($i+1) . "\">";
						echo "<div class='tab_content'>";

						if($hint){
							echo "<i class=\"fa fa-info-circle auto_info_tooltip\" data-title=\"" . esc_attr($hint) . "\"></i>";
						}

						if($type && $name){
							$value = '';

							// post_content
							if($name == 'content'){
								$value = $Automotive_Listing->post_content;
							} elseif($name == 'technical_specifications'){
								$value = $Automotive_Listing->get_technical_specifications(false);
							} elseif($name == 'other_comments'){
								$value = $Automotive_Listing->get_other_comments(false);
							} else {
								$value = $Automotive_Listing->get_post_meta($name);
							}

							if($type == 'wp_editor'){
								wp_editor( $value, $name, array( "textarea_rows" => 12 ) );
							} elseif($type == 'multi_options'){
								$options = $Listing->get_category_terms('options');

								echo "<div class=\"features-options-container\">";
								if ( ! empty( $options ) ) {
									/* Default Options */
									$default_options  = get_option( "options_default_auto", array() );
									$selected_options = $Automotive_Listing->get_features_and_options('array');

									if(!is_array($default_options)){
										$default_options = array();
									}

									natcasesort( $options );

									foreach($options as $single_option){
										$checked = (
											( in_array( $single_option, $selected_options ) ) ||
											( in_array( esc_html(stripslashes($single_option)), $selected_options ) ) ||
											( $Listing->is_edit_page( 'new' ) && in_array( addslashes($single_option), $default_options ) ) ||
											( $Listing->is_edit_page( 'new' ) && in_array($single_option, $default_options ) )
										);

										echo "<div class=\"single-feature-option\">";
										echo "<label><input type='checkbox' value='" . esc_html($single_option) . "' name='" . esc_attr($name) . "[]'" . ( $checked ? " checked='checked'" : "" ) . "> <span>" . stripslashes( esc_html($single_option) ) . "</span></label>\n";
										echo "</div>";
									}
								}

								echo "</div>"; ?>

								<h4>
				          <a href="#" class="hide-if-no-js add_new_name" data-id="options">
				              + <?php _e( "Add New Option", "listings" ); ?>
				          </a>
								</h4>

								<div class='add_new_content options_sh'>
									<input type="text" class="options" placeholder="<?php esc_html_e("Option Text", "listings"); ?>">
									<button class='button submit_new_name' data-type='options' data-exact="options" data-nonce="<?php echo wp_create_nonce("add_listing_value_options"); ?>">
										<?php _e( "Add New Option", "listings" ); ?>
									</button>
				        </div><?php
							} elseif($type == 'map'){
						    $has_autocomplete = automotive_listing_get_option('google_maps_autocomplete', false);
								$location         = $Automotive_Listing->get_location(); ?>
								<div class="vehicle-location-data">
			            <?php if($has_autocomplete){ ?>
			            <div class="vehicle-location-row">
			              <div class="vehicle-location-label"><?php _e("Address", "listings"); ?>:</div>
			              <div class="vehicle-location-value">
			                <input type="text" placeholder="<?php _e("Get latitude and longitude from an address", "listings"); ?>" id="address_autocomplete">
			              </div>
			            </div>
			            <?php } ?>

									<div class="vehicle-location-row">
										<div class="vehicle-location-label"><?php _e( "Latitude", "listings" ); ?>:</div>
										<div class="vehicle-location-value">
											<input type='text' name='<?php echo esc_attr($name); ?>[latitude]' class='location_value' data-location='latitude' value='<?php echo esc_attr($location['latitude']); ?>'/>
										</div>
									</div>

									<div class="vehicle-location-row">
										<div class="vehicle-location-label"><?php _e( "Longitude", "listings" ); ?>:</div>
										<div class="vehicle-location-value">
											<input type='text' name='<?php echo esc_attr($name); ?>[longitude]' class='location_value' data-location='longitude' value='<?php echo esc_attr($location['longitude']); ?>'/>
										</div>
									</div>

									<div class="vehicle-location-row">
										<div class="vehicle-location-label"><?php _e( "Zoom", "listings" ); ?>:</div>
										<div class="vehicle-location-value">
											<span class='zoom_level_text'></span>
											<input type='hidden' readonly="readonly" class='zoom_level' name='<?php echo esc_attr($name); ?>[zoom]' value='<?php echo esc_attr($location['zoom']); ?>'/>
										</div>
									</div>
								</div>

								<br/>

								<div id='google-map'<?php echo " data-latitude='" . esc_attr($location['latitude']) . "' data-longitude='" . esc_attr($location['longitude']) . "'"; ?>></div>
								<div id="slider-vertical" style="height: 400px;" data-value="<?php echo esc_attr($location['zoom']); ?>"></div><?php
							} else {
								echo apply_filters('automotive_listing_custom_backend_tab-' . $tab_id, '', array(
									$Automotive_Listing
								));
							}
						}

						echo "</div>";
						echo "</div>";
					}

					$i++;
				}
			} ?>
		</div>
	</div>

	<?php
}

function automotive_listing_gallery_images() {
	global $post;

	$Automotive_Listing = new Automotive_Listing($post->ID);
	$Listing            = Automotive_Plugin();

	$is_hotlink = $Listing->is_hotlink();

	$gallery_images                  = $Automotive_Listing->get_gallery_images(true, 'full', false);
	$woocommerce_listing_integration = automotive_listing_get_option('woocommerce_listing_integration', false);

	// translations
	$image        = __( "Image", "listings" );
	$change_image = __( "Change image", "listings" );
	$set_default  = __( "Set default image", "listings" );
	$delete_image = __( "Delete image", "listings" );
	$no_images    = __( "No gallery images", "listings" );
	$url_text     = __( "URL", "listings" ); ?>
	<div id="meta_tabs" class="automotive_meta_tabs loading_status_tabs">
		<div class="loading_tabs_overlay">
			<span class="spinner is-active"></span>
		</div>

		<div style="display:none;" class="hidden_content">
			<ul>
				<li><a href="#tab-images"><i class="fa fa-picture-o"></i> <span><?php _e( "Gallery Images", "listings" ); ?></span></a></li>
				<li><a href="#tab-price"><i class="fa fa-usd"></i> <span><?php _e( "Pricing", "listings" ); ?></span></a></li>
				<li><a href="#tab-tax"><i class="fa fa-university"></i> <span><?php _e( "Tax Labels", "listings" ); ?></span></a></li>
				<?php echo ( $woocommerce_listing_integration ? '<li><a href="#tab-woo"><i class="fa fa-shopping-cart"></i> <span>' . __( "WooCommerce", "listings" ) . '</span></a></li>' : ""); ?>
				<li><a href="#tab-fuel"><i class="fa fa-car"></i> <span><?php _e( "Fuel Efficiency", "listings" ); ?></span></a></li>
				<li><a href="#tab-pdf"><i class="fa fa-file-pdf-o"></i> <span><?php _e( "PDF Replacement", "listings" ); ?></span></a></li>
				<li><a href="#tab-video"><i class="fa fa-video-camera"></i> <span><?php _e( "Video", "listings" ); ?></span></a></li>
				<li><a href="#tab-badge"><i class="fa fa-certificate"></i> <span><?php _e( "Listing Badge", "listings" ); ?></span></a></li>
				<li><a href="#tab-categories"><i class="fa fa-list"></i> <span><?php _e( "Listing Categories", "listings" ); ?></span></a></li>
				<li><a href="#tab-additional"><i class="fa fa-list-ul"></i> <span><?php _e( "Additional Categories", "listings" ); ?></span></a></li>
				<li><a href="#tab-others"><i class="fa fa-cog"></i> <span><?php _e( "Widget Settings", "listings" ); ?></span></a></li>
				<li><a href="#tab-status"><i class="fa fa-sliders"></i> <span><?php _e( "Status", "listings" ); ?></span></a></li>
				<li><a href="#tab-shortcode"><i class="fa fa-code"></i> <span><?php _e( "Revolution Slider Shortcode", "listings" ); ?></span></a></li>
			</ul>

			<div id="tab-images">
				<?php echo $Listing->automotive_admin_help_message(__("These are the images used in the slideshow when viewing the single listing. The first image (also known as the default image) is used throughout the site in the comparison table, recent vehicle scroller and on the inventory page.", "listings")); ?>

				<?php $is_boxed = (isset($_COOKIE['gallery_layout']) && $_COOKIE['gallery_layout'] == "boxed"); ?>
				<div class="tab_content">
					<div style="height: 30px;">
						<ul class="gallery-image-view page-view nav nav-tabs">
							<li data-layout="wide_fullwidth"<?php echo (!$is_boxed ? " class='active'" : ""); ?>>
								<a href="#"><i class="fa"></i></a>
							</li>
							<li data-layout="boxed_fullwidth"<?php echo ($is_boxed ? " class='active'" : ""); ?>>
								<a href="#"><i class="fa"></i></a>
							</li>
						</ul>

						<div class="clearfix"></div>
					</div>

					<?php do_action('automotive_listing_admin_meta_images_before'); ?>

					<div id="gallery_images" class="<?php echo ($is_boxed ? ' boxed' : "") . ($is_hotlink ? ' hotlink' : ''); ?>" data-image="<?php echo $image; ?>"	data-change-image="<?php echo $change_image; ?>"
					     data-set-default="<?php echo $set_default; ?>" data-delete-image="<?php echo $delete_image; ?>"
					     data-no-images="<?php echo $no_images; ?>" data-url="<?php echo $url_text; ?>">
						<?php
						if ( isset( $gallery_images ) && ! empty( $gallery_images ) ) {
							$i = 1;

							foreach( $gallery_images as $gallery_image ){
								$image_alt  = '';
								$image_full = $gallery_image;

								if(!$is_hotlink){
									$image_alt  = sanitize_text_field( get_post_meta( $gallery_image, "_wp_attachment_image_alt", true ) );

									$full_url   = wp_get_attachment_image_src($gallery_image, "full");
									if(isset($full_url[0])){
										$image_full = esc_url_raw($full_url[0]);
									} else {
										$image_full = '';
									}
								} ?>
								<div class="single-gallery-image" data-id='<?php echo $i; ?>'>
									<div class='top_header'>
										<?php
										if(!$is_hotlink){
											echo $image . " #" . $i;
										} else {
											echo "<input type='url' name='gallery_images[]' placeholder='" . $url_text . "' value='" . $gallery_image . "'>";
										} ?>
									</div>

									<div class='image_preview'
										data-alt='<?php echo esc_attr($image_alt); ?>'
										data-full-image='<?php echo esc_url($image_full); ?>'>
										<?php echo $Listing->auto_image( $gallery_image, "auto_thumb" ); ?>
									</div>

									<div class='buttons'>
										<?php if(!$is_hotlink) { ?>
										<span class='button add_image_gallery'>
											<span><?php echo $change_image; ?></span>
											<i class="fa fa-pencil"></i>
										</span>
										<span class='button make_default_image'>
											<span><?php echo $set_default; ?></span>
											<i class="fa fa-hand-pointer-o"></i>
										</span>
										<?php } ?>
										<span class='button delete_image'>
											<span><?php echo $delete_image; ?></span>
											<i class="fa fa-trash"></i>
										</span>
									</div>

                  <?php if(!$is_hotlink){ ?>
									<input type='hidden' name='gallery_images[]' value='<?php echo $gallery_image; ?>'>
                  <?php } ?>
								</div>
								<?php

								$i++;
							}
						} ?>
					</div>

					<button class='<?php echo (!$is_hotlink ? "add_image" : "add_image_hotlink"); ?> button button-primary'><?php _e( "Add Image", "listings" ); ?></button>

					<?php do_action('automotive_listing_admin_meta_images_after'); ?>

					<input type="hidden" name="gallery_image_meta" value="true">

					<div class='clear'></div>
				</div>
			</div>

			<div id="tab-price">
				<?php echo $Listing->automotive_admin_help_message(__("This allows you to control the pricing of the vehicle. The original price can be used for when there are sales on a specific vehicle.", "listings")); ?>

				<div class="tab_content">
				<?php
				$currency_symbol    = automotive_listing_get_option('currency_symbol', '');
				$currency_placement = automotive_listing_get_option('currency_placement', '');
				$decimal_sep        = automotive_listing_get_option('currency_separator_decimal', '.');
				$thousand_sep       = automotive_listing_get_option('currency_separator', '.');
				$taxrate            = automotive_listing_get_option('tax_amount', '0');
				$decimals           = automotive_listing_get_option('currency_decimals', '2');
				$default_tax        = automotive_listing_get_option('default_tax', false);

				if((int)$taxrate != 0){
					$taxrate = "1.".$taxrate;
				} ?>

					<div>
            <h2 class="detail_heading"><?php _e( "Current Price", "listings" ); ?></h2><br>
            <?php echo( $currency_placement ? $currency_symbol : "" ); ?>
            <input type="text" name="options[price][value]"
						data-decimal-char="<?php echo $decimal_sep; ?>"
						data-thousand-char="<?php echo $thousand_sep; ?>"
						value="<?php echo esc_attr( $Automotive_Listing->get_price() ); ?>"
						class="info price current_price" data-placement="right" data-trigger="focus"
						data-title="<img src='<?php echo LISTING_DIR; ?>/images/thumbnails/widget_slider/example-price.png' style='opacity: 1'>"
						data-html="true" data-original-title=""
						title=""><?php echo( ! $currency_placement ? $currency_symbol : "" ); ?>

            <?php if ( $Listing->is_tax_active() ) { ?>
						<br>
						<label>
							<?php _e( "Add Tax on Value", "listings" ); ?>
							<input type="checkbox" class="add_tax_value"
							data-input="current_price" <?php checked( $default_tax, 1 ); ?>
							data-taxrate="<?php echo $taxrate; ?>"
							data-decimals="<?php echo $decimals; ?>">
						</label>
            <?php } ?>
	        </div>
	        <div>
            <h2 class="detail_heading"><?php _e( "Original Price (Optional)", "listings" ); ?></h2>
            <br>
            <?php echo( $currency_placement ? $currency_symbol : "" ); ?>
            <input type="text" name="options[price][original]"
						data-decimal-char="<?php echo $decimal_sep; ?>"
						data-thousand-char="<?php echo $thousand_sep; ?>"
						value="<?php echo esc_attr( $Automotive_Listing->get_original_price() ); ?>"
						class="info price original_price" data-placement="right" data-trigger="focus"
						data-title="<img src='<?php echo LISTING_DIR; ?>/images/thumbnails/widget_slider/example-original.png' style='opacity: 1'>"
						data-html="true" data-original-title=""
						title=""><?php echo( ! $currency_placement ? $currency_symbol : "" ); ?>

            <?php if ( $Listing->is_tax_active() ) { ?>
              <br>
							<label>
                <?php _e( "Add Tax on Value", "listings" ); ?>
                <input type="checkbox" class="add_tax_value"
								data-input="original_price" <?php checked( $default_tax, 1 ); ?>
								data-taxrate="<?php echo $taxrate; ?>"
								data-decimals="<?php echo $decimals; ?>">
              </label>
            <?php } ?>
	        </div>
				</div>
			</div>

			<?php if( $woocommerce_listing_integration ){ ?>
			<div id="tab-woo">
				<?php echo $Listing->automotive_admin_help_message(__("This setting allows you to associate a WooCommerce product with a listing and show an Add to Cart section to allow users to checkout and purchase items.", "listings")); ?>

				<div class="tab_content">
					<div>
            <?php
            $current_woo_id = $Automotive_Listing->get_post_meta('woocommerce_integration_id', false);

            $args = array(
              'post_type'      => 'product',
              'posts_per_page' => - 1
            );

            $loop = get_posts( $args );

            if ( ! empty( $loop ) ) {
              echo "<select name='woocommerce_integration_id' class='chosen-dropdown' style='width: 300px;'>";
              echo "<option value=''>" . __( "No product association", "listings" ) . "</option>";

              foreach ( $loop as $product ) {
                $current_id = $product->ID;

                echo "<option value='" . $current_id . "'" . selected( $current_id, $current_woo_id, false ) . ">" . esc_html($product->post_title) . "</option>";
              }

              echo "</select>";
            } else {
              echo __( 'No WooCommerce products found', 'listings' );
            }

            wp_reset_query();

						if($current_woo_id){
							echo " <a href='" . esc_url( get_edit_post_link($current_woo_id) ) . "' target='_blank'>" . esc_html__("Edit Product", "listings") . "</a>";
						}
            ?>
          </div>
				</div>
			</div>
			<?php } ?>

			<div id="tab-pdf">
				<?php echo $Listing->automotive_admin_help_message(__("This setting allows you to upload a custom PDF for the user to download rather than the automatically generated one.", "listings")); ?>

				<div class="tab_content">
					<?php
					$pdf_brochure = $Automotive_Listing->get_post_meta('pdf_brochure_input', false);
					$pdf_link     = wp_get_attachment_url( $pdf_brochure ); ?>

					<button class="pick_pdf_brochure button primary"><?php _e( "Choose a PDF Brochure", "listings" ); ?></button>

					<?php if ( isset( $pdf_link ) && ! empty( $pdf_link ) ) {
						echo "<button class='remove_pdf_brochure button primary'>" . __( "Remove", "listings" ) . "</button>";
					} ?>

					<br><br> <?php _e( "Current File", "listings" ); ?>:
					<span class="pdf_brochure_label">
						<a href="<?php echo esc_url($pdf_link); ?>" target="_blank"><?php echo esc_url($pdf_link); ?></a>
					</span>

					<input type="hidden" name="pdf_brochure_input" class="pdf_brochure_input" value="<?php echo $pdf_brochure; ?>">
				</div>
			</div>

			<div id="tab-tax">
				<?php echo $Listing->automotive_admin_help_message(__("Here you can customize the tax labels used on this individual listing, if you click on a text box you can see exactly which area in a screenshot.", "listings")); ?>

				<div class="tab_content">
					<div>
            <div>
              <label>
                  <?php _e( "Tax Label (below the price) on Listing Page", "listings" ); ?>:<br>
                  <input type='text' name='options[custom_tax_inside]'
									value='<?php echo esc_attr($Automotive_Listing->get_tax_label(true)); ?>' class='info' data-placement='right'
									data-trigger='focus'
									data-title="<img src='<?php echo THUMBNAIL_URL; ?>widget_slider/example-tax-inside.png' style='opacity: 1'>"
									data-html='true'/>
              </label>
            </div>

            <br>

            <div>
              <label>
                  <?php _e( "Tax Label (below the price) on Inventory Page", "listings" ); ?>:<br>
                  <input type='text' name='options[custom_tax_page]'
									value='<?php echo esc_attr($Automotive_Listing->get_tax_label(false)); ?>'
									class='info' data-placement='right' data-trigger='focus'
									data-title="<img src='<?php echo THUMBNAIL_URL; ?>widget_slider/example-tax-page.png' style='opacity: 1'>"
									data-html='true'/>
              </label>
            </div>
					</div>
				</div>
			</div>

			<div id="tab-fuel">
				<?php echo $Listing->automotive_admin_help_message(__("Set the city and highway MPG for the current listing.", "listings"));

				$listing_mpg = $Automotive_Listing->get_mpg(); ?>

				<div class="tab_content">
					<div>
              <h2 class="detail_heading"><?php _e( "City MPG", "listings" ); ?></h2><br>
              <input type="text" name="options[city_mpg][value]"
							placeholder="<?php _e( "City MPG", "listings" ); ?>"
							value="<?php echo esc_attr($listing_mpg['city']); ?>"
							class="info city_mpg" data-placement="right" data-trigger="focus"
							data-title="<img src='<?php echo LISTING_DIR; ?>/images/thumbnails/widget_slider/example-city_mpg.png' style='opacity: 1'>"
							data-html="true" data-original-title="" title="">
          </div>

          <br>

          <div>
              <h2 class="detail_heading"><?php _e( "Highway MPG", "listings" ); ?></h2><br>
              <input type="text" name="options[highway_mpg][value]"
							placeholder="<?php _e( "Highway MPG", "listings" ); ?>"
							value="<?php echo esc_attr($listing_mpg['highway']); ?>"
							class="info highway_mpg" data-placement="right" data-trigger="focus"
							data-title="<img src='<?php echo LISTING_DIR; ?>/images/thumbnails/widget_slider/example-highway_mpg.png' style='opacity: 1'>"
							data-html="true" data-original-title="" title="">
          </div>
				</div>
			</div>

			<div id="tab-badge">
				<?php echo $Listing->automotive_admin_help_message(__("Here you can enable a listing badge on a listing and control the color. Creating custom badges can be done under Listing Options >> Automotive Settings >> Custom Badges.", "listings")); ?>

				<div class="tab_content">
					<?php
					$custom_badges = automotive_listing_get_option('custom_badges', array());

					if( !empty($custom_badges) ){
						$custom_badges['name']   = array_values(array_filter($custom_badges['name']));
						$custom_badges['color']  = array_values(array_filter($custom_badges['color']));
						$custom_badges['font']   = array_values(array_filter($custom_badges['font']));

						echo __("Custom Listing Badge", "listings") . ": <select name='options[custom_badge]'>";
						echo "<option value=''>" . __("None", "listings") . "</option>";
						foreach($custom_badges['name'] as $key => $badge_name){
							if(!empty($badge_name)){
								echo "<option value='" . $badge_name . "'" . selected($Automotive_Listing->get_badge_text(), $badge_name, false) . ">" . esc_html($badge_name) . "</option>";
							}
						}
						echo "</select>";
					}

					echo "<hr>"; ?>

					<h4 style="margin-bottom: 5px;"><?php _e("Add a new badge", "listings"); ?></h4>
					<table>
						<tr><td><?php _e("Badge Name", "listings"); ?>:</td><td> <input type="text" name="new_badge_name" class="new_badge_name"></td></tr>
						<tr><td><?php _e("Badge Color", "listings"); ?>:</td><td> <input type="text" name="new_badge_color" class="new_badge_color auto_color_picker"></td></tr>
						<tr><td><?php _e("Font Color", "listings"); ?>:</td><td> <input type="text" name="new_badge_font" class="new_badge_font auto_color_picker"></td></tr>
						<tr><td colspan="2"> <button class="add_new_badge button-primary"><?php _e("Add New Badge", "listings"); ?></button> </td></tr>
					</table>
				</div>
			</div>

			<div id="tab-video">
				<?php echo $Listing->automotive_admin_help_message(__("Add a YouTube/Vimeo video to show off the listing in action.", "listings"));

				$listing_video     = $Automotive_Listing->get_video();
				$listing_video_url = $Automotive_Listing->get_video(true);

				if(empty($listing_video_url)){
					$listing_video_url = '';
				} ?>

				<div class="tab_content">
					<?php _e( "YouTube/Vimeo/Rumble/Self Hosted Video Link", "listings" ); ?>:
					<input type='text' name='options[video]' id='listing_video_input' style='width: 500px; max-width: 100%;' value="<?php echo esc_url($listing_video_url); ?>" />

					<div id='listing_video'>
						<?php

						if ( !empty( $listing_video ) ) {

							if($listing_video[0]){
								echo "<br><br>";

								if($listing_video[0] == "youtube"){
									echo "<iframe width=\"644\" height=\"400\" src=\"https://www.youtube.com/embed/" . $listing_video[1] . "\" allowfullscreen></iframe>";
								} elseif($listing_video[0] == "vimeo"){
									echo "<iframe width=\"644\" height=\"400\" src=\"https://player.vimeo.com/video/" . $listing_video[1] . "\" allowfullscreen></iframe>";
								} elseif($listing_video[0] == 'rumble'){
									echo "<iframe width=\"644\" height=\"400\" src=\"https://rumble.com/embed/" . $listing_video[1] . "\" allowfullscreen></iframe>";
								} elseif($listing_video[0] == "self_hosted"){
									echo do_shortcode("[video width=\"600\" height=\"480\" mp4=\"" . $listing_video[1] . "\"]");
								}
							} else {
								echo __( "Not a valid YouTube/Vimeo/Rumble/Self Hosted Video Link", "listings" ) . "...";
							}
						} ?>
					</div>
				</div>
			</div>

			<div id="tab-categories">
				<?php echo $Listing->automotive_admin_help_message(__("This allows you to the listing category values you have configured for your listings.", "listings")); ?>

				<div class="tab_content">
						<?php
						$listing_categories = $Listing->get_listing_categories();

						if ( ! empty( $listing_categories ) ) {
							foreach ( $listing_categories as $slug => $category ) {
								$category['link_value'] = ( isset( $category['link_value'] ) && ! empty( $category['link_value'] ) ? $category['link_value'] : "" );

								// link value
								if ( empty( $category['link_value'] ) || $category['link_value'] == "none" ) {
								    echo "<div class='category_row'>";

									echo " <div class='category_add'><a href='#' class='hide-if-no-js add_new_name' data-id='" . $slug . "'>+ <span>" . __( "Add New Term", "listings" ) . "</span></a>";
									echo '<div class="add_new_content ' . $slug . '_sh" style="display: none;">
							        <input class="' . $slug . '" type="text" style="margin-left: 0;" />
							        <button class="button submit_new_name" data-type="' . $slug . '" data-exact="' . $slug . '" data-nonce="' . wp_create_nonce("add_listing_value_" . $slug) . '">' . __( "Add New Term", "listings" ) . '</button>
							    </div></div>';

									echo "<span class='category_singular'>" . $Listing->display_listing_category($category['singular']) . ":</span> ";

									if ( ! isset( $category['is_number'] ) || ( isset( $category['is_number'] ) && $category['is_number'] == 0 ) ) {
										echo "<select name='" . $slug . "' class='category_dropdown' id='" . $slug . "'>";
										echo "<option value='None'>" . __( "None", "listings" ) . "</option>";

										// sort
										if ( ! empty( $category['terms'] ) && is_array($category['terms'])  ) {
											if ( isset( $category['sort_terms'] ) && $category['sort_terms'] == "desc" ) {
												arsort( $category['terms'] );
											} else {
												asort( $category['terms'] );
											}
										}

										if ( ! empty( $category['terms'] ) && is_array($category['terms'])  ) {
											foreach ( $category['terms'] as $term_key => $term ) {
												$option_value = htmlentities( stripslashes( $term ), ENT_QUOTES );
                        $current_term = get_post_meta( $post->ID, $slug, true );

                        if(!is_string($current_term) || !is_string($option_value)){
                          continue;
                        }

												echo "<option value='" . htmlentities( stripslashes( $option_value ), ENT_QUOTES ) . "' ";
                        echo selected( $option_value, htmlentities( stripslashes( $current_term ), ENT_QUOTES ), false ) . ">";
                        echo esc_html( stripslashes( $Listing->display_listing_category_term($term) ) ) . "</option>";
											}
										}

										echo "</select>";
									} else {
										$text_value = $Automotive_Listing->get_term($slug);

										if(is_array($text_value)){
											$text_value = $text_value[0];
										}

										echo "<input type='text' class='category_input' name='" . $slug . "' value='" . htmlspecialchars( stripslashes( $text_value ), ENT_QUOTES ) . "'>";
									}

									echo "<div class='clearfix'></div></div>";
								}
							}
						}
						?>
				</div>
			</div>

			<div id="tab-additional">
				<?php echo $Listing->automotive_admin_help_message(__("Here you can set the additional categories you have configured under Listing Options >> Automotive Settings >> Additional Categories.", "listings"));

				$default_vehicle_history = automotive_listing_get_option('default_vehicle_history', array());
				?>

				<div class="tab_content">
					<table>
						<?php
						echo "<tr><td><label for='verified'>" . __( "Show vehicle history report image", "listings" ) . ":</label></td><td><input type='checkbox' name='verified' value='yes' id='verified'" . ( ( $Automotive_Listing->is_verified ) || $Listing->is_edit_page( 'new' ) && isset( $default_vehicle_history['on'] ) && $default_vehicle_history['on'] == "1" ? " checked='checked'" : "" ) . "></td></tr>";

						$additional_categories_key = 'additional_categories';

						$additional_categories = automotive_listing_get_option($additional_categories_key, array());

						if ( ! empty( $additional_categories['value'] ) ) {
							foreach ( $additional_categories['value'] as $key => $category ) {
								if ( ! empty( $category ) ) {
									$safe_handle = str_replace( " ", "_", strtolower( $category ) );
									$current_val = $Automotive_Listing->get_post_meta($safe_handle, 0);

									if ( $Listing->is_edit_page( 'new' ) && isset( $additional_categories['check'][ $key ] ) && $additional_categories['check'][ $key ] == "on" ) {
										$current_val = 1;
									}

									echo "<tr><td><label for='" . $safe_handle . "'>" . $category . ":</label></td><td><input type='checkbox' name='" . $additional_categories_key . "[value][" . $safe_handle . "]' id='" . $safe_handle . "' value='1'" . ( $current_val == 1 ? "checked='checked'" : "" ) . "></td></tr>";
								}
							}
						} ?>
					</table>
				</div>
			</div>

			<div id="tab-others">
				<?php echo $Listing->automotive_admin_help_message(__("This allows you to set the short description used on the recent vehicle slider.", "listings")); ?>

				<div class="tab_content">

          <label>
            <?php _e( "Short Description For Vehicle Slider Widget", "listings" ); ?>:

            <input type='text'
						name='options[short_desc]' value="<?php echo esc_attr($Automotive_Listing->get_scroller_desc()); ?>"
						class='info' data-placement='right' data-trigger="focus"
						data-title="<img src='<?php echo THUMBNAIL_URL; ?>widget_slider/example.png' width='183' height='201' style='opacity: 1'>"
						data-html='true' style="margin-top: 10px"/>
          </label>
				</div>
			</div>

			<div id="tab-status">
				<?php echo $Listing->automotive_admin_help_message(__("Set the vehicle status using toggles.", "listings")); ?>

				<div class="tab_content">
					<table>
						<tr>
							<td>
								<label for='sold_check'><?php _e( "Sold", "listings" ); ?>:</label>
							</td>
							<td>
								<div class="auto_toggle toggle-light" data-checkbox="sold_check"></div>
								<input type='checkbox' name='car_sold' id='sold_check' class="hide" value='1' <?php echo ( $Automotive_Listing->is_sold ? " checked='checked'" : "" ); ?>>
							</td>
						</tr>
						<?php
						if( automotive_listing_get_option('featured_vehicle_widget', false) ){ ?>
							<tr><td><label for='featured_check'><?php _e( "Featured", "listings" ); ?>:</label></td><td><div class="auto_toggle toggle-light" data-checkbox="featured_check"></div> <input type='checkbox' name='car_featured' id='featured_check' class="hide" value='1' <?php echo ( $Automotive_Listing->get_post_meta('car_featured', false) ? " checked='checked'" : "" ); ?>></td></tr>
						<?php } ?>
					</table>
				</div>
			</div>

			<div id="tab-shortcode">
				<?php echo $Listing->automotive_admin_help_message(__("Generate the shortcode to use in a Revolution Slider layer, create shortcode styles under Listing Options >> Revolution Slider Shortcode.", "listings")); ?>

				<div class="tab_content">
					<div>
            <?php _e("Template", "listings"); ?>:
            <?php
            $templates = get_option("automotive_rev_slider_templates");

            if(!empty($templates)){
                echo "<select id='rev_slider_template'>";
                echo "<option>" . __("Choose...", "listings") . "</option>";
                foreach($templates as $template_id => $template){
                    echo "<option value='" . $template_id . "'>" . $template['name'] . "</option>";
                }
                echo "</select>";
            } else {
                _e("No templates found, create some under Listing Options >> Revolution Slider Shortcode", "listings");
            }
            ?>
          </div>
          <div>
            <?php _e("Shortcode", "listings"); ?>:
            <input type="text" id="rev_shortcode" data-listing-id="<?php echo $Automotive_Listing->get_id(); ?>" value="[auto_card id='<?php echo $Automotive_Listing->get_id(); ?>' template='0']" style="width: 300px;" disabled>
          </div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'add_meta_boxes', 'automotive_listing_add_custom_boxes' );

function automotive_add_after_editor() {
	global $post, $wp_meta_boxes;

	do_meta_boxes( get_current_screen(), 'advanced', $post );

	$post_types = get_post_types();

	foreach ( $post_types as $post_type ) {
		unset( $wp_meta_boxes[ $post_type ]['advanced'] );
	}
}

add_action( "edit_form_after_title", "automotive_add_after_editor" );

function plugin_secondary_title() {
	global $post;

	$secondary_title = get_post_meta( $post->ID, "secondary_title", true );
	echo "<input type='text' value='" . $secondary_title . "' name='secondary_title' style='width:100%;'/>";
}

//********************************************
//	Custom meta boxes for custom categories
//***********************************************************
function automotive_register_menu_pages() {
	global $Listing;

	$listing_category_permission = automotive_listing_get_option('listing-category-permission', 'manage_options');

	add_submenu_page(
		'edit.php?post_type=listings',
		__( "Options", "listings" ),
		__( "Options", "listings" ),
		$listing_category_permission,
		'options',
		'auto_listing_category_terms_page'
	);

	$listing_categories = $Listing->get_listing_categories();

	if(!empty($listing_categories)){
		foreach ( $listing_categories as $key => $field ) {
			$plural = $field['plural'];
			$slug   = $field['slug'];

			if ( ! empty( $plural ) && ! empty( $slug ) ) {
				add_submenu_page(
					'edit.php?post_type=listings',
					$plural,
					stripslashes( $plural ),
					$listing_category_permission,
					$slug,
					'auto_listing_category_terms_page'
				);
			}
		}
	}
}
add_action( 'admin_menu', 'automotive_register_menu_pages' );

function auto_listing_category_terms_page() {
	global $Listing;

	// refresh the var to see newly added terms
	$Listing->refresh_listing_categories();

	$is_options  = false;
	$value       = $svalue = $_GET['page'];
	$category    = $Listing->get_single_listing_category( $svalue );

	$is_location = (isset($category['location_email']) && $category['location_email'] == 1 ? true : false);

	if ( $value == "options" ) {
		$label      = __( "Options", "listings" );
		$is_options = true;

		$default = get_option( "options_default_auto" );
	} else {
		$label = stripslashes( $category['singular'] );
	}


	if(!is_array($category['terms'])){
		$category['terms'] = array();
	}

	$options = $options_key_order = ( isset( $category['terms'] ) && ! empty( $category['terms'] ) ? $category['terms'] : "" );
	$i       = 0;

	if ( ! empty( $options ) ) {
		// alphabetically sort options (case insensitive)
		$options = array_filter( $options, 'is_not_null' );

		array_multisort( array_map( 'strtolower', $options ), $options );

		if(!$is_options) {
			$total_options = count( $options );
			$per_page      = 50;
			$paged_options = array_chunk( $options, $per_page, true );
			$current_page  = ( ( isset( $_GET['o_page'] ) && ! empty( $_GET['o_page'] ) ? preg_replace( '/\D/', '', $_GET['o_page'] ) : 1 ) - 1 );

			$pagination = ' <div class="tablenav">
                            <div class="tablenav-pages">
                                <span class="displaying-num">' . $total_options . ' item' . ( $total_options != 1 ? 's' : '' ) . '</span>
                                <span class="pagination-links">';

			foreach ( $paged_options as $key => $value ) {
				$pagination .= '<a class="next-page' . ( $key == $current_page ? " disabled" : "" ) . '" href="' . add_query_arg( "o_page", ( $key + 1 ) ) . '">' . ( $key + 1 ) . '</a>';
			}

			$pagination .= '</span>
                            </div>
                        </div>';
		}

	} else {
		$pagination = ' <div class="tablenav">
                            <div class="tablenav-pages">
                                <span class="displaying-num">0 items</span>
                                <span class="pagination-links"><a class="next-page disabled" href="#">1</a></span>
                            </div>
                        </div>';
	}

	?>
	<style type="text/css"> .delete_name {
			cursor: pointer
		} </style>
	<div class='wrap nosubsub view-category-terms'>
		<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		<h2><?php echo ucwords( $label ); ?> (<a href="<?php echo esc_url( admin_url('edit.php?post_type=listings&page=listing_categories&edit=' . $svalue) ); ?>"><?php _e("Edit Category", "listings"); ?></a>)</h2>

		<div id='col-container'>
			<div id='col-left'>
				<strong><?php echo __( "Add New", "listings" ) . " " . $label; ?></strong><br/>

				<form method="POST" action="">
					<table border='0'>
						<tr>
							<td><?php _e( "Value", "listings" ); ?>:</td>
							<td> <?php echo( isset( $category['compare_value'] ) && ! empty( $category['compare_value'] ) && $category['compare_value'] != "=" ? $category['compare_value'] : "" ); ?>
								<input type='text' name='new_name'/></td>
						</tr>
						<tr>
							<td colspan="2">
								<input type='submit' class='button-primary' name='add_new_name' value='<?php _e( "Add", "listings" ); ?>'/>
							</td>
						</tr>
					</table>
				</form>
			</div>

			<div id='col-right'>

				<?php echo (!$is_options ? $pagination : ""); ?>

				<form method="POST" action="">
					<table border='0' class='wp-list-table widefat fixed tags listing_table'
					       data-save-text='<?php _e( "Save", "listings" ); ?>' data-slug="<?php echo $svalue; ?>">
						<thead>
						<tr>
							<th><?php _e( "Value", "listings" ); ?></th>
							<th><?php _e( "Slug", "listings" ); ?></th>
							<th><?php _e( "Posts", "listings" ); ?></th>
							<?php if ( isset( $category['location_email'] ) && ! empty( $category['location_email'] ) ) { ?>
								<th><?php _e( "Email Address", "listings" ); ?></th>

								<?php $location_email = get_option( "location_email" );
							} ?>
							<th><?php _e( "Actions", "listings" ); ?></th>
							<?php echo( $is_options ? "<th>" . __( "Default Selection", "listings" ) . "</th>" : "" ); ?>
						</tr>
						</thead>

						<tbody>
						<?php
						//********************************************
						//  Page Pagination
						//***********************************************************
						if ( empty( $options ) ) {
							echo "<tr><td colspan='3'>" . __( "No terms yet", "listings" ) . "</td></tr>";
						} else {
							$loop_options = (!$is_options ? $paged_options[ $current_page ] : $options);
							$show_sold    = automotive_listing_get_option('inventory_no_sold', false);

							foreach ( $loop_options as $key => $option ) {
								$option_label        = stripslashes( $option );
								$option_array_search = $option;

								echo "<tr" . ( $i % 2 == 0 ? " class='alt'" : "" ) . " id='t_" . $i . "'>\n<td>" . $option_label . "</td>\n";

								echo "<td>" . $Listing->slugify( $option_label ) . "</td>\n";

								echo "<td>" . get_total_meta( $svalue, $option_label, $is_options, $show_sold ) . "</td>\n";

								if ( isset( $category['location_email'] ) && ! empty( $category['location_email'] ) ) {
									echo "<td><input type='email' placeholder='" . __( "Email", "listings" ) . "' value='" . ( isset( $location_email[ htmlspecialchars_decode( $option ) ] ) && ! empty( $location_email[ htmlspecialchars_decode( $option ) ] ) ? $location_email[ htmlspecialchars_decode( $option ) ] : "" ) . "' name='location_email[" . htmlspecialchars( $option, ENT_QUOTES ) . "]'></td>\n";
								}

								echo "<td><button class='delete_name button-primary' data-id='" . array_search( $option_array_search, $options_key_order ) . "' data-type='" . $svalue . "' data-row='" . $i . "'>" . __( "Delete", "listings" ) . "</button>&nbsp;&nbsp;<button class='edit_name_text button-primary' data-id='" . array_search( $option_array_search, $options_key_order ) . "' data-type='" . $svalue . "' data-row='" . $i . "'>" . __( "Edit", "listings" ) . "</button></td>\n";

								if ( $is_options ) {
									echo "<td><input type='checkbox' name='default[]' value='" . htmlspecialchars($option, ENT_QUOTES, "UTF-8") . "' " . ( ! empty( $default ) && is_array($default) && in_array( $option, $default ) ? " checked='checked'" : "" ) . "></td>\n";
								}

								echo "</tr>\n";
								$i ++;
							}
						}
						?>
						</tbody>
					</table>

					<?php echo (!$is_options ? $pagination : ""); ?>

                    <?php if($is_options || $is_location){ ?>
					<input type="submit" name="submit" value="Save Default" class="button button-primary" style="margin-top: 15px;">
                    <?php } ?>

				</form>
			</div>
		</div>
	</div>
	<?php
}

// saving
function automotive_save_page_terms() {
	if ( isset( $_POST['add_new_name'] ) ) {
		global $Listing;

		$name         = sanitize_text_field( $_POST['new_name'] );
		$current_page = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? $_GET['page'] : "" );

		// add the term
		$Listing->add_listing_category_term($current_page, $name);
	}

	if ( isset( $_POST['location_email'] ) && ! empty( $_POST['location_email'] ) ) {
		update_option( "location_email", $_POST['location_email'] );
	}

	if ( isset( $_POST['default'] ) && ! empty( $_POST['default'] ) ) {
	    $default = array_map('stripslashes', $_POST['default']);

		update_option( "options_default_auto", $default );
	}
}
add_action( 'init', 'automotive_save_page_terms', 15 );



//********************************************
//	Quick Edit
//***********************************************************
function automotive_quick_edit_fields( $column_name, $post_type, $taxonomy ) {
  global $post;

  switch ( $post_type ) {
    case 'listings':

			$Automotive_Listing = new Automotive_Listing($post->ID);

			$price          = $Automotive_Listing->get_price();
			$original_price = $Automotive_Listing->get_original_price();
    ?>
      <fieldset class="inline-edit-col-right">
          <div class="inline-edit-col">
              <label>
                  <span class="title">Current Price</span>
                  <span class="input-text-wrap"><input type="text" name="options[price][value]" class="inline-edit-menu-order-input current-price" value="<?php echo $price; ?>"></span>
              </label>
          </div>
          <div class="inline-edit-col">
              <label>
                  <span class="title">Original Price</span>
                  <span class="input-text-wrap"><input type="text" name="options[price][original]" class="inline-edit-menu-order-input original-price" value="<?php echo $original_price; ?>"></span>
              </label>
          </div>
      </fieldset> <?php
      break;

    default:
      break;
  }
}
add_action( 'quick_edit_custom_box', 'automotive_quick_edit_fields', 10, 3 );

// load up the data we need for the quick edit since this isn't generated each request
function automotive_get_edit_field_data(){
	$current_screen = get_current_screen();

	if(isset($current_screen->id) && $current_screen->id == 'edit-listings'){
		global $wp_query;

		// D($wp_query);
		$shown_listings = $wp_query->posts;
		$listing_data   = array();

		if(!empty($shown_listings)){
			foreach($shown_listings as $single_listing){
				$Automotive_Listing = new Automotive_Listing($single_listing->ID);

				$listing_data[$single_listing->ID] = array(
					'price'          => $Automotive_Listing->get_price(),
					'original_price' => $Automotive_Listing->get_original_price(),
				);
			}
		}

		echo "<script>";
		echo 'var currentListingData = ' . wp_json_encode($listing_data) . ';';
		echo "</script>";

	}

}
add_action('admin_footer', 'automotive_get_edit_field_data');
