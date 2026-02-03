<?php
/*
	Automotive Google Map Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/google_map.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title; ?>
	<script>
        jQuery(document).ready( function($) {
            var map;
            var latlng = new google.maps.LatLng(<?php echo (isset($latitude) && !empty($latitude) ? $latitude : "-34.397"); ?>, <?php echo (isset($longitude) && !empty($longitude) ? $longitude : "150.644"); ?>);

            function initialize() {
                var mapOptions = {
                    zoom: <?php echo $zoom; ?>,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.<?php echo (isset($type) && !empty($type) ? strtoupper($type) : "ROADMAP"); ?>
                };
                map = new google.maps.Map(document.getElementById('<?php echo $rand_id; ?>'),
                    mapOptions);

                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
            }

            setTimeout(function(){
                $("#<?php echo $rand_id; ?>").height($("#<?php echo $rand_id; ?>").width());
                initialize();
            }, 500);
        });
	</script>
	<div id="<?php echo $rand_id; ?>" class='map-canvas'></div>
<?php
echo $after_widget;