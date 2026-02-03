<?php
    function divi_child_form_submission_success() {
        $phoneNumber = get_field('quick_call_phone_number', 'options');
        $scheduleService = get_site_url() . '/service-and-parts/schedule-express-service-durango-co';
        $viewInventory = get_site_url() . '/new-vehicles-durango-colorado';
        $success = '<div class="global-form-success d_none">'.
                    '<h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>'.
                    '<div class="d-flex justify-content-center">'.
                    '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submission success" title="Form submission success" itemprop="image" loading="lazy" width="166" height="146">'.                      
                    '</div>'.
                    '<h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>'.
                    '<p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>'.
                    '<div class="sidebar__ctas">'.
                    '<a class="text_uppercase" href="'.$scheduleService.'">Schedule Service</a>'.
                    '<a class="text_uppercase" href="'.$viewInventory.'">View Inventory</a>'.
                    '<a class="text_uppercase" href="tel:'.$phoneNumber.'">Call Service & Parts</a>'.
                    '</div>'.
                    '</div>';
        return $success;
    }
    function divi_child_sidebar_form_submission_success() {
        $phoneNumber = get_field('quick_call_phone_number', 'options');
        $scheduleService = get_site_url() . '/service-and-parts/schedule-express-service-durango-co';
        $viewInventory = get_site_url() . '/new-vehicles-durango-colorado';
        $sidebarSuccess = '<div class="sidebar__form-success d_none">'.
                          '<div class="sidebar__form-success-img d_flex d_flex__justify-center">'.
                          '<img src="'. site_url() .'/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submission success" title="Form submission success" itemprop="image" loading="lazy" width="166" height="146">'.
                          '</div>'.
                          '<h3 class="sidebar__success-msg text_capitalize font_segoe font_bold text_center">Your message has been sent!</h3>'.
                          '<p class="sidebar__success-desc text_center">Thank you for your message. A representative will contact you soon.</p>'.
                          '<div class="sidebar__ctas">'.
                          '<a class="text_uppercase" href="'.$scheduleService.'">Schedule Service</a>'.
                          '<a class="text_uppercase" href="'.$viewInventory.'">View Inventory</a>'.
                          '<a class="text_uppercase" href="tel:'.$phoneNumber.'">Call Service & Parts</a>'.
                          '</div></div>';
        return $sidebarSuccess;
    }