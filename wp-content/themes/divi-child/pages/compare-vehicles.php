<?php /* Template Name: Compare Vehicles */
get_header(); ?>
<main class="mt-20 mb-20 px-g">
<a class="page-back-history border border-danger align-items-center justify-content-center d-none" href="#">
    <i class="fa-solid fa-arrow-left-long text-danger font-xl"></i>
</a>
<h1 class="mt-20 mb-20 text-eight font-weight-normal text-capitalize font-xl font-inter p-0">Compare</h1>
<!-- Get the user IP and run a query to load the posts -->
<?php
$table_name = accessWPDB()->prefix . 'user_compared_vehicles';
$compareQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
$updateResult = accessWPDB()->get_row($compareQuery, ARRAY_A);

if( !$updateResult ) {
// User data doesnot exist
$compareVehiclesIDs = array(0);
}else {
$compareVehiclesIDs = !empty($updateResult['user_compared_vehicles']) ? unserialize($updateResult['user_compared_vehicles']) : array(0);
}

$args = array(
'post_type' => 'listings',
'posts_per_page' => -1,
'post__in' => $compareVehiclesIDs,
);
$comparedQuery = new WP_Query($args);
$foundVehicles = $comparedQuery->post_count;
$cardLayout = '<div class="compare-vehicles-wrapper">';

if( $comparedQuery->have_posts() ) {
    while($comparedQuery->have_posts()) {
        $comparedQuery->the_post();
        $cardLayout .= '<div class="d-flex mb-30">'.
        '<div class="accordion-card-thumbnail mr-30">';
        if( has_post_thumbnail() ) {
            $cardLayout .= get_the_post_thumbnail();
        }
        $cardLayout .= '</div>'.
                    '<div class="compare-content position-relative">'.
                    '<h4 class="text-primary font-20 mb-10 p-0 font-inter">'.get_the_title().'</h4>'.
                    '<p class="text-grey-6 font-md font-inter mb-1 p-0">'.get_post_meta(get_the_ID(), 'stock-number', true).'</p>';
                    $listingPrice = number_format(get_post_meta(get_the_ID(), 'original_price', true));
                    if( empty(trim($listingPrice)) || $listingPrice === 'None' || !isset($listingPrice) ) {
                        $cardLayout .= '<a class="font-inter font-md text-grey-6" href="tel:'.salesPhoneNumber().'" data-toggle="tooltip" data-placement="top" title="Call For Price"><i class="fa fa-phone"></i></a>';
                    }else {
                        $cardLayout .= '<p class="text-grey-6 font-md font-inter mb-0 p-0 font-weight-bold">$ '. number_format(get_post_meta(get_the_ID(), 'original_price', true)) .'</p>';
                    }
                    $cardLayout .= '<span class="remove-vdp-compare text-primary font-sm text-link cursor-pointer font-inter font-weight-light position-absolute" style="right:0;bottom:0;" data-remove="'.get_the_ID().'">Remove</span>'.
                    '</div>'.
                    '</div>';
    }
    if( $foundVehicles < 3 ) {
        for( $i = 0; $i < 3 - $foundVehicles; $i++ ) {
            $cardLayout .= '<div class="d-flex mb-30">'.
            '<div class="accordion-card-thumbnail mr-30 position-relative">'.
            '<a href="'.site_url().'/inventory" target="_blank" class="d-inline-block w-100">'.
            '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/dummy-compare.png"
            alt="dummy compare" itemprop="image" width="147" height="113" loading="lazy" class="img-fluid w-100" />'.
            '<i class="fa fa-plus position-absolute compare-another-vehicle-icon text-white"></i>'.
            '</a>'.
            '</div>'.
            '<div class="d-flex align-items-center justify-content-start">'.
            '<a class="font-inter text-primary font-lg font-weight-light p-0 m-0" href="'.site_url().'/inventory" target="_blank">Compare Another Vehicle</a>'.
            '</div>'.
            '</div>';
        }
    }
    wp_reset_postdata();
}else {
    for( $i = 0; $i < 3; $i++ ) {
        $cardLayout .= '<div class="d-flex mb-30 '.( $i === 0 ? 'add-current-vehicle-compare' : '' ).'" data-id="'.$VDPListing.'">'.
        '<div class="accordion-card-thumbnail mr-30 position-relative">';
         $cardLayout .= '<a href="'.site_url().'/inventory" target="_blank" class="d-inline-block w-100">'.
        '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/dummy-compare.png"
        alt="dummy compare" itemprop="image" width="147" height="113" loading="lazy" class="img-fluid w-100" />'.
        '<i class="fa fa-plus position-absolute compare-another-vehicle-icon text-white"></i>'.
        '</a>';
        $cardLayout .=  '</div>'.
        '<div class="d-flex align-items-center justify-content-start">'.
        '<a class="font-inter text-primary font-lg font-weight-light p-0 m-0" '.($i !== 0 ? 'href="'.site_url().'/inventory"' : '').' '.($i !== 0 ? 'target="_blank"' : '').'>Compare Another Vehicle</a>'.
        '</div>'.
        '</div>';
    }
}
$cardLayout .= '</div>';
$cardLayout .= '<div class="compare-btn mb-5 recent-card-compare-btn '.($foundVehicles < 2 ? 'd-none' : '').'">'.
'<a class="btn btn-primary text-white w-100 d-inline-block">Compare</a></div>';
echo $cardLayout;

?>
<!-- // Recently Viewed Vehicles Grid -->
<h3 class="text-primary">Recently Viewed</h3>
<?php
    $recentTableName = accessWPDB()->prefix . 'user_recently_viewed';
    $recentQuery = accessWPDB()->prepare("SELECT * FROM $recentTableName WHERE user_ip = %s", getUserIP());
    $recentQueryResult = accessWPDB()->get_row($recentQuery, ARRAY_A);

    if( !$recentQueryResult ) {
        // Data Does not exist
        $user_recently_viewed = array(0);
    }else {
        $user_recently_viewed = !empty($recentQueryResult['recent_view_vehicles']) ? unserialize($recentQueryResult['recent_view_vehicles']) : array();
    }

    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => -1,
        'post__in' => $user_recently_viewed,
    );
    $recentViewQuery = new WP_Query($args);
    $recentCard = '';
    if($recentViewQuery->have_posts()) {
        $recentCard .= '<div class="row">';
        while($recentViewQuery->have_posts()) {
            $recentViewQuery->the_post();
            $recentCard .= '<div class="recent-card col-6 col-md-4 mb-30" data-id="'.get_the_ID().'">'.
            '<div class="position-relative mb-15 recent-card-image-wrapper">';
            if( has_post_thumbnail() ) {
                $recentCard .= get_the_post_thumbnail();
            }
            $recentCard .= '<div class="recent-card-overlay w-100 h-100 position-absolute"></div>'.
            '<i class="fa fa-plus text-white position-absolute font-30"></i>'.
            '</div>'.
            '<h3 class="text-center font-sm font-weight-bold text-dark mb-10 p-0 text-capitalize">'.get_the_title().'</h3>'.
            '<p class="text-center font-sm font-weight-normal text-dark mb-2 text-capitalize p-0">our best price</p>';
            $listingPrice = number_format(get_post_meta(get_the_ID(), 'original_price', true));
            if( empty(trim($listingPrice)) || $listingPrice === 'None' || !isset($listingPrice) ) {
                $recentCard .= '<a class="font-inter font-md text-grey-6 text-center d-block" href="tel:'.salesPhoneNumber().'"><i class="fa fa-phone"></i>Call For Price</a>';
            }else {
                $recentCard .= '<p class="m-0 text-grey-6 font-inter text-center font-lg p-0 text-center">$ '. number_format(get_post_meta(get_the_ID(), 'original_price', true)) .'</p>';
            }
            $recentCard .= '</div>';
        }
        $recentCard .= '</div>';
        wp_reset_postdata();
    }else {
        $recentCard .= 'no recent viewed vehicles? no problem explore some from our <a href="'.site_url().'/inventory">inventory</a>';
    }
    echo $recentCard;
?>
</main>
<?php get_footer(); ?>

<script>
    $(document).ready(function() {
        $(document).on('click', '.recent-card', function(e) {
            e.stopPropagation()
            $listing = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    addCurrentVehicle: true,
                    pageLoad: false,
                    VDPListing: $listing,
                    action: 'loadCompareVehicles',
                },
                success: function(response) {
                    let res = jQuery.parseJSON(response);
                    if( res.cardLayout !== '') {
                        $('.compare-vehicles-wrapper').html(res.cardLayout)
                    }
                    if( res.foundVehicles !== '' ) {
                        if( res.foundVehicles < 2 ) {
                            $('.recent-card-compare-btn').addClass('d-none')
                        }else {
                            $('.recent-card-compare-btn').removeClass('d-none')
                        }
                    }
                }
            })
        })

        $('.recent-card-compare-btn a').click(function() {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: "POST",
            data: { "action": "rr_compare_vehicles" },
            success: function (resp) {
                var obj = jQuery.parseJSON(resp);
                if (obj.html != '') {
                    jQuery("#compareModal .compare-result").html(obj.html);
                    // jQuery("#compareModal").modal("show");
                    jQuery("#compareModal").css('display', 'flex');
                    $('body').addClass('overflow-hidden')
                }
            },
            error: function () {
                alert('error in comparing');
            }
        });
    })
    $('.compare-vehicles-popup__close').click(function() {
        $('.compare-vehicles-popup').css('display', 'none')
        $('body').removeClass('overflow-hidden').removeClass('hideCompareCheckbox')
    })
})
</script>