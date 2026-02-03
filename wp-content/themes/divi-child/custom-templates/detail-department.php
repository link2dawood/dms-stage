<?php /* Template Name: Detail Department Tempalte */ ?>
<?php

get_header();
global $wpdb;
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
    <!-- top header banner -->
    <div class="global-content-banner-row m_0 has-image-banner top-header-banner">
        <div class="global-content-banner-column detail-department-banner-column image_column">
            <div class="global-content-banner-module top-header-banner__inner position_relative">
                <h1 class="p_0 global-content-banner-heading color_white font_helvetica font_bold text_uppercase"><?php echo get_field('detail_department_banner_heading', 'options'); ?></h1>
                <div class="global-content-banner-para-wrapper">
                <p class="p_0 global-content-banner-para color_white font_helvetica font_bold global-content-first-para"><?php echo get_field('detail_department_banner_para', 'options'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- top header banner ended -->
    <!-- detail department content section started -->
    <div class="global-content-row detail-department-row">
        <div class="global-content-column-first">
            <h3 class="font_segoe global-content-primary-heading  p_0 font_bold">
            At Durango Motor Company, we use only the best products on the market. Your vehicle will shine the brightest, inside and out!
            </h3>
            <!-- start from here -->
            <div class="auto-detail d_grid auto-detail-grid">
                <?php 
                    $detailDepartments = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}detail_department" );
                    foreach( $detailDepartments as $detailDepartment ) {
                        echo '<div class="auto-detail-column" data-id="'.$detailDepartment->id.'">';
                        echo '<div class="auto-detail-img-wrapper d_none d_lg_block">';
                        echo '<img src="'. site_url() . $detailDepartment->service_thumbnail . '" alt="service thumbnail" class="w-100" />';
                        echo '</div>';
                        echo '<div class="auto-detail-content position_relative">';
                        echo '<h4 class="auto-detail-title text_uppercase font_helvetica p_0 font_bold text_center">'. $detailDepartment->service_title .'</h4>';
                        echo '<div class="auto-detail-bottom">';
                        echo '<div class="auto-detail-price d_flex d_flex__justify-center d_flex__align-center">';
                        echo '<strong>$'.number_format($detailDepartment->service_price).'.<sup>'. $detailDepartment->service_price_ext .'</sup></strong>';
                        echo '</div>';
                        $description = $detailDepartment->service_description;
                        echo '<p class="auto-detail-desc">' . ((str_word_count($description) > 12) ? implode(' ', array_slice(explode(' ', $description), 0, 12)) . '...' : $description) . '</p>';
                        echo '<div class="auto-detail-cta d_flex d_flex__column">';
                        echo '<a href="javascript:void(0)" data-popup="detail-department" class="auto-detail-cta-link auto-detail-view-details popup-trigger font_bold text_uppercase text_center" data-service-counter ="'.$detailDepartment->id.'">view details</a>';
                        echo '<a href="'.site_url().'/service-and-parts/schedule-express-service-durango-co/" class="auto-detail-cta-link auto-detail-schedule text_capitalize text_center">schedule auto detail</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>
            </div>
                </div>
                <div class="global-content-columns-second">
                    <div class="detail-department-block global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">DGO Detail Center</h3>
                    <a href="tel:8558941386" class="global-content-right-list-wrapper d_inline-block global-content-right-lists-item font_helvetica text_capitalize">(855) 894-1386</a>
                    </div>
                    <div class="detail-department-block global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">Location</h3>
                    <a href="https://goo.gl/maps/eivBa92cjyMBhWrm9" target="_blank" class="global-content-right-list-wrapper d_inline-block global-content-right-lists-item font_helvetica text_capitalize">463 Turner Drive Suite 103, Durango, CO</a>
                    </div>
                    <div class="detail-department-block mb_0 global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">Hours Of Operation</h3>
                    <div class="detail-department-hours-table global-content-right-list-wrapper">
                        <div class="hour-row">
                            <b class="text_capitalize">monday</b>
                            <span>7 am - 5 pm</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">tuesday</b>
                            <span>7 am - 5 pm</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">wednesday</b>
                            <span>7 am - 5 pm</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">thursday</b>
                            <span>7 am - 5 pm</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">friday</b>
                            <span>7 am - 5 pm</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">Saturday</b>
                            <span>Closed</span>
                        </div>
                        <div class="hour-row">
                            <b class="text_capitalize">Saturday</b>
                            <span>Closed</span>
                        </div>
                    </div>
                    </div>
                    
                </div>
            </div>
            <!-- detail department content section ended -->
</div>

<!-- car service popup started -->
<div class="global_popup_wrapper position_fixed w_100 h_100 d_none d_flex__align-center" data-popup="detail-department">
    <div class="global_popup_wrapper_overlay popup-close position_absolute w_100 h_100" data-popup="detail-department"></div>
    <div class="global_popup_wrapper_content-wrapper overflow-auto rounded-0 w-100">
        <span class="popup-close global_popup_wrapper_close position-relative global_popup_wrapper_close border_circle d-flex align-items-center justify-content-center cursor-pointer ml-auto d-block mb-1" data-popup="detail-department" style="right:0; top:0;">
            <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <div class="services__popup-inner-content border-top pt-2 ">
           
        </div>
    </div>
</div>
    <?php get_footer(); ?>
<script>
    $(document).ready(function() {
    let viewDetail = $('.auto-detail-view-details');
    function loaderHTML() {
        let html = ` <div class="car-service-pre-loader d_flex d_flex__align-center d_flex__justify-center">
                <div class="car-service-pre-loader-wrapper d_flex d_flex__align-center d_flex__justify-center d_flex__column">
                    <span class="d_inline-block car-service__loader"></span>
                    <p class="text-center text_uppercase font_helvetica">Loading...</p>
                </div>
            </div>`
        return html;
    }
    $(viewDetail).click(function() {
        $('.services-title').html('loading from functions...');
        $('.services__popup-inner-content').html(loaderHTML)
        let serviceCounter = $(this).attr('data-service-counter')
        $.ajax({
            type : 'POST',
            datatype : 'html',
            url : '<?php echo admin_url('admin-ajax.php'); ?>',
            data : {
                'serviceCounter': serviceCounter ,
                'action' : 'carServicePopup'
            },
            success : function(result) {
               $('.services__popup-inner-content').html(result)
            },
            error : function(error) {
                alert('error in getting record')
            }
        })
    })
    })
</script>
