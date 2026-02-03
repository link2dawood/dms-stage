<?php
	global $authordata;
	$categories    = '';
	$author        = '';
	$thumb         = '';
	$date          = '';
	$author_avatar = '';
	$excerpt       = '';
	$thumb_id      = get_post_thumbnail_id( get_the_ID() );
	$alt           = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );


if ( 'on' === $show_categories ) {
	$categories = BRBL_PostHelper::get_post_categories( $show_first_category, $include_categories, 'brbl-post-tile-categories' );
}

if ( 'on' === $show_author ) {

	if ( 'on' === $use_author_photo ) {
		$author_avatar = sprintf(
			'<img src="%1$s" />',
			esc_url( get_avatar_url( $authordata->ID ) )
		);
	} else {
		$author_avatar = brbl_get_svg_user_icon();
	}

	$author = sprintf(
		'<div class="brbl-post-tile-author">
            %1$s
            %2$s
        </div>',
		$author_avatar,
		et_pb_get_the_author_posts_link()
	);
}

if ( 'on' === $show_date ) {
	$date = sprintf(
		'<div class="brbl-post-tile-date">
            %1$s
            %2$s
        </div>',
		brbl_get_svg_clock_icon(),
		get_the_date( $date_format )
	);
}

$excerpt = sprintf(
	'<div class="brbl-post-tile-excerpt">
		%1$s
	</div>',
	BRBL_PostHelper::get_post_excerpt( $excerpt_length )
);


if ( has_post_thumbnail() ) {
	$thumbnail = wp_get_attachment_image_src( $thumb_id, $thumb_size );
	$thumb     = sprintf(
		'<figure class="brbl-post-tile-figure">
			<a href="%2$s"><img src="%1$s" alt="%3$s" /></a>
		</figure>',
		isset( $thumbnail[0] ) ? $thumbnail[0] : '',
		esc_url( get_the_permalink() ),
		$alt
	);
} else {
	$thumb = '<figure class="brbl-post-tile-figure brbl-empty"></figure>';
}

printf(
	'<div class="brbl-post-tile %8$s">
        <article class="brbl-post-tile-inner">
			%1$s
			%7$s
            <div class="brbl-post-tile-content">
                <div class="brbl-post-tile-meta">
                    %2$s
                    %3$s
                </div>
                <h4 class="brbl-post-tile-title">
                    <a href="%4$s">%5$s</a>
                </h4>
                %6$s
            </div>
        </article>
    </div>',
	et_core_intentionally_unescaped( $categories, 'html' ),
	et_core_intentionally_unescaped( $author, 'html' ),
	et_core_intentionally_unescaped( $date, 'html' ),
	esc_url( get_the_permalink() ),
	et_core_intentionally_unescaped( get_the_title(), 'html' ),
	et_core_intentionally_unescaped( $excerpt, 'html' ),
	et_core_intentionally_unescaped( $thumb, 'html' ),
	et_core_esc_previously( $item_class )
);

