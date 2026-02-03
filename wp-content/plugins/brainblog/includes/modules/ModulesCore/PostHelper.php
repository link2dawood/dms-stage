<?php
class BRBL_PostHelper {

	public static function get_post_thumb( $overlay_icon, $render_empty_figure ) {

		$thumb_id       = get_post_thumbnail_id( get_the_ID() );
		$alt            = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
		$processed_icon = esc_attr( et_pb_process_font_icon( $overlay_icon ) );
		$overlay_icon   = ! empty( $processed_icon ) ? $processed_icon : '';

		if ( has_post_thumbnail() ) {
			return sprintf(
				'<figure class="brbl-post-thumb">
                    <a href="%1$s">
                        <div class="brbl-overlay" data-icon="%4$s"></div>
                        <img src="%2$s" alt="%3$s">
                    </a>
                </figure>',
				esc_url( get_the_permalink() ),
				esc_url( get_the_post_thumbnail_url() ),
				$alt,
				esc_html( $overlay_icon )
			);
		} else {
			if ( true === $render_empty_figure ) {
				return '<figure class="brbl-post-thumb brbl-empty-thumb"></figure>';
			}
		}
	}

	public static function get_post_title( $tag ) {
		return sprintf(
			'<%1$s class="brbl-post-title">
                <a href="%2$s">%3$s</a>
            </%1$s>',
			$tag,
			esc_url( get_the_permalink() ),
			get_the_title()
		);
	}

	public static function get_post_categories( $show_first_category, $include_categories, $class_name ) {
		if ( 'off' === $show_first_category ) {
			return sprintf(
				'<div class="%2$s">%1$s</div>',
				et_builder_get_the_term_list( ', ' ),
				$class_name
			);
		} else {

			$pattern            = '/,?current/';
			$include_categories = preg_replace( $pattern, '', $include_categories );
			$pattern            = '/^,?/';
			$include_categories = preg_replace( $pattern, '', $include_categories );

			if ( ! empty( $include_categories ) ) {
				$categories_ids = explode( ',', $include_categories );
			} else {
				$categories_ids = get_terms(
					array( 'category' ),
					array( 'fields' => 'ids' )
				);
			}

			return sprintf(
				'<div class="%3$s" xyx="%4$s"><a rel="tag" href="%2$s">%1$s</a></div>',
				get_the_category_by_ID( $categories_ids[0] ),
				get_category_link( $categories_ids[0] ),
				$class_name,
				$include_categories
			);
		}
	}

	public static function get_post_button(
		$button_text = 'Read More',
		$icon_name = '5',
		$classes = ''
		) {
		return sprintf(
			'<div class="brbl-post-btn-wrap %4$s">
                <a href="%1$s" target="_self" class="et_pb_button brbl-post-btn" data-icon="%2$s">
                    %3$s
                </a>
            </div>',
			esc_url( get_the_permalink() ),
			$icon_name,
			$button_text,
			$classes
		);
	}


	public static function strip_shortcodes( $content, $truncate_post_based_shortcodes_only = false ) {
		global $shortcode_tags;

		$content = trim( $content );

		$strip_content_shortcodes = array(
			'et_pb_code',
			'et_pb_fullwidth_code',
		);

		// list of post-based shortcodes.
		if ( $truncate_post_based_shortcodes_only ) {
			$strip_content_shortcodes = array(
				'et_pb_post_slider',
				'et_pb_fullwidth_post_slider',
				'et_pb_blog',
				'et_pb_comments',
			);
		}

		foreach ( $strip_content_shortcodes as $shortcode_name ) {
			$regex = sprintf(
				'(\[%1$s[^\]]*\][^\[]*\[\/%1$s\]|\[%1$s[^\]]*\])',
				esc_html( $shortcode_name )
			);

			$content = preg_replace( $regex, '', $content );
		}

		// do not proceed if we need to truncate post-based shortcodes only.
		if ( $truncate_post_based_shortcodes_only ) {
			return $content;
		}

		$shortcode_tag_names = array();
		foreach ( $shortcode_tags as $shortcode_tag_name => $shortcode_tag_cb ) {
			if ( 0 !== strpos( $shortcode_tag_name, 'et_pb_' ) ) {
				continue;
			}

			$shortcode_tag_names[] = $shortcode_tag_name;
		}

		$et_shortcodes = implode( '|', $shortcode_tag_names );

		$regex_opening_shortcodes = sprintf( '(\[(%1$s)[^\]]+\])', esc_html( $et_shortcodes ) );
		$regex_closing_shortcodes = sprintf( '(\[\/(%1$s)\])', esc_html( $et_shortcodes ) );

		$content = preg_replace( $regex_opening_shortcodes, '', $content );
		$content = preg_replace( $regex_closing_shortcodes, '', $content );

		return $content;
	}


	public static function get_post_excerpt( $length = '-1' ) {
		global $post;
		$content = '';

		if ( ! has_excerpt() ) {

			$content = $post->post_content;
			$content = preg_replace( '@\[caption[^\]]*?\].*?\[\/caption]@si', '', $content );
			$content = preg_replace( '@\[et_pb_post_nav[^\]]*?\].*?\[\/et_pb_post_nav]@si', '', $content );
			$content = preg_replace( '@\[audio[^\]]*?\].*?\[\/audio]@si', '', $content );
			$content = preg_replace( '@\[embed[^\]]*?\].*?\[\/embed]@si', '', $content );
			$content = wp_strip_all_tags( $content );
			$content = self::strip_shortcodes( $content );
			$content = et_builder_strip_dynamic_content( $content );
			$content = apply_filters( 'et_truncate_post', $content, get_the_ID() );
		} else {

			$content = apply_filters( 'the_excerpt', $post->post_excerpt );
		}

		if ( $length > 0 ) {

			if ( strlen( $content ) <= $length ) {

				$str_end = '';
			} else {

				$str_end = '...';
			}

			$content = rtrim( et_wp_trim_words( $content, $length, '' ) );

			if ( ! empty( $str_end ) ) {

				$new_words_array = (array) explode( ' ', $content );
				array_pop( $new_words_array );
				$content  = implode( ' ', $new_words_array );
				$content .= $str_end;
			}

			return et_core_intentionally_unescaped( $content, 'html' );
		} else {

			return et_core_intentionally_unescaped( $content, 'html' );
		}
	}

	public static function get_post_excerpt_html( $length = '-1' ) {

		return sprintf( '<div class="brbl-post-excerpt">%1$s</div>', self::get_post_excerpt( $length ) );
	}

}
