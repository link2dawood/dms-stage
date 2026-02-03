<?php
/*
	Automotive Video Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/video.php

	Version: 16.6
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
global $post;

echo $before_widget;

echo (!empty($title) ? $before_title . $title . $after_title : "");

$display_vehicle_video = automotive_listing_get_option('display_vehicle_video', true);

if($display_vehicle_video){
	$Automotive_Listing = new Automotive_Listing($post->ID);

	if ( !empty( $Automotive_Listing->get_video() ) ) {

		$video_details = $Automotive_Listing->get_video();

		if(!empty($video_details)){
			echo "<br>";

			$youtube_args          = array();
			$youtube_video_options = automotive_listing_get_option('youtube_video_options', array());

			if(!empty($youtube_video_options)){
				$is_rel             = (isset($youtube_video_options['rel']) && !empty($youtube_video_options['rel']) ? $youtube_video_options['rel'] : "");
				$is_auto_play       = (isset($youtube_video_options['auto_play']) && !empty($youtube_video_options['auto_play']) ? $youtube_video_options['auto_play'] : "");
				$is_player_controls = (isset($youtube_video_options['player_controls']) && !empty($youtube_video_options['player_controls']) ? $youtube_video_options['player_controls'] : "");
				$is_title_actions   = (isset($youtube_video_options['title_actions']) && !empty($youtube_video_options['title_actions']) ? $youtube_video_options['title_actions'] : "");
				$is_privacy         = (isset($youtube_video_options['privacy']) && !empty($youtube_video_options['privacy']) ? $youtube_video_options['privacy'] : "");
			}

			if(!$is_rel){
				$youtube_args['rel'] = 0;
			}

			if($is_auto_play){
				$youtube_args['autoplay'] = 1;
			}

			if(!$is_player_controls){
				$youtube_args['controls'] = 0;
			}

			if(!$is_title_actions){
				$youtube_args['showinfo'] = 0;
			}

			if($video_details[0] == "youtube"){
				echo "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube" . ($is_privacy ? "-nocookie" : "") . ".com/embed/" . $video_details[1] . "?" . http_build_query($youtube_args) . "\" allowfullscreen></iframe>";
			} elseif($video_details[0] == "vimeo"){
				echo "<iframe width=\"560\" height=\"315\" src=\"https://player.vimeo.com/video/" . $video_details[1] . "\"  allowfullscreen></iframe>";
			} elseif($video_details[0] == "rumble"){
				echo "<iframe width=\"560\" height=\"315\" src=\"https://rumble.com/embed/" . $video_details[1] . "\" allowfullscreen></iframe>";
			} elseif($video_details[0] == "self_hosted"){
				echo do_shortcode("[video width=\"600\" height=\"480\" mp4=\"" . $video_details[1] . "\"]");
			}
		} else {
			echo __( "Not a valid YouTube/Vimeo/Self Hosted link", "listings" ) . "...";
		}
	}
}

echo $after_widget;
