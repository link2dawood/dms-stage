<?php /* Template Name: Managers Specials Tempalte */ ?>
<?php get_header();
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>

<div id="main-content">
    <?php 
        $heroThumbnail = '';
        $heroThumbnailWidth = '';
        $heroThumbnailHeight = '';
        $heroBannerHeading = '';
        $heroBannerContent = '';
        if( get_field('managers_specials_hero_banner_image', 'options') ) {
            $heroThumbnail = wp_get_attachment_image_src(get_field('managers_specials_hero_banner_image', 'options'),'full')[0];
            $heroThumbnailWidth = wp_get_attachment_image_src(get_field('managers_specials_hero_banner_image', 'options'),'full')[1];
            $heroThumbnailHeight = wp_get_attachment_image_src(get_field('managers_specials_hero_banner_image', 'options'),'full')[2];
        }
    
        if( get_field('managers_specials_hero_banner_title', 'options') ) {
            $heroBannerHeading = get_field('managers_specials_hero_banner_title', 'options');
        }
        if( get_field('managers_specials_hero_banner_content', 'options') ) {
            $heroBannerContent = get_field('managers_specials_hero_banner_content', 'options');
        }
        echo divi_child_page_banner($heroThumbnail, null, $heroThumbnailWidth, $heroThumbnailHeight, $heroBannerHeading, $heroBannerContent);    
    ?>
    <div class="content-page-container">
        <div class="row manager-specials-row" style="margin-left: -10px; margin-right: -10px;">
            <?php  
                $stockNumbers = get_field('managers_specials_vehicles_stock_number', 'options');
                if( $stockNumbers && !empty($stockNumbers) ) {
                    $stockNumbers = explode(',', $stockNumbers);
                    $stockNumbers = array_map('trim', $stockNumbers);
                    $args = array(
                        'post_type' => 'listings',
                        'posts_per_page' => -1,
                        'order' => 'ASC',
                        'meta_query' => array(
                            array(
                                'key' => 'stock-number',
                                'value' => $stockNumbers,
                            ),
                        ),
                    );
                    $specialsQuery = new WP_Query( $args );
                    if( $specialsQuery->have_posts() ) {
                        while ($specialsQuery->have_posts()) {
                            $specialsQuery->the_post();
                            $attachment_id = get_post_meta(get_the_ID(), 'gallery_images', true)[0];
                            $specialImage = wp_get_attachment_url($attachment_id) ? wp_get_attachment_url($attachment_id) : 'http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg';
                            $attachment_meta = wp_get_attachment_metadata($attachment_id);
                            $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                            $image_width = $attachment_meta['width'];
                            $image_height = $attachment_meta['height'];
                            $priceMeta = get_post_meta(get_the_ID(), 'original_price', true);
                            $vehiclePrice = ($priceMeta && $priceMeta !== 'None') ? '$' . number_format((int)$priceMeta) : '<a href="tel:'. get_field('quick_call_phone_number', 'options') .'" class="quick-call-link"><i class="fa fa-phone"></i></a>';

                            $special = '<div class="col-12 col-sm-6 col-md-6 col-xl-3 px-10 mb-20">' .
                                '<div class="manager-special-card position-relative h-100">'.
                                '<a href="' . get_the_permalink() . '" class="d-inline-block position-relative manager-special-thumbnail w-100">' .
                                '<img src="' . $specialImage . '" alt="' . $alt_text . '" width="' . $image_width . '" height="' . $image_height . '" title="' . $alt_text . '" loading="lazy" class="img-fluid w-100 h-100 object_fit_cover" itemprop="image" />' .
                                '<div class="position-absolute">' .
                                '<p class="color_black font-lg font-weight-bold font-segoe">Manager Specials</p>' .
                                '</div>' .
                                '</a>' .
                                '<a class="d-inline-block mt-3" href="' . get_the_permalink() . '">' .
                                '<h2 class="font-weight-bold text-grey-3 font-xxl p-0">' . get_the_title() . '</h2>' .
                                '</a>';
                                if (str_word_count(get_the_content()) > 20) {
                                    $content = explode(' ', get_the_content());
                                    $contentTruncate = array_slice($content, 0, 20);
                                    $truncateContent = implode(' ', $contentTruncate);
                                    $strippedContent = preg_replace('/<[^>]+>/', '', $truncateContent);
                                    $special .= '<p class="text-grey-3 mb-20 font-md">' . $strippedContent . '</p>';
                                } else {
                                    $strippedContent = preg_replace('/<[^>]+>/', '', get_the_content());
                                    $special .= '<p class="text-grey-3 mb-20 font-md">' . $strippedContent . '</p>';
                                }                                
                            $special .= '<div class="d-flex align-items-center justify-content-between">'.
                                        '<strong class="font-weight-bold text-grey-3 font-xl">Our Best Price</strong>'.
                                        '<strong class="font-weight-bold text-grey-3 font-xl">'. $vehiclePrice .'</strong>'.
                                        '</div>'.
                                        '<a class="btn manager-special-view-vehicle font-weight-bold font-20 p-3 rounded-10 text-white text-capitalize position-absolute" href="'.get_the_permalink().'">View Details</a>'.
                                        '</div></div>';
                            echo $special;
                        }                        
                        wp_reset_postdata();
                    }else{
                        echo 'sorry no posts found';
                    }
                }
            
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>



