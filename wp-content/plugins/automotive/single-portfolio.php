<?php get_header();

global $post;
if (have_posts()) : while (have_posts()) : the_post();

function portfolio_details(){
	global $post;
	$project_details = get_post_meta($post->ID, "project_details", true);

	if(isset($project_details) && !empty($project_details[0])){
        echo "<ul>";
		foreach($project_details as $project_detail){
			echo "<li><i class='fa fa-check-circle'></i>" . $project_detail . "</li>";
		}
        echo "</ul>";
	}
}

function portfolio_related($title){
	global $post;

	$Automotive_Plugin = Automotive_Plugin();
	$terms             = get_the_terms( $post->ID , 'project-type');
	$term_ids          = (empty($terms) ? array() : array_values( wp_list_pluck($terms, 'term_id') ) );
	$i                 = 1;

	$related_query = array(
		'post_type'      => 'listings_portfolio',
		'tax_query'      => array( ),
		'posts_per_page' => 4,
		'orderby'        => 'rand',
		'post__not_in'   => array($post->ID)
	);

	if(!empty($term_ids)){
		$related_query['tax_query'][] = array(
		  'taxonomy' => 'project-type',
		  'field'    => 'id',
		  'terms'    => $term_ids,
		  'operator' => 'IN'
   );
	}

	$second_query  = new WP_Query( $related_query );

	echo "<div class=\"container\">";
	echo $title;
	echo "<div class=\"related_post row clearfix\">";

	//Loop through posts and display...
	if($second_query->have_posts()) : while ($second_query->have_posts() ) : $second_query->the_post();
		$format      = get_post_meta(get_the_ID(), "format", true);
		$content     = get_post_meta(get_the_ID(), "portfolio_content", true);
		$description = get_post_meta(get_the_ID(), "short_description", true);


		if($format == "image"){
			$class     = "picture-o";
			$thumbnail = get_the_post_thumbnail(get_the_ID(), 'related_portfolio');
		} elseif($format == "video"){
			$class     = "youtube-play";
			$video_id  = $Automotive_Plugin->get_video_id($content);
			$video_id  = $video_id[1];
			$thumbnail = "<img src='https://i.ytimg.com/vi/" . $video_id . "/hqdefault.jpg' alt='' class='img-responsive'>";
		} elseif($format == "gallery"){
			$class     = "plus-square-o";

			if($content[0]){
				$full      = wp_get_attachment_image_src($content[0], "related_portfolio");
				$thumbnail = "<img src='" . (isset($content[0]['related']['url']) && !empty($content[0]['related']['url']) ? $content[0]['related']['url'] : $full[0]) . "' alt='' class='img-responsive'>";
			} else {
				$full      = '#';
				$thumbnail = '';
			}
		}
	?>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12">
            <div class="car-block"><a href="<?php echo get_permalink(get_the_ID()); ?>">
                <div class="img-flex"> <span class="align-center"><i class="fa fa-3x fa-<?php echo $class; ?>"></i></span> <?php echo $thumbnail; ?> </div>
                <div class="car-block-bottom">
                    <h2><?php the_title(); ?></h2>
                    <?php echo (!empty($description) ? "<h4>" . $description . "</h4>" : ""); ?>
                </div>
                </a>
            </div>
        </div>
   <?php $i++; endwhile;
   	else:
   		echo "<span class='no_related_projects'>" . __("No related projects", "listings") . "</span>";
   	endif;

	echo "</div>";
	echo "</div>";
}

function portfolio_layout($layout){
	global $post;

	$Automotive_Plugin = Automotive_Plugin();

	if(function_exists("content_classes")){
		$sidebar = get_post_meta( $post->ID, "sidebar", true );
		$classes = content_classes( $sidebar );

		$content_class = $classes[0];
		$sidebar_class = ( isset( $classes[1] ) && ! empty( $classes[1] ) ? $classes[1] : "" );
	}

	$format  = get_post_meta($post->ID, "format", true);
  $content = get_post_meta($post->ID, "portfolio_content", true);
  $links   = get_post_meta($post->ID, "portfolio_links", true);

	$portfolio_sidebar_disabled = automotive_listing_get_option('disable_portfolio_sidebar', false);	?>

	<div class="<?php echo (isset($sidebar) ? $content_class : 'col-lg-12'); ?>">
	<?php
	if($layout == "wide"){

		if($portfolio_sidebar_disabled){
			$main_column_classes = 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 left-content padding-left-none';
		} else {
			$main_column_classes = 'col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 left-content padding-left-none';
		} ?>
    	<div class="page-content">
    	<?php
		switch($format){
			case "image":
				if( has_post_thumbnail() ){
					echo "<div class='padding-bottom-40'>";
					the_post_thumbnail();
					echo "</div>";
				}
			break;

			case "video":
				$video_id = $Automotive_Plugin->get_video_id($content);

				if($video_id){
					echo "<div class=\"video-container\">";

					if($video_id[0] == "youtube"){
						echo "<iframe class=\"portfolio_video " . $layout . "\" width=\"100%\" height=\"auto\" src=\"https://www.youtube.com/embed/" . $video_id[1] . "?rel=0\" allowfullscreen></iframe>";
					} elseif($video_id[0] == "vimeo"){
						echo "<iframe class=\"portfolio_video " . $layout . "\" width=\"100%\" height=\"auto\" src=\"https://player.vimeo.com/video/" . $video_id[1] . "?rel=0\" allowfullscreen></iframe>";
					} elseif($video_id[0] == "self_hosted"){
						echo do_shortcode("[video width=\"2000px\" height=\"auto\" mp4=\"" . $video_id[1] . "\"]");
					}
				} else {
					echo __( "Not a valid YouTube/Vimeo link", "listings" ) . "...";
				}

				echo "</div>";
			break;

			case "gallery":
				if(!empty($content)){
					echo "<!--OPEN OF SLIDER-->";
					echo "<div class=\"slider padding-left-none padding-right-none padding-bottom-40\">";
						echo "<div class=\"flexslider flexslider2\">";
							echo "<ul class=\"slides item\">";
                                $i = 0;
							    foreach($content as $image){
						        $full = wp_get_attachment_image_src($image, "full");
						        $alt  = get_post_meta( $image, '_wp_attachment_image_alt', true);

								    echo "<li>";
                    echo (isset($links[$i]) && !empty($links[$i]) ? "<a href='" . esc_url($links[$i]) . "'>" : "");
                    echo "<img src='" . esc_url($full[0]) . "' alt='" . esc_attr($alt) . "' class='disable-lazy-load'>";
                    echo (isset($links[$i]) && !empty($links[$i]) ? "</a>" : "");
                    echo "</li>";

                    $i++;
							    }
							echo "</ul>";
						echo "</div>";
					echo "</div>";
					echo "<!--CLOSE OF SLIDER-->";
				} else {
					echo __("No images in slideshow", "listings");
				}
			break;
		} ?>

    	<div class="container">
	        <div class="row padding-bottom-40">
	            <div class="<?php echo $main_column_classes; ?>">
	                <div class="right_site_job">
	                    <div class="job  margin-top-30 sm-padding-bottom-40 xs-padding-bottom-40">
	                        <h2><?php echo automotive_listing_get_option('job_description_title', ''); ?></h2>
	                        <?php the_content(); ?>
	                    </div>
	                </div>
	            </div>
							<?php if(!$portfolio_sidebar_disabled){ ?>
		            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 right-content padding-right-none xs-padding-left-none">
		                <div class="right_site_job">
		                    <div class="project_details margin-top-30">
		                        <?php echo '<h2>' . automotive_listing_get_option('project_details_title', '') . '</h2>' ?>
		                        <?php portfolio_details();	?>
		                    </div>
		                </div>
		            </div>
							<?php } ?>
	        </div>
        </div>
        </div>

<?php } else {

	if($portfolio_sidebar_disabled){
		$main_column_classes = 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 left-content xs-padding-bottom-40 sm-padding-bottom-40';
	} else {
		$main_column_classes = 'col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12 left-content xs-padding-bottom-40 sm-padding-bottom-40';
	} ?>
      	<div class='page-content'>
        	<div class="row">
                <div class="<?php echo $main_column_classes; ?>">
                    <?php

                    switch($format){
                        case "image":
                            if( has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                        break;

                        case "video":
	                        $video_id = $Automotive_Plugin->get_video_id($content);

	                        if($video_id){
		                        echo "<div class=\"video-container\">";

		                        if($video_id[0] == "youtube"){
			                        echo "<iframe class=\"portfolio_video " . $layout . "\" width=\"100%\" height=\"auto\" src=\"https://www.youtube.com/embed/" . $video_id[1] . "?rel=0\" allowfullscreen></iframe>";
		                        } elseif($video_id[0] == "vimeo"){
			                        echo "<iframe class=\"portfolio_video " . $layout . "\" width=\"100%\" height=\"auto\" src=\"https://player.vimeo.com/video/" . $video_id[1] . "?rel=0\" allowfullscreen></iframe>";
		                        } elseif($video_id[0] == "self_hosted"){
			                        echo do_shortcode("[video width=\"1000px\" height=\"auto\" mp4=\"" . $video_id[1] . "\"]");
		                        }
	                        } else {
		                        echo __( "Not a valid YouTube/Vimeo link", "listings" ) . "...";
	                        }

	                        echo "</div>";
                        break;

                        case "gallery":
							if(!empty($content)){
								echo "<!--OPEN OF SLIDER-->";
								echo "<div class=\"slider padding-left-none padding-right-none\">";
									echo "<div class=\"flexslider flexslider2\">";
										echo "<ul class=\"slides item\">";

	                  $i = 0;
	                  foreach($content as $image){
						        	$alt  = get_post_meta( $image, '_wp_attachment_image_alt', true);
											$image_src = $Automotive_Plugin->auto_image($image, "auto_portfolio", true);

	                    echo "<li>";
	                    echo (isset($links[$i]) && !empty($links[$i]) ? "<a href='" . esc_url($links[$i]) . "'>" : "");
	                    echo "<img src='" . esc_url($image_src) . "' alt='" . esc_attr($alt) . "' class='disable-lazy-load'>";
	                    echo (isset($links[$i]) && !empty($links[$i]) ? "</a>" : "");
	                    echo "</li>";

	                    $i++;
	                  }

										echo "</ul>";
									echo "</div>";
								echo "</div>";
								echo "<!--CLOSE OF SLIDER-->";
							} else {
								echo __("No images in slideshow", "listings");
							}
                        break;
                    } ?>
                </div>
								<?php if(!$portfolio_sidebar_disabled){ ?>
	                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 right-content">
	                    <div class="right_site_job">
	                        <div class="job sm-padding-bottom-40 xs-padding-bottom-40 xs-padding-top-30">
	                            <h2><?php echo automotive_listing_get_option('job_description_title', ''); ?></h2>
	                            <?php the_content(); ?>
	                        </div>
	                        <div class="project_details margin-top-30">
	                            <h2><?php echo automotive_listing_get_option('project_details_title', ''); ?></h2>
	                                <?php portfolio_details(); ?>
	                        </div>
	                    </div>
	                </div>
								<?php } ?>
            </div>
            </div>
<?php }
		echo "</div>";

		$default_sidebar = get_post_meta( $post->ID, "sidebar_area", true );

		if ( isset( $sidebar ) && ! empty( $sidebar ) && $sidebar != "none" && isset( $default_sidebar ) && ! empty( $default_sidebar ) ) {
			echo "<div class='" . $sidebar_class . " sidebar-widget side-content'>";
			dynamic_sidebar( $default_sidebar );
			echo "</div>";
		}



		if(automotive_listing_get_option('show_related_projects', false)){ ?>
		<div class="row margin-top-30">
			<div class="col-lg-12">
				<div class="project_wrapper clearfix margin-top-30">
						<?php portfolio_related('<h4 class="related_project_head margin-top-10 margin-top-none">' . automotive_listing_get_option('related_projects_title', '') . '</h4>'); ?>
				</div>
			</div>
		</div>
		<?php }
}

global $post;

$layout  = get_post_meta($post->ID, "layout", true);
$format  = get_post_meta($post->ID, "format", true);
$content = get_post_meta($post->ID, "portfolio_content", true);

echo "<div class='inner-page portfolio_content row'>";

portfolio_layout($layout); ?>

</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
