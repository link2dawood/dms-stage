<?php
/*
	Automotive Car Comparison Template File
	To overwrite this file copy it to automotive-child/auto_templates/car_comparison.php

	Version: 15.8
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
 $Automotive_Plugin = Automotive_Plugin();

//********************************************
//	Language Variables
//***********************************************************
$listing_image_alt = __( "Listing Image", "listings" );
$none_text         = __( "None", "listings" );
$options_text      = __( "OPTIONS", "listings" );
$view_listing_text = __( "View Listing", "listings" );

if ( $Automotive_Plugin->is_wpml_active() ) {
	$car = apply_filters( "wpml_object_id", $car, "listings", true );
}

$Automotive_Listing = new Automotive_Listing($car);
$main_image         = $Automotive_Listing->get_main_image(false);

?>
<div class='col-lg-<?php echo ($class == "3" ? $class . " col-md-6" : $class); ?>'>
    <div class="comparison-container margin-bottom-25">
        <div class="comparison-header"><span><?php echo get_the_title( $car ); ?></span>
          <strong><?php echo $Automotive_Plugin->format_currency( $Automotive_Listing->get_price() ); ?></strong>
				</div>
        <div class="comparison-img">
					<img src="<?php echo esc_url($main_image['src']); ?>" alt="<?php echo esc_attr($main_image['alt']); ?>" class="no_border">
				</div>
        <div class="car-detail clearfix">
            <div class="table-responsive">
              <table class="table comparison">
                  <tbody>
										<?php
										$listing_categories = $Automotive_Plugin->get_listing_categories();

										foreach ( $listing_categories as $category ) {
											$value = $Automotive_Listing->get_term($category['slug']);

											$value = $Automotive_Plugin->display_listing_category_term( $value, $category['slug'] );

											if ( isset( $category['currency'] ) && $category['currency'] == 1 ) {
												$value = $Automotive_Plugin->format_currency( $value );
											}

											$value = ( empty( $value ) ? $none_text : $value );

											if ( ! isset( $category['hide_category'] ) || $category['hide_category'] == 0 ) {
												echo "<tr><td>" . $Automotive_Plugin->display_listing_category( $category['singular'] ) . ": </td><td>" . html_entity_decode( $value ) . "</td></tr>";
											}
										} ?>
	                  <tr>
	                      <td><?php echo $options_text; ?>:</td>
	                      <td></td>
	                  </tr>
                  </tbody>
              </table>
            </div>
            <div class="option-tick-list clearfix">
                <div class="container">
                    <div class="row">
											<?php
											$features_and_options = $Automotive_Listing->get_features_and_options('array');

											if ( !empty($features_and_options) ) {

												switch ( $class ) {
													case 6:
														$columns      = 3;
														$column_class = 4;
														break;

													case 4:
														$columns      = 2;
														$column_class = 6;
														break;

													case 3:
														$columns      = 1;
														$column_class = 12;
														break;
												}

												$amount = ceil( count( $features_and_options ) / $columns );
												$new    = array_chunk( $features_and_options, $amount );

												foreach ( $new as $options ) {
											    echo "<div class='col-lg-" . $column_class . "'>";
													echo "<ul class='options'>";

													if(!empty($options)){
														foreach($options as $option){
															echo "<li>" . esc_html($option) . "</li>";
														}
													}

													echo "</ul>";
													echo "</div>";
												}
											} else {
												echo "<ul class='empty'><li>" . __( "No options yet", "listings" ) . "</li></ul>";
											} ?>
                    </div>
                </div>
            </div>
            <div class="comparison-footer margin-top-25 padding-top-20 padding-bottom-15">
                <a href="<?php echo esc_url( $Automotive_Listing->get_permalink() ); ?>" class="button-link"><?php echo $view_listing_text; ?></a>
            </div>
        </div>
    </div>
</div>