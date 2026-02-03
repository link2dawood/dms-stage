<?php /* Template Name: Trade In Car Tempalte */ ?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
        <!-- top header banner -->
        <div class="global-content-banner-row m_0 has-image-banner top-header-banner">
                <div class="global-content-banner-column trade-in-car-banner-column image_column">
                    <div class="global-content-banner-module top-header-banner__inner position_relative">
                        <h1 class="p_0 global-content-banner-heading color_white font_helvetica font_bold text_uppercase">Value your trade</h1>
                        <div class="global-content-banner-para-wrapper">
                        <p class="p_0 global-content-banner-para color_white font_helvetica font_bold global-content-first-para">Are you interested in upgrading from your current vehicle to something new? At Durango Value Autos, we make it easy with our no-hassle trade-in process. We buy well-maintained cars, trucks, and SUVs from all the major brands. Use our calculator here to see what your vehicle might be worth on the market today.</p>
                        </div>
                    </div>
                </div>
        	</div>
            <!-- top header banner ended -->
            <!-- trade in car content section started -->
            <div class="global-content-row trade-in-car-row">
                <div class="global-content-column-first">
                    <h3 class="font_segoe global-content-primary-heading  p_0 text_uppercase text_center font_bold m_0">we want your vehicle</h3>
                    <p class="global-content-primary-para trade-in-car-para-1 p_0 font_segoe trade-in-car-para">Durango Motor Company wants to purchase your vehicle and we are willing to pay up to $2,000 over your vehicles KBB Value.  Receive the same price for your vehicle whether you purchase a vehicle from us or not!</p>
                    <p class="global-content-primary-para trade-in-car-para-2 p_0 font_segoe trade-in-car-para">Price offering depends on the make and model, mileage under 15,000 per year, and the condition of the vehicle after inspection.</p>
                    <p class="global-content-primary-para trade-in-car-para-3 p_0 font_segoe trade-in-car-para">Call for more information: <a href="tel:<?php echo get_field('quick_call_phone_number', 'options'); ?>">+1 (855) 894-1386 </a></p>
                  
                    <div class="indemand-vehicles">
                        <h3 class="p_0 text_uppercase indemand-primary-heading text_center font_seoge font_bold">TOP IN-DEMAND VEHICLES</h3>
                            <div class="indemand-vehicle">
                            <div class="indemand-vehicles-img-wrapper has-image-banner">
                                <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/indemand-ford.webp" alt="ford vehicle" class="image_column w_100 object_fit_cover">
                            </div>
                            <div class="indemand-vehicles-info d_grid indemand-ford-info">
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">ford f-150</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">5.0 or 3.5</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">ford f-250</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">6.7</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">ford f-350</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">6.7</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="indemand-vehicle">
                            <div class="indemand-vehicles-img-wrapper has-image-banner">
                                <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/indemand-toyota.webp" alt="toyota vehicle" class="image_column w_100 object_fit_cover">
                            </div>
                            <div class="indemand-vehicles-info d_grid indemand-toyota-info">
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Toyota 4Runner</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Toyota Tacoma</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Toyota Rav4</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Toyota Tundra</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">4x4</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="indemand-vehicle">
                            <div class="indemand-vehicles-img-wrapper has-image-banner">
                                <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/indemand-kia.webp" alt="kia vehicle" class="image_column w_100 object_fit_cover">
                            </div>
                            <div class="indemand-vehicles-info d_grid indemand-kia-info">
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Kia Sorento</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">AWD</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Kia sportage</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                        <span class="font_segoe">AWD</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Kia Telluride</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2020-2023</span>
                                        <span class="font_segoe">AWD</span>
                                    </div>
                                </div>
                                <div class="indemand-column">
                                    <h3 class="indemand-heading text_center text_capitalize font_segoe p_0">Kia Optima</h3>
                                    <div class="indemand-overview d_flex d_flex__column d_flex__align-center">
                                        <span class="font_segoe">2016-2023</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        </div>
                </div>
                <div class="global-content-columns-second">
                    <div class="trade-in-car-iframe global-kbb-iframe">
                        <iframe src="//www.kbb.com/instant-cash-offer/W/70317903/43A6F9B8-DB6C-48C0-A360-F658B2176E3E/" frameborder="0" class="w-100"></iframe>
                    </div>
                </div>
            </div>
            <!-- trade in car content section ended -->
</div>

    <?php

get_footer();

?>

