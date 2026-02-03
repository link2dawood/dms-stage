<?php
function custom_popup_slider_shortcode($atts) {
    ob_start();
    
    // Get attributes
    $atts = shortcode_atts(array(
        'popup_id' => ''
    ), $atts, 'popup_slider');

    if (empty($atts['popup_id'])) {
        return '';
    }

    // Get the popup list from ACF options
    $popup_list = get_field('popup_list', 'option'); 

    if ($popup_list) {
        foreach ($popup_list as $popup) {
            // Check if this popup's ID matches our shortcode attribute
            if ($popup['popup_id'] === $atts['popup_id']) {
                $slider_images = $popup['slider_images'];
                $logo_items = $popup['popup_sidebar_logos'];
                $has_logos = !empty($logo_items) && is_array($logo_items);
                
                // Enqueue Slick Slider (CSS & JS)
                wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css');
                wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js', array('jquery'), '', true);
                ?>
                
                <div class="custom-popup-wrapper <?php echo !$has_logos ? 'no-logos' : ''; ?>">
                    <!-- Slider Section -->
                    <div class="dgo-autogear-popup-slider">
                        <?php if (!empty($slider_images) && is_array($slider_images)): ?>
                            <div class="slick-slider">
                                <?php foreach ($slider_images as $image): ?>
                                    <div class="slide">
                                        <img src="<?php echo esc_url($image); ?>" alt="Slider Image">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Logos Section - Only show if there are logos -->
                    <?php if ($has_logos): ?>
                    <div class="custom-logos">
                        <div class="logo-list">
                            <?php foreach ($logo_items as $logo_item): ?>
                                <?php if (!empty($logo_item['logo']) && !empty($logo_item['logo_url'])): ?>
                                    <a href="<?php echo esc_url($logo_item['logo_url']); ?>" target="_blank">
                                        <img src="<?php echo esc_url($logo_item['logo']['url']); ?>" alt="<?php echo esc_attr($logo_item['logo']['alt']); ?>">
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <p class="and-many-more">And Many More</p>
                    </div>
                    <?php endif; ?>
                </div>
                <script>
                    jQuery(document).ready(function($) {
                        var $slider = $('.dgo-autogear-popup-slider .slick-slider');
                        if ($slider.length) {
                            $slider.slick({
                                dots: true,
                                infinite: true,
                                speed: 500,
                                fade: true,
                                cssEase: 'linear',
                                arrows: true,
                                prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-angle-left"></i></button>',
                                nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-angle-right"></i></button>'
                            });
                        }
                    });
                </script>


                <style>
                    .slick-prev, .slick-next {
                        background: white !important;
                        color: black !important;
                        border: none;
                        border-radius: 100px;
                        padding: 0 !important;
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        z-index: 999;
                        cursor: pointer !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        width: 24px !important;
                        height: 24px !important;
                    }

                    .slick-prev i, .slick-next i {
                        font-size: 17px !important;
                    }

                    .slick-arrow.slick-prev {
                        left: 30px !important;
                    }

                    .slick-arrow.slick-next {
                        right: 30px !important;
                    }
                    .custom-popup-wrapper {
                        display: flex;
                        align-items: center;
                        gap: 20px;
                        max-width: 100%;
                    }

                    .dgo-autogear-popup-slider {
                        width: 70%;
                    }

                    .dgo-autogear-popup-slider .slick-slider {
                        width: 100%;
                    }

                    .dgo-autogear-popup-slider .slick-slide {
                        display: flex !important;
                        justify-content: center;
                        align-items: center;
                    }
                    .dgo-autogear-popup-slider .slick-slide {
                        width: 100% !important;
                        flex: 0 0 100%;
                    }

                    .dgo-autogear-popup-slider .slick-slide img {
                        width: 100%;
                        height: auto;
                        object-fit: cover;
                    }

                    .custom-logos {
                        width: 30%;
                        display: flex;
                        flex-direction: column;
                        align-items: start;
                        margin-left: 5%;
                        justify-content: start;
                    }

                    .custom-logos img {
                        max-width: 150px;
                        margin-bottom: 20px;
                        margin-bottom: 20px;
                        height: auto;
                    }
                    

                    /* Styles when there are no logos */
                    .custom-popup-wrapper.no-logos .dgo-autogear-popup-slider {
                        width: 100%;
                    }
                    
                    .custom-popup-wrapper.no-logos .slick-list {
                        min-width: 96% !important;
                        margin: 0 auto !important;
                    }

                    @media (max-width: 768px) {
                        .custom-popup-wrapper {
                            flex-direction: column;
                        }
                        .dgo-autogear-popup-slider,
                        .custom-logos {
                            width: 100%;
                        }
                    }
                </style>

                <?php
                // Stop looping after finding the correct popup
                break;
            }
        }
    }

    return ob_get_clean();
}
add_shortcode('popup_slider', 'custom_popup_slider_shortcode');