<?php

$figure    = '';
$author    = '';
$date      = '';
$separator = '';
$excerpt   = '';
$meta      = '';

if ( 'on' === $show_thumb ) {
	if ( has_post_thumbnail() ) {
		if ( 'custom' === $thumb_size ) {
			$thumb_size = array( intval( $thumb_width ), intval( $thumb_height ) );
		}
		$figure = sprintf(
			'<figure class="brbl-post-list-thumb">
				%1$s
			</figure>',
			get_the_post_thumbnail( get_the_ID(), $thumb_size )
		);
	} else {
		$figure = '<figure class="brbl-post-list-thumb brbl-empty-thumb"></figure>';
	}
} else {
	if ( 'on' === $show_icon ) {
		$figure = sprintf(
			'<div class="brbl-post-list-icon">
				<i class="brbl-et-font-icon">%1$s</i>
			</div>',
			$list_icon
		);
	}
}

if ( 'on' === $show_author ) {
	$author = sprintf(
		'<div class="brbl-post-list-author">
			By %1$s
		</div>',
		get_the_author()
	);
}

if ( 'on' === $show_date ) {
	$date = sprintf(
		'<div class="brbl-post-list-date">
			%1$s
		</div>',
		get_the_time( $date_format )
	);
}

if ( 'on' === $show_author && 'on' === $show_date ) {
	$separator = '<span class="brbl-separator">|</span>';
}

if ( 'on' === $show_excerpt ) {
	$excerpt = sprintf(
		'<p class="brbl-post-list-excerpt">%1$s</p>',
		BRBL_PostHelper::get_post_excerpt( $excerpt_length )
	);
}

if ( 'on' === $show_author || 'on' === $show_date ) {
	$meta = sprintf(
		'<div class="brbl-post-list-meta">
			%1$s %2$s %3$s
		</div>',
		$author,
		$separator,
		$date
	);
}


printf(
	'<li class="brbl-post-list-child">
		<a class="brbl-post-list-child-inner" href="%1$s">
			%5$s
			<div class="brbl-post-list-content">
				<h3 class="brbl-post-list-title">%2$s</h3>
				%3$s
				%4$s
			</div>
		</a>
	</li>',
	esc_url( get_the_permalink() ),
	et_core_intentionally_unescaped( get_the_title(), 'html' ),
	et_core_intentionally_unescaped( $meta, 'html' ),
	et_core_intentionally_unescaped( $excerpt, 'html' ),
	et_core_intentionally_unescaped( $figure, 'html' )
);
