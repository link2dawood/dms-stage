<?php
function incentive_specials_slider_shortcode() {
    ob_start();
    $args = array( 'post_type' => 'listings', 'posts_per_page' => 12 );
    $the_query = new WP_Query( $args ); 

    if ( $the_query->have_posts() ) : 
        ?>
        <div class="incentive-specials-slider-wrapper">
        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <div class="incentive-specials-slide">
                <div class="incentive-specials-slide-img-wrapper">
                    <?php 
                    if( has_post_thumbnail() ) {
                        echo '<a href="'. get_the_permalink() .'">';
                        the_post_thumbnail();
                        echo '</a>';
                    } else {
                        echo '<img src="http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg" alt="coming soon">';
                    } 
                    ?>
                </div>
                <div class="incentive-special-slide-content">
                    <a class="incentive-special-slide-title" href=<?php the_permalink() ?>><h3 ><?php the_title(); ?></h3></a>
                    <div class="incentive-slide-meta">
                        <div class="incentive-slide-apr incentive-slide-meta-content">
                            <strong>2.49%</strong>
                            <span>apr</span>
                        </div>
                        <div class="incentive-slide-mon incentive-slide-meta-content">
                            <strong>60</strong>
                            <span>months</span>
                        </div>
                        <div class="incentive-slide-due incentive-slide-meta-content">
                            <strong>
                            <?php
                            $meta_array = get_post_meta(get_the_ID() );
                            $xyz = $meta_array['listing_options'];
                            $sd = serialize($xyz[0]);
                            $ud = unserialize(unserialize(unserialize($sd)));
                            echo '$' . $ud['price']['value'];
                            ?>
                            </strong>
                            <span>Due at Signin</span>
                        </div>
                    </div>
                </div>
                <div class="incentive-slide-footer">
                    <div class="incentive-slide-cta incentive-slide-view-details">
                        <a class="incentive-special-slide-title" href=<?php the_permalink() ?>><h3 >view details</h3></a>
                    </div>
                    <div class="incentive-special-slide-cta incentive-slide-expiry">
                        <strong>exp. 05/31/22</strong>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_postdata(); ?>
        </div>
    <?php else:  ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif;

    return ob_get_clean();
}
add_shortcode( 'incentivesslider', 'incentive_specials_slider_shortcode' );
