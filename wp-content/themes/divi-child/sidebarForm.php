<?php
require_once get_stylesheet_directory() . '/custom-templates/form-success.php';
function sidebarForm($popup, $image, $alt, $title, $stock, $price, $popupTitle, $cf7Form, $half = false) {
    $form = '<div class="sidebar__form d_none" data-popup="'.$popup.'">'.
            '<div class="sidebar__overlay sidebar-popup-close" data-popup="'.$popup.'"></div>'.
            '<div class="sidebar__content position_relative">'.
            '<div class="sidebar__content-inner">';
            if( $half ) {
                $form .= '<div class="sidebar__top sidebar__top-full d_flex sidebar__pending">'.
                         '<div class="sidebar__top-img w-100 h-100">'.
                         '<img src="'.$image.'" alt="'.$alt.'" width="483" height="150" class="img-fluid w-100 h-100 object_fit_cover" loading="lazy" title="'.$alt.'" />'.
                         '<div class="sidebar-popup-close position-absolute sidebar-close-icon bg-white d-flex justify-content-center align-items-center rounded-circle-px" data-popup="'.$popup.'">'.
                         '<span class="fa fa-times text-primary" aria-hidden="true"></span></div></div></div>';
            }else{
                $form .= '<div class="sidebar__top sidebar__top-half d_flex sidebar__pending">'.
                '<div class="sidebar__top-img">'.
                '<img src="'.$image.'" alt="'.$alt.'" width="140" height="150" class="img-fluid w-100 h-100 object_fit_cover" loading="lazy" title="'.$alt.'" />'.
                '</div>'.
                '<div class="sidebar__top-content">'.
                '<h3 class="color_white font_bold font_helvetica mb-0 pb-0 font-lg" style="width:90%;">'. $title .'</h3>'.
                '<b class="color_white">Stock #: '. $stock .'</b>'.
                '<strong class="color_white d_block font-lg mb-0">'.$price.'</strong>'.
                // '<a href="'.$viewDetails.'" class="text_underline d_block color_white">View more details</a>'.
                '<div class="sidebar-popup-close position-absolute sidebar-close-icon bg-white d-flex justify-content-center align-items-center rounded-circle-px" data-popup="'.$popup.'">'.
                '<span class="fa fa-times text-primary"></span>'.
                '</div></div></div>';
            }
            
            $form .= '<div class="sidebar__form-content">'.
            '<div class="sidebar__form-first">'.
            '<h2 class="sidebar__form-title font_bold text_capitalize font_segoe p_0 color_black">'. $popupTitle .'</h2>'.
            '<h3 class="text_capitalize sidebar__form-subheading font_bold p_0">Send a message</h3>';
            $form .='<div class="sidebar__form-form">';
            $form .= vehicleMoreDetailsBody();
            $form .= vehicleShareForm();
            $form .= do_shortcode($cf7Form);
            $form .= '</div>';
			$form .= '</div>';
			$form .= divi_child_sidebar_form_submission_success();  
          
            $form .= '</div></div></div></div>';
    return $form;
}
function vehicleMoreDetailsBody() {
    $form = '<div class="vehicle-more-container">'.
            '<div class="d-flex align-items-center justify-content-between more-vehicles-btn-wrapper mb-5 pb-1">'.
            '<a href="tel:'.salesPhoneNumber().'" class="more-vehicle-form-box py-15 w-50">'.
            '<i class="fa fa-phone d-block mb-1 text-center text-sixth font-lg"></i>'.
            '<span class="text-sixth font-weight-bold d-block text-center">Phone</span>'.
            '</a>'.
            '<a href="#" class="more-vehicle-form-box py-15 w-50 sidebar-popup-trigger" data-popup="guest-request-text">'.
            '<span class="icon-icon-sms mb-1 font-lg d-block text-center"></span>'.
            '<span class="text-sixth font-weight-bold d-block text-center">Text</span>'.
            '</a>'.
            '</div>';
    $pagesLinks = array(
        'Online Credit Approval' => '/finance/car-loans-in-durango-co/',
        'Value My Trade' => '/finance/value-your-trade/',
        'KBB Your car' => 'https://www.kbb.com/instant-cash-offer/W/70317903/43A6F9B8-DB6C-48C0-A360-F658B2176E3E/',
        'guest reviews' => '/about-us/reviews-us-and-testimonials/',
        'view all inventory' => '/inventory',
        'manager specials' => '/specials/managers-specials/',
        'schedule service' => '/service-and-parts/schedule-express-service-durango-co/',
    );
    foreach( $pagesLinks as $page => $url ) {
        $form .= '<a href="'.(strtolower($page) !== 'kbb your car' ? site_url() : ''). $url.'" class="text-uppercase d-block bg-sixth text-center font-weight-bold text-white py-15  font-segoe mb-30"  '.(strtolower($page) === 'kbb your car' ? 'target="_blank"' : '').' >'.$page.'</a>';
    }

    $form .= '</div>';
    return $form;
}
function vehicleShareForm() {
    // Check if the request was made using HTTPS
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

    // Get the host (domain name)
    $host = $_SERVER['HTTP_HOST'];

    // Get the URL path and query string
    $request_uri = $_SERVER['REQUEST_URI'];

    // Combine the parts to form the complete URL
    $current_url = $protocol . $host . $request_uri;
    $form = '<div class="vehicle-share-form">'.
            '<a href="https://www.facebook.com/sharer/sharer.php?u='.$current_url.'" target="_blank" class="d-block w-100 mb-30 text-white font-weight-bold font-lg bg-primary p-15" href="#"><i class="fa fa-facebook mr-2"></i>Facebook</a>'.            
            // '<a class="d-block w-100 mb-30 text-white font-weight-bold font-lg bg-primary p-15" href="https://mail.google.com/mail/?view=cm&fs=1&su=Checkout This Vehicle&body='.$current_url.'" target="_blank"><img src="'.site_url().'/wp-content/themes/divi-child/assets/images/vehicle-email-share.png" alt="vehicle email share" itemprop="image" width="30" class="mr-2"/>Email</a>'.            
            '<div class="d-block cursor-pointer w-100 mb-30 share-via-email text-white font-weight-bold font-lg bg-primary p-15" target="_blank"><img src="'.site_url().'/wp-content/themes/divi-child/assets/images/vehicle-email-share.png" alt="vehicle email share" itemprop="image" width="30" class="mr-2"/>Email</div>'.
            '<div class="share-via-email-wrapper d-none">';
    $form .= do_shortcode('[contact-form-7 id="c6abfa1" title="Share Listing Email"]');
    $form .= '</div>'.
             '<div class="copy-page-url d-flex align-items-center cursor-pointer justify-content-start w-100 mb-30 text-dark font-weight-bold font-lg bg-grey-10 p-15" href="#"><img src="'.site_url().'/wp-content/themes/divi-child/assets/images/vehicle-link-share.png" alt="vehicle link share" itemporp="image" width="30" class="mr-2"/>Copy Link To Share</div>'.      
            '<button class="btn w-100 sidebar-popup-close" data-popup="sticky-cta">DONE</button>'.      
            '</div>';

    return $form;
}