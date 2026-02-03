<?php
namespace BrainBlog\Traits;

defined( 'ABSPATH' ) || die();

trait Smart_Post_List {

	public static function spl_render_posts( $settings, $query, $post_counter, $btn_icon ) {

		$show_excerpt      = $settings['show_excerpt'];
		$excerpt_length    = $settings['excerpt_length'];
		$ft_excerpt_length = $settings['ft_excerpt_length'];
		$ft_show_excerpt   = $settings['ft_show_excerpt'];
		$ft_show_date      = $settings['ft_show_date'];
		$ft_date_format    = $settings['ft_date_format'];
		$ft_show_author    = $settings['ft_show_author'];
		$ft_show_btn       = $settings['ft_show_btn'];
		$btn_text          = $settings['btn_text'];
		$date_format       = $settings['date_format'];
		$show_thumb        = $settings['show_thumb'];
		$is_featured       = $settings['is_featured'];
		$show_author       = $settings['show_author'];
		$show_date         = $settings['show_date'];
		$ft_layout         = $settings['ft_layout'];
		$thumb_size        = $settings['thumb_size'];
		$ft_img_size       = $settings['ft_img_size'];

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) :

				$query->the_post();

				if ( 'on' === $is_featured && 1 === $post_counter ) {
					echo '<div class="brbl-smart-post-list">';
				}

				$thumb          = '';
				$post_title     = '';
				$readmore       = '';
				$author         = '';
				$date           = '';
				$meta           = '';
				$excerpt        = '';
				$thumb_html     = '';
				$featured_class = 'brbl-default';

				$is_date   = false;
				$is_author = false;
				$is_meta   = false;

				$_date_format = null;

				$thumb_id = get_post_thumbnail_id( get_the_ID() );
				$alt      = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );

				if ( 'on' === $is_featured && 0 === $post_counter ) {
					$featured_class = 'brbl-featured';
					$featured_class = $featured_class . ' ' . $ft_layout;
					$thumbnail_size = $ft_img_size;
				} else {
					$thumbnail_size = $thumb_size;
				}

				if ( has_post_thumbnail() ) {
					$thumbnail  = wp_get_attachment_image_src( $thumb_id, $thumbnail_size );
					$thumb_html = sprintf(
						'<figure class="brbl-smart-post-thumb">
							<a href="%1$s">
								<img src="%2$s" alt="%3$s">
							</a>
						</figure>',
						esc_url( get_the_permalink() ),
						! empty( $thumbnail ) ? $thumbnail[0] : '',
						$alt
					);
				} else {
					if ( 'on' === $is_featured && 0 === $post_counter ) {
						$thumb_html = '<figure class="brbl-smart-post-thumb empty-thumb"></figure>';
					}
				}

				if ( 'on' === $is_featured && 0 === $post_counter ) {

					$thumb = $thumb_html;

					if ( 'on' === $ft_show_date ) {
						$is_date      = true;
						$_date_format = $ft_date_format;
					}

					if ( 'on' === $ft_show_author ) {
						$is_author = true;
					}

					if ( 'on' === $ft_show_author || 'on' === $ft_show_date ) {
						$is_meta = true;
					}
				} elseif ( ( 'on' === $is_featured && 0 !== $post_counter ) || ( 'off' === $is_featured ) ) {

					if ( 'on' === $show_thumb ) {
						$thumb = $thumb_html;
					}

					if ( 'on' === $show_date ) {
						$is_date      = true;
						$_date_format = $date_format;
					}

					if ( 'on' === $show_author ) {
						$is_author = true;
					}

					if ( 'on' === $show_author || 'on' === $show_date ) {
						$is_meta = true;
					}
				}

				if ( $is_date ) {

					$date = sprintf(
						'<div class="brbl-smart-post-date">
							%1$s
							%2$s
						</div>',
						brbl_get_svg_clock_icon(),
						get_the_date( $_date_format )
					);

				}

				$author_id = get_the_author_meta( 'ID' );

				if ( $is_author ) {

					$author = sprintf(
						'<div class="brbl-smart-post-author">
								%1$s
								%2$s
							</div>',
						brbl_get_svg_user_icon(),
						esc_html( get_the_author_meta( 'display_name', $author_id ) )
					);

				}

				if ( $is_meta ) {

					$meta = sprintf(
						'<div class="brbl-smart-post-meta">
							%1$s %2$s
						</div>',
						$author,
						$date
					);

				}

				if ( 'on' === $is_featured && 0 === $post_counter && 'on' === $ft_show_btn ) {
					$readmore = sprintf(
						'<div class="brbl-smart-post-btn">
							<a href="%1$s" target="_self" class="et_pb_button" data-icon="%2$s">
								%3$s
							</a>
						</div>',
						esc_url( get_the_permalink() ),
						$btn_icon,
						$btn_text
					);
				}

				$post_title = sprintf(
					'<%1$s class="brbl-post-title">
						<a href="%2$s">%3$s</a>
					</%1$s>',
					'h4',
					esc_url( get_the_permalink() ),
					get_the_title()
				);

				if ( 'on' === $is_featured && 0 === $post_counter ) {

					if ( 'on' === $ft_show_excerpt ) {
						$excerpt = brbl_get_excerpt( $ft_excerpt_length );
					}
				} else {

					if ( 'on' === $show_excerpt ) {
						$excerpt = brbl_get_excerpt( $excerpt_length );
					}
				}

				printf(
					'<div class="brbl-smart-post-item %6$s">
						<article class="brbl-smart-post-item-inner">
							%1$s
							<div class="brbl-smart-post-content">
								%2$s %3$s
								%4$s
								%5$s
							</div>
						</article>
					</div>',
					et_core_intentionally_unescaped( $thumb, 'html' ),
					et_core_intentionally_unescaped( $meta, 'html' ),
					et_core_intentionally_unescaped( $post_title, 'html' ),
					et_core_intentionally_unescaped( $excerpt, 'html' ),
					et_core_intentionally_unescaped( $readmore, 'html' ),
					et_core_esc_previously( $featured_class )
				);

				$post_counter++;

			endwhile;

			wp_reset_postdata();

		endif;

		if ( 'on' === $is_featured && 0 !== $post_counter ) {
			echo '</div>';
		}

	}

}
