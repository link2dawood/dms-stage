<?php
/*
	Automotive Twitter Feed Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/twitter_feed.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;  ?>
	<div class='twitterfeed' data-modpath="<?php echo esc_attr(JS_DIR . "twitter/"); ?>" data-count="<?php echo $tweets; ?>" data-loading-text="<?php esc_html_e("Loading twitter feed", "listings"); ?>..." data-username="<?php esc_html_e($username); ?>"></div>
	<?php
echo $after_widget;