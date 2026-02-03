<?php /* Template Name: contact us Tempalte */ ?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
        <!-- top header banner -->
        <div class="global-content-banner-row m_0 has-image-banner top-header-banner">
                <div class="global-content-banner-column contact-us-banner-column image_column">
                    <div class="global-content-banner-module top-header-banner__inner position_relative">
                        <h1 class="p_0 global-content-banner-heading color_white font_helvetica font_bold text_uppercase">contact us</h1>
                        <div class="global-content-banner-para-wrapper">
                        <p class="p_0 global-content-banner-para color_white font_helvetica font_bold global-content-first-para">If you need help with any aspect of the buying process, please don't hesitate to ask us. Our customer service representatives will be happy to assist you in any way. Whether through email, phone or in person, we're here to help you get the customer service you deserve.</p>
                        </div>
                    </div>
                </div>
        	</div>
            <!-- top header banner ended -->
            <!-- contact us content section started -->
            <div class="global-content-row contact-us-row">
                <div class="global-content-column-first">
                    <div class="inline-40">
                    <h3 class="font_segoe global-content-primary-heading  p_0 font_bold">
                    WE WOULD LOVE TO HEAR FROM YOU
                    </h3>
                    <p class="global-content-primary-para">
                    If you have a question, then we would like to help. Reach out to us today with any of the resources on this page or use the map here to see exactly where our dealership is located in Durango Value Autos. We look forward to hearing from you very soon.
                    </p>
                    </div>
                    <div class="contact d_grid contact__row">
                        <div class="contact__col contact__col-left">
                            <!-- contact info will be here -->
                            <h3 class="font_sans font_bold p_0">QUICK CONTACT</h3>
                        `   <?php echo do_shortcode('[contact_us_form]');  ?>
                            <div class="__module-with-content">
                            <h4 class="text_capitalize font_bold p_0" style="padding-top:30px;">durango value autos</h4>
                            <p class="font_sans contact__location global-content-primary-para">1240 Escalante Dr. Durango, CO 81303</p>
                            <a href="https://www.google.com/maps/dir//Durango+Value+Autos,+1240+Escalante+Dr,+Durango,+CO+81303,+United+States/@37.2271933,-107.864276,17z" target="_blank" class="text_capitalize contact__get-directions font_sans">get directions</a>
                            </div>

                            <div class="contact__hours d_flex font_sans global-content-primary-para">
                                <p>Sales <a href="tel:8558941386">(855) 894-1386</a></p> <span class="seperator">|</span> <div class="contact__hours-trigger">Hours</div>
                            </div>

                        </div>
                        <div class="contact__col contact__col-right">
                            <!-- map will go here -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3176.8614623637686!2d-107.86379198530084!3d37.22726355124375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x873c0332609fc183%3A0x2c671b0ad9d35215!2s1240%20Escalante%20Dr%2C%20Durango%2C%20CO%2081303%2C%20USA!5e0!3m2!1sen!2s!4v1668456739985!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="h_100"></iframe>
                        </div>
                    </div>
                    
                </div>
                <div class="global-content-columns-second">
                    <div class="contact-us-block global-side-block">
                    <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold text_capitalize global-content-right-primary-heading d_block">Quick Links</h3>
                    <ul class="global-content-right-list-wrapper pl_0">
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/inventory" class="global-content-right-lists-item font_helvetica text_capitalize">inventory</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co/" class="global-content-right-lists-item font_helvetica text_capitalize">schedule service</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/accessories" class="global-content-right-lists-item font_helvetica ">DGO Accessories</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/about-us" class="global-content-right-lists-item font_helvetica text_capitalize">about value autos</a></li>
                    <li class="global-content-right-list"><a href="<?php echo site_url(); ?>/blog" class="global-content-right-lists-item font_helvetica">DMC Blog</a></li>
                    </ul>
                    </div>
                   
                    
                    
                </div>
            </div>
            <!-- contact us content section ended -->
</div>

    <?php

get_footer();

?>

