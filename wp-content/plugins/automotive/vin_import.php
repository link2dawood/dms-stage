<?php
function register_vin_menu_item() {
	global $lwp_options;

	add_submenu_page( 'edit.php?post_type=listings', __( 'VIN Import', 'listings' ), __( 'VIN Import', 'listings' ), ( isset( $lwp_options['vin-import-permission'] ) && ! empty( $lwp_options['vin-import-permission'] ) ? $lwp_options['vin-import-permission'] : "manage_options" ), 'vin-import', 'automotive_vin_import' );
}

add_action( 'admin_menu', 'register_vin_menu_item' );

function vin_test() {
	if ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ) {
		$vin  = ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? $_GET['vin'] : "" );
		$body = get_vin_info( $vin );

		if ( isset( $body->errorType ) && ! empty( $body->errorType ) && ! isset( $_GET['error'] ) ) {
			header( "Location: " . add_query_arg( "error", "" ) );
		}
	} elseif ( isset( $_GET['vin'] ) ) {
		// nothing return, lets check the additional fields
		global $VIN_Import;

		if ( isset( $VIN_Import->current_api->alternate_fields ) && ! empty( $VIN_Import->current_api->alternate_fields ) ) {
			$alternate_fields = $VIN_Import->current_api->alternate_fields;

			foreach ( $alternate_fields as $name => $placeholder ) {
				$value = ( isset( $_GET[ $name ] ) && ! empty( $_GET[ $name ] ) ? $_GET[ $name ] : "" );
				$body  = $VIN_Import->current_api->get_vin_info( $value, $name );

				if ( ! empty( $body ) ) {
					header( "Location: " . add_query_arg( "vin", $value, remove_query_arg( $name ) ) );

					break;
				}
			}
		}
	}
}

add_action( 'init', 'vin_test', 20 );

function get_vin_info( $vin ) {
	global $VIN_Import;

	return $VIN_Import->get_vin_info( $vin );
}

function auto_object_to_array( $obj ) {
	if ( is_object( $obj ) ) {
		$obj = (array) $obj;
	}
	if ( is_array( $obj ) ) {
		$new = array();
		foreach ( $obj as $key => $val ) {
			$new[ $key ] = auto_object_to_array( $val );
		}
	} else {
		$new = $obj;
	}

	return $new;
}

/* errors
/* * * * */
function admin_errors_vin() {
	$vin = ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? $_GET['vin'] : "" );

	if ( ! empty( $vin ) && isset( $_GET['error'] ) ) {
		$body = get_vin_info( $vin );

		if ( isset( $body->errorType ) && ! empty( $body->errorType ) ) {
			echo "<div class='error'><span class='error_text'>";

			echo "Error: " . $body->message . "<br>";

			echo "</span></div>";
		}
	}
}

add_action( 'admin_notices', 'admin_errors_vin' );

function automotive_vin_import() {
	global $lwp_options, $Listing, $VIN_Import, $wpdb;

	$update_listing = false;

	$is_import 				= (isset( $_POST['import'] ) && ! empty( $_POST['import'] ));
	$vin 							= ( isset( $_GET['vin'] ) && ! empty( $_GET['vin'] ) ? $_GET['vin'] : "" );
	$duplicate_check 	= ( isset( $_GET['duplicate_check'] ) && ! empty( $_GET['duplicate_check'] ) ? $_GET['duplicate_check'] : "" );

	if ( ! empty( $vin ) ) {
		$body = get_vin_info( $vin );
		$body = auto_object_to_array( $body );

		// we grab title here just in case the duplicate check is by title
		if($is_import){
			$import 		= $_POST['import'];
			$post_title = "";

			if ( isset( $import['title'] ) && ! empty( $import['title'] ) ) {
				foreach ( $import['title'] as $title ) {
					$title       = $VIN_Import->get_vin_value( $title, $body, 'title' );
					$post_title .= sanitize_text_field( $title ) . " ";
				}
			}

			$post_title = trim( $post_title );
		}

		// update duplicate check to save a step next import
		update_option('vin_import_duplicate_check', $duplicate_check);

		if($duplicate_check == 'title'){
			$check_for_vin = get_page_by_title($post_title, OBJECT, 'listings');

			if($check_for_vin->ID){
				$update_listing = $check_for_vin->ID;
			}
		} else {
			$check_for_vin = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='" . sanitize_key($duplicate_check) . "' AND meta_value='" . $vin . "'" );

			if(!empty($check_for_vin[0]) && !empty($check_for_vin[0]->post_id)){
				$update_listing = $check_for_vin[0]->post_id;
			}
		}
	} ?>

    <div class="wrap auto_import">
        <h2 style="display: inline-block;"><?php echo esc_html(__( "VIN Import", "listings" )); ?></h2>

				<?php if ( ! empty( $vin ) && ! isset( $_POST['import'] ) && ! isset( $_GET['error'] ) ) { ?>
		      <button class='button button-primary' onclick="jQuery('form[name=vin_import_form]').submit()" style="vertical-align: super;">
						<?php echo esc_html($update_listing ? sprintf(__( "Update Listing: %s", "listings" ), get_the_title($update_listing)) : __( "Import Listing", "listings" ) ); ?>
					</button>
				<?php } ?>

        <br>

		<?php

		if ( $is_import ) {

			$post_content = "";
			if ( isset( $import['vehicle_overview'] ) && ! empty( $import['vehicle_overview'] ) ) {
				foreach ( $import['vehicle_overview'] as $overview ) {
					$overview      = $VIN_Import->get_vin_value( $overview, $body, 'vehicle_overview' );
					$post_content .= sanitize_text_field( $overview ) . " ";
				}
			}

			$post_content = trim( $post_content );

			$dependancy_categories = array();

			if(!$update_listing){
				// insert post, get id
				$insert_info = array(
					'post_type'    => "listings",
					'post_title'   => $post_title,
					'post_content' => $post_content,
					'post_status'  => "publish"
				);

				$insert_id = wp_insert_post( $insert_info );
			} else {
				$insert_id = $update_listing;
				$update_post_params = array( 'ID' => $insert_id );

				if(!empty($post_title)){
					$update_post_params['post_title'] = $post_title;
				}

				if(!empty($post_content)){
					$update_post_params['post_content'] = $post_content;
				}

				wp_update_post($update_post_params);
			}

			// if success :)
			if ( $insert_id ) {

				$listing_categories_safe = $listing_categories = $Listing->get_listing_categories( true );

				// add
				$listing_categories['Technical Specifications'] = array();
				$listing_categories['Other Comments']           = array();

				foreach ( $listing_categories as $key => $category ) {
					$value    = "";
					$safe_key = ( isset( $category['slug'] ) && ! empty( $category['slug'] ) ? $category['slug'] : str_replace( " ", "_", strtolower( $key ) ) );

					if ( isset( $import[ $safe_key ] ) && ! empty( $import[ $safe_key ] ) && is_array( $import[ $safe_key ] ) ) {
						foreach ( $import[ $safe_key ] as $key => $import_value ) {
							$value .= ($safe_key == 'technical_specifications' && isset($_POST[$import_value]) && !empty($_POST[$import_value]) ? esc_html($_POST[$import_value]) . ": " : "") . $VIN_Import->get_vin_value( $import_value, $body, $safe_key ) . "<br> ";
						}
					} elseif ( isset( $import[ $safe_key ] ) && ! empty( $import[ $safe_key ] ) ) {
						$value = $VIN_Import->get_vin_value( $import[ $safe_key ], $body, $safe_key );
					}

					if ( ! empty( $value ) ) {
						update_post_meta( $insert_id, $safe_key, $value );
						$dependancy_categories[ $safe_key ] = array( $Listing->slugify( $value ) => $value );
					}

					$terms = ( isset( $listing_categories_safe[ $key ]['terms'] ) && ! empty( $listing_categories_safe[ $key ]['terms'] ) ? $listing_categories_safe[ $key ]['terms'] : array() );
					//compare_value
					if ( ! in_array( $value, $terms ) && ! empty( $value ) && isset( $category['compare_value'] ) && $category['compare_value'] == "=" ) {
						$listing_categories_safe[ $key ]['terms'][ $Listing->slugify( $value ) ] = $value;
					}
				}

				// gallery images
				$gallery_values = ( isset( $import['gallery_images'] ) && ! empty( $import['gallery_images'] ) ? $import['gallery_images'] : "" );
				$gallery_images = array();

				if ( ! empty( $gallery_values ) ) {
					foreach ( $gallery_values as $val ) {
						$val = $VIN_Import->get_vin_value( $val, $body, 'gallery_images' );

						if ( filter_var( $val, FILTER_VALIDATE_URL ) ) {
							$gallery_images[] = $VIN_Import->get_upload_image( $val );
						}
					}
				}

				if ( ! empty( $gallery_images ) ) {
					update_post_meta( $insert_id, "gallery_images", $gallery_images );
				}

				// Features & Options
				$features_and_options = ( isset( $import['features_and_options'] ) && ! empty( $import['features_and_options'] ) ? $import['features_and_options'] : "" );

				if ( ! empty( $features_and_options ) ) {
					$options                 = $listing_categories_safe['options']['terms'];
					$listing_feature_options = array();

					foreach ( $features_and_options as $option ) {
						$option = $VIN_Import->get_vin_value( $option, $body, 'features_and_options' );
						$option = trim( $option );
						$option = preg_replace( '/\x{EF}\x{BF}\x{BD}/u', '', automotive_iconv( mb_detect_encoding( $option ), 'UTF-8', $option ) );

						$listing_feature_options[ $Listing->slugify( $option ) ] = $option;

						if ( ! in_array( $option, $options ) ) {
							$listing_categories_safe['options']['terms'][ $Listing->slugify( $option ) ] = $option;
						}
					}

					update_post_meta( $insert_id, "multi_options", $listing_feature_options );
				}

				$default_video 					= "";
				$default_price 					= "";
				$default_original_price = "";
				$default_city_mpg 			= "";
				$default_highway_mpg 		= "";
				$default_badge 					= "";
				$default_tax_inside			= "";
				$default_tax_page 			= "";

				if($update_listing){
					$existing_post_options = get_post_meta($insert_id, "listing_options", true);

					if($existing_post_options){
						$existing_post_options = unserialize($existing_post_options);

						$default_video 					= (isset($existing_post_options['video']) && !empty($existing_post_options['video']) ? $existing_post_options['video'] : "");
						$default_price					= (isset($existing_post_options['price']) && !empty($existing_post_options['price']) ? $existing_post_options['price']['value'] : "");
						$default_original_price	= (isset($existing_post_options['price']['original']) && !empty($existing_post_options['price']['original']) ? $existing_post_options['price']['original'] : "");

						$default_city_mpg 		= (isset($existing_post_options['city_mpg']['value']) && !empty($existing_post_options['city_mpg']['value']) ? $existing_post_options['city_mpg']['value'] : "");
						$default_highway_mpg 	= (isset($existing_post_options['highway_mpg']['value']) && !empty($existing_post_options['highway_mpg']['value']) ? $existing_post_options['highway_mpg']['value'] : "");

						if(!empty($existing_post_options['custom_tax_inside'])){
							$default_tax_inside = $existing_post_options['custom_tax_inside'];
						}

						if(!empty($existing_post_options['custom_tax_page'])){
							$default_tax_page = $existing_post_options['custom_tax_page'];
						}

						if(!empty($existing_post_options['custom_badge'])){
							$default_badge = $existing_post_options['custom_badge'];
						}
					}
				}

				$video       		= ( isset( $import['video'] ) && ! empty( $import['video'] ) ? $VIN_Import->get_vin_value( $import['video'], $body, 'video' ) : $default_video );
				$original_price = $default_original_price;
				$price       		= ( isset( $import['price'] ) && ! empty( $import['price'] ) ? $VIN_Import->get_vin_value( $import['price'], $body, 'price' ) : $default_price );
				$city_mpg    		= ( isset( $import['city_mpg'] ) && ! empty( $import['city_mpg'] ) ? $VIN_Import->get_vin_value( $import['city_mpg'], $body, 'city_mpg' ) : $default_city_mpg );
				$highway_mpg 		= ( isset( $import['highway_mpg'] ) && ! empty( $import['highway_mpg'] ) ? $VIN_Import->get_vin_value( $import['highway_mpg'], $body, 'highway_mpg' ) : $default_highway_mpg );

				// other categories
				$post_options = array(
					"video"       => $video,
					"price"       => array(
						"value" 		=> $price,
						"original" 	=> $original_price
					),
					"city_mpg"    => array(
						"value" => $city_mpg
					),
					"highway_mpg" => array(
						"value" => $highway_mpg
					),
					"custom_badge" 			=> $default_badge,
					"custom_tax_inside" => $default_tax_inside,
					"custom_tax_page"   => $default_tax_page
				);

				update_post_meta( $insert_id, "listing_options", serialize( $post_options ) );

				// default history image
				if ( isset( $lwp_options['default_vehicle_history']['on'] ) && $lwp_options['default_vehicle_history']['on'] == "1" ) {
					update_post_meta( $insert_id, "verified", "yes" );
				}

				$Listing->update_listing_categories( $listing_categories_safe );

				// update car_sold
				update_post_meta( $insert_id, "car_sold", 2 );

				$Listing->update_dependancy_option( $insert_id, $dependancy_categories );

				if($update_listing){
					_e( "Congratulations, you successfully updated listing: ", "listings" );
				} else {
					_e( "Congratulations, you successfully imported this listing: ", "listings" );
				}

				if(empty($post_title) && $update_listing){
					$post_title = get_the_title($insert_id);
				}

				echo "<a href='" . esc_url( get_permalink( $insert_id ) ) . "'>" . ( ! empty( $post_title ) ? esc_html( $post_title ) : __( "Untitled", "listings" ) ) . "</a>";
			} else {
				_e( "Error importing your listing", "listings" );
			}
		} else { ?>

			<?php if ( ! empty( $vin ) && ! isset( $_GET['error'] ) ) {
				echo "<p>" . __( "To import your listings simply drag and drop the left column items returned from the API into the listing category boxes on the right hand side then click the above \"Import Vehicle\" button. For more information please refer to our Automotive Plugin Documentation.", "listings" ) . "</p><br>";

				echo '<ul id="items" class="form_value ui-sortable">';
				if ( ! empty( $body ) ) {

					echo $VIN_Import->display_options( $body );

				}
				// recursive_get_all_values($body);
				echo '</ul>';

				$vin_import_var = get_option( "vin_import_associations" );

				if ( ! empty( $vin_import_var ) ) {
					$vin_import_associations = ( ! empty( $vin_import_var ) ? $vin_import_var : "" );
					$vin_import_associations = $vin_import_associations['import'];
				} else {
					$vin_import_associations = array();
				}
				?>

        <form method="post" action="" name="vin_import_form" id="vin_import_form" data-nonce="<?php echo wp_create_nonce( "automotive_vin_import" ); ?>" data-import-label="<?php echo esc_attr(__("Import Label", "listings")); ?>">
					<?php
					$categories = $Listing->get_listing_categories();

					foreach ( $categories as $key => $value ) {
						$safe_name = $value['slug'];

						echo "<fieldset class='category'>";
						echo "<legend>" . $value['singular'] . "</legend>";

						echo "<ul class='listing_category form_value' data-name='" . $safe_name . "' data-limit='1'>";

						vin_import_list_item( $safe_name, $vin_import_associations, $body, $vin_import_var );

						echo "</ul>";

						echo "</fieldset>";
					}

					// extra spots
					$extra_spots = array(
						__( "Title", "listings" )                    => 0,
						__( "Vehicle Overview", "listings" )         => 0,
						__( "Technical Specifications", "listings" ) => 0,
						__( "Other Comments", "listings" )           => 0,
						__( "Gallery Images", "listings" )           => 0,
						__( "Price", "listings" )                    => 1,
						__( "Original Price", "listings" )           => 1,
						__( "City MPG", "listings" )                 => 1,
						__( "Highway MPG", "listings" )              => 1,
						__( "Video", "listings" )                    => 1,
						__( "Features and Options", "listings" )     => 0
					);

					foreach ( $extra_spots as $key => $option ) {
						$safe_name = str_replace( " ", "_", strtolower( $key ) ); ?>

            <fieldset class="category">
                <legend><?php echo $key . ( $option == 0 ? " <i class='fa fa-bars'></i>" : "" ); ?></legend>

                <ul class="listing_category form_value" data-limit="<?php echo $option; ?>" data-name="<?php echo $safe_name; ?>">
									<?php vin_import_list_item( $safe_name, $vin_import_associations, $body, $vin_import_var, $option ); ?>
                </ul>
            </fieldset><?php
					} ?>

            <br><br>

            *
            <i class="fa fa-bars"></i> <?php _e( "Categories with this symbol can contain multiple values", "listings" ); ?>

            <br><br>

            <button class="save_vin_import_categories button button-primary"><?php _e( "Save the above associations", "listings" ); ?></button>
          </form>

			<?php } else { ?>

				<?php

				if ( $VIN_Import->valid() ) {

					$listing_categories  = $Listing->get_listing_categories();
					$duplicate_check_val = get_option('vin_import_duplicate_check');
					?>

          <div class="upload-plugin vin-importer">
              <form method="GET" class="wp-upload-form" action="" name="import_url">
                  <input type="hidden" name="post_type" value="listings">
                  <input type="hidden" name="page" value="vin-import">

                  <input type="text" name="vin" placeholder="<?php _e( "VIN #", "listings" ); ?>">

									<?php $VIN_Import->call( "alternate_fields" ); ?>

                  <br>

									<?php echo esc_html( __("Check for duplicates using", "listings") ); ?>:<br>
									<select name="duplicate_check">
	                    <option value="none"><?php _e("None", "listings"); ?></option>
	                    <option value="title" <?php selected( "title", $duplicate_check_val, true ) ?>><?php _e("Title", "listings"); ?></option>
	                    <?php
	                    foreach($listing_categories as $key => $option){
	                        $slug = $option['slug'];
	                        echo "<option value='" . $slug . "' " . selected( $slug, $duplicate_check_val, false ) . ">" . $option['singular'] . "</option>";
	                    } ?>
	                </select>

									<br>
									<br>

                  <button onclick="jQuery(this).closest('form').submit()" class="button">
										<?php echo esc_html( __( "Get vehicle details", "listings" ) ); ?>
									</button>
              </form>
          </div>

				<?php } else { ?>

                    <a href="<?php echo admin_url( "admin.php?page=listing_wp&tab=8" ); ?>"><?php _e( "Please setup the API info under Listing Options >> VIN Import Settings.", "listings" ); ?></a>

				<?php } ?>


			<?php } ?>

		<?php } ?>

    </div>

    <script>
      jQuery(document).ready(function ($) {
        var list_html;
        var open_parents = [];
        var open_children = [];

				var has_import_label = <?php echo ($VIN_Import->has_import_label ? 'true' : 'false'); ?>;

        $("#items, .listing_category").sortable({
          items: 'li',
          connectWith: ".form_value",
          placeholder: "ui-state-highlight",
          forcePlaceholderSize: false,
          create: function (e, ui) {
            list_html = $("#items").html();

            // proper height calculation for the the accordions, after html stored
            if ($("#items .accordion")) {
              $("#items .accordion_child").accordion({
                collapsible: true
              });
              $("#items .accordion_child").accordion("option", "active", false);


              $("#items .accordion_parent").accordion({
                collapsible: true,
                active: false,
                activate: function (event, ui) {
                  var index = $(".accordion_parent").index($(this));
                  open_parents.push(index);
                  open_children[index] = "";
                }
              });
              $("#items .accordion_parent").accordion("option", "active", false);
            }
          },
          start: function (e, ui) {
            ui.placeholder.height(ui.item.height());
          },
          receive: function (event, ui) {
            var $this = $(this);

            if ($this.data("limit") === 1 && $this.children('li').length > 1 && $this.attr('id') !== "items") {
              alert('<?php _e( "Only one per list!", "listings" ); ?>');
              $(ui.sender).sortable('cancel');
            }

            // set val
            var name = $this.data('name');
            var name_attr = ($this.data("limit") === 1 ? "import[" + name + "]" : "import[" + name + "][]");

            ui.item.find('input[type="hidden"]').attr("name", name_attr);
						if(has_import_label){
							var import_label = $('form[name="vin_import_form"]').data('import-label');

							ui.item.append('<div><label><input type="checkbox" name="import_label[' + name + ']" value="true"> Import Label</label></div>');
						}

            if (name === 'technical_specifications') {
              var label = $this.find(".label-key").text();
              var item_val = ui.item.find('input[type="hidden"]').val();

              ui.item.find('input[type="hidden"]').after("<input type='hidden' name='" + item_val + "' value='" + ui.item.find('.label-key').text() + "'>");
              console.log('name', name, ui.item, ui.item.find('.label-key').text());
            }
          },
          stop: function (event, ui) {
            var $this = $(this);
            $("#items").html(list_html);

            if ($("#items .accordion")) {
              $("#items .accordion_child").accordion({
                collapsible: true
              });
              $("#items .accordion_child").accordion("option", "active", false);
              $("#items .accordion_child").accordion("refresh");


              $("#items .accordion_parent").accordion({
                collapsible: true,
                active: false,
                activate: function (event, ui) {
                  open_parents.push($(".accordion_parent").index($(this)));
                }
              });
              $("#items .accordion_parent").accordion("option", "active", false);

              if (open_parents != []) {
                for (i = 0; i <= open_parents.length; i++) {
                  $(".accordion_parent:eq(" + open_parents[i] + ")").accordion("option", "active", 0);
                }
              }
            }
          }
        }).disableSelection();

        $(document).on("click", ".remove_element", function () {
          $(this).closest("li").remove();

          list_html = $("#items").html();
        });

        $("form[name='vin_import_form']").width(($(".auto_import").width() - $("#items").width()) + "px").show();

        $(window).resize(function () {
          $("form[name='vin_import_form']").width(($(".auto_import").width() - $("#items").width()) + "px").show();
        });

        $(document).on("click", ".save_vin_import_categories", function (e) {

          e.preventDefault();

          jQuery.ajax({
            url: myAjax.ajaxurl,
            type: 'POST',
            data: {
              action: 'save_vin_import_categories',
              form: $("#vin_import_form").serialize(),
              nonce: $("#vin_import_form").data('nonce')
            },
            success: function (response) {
              alert(response);
            }
          });
        });
      });
    </script>

    <style>
        fieldset.category {
            border: 1px solid #CCC;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        #items, ul.listing_category {
            width: 150px;
            min-height: 20px;
            list-style-type: none;
            margin: 0;
            padding: 5px 0 10px 0;
            float: left;
            margin-right: 10px;
        }

        #items {
            max-width: 30%;
            min-width: 300px;
        }

        .ui-state-highlight {
            margin-bottom: 0;
        }

        .ui-state-default .title {
            display: block;
            text-align: center;
            border-bottom: 1px solid #2D2D2D;
            margin: 0 4px;
            color: #2D2D2D;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .ui-state-default .inside_value {
            padding: 4px;
        }

        .ui-state-default .remove_element {
            position: absolute;
            right: 2px;
            top: 2px;
            cursor: pointer;
        }

        .ui-state-default .remove_element:hover {
            color: #000;
        }

        #items li, ul.listing_category li {
            margin: 0 5px 5px 5px;
            padding: 5px;
            width: 125px;
            cursor: move;
            overflow: hidden;
            text-overflow: ellipsis;
            position: relative;
            vertical-align: top;
        }

        #items li {
            display: inline-block;
        }

        .error_text {
            padding: 10px 0;
            display: block;
        }

        form[name='vin_import_form'] {
            display: none;
            right: 0;
        }
    </style>
<?php }

function display_vin_category_value( $vin_ass, $safe_name, $body, $option, $all_vin_import ) {
	global $VIN_Import;

	$value_exists  		= true;
	$current_value 		= $body;
	$navigate      		= explode( "|", $vin_ass );
	$include_checkbox = false;

	// var_dump([$navigate, $current_value]);

	foreach ( $navigate as $nav_value ) {
		if ( isset( $current_value[ $nav_value ] ) ) {
			$current_value = $current_value[ $nav_value ];
		} else {
			$value_exists = false;
			break;
		}
	}

	if($value_exists){
		$label = $VIN_Import->get_title_key( array(
			'safe_name' 			=> $safe_name,
			'vin_association'	=> $vin_ass,
			'body'						=> $body
		) );

		if(!$label){
			$label = end( $navigate );
		} else {
			$include_checkbox = true;
		}

		echo "<li class='ui-state-default'>";
		echo $label . ": " . $current_value;
		echo " <input type='hidden' name='import[" . $safe_name . "]" . ( $option == 0 ? "[]" : "" ) . "' value='" . $vin_ass . "' />";

		if($include_checkbox){
			if($option == 0){
				$checked = (!empty($all_vin_import['import_label']) && isset($all_vin_import['import_label'][$safe_name][$vin_ass]) ? true : false);
			} else {
				$checked = (!empty($all_vin_import['import_label']) && isset($all_vin_import['import_label'][$safe_name]) ? true : false);
			}

			echo "<div><label><input type='checkbox' name='import_label[" . $safe_name . "]" . ( $option == 0 ? "[" . $vin_ass . "]" : "" ) . "' value='true'" . ($checked ? ' checked="checked"' : '') . "> " . esc_html(__("Import Label", "listings")) . "</label></div>";
		}
		echo "</li>\n";
	} else {
		echo "";
	}
}

function vin_import_list_item( $safe_name, $vin_import_associations, $body, $all_vin_import, $option = 1) {
	global $VIN_Import;

	// vin associations
	if ( ! empty( $vin_import_associations ) && isset( $vin_import_associations[ $safe_name ] ) && ! empty( $vin_import_associations[ $safe_name ] ) ) {
		$vin_ass = $vin_import_associations[ $safe_name ];

		if ( is_array( $vin_ass ) ) {

			foreach ( $vin_ass as $single_vin_ass ) {
				display_vin_category_value( $single_vin_ass, $safe_name, $body, $option, $all_vin_import );

				// technical specs label for value
				if($option == 'technical_specifications' && !empty($all_vin_import) && isset($all_vin_import[$single_vin_ass])){
				    echo '<input type="hidden" name="' . $single_vin_ass . '" value="' . $all_vin_import[$single_vin_ass] . '">';
				}
			}

			// if single level
		} elseif ( strpos( $vin_ass, "|" ) === false ) {
			$label = $VIN_Import->get_title_key( array(
				'safe_name' 			=> $safe_name,
				'vin_association'	=> $vin_import_associations[$safe_name],
				'body'						=> $body
			) );

			if(!$label){
				$label = $vin_ass;
			}

			// echo "<li class='ui-state-default'>" . $label . ": " . $body[ $vin_ass ] . " <input type='hidden' name='import[" . $safe_name . "]" . ( $option == 0 ? "[]" : "" ) . "' value='" . $vin_ass . "' /></li>";

			if(isset($body[ $vin_ass ])){
				echo $VIN_Import->draggable_element($vin_ass, $label, $body[ $vin_ass ], $safe_name);
			}
		} else {
			display_vin_category_value( $vin_ass, $safe_name, $body, $option, $all_vin_import );
		}
	}
}

function save_vin_import_categories() {

	if ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) {

		$nonce = ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) ? $_POST['nonce'] : "" );

		if ( wp_verify_nonce( $nonce, "automotive_vin_import" ) ) {
			parse_str( $_POST['form'], $form );

			update_option( "vin_import_associations", $form );

			_e( "Saved", "listings" );
		} else {
			_e( "Nonce not valid, clear your cache and try again", "listings" );
		}
	}

	die;
}

add_action( "wp_ajax_save_vin_import_categories", "save_vin_import_categories" );
add_action( "wp_ajax_nopriv_save_vin_import_categories", "save_vin_import_categories" ); ?>