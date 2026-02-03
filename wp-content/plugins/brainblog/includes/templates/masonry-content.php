<?php
global $authordata;
$thumb          = '';
$categories     = '';
$author         = '';
$date           = '';
$meta           = '';
$author_avatar  = '';
$button         = '';
$excerpt        = '';
$thumb_id       = get_post_thumbnail_id(get_the_ID());
$alt            = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
$processed_icon = esc_attr(et_pb_process_font_icon($overlay_icon));
$overlay_icon   = !empty($processed_icon) ? $processed_icon : '';

if ('on' === $show_thumb) {
	$thumbnail = wp_get_attachment_image_src($thumb_id, $thumb_size);

	if (isset($thumbnail[0])) {
		$thumb     = sprintf(
			'<figure class="brbl-post-thumb">
				<a href="%1$s">
					<div class="brbl-overlay" data-icon="%4$s"></div>
					<img src="%2$s" alt="%3$s">
					%5$s
				</a>
			</figure>',
			esc_url(get_the_permalink()),
			$thumbnail[0],
			$alt,
			$overlay_icon,
			brbl_get_img_masking_shapes($img_masking_shape)
		);
	} else {
		$thumb = '';
	}
}

if ('on' === $show_categories) {
	$categories = BRBL_PostHelper::get_post_categories($show_first_category, $include_categories, 'brbl-masonry-categories');
}

if ('on' === $show_author) {

	if ('on' === $use_author_photo) {
		$author_avatar = sprintf(
			'<img src="%1$s">',
			esc_url(get_avatar_url($authordata->ID))
		);
	} else {
		$author_avatar = brbl_get_svg_user_icon();
	}

	$author = sprintf(
		'<div class="brbl-masonry-author">
            %1$s
            %2$s
        </div>',
		$author_avatar,
		et_pb_get_the_author_posts_link()
	);
}

if ('on' === $show_date) {
	$date = sprintf(
		'<div class="brbl-masonry-date">
            %1$s
            %2$s
        </div>',
		brbl_get_svg_clock_icon(),
		get_the_date($date_format)
	);
}

if ('on' === $show_meta) {
	$meta = sprintf(
		'<div class="brbl-blog-meta">
            %1$s
            %2$s
        </div>',
		$author,
		$date
	);
}

if ('on' === $show_excerpt) {
	$excerpt = BRBL_PostHelper::get_post_excerpt_html($excerpt_length);
}

if ('on' === $show_btn) {
	$button = BRBL_PostHelper::get_post_button($button_text, $button_icon, '');
}

printf(
	'<div class="brbl-blog-item %8$s">
        <article class="brbl-masonry-card brbl-post-%1$s">
            %7$s
            %3$s
            <div class="brbl-masonry-content">
				%4$s
                %2$s
                %5$s
                %6$s
            </div>
        </article>
    </div>',
	et_core_esc_previously($layout),
	et_core_intentionally_unescaped(BRBL_PostHelper::get_post_title($title_tag), 'html'),
	et_core_intentionally_unescaped($categories, 'html'),
	et_core_intentionally_unescaped($meta, 'html'),
	et_core_intentionally_unescaped($excerpt, 'html'),
	et_core_intentionally_unescaped($button, 'html'),
	et_core_intentionally_unescaped($thumb, 'html'),
	et_core_esc_previously($item_class)
);
