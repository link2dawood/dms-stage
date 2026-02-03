<?php /* Template Name: Car Loans Tempalte */ ?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
        <!-- top header banner -->
        <div class="global-content-banner-row m_0 has-image-banner top-header-banner">
                <div class="global-content-banner-column car-loans-banner-column image_column">
                    <div class="global-content-banner-module top-header-banner__inner position_relative">
                        <h1 class="p_0 global-content-banner-heading color_white font_helvetica font_bold text_uppercase">Online Pre-Approval</h1>
                        <div class="global-content-banner-para-wrapper">
                        <p class="p_0 global-content-banner-para color_white font_helvetica font_bold global-content-first-para">Are you looking for an auto loan or to finance your vehicle? We're here to help. We work with financial institutions and lenders across the region who can provide you the best auto loan or lease. Begin your process here by applying to get pre-approved for financing.</p>
                        </div>
                    </div>
                </div>
        	</div>
            <!-- top header banner ended -->
            <!-- car loans content section started -->
            <div class="global-content-row car-loans-row">
                <div class="global-content-column-first">
                    <h3 class="font_segoe global-content-primary-heading  p_0 font_bold">
                    Get Online Pre-Approval for Financing in Durango, CO
                    </h3>
                    <div class="mt-20">
                        <a href="<?php echo site_url(); ?>/coappl-finance" class="btn btn-primary">have a co-applicant? click here</a>
                    </div>
                    <div class="multistep-step-indicator-wrapper d_grid">
                        <div class="multistep-step-col multistep-step-col-active" data-attr="step-1">
                            <span class="multistep-step-counter d_inline-block">1</span>
                            <b class="color_white font_helvetica text_uppercase">contact info</b>
                        </div>
                        <div class="multistep-step-col" data-attr="step-2">
                            <span class="multistep-step-counter d_inline-block">2</span>
                            <b class="color_white font_helvetica text_uppercase">emplyment & income info</b>
                        </div>
                        <div class="multistep-step-col multistep-col-3" data-attr="step-3">
                            <span class="multistep-step-counter d_inline-block">3</span>
                            <b class="color_white font_helvetica text_uppercase">vehicle info</b>
                        </div>
                    </div>
                    <div class="multistep-form-container">
                        <?php echo do_shortcode('[car_loans_contact_form]'); ?>
                    </div>
                    <div class="car-loans-form-notice">
                        <p class="global-content-primary-para p_0 multistep-after-form-para">In connection with your transaction, Durango Value Autos may acquire information about you as described in this notice, which we handle as stated in this notice.</p>
                        <p class="global-content-primary-para p_0 ">We may collect personally identifiable information such as name, postal address, telephone number, e-mail address, social security number, date of birth, etc. This personal information is collected and used by Durango Value Autos Credit Application staff for the purpose of facilitating a relationship or business transaction.</p>
                        <p class="global-content-primary-para p_0 ">Our website resides behind a firewall and uses SSL (Secure Sockets Layer, the industry-standard security protocol used to communicate with browsers) to transmit personal information. Data is strongly encrypted during transmission to ensure that personal and payment information is secure. Industry-standard data encryption techniques are used to protect personal information..</p>
                    </div>
                </div>
                <div class="global-content-columns-second">
                <div class="global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">quick links</h3>
                    <ul class="global-content-right-list-wrapper pl_0">
                    <li class="global-content-right-list"><a href="<?php echo site_url() . get_field('inventory_quick_link', 'option'); ?>" class="global-content-right-lists-item font_helvetica text_capitalize">inventory</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url() . get_field('schedule_service_quick_link', 'option'); ?>" class="global-content-right-lists-item font_helvetica text_capitalize">schedule service</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url() . get_field('dgo_accessories_quick_link', 'option'); ?>" class="global-content-right-lists-item font_helvetica ">DGO Accessories</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url() . get_field('about_valueautos_quick_link', 'option'); ?>" class="global-content-right-lists-item font_helvetica text_capitalize">about value autos</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url() . get_field('dmc_quick_link', 'option'); ?>" class="global-content-right-lists-item font_helvetica">DMC Blog</a></li>
                    </ul>
                </div>    
                </div>
            </div>
            <!-- car loans content section ended -->
</div>

    <?php get_footer(); ?>
    <script>
    $(document).ready(function() {

        let tabChanger = $('.multistep-step-change-wrapper a');
        let tabStep = $('.multistep-step');
        let tabCol = $('.multistep-step-col')

        $(tabChanger).click((e) => submitMultistep(e))
        $('.multistep-submit-button input[type="submit"]').click((e) => submitMultistep(e))


        function submitMultistep(e) {
            let btnAttr = $(e.target).attr('data-attr');
            let btnFieldAttr = $(e.target).attr('data-field-attr')
            
            let currentTab = parseInt(btnAttr) - 1;
            let currentStep = $(tabStep)[btnFieldAttr];
            let x = $(currentStep).find('.multistep-required-field');
            var allFieldsHaveValues = true;
            
            $(x).each(function(index, data) {
                if (!$(data).val()) {
                    if ($(data).next('.error-message').length == 0) {
                        $(data).after('<div class="error-message" style="color:red;">Please fill out this field</div>');
                    }
                    allFieldsHaveValues = false;
                } else {
                    $(data).next('.error-message').remove();
                }
            });
            if (allFieldsHaveValues) {
                $(tabStep).css('display', 'none');
                let newTab = $(tabStep)[btnAttr];
                $(newTab).css('display', 'block');
                $('.multistep-step').css('display','none');
                $('.multistep-step-col').removeClass('multistep-step-col-active');
                let newStepTab = $('.multistep-step')[btnAttr]
                let newStepCol = $('.multistep-step-col')[btnAttr]
                $(newStepTab).css('display','block')
                $(newStepCol).addClass('multistep-step-col-active')
            } else {
                e.preventDefault(); // prevent the form from submitting if any required field is empty
            }
        }
    })
</script>
