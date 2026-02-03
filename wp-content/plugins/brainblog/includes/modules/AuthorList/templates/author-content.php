<?php
if ( 'avatar' === $figure ) :
	$avatar_html = get_avatar( $user_id['author_id'], $avatar_size );
elseif ( 'icon' === $figure ) :
	$avatar_html = sprintf( '<span class="brbl-icon brbl-et-icon">%1$s</span>', $icon );
elseif ( 'custom' === $figure ) :
	$avatar_html = sprintf( '<img src="%1$s" alt="" />', $image );
endif;

$author_id = $user_id['author_id'];

$author_url       = get_the_author_meta( 'user_url', $author_id );
$author_name      = get_the_author_meta( $name, $author_id );
$author_posts_url = get_author_posts_url( $author_id );
$author_email     = get_the_author_meta( 'user_email', $author_id );
$author_desc      = get_the_author_meta( 'description', $author_id );

$author_href = 'website' === $figure_url ? $author_url : $author_posts_url;
$name_href   = 'none' === $name_url ? $author_url : $author_posts_url;

?>

<li class="brbl-author-list-child design-1">
	<div class="brbl-author-list-child-inner">
		<div class="brbl-author-list-top">
			<div class="brbl-author-list-figure">
				<?php
				if ( 'none' !== $figure_url ) {
					printf(
						'<a href="%s">
							%s
						</a>',
						et_core_esc_previously( $author_href ),
						et_core_intentionally_unescaped( $avatar_html, 'html' )
					);
				} else {
					echo et_core_intentionally_unescaped( $avatar_html, 'html' );
				}
				?>
			</div>
			<div class="brbl-author-list-bio-info">
				<h4 class="brbl-author-list-name">
					<?php
					if ( 'none' !== $name_url ) {
						printf(
							'<a href="%s">%s</a>',
							et_core_esc_previously( $name_href ),
							et_core_esc_previously( $author_name )
						);
					} else {
						echo et_core_esc_previously( $author_name );
					}
					?>
				</h4>
				<?php if ( 'on' === $show_post_count ) : ?>
					<div class="brbl-author-list-post-count">
						<?php echo et_core_esc_previously( $post_count_text ); ?>
						<?php echo et_core_esc_previously( $user_id['post_count'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'on' === $show_email ) : ?>
					<div class="brbl-author-list-email">
						<?php echo et_core_esc_previously( $author_email ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( 'on' === $show_bio ) : ?>
			<div class="brbl-author-list-bio">
				<?php echo et_core_esc_previously( $author_desc ); ?>
			</div>
		<?php endif; ?>
	</div>
</li>
