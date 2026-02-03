<?php
function newsletterForm() {
    $newsletter = '<div class="get-newsletter-main-container global_popup_wrapper custom-lead-capture-form">' .
        '<div class="get-newsletter-overlay global_popup_wrapper_overlay"></div>' .
        '<div class="get-newsletter-popup-wrapper global_popup_wrapper_content-wrapper overflow-auto">' .
            '<div class="get-newsletter-popup-container">' .
                '<span class="get-newsletter-popup-close global_popup_wrapper_close d_flex d_flex__justify-center d_flex__align-center cursor-pointer position_absolute border_circle"><i class="fa fa-times"></i></span>' .
                '<h2 class="font-weight-bold font-xl font-sans text-dark text-uppercase m-0 p-0">Newsletter</h2>' .
                '<div class="global-form-wrapper">' .
                    '<div class="global-form-form">' ;
                    $newsletter .= do_shortcode('[contact-form-7 id="25293" title="Newsletter form"]');
              $newsletter .= '</div>' .
                    '<div class="global-form-success d_none">' .
                        '<h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>' .
                        '<div class="d-flex justify-content-center">' .
                            '<img src="' . site_url() . '/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted">' .
                        '</div>' .
                        '<h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>' .
                        '<p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>' .
                        '<div class="sidebar__ctas">' .
                            '<a class="text_uppercase" href="' . site_url() . '/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>' .
                            '<a class="text_uppercase" href="' . site_url() . '/inventory">View Inventory</a>' .
                            '<a class="text_uppercase" href="' . site_url() . '/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>' .
	    '</div>' .
    '</div>';
    echo $newsletter;
}
function leaveReview() {
    echo '<div class="modal fade" id="leaveReviewModal" tabindex="-1" role="dialog" aria-labelledby="leaveReviewModalLabel" data-backdrop="true" aria-modal="true">'.
         '<div class="modal-dialog">'.
         '<div class="modal-content">'.
         '<div class="modal-header pb-20 px-0 pt-0 border-0">'.
         '<button type="button" class="close mb-4 float-none ml-auto global_popup_wrapper_close d-flex align-items-center justify-content-center border_circle p-0 m-0" data-dismiss="modal" aria-label="Close" style="opacity:1;margin-bottom:0 !important;">'.
         '<i class="fa fa-times" aria-hidden="true"></i>'.
         '</button>'.
         '</div>'.
         '<div class="modal-body leave-review-modal">'.
         '<iframe title="Leave a review for us" src="https://www.google.com/search?q=durangovalueautos%20reviews&igu=1" class="w-100" height="500" width="100%" loading="lazy"></iframe>'.
         '</div>'.
         '</div>'.
         '</div>'.
         '</div>';
  }
  