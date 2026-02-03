<?php
namespace Brain_Blog\Includes;

use BrainBlog\Traits\Smart_Post_List;
use WP_Query;

defined( 'ABSPATH' ) || die();

class Post_Helper {

	use Smart_Post_List;

	public static function init() {
		add_action( 'wp_ajax_brbl_smart_post_filter', array( __CLASS__, 'smart_post_filter' ) );
		add_action( 'wp_ajax_nopriv_brbl_smart_post_filter', array( __CLASS__, 'smart_post_filter' ) );
		add_action( 'wp_ajax_brbl_post_grid_pagination', array( __CLASS__, 'post_grid_pagination' ) );
		add_action( 'wp_ajax_nopriv_brbl_post_grid_pagination', array( __CLASS__, 'post_grid_pagination' ) );
	}

	public static function post_grid_pagination() {

        $settings = isset( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ): ''; // phpcs:ignore
        $paged = isset( $_POST['paged'] ) ? wp_unslash( $_POST['paged'] ): '1'; // phpcs:ignore
        $paged = intval( $paged ); // phpcs:ignore

		$security = check_ajax_referer( 'brain_blog_nonce', 'security' );

		if ( true == $security && ! empty( $settings ) ) :

			$include_categories  = $settings['include_categories'];
			$img_masking_shape   = $settings['img_masking_shape'];
			$include_posts       = $settings['include_posts'];
			$exclude_posts       = $settings['exclude_posts'];
			$show_categories     = $settings['show_categories'];
			$show_thumb          = $settings['show_thumb'];
			$show_author         = $settings['show_author'];
			$show_date           = $settings['show_date'];
			$show_title          = $settings['show_title'];
			$show_excerpt        = $settings['show_excerpt'];
			$thumb_size          = $settings['thumb_size'];
			$date_format         = $settings['date_format'];
			$content_length      = $settings['content_length'];
			$overlay_icon        = $settings['overlay_icon'];
			$title_tag           = $settings['title_tag'];
			$offset_number       = $settings['offset_number'];
			$show_btn            = $settings['show_btn'];
			$show_first_category = $settings['show_first_category'];
			$thumb_width         = $settings['thumb_width'];
			$thumb_height        = $settings['thumb_height'];
			$button_icon         = $settings['button_icon'];
			$button_text         = $settings['button_text'];
			$item_class          = '';

			// remove current category from the list.
			$pattern            = '/,?current/';
			$include_categories = preg_replace( $pattern, '', $include_categories );
			$pattern            = '/^,?/';
			$include_categories = preg_replace( $pattern, '', $include_categories );

			$query_args = array(
				'posts_per_page' => intval( $settings['post_count'] ),
				'post_type'      => $settings['post_type'],
				'post_status'    => array( 'publish', 'private' ),
				'perm'           => 'readable',
				'orderby'        => $settings['order_by'],
				'order'          => $settings['order'],
				'paged'          => $paged,
			);

			// Exclude Posts.
			if ( ! empty( $exclude_posts ) ) {
				$exclude_posts              = str_replace( ' ', '', $exclude_posts );
				$exclude_posts              = explode( ',', $exclude_posts );
				$query_args['post__not_in'] = $exclude_posts;
			}

			// Include Posts.
			if ( ! empty( $include_posts ) && ! is_singular() ) {
				$include_posts          = str_replace( ' ', '', $include_posts );
				$include_posts          = explode( ',', $include_posts );
				$query_args['post__in'] = $include_posts;
			}

			if ( $paged > 1 ) {
				$query_args['offset'] = ( ( $paged - 1 ) * intval( $settings['post_count'] ) ) + intval( $offset_number );
			} else {
				$query_args['offset'] = intval( $offset_number );
			}

			if ( ! empty( $include_categories ) ) {
				$query_args['cat'] = explode( ',', $include_categories );
			}

			$query = new WP_Query( $query_args );
			ob_start();
			// Query.
			if ( $query->have_posts() ) {

				while ( $query->have_posts() ) {
					$query->the_post();
					if ( 'layout1' === $settings['layout'] ) {
						include BRAIN_BLOG_DIR . 'includes/templates/post-card-1.php';
					} elseif ( 'layout2' === $settings['layout'] ) {
						include BRAIN_BLOG_DIR . 'includes/templates/post-card-2.php';
					} elseif ( 'layout3' === $settings['layout'] ) {
						include BRAIN_BLOG_DIR . 'includes/templates/post-card-3.php';
					} elseif ( 'layout4' === $settings['layout'] ) {
						include BRAIN_BLOG_DIR . 'includes/templates/post-card-4.php';
					}
				}
			}

			// Output.
			$html = ob_get_clean();

			wp_send_json(
				array(
					'html' => $html,
				)
			);

			wp_die();
		endif;
	}


	public static function smart_post_filter() {

		$security = check_ajax_referer( 'brain_blog_nonce', 'security' );

		if ( true == $security && isset( $_POST['settings'] ) ) :

			$settings = isset( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ): ''; // phpcs:ignore

			$category_id = isset( $_POST['category_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_id'] ) ) : '';
			$offset      = isset( $_POST['offset'] ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : '';

			$post_counter = 0;

			$post_count = $settings['post_count'];
			$btn_icon   = $settings['btn_icon'];
			$post_type  = $settings['post_type'];
			$order_by   = $settings['order_by'];
			$order      = $settings['order'];

			$args = array(
				'post_status'    => 'publish',
				'posts_per_page' => $post_count,
				'post_type'      => $post_type,
				'orderby'        => $order_by,
				'order'          => $order,
				'offset'         => intval( $offset ),
			);

			if ( 'all' !== $category_id ) {
				$category_id = explode( ',', $category_id );
				$args['cat'] = $category_id;
			}

			$_query = new WP_Query( $args );

			$args['posts_per_page'] = -1;

			$all_post_query = new WP_Query( $args );
			$total_post     = $all_post_query->post_count;

			ob_start();

			self::spl_render_posts( $settings, $_query, $post_counter, $btn_icon );

			$html = ob_get_clean();

			wp_send_json(
				array(
					'html'          => $html,
					'total_posts'   => $total_post,
					'current_posts' => $_query->post_count,
					'offset'        => intval( $offset ),
				)
			);

		endif;

		wp_die();

	}

	public static function sanitize_fields( $fields ) {

		foreach ( $fields as $field ) {
			self::sanitize_field( $field );
		}

	}

	public static function sanitize_field( $field ) {
		sanitize_text_field( $field );
	}


}

Post_Helper::init();
