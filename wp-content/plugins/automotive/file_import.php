<?php

///// register page
function register_csv_menu_item() {
	$file_import_permission = automotive_listing_get_option('file-import-permission', 'manage_options');

  add_submenu_page( 'edit.php?post_type=listings', __('Automotive Listings Import', 'listings'), __('File Import', 'listings'), $file_import_permission, 'file-import', 'automotive_file_import' );
}
add_action('admin_menu', 'register_csv_menu_item');


/* errors
/* * * * */
function admin_errors_auto(){
    $error = (isset($_GET['error']) && !empty($_GET['error']) ? sanitize_text_field($_GET['error']) : "");

    if(!empty($error) || (isset($_SESSION['auto_csv']['error']) && !empty($_SESSION['auto_csv']['error']))){
        echo "<div class='error'><span class='error_text'>";

        if(isset($_SESSION['auto_csv']['error']) && !empty($_SESSION['auto_csv']['error'])){
            $return = "";
            foreach($_SESSION['auto_csv']['error'] as $key => $message){
                $return .= $message[1] . "<br>";
            }

            echo rtrim($return, "<br>");

            unset($_SESSION['auto_csv']['error']);
        } elseif($error == "file"){
            _e("The file uploaded wasn't a valid XML or CSV file, please try again.", "listings");
        } elseif($error == "url"){
            _e("The URL submitted wasn't a valid URL, please try again.", "listings");
        } elseif($error == "int_file"){
            _e("The file must not contain numbers as the column title.", "listings");
        } elseif($error == "empty"){
            _e("The file must contain listings.", "listings");
				}

        echo "</span></div>";
    }
}
add_action( 'admin_notices', 'admin_errors_auto' );

function automotive_array_map_recursive( $func, $arr ){
  $new_array = array();

  foreach( $arr as $key => $value ){
      $new_array[ $key ] = ( is_array( $value ) ? automotive_array_map_recursive( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
  }

  return $new_array;
}

function automotive_sanitize_utf8($string){
	if(function_exists('mb_strtolower')){
		return  preg_replace( '/\x{EF}\x{BF}\x{BD}/u', '', automotive_iconv( mb_detect_encoding( $string ), 'UTF-8//IGNORE', $string ) );
	} else {
		return  preg_replace( '/\x{EF}\x{BF}\x{BD}/u', '', automotive_iconv( 'ISO-8859-1', 'UTF-8', $string ) );
	}
}

function file_type_check_auto() {
    include_once(WP_PLUGIN_DIR . "/automotive/classes/class.import.php");
    $Auto_Import = new Auto_Import();

		if(isset($_GET['cancel']) && !empty($_GET['cancel'])){
			unset($_SESSION['auto_csv']['ajax_import']);
			delete_option('automotive_next_import_info');
		}

    if(isset($_POST['csv']) && !empty($_POST['csv'])){
        $rows = $_SESSION['auto_csv']['file_row'];

				// single XML listing workaround
				if(!isset($rows[0])){
					$temp_rows = $rows;

					$rows = array(
						$temp_rows
					);
				}

				$additional_options = array();

				$additional_options['overwrite_existing_listing_images'] = (isset($_POST['overwrite_existing_listing_images']) && !empty($_POST['overwrite_existing_listing_images']) ? "true" : "false");
				$additional_options['dont_overwrite_empty']              = (isset($_POST['dont_overwrite_empty']) && !empty($_POST['dont_overwrite_empty']) ? true : false);

				// $_SESSION['auto_csv']['import_results'] = $Auto_Import->insert_listings($rows, $additional_options);
				$_SESSION['auto_csv']['ajax_import'] = true;
				$_SESSION['auto_csv']['ajax_post']   = $_POST;

				update_option('automotive_next_import_info', array(
					'rows' 								=> automotive_array_map_recursive('automotive_sanitize_utf8', $rows),
					'additional_options' 	=> $additional_options
				));

				header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import' ));
				die;
    }

		// import logic for cron
    if(isset($_GET['cron_job']) && function_exists("automotive_auto_import")){
        $cron_jobs    = get_option("auto_import_options");
        $current_cron = (int)$_GET['cron_job'];

        if(isset($cron_jobs['crons'][$current_cron]) && !empty($cron_jobs['crons'][$current_cron])){
	        $file_content = $Auto_Import->get_file_contents("url", $cron_jobs['crons'][$current_cron]['server_import_url']);

	        if($file_content[0] == "success"){
		        $file_type  = $Auto_Import->get_file_type($file_content[1]);

						$import_parent = false;
		        $file_array = $Auto_Import->convert_file_content_to_array($file_type, $file_content[1], $import_parent);

		        // forward to import screen if passes
		        if($file_type == "csv"){

			        if($file_array[0] == "error"){
				        header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&error=file' ));
			        } else {
				        header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&import_file&cron=' . $current_cron ));
			        }
		        } else {
			        header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&' . $file_type . '&cron=' . $current_cron ));
		        }

	        }
        }
    }

		// manual import
    if(isset($_POST['url_automotive']) || isset($_POST['import_submit_auto'])){
        if(isset($_POST['url_automotive'])) {
            $file_content = $Auto_Import->get_file_contents("url", $_POST['url_automotive']);
        } else {
            $file_content = $Auto_Import->get_file_contents("file", $_FILES['import_upload']);
        }

        if($file_content[0] == "success"){
            $file_type  = $Auto_Import->get_file_type($file_content[1]);
            $file_array = $Auto_Import->convert_file_content_to_array($file_type, $file_content[1]);

            // forward to import screen if passes
            if($file_type == "csv"){

              if($file_array[0] == "error"){
                header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&error=' . ($file_array[1] == 'empty' ? 'empty' : 'file') ));
              } else {
                header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&import_file' ));
              }

            } elseif($file_type == "json"){
							header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&json' ));
						} else {
              header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&xml' ));
            }

        }

    } elseif(isset($_SESSION['auto_csv']['file_row']) && !empty($_SESSION['auto_csv']['file_row']) && !isset($_GET['import_file'])){
			$is_xml = (isset($_GET['xml']) && (!empty($_GET['xml']) || (int)$_GET['xml'] === 0) );

			$import_file_data = ($is_xml ? $_GET['xml'] : false);

			if(!$import_file_data){
				$import_file_data = (isset($_GET['json']) && (!empty($_GET['json']) || $_GET['json'] === "0") ? $_GET['json'] : false);
			}


			if($import_file_data !== false){
	      $file_array = $Auto_Import->convert_file_content_to_array($_SESSION['auto_csv']['file_type'], $_SESSION['auto_csv']['file_content'], $import_file_data);


	      if($file_array[0] == "error"){
	          header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&error=file' ));
	      } else {
	          header("Location: " . admin_url( 'edit.php?post_type=listings&page=file-import&' . ($is_xml ? 'xml' : 'json') . '=' . sanitize_text_field($import_file_data) . '&import_file' . (isset($_GET['cron_job']) ? "&cron=" . (int)$_GET['cron_job'] : "") ));
	      }
			}
    }
}
add_action( 'wp_loaded', 'file_type_check_auto' );

function automotive_file_import() {
	$Automotive_Plugin  = Automotive_Plugin();
	$listing_categories = $Automotive_Plugin->get_listing_categories();

	$is_cron = (isset($_GET['cron']));
	$is_xml  = (isset($_GET['xml']) && empty($_GET['xml']));
	// $is_json = (isset($_GET['json']) && (!empty($_GET['json']) && (int)$_GET['json'] !== 0));
	$is_json = (isset($_GET['json']) && $_GET['json'] === '');

	if(isset($_SESSION['auto_csv']['ajax_import']) && !empty($_SESSION['auto_csv']['ajax_import'])){

		$automotive_next_import_info = get_option('automotive_next_import_info');

		if(!$automotive_next_import_info){
			unset($_SESSION['auto_csv']);
		}

	}

	// extra spots
	$extra_spots = array(
		"vehicle_overview"          => array(__("Vehicle Overview", "listings"), 0),
		"technical_specifications"  => array(__("Technical Specifications", "listings"), 0),
		"other_comments"            => array(__("Other Comments", "listings"), 0),
		"gallery_images"            => array(__("Gallery Images", "listings"), 0),
		"price"                     => array(__("Price", "listings"), 1),
		"original_price"            => array(__("Original Price", "listings"), 1),
		"city_mpg"                  => array(__("City MPG", "listings"), 1),
		"highway_mpg"               => array(__("Highway MPG", "listings"), 1),
		"video"                     => array(__("Video", "listings"), 1),
		"features_and_options"      => array(__("Features and Options", "listings"), 0),
    "secondary_title"           => array(__("Secondary Title", "listings"), 1),
		"permalink"                 => array(__("Permalink", "listings"), 1),
		"latitude"                  => array(__("Latitude", "listings"), 1),
		"longitude"                 => array(__("Longitude", "listings"), 1),
		"listing_badge"             => array(__("Badge", "listings"), 1),
    "tax_label_listing"         => array(__("Tax Label on Listing Page", "listings"), 1),
    "tax_label_inventory"       => array(__("Tax Label on Inventory Page", "listings"), 1),
    "car_sold"                  => array(__("Car Sold", "listings"), 1),
    "pdf"                       => array(__("PDF", "listings"), 1)
	);

    include_once(WP_PLUGIN_DIR . "/automotive/classes/class.import.php");
    $Auto_Import = new Auto_Import();    ?>
    <div class="wrap auto_import">
        <h2 style="display: inline-block;"><?php _e("File Import", "listings"); echo ($is_cron ? ": CRON job" : ""); ?></h2>

        <?php if(isset($_GET['import_file']) && !$is_cron){ ?>
            <button class="submit_csv button button-primary" style="vertical-align: super"><?php _e("Import Listings", "listings"); ?></button>
        <?php } ?>

        <br>

        <?php
        if(isset($_SESSION['auto_csv']['ajax_import']) && !empty($_SESSION['auto_csv']['ajax_import'])){
            echo "<br>";
            // echo $_SESSION['auto_csv']['import_results'];

						$automotive_next_import_info = get_option('automotive_next_import_info');

						if($automotive_next_import_info){
						$total_listings = absint(count($automotive_next_import_info['rows']));
						?>

						<div class="ajax_listings_import" data-listing-total="<?php echo $total_listings; ?>" data-increment="<?php echo (100 / $total_listings); ?>" data-listing-post='<?php echo wp_json_encode($_SESSION['auto_csv']['ajax_post']); ?>' data-error-messge="<?php _e("Request failed: %s, you can refresh the page to continue importing the rest of the listings", "listings"); ?>">
							<div class="success auto_message hide">
                  <h3><?php _e('Successfully imported your listings', 'listings'); ?></h3>
              </div>

							<h2 class='importing_text'><?php echo sprintf( __('Importing listing %s of %s listings.', 'listings'), '<span class="current">0</span>', $total_listings); ?></h2>

							<div class="listing-import-progress"></div>

							<div class="percentage_complete">
								<span class="percent">0%</span>
							</div>

							<a class="button button-primary cancel-button" href="<?php echo add_query_arg("cancel", "true"); ?>"><?php _e("Delete Queued Import Data", "listings"); ?></a>

							<div class="imported_listings hide">
								<h3><?php _e("Imported Listings", "listings"); ?></h3>
								<ul></ul>
							</div>

							<div class="updated_listings hide">
								<h3><?php _e("Updated Listings", "listings"); ?></h3>
								<ul></ul>
							</div>
						</div>

						<?php

            // unset($_SESSION['auto_csv']);
					}
        } elseif($is_xml || $is_json){  ?>

            <div class="upload-plugin">

                <p class="install-help"><?php
								if($is_xml){
									_e("Choose which XML node contains each listings information.", "listings");
								} else {
									_e("Choose which JSON node contains each listings information.", "listings");
								} ?></p>

                <form method="get" class="wp-upload-form" action="">
                    <input type="hidden" name="post_type" value="listings">
                    <input type="hidden" name="page" value="file-import">
                    <?php if(isset($_GET['cron'])){ ?>
                        <input type="hidden" name="cron_job" value="<?php echo (int)$_GET['cron']; ?>">
                    <?php } ?>

                    <select name="json">
                    <?php
										$rows = $_SESSION['auto_csv']['file_row'];

										if($is_json){
											$default_xml_option = get_option("file_import_json_key");
										} else {
											$default_xml_option = get_option("file_import_xml_key");
										}

                    // single row fix
                    if(!isset($rows[0]) && $is_xml){
	                    $temp_rows = $rows;
	                    $rows = array();

	                    $rows[0] = $temp_rows;
                    }

                    $title_sort  = $Auto_Import->recursive_all_keys( $rows );
                    $xml_options = $Auto_Import->recursive_sortable_open( $rows, $title_sort );

                    if(!empty($xml_options)) {
	                    foreach ( $xml_options as $value => $option ) {
		                    echo "<option value='" . $value . "'" . selected( $value, $default_xml_option, false ) . ">" . $option . "</option>";
	                    }
                    } ?>
                    </select>

                    <button onclick="jQuery(this).closest('form').submit()" class="button"><?php _e("Import Now", "listings"); ?></button>
                </form>

            </div>

      <?php
			} elseif(isset($_GET['import_file'])){
				$is_xml_json = (isset($_SESSION['auto_csv']['file_type']) && ($_SESSION['auto_csv']['file_type'] == "xml" || $_SESSION['auto_csv']['file_type'] == "json") );
				$is_json     = (isset($_SESSION['auto_csv']['file_type']) && $_SESSION['auto_csv']['file_type'] == "json");
				$is_xml      = (isset($_SESSION['auto_csv']['file_type']) && $_SESSION['auto_csv']['file_type'] == "xml");
			?>

            <?php if(isset($rows) && count($rows) > 100){ ?>
            <div class="error">
                <span class="error_text"><?php _e("Please consider breaking the import file into multiple files, large imports may not import fully depending on your server's settings.", "listings"); ?></span>
            </div>
            <?php } ?>

            <p><?php _e("To import your listings simply drag and drop the left column items from your import file into the listing category boxes on the right hand side then click the above \"Import Listings\" button. For more information please refer to our Automotive Plugin Documentation.", "listings"); ?></p>

            <?php $Automotive_Plugin->automotive_message(
		        __( "Car Sold Setting", "listings" ),
		        __( "In order to mark cars as sold you can now configure the sold value that will be in your import file under Listings >> File Import Settings >> Sold Value.", "listings" ) ,
		        "info",
		        "car_sold_file_import"
	        ); ?>

            <br>

            <?php
            $assoc_val              = get_option("file_import_associations" . ($is_cron ? "_" . (int)$_GET['cron'] : ""));
            $associations           = ($assoc_val ? $assoc_val : array());
            $duplicate_check_val    = (isset($associations['duplicate_check']) && !empty($associations['duplicate_check']) ? $associations['duplicate_check'] : "");
		        $overwrite_existing_val = (isset($associations['overwrite_existing']) && !empty($associations['overwrite_existing']) ? $associations['overwrite_existing'] : "");
		        $dont_overwrite_empty   = (isset($associations['dont_overwrite_empty']) && !empty($associations['dont_overwrite_empty']) ? $associations['dont_overwrite_empty'] : "");

	        	$overwrite_existing_listing_images = (isset($associations['overwrite_existing_listing_images']) && !empty($associations['overwrite_existing_listing_images']) ? $associations['overwrite_existing_listing_images'] : "" ); ?>

            <ul id="csv_items" class="connectedSortable">
                <?php
                if($is_xml_json){
                  $rows   = $_SESSION['auto_csv']['file_row'];

	                // single row fix
	                if(!isset($rows[0]) && $is_xml){
		                $temp_rows = $rows;
		                $rows = array();

		                $rows[0] = $temp_rows;
	                }

	                $title_sort = $Auto_Import->recursive_all_keys( $rows );
	                $titles     = $Auto_Import->recursive_sortable( $rows, $title_sort, $associations, array() );

                } else {
                    if(isset($_SESSION['auto_csv']['titles']) && !empty($_SESSION['auto_csv']['titles'])){
                        $titles = $_SESSION['auto_csv']['titles'];
                    }
                }

                if(!empty($titles)){
                    foreach($titles as $key => $value){
                        $key = (is_int($key) ? $value : $key); // xml/csv name detection
                        if(!isset($associations['csv'][$key]) || (isset($associations['csv'][$key]) && (!isset($listing_categories[$associations['csv'][$key]]) && !isset($extra_spots[$associations['csv'][$key]])))){
                        	echo "<li class='ui-state-default'><input type='hidden' name='csv[" . $key .  "]' > " . $value . "</li>";
                        }
                    }
                }?>
            </ul>

            <form method="post" id="csv_import" data-one-per="<?php _e("Only one per list!", "listings"); ?>" data-nonce="<?php echo wp_create_nonce("automotive_csv_import"); ?>">
								<?php if(isset($_GET['cron'])){ ?>
		            	<input type="hidden" name="cron_job" value="<?php echo (int)$_GET['cron']; ?>">
								<?php } ?>

								<?php foreach($listing_categories as $key => $option) {
	                $needle         = $option['slug'];
	                $is_association = ( isset( $associations['csv'] ) && is_array( $associations['csv'] ) && array_search( $needle, $associations['csv'] ) ? true : false );

	                if(!isset($option['link_value']) || (isset($option['link_value']) && $option['link_value'] == "none")){ ?>
		                <fieldset class="category">
			                <legend><?php echo $option['singular']; ?></legend>

			                <ul class="listing_category connectedSortable" data-limit="1"
			                    data-name="<?php echo $key; ?>">
				                <?php

												if ( $is_association ) {
					                $values = array_keys( $associations['csv'], $needle );

					                if ( ( isset( $rows[0][ $values[0] ] ) || (is_array($titles) && array_search( $values[0], $titles ) !== false) || (is_array($titles) && isset($titles[$values[0]])) ) ) {
						                $label = ($is_xml_json && isset($titles[$values[0]]) ? $titles[$values[0]] : $values[0]);

						                echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . esc_attr($values[0]) . ']" value="' . $needle . '"> ' . $label . '</li>';
					                }
				                } ?>
			                </ul>
		                </fieldset>
	                <?php }
                }

                if($Automotive_Plugin->is_yoast_active()){
	                $extra_spots['_yoast_wpseo_metadesc'] = array(__("Yoast SEO Description", "listings"), 1);
	                $extra_spots['_yoast_wpseo_focuskw']  = array(__("Yoast Focus Keyword", "listings"), 1);
                }

                foreach($extra_spots as $key => $option){
                    $needle         = $key;
                    $is_association = (isset($associations['csv']) && is_array($associations['csv']) && array_search($needle, $associations['csv']) ? true : false); ?>
                    <fieldset class="category">
                        <legend><?php echo $option[0] . ($option[1] == 0 ? " <i class='fa fa-bars'></i>" : ""); ?></legend>

                        <ul class="listing_category connectedSortable" data-limit="<?php echo $option[1]; ?>" data-name="<?php echo str_replace(" ", "_", strtolower($key)); ?>">
                            <?php
														if($is_association){
                              $safe_val = $needle;
                              $values   = array_keys($associations['csv'], $safe_val);

                              foreach($values as $val_key => $val_val){
                                if( (isset($rows[0][$val_val])  || (is_array($titles) && array_search($val_val, $titles) !== false)  || (is_array($titles) && isset($titles[$val_val]))) ){
                                  $label = ($is_xml_json && isset($titles[$val_val]) ? $titles[$val_val] : $val_val);

                                  echo '<li class="ui-state-default ui-sortable-handle"><input type="hidden" name="csv[' . esc_attr($val_val) . ']" value="' . $safe_val . '"> ' . $label . '</li>';
                                }
                              }
                            } ?>
                        </ul>
                    </fieldset>
                <?php } ?>

                <br><br>

	            <?php _e("Title Values", "listings"); ?>:
	            <select class="multiselect title_values" multiple="multiple" name="title_from_values[]">
		            <?php
		            if($is_xml_json){
			            $rows   = $_SESSION['auto_csv']['file_row'];

			            // single row fix
			            if(!isset($rows[0])){
				            $temp_rows = $rows;
				            $rows = array();

				            $rows[0] = $temp_rows;
			            }

			            $titles = $yoast_titles = $Auto_Import->recursive_sortable($rows[0], $rows[0], $associations);
		            } else {
			            if(isset($_SESSION['auto_csv']['titles']) && !empty($_SESSION['auto_csv']['titles'])){
				            $titles = $yoast_titles = $_SESSION['auto_csv']['titles'];
			            }
		            }

		            if(!empty($titles)){
			            if(!isset($associations['title_from_values']) || empty($associations['title_from_values'])){
				            $associations['title_from_values'] = array();
			            }

			            // do associations first if they exist so the order is preserved
			            if(!empty($associations['title_from_values'])){
				            foreach($associations['title_from_values'] as $title_value){

				            	if(in_array($title_value, $titles)) {
						            echo "<option value='" . $title_value . "' selected='selected'>" . $title_value . "</option>";

						            $title_index = array_search( $title_value, $titles );
						            if ( $title_index !== false ) {
							            unset( $titles[ $title_index ] );
						            }
					            }
				            }
			            }

			            foreach($titles as $key => $value){
				            $option_value = ($is_xml_json ? $key : $value);

				            echo "<option value='" . $option_value .  "'" . (in_array($option_value, $associations['title_from_values']) ? " selected='selected'" : "") . ">" . $value . "</option>\n";
			            }
		            }
		            ?>
	            </select>

	            <p style="font-style: italic;"><?php _e("Generate the listing title from multiple values in the import file", "listings"); ?>.</p>

	            <hr><br>

	            <?php if($Automotive_Plugin->is_yoast_active()){ ?>

		            <?php _e("Yoast SEO Title Values", "listings"); ?>:
		            <select class="multiselect yoast_seo_values" multiple="multiple" name="yoast_title_from_values[]">
			            <?php
			            if(!empty($yoast_titles)){
				            if(!isset($associations['yoast_title_from_values']) || empty($associations['yoast_title_from_values'])){
					            $associations['yoast_title_from_values'] = array();
				            }

				            // do associations first if they exist so the order is preserved
				            if(!empty($associations['yoast_title_from_values'])){
					            foreach($associations['yoast_title_from_values'] as $title_value){
						            echo "<option value='" . $title_value . "' selected='selected'>" . $title_value . "</option>";

						            $title_index = array_search( $title_value, $yoast_titles );
						            if($title_index !== false) {
							            unset( $yoast_titles[ $title_index ] );
						            }
					            }
				            }

				            foreach($yoast_titles as $key => $value){
					            $option_value = ($is_xml_json ? $key : $value);

					            echo "<option value='" . $option_value .  "'" . (in_array($option_value, $associations['yoast_title_from_values']) ? " selected='selected'" : "") . "> " . $value . "</option>\n";
				            }
			            }
			            ?>
		            </select>

		            <p style="font-style: italic;"><?php _e("Generate the Yoast SEO title from multiple values in the import file", "listings"); ?>.</p>

		            <br>

	            <?php } ?>

                <?php _e("Check for duplicate listings using", "listings"); ?>:
                <select name="duplicate_check">
                    <option value="none"><?php _e("None", "listings"); ?></option>
                  <option value="title" <?php selected( "title", $duplicate_check_val, true ) ?>><?php _e("Title", "listings"); ?></option>
                  <option value="secondary_title" <?php selected( "secondary_title", $duplicate_check_val, true ) ?>><?php _e("Secondary Title", "listings"); ?></option>
                    <?php
                    foreach($listing_categories as $key => $option){
                        $val = $option['slug'];
                        echo "<option value='" . $val . "' " . selected( $val, $duplicate_check_val, false ) . ">" . $option['singular'] . "</option>";
                    } ?>
                </select>&nbsp;&nbsp;&nbsp;
                <label><input type="checkbox" name="overwrite_existing" value="on" <?php echo (!empty($overwrite_existing_val) ? "checked='checked'" : ""); ?>> <?php _e("Overwrite duplicate listings with new data", "listings"); ?></label>

	            <br><br>

	            <div class="overwrite_existing_listing_images" style="display: <?php echo ((isset($duplicate_check_val) && $duplicate_check_val != "none") ? "block" : "none"); ?>;">
					<label><input type="checkbox" name="overwrite_existing_listing_images" value="on" <?php echo ((isset($overwrite_existing_listing_images) && $overwrite_existing_listing_images == "on") ? "checked='checked'" : ""); ?>> <?php _e("Overwrite images on existing listings", "listings"); ?></label>

		            <br>

                    <label><input type="checkbox" name="dont_overwrite_empty" value="on" <?php echo (!empty($dont_overwrite_empty) ? "checked='checked'" : ""); ?>> <?php _e("Don't overwrite empty values on existing listings", "listings"); ?></label>

                    <br><br>
	            </div>

                * <i class="fa fa-bars"></i> <?php _e("Categories with this symbol can contain multiple values", "listings"); ?>

	            <br><br>

                <button class="save_import_categories button button-primary"><?php echo ($is_cron ? __("Save the above CRON associations", "listings") : __("Save the above associations", "listings") ); ?></button>



            </form>
        <?php } else { ?>
            <div class="upload-plugin">
                <p class="install-help"><?php _e("If you have a listing data in a .csv, .xml, or .json file format, you may import it by uploading it here.", "listings"); ?></p>

                <form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="import_upload">
                    <input type="hidden" name="post_type" value="listings">
                    <input type="hidden" name="page" value="file-import">
                    <input type="hidden" name="file" value="uploaded">

                    <label class="screen-reader-text" for="import_upload"><?php _e("Listing file", "listings"); ?></label>
                    <input type="file" id="import_upload" name="import_upload">
                    <input type="submit" name="import_submit_auto" id="install-plugin-submit" class="button" value="<?php _e("Import Now", "listings"); ?>" disabled="">
                </form>
            </div>


            <div class="upload-plugin">
                <p class="install-help"><?php _e("If you have a link to a .csv, .xml, or .json listing file, you may import it by pasting the URL it here.", "listings"); ?></p>

                <form method="post" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="import_url">
                    <input type="hidden" name="post_type" value="listings">
                    <input type="hidden" name="page" value="file-import">
                    <input type="hidden" name="file" value="uploaded">

                    <label class="screen-reader-text" for="pluginzip"><?php _e("Listing file", "listings"); ?></label>
                    <input type="text" name="url_automotive" placeholder="<?php _e("URL to csv, xml, or json", "listings"); ?>" style="width: 70%;">
                    <button onclick="jQuery(this).closest('form').submit()" class="button"><?php _e("Import Now", "listings"); ?></button>
                </form>
            </div>
        <?php } ?>

    </div>
<?php }

function save_import_categories(){
    if(isset($_POST['form']) && !empty($_POST['form'])){

        $nonce       = (isset($_POST['nonce']) && !empty($_POST['nonce']) ? $_POST['nonce'] : "");
	    $option_name = "file_import_associations";

        if(wp_verify_nonce($nonce, "automotive_csv_import")) {
	        parse_str( $_POST['form'], $form );

	        // cron job
            if(isset($form['cron_job'])){
	            $cron_option_name = $option_name . "_" . (int)$form['cron_job'];

                unset($form['cron_job']);

	            update_option( $cron_option_name, $form );
            }

	        update_option( $option_name, $form );

	        _e("Saved", "listings");
        } else {
            _e("Nonce not valid, clear your cache and try again", "listings");
        }
    }

    die;
}
add_action("wp_ajax_save_import_categories", "save_import_categories");
add_action("wp_ajax_nopriv_save_import_categories", "__return_false");

function automotive_import_scripts() {
    wp_enqueue_script( 'jquery-ui' );
    wp_enqueue_script( 'jquery-ui-sortable' );
}

add_action( 'wp_enqueue_scripts', 'automotive_import_scripts' ); ?>
