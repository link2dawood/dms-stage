<?php
/*
	Automotive Listing Info Table Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/listing_info_table.php

	Version: 15.8
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$Automotive_Plugin = Automotive_Plugin();
$none_text         = __("None", "listings");

global $post;

echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : ""); ?>
<div class="car-info">
	<div class="table-responsive">
		<table class="table">
			<tbody>
			<?php
			$listing_categories = $Automotive_Plugin->get_listing_categories();

			if(!empty($listing_categories)){
				$Automotive_Listing = new Automotive_Listing($post->ID);

				foreach($listing_categories as $key => $category){
					$slug   = $category['slug'];
					$value  = $Automotive_Listing->get_term($slug, '');
					$hidden = (isset($category['hide_category']) && $category['hide_category'] == 1);

					$value = (is_array($value) ? $value[0] : $value);

					if(!$hidden){
						$hide_sold_price = automotive_listing_get_option('hide_sold_price', false);
						$link_value      = (isset($category['link_value']) && !empty($category['link_value']) ? $category['link_value'] : "");

						if(empty($value) && isset($category['is_number']) && $category['is_number'] == 1){
							$value = 0;
						} elseif(empty($value)) {
							$value = $none_text;
						}

						$value = $Automotive_Plugin->display_listing_category_term($value, $slug);

						// price
						if(isset($category['currency']) && $category['currency'] == 1){
							$value = $Automotive_Plugin->format_currency($value);
						}

						$has_value = ( automotive_strtolower( $value ) != "none" && ! empty( $value ) ) && $value != __( "None", "listings" );

						if($link_value == "price" && $Automotive_Listing->is_sold && $hide_sold_price){
							$has_value = false;
						}

						if($has_value){
							$display_value = html_entity_decode( $value );
							$schema        = (isset($category['schema']) && !empty($category['schema']) && $category['schema'] !== 'none' ? $category['schema'] : false);

							if($link_value == "mpg"){
								$listing_mpg			 = $Automotive_Listing->get_mpg();
								$city_mpg_value    = $listing_mpg['city'];
								$highway_mpg_value = $listing_mpg['highway'];

								if(!empty($city_mpg_value) && !empty($highway_mpg_value)) {
									$display_value = $city_mpg_value . " " . automotive_listing_get_option('default_value_city', '') . " / " . $highway_mpg_value . " " . automotive_listing_get_option('default_value_hwy', '');
								}
							}

							echo "<tr class='listing_category_" . sanitize_html_class($slug) . "'>";
							echo "<td>" . $Automotive_Plugin->display_listing_category($category['singular']) . ": </td>";
							echo "<td" . ($schema ? ' itemprop="' . esc_attr($schema) . '"' : '') . ">" . esc_html(stripslashes($display_value)) . "</td>";
							echo "</tr>\n";

						}
					}
				}
			} ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $after_widget; ?>