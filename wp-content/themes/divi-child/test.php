<?php
/** Template Name: Query Test Template */

get_header();

$args = [
    'post_type'      => 'listings',
    'posts_per_page' => -1,
	'meta_query' => [
		'relation' => 'AND',
		[
            'key'     => 'series',
            'value'   => ['se', ' capstone'],
            'compare' => 'IN',
        ]
	]
];

$query = new WP_Query($args);
// echo '<pre>';
// print_r($query);
// echo '</pre>';

if ($query->have_posts()) {
    echo '<ul>';
    while ($query->have_posts()) {
        $query->the_post();
        echo '<li>' . get_the_title() . '</li>';
    }
    echo '</ul>';
}

wp_reset_postdata();

get_footer();
