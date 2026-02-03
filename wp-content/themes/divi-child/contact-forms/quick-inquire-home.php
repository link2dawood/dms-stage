<?php 
function divi_child_homepage_quick_inquire() {
    ob_start(); // Start output buffering
    ?>
    <div class="global-form-wrapper">
        <div class="global-form-form">
            <?php echo do_shortcode('[contact-form-7 id="136555" title="Home quick inquire"]'); ?>
        </div>
        <div class="global-form-success d_none">
            <h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>
                <div class="d-flex justify-content-center">
                    <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted">
                </div>
                <h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>
                <p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>
                <div class="sidebar__ctas">
                    <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>
                    <a class="text_uppercase" href="<?php echo site_url(); ?>/inventory">View Inventory</a>
                    <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>
                </div>
        </div>
    </div>
        
    <?php
    return ob_get_clean(); // Return the output buffer and clean it
}

add_shortcode('homepage-quick-inquire','divi_child_homepage_quick_inquire');
?>
