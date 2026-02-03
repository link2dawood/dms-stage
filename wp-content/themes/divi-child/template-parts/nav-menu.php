<?php
function pre_owmed_menu_dropdown() {
    ob_start();
    // Define the condition to filter by
    $condition_meta_key = 'condition';
    $condition_value = 'U';

    // Fetch all listings with the specified condition using WP_Query
    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => $condition_meta_key,
                'value' => $condition_value,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);

    // Prepare to hold the types and makes
    $types = [];
    $makes = [];

    // Loop through the posts and collect meta values
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get vehicle type and make
            $type = get_post_meta(get_the_ID(), 'type-of-vehicle', true);
            $make = get_post_meta(get_the_ID(), 'make', true);

            // Collect unique values for types
            if ($type) {
                $types[$type] = isset($types[$type]) ? $types[$type] + 1 : 1;
            }

            // Collect unique values for makes
            if ($make) {
                $makes[$make] = isset($makes[$make]) ? $makes[$make] + 1 : 1;
            }
        }
        wp_reset_postdata();
    }

    // Calculate total counts for types and makes
    $total_types_count = array_sum($types);
    $total_makes_count = array_sum($makes);

    echo '<ul id="pre-owned-menu-container" class="pre-owned-menu-container container my-4 sub-menu d-none">';
    echo '<div class="row">';

    // Column 1: Vehicle Types
    echo '<div class="col-md-4 mb-4">';
    echo '<div class="list-group">';

    // "View All" item with search icon for types
    echo '<a href="' . site_url('/used-vehicles-durango-colorado') . '"><li class="pre-owned-list-group-item">';
    echo '<div class="pre-owned-menu-image">';
    echo '<i class="fas fa-search"></i>'; // Search icon added here
    echo '</div>';
    echo '<div class="pre-owned-menu-item-name">';
    echo '<span class="font-weight-bold pre-owned-menu-item">View All</span>';
    echo '<span class="pre-owned-menu-item pre-owned-menu-item-count">[' . esc_html($total_types_count) . ']</span>';
    echo '</div>';
    echo '</li></a>';

    foreach ($types as $type => $count) {
        // Encode the type for the URL
        $type_slug = rawurlencode(strtolower($type)); // Use rawurlencode to properly encode spaces as %20
        $type_permalink = site_url('/used-vehicles-durango-colorado/?type-of-vehicle%5B%5D=' . $type_slug);

        echo '<a href="' . esc_url($type_permalink) . '" class="text-decoration-none">';
        echo '<li class="pre-owned-list-group-item">';
        echo '<div class="pre-owned-menu-image">';
        echo '<i class="fas fa-car"></i>'; // Generic icon for vehicle type
        echo '</div>';
        echo '<div class="pre-owned-menu-item-name">';
        echo '<span class="pre-owned-menu-item">' . esc_html($type) . '</span>';
        echo '<span class="pre-owned-menu-item pre-owned-menu-item-count">[' . esc_html($count) . ']</span>';
        echo '</div>';
        echo '</li></a>';
    }
    echo '</div>';
    echo '</div>';

    // Column 2: Vehicle Makes
    echo '<div class="col-md-4 mb-4">';
    echo '<div class="list-group">';

    // "View All" item with search icon for makes
    echo '<a href="' . site_url('/used-vehicles-durango-colorado') . '">
    <li class="pre-owned-list-group-item">';
    echo '<div class="pre-owned-menu-image">';
    echo '<i class="fas fa-search"></i>';
    echo '</div>';
    echo '<div class="pre-owned-menu-item-name">';
    echo '<span class="font-weight-bold pre-owned-menu-item">View All</span>';
    echo '<span class="pre-owned-menu-item pre-owned-menu-item-count">[' . esc_html($total_makes_count) . ']</span>';
    echo '</div>';
    echo '</li></a>';

    foreach ($makes as $make => $count) {
        // Encode the make for the URL
        $make_slug = rawurlencode(strtolower($make)); // Use rawurlencode to properly encode spaces as %20
        $make_permalink = site_url('/used-vehicles-durango-colorado/?make%5B%5D=' . $make_slug);
        $image_path = get_stylesheet_directory() . '/assets/images/' . strtolower($make) . '.jpg';
        $image_url = get_stylesheet_directory_uri() . '/assets/images/' . strtolower($make) . '.jpg';
        
        echo '<a href="' . esc_url($make_permalink) . '" class="text-decoration-none">';
        echo '<li class="pre-owned-list-group-item">';
        echo '<div class="pre-owned-menu-image">';
        
        if (file_exists($image_path)) {
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($make) . '" />';
        } else {
            echo '<i class="fas fa-search"></i>'; // Show search icon if no image found
        }
        
        echo '</div>';
        echo '<div class="pre-owned-menu-item-name">';
        echo '<span class="pre-owned-menu-item">' . esc_html($make) . '</span>';
        echo '<span class="pre-owned-menu-item pre-owned-menu-item-count">[' . esc_html($count) . ']</span>';
        echo '</div>';
        echo '</li></a>';
    }

    echo '</div>';
    echo '</div>';

    echo '<div class="col-md-4 mb-4">';
    echo '<div class="list-group pre-owned-shopping-tool">';
    echo '<li class="pre-owned-menu-shopping-tool-heading">Shopping Tools</li>';
    echo '<a href="' . esc_url(site_url('/used-vehicles-durango-colorado/?make%5B%5D=toyota&certified%5B%5D=yes')) . '" class="text-decoration-none"><li class="pre-owned-menu-shopping-tool">Certified Pre-Owned Toyota</li></a>';
    echo '<a href="' . esc_url(site_url('/used-vehicles-durango-colorado/?make%5B%5D=kia&certified%5B%5D=yes')) . '" class="text-decoration-none"><li class="pre-owned-menu-shopping-tool">Certified Pre-Owned Kia</li></a>';
    echo '<a href="' . esc_url(site_url('/used-vehicles-durango-colorado/?make%5B%5D=ford&certified%5B%5D=yes')) . '" class="text-decoration-none"><li class="pre-owned-menu-shopping-tool">Certified Pre-Owned Ford</li></a>';
    echo '<li class="pre-owned-menu-shopping-tool">We Will Buy Your Vehicle!</li>';
    echo '</div>';
    echo '</div>';

    echo '</div>';
    echo '</ul>';

    $output = ob_get_clean();
    echo $output;
?>

	<script>
		(function($) {
			$('header li.menu-item-944').addClass('menu-item-has-children').append($('#pre-owned-menu-container'))
			$('header li.menu-item-944 #pre-owned-menu-container').removeClass('d-none')
		})(jQuery)

(function($) {
    $(document).ready(function() {
        if ($(window).width() <= 768) { // Mobile only
            // Append the dropdown container inside menu-item-944
            $('li.menu-item-944').append($('#pre-owned-menu-container'));

            // Toggle dropdown visibility on click
            $('li.menu-item-944 > a').on('click', function(e) {
                e.preventDefault(); // Prevent link default action
                $('#pre-owned-menu-container').slideToggle(); // Toggle dropdown display
            });
        }
    });
})(jQuery);
</script>
<style>
		.menu-item-944 {
			position: static !important;
		}
		#pre-owned-menu-container {
			position: absolute;
			bottom: 0;
			left: 5%;
			width: 95% !important;
			height: 60vh;
			overflow-y: scroll;
			box-shadow: 0 2px 4px 0 rgba(0, 0, 0, .5) !important;
		}
		li.pre-owned-list-group-item {
			display: flex;
    		width: 100%;
		}
		

	</style>
	<?php
}
add_action('wp_footer', 'pre_owmed_menu_dropdown');
