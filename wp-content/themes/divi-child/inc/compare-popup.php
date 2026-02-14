<?php
/**
 * File displays compare modal popup on inventory pages
 */

add_action('wp_footer', 'add_popup_modal');

function add_popup_modal(){
    
if(is_page('new-vehicles-durango-colorado')
   || is_page('used-vehicles-durango-colorado')
   || is_page('kia')
|| is_singular( 'listings' ) 
|| is_page('compare-vehicles')) { ?>

  <div role="document" class="global_popup_wrapper compare-vehicles-popup" id="compareModal" aria-labelledby="compareModal" aria-hidden="true" tabindex="-1" role="dialog">
    <div class="global_popup_wrapper_overlay compare-vehicles-popup__overlay"></div>
    <div class="global_popup_wrapper_content-wrapper overflow_y compare-vehicles-popup__content">
      <div class="compare-vehicles-popup__header">
        <span class="compare-vehicles-popup__close global_popup_wrapper_close d_flex d_flex__justify-center d_flex__align-center border_circle cursor-pointer ml-auto">
         <i class="fa fa-times" style="pointer-events:none;user-select:none;"></i>
        </sp>
      </div>
      <div class="compare-vehicles-popup__content">
          <div class="compare-result"></div>
      </div>
    </div>
  </div>

<?php
    }
}