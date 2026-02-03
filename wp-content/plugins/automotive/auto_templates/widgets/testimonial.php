<?php
/*
	Automotive Testimonial Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/testimonial.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo $before_widget;
echo $before_title . $title . $after_title;

$field_and_value = explode("&", $fields);
$field_and_value = array_chunk($field_and_value, 2);

$widget = array();

if(!empty($field_and_value) && !empty($field_and_value[0]) && !empty($field_and_value[0][0])){
	foreach($field_and_value as $values){
		$explode  = explode("=", $values[0]);
		$explode2 = explode("=", $values[1]);

		$name = $explode[1];
		$text = $explode2[1];

		array_push($widget, array('name' => urldecode($name), 'content' => urldecode($text)));
	}

	echo testimonial_slider("", $widget);
}

echo $after_widget;