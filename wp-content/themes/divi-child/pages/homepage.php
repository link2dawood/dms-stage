<?php /* Template Name: Homepage Template */ 
require_once get_stylesheet_directory() . '/sidebarForm.php';
get_header();
?>
<!-- Hero Slider -->
<section class="section-margin-bottom" itemscope itemtype="https://schema.org/CarDealer">
  <?php 
    $sliders = array(
      'desktop' => array(
          'field' => 'hero_slider_desktop',
          'type' => 'd-none d-md-block',
      ),
      'mobile' => array(
          'field' => 'hero_slider_mobile',
          'type' => 'd-block d-md-none',
      ),
  );
  $defaultHeroSliderImages = array(
  array(
  'image' => '/wp-content/uploads/2023/07/local-shop-for-automotive-accessories.webp',
'url' => 'service-and-parts/accessories/',
	  'slide' => 'desktop',
  ),
	array(
  'image' => '/wp-content/uploads/2023/11/dust-the-dirt-with-a-detail.jpg',
'url' => 'service-and-parts/detail-department/',
		'slide' => 'desktop',
  ),
	  array(
  'image' => '/wp-content/uploads/2023/11/book-your-vehicle-now.jpg',
'url' => 'service-and-parts/schedule-express-service-durango-co/',
		  'slide' => 'desktop',
  ),
	  array(
  'image' => '/wp-content/uploads/2023/11/careers.jpg',
'url' => 'service-and-parts/careers/',
		  'slide' => 'desktop',
  ),
	   array(
  'image' => '/wp-content/uploads/2023/11/we-want-your-vehicle.jpg',
'url' => 'finance/value-your-trade/',
		   'slide' => 'desktop',
  ),
	   array(
  'image' => '/wp-content/uploads/2023/11/certified-pre-owned.jpg',
'url' => 'inventory/?certified-pre-owned-kia',
		   'slide' => 'desktop',
  ),
	  array(
  'image' => '/wp-content/uploads/2023/11/drive-off-the-lot-of-confidence.jpg',
'url' => 'inventory/?certified-pre-owned',
		  'slide' => 'desktop',
  ),
  );
	
  foreach( $sliders as $key => $value ) {
      $slider = get_field($value['field'], 'options');
      if (!empty($slider) && isset($slider)) {
          if (!is_array($slider)) {
              $slider = array($slider);
          }
          echo '<div class="home-hero-slider '.$value['type'].'">';
          $image_urls = array(); // initialize array to hold image URLs
          // Scheduled thanks giving banner
          $timezonee = new DateTimeZone('America/Denver');
          $dateNow = new DateTime('now', $timezonee);

          // Scheduled Black Friday Banner
          $xmas_start_time = new DateTime('2024-11-26 12:00:00', $timezone);
		  $xmas_end_time = new DateTime('2024-12-25 11:59:59', $timezone);
		  if( $dateNow >= $xmas_start_time && $dateNow <= $xmas_end_time ) {
			  echo '<a href="javascript:void(0)" class="d-inline-block" itemprop="url">';
            echo '<picture>'.
            '<source srcset="'.site_url().'/wp-content/uploads/2024/12/Copy-of-Christmas-Hours-1920-x-614-px.png" alt="4th July" width="1920" height="614" loading="lazy" media="(min-width: 575px)">'.
            '<img src="'.site_url().'/wp-content/uploads/2024/12/Copy-of-Christmas-Hours-600-x-400-px-1.png" alt="4th July" class="img-fluid w-100" width="600" height="400" loading="lazy" />'.
            '</picture>'.
            '</a>';
		  }

          // Black Friday Start Time: 11-22-23 @ 06:30 PM
          // Baclk Friday End Time: 11-23-23 @ 11:59 PM.
          $blackFriday_start_time = new DateTime('2024-11-26 12:00:00', $timezonee);
          $blackFriday_end_time = new DateTime('2024-12-23 11:59:59', $timezonee);
          // if($dateNow >= $blackFriday_start_time && $dateNow <= $blackFriday_end_time) {
          if($dateNow >= $blackFriday_start_time && $dateNow <= $blackFriday_end_time) {
            // Show the thanksgiving banner
            echo '<a href="javascript:void(0)" class="d-inline-block" itemprop="url">';
            echo '<picture>'.
            '<source srcset="'.site_url().'/wp-content/uploads/2024/12/12.12.24-12-Days-of-Christmas_1920x1080.jpg" alt="4th July" width="1920" height="614" loading="lazy" media="(min-width: 575px)">'.
            '<img src="'.site_url().'/wp-content/uploads/2024/12/12-Days-of-Christmas-600-x-400-px.png" alt="4th July" class="img-fluid w-100" width="600" height="400" loading="lazy" />'.
            '</picture>'.
            '</a>';
          }

          foreach( $slider as $slide ) {
            $imageID = $slide['hero_slider_'.$key.'_slide']['hero_slider_slide_image_'.$key.''];
            $image_attributes = wp_get_attachment_image_src($imageID, 'full');
            $image_url = $image_attributes[0];
            $image_urls[] = $image_url; // add URL to array
            $slideURL = $slide['hero_slider_'.$key.'_slide']['hero_slider_slide_url_'.$key.''];
            $imgAlt = $slide['hero_slider_'.$key.'_slide']['hero_slider_slide_alt_text_'.$key.''];
            echo '<a href="'.$slideURL.'" class="d-inline-block" itemprop="url">';
            echo '<img src="'.$image_url.'" alt="'.$imgAlt.'" class="img-fluid w-100" width="1548" height="512" loading="lazy" />';
            echo '</a>';
        }
        
          echo '</div>';
      }else {
		  /* In case if something go wrong in the acf side or somehow the fields are removed
		   *** from durango panel then use a default slider images ***/
		 echo '<div class="home-hero-slider '.$value['type'].'">';
		  foreach($defaultHeroSliderImages as $images) {
			  echo '<a href="'.site_url().'/'.$images['url'].'" class="d-inline-block">';
			  echo '<img src="'.site_url().'/'.$images['image'].'" class="img-fluid w-100" width="1548" height="512" loading="lazy" alt="hero slider image" />';
			  echo '</a>'; 
			  		 
		  }
		  echo '</div>';
	  }
  }
  ?>
</section>
<!-- Dream in driveways -->
<section class="section-margin-bottom pt-5 dream-driveways">
    <h2 class="text-capitalize text-center font-segoe text-grey-4 font-weight-bold section-margin-bottom pb-0" style="font-size:30px;"><?php echo get_field('dream_in_driveways_heading', 'options'); ?></h2>
    <?php 
        $dreamInDrivewaysRow = get_field('dream_in_driveways_row', 'options');
        echo '<div class="px-g">';
        echo '<div class="row">';
        $dreamdrivewaysCounter = 0;
        foreach( $dreamInDrivewaysRow as $row ) {
          $column = $row['dream_in_driveways_column'];
            echo '<div class="col-12 col-md-4 position-relative feature-box '. ( $dreamdrivewaysCounter < 2 ? "mb-20" : "" ) .' mb-md-0">';
            echo '<div class="position-relative">';
            echo '<a href="'.site_url(). $column['dream_in_driveways_url'].'" class="d-inline-block w-100" itemprop="url">';
            echo '<img src="'.wp_get_attachment_image_src($column['dream_in_driveways_image'], "full")[0].'" class="w-100 img-fluid object_fit_cover rounded-20" loading="lazy" height="270" width="493" itemprop="image" loading="lazy" />';
            echo '</a>';
            echo '<div class="feature-box-title">';
            echo '<a href="'.$column['dream_in_driveways_url'].'" class="font-segoe text-white font-xxl text-uppercase text-center text_underline" itemprop="url">'.$column['dream_in_driveways_column_text'].'</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            $dreamdrivewaysCounter++;
        }
        echo '</div>';
        echo '</div>';
    ?>
</section>
<!-- Durango Value Autos History -->
<section class="section-margin-bottom px-g position-relative durango-history-section overflow-hidden">
    <div class="h-100">
      <picture class="d-inline-block w-100">
        <source media="(min-width:767px)" srcset="<?php echo wp_get_attachment_image_src(get_field('durango_value_autos_history_banner_desktop','options'), 'full')[0]; ?>">
        <img src="<?php echo wp_get_attachment_image_src(get_field('durango_value_autos_history_banner_mobile', 'options'), 'full')[0] ?>" class="w-100 img-fluid h-100" loading="lazy" sizes="
                (min-width: 767px) 1526px,100vw">
      </picture>
    </div>
      <div class="position-absolute durango-history font-md">
        <p class="text-white font-segoe mb-20">
          <?php echo get_field('durango_value_autos_history_text','options'); ?>
        </p>
        <div class="durango-history-cta d-md-flex align-items-center justify-content-start">
            <a href="<?php echo site_url(); ?>/about-us" class="our-promise-btn mr-md-5 mb-3 mb-md-0 d-block text-center rounded-circle-px text-white text-uppercase font-weight-normal font-md font-helvetica shadow-secondary px-30">OUR PROMISE</a>
            <a href="https://www.durangomotorcompany.com/" target="_blank" class="our-promise-btn rounded-circle-px d-block text-center text-white text-uppercase font-weight-normal font-md font-helvetica shadow-secondary px-30">EXPLORE DMC <i class="ml-2 fa fa-arrow-up-right-from-square"></i></a>
        </div>
      </div>
</section>
<!-- Reviews Slider -->
<section class="section-margin-bottom px-g position-relative">
  <h2 class="text-center font-30 font-weight-bold pb-0 text-grey-4 font_segoe section-margin-bottom"><?php echo get_field('reviews_slider_heading', 'option'); ?></h2>
  <?php 
    echo '<div class="px-0 px-md-3 px-lg-4">';
    echo '<div class="global-slick-slider testimonials-slider">';
    foreach( get_field('reviews_slider_row', 'options') as $review ) {
      $authorName = $review['reviews_slider_slide_group']['review_author_name'];
      $starCount = $review['reviews_slider_slide_group']['review_star_count'];
      $uploadDate = $review['reviews_slider_slide_group']['review_upload_date'];
      $reviewContent = $review['reviews_slider_slide_group']['review_content'];
      $contentLength = str_word_count($reviewContent);
      echo '<div class="testimonials-slide mx-15 h-auto shadow-primary border rounded">';
      echo '<div class="d-flex flex-column-reverse flex-md-row align-items-start align-items-md-center justify-content-center justify-content-md-between mb-0 mb-md-2">';
      echo '<h3 class="pb-0 font-weight-bold text-grey-4 font-sm font-segoe mb-0 text-nowrap">'.$authorName.'</h3>';
      echo '<div class="review-slider-rating d-flex align-items-center justify-content-start justify-content-md-end mb-15 mb-md-0">';
      for( $i = 0; $i < $starCount; $i++ ) {
        if( $i != 5 ) {
          echo '<span class="review-slider-rating-star testimonials-rating-star font-20"><i class="fa-solid fa-star"></i></span>';
        }
      }
      echo '</div></div>';
      echo '<div class="review-slider-slide-date mb-20 text-left">';
      echo '<h4 class="font-weight-bold pb-0 text-grey-4 font-sm font-segoe">'.$uploadDate.' Ago</h4>';
      echo '</div>';
      if( $contentLength > 30 ) {
        $short_review = implode(' ', array_slice(explode(' ', $reviewContent), 0, 30));
        print_r('<p class="full-review-text d-none text-grey-4 font-20 pb-0 font-weight-normal font-segoe">'.$reviewContent.'</p>');
        echo '<p class="d-block short-review-text">'.$short_review.' <span class="read-all-review-text text-primary cursor-pointer ml-2" data-toggle="modal" data-target="#reviewsModal" data-backdrop="true">Read More...</span></p>';
      } else {
        print_r('<p class="m-0 p-0">'.$reviewContent.'</p>');
      }
      echo '</div>';
    }
    echo '</div>';
    echo '</div>';   
  ?>
</section>
<section class="section-margin-bottom d-flex justify-content-center align-items-center pt-30 pt-md-0">
  <button class="btn btn-primary text-white font-weight-bold font-20 d-inline-block rounded-circle-px font-sans leave-review" data-toggle="modal" data-target="#leaveReviewModal" data-backdrop="true">LEAVE US A REVIEW</button>
</section>
<!-- Maintain Loyalty -->
<section class="section-margin-bottom">
  <div class="row px-g m-0">
    <div class="col-12 col-md-5 position-relative maintain-loyalty-text-content">
      <h2 class="text-primary font-30 font-weight-bold">Maintain Loyalty</h2>
      <h2 class="text-primary font-30 font-weight-bold">For Lifetime</h2>
      <div class="maintain-divider mb-30 bg-primary"></div>
      <p class="text-primary font-helvetica font-lg"><?php echo get_field('maintain_loyalty_text_content', 'options'); ?></p>
    </div>
    <div class="col-12 col-md-7 pl-0 pr-0">
      <?php 
      $maintainLoyaltySlider = get_field('maintain_loyalty_slider_row', 'options');
      echo '<div class="maintain-loyalty-slider-wrapper d-flex flex-column flex-md-row">';
      foreach( $maintainLoyaltySlider as $slider ) {
        $slide = $slider['maintain_loyalty_slider_slide'];
        $thumbnail = wp_get_attachment_image_src($slide['maintain_loyalty_slide_thumbnail'],'full')[0];
        $width = wp_get_attachment_image_src($slide['maintain_loyalty_slide_thumbnail'],'full')[1];
        $height = wp_get_attachment_image_src($slide['maintain_loyalty_slide_thumbnail'],'full')[2];
        $alt = get_post_meta($slide['maintain_loyalty_slide_thumbnail'], '_wp_attachment_image_alt', true);
        $heading = $slide['maintain_loyalty_slide_heading'];
        $content = $slide['maintain_loyalty_slide_text_content'];
        echo '<div class="maintain-loyalty-slide">';
        echo '<div class="maintain-loyalty-slide-image">';
        echo '<img src="'.$thumbnail.'" width="'.$width.'" height="'.$height.'" class="img-fluid w-100 h-100 object_fit_cover" alt="'.$alt.'" loading="lazy" itemprop="image" />';
        echo '</div>';
        echo '<h3 class="pl-md-4 text-center text-md-left py-3 font-weight-bold text-primary font-helvetica font-xxl m-0">'. $heading .'</h3>';
        echo '<div class="pt-3 pb-0 pt-md-4 pb-md-4 px-30">';
        echo '<p class="font-lg font-helvetica text-primary text-center text-md-left">'.$content.'</p>';
        echo '</div>';
        echo '</div>';
      }
      echo '</div>';
      ?>
    </div>
  </div>
</section>
<!-- Automotive needs -->
<section class="section-margin-bottom px-g">
  <h2 class="p-0 text-center text-uppercase section-margin-bottom help-automotive-needs-heading"><?php echo get_field('help_automotive_needs_heading', 'options'); ?></h2>
  <div class="section-margin-bottom d-none d-lg-flex">
      <div class="flex-grow-1 font-helvetica text-center py-20 text-uppercase cursor-pointer automotive-navbar-pill active" data-slide-index="0">
        <span>Services</span>
      </div>
      <div class="flex-grow-1 font-helvetica text-center py-20 text-uppercase cursor-pointer automotive-navbar-pill" data-slide-index="1">
        <span>Parts</span>
      </div>
      <div class="flex-grow-1 font-helvetica text-center py-20 text-uppercase cursor-pointer automotive-navbar-pill" data-slide-index="2">
        <span>Detailing</span>
      </div>
      <div class="flex-grow-1 font-helvetica text-center py-20 text-uppercase cursor-pointer automotive-navbar-pill" data-slide-index="3">
        <span>DGO Autogear</span>
      </div>
      <div class="flex-grow-1 font-helvetica text-center py-20 text-uppercase cursor-pointer automotive-navbar-pill" data-slide-index="4">
        <span>Autobody</span>
      </div>
  </div>
  <div class="dropdown section-margin-bottom d-lg-none">
    <button class="dropdown-toggle automotive-needs-dropdown-value d-inline-block w-100 rounded-20 bg-transparent p-15 text-uppercase text-primary font-helvetica font-20 position-relative text-left font-weight-bold border" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      services
    </button>
    <div class="dropdown-menu w-100 bg-white shadow-secondary p-15 rounded-10" aria-labelledby="dropdownMenuButton">
      <span class="automotive-needs-dropdown-item dropdown-item text-uppercase cursor-pointer font-helvetica mb-15" data-slide-index="0">Services</span>
      <span class="automotive-needs-dropdown-item dropdown-item text-uppercase cursor-pointer font-helvetica mb-15" data-slide-index="1">Parts</span>
      <span class="automotive-needs-dropdown-item dropdown-item text-uppercase cursor-pointer font-helvetica mb-15" data-slide-index="2">Detailing</span>
      <span class="automotive-needs-dropdown-item dropdown-item text-uppercase cursor-pointer font-helvetica mb-15" data-slide-index="3">DGO Autogear</span>
      <span class="automotive-needs-dropdown-item dropdown-item text-uppercase cursor-pointer font-helvetica" data-slide-index="4">Autobody</span>
    </div>
  </div>

  <div class="row overflow-hidden" id="home-cardflow-sec">
    <div class="col-12 col-lg-6 m-0">
        <?php
        $rotatorSlider = get_field('help_automotive_needs_row', 'options');
        echo '<div class="swiper mySwiper w-100">';
        echo '<div class="swiper-wrapper">';
        foreach( $rotatorSlider as $slider ) {
          $slide = $slider['help_automotive_needs_slide'];
          $thumbnail = wp_get_attachment_image_src($slide['help_automotive_needs_slide_thumbnail'], 'full')[0];
          $width = wp_get_attachment_image_src($slide['help_automotive_needs_slide_thumbnail'], 'full')[1];
          $height = wp_get_attachment_image_src($slide['help_automotive_needs_slide_thumbnail'], 'full')[2];
          $alt = get_post_meta($slide['help_automotive_needs_slide_thumbnail'], '_wp_attachment_image_alt', true);
          $content = $slide['help_automotive_needs_slide_text_content'];
          $buttonText = $slide['help_automotive_needs_slide_button_text'];
          $buttonUrl = $slide['help_automotive_needs_slide_button_url'];
          $heading = $slide['help_automotive_needs_slide_heading'];
          $tooltipHours = '';
          if( strtolower($heading) == 'service' ) {
              $tooltipHours = get_field('store_services_hours', 'options');
            }else if( strtolower($heading) == 'parts' ) {
              $tooltipHours = get_field('store_parts_hours', 'options');
            }else if( strtolower($heading) == 'detailing' ) {
              $tooltipHours = get_field('store_detailing_hours', 'options');
            }else if( strtolower($heading) == 'dgo autogear' ) {
              $tooltipHours = get_field('store_dgo_autogear_hours', 'options');
            }else if( strtolower($heading) == 'abra autobody' ) {
              $tooltipHours = get_field('store_abra_autobody_hours', 'options');
          }
          echo '<div class="swiper-slide bg-white rounded-10 border shadow-secondary">';
          echo '<div class="mb-15 automotive-needs-thumbnail-image">';
          echo '<img src="'.$thumbnail.'" width="'.$width.'" height="'.$height.'" class="img-fluid h-100 w-100 object_fit_cover" loading="lazy"  title="'.$alt.'" alt="'.$alt.'" />';
          echo '</div>';
          echo '<div class="d-flex align-items-center justify-content-between mb-15">';
          echo '<h2 class="automotive-needs-heading font-xxl text-uppercase font-weight-bold pb-0 font-segoe s-s-title mb-0">'.$heading.'</h2>';
          echo '<div class="d-flex align-items-center justify-content-end flex-grow-1">';
          echo '<div class="position-relative">';
          echo '<span class="sidebar-popup-trigger mr-3 cursor-pointer" data-popup="rotator-slider-form" data-thumbnail="'.$thumbnail.'" data-heading="'.$heading.'"><i class="fa-solid fa-comment-dots text-primary font-20"></i></span>';
          echo '</div>';
          echo '<div class="mr-15 position-relative tooltip-hours-container">';
          echo '<span><i class="fa-regular fa-clock text-primary cursor-pointer font-20"></i></span>';
          if( $tooltipHours && !empty($tooltipHours) ) {
            echo '<div class="position-absolute bg-white shadow-secondary tooltip-hours pt-15 pb-0 px-10">';
            foreach($tooltipHours as $hours) {
              echo '<div class="d-flex align-items-center justify-content-between mb-15">';
              echo '<p class="p-0 font-helvetica">'.$hours['store_'.strtolower(str_replace(" ", "_", $heading)).'_hours_day_name'].'</p>';
              echo '<p class="p-0 font-helvetica">'.$hours['store_'.strtolower(str_replace(" ", "_",$heading)).'_hours_time'].'</p>';
              echo '</div>';
            }
            echo '</div>';
          }
          echo '</div>';
          echo '<div>';
          echo '<a href="tel:'.get_field('quick_call_phone_number', 'options').'"><i class="fa-solid fa-phone text-primary font-20"></i></a>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '<p class="pb-0 font-md font-helvetica text-primary mb-4">'.$content.'</p>';
          echo '<a href="'.$buttonUrl.'" class="btn btn-primary automotive-needs-link w-100 py-20 px-10 d-flex justify-content-center align-items-center text-white font-xxl font-helvetica text-uppercase">'.$buttonText.'</a>';
          echo '</div>';
        }
        echo '</div>';
        echo '<div class="swiper-slide-arr-container d-none d-md-block">';
        echo '<div class="swiper-button-next font-weight-bold"><i class="fa-solid fa-play text-primary"></i></div>';
        echo '<div class="swiper-button-prev font-weight-bold"><i class="fa-solid fa-play text-primary"></i></div>';
        echo '</div>';
        echo '<div class="swiper-pagination position-static mt-20 mb-20"></div>';
        echo '</div>';
        ?>
    </div>
    <div class="col-12 col-lg-6 d-none d-lg-flex align-items-center justify-content-center automotive-vehicle-image">
      <?php
        $automotiveVehicleImage = wp_get_attachment_image_src(get_field('help_automotive_needs_vehicle_image', 'options'), 'full')[0];
        $automotiveVehicleImageWidth = wp_get_attachment_image_src(get_field('help_automotive_needs_vehicle_image', 'options'), 'full')[1];
        $automotiveVehicleImageHeight = wp_get_attachment_image_src(get_field('help_automotive_needs_vehicle_image', 'options'), 'full')[2];
        $automotiveVehicleImageAlt = get_post_meta('help_automotive_needs_vehicle_image', '_wp_attachment_image_alt', true);
        echo '<img src="'.$automotiveVehicleImage.'" class="img-fluid w-100 position-absolute automotive-vehicle-image" width="'.$automotiveVehicleImageWidth.'" height="'.$automotiveVehicleImageHeight.'" alt="'.$automotiveVehicleImageAlt.'" loading="lazy" />';
      ?>
    </div>
  </div>
</section>
<!-- Get in touch -->
<section class="section-margin-bottom get-in-touch">
  <div class="row px-g m-0">
    <div class="col-12 col-lg-6 py-20 px-30 bg-primary mb-20 mb-lg-0">
      <h2  class="text-center font-weight-bold font-30 text-white font-helvetica pb-0">GET IN TOUCH</h3>
      <div class="row">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
          <h3 class="text-center text-white font-helvetica text_underline font-xxl mb-30 font-weight-bold">SALE HOURS</h3>
          <?php  
          $storeSaleHours = get_field('store_sale_hours', 'options');
          if( $storeSaleHours && !empty($storeSaleHours) ) {
            foreach( $storeSaleHours as $hours ) {
              $storeHour = '<div class="d-flex align-items-center justify-content-between mb-15">'.
                           '<p class="pb-0 font-weight-bold font-helvetica text-white font-lg '. ( strtolower($hours['store_sale_hours_day_name']) == "sunday" ? "flex-grow-1" : "") .' ">'. $hours['store_sale_hours_day_name'] .'</p>'.
                           '<p class="pb-0 font-weight-normal font-segoe text-white font-lg '. ( strtolower($hours['store_sale_hours_day_name']) == "sunday" ? "flex-grow-1 text-center" : "") .'">'. $hours['store_sale_hours_time'] .'</p>'.
                           '</div>';
              echo $storeHour;
            }
          }
          ?>
        </div>
        <div class="col-12 col-md-6">
          <h3 class="text-center text-white font-helvetica text_underline font-xxl mb-30 font-weight-bold">SERVICE HOURS</h3>
          <?php  
			$defaultStoreServiceHours = array(
				array(
				'day' => 'Monday',
				'time' => '7:00 - 5:00 PM',
				),
				array(
				'day' => 'Tuesday',
				'time' => '7:00 - 5:00 PM'
				),
				array(
				'day' => 'Wednesday',
				'time' => '7:00 - 5:00 PM'
				),
				array(
				'day' => 'Thursday',
				'time' => '7:00 - 5:00 PM'
				),
				array(
				'day' => 'Friday',
				'time' => '7:00 - 5:00 PM'
				),
				array(
				'day' => 'Saturday',
				'time' => '7:00 - 5:00 PM'
				),
				array(
				'day' => 'Sunday',
				'time' => 'Closed'
				),
			);
			
			foreach($defaultStoreServiceHours as $hours) {
				    $storeHours = '<div class="d-flex align-items-center justify-content-between mb-15">'.
                           '<p class="pb-0 font-weight-bold font-helvetica text-white font-lg '. ( strtolower($hours['day']) == "sunday" ? "flex-grow-1" : "") .' ">'. $hours['day'] .'</p>'.
                           '<p class="pb-0 font-weight-normal font-segoe text-white font-lg '. ( strtolower($hours['day']) == "sunday" ? "flex-grow-1 text-center" : "") .'">'. $hours['time'] .'</p>'.
                           '</div>';
              echo $storeHours;
			  }
			
          $storeServiceHours = get_field('store_services_hours', 'options');
			
        
          ?>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6 pr-0 pl-0 pl-lg-3">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3176.864243130609!2d-107.86427602520061!3d37.227197543515395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x873c03328c911bd7%3A0x6cd230ed756a48ba!2sDurango%20Value%20Autos!5e0!3m2!1sen!2s!4v1683717950546!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-100 h-100"></iframe>
    </div>
  </div>
</section>
<!-- Quick Inquire -->
<section class="section-margin-bottom px-g">
  <div class="row">
    <div class="col-12 col-lg-6 mb-20 mb-lg-0 company-showcase-column">
      <?php 
        $video_id = get_field('durango_motor_company_showcase_video', 'options');
        $video_url = wp_get_attachment_url($video_id);
        if( isset( $video_url ) && !empty($video_url) ) {
          echo '<video poster="'.wp_get_attachment_image_src(get_field('durango_motor_company_showcase_video_poster', 'options'),'full')[0].'" class="w-100 h-100 object_fit_cover company-showcase-video">';
          echo '<source type="video/webm" src="'.$video_url.'">';
          echo '</video>';
        }
      ?>
      <div class="company-showcase-overlay position-absolute h-100"></div>
        <i class="company-showcase-video-icon company-showcase-video-play position-absolute fa-solid fa-circle-play text-white cursor-pointer"></i>
        <i class="company-showcase-video-icon company-showcase-video-pause position-absolute fa-sharp fa-solid fa-circle-pause text-white cursor-pointer d-none"></i>
    </div>
    <div class="col-12 col-lg-6 quick-inquire-column spinner-hidden">
      <div class="global-form-wrapper">
          <div class="global-form-form">
              <?php echo do_shortcode('[contact-form-7 id="136555" title="Home quick inquire"]'); ?>
          </div>
          <?php echo divi_child_form_submission_success(); ?>
      </div>
    </div>
  </div>
</section>
<!-- Recent post scroller -->
<section class="section-margin-bottom px-g" id="home-recent-post-scroller">
    <?php echo do_shortcode('[vehicle_scroller limit="30" title="New Arrivals" description="Browse throught the vast selection of vehicles that have recently added to our inventory"]'); ?>
</section>
<!-- View Used Vehicles -->
<section class="section-margin-bottom px-g">
  <?php 
        $viewVehiclesRow = get_field('view_used_vehicles_row', 'options');
        echo '<div class="row">';
        $viewVehiclesCounter = 0;
        foreach( $viewVehiclesRow as $row ) {
          $column = $row['view_used_vehicles_column'];
            echo '<div class="col-12 col-md-4 position-relative feature-box '. ( $viewVehiclesCounter < 2 ? "mb-20" : "" ) .' mb-lg-0">';
            echo '<div class="position-relative">';
            echo '<a href="'.site_url(). $column['view_used_vehicles_url'].'" class="d-inline-block w-100" itemprop="url">';
            echo '<img src="'.wp_get_attachment_image_src($column['view_used_vehicles_image'], "full")[0].'" class="w-100 img-fluid object_fit_cover rounded-20" loading="lazy" height="270" width="493" itemprop="image" loading="lazy" />';
            echo '</a>';
            echo '<div class="feature-box-title">';
            echo '<a href="'.$column['view_used_vehicles_url'].'" class="font-segoe text-white font-xxl text_underline text-uppercase text-center" itemprop="url">'.$column['view_used_vehicles_text'].'</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            $viewVehiclesCounter++;
        }
        echo '</div>';
    ?>
</section>
<!-- Bottom Banners -->
<section class="section-margin-bottom px-g">
  <div class="row">
  <?php 
  $offersBanners =  get_field('bottom_offers_banners_group', 'options');
  $offerBannerCounter = 0;
  foreach( $offersBanners as $banner ) {
    $image = wp_get_attachment_image_src($banner['bottom_offers_banners_image'],'full')[0];
    $imageUrl = $banner['bottom_offers_banners_urls'];
    $imageWidth = wp_get_attachment_image_src($banner['bottom_offers_banners_image'],'full')[1];
    $imageHeight = wp_get_attachment_image_src($banner['bottom_offers_banners_image'],'full')[2];
    $imageAlt = get_post_meta($banner['bottom_offers_banners_image'], '_wp_attachment_image_alt', true);
    echo '<div class="col-12 col-lg-6 '. ( $offerBannerCounter < 1 ? "mb-20" : "" ) .' mb-lg-0">';
    echo '<a href="'.$imageUrl.'" class="d-inline-block w-100 h-100">';
    echo '<img src="'.$image.'" alt="'.$imageAlt.'" width="'.$imageWidth.'" height="'.$imageHeight.'" class="img-fluid w-100 h-100 border border-dark" loading="lazy" itemprop="image" />';
    echo '</a>';
    echo '</div>';
    $offerBannerCounter++;
  }
  ?>
   </div>
</section>

<!-- Reviews Popup -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" data-backdrop="true" aria-modal="true" style="z-index:99999;">
  <div class="modal-dialog">
    <div class="modal-content overflow-auto">
      <div class="modal-header pb-20 px-0 pt-0 border-0">
        <button type="button" class="close mb-4 float-none ml-auto global_popup_wrapper_close d-flex align-items-center justify-content-center border_circle p-0 m-0" data-dismiss="modal" aria-label="Close" style="opacity:1;margin-bottom:0 !important;">
          <i class="fa fa-times" aria-hidden="true"></i>
        </button>
      </div>
      <div class="modal-body reviews-modal">
      </div>
    </div>
  </div>
</div>

<?php echo sidebarForm('rotator-slider-form', null, 'Schedule a service', null, null, null, null, '[contact-form-7 id="148997" title="Rotator slider form"]', true ); ?>


<?php get_footer();  ?>