<?php
    function divi_child_page_banner($image = null, $alt = null, $width = null, $height = null, $heading = null, $content = null, $smallScreenBanner = null) {
        $banner = '<div class="position-relative px-g content-page-banner d-none d-lg-block">'.
                  '<picture class="h-100 d-inline-block w-100">';
                  if( $smallScreenBanner && !empty($smallScreenBanner) ) {
                    $banner .= '<source media="(min-width:1200px)" srcset="'.$image.'" />';
                  }
                  $banner .= '<img src="'. ( $smallScreenBanner && !empty($smallScreenBanner) && $smallScreenBanner !== null ? $smallScreenBanner : $image ) .'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'" title="'.$alt.'" class="img-fluid w-100 h-100 object_fit_cover" loading="lazy" itemprop="image" />'.
                  '</picture>'. 
                  '<div class="position-absolute">'.
                  '<h1 class="text-white font-weight-bold font-helvetica p-0 mb-30 text-uppercase">'.$heading.'</h1>'.
                  '<p class="text-white font-weight-bold font-helvetica">'.$content.'</p>'.
                  '</div>'.
                  '</div>';
        return $banner;
    }