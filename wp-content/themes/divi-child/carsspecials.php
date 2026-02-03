<?php  /* Template Name: Cars Specials */

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>
<div id="main-content" class="manager-special-tempalte cars-special-template">

    <?php if ( ! $is_page_builder_used ) : ?>

    <div class="container">
        <div id="content-area" class="clearfix">
            <div id="left-area">

                <?php endif; ?>

                <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <?php if ( ! $is_page_builder_used ) : ?>

                    <h1 class="entry-title main_title"><?php the_title(); ?></h1>
                    <?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$alttext = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
					$thumbnail = get_thumbnail( $width, $height, $classtext, $alttext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $alttext, $width, $height );
				?>

                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>

                        <!-- custom cars specials tempalte starts -->
                        <div class="custom-cars-special-template">
                            <div class="ccs-ms-section">
                                <div class="ccs-ms-header ccs-default-header">
                                    <h2 class="ccs-header-title">
                                        Manager's specials
                                    </h2>
                                    <a class="ccs-ms-link ccs-default-link"
                                        href="https://wordpress-905721-3396462.cloudwaysapps.com/managers-specials">
                                        View All
                                    </a>
                                </div>
                                <div class="ccs-ms-carousel">

                                    <!-- fetch posts from backend -->
                                    <?php 
                                        $args = array( 'post_type' => 'listings', 'posts_per_page' => 10 );
                                        $the_query = new WP_Query( $args ); 
                                        ?>
                                    <?php if ( $the_query->have_posts() ) : ?>
                                    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>


                                    <div class="ccs-special-card">
                                        <div class="ccs-image-wrapper" style="height: 300px">
                                            <a href=<?php the_permalink() ?>> <img src=<?php the_post_thumbnail() ?>
                                                    </a>
                                        </div>
                                        <div class="ccs-card-info">
                                            <h2 class="ccs-card-title">
                                                <a href=<?php the_permalink() ?>>
                                                    <?php  
                                       the_title();
                                        ?>
                                                </a>
                                            </h2>
                                            <strong class="ccs-ms-price">
                                                <?php
                                          $meta_array = get_post_meta(get_the_ID() );
                                          $xyz = $meta_array['listing_options'];
                                          $sd = serialize($xyz[0]);
                                          $ud = unserialize(unserialize(unserialize($sd)));
                                          print_r('$' . $ud['price']['value']);
                                          ?>
                                            </strong>
                                            <div class="ccs-card-view-listing"><a href=<?php the_permalink() ?>> view
                                                    details </a></div>

                                        </div>
                                    </div>
                                    <?php endwhile;
                                        wp_reset_postdata(); ?>
                                    <?php else:  ?>
                                    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
                                    <?php endif; ?>




                                </div>


                            </div>


                        </div>

                        <!-- custom cars special template ends -->
                        <!-- brand cars tempalte staretd -->

                        <div class="custom-cars-special-template brand-incentives-slider-wrapper"
                            style="padding-top: 2.5em ;">
                            <div class="ccs-ms-section">
                                <div class="ccs-ms-header ccs-default-header">
                                    <h2 class="ccs-header-title">
                                        Brand Incentives
                                    </h2>
                                    <a class="ccs-ms-link ccs-default-link">
                                        View All
                                    </a>
                                </div>
                                <div class="ccs-ms-carousel">

                                    <!-- fetch posts from backend -->
                                    <?php 
                                        $args = array( 'post_type' => 'listings', 'posts_per_page' => 10, 'order' => 'ASC' );
                                        $the_query = new WP_Query( $args ); 
                                        ?>
                                    <?php if ( $the_query->have_posts() ) : ?>
                                    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>


                                    <div class="ccs-special-card">
                                        <div class="ccs-image-wrapper" style="height: 300px">
                                            <a href=<?php the_permalink() ?>> <img src=<?php the_post_thumbnail() ?>
                                                    </a>
                                        </div>
                                        <div class="ccs-card-info">
                                            <h2 class="ccs-card-title">
                                                <a href=<?php the_permalink() ?>>
                                                    <?php  
                                       the_title();
                                        ?>
                                                </a>
                                            </h2>
                                            <div class="ccs-ms-meta-info">
                                                <div class="ccs-ms-meta-apr">
                                                    <strong>
                                                        2.49%
                                                    </strong>
                                                    <span class="ccs-ms-meta-apr-text default-meta-text">
                                                        APR
                                                    </span>
                                                </div>
                                                <div class="ccs-ms-meta-mon">
                                                    <strong>60</strong>
                                                    <span class="ccs-ms-meta-mon-text default-meta-text">
                                                        Mon ths
                                                    </span>
                                                </div>
                                                <div class="ccs-ms-meta-price">
                                                    <strong>
                                                        <?php
                                          $meta_array = get_post_meta(get_the_ID() );
                                          $xyz = $meta_array['listing_options'];
                                          $sd = serialize($xyz[0]);
                                          $ud = unserialize(unserialize(unserialize($sd)));
                                          print_r('$' . $ud['price']['value']);
                                          ?>
                                                    </strong>
                                                    <span class="ccs-ms-meta-price-text default-meta-text">
                                                        due at signing
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="ccs-ms-card-footer">
                                                <div class="ccs-card-view-listing"><a href=<?php the_permalink() ?>>
                                                        view
                                                        details </a></div>
                                                <div class="ccs-ms-card-expiry ccs-card-view-listing">
                                                    <a>Exp. 05/31/22</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php endwhile;
                                        wp_reset_postdata(); ?>
                                    <?php else:  ?>
                                    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
                                    <?php endif; ?>




                                </div>


                            </div>


                        </div>
                        <!-- brand cars templat eneded -->
                        <!-- coupons custom template started -->
                        <div class="custom-cars-special-template" style="padding-top: 2.5em ; padding-bottom: 1.563em ;">
                            <div class="ccs-ms-section">
                                <div class="ccs-ms-header ccs-default-header">
                                    <h2 class="ccs-header-title">
                                        Service, Parts & Accessories Discount
                                    </h2>
                                    <a class="ccs-ms-link ccs-default-link">
                                        View All
                                    </a>
                                </div>
                                <div class="ccs-ms-carousel">
                                    <!-- cars special coupons template -->
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-coupon-card">
                                        <div class="ccc-top">
                                            <div class="ccc-header">
                                                <img src="https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/logo-white.png"
                                                    alt="coupons card header logo">
                                                <div class="ccc-cta">
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-download"></i>
                                                    </span>
                                                    <span class="ccc-cta-icon">
                                                        <i class="fa-solid fa-share"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ccc-content">
                                                <h2 class="ccc-off">
                                                    25% off
                                                </h2>
                                                <h2 class="ccc-service">
                                                    service Alignment
                                                </h2>
                                                <strong class="ccc-benefits">
                                                    lifted vehicle additional chanrge
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="ccc-footer">
                                            <div class="ccc-view-disclaimer">
                                                <p class="ccc-disclaimer-text">
                                                    view disclaimer
                                                </p>
                                                <div class="ccc-expiry-date">
                                                    <div class="ccc-expiry-text">
                                                        EXP, MM/DD/YY
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- coupons custom template ended -->


            </div>

            <?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

            </article>

            <?php endwhile; ?>

            <?php if ( ! $is_page_builder_used ) : ?>

        </div>

        <?php get_sidebar(); ?>
    </div>
</div>

<?php endif; ?>

</div>

<?php

get_footer();

 
?>
<script>
let x = $(".ccs-ms-carousel")
$(x).slick({
    infinite: true,
    slidesToShow: 3,
    autoplay: false,
    autoplaySpeed: 2000,
    slidesToScroll: 3,
    prevArrow: "<button type='button' class='slick-prev pull-left'><img src='https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/chevron-icon-png-15-removebg-preview.png' /></button>",
    nextArrow: "<button type='button' class='slick-next pull-right'><img src='https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/07/chevron-icon-png-15-removebg-preview.png' /></button>",
    responsive: [

        {
            breakpoint: 990,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
        {
            breakpoint: 450,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
    ]
})
</script>