<?php
/*
	Automotive Listing Filter Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing_filter_sort.php

	Version: 18.3
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$Automotive_Plugin = Automotive_Plugin();

$fake_get          = (isset($fake_get) && !empty($fake_get) ? $fake_get : null);
$get_holder        = (!is_null($fake_get) && !empty($fake_get) ? $fake_get : $_GET);
$sold_dependancies = (isset($sold_dependancies) && !empty($sold_dependancies) ? $sold_dependancies : false);

$cookie_key        = 'compare_vehicles' . (defined("ICL_LANGUAGE_CODE") ? '_' . ICL_LANGUAGE_CODE : '');

$filter_showall           = automotive_listing_get_option('filter_showall', false);
$mobile_inventory_filters = (int)automotive_listing_get_option('mobile_inventory_filters', false);
$sortby_default           = automotive_listing_get_option('sortby_default', true);
$car_comparison           = automotive_listing_get_option('car_comparison', true);
$sort_by                  = (isset($hide_sortby) && $hide_sortby == "true" ? false : automotive_listing_get_option('sortby') );
$comparison_page          = automotive_listing_get_option('comparison_page', '#');

if($comparison_page !== '#'){
	$comparison_page = get_permalink(apply_filters("wpml_object_id", $comparison_page, "page", true));
}

//********************************************
//	Language Variables
//***********************************************************
$select_prefix      = automotive_listing_get_option('filter_prefix', '');
$sortby_text        = __("Sort By", "listings");
$date_added_text    = __("Date Added", "listings");
$title_text         = __("Title", "listings");
$random_text        = __("Random", "listings");
$ascending_text     = __("Ascending", "listings");
$descending_text    = __("Descending", "listings");
$configure_text     = __("Configure under Listing Options &lt;&lt; Inventory Page &lt;&lt; Sort By Categories", "listings");
$reset_filters_text = __("Reset Filters", "listings");
$deselect_text      = __("Deselect All", "listings");
$vehicle_singular   = automotive_listing_get_option('vehicle_singular_form',  __('Vehicle', 'listings') );
$vehicle_plural     = automotive_listing_get_option('vehicle_plural_form', __('Vehicles', 'listings') );

if(isset($get_holder['sold_only']) && $get_holder['sold_only'] == 'false'){
	unset($get_holder['sold_only']);

	$sold_dependancies = array();
}

?>
<div class="clearfix"></div>
<form method="post" action="#" class="listing_sort">
	<div class="select-wrapper listing_select clearfix margin-bottom-15<?php echo ($mobile_inventory_filters ? ' show_mobile_filters' : ''); ?>"<?php echo (isset($get_holder['sold_only']) && !empty($get_holder['sold_only']) ? " data-sold_only='true'" : ""); ?>
	<?php echo (isset($hide_dropdown_filters) && $hide_dropdown_filters == "true" ? "style='display: none;'" : ""); ?>>
		<?php
		$filterable_categories = $Automotive_Plugin->get_filterable_listing_categories();
		if($filter_showall){
		$dependancies = $Automotive_Plugin->process_dependancies( array(), $sold_dependancies);
		} else {
		$dependancies = $Automotive_Plugin->process_dependancies_plain( $get_holder, $sold_dependancies);			
		}

		foreach($filterable_categories as $filter){
			$slug     = $filter['slug'];
			$get_slug = ($slug == "year" ? "yr" : $slug);
			$current  = (isset($get_holder[$get_slug]) && !empty($get_holder[$get_slug]) ? $get_holder[$get_slug] : "");
			$is_range = (isset($filter['range']) && $filter['range']);

			$other_options = array(
				"current_option" => $current
			);

			if(isset($filter['show_amount']) && $filter['show_amount'] == 1){
				$other_options['show_amount'] = (isset($dependancies[1][$slug]) && !empty($dependancies[1][$slug]) ? $dependancies[1][$slug] : array());
			}

			if($is_range){
				$range_terms     = automotive_listing_remove_non_numerical($filter['terms']);
				$range_increment = (isset($filter['range_increment']) && !empty($filter['range_increment']) ? $filter['range_increment'] : 1);
				$min             = min($range_terms);
		    	$max             = max($range_terms);

		    $current_min = (isset($_GET[$filter['slug']]) && is_array($_GET[$filter['slug']]) && isset($_GET[$filter['slug']][0]) ? absint($_GET[$filter['slug']][0]) : $min);
		    $current_max = (isset($_GET[$filter['slug']]) && is_array($_GET[$filter['slug']]) && isset($_GET[$filter['slug']][1]) ? absint($_GET[$filter['slug']][1]) : $max);

				// use params if set
				if(isset($params[$filter['slug']][0])){
					$current_min = $params[$filter['slug']][0];
				}

				if(isset($params[$filter['slug']][1])){
					$current_max = $params[$filter['slug']][1];
				}

		    echo "<div class=\"listing-range-slider-container\">";
		    echo "<div class=\"listing-range-slider\"
				data-type=\"" . $filter['slug'] . "\"
				data-min=\"" . $min . "\"
				data-max=\"" . $max . "\"
				data-current-min=\"" . $current_min . "\"
				data-current-max=\"" . $current_max . "\"
				data-increment=\"" . absint($range_increment) . "\"
				data-currency=\"" . (isset($filter['currency']) && $filter['currency'] ? 1 : 0) . "\"";

				if(isset($filter['currency']) && $filter['currency']) {
					echo " data-currency-symbol=\"" . automotive_listing_get_option('currency_symbol', '') . "\"";
					echo " data-thousand-sep=\"" . automotive_listing_get_option('currency_separator', '') . "\"";
					echo " data-decimal-sep=\"" . automotive_listing_get_option('currency_separator_decimal', '') . "\"";
					echo " data-decimals=\"" . automotive_listing_get_option('currency_decimals', '') . "\"";

					$current_min = $Automotive_Plugin->format_currency($current_min);
					$current_max = $Automotive_Plugin->format_currency($current_max);
				}

				echo "></div>";
		    echo "<div class=\"listing-range-slider-label\"><span class=\"singular-label\">" . $filter['singular'] . "</span>: <span class=\"min-label\">" . $current_min . "</span> &mdash; <span class=\"max-label\">" . $current_max . "</span> </div>";
		    echo "</div>";
			} else {
				echo '<div class="my-dropdown ' . $slug . '-dropdown">';
				$Automotive_Plugin->listing_dropdown($filter, $select_prefix, "listing_filter", (isset($dependancies[0][$slug]) && !empty($dependancies[0][$slug]) ? $dependancies[0][$slug] : array()), $other_options);
				echo '</div>';
			}
		} ?>

		<div class="loading_results">
			<i class="fa fa-circle-o-notch fa-spin"></i>
		</div>
	</div>
	<div class="select-wrapper pagination clearfix margin-bottom-15">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sort-by-menu">
				<?php	$sort_by_style = ($sort_by ? "" : " style='display: none;'"); ?>
					<span class="sort-by"<?php echo $sort_by_style; ?>><?php echo $sortby_text; ?>:</span>
					<div class="my-dropdown price-ascending-dropdown"<?php echo $sort_by_style; ?>>
						<select name="price_order" class="listing_filter" tabindex="1" >
							<?php
							$listing_orderby = $Automotive_Plugin->sort_by();

							if(!empty($listing_orderby)){

								$listing_order_url   = (isset($_GET['listing_order']) && !empty($_GET['listing_order']) ? $_GET['listing_order'] : "");
								$listing_orderby_url = (isset($_GET['listing_orderby']) && !empty($_GET['listing_orderby']) ? $_GET['listing_orderby'] : "");

								if(empty($listing_order_url)){
									reset($listing_orderby);
									$listing_order_url = key($listing_orderby);
								}

								if(empty($listing_orderby_url)){
									$listing_orderby_url = ($sortby_default ? "ASC" : "DESC");
								}

								$order_selected = $listing_order_url . "|" . $listing_orderby_url;

								foreach($listing_orderby as $key => $value){
									if($key == "date"){
										$option_label = $date_added_text;

									} elseif($key == "title"){
										$option_label = $title_text;

									} elseif($key == "random"){
										$option_label = $random_text;

									} else {
										$orderby_category = $Automotive_Plugin->get_single_listing_category($key);

										$option_label     = $Automotive_Plugin->display_listing_category((isset($orderby_category['singular']) ? $orderby_category['singular'] : ''));
									}

									if($key == "random"){
										echo "<option value='" . $key . "'" . selected( $listing_order_url, $key, false ) . ">" . $option_label . "</option>\n";
									} else {
										echo "<option value='" . $key . "|ASC'" . selected( $order_selected, $key . "|ASC", false ) . ">" . $option_label . " " . $ascending_text . "</option>\n";
										echo "<option value='" . $key . "|DESC'" . selected( $order_selected, $key . "|DESC", false ) . ">" . $option_label . " " . $descending_text . "</option>\n";
									}
								}
							} else {
								echo "<option value='none'>" . $configure_text . "</option>";
							} ?>
						</select>
					</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12<?php echo (is_rtl() ? ' offset-lg-1' : ''); ?>">
				<?php echo page_of_box(false, $fake_get); ?>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12<?php echo (!is_rtl() ? ' offset-lg-1' : ''); ?>">
				<ul class="form-links top_buttons">
					<?php if(!isset($hide_reset_button) || (isset($hide_reset_button) && $hide_reset_button == "false")){ ?>
					<li><a href="#" class="gradient_button reset"><?php echo $reset_filters_text; ?></a></li>
					<?php }
					if($car_comparison){ ?>
						<li><a href="#" class="gradient_button deselect"><?php echo $deselect_text; ?></a></li>
						<li><a href="<?php echo $comparison_page; ?>" class="gradient_button compare"<?php echo (defined("ICL_LANGUAGE_CODE") ? " data-lang='" . ICL_LANGUAGE_CODE . "'" : ""); ?>><?php echo sprintf(__("Compare <span class='number_of_vehicles'>%s</span> %s", "listings"), (isset($_COOKIE[$cookie_key]) && !empty($_COOKIE[$cookie_key]) ? count(explode(",", urldecode($_COOKIE[$cookie_key]))) : 0), $vehicle_plural); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</form>
