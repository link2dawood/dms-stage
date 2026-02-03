<?php
/**
 * Dropdown for new inventory vehicles
 */
function dmc_inventory_dropdown() {
    // Define the condition to filter by
    $condition_meta_key = 'condition';
    $condition_value = 'N';

    // Fetch all listings with the specified condition using WP_Query
    $args = array(
        'post_type'      => 'listings',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'meta_query'     => array(
			'relation'	=> 'OR',
            array(
                'key'     => $condition_meta_key,
                'value'   => $condition_value,
                'compare' => '=',
            ),
			[
				'key' => 'condition',
				'value' => 'New',
				'compare' => '='
			]
        ),
    );

    $query = new WP_Query($args);

    // Prepare arrays to hold the makes
    $makes = [];

    // Loop through the posts and collect meta values
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get vehicle make and model
            $make  = strtolower(get_post_meta(get_the_ID(), 'make', true));
            $model = strtolower(get_post_meta(get_the_ID(), 'model', true));

            // Collect unique values for makes
            if ($make) {
                if (!isset($makes[$make])) {
                    $makes[$make] = [
                        'count'   => 0,
                        'models'  => [],
                    ];
                }

                $makes[$make]['count'] += 1;

                // Collect unique values for models under each make
                if ($model) {
                    if (!isset($makes[$make]['models'][$model])) {
                        $makes[$make]['models'][$model] = [
                            'count' => 0,
                        ];
                    }
                    $makes[$make]['models'][$model]['count'] += 1;
                }
            }
        }
        wp_reset_postdata();
    }

    // Define the order of makes
    $make_order = ['ford', 'kia', 'toyota', 'lincoln'];

    $ordered_makes = [];
    foreach ($make_order as $make) {
        if (isset($makes[$make])) {
            // Store make as the key in inventory data
            $ordered_makes[$make] = [
                'make'   => $make,
                'count'  => $makes[$make]['count'],
                'models' => $makes[$make]['models'],
            ];
        }
    }

    // Store the ordered makes in $inventory_data
    $make = $ordered_makes[$make]['make'];
?>
<!-- Begin HTML output -->
<ul class="inventory-dropdown--wrapper row sub-menu d-none" style="background:white !important;">
    <div class="inventory-dropdown--left col-12 col-lg-3">
        <!-- A custom block for view all start -->
        <a href="<?php echo esc_url(home_url('/new-vehicles-durango-colorado/')); ?>"
           class="inventory-dropdown--make d-flex align-items-center justify-content-start"
           title="View all inventory" aria-label="View all inventory">
            <i class="fa-solid fa-magnifying-glass inventory-dropdown--thumbnail mr-3"></i>
            <div class="position-relative w-100">
                <h3 class="mb-0 text-capitalize inventory-dropdown--heading p-0">
                    <?php echo esc_html('View All'); ?>
                </h3>
                <span class="inventory-dropdown--count">
                    [<?php echo esc_html(array_sum(array_column($makes, 'count'))); ?>]
                </span>
            </div>
        </a>
        <!-- A custom block for view all end -->

        <?php
        // Loop through the makes in the specified order
        foreach ($make_order as $make) {
            if (isset($makes[$make])) {
                $make_data = $makes[$make];
                ?>
                <a href="<?php echo esc_url(home_url('/new-vehicles-durango-colorado/?make%5b%5d=' . esc_attr($make))); ?>"
                   class="inventory-dropdown--make d-flex align-items-center justify-content-start"
                   title="View inventory for <?php echo esc_attr(ucfirst($make)); ?>"
                   aria-label="View inventory for <?php echo esc_attr(ucfirst($make)); ?>"
                   data-make="<?php echo esc_attr(str_replace(' ', '-', $make)); ?>">
                    <img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $make . '.jpg'); ?>"
                        alt="<?php echo esc_attr($make); ?>" width="77" height="54"
                        loading="lazy" decoding="async" class="mr-3 d-inline-block inventory-dropdown--thumbnail" />
                    <div class="position-relative w-100">
                        <h3 class="mb-0 text-capitalize inventory-dropdown--heading p-0">
                            <?php echo esc_html(ucfirst($make)); ?>
                        </h3>
                        <span class="inventory-dropdown--count">
                            [<?php echo esc_html($make_data['count']); ?>]
                        </span>
                        <i class="fa-solid fa-chevron-right position-absolute"></i>
                    </div>
                </a>
                <?php
            }
        }

        // Optionally, display makes not in the make_order array
        foreach ($makes as $make => $make_data) {
            if (!in_array($make, $make_order)) {
                ?>
                <a href="<?php echo esc_url(home_url('/new-vehicles-durango-colorado/?make%5b%5d=' . esc_attr($make))); ?>"
                   class="inventory-dropdown--make d-flex align-items-center justify-content-start"
                   title="View inventory for <?php echo esc_attr(ucfirst($make)); ?>"
                   aria-label="View inventory for <?php echo esc_attr(ucfirst($make)); ?>"
                   data-make="<?php echo esc_attr(str_replace(' ', '-', $make)); ?>">
                    <img
                        src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $make . '.jpg'); ?>"
                        alt="<?php echo esc_attr($make); ?>" width="77" height="54"
                        loading="lazy" decoding="async" class="mr-3 d-inline-block inventory-dropdown--thumbnail" />
                    <div class="position-relative w-100">
                        <h3 class="mb-0 text-capitalize inventory-dropdown--heading p-0">
                            <?php echo esc_html(ucfirst($make)); ?>
                        </h3>
                        <span class="inventory-dropdown--count">
                            [<?php echo esc_html($make_data['count']); ?>]
                        </span>
                        <i class="fa-solid fa-chevron-right position-absolute"></i>
                    </div>
                </a>
                <?php
            }
        }
        ?>
    </div>
    <div class="inventory-dropdown--right col-12 col-lg-9 position-relative overflow-hidden">
        <!-- Vehicle Models Wrapper -->
        <?php
        if (!empty($makes)) {
            foreach ($make_order as $make) {
                if (isset($makes[$make]) && !empty($makes[$make]['models'])) {
                    ?>
                    <div class="row inventory-dropdown--right-wrapper w-100 position-absolute"
                         data-make="<?php echo esc_attr(str_replace(' ', '-', $make)); ?>">
                        <?php
                        foreach ($makes[$make]['models'] as $model => $model_data) {
                            $model_image_src = get_stylesheet_directory_uri() . '/assets/images/' . str_replace(' ', '-', $model) . '.png';
                            $model_image_path = get_stylesheet_directory() . '/assets/images/' . str_replace(' ', '-', $model) . '.png';
                            $model_image      = '<i class="fa-solid fa-magnifying-glass"></i>';

                            if (file_exists($model_image_path)) {
                                $model_image = '<img src="' . esc_url($model_image_src) . '" alt="' . esc_attr($model) . '" title="' . esc_attr($model) . '" width="77" height="54" loading="lazy" decoding="async" class="me-3 d-inline-block inventory-dropdown--thumbnail" />';
                            }
                            ?>
                            <div class="col-4">
                                <a href="<?php echo esc_url(home_url('/new-vehicles-durango-colorado/?model%5b%5d=' . urlencode($model))); ?>"
                                   class="inventory-dropdown--model d-flex align-items-center justify-content-start"
                                   title="<?php echo esc_attr($model); ?>"
                                   aria-label="<?php echo esc_attr($model); ?>">
                                    <div class="inventory-dropdown--thumbnail-wrap">
                                        <?php echo $model_image; ?>
                                    </div>
                                    <div class="position-relative w-100">
                                        <h3 class="mb-0 text-capitalize inventory-dropdown--heading p-0">
                                            <?php echo esc_html($model); ?>
                                        </h3>
                                        <span class="inventory-dropdown--count">
                                            [<?php echo esc_html($model_data['count']); ?>]
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
            }

            // Optionally, display models for makes not in the make_order array
            foreach ($makes as $make => $make_data) {
                if (!in_array($make, $make_order) && !empty($make_data['models'])) {
                    ?>
                    <div class="row inventory-dropdown--right-wrapper w-100 position-absolute"
                         data-make="<?php echo esc_attr(str_replace(' ', '-', $make)); ?>">
                        <?php
                        foreach ($make_data['models'] as $model => $model_data) {
                            $model_image_src = get_stylesheet_directory_uri() . '/assets/images/' . str_replace(' ', '-', $model) . '.png';
                            $model_image_path = get_stylesheet_directory() . '/assets/images/' . str_replace(' ', '-', $model) . '.png';
                            $model_image      = '<i class="fa-solid fa-magnifying-glass"></i>';

                            if (file_exists($model_image_path)) {
                                $model_image = '<img src="' . esc_url($model_image_src) . '" alt="' . esc_attr($model) . '" title="' . esc_attr($model) . '" width="77" height="54" loading="lazy" decoding="async" class="me-3 d-inline-block inventory-dropdown--thumbnail" />';
                            }
                            ?>
                            <div class="col-4">
                                <a href="<?php echo esc_url(home_url('/new-vehicles-durango-colorado/?model%5b%5d=' . urlencode($model))); ?>"
                                   class="inventory-dropdown--model d-flex align-items-center justify-content-start"
                                   title="<?php echo esc_attr($model); ?>"
                                   aria-label="<?php echo esc_attr($model); ?>">
                                    <div class="inventory-dropdown--thumbnail-wrap">
                                        <?php echo $model_image; ?>
                                    </div>
                                    <div class="position-relative w-100">
                                        <h3 class="mb-0 text-capitalize inventory-dropdown--heading p-0">
                                            <?php echo esc_html($model); ?>
                                        </h3>
                                        <span class="inventory-dropdown--count">
                                            [<?php echo esc_html($model_data['count']); ?>]
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</ul>

	<!-- Append dropdown -->
	<script>
	jQuery(document).ready(function($) {
		$('header li.menu-item-943').addClass('menu-item-has-children').append($('.inventory-dropdown--wrapper'))
		$('header li.menu-item-943 .inventory-dropdown--wrapper').removeClass('d-none');
	})
	
	</script>

	<!-- Style the dropdown	 -->
	<style>
		.menu-item-943 {
			position: static !important;
		}
		ul.inventory-dropdown--wrapper {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 100vw;
			height: 60vh;
			background: white;
			z-index: 10;
		}
		.inventory-dropdown--wrapper .inventory-dropdown--left {
			height: 100%;
			overflow-y: scroll;
			padding-left: 0;
		}
		.inventory-dropdown--wrapper .inventory-dropdown--left::-webkit-scrollbar {
			display: none;
		}
		.inventory-dropdown--make:not(:last-child) {
			border-bottom: 1px solid #cecece;
		}
		.inventory-dropdown--make {
			padding: 5px 12px;
			height: 64px;
			display: inline-block;
		}
		
		.inventory-dropdown--heading {
			font-weight: 700;
			font-size: 0.95rem;
			letter-spacing: 0;
			color: #444;
			text-transform: capitalize;
		}
		.inventory-dropdown--count {
			font-size: .733rem;
			color: #444444;
			font-size: 15px;
			text-decoration: none;
			text-transform: none;
			font-weight: normal;
		}
		.inventory-dropdown--make i.fa-chevron-right {
			top: 50%;
			transform: translatey(-50%);
			right: 5px;
			color: #444;
			font-weight: bold;
		}
		.inventory-dropdown--thumbnail-wrap {
			max-width: 77px;
			min-width: 77px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-right: 1rem;
		}
		.inventory-dropdown--thumbnail {
			max-width: 77px;
			min-width: 77px;
			object-fit: contain;
			margin-left: 5px !important;

		}
		i.inventory-dropdown--thumbnail {
			padding-left: calc(77px - 50px);
			font-size: 1.5rem;
			color: #444;
		}
		.inventory-dropdown--right-wrapper {
			top: 0;
			left: -100%;
			transition: all .3s;
			width: 100%;
			height: 100%;
/* 			background: red; */
		}
		.inventory-dropdown--right-wrapper.active {
			left: 0;
		}
		.inventory-dropdown--model[href] {
			padding: 5px 12px !important;
			border-bottom: 1px solid #cecece;
		}
		.inventory-dropdown--model .inventory-dropdown--heading {
			font-weight: normal;
		}
		.inventory-dropdown--make:hover,
		.inventory-dropdown--model:hover {
			background: #ececec;
		}
		.inventory-dropdown--make:hover *:not(img),
		.inventory-dropdown--model:hover *:not(img) {
			color: #111;
		}
		
	</style>

	<script>
		jQuery(document).ready(function($) {
			let removeTimeout;

			$('.inventory-dropdown--make').on('mouseenter', function(e) {
				let make = $(this).attr('data-make');

				// Clear any previous timeout to prevent unintended hiding
				clearTimeout(removeTimeout);

				// Remove 'active' from all right panels to prevent overlapping
				$('.inventory-dropdown--right-wrapper').removeClass('active');

				// Add 'active' to the current panel
				$(`.inventory-dropdown--right-wrapper[data-make="${make}"]`).addClass('active');
			}).on('mouseleave', function(e) {
				let make = $(this).attr('data-make');
				removeTimeout = setTimeout(() => {
					$(`.inventory-dropdown--right-wrapper[data-make="${make}"]`).removeClass('active');
				}, 2000); // 2-second delay
			});

			/** Keep the panel hovered **/
			$('.inventory-dropdown--right-wrapper').on('mouseenter', function(e) {
				let parent = $(this).attr('data-make');

				// Clear timeout to prevent accidental hiding while hovering
				clearTimeout(removeTimeout);

				$(`.inventory-dropdown--right-wrapper[data-make="${parent}"]`).addClass('active');
			}).on('mouseleave', function(e) {
				let parent = $(this).attr('data-make');
				removeTimeout = setTimeout(() => {
					$(`.inventory-dropdown--right-wrapper[data-make="${parent}"]`).removeClass('active');
				}, 2000); // 2-second delay
			});
		});

	</script>
<?php }

add_action( 'wp_footer', 'dmc_inventory_dropdown' );