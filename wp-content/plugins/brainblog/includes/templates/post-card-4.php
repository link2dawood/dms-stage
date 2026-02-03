<?php
global $authordata;
$thumb         = '';
$post_title    = '';
$categories    = '';
$author        = '';
$date          = '';
$author_avatar = '';
$excerpt       = '';
$thumb_id      = get_post_thumbnail_id( get_the_ID() );
$alt           = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );

if ( 'on' === $show_categories ) {
	$categories = BRBL_PostHelper::get_post_categories( $show_first_category, $include_categories, 'brbl-post-categories' );
}

if ( 'on' === $show_thumb ) {
	$author_avatar = sprintf(
		'<div class="brbl-author-avatar"><img src="%1$s"></div>',
		esc_url( get_avatar_url( $authordata->ID ) )
	);

	if ( 'custom' === $thumb_size ) {
		$thumb_size = array( intval( $thumb_width ), intval( $thumb_height ) );
	}

	if ( has_post_thumbnail() ) {
		$thumb = sprintf(
			'<figure class="brbl-post-thumb">
				<a href="%1$s">
					<div class="brbl-overlay" data-icon="%3$s"></div>
					%2$s
				</a>
				%4$s
			</figure>',
			esc_url( get_the_permalink() ),
			get_the_post_thumbnail( get_the_ID(), $thumb_size ),
			esc_html( $overlay_icon ),
			$categories
		);
	} else {
		$thumb = sprintf(
			'<figure class="brbl-post-thumb brbl-empty-thumb">
				<a href="%1$s">
					<div class="brbl-overlay" data-icon="%2$s"></div>
				</a>
				%3$s
			</figure>',
			esc_url( get_the_permalink() ),
			esc_html( $overlay_icon ),
			$categories
		);
	}
}

if ( 'on' === $show_author ) {
	$author = sprintf(
		'<div class="brbl-post-author">
	%1$s
	%2$s
	</div>',
		brbl_get_svg_user_icon(),
		get_the_author_posts_link()
	);
}

if ( 'on' === $show_date ) {
	$date = sprintf(
		'<div class="brbl-post-date">
            %1$s
            %2$s
        </div>',
		brbl_get_svg_clock_icon(),
		get_the_date( $date_format )
	);
}

if ( 'on' === $show_title ) {
	$post_title = BRBL_PostHelper::get_post_title( $title_tag );
}

if ( 'on' === $show_excerpt ) {
	$excerpt = BRBL_PostHelper::get_post_excerpt_html( $content_length );
}

printf(
	'<div class="brbl-blog-item %7$s">
        <article class="brbl-post-card brbl-post-card-4">
			%1$s
			%6$s
            <div class="brbl-blog-content">
				%2$s
				%5$s
                <div class="brbl-blog-meta">
                    %3$s
                    %4$s
                </div>
            </div>
        </article>
    </div>',
	et_core_intentionally_unescaped( $thumb, 'html' ),
	et_core_intentionally_unescaped( $post_title, 'html' ),
	et_core_intentionally_unescaped( $author, 'html' ),
	et_core_intentionally_unescaped( $date, 'html' ),
	et_core_intentionally_unescaped( $excerpt, 'html' ),
	et_core_intentionally_unescaped( $author_avatar, 'html' ),
	et_core_esc_previously( $item_class )
);
