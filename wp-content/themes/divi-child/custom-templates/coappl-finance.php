<?php /* Template Name: Co-apply Finance */ 
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
         <!-- top header banner -->
         <div class="global-content-banner-row m_0 has-image-banner top-header-banner">
                <div class="global-content-banner-column about-us-banner-column image_column">
                    <div class="global-content-banner-module top-header-banner__inner position_relative">
                        <h1 class="p_0 global-content-banner-heading color_white font_helvetica font_bold text_uppercase">Co Apply Finance</h1>
                        <div class="global-content-banner-para-wrapper">
                        <p class="p_0 global-content-banner-para color_white font_helvetica font_bold global-content-first-para">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rerum tempora enim repellat sed est. Libero blanditiis optio cum voluptas? Sed sequi nulla facere debitis dignissimos accusantium non corporis! Ipsa, delectus!.</p>
                        </div>
                    </div>
                </div>
        	</div>
            <!-- top header banner ended -->
            <!-- about us content section started -->
            <div class="global-content-row about-us-car-row">
                <div class="global-content-column-first">
                     <h3 class="font_segoe global-content-primary-heading  p_0 font_bold">
                            <?php echo get_field('co_applicant_page_main_heading', 'options'); ?>
                    </h3>           
                    <p class="global-content-primary-para">
                        <?php echo get_field('co_applicant_paragraph_1', 'options'); ?>    
                    </p>
                    <div class="mt-20">
                        <a href="<?php echo site_url() . get_field('co_applicant_single_applicant_button_link', 'options'); ?>" class="btn btn-primary"><?php echo get_field('co_applicant_single_applicant_button_text', 'options'); ?></a>
                    </div>
                    <div class="multistep-step-indicator-wrapper d_grid co-applicant-multistep-form">
                        <div class="multistep-step-col multistep-step-col-active" data-attr="step-1">
                            <span class="multistep-step-counter d_inline-block">1</span>
                            <b class="color_white font_helvetica text_uppercase"><?php echo get_field('co_applicant_multistep_form_step_1_text', 'options'); ?></b>
                        </div>
                        <div class="multistep-step-col" data-attr="step-2">
                            <span class="multistep-step-counter d_inline-block">2</span>
                            <b class="color_white font_helvetica text_uppercase"><?php echo get_field('co_applicant_multistep_form_step_2_text', 'options') ?></b>
                        </div>
                        <div class="multistep-step-col" data-attr="step-3">
                            <span class="multistep-step-counter d_inline-block">3</span>
                            <b class="color_white font_helvetica text_uppercase"><?php echo get_field('co_applicant_multistep_form_step_3_text', 'options') ?></b>
                        </div>
                        <div class="multistep-step-col" data-attr="step-4">
                            <span class="multistep-step-counter d_inline-block">4</span>
                            <b class="color_white font_helvetica text_uppercase"><?php echo get_field('co_applicant_multistep_form_step_4_text', 'options') ?></b>
                        </div>
                        <div class="multistep-step-col" data-attr="step-5">
                            <span class="multistep-step-counter d_inline-block">5</span>
                            <b class="color_white font_helvetica text_uppercase"><?php echo get_field('co_applicant_multistep_form_step_5_text', 'options') ?></b>
                        </div>
                    </div>
                    <div class="multistep-form-container">
                       <?php echo do_shortcode('[co_applicant_contact_form]'); ?>
                    </div>
                    <div class="car-loans-form-notice">
                        <p class="global-content-primary-para p_0 " style="clear:both; margin-top:60px;"><?php echo get_field('co_applicant_paragraph_2', 'options'); ?></p>
                        <p class="global-content-primary-para p_0 "><?php echo get_field('co_applicant_paragraph_3', 'options'); ?></p>
                        <p class="global-content-primary-para p_0 "><?php echo get_field('co_applicant_paragraph_4', 'options'); ?></p>
                    </div>


                </div>
                <div class="global-content-columns-second">
                <div class="global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">quick links</h3>
                    <ul class="global-content-right-list-wrapper pl_0">
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/inventory" class="global-content-right-lists-item font_helvetica text_capitalize">inventory</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/schedule-express-service-durango-co" class="global-content-right-lists-item font_helvetica text_capitalize">schedule service</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/accessories" class="global-content-right-lists-item font_helvetica ">DGO Accessories</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/about-us" class="global-content-right-lists-item font_helvetica text_capitalize">about value autos</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/blog" class="global-content-right-lists-item font_helvetica">DMC Blog</a></li>
                    </ul>
                </div>    
                </div>
            </div>
            <!-- co apply finance content section ended -->
</div>

        <?php  get_footer(); ?>
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
