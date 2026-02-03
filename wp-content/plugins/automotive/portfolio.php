<?php
add_action("init", "automotive_listing_portfolio_register", 0);
add_action("admin_init", "automotive_listing_add_portfolio_meta");
add_action("save_post", "automotive_listing_save_portfolio_details");

function automotive_listing_add_portfolio_meta(){
	add_meta_box("portfolio_format-meta", apply_filters("automotive_portfolio_format_text", "Format"), "portfolio_format", "listings_portfolio", "side", "core");
	add_meta_box("portfolio_content-meta", apply_filters("automotive_portfolio_portfolio_content_text", "Portfolio Content"), "content_box", "listings_portfolio", "normal", "core");
	add_meta_box("portfolio_project-details", apply_filters("automotive_portfolio_project_details_text", "Project Details"), "project_details", "listings_portfolio", "side", "core");
}

function automotive_listing_portfolio_register() {
  $args = array(
    'label'           => apply_filters("automotive_portfolio_label_text", __('Portfolio Items', 'listings') ),
    'singular_label'  => apply_filters("automotive_portfolio_singular_text", __('Project', 'listings') ),
    'public'          => true,
    'show_ui'         => true,
    'capability_type' => 'post',
    'hierarchical'    => false,
		'query_var'       => 'portfolio',
    'rewrite'         => array(
			'slug' => automotive_listing_get_option('portfolio_slug', 'listings_portfolio')
		),
    'supports'        => array('title', 'editor', 'thumbnail'),
		'labels'          => array(
			'not_found'    => apply_filters("automotive_portfolio_not_found_text", __('No portfolio items found', 'listings') ),
			'add_new_item' => apply_filters("automotive_portfolio_add_new_item_text", __('Add a new portfolio item', 'listings') )
		)
  );

  register_post_type( 'listings_portfolio' , apply_filters("automotive_portfolio_cpt_args", $args) );


	register_taxonomy(
		"project-type",
		array("listings_portfolio"),
		array(
			"hierarchical"   => false,
			"label"          => apply_filters("automotive_portfolio_categories_text", __("Categories", 'listings') ),
			"singular_label" => apply_filters("automotive_portfolio_category_text", __("Category", 'listings') )
		)
	);

	register_taxonomy(
		"portfolio_in",
		array("listings_portfolio"),
		array(
			"hierarchical"   => false,
			"label"          => apply_filters("automotive_portfolio_portfolios_text", __("Portfolios", 'listings') ),
			"singular_label" => apply_filters("automotive_portfolio_portfolio_text", __("Portfolio", 'listings') ),
			'labels'         => array(
				'add_new_item' => apply_filters("automotive_portfolio_add_new_portfolio_text", __('Add new Portfolio', 'listings') )
			)
		)
	);
}

function project_details(){
	global $post;

	$project_details   = get_post_meta($post->ID, "project_details", true);

	echo "<div class='project_details'>";

	if(isset($project_details) && !empty($project_details)){
		foreach($project_details as $project_detail){
			echo "<input type='text' name='project_details[]' class='widefat' value='" . htmlspecialchars($project_detail, ENT_QUOTES) . "'>";
		}
	} else {
		echo "<input type='text' name='project_details[]' class='widefat'>";
	}
	echo "<div class='new_details'></div>";
	echo "<span class='button btn add_detail'>" . __("Add A Detail", "listings") . "</span>";
	echo "<span class='button btn remove_detail'>" . __("Remove Last Detail", "listings") . "</span>";

	echo "</div>";
}

function content_box(){
	global $post;

	$format = get_post_meta($post->ID, "format", true);

	if(isset($format) && !empty($format)){
		automotive_listing_portfolio_editor($format);
	} else {
		echo "<h2 style='margin: 15px 0 15px 15px;'>";
		_e("Select a format form the right sidebar", "listings");
		echo "</h2>";
	}
}

function portfolio_format() {
	global $post;

	$format  = get_post_meta($post->ID, "format", true);
	$formats = array("image", "gallery", "video");
	$layout  = get_post_meta($post->ID, "layout", true);

	$short_description = get_post_meta($post->ID, "short_description", true);

	echo "<p>" . __("Short Description", "listings");
	echo "<input type='text' name='short_description' value='" . (isset($short_description) && !empty($short_description) ? $short_description : "") . "'></p><hr style='width: 108%; margin-left: -10px; border-top-color: #fff; border-bottom-color: #dfdfdf; border-top: 1px;'>";
	?>
    <div id="post-formats-select">
    	<?php foreach($formats as $single_format){ ?>
		<input type="radio" name="portfolio_post_format" class="portfolio-post-format" id="post-format-<?php echo $single_format; ?>" value="<?php echo $single_format; ?>" <?php checked($format, $single_format); ?> />
        <label for="post-format-<?php echo $single_format; ?>" class="post-format-icon post-format-<?php echo $single_format; ?>"><?php echo ucwords($single_format); ?></label><br />
        <?php } ?>
	</div>
    <hr style='width: 108%; margin-left: -10px; border-top-color: #fff; border-bottom-color: #dfdfdf; border-top: 1px;'>
    <p><b><?php _e("Layout", "listings"); ?></b></p>

    <select name="layout">
        <option value='wide' <?php selected($layout, "wide"); ?>><?php _e("Wide", "listings"); ?></option>
        <option value='split' <?php selected($layout, "split"); ?>><?php _e("Split", "listings"); ?></option>
    </select>
	<?php
}

function automotive_listing_save_portfolio_details() {
	if( "listings_portfolio" == get_post_type() ){
		global $post;

		$save_format = (isset($_POST["portfolio_post_format"]) ? $_POST["portfolio_post_format"] : null);
		//$post->ID  = (isset($post->ID) ? $post->ID : null);

		update_post_meta($post->ID, "format", $save_format);

		if(isset($_POST['portfolio_image']) && !empty($_POST['portfolio_image'])){
			update_post_meta($post->ID, "portfolio_content", $_POST['portfolio_image']);
		} elseif(isset($_POST['portfolio_video']) && !empty($_POST['portfolio_video'])){
			update_post_meta($post->ID, "portfolio_content", $_POST['portfolio_video']);
		} elseif(isset($_POST['gallery_images']) && !empty($_POST['gallery_images'])){
			$save_gallery_images = array();

			if(!empty($_POST['gallery_images'])){

				foreach($_POST['gallery_images'] as $gallery_image){
          $save_gallery_images[] = $gallery_image;
				}
			}

			update_post_meta($post->ID, "portfolio_content", $save_gallery_images);
		}

		if($save_format == "gallery" && empty($_POST['gallery_images'])) {
			update_post_meta( $post->ID, "portfolio_content", array() );
		}

		if(isset($_POST['project_details'])){
			update_post_meta($post->ID, "project_details", $_POST['project_details']);
		} else {
			delete_post_meta($post->ID, "project_details");
		}

    if(isset($_POST['short_description'])){
      update_post_meta($post->ID, "short_description", $_POST['short_description']);
    } else {
			delete_post_meta($post->ID, "short_description");
		}

    if(isset($_POST['portfolio_links'])){
      update_post_meta($post->ID, "portfolio_links", $_POST['portfolio_links']);
    } else {
			delete_post_meta($post->ID, "portfolio_links");
		}
	}
}

function automotive_listing_portfolio_editor($format = null){
	global $post, $Listing;

	if(is_ajax_request()){
		$format  = $_POST['format'];
		$post_id = (isset($_POST['post_id']) && !empty($_POST['post_id']) ? $_POST['post_id'] : "");
	} else {
		$post_id = $post->ID;
	}

    $content   = get_post_meta($post_id, "portfolio_content", true);
    $links     = get_post_meta($post_id, "portfolio_links", true);
	$og_format = get_post_meta($post_id, "format", true);

	$image        = __( "Image", "listings" );
	$change_image = __( "Change image", "listings" );
	$set_default  = __( "Set default image", "listings" );
	$delete_image = __( "Delete image", "listings" );
	$move_image   = __( "Move Image", "listings" );
	$no_images    = __( "No gallery images", "listings" );

	switch($format){
		case "image":
			echo __("Featured post will be used", "listings") . ".";
			break;

		case "gallery": ?>
        <div id="gallery_images" data-image="<?php echo $image; ?>"
               data-change-image="<?php echo $change_image; ?>" data-set-default="<?php echo $set_default; ?>"
               data-delete-image="<?php echo $delete_image; ?>" data-move-image="<?php echo $move_image; ?>"
               data-no-images="<?php echo $no_images; ?>">
			<?php
            if(isset($content) && !empty($content) && $og_format == "gallery"){
                $gallery_images = $content;

                $i = 1;

                foreach($gallery_images as $gallery_image){
                    $auto_thumb = wp_get_attachment_image_src($gallery_image, "auto_thumb");
                    ?>
                    <div class="single-gallery-image" data-id='<?php echo $i; ?>'>
                        <div class='top_header'>
			                <?php echo $image . " #" . $i; ?>
                        </div>

                        <div class='image_preview'><?php echo "<img src='" . $auto_thumb[0] . "'>"; ?></div>

                        <div class='buttons'>
                            <span class='button add_image_gallery'>
                                <span><?php echo $change_image; ?></span>
                                <i class="fa fa-pencil"></i>
                            </span>

                            <span class='button make_default_image'>
                                <span><?php echo $set_default; ?></span>
                                <i class="fa fa-hand-pointer-o"></i>
                            </span>

                            <span class='button delete_image'>
                                <span><?php echo $delete_image; ?></span>
                                <i class="fa fa-trash"></i>
                            </span>

                            <input type='text' name='portfolio_links[<?php echo $i; ?>]' value='<?php echo (isset($links[$i]) && !empty($links[$i]) ? $links[$i] : ""); ?>' placeholder='<?php _e("Image link", "listings"); ?>'>
                        </div>

                        <input type='hidden' name='gallery_images[]' value='<?php echo $gallery_image; ?>'>
                    </div>
                    <?php
					$i++;
				}
            } ?>
        </div>
        <button class='add_image button button-primary'><?php _e("Add Image", "listings"); ?></button>

        <div class='clear'></div>
        <?php
			break;

		case "video":
			echo __("Video URL", "listings") . ": <input id='portfolio_video' type='text' value='" . (isset($content) && !empty($content) && $og_format == "video" ? $content : "") . "' name='portfolio_video'><br>";
			echo "<div class='video_preview'>";

			if(isset($content) && !empty($content) && $og_format == "video"){
				$video_id = $Listing->get_video_id($content);

				if($video_id){
					echo "<br>";

					if($video_id[0] == "youtube"){
						echo "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/" . $video_id[1] . "\" allowfullscreen></iframe>";
					} elseif($video_id[0] == "vimeo"){
						echo "<iframe width=\"560\" height=\"315\" src=\"https://player.vimeo.com/video/" . $video_id[1] . "\" allowfullscreen></iframe>";
					}
				} else {
					echo __( "Not a valid YouTube/Vimeo link", "listings" ) . "...";
				}
			}

			echo "</div>";
			break;
	}

	if(is_ajax_request()){
		die;
	}
}
add_action('wp_ajax_portfolio_editor', 'automotive_listing_portfolio_editor');
