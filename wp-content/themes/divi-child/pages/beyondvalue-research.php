<?php /* Template Name: Beyond Value Research Template*/
get_header(); ?>

    <main class="mt-20 mb-20 px-g">
        <a class="page-back-history border border-danger d-flex align-items-center justify-content-center" href="#">
            <i class="fa-solid fa-arrow-left-long text-danger font-xl"></i>
        </a>
        <h1 class="mt-20 mb-20 text-eight font-weight-normal text-capitalize font-xl font-inter p-0">Beyond Value Research</h1>
        <?php 
$contextualVehiclesRow = get_field('contextual_page_vehicles_row','options');

echo '<div class="mb-5 d-flex justify-content-start flex-wrap">';
foreach( $contextualVehiclesRow as $contextualRow ) {
    $vehicleGroup = $contextualRow['contextual_page_vehicles_type_group'];
    $vehicleGroupHeading = $vehicleGroup['contextual_page_vehicle_type_heading'] && !empty($vehicleGroup['contextual_page_vehicle_type_heading']) ? $vehicleGroup['contextual_page_vehicle_type_heading'] : null ;
    $filterURLPos = strpos($vehicleGroupHeading, '&');
    $filterURL = strtolower($vehicleGroupHeading);

    if ($filterURLPos !== false) {
        $filterURL = substr($vehicleGroupHeading, 0, $filterURLPos);
        $filterURL = strtolower($filterURL);
    }
    echo '<a class="research-pill bg-grey-8 border mr-30 py-10 px-20 rounded-circle-px mb-15" href="'.site_url().'/inventory/?type_of_vehicle%5b%5d='.$filterURL.'" itemprop="link">'.$vehicleGroupHeading.'</a>';
}
echo '</div>';

$contextual = '<div class="beyond-research-wrapper">';
if( $contextualVehiclesRow ) {
foreach( $contextualVehiclesRow as $contextualRow ) {
    $vehicleGroup = $contextualRow['contextual_page_vehicles_type_group'];
    $vehicleGroupHeading = $vehicleGroup['contextual_page_vehicle_type_heading'] && !empty($vehicleGroup['contextual_page_vehicle_type_heading']) ? $vehicleGroup['contextual_page_vehicle_type_heading'] : null ;
    $vehicleGroupRow = $vehicleGroup['contextual_page_vehicle_types_row'];
    if( $vehicleGroupHeading && $vehicleGroupRow ) {
    $contextual .= '<h2 class="text-eight font-weight-bold font-xl font-inter mb-30">'.$vehicleGroupHeading.'</h2>'.
                   '<div class="mb-30">';
    foreach( $vehicleGroupRow as $row ) {
      $rowGroup = $row['contextual_page_vehicle_type_group'];
      $image = wp_get_attachment_image_src($rowGroup['contextual_vehicle_type_image'], 'full');
      if( $image ) {
        $image = $image[0];
        $imageWidth = $image[1];
        $imageHeight = $image[2];
        $imageAlt = get_post_meta($rowGroup['contextual_vehicle_type_image'], '_wp_attachment_image_alt', true);
        $imageHeading = $rowGroup['contextual_vehicle_type_name'];
        $contextual .= '<div class="position-relative">'.
        '<div class="position-relative d-flex align-items-start justify-content-start">'.
        '<a class="d-inline-block mr-4 beyond-research-image" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.
        '<img src="'.$image.'" alt="'.$imageAlt.'" width="'.$imageWidth.'" height="'.$imageHeight.'" class="img-fluid w-100" loading="lazy" itemprop="image" />'.
        '</a>'.
        '<a class="font-segoe font-xl text-eight font-weight-normal text-capitalize" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.$imageHeading.'</a>'.
        '</div>'.
        '</div>';
      }
    }
    $contextual .= '</div>';
  }
}
}
$contextual .= '</div>';
echo $contextual;

?>
    </main>

<?php get_footer(); ?>