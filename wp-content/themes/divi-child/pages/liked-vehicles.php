<?php /* Template Name: Liked Vehicles Template*/
get_header(); ?>
    <main class="mt-20 mb-20 px-g">
        <a class="page-back-history border d-none border-danger align-items-center justify-content-center" href="#">
            <i class="fa-solid fa-arrow-left-long text-danger font-xl"></i>
        </a>
        <h1 class="mt-20 mb-20 text-eight font-weight-normal text-capitalize font-xl font-inter p-0">Liked Vehicles</h1>
        <!-- Get the user IP and run a query to load the posts -->
        <?php 
            $table_name = accessWPDB()->prefix . 'user_liked_vehicles';
            $likedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
            $updateResult = accessWPDB()->get_row($likedQuery, ARRAY_A);
            if( !$updateResult ) {
                $likedVehicleIDs = array(0);
            }else {
                $likedVehicleIDs = !empty($updateResult['user_liked_vehicles']) ? unserialize($updateResult['user_liked_vehicles']) : array(0);
            }

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => -1,
                'post__in' => $likedVehicleIDs,
                
            );
            $likedQuery = new WP_Query($args);
            if( $likedQuery->have_posts() ) {
                echo '<div class="liked-vehicles-wrapper row">';
                while($likedQuery->have_posts()) {
                    $likedQuery->the_post();
                    echo '<div class="col-6 col-md-4 col-lg-3 mb-20">'.
                        '<div class="recent-view-card">'.
                        '<a href="'.get_the_permalink().'" class="recent-view-img-wrapper position-relative d-inline-block">';
                        if( has_post_thumbnail() ) {
                            the_post_thumbnail();
                        }
                    echo '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png" alt="vehicle liked" itemprop="image" class="position-absolute vehicle-liked-icon border-0">'.
                         '</a>'.
                        '<a href="'.get_the_permalink().'" class="font-weight-bold font-sm text-dark font-inter p-0 mb-10 text-center d-block">'.get_the_title().'</a>';
                        if( !empty(get_post_meta(get_the_ID(), 'original_price', true)) ) {
                            echo '<strong class="d-block mb-1 text-dark font-sm font-weight-normal font-inter text-center text-capitalize">our best price</strong>'.
                                '<span class="text-dark font-weight-normal font-sm font-inter d-block text-center text-capitalize">$ '.number_format(get_post_meta(get_the_ID(), 'original_price', true)).'</span>';
                        }
                    echo '</div>'.
                        '</div>';
                }
                echo '</div>';
                wp_reset_postdata();
            }else {
                echo '<h1 class="text-capitalize p-0 font-xxl">Sorry, no liked posts found explore some from our '.
                    '<a href="'.site_url().'/inventory" class="text-decoration-underline text-link text-primary font-weight-bold">Inventory</a>'.
                    '</h1>';
            }
        ?>
    </main>
<?php get_footer(); ?>