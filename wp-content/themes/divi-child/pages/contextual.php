<?php /* Template Name: contextual page template */ ?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
          $heroThumbnail = '';
          $heroThumbnailWidth = '';
          $heroThumbnailHeight = '';
          $heroBannerHeading = '';
          $heroBannerContent = '';
          if( get_field('contextual_page_hero_banner_image', 'options') ) {
              $heroThumbnail = wp_get_attachment_image_src(get_field('contextual_page_hero_banner_image', 'options'),'full')[0];
              $heroThumbnailWidth = wp_get_attachment_image_src(get_field('contextual_page_hero_banner_image', 'options'),'full')[1];
              $heroThumbnailHeight = wp_get_attachment_image_src(get_field('contextual_page_hero_banner_image', 'options'),'full')[2];
              $heroThumbnailAlt = get_post_meta(get_field('contextual_page_hero_banner_image','options'), '_wp_attachment_image_alt', true);
          }
      
          if( get_field('contextual_page_hero_banner_title', 'options') ) {
              $heroBannerHeading = get_field('contextual_page_hero_banner_title', 'options');
          }
          if( get_field('contaxtual_page_hero_banner_content', 'options') ) {
              $heroBannerContent = get_field('contaxtual_page_hero_banner_content', 'options');
          }
          echo divi_child_page_banner($heroThumbnail, $heroThumbnailAlt, $heroThumbnailWidth, $heroThumbnailHeight, $heroBannerHeading, $heroBannerContent);  
    ?>
    <div class="content-page-container">
      <?php 
      $contextualVehiclesRow = get_field('contextual_page_vehicles_row','options');
      $contextual = '<div class="contextual">';
      if( $contextualVehiclesRow ) {
      foreach( $contextualVehiclesRow as $contextualRow ) {
          $vehicleGroup = $contextualRow['contextual_page_vehicles_type_group'];
          $vehicleGroupHeading = $vehicleGroup['contextual_page_vehicle_type_heading'] && !empty($vehicleGroup['contextual_page_vehicle_type_heading']) ? $vehicleGroup['contextual_page_vehicle_type_heading'] : null ;
          $vehicleGroupRow = $vehicleGroup['contextual_page_vehicle_types_row'];
          if( $vehicleGroupHeading && $vehicleGroupRow ) {
          $contextual .= '<h2 class="beyond-heading font-weight-bold font-helvetica text-grey-3 pb-15 border-bottom mb-30">'.$vehicleGroupHeading.'</h2>'.
                         '<div class="row mb-30">';
          foreach( $vehicleGroupRow as $row ) {
            $rowGroup = $row['contextual_page_vehicle_type_group'];
            $image = wp_get_attachment_image_src($rowGroup['contextual_vehicle_type_image'], 'full');
            if( $image ) {
              $image = $image[0];
              $imageWidth = $image[1];
              $imageHeight = $image[2];
              $imageAlt = get_post_meta($rowGroup['contextual_vehicle_type_image'], '_wp_attachment_image_alt', true);
              $imageHeading = $rowGroup['contextual_vehicle_type_name'];
              $contextual .= '<div class="col-12 col-md-4 feature-box position-relative">'.
              '<div class="position-relative">'.
              '<a class="d-inline-block w-100" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.
              '<img src="'.$image.'" alt="'.$imageAlt.'" width="'.$imageWidth.'" height="'.$imageHeight.'" class="img-fluid w-100" loading="lazy" itemprop="image" />'.
              '</a>'.
              '<div class="feature-box-title">'.
              '<a class="font-segoe text-white font-xxl font-weight-bold" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.$imageHeading.'</a>'.
              '</div>'. 
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
    </div>
    </article>
<?php endwhile; ?>
</div>
<?php get_footer(); ?>