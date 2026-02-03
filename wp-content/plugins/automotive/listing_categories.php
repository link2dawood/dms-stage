<?php

function automotive_listing_categories_page() {
  add_submenu_page(
    'edit.php?post_type=listings',
    __("Listing Categories", "listings"),
    __("Listing Categories", "listings"),
    automotive_listing_get_option('listing-category-permission', 'manage_options'),
    'listing_categories',
    'listing_categories'
  );
}
add_action('admin_menu', 'automotive_listing_categories_page');

// process form data
function automotive_add_listing_category() {
    global $Listing;

    // get current categories
    $current_categories = $Listing->get_listing_categories(true);
    if ($current_categories == false) {
        $current_categories = array();
    }

    $public_query_vars = $Listing->get_public_query_vars();

    if (isset($_POST['submit_listing_cat']) && !empty($_POST['submit_listing_cat'])) {
        if (in_array(strtolower($_POST['singular-form']), $public_query_vars) || in_array(strtolower($_POST['plural-form']), $public_query_vars)) {
            $redirect = add_query_arg("err", "var");
            header("Location: " . $redirect);
            die;
        }

        if (isset($_GET['edit']) && !empty($_GET['edit'])) {
            $current_edit = urldecode($_GET['edit']);

            $singular_form = sanitize_text_field($_POST['singular-form']);
            $plural_form   = sanitize_text_field($_POST['plural-form']);
            $slug          = sanitize_text_field((isset($_POST['url-slug']) && !empty($_POST['url-slug']) ? $_POST['url-slug'] : $_POST['singular-form']));

            $slug = $Listing->slugify($slug);


            // if it gets renamed, remove the previous entry and insert new one
            if ($current_edit != $slug) {
                do_action('automotive_listing_category_rename', $current_edit, $slug);

                $insert_after = automotive_array_insert_after($current_edit, $current_categories, $slug, array());

                //$current_edit = str_replace(" ", "_", strtolower($singular_form));
                if ($insert_after != false) {
                    $current_categories = $insert_after;
                }

                // set up new category
                $current_categories[$slug] = array(
                    "singular"        => $singular_form,
                    "plural"          => $plural_form,
                    "slug"            => $slug,
                    "filterable"      => (isset($_POST['filterable']) && !empty($_POST['filterable']) ? $_POST['filterable'] : 0),
                    "range"           => (isset($_POST['range']) && !empty($_POST['range']) ? $_POST['range'] : 0),
                    "range_increment" => (isset($_POST['range_increment']) && !empty($_POST['range_increment']) ? absint($_POST['range_increment']) : 0),
                    "use_on_listing"  => (isset($_POST['use_on_listing']) && !empty($_POST['use_on_listing']) ? $_POST['use_on_listing'] : 0),
                    "hide_category"   => (isset($_POST['hide_category']) && !empty($_POST['hide_category']) ? $_POST['hide_category'] : 0),
                    "show_amount"     => (isset($_POST['show_amount']) && !empty($_POST['show_amount']) ? $_POST['show_amount'] : 0),
                    "column"          => (isset($_POST['column']) && !empty($_POST['column']) ? $_POST['column'] : 0),
                    "location_email"  => (isset($_POST['location_email']) && !empty($_POST['location_email']) ? $_POST['location_email'] : 0),
                    "compare_value"   => (isset($_POST['compare_value']) && !empty($_POST['compare_value']) ? sanitize_text_field($_POST['compare_value']) : "="),
                    "is_number"       => (isset($_POST['is_number']) && !empty($_POST['is_number']) ? $_POST['is_number'] : "0"),
                    "currency"        => (isset($_POST['currency']) && !empty($_POST['currency']) ? $_POST['currency'] : 0),
                    "link_value"      => (isset($_POST['link_value']) && !empty($_POST['link_value']) ? $_POST['link_value'] : 0),
                    "sort_terms"      => (isset($_POST['sort_terms']) && !empty($_POST['sort_terms']) ? $_POST['sort_terms'] : 0),
										"schema"                    => (isset($_POST['schema']) && !empty($_POST['schema']) ? $_POST['schema'] : 'none'),
                    "terms"           => (isset($current_categories[$current_edit]['terms']) && !empty($current_categories[$current_edit]['terms']) ? $current_categories[$current_edit]['terms'] : "")
                );

                unset($current_categories[$current_edit]);
            } else {

                // keeps same key
                $current_categories[$current_edit] = array(
                    "singular"        => $singular_form,
                    "plural"          => $plural_form,
                    "slug"            => $slug,
                    "filterable"      => (isset($_POST['filterable']) && !empty($_POST['filterable']) ? $_POST['filterable'] : 0),
                    "range"           => (isset($_POST['range']) && !empty($_POST['range']) ? $_POST['range'] : 0),
                    "range_increment" => (isset($_POST['range_increment']) && !empty($_POST['range_increment']) ? absint($_POST['range_increment']) : 0),
                    "use_on_listing"  => (isset($_POST['use_on_listing']) && !empty($_POST['use_on_listing']) ? $_POST['use_on_listing'] : 0),
                    "hide_category"   => (isset($_POST['hide_category']) && !empty($_POST['hide_category']) ? $_POST['hide_category'] : 0),
                    "show_amount"     => (isset($_POST['show_amount']) && !empty($_POST['show_amount']) ? $_POST['show_amount'] : 0),
                    "column"          => (isset($_POST['column']) && !empty($_POST['column']) ? $_POST['column'] : 0),
                    "location_email"  => (isset($_POST['location_email']) && !empty($_POST['location_email']) ? $_POST['location_email'] : 0),
                    "compare_value"   => (isset($_POST['compare_value']) && !empty($_POST['compare_value']) ? sanitize_text_field($_POST['compare_value']) : "="),
                    "is_number"       => (isset($_POST['is_number']) && !empty($_POST['is_number']) ? $_POST['is_number'] : 0),
                    "currency"        => (isset($_POST['currency']) && !empty($_POST['currency']) ? $_POST['currency'] : 0),
                    "link_value"      => (isset($_POST['link_value']) && !empty($_POST['link_value']) ? $_POST['link_value'] : 0),
                    "sort_terms"      => (isset($_POST['sort_terms']) && !empty($_POST['sort_terms']) ? $_POST['sort_terms'] : 0),
										"schema"                    => (isset($_POST['schema']) && !empty($_POST['schema']) ? $_POST['schema'] : 'none'),
                    "terms"           => (isset($current_categories[$current_edit]['terms']) && !empty($current_categories[$current_edit]['terms']) ? $current_categories[$current_edit]['terms'] : "")
                );
            }

        } else {
            // get current categories
            $current_categories = $Listing->get_listing_categories(true);
            if ($current_categories == false) {
                $current_categories = array();
            }

            $singular_form = sanitize_text_field($_POST['singular-form']);
            $plural_form   = sanitize_text_field($_POST['plural-form']);
            $slug          = sanitize_text_field((isset($_POST['url-slug']) && !empty($_POST['url-slug']) ? $_POST['url-slug'] : $_POST['singular-form']));

            $slug = $Listing->slugify($slug);

            // add to array
            $current_categories[$slug] = array(
                "singular"        => $singular_form,
                "plural"          => $plural_form,
                "slug"            => $slug,
                "filterable"      => (isset($_POST['filterable']) && !empty($_POST['filterable']) ? $_POST['filterable'] : 0),
                "range"           => (isset($_POST['range']) && !empty($_POST['range']) ? $_POST['range'] : 0),
                "range_increment" => (isset($_POST['range_increment']) && !empty($_POST['range_increment']) ? absint($_POST['range_increment']) : 0),
                "use_on_listing"  => (isset($_POST['use_on_listing']) && !empty($_POST['use_on_listing']) ? $_POST['use_on_listing'] : 0),
                "hide_category"   => (isset($_POST['hide_category']) && !empty($_POST['hide_category']) ? $_POST['hide_category'] : 0),
                "show_amount"     => (isset($_POST['show_amount']) && !empty($_POST['show_amount']) ? $_POST['show_amount'] : 0),
                "column"          => (isset($_POST['column']) && !empty($_POST['column']) ? $_POST['column'] : 0),
                "location_email"  => (isset($_POST['location_email']) && !empty($_POST['location_email']) ? $_POST['location_email'] : 0),
                "compare_value"   => sanitize_text_field($_POST['compare_value']),
                "is_number"       => (isset($_POST['is_number']) && !empty($_POST['is_number']) ? $_POST['is_number'] : 0),
                "currency"        => (isset($_POST['currency']) && !empty($_POST['currency']) ? $_POST['currency'] : 0),
                "link_value"      => (isset($_POST['link_value']) && !empty($_POST['link_value']) ? $_POST['link_value'] : 0),
                "sort_terms"      => (isset($_POST['sort_terms']) && !empty($_POST['sort_terms']) ? $_POST['sort_terms'] : 0),
								"schema"                  => (isset($_POST['schema']) && !empty($_POST['schema']) ? $_POST['schema'] : 'none')
            );

            do_action('automotive_listing_category_add', $slug);
        }

        update_option($Listing->get_listing_categories_option_name(), $current_categories);

        header("Location: " . remove_query_arg(array( "delete", "edit", "err" )));
    }

    // if delete category
    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        $to_delete = str_replace(" ", "_", strtolower(urldecode($_GET['delete'])));

        unset($current_categories[$to_delete]);

        do_action('automotive_listing_category_remove', $to_delete);

        update_option($Listing->get_listing_categories_option_name(), $current_categories);

        header("Location: " . remove_query_arg(array( "delete", "edit" )));
    }

    // if order needs to be changed
    if (isset($_POST['save_listing_order']) && !empty($_POST['save_listing_order'])) {
        $new_options   = (isset($_POST['categories']) && !empty($_POST['categories']) ? $_POST['categories'] : array());
        $original_info = $Listing->get_listing_categories();
        $new_order     = array();

        foreach ($new_options as $key => $option) {
            $new_order[$key] = $original_info[$key];
        }

        $options = $Listing->get_single_listing_category("options");

        $new_order['options'] = $options;

        update_option($Listing->get_listing_categories_option_name(), $new_order);
    }

    if (isset($_POST['seo_string_holder'])) {
        update_option("listing_seo_string", sanitize_text_field($_POST['seo_string_holder']));
    }
}

add_action("init", "automotive_add_listing_category");


function listing_categories() {
    global $Listing;

    $public_query_vars = $Listing->get_public_query_vars();

    $action             = (isset($_GET['edit']) && !empty($_GET['edit']) ? __("Save", "listings") : __("Add New", "listings"));
    $listing_categories = $Listing->get_listing_categories();

    $safe_edit = (isset($_GET['edit']) && !empty($_GET['edit']) ? $_GET['edit'] : "");

    if (isset($safe_edit) && !empty($safe_edit) && isset($listing_categories[$safe_edit]) && !empty($listing_categories[$safe_edit])) {
        $_GET['edit'] = stripslashes(str_replace(" ", "_", strtolower($_GET['edit'])));

        $singular_form   = stripslashes($listing_categories[$_GET['edit']]['singular']);
        $plural_form     = stripslashes($listing_categories[$_GET['edit']]['plural']);
        $slug_current    = htmlentities(stripslashes($listing_categories[$_GET['edit']]['slug']), ENT_QUOTES);
        $filterable      = $listing_categories[$_GET['edit']]['filterable'];
        $range           = (isset($listing_categories[$_GET['edit']]['range']) && !empty($listing_categories[$_GET['edit']]['range']) ? $listing_categories[$_GET['edit']]['range'] : '');
        $range_increment = (isset($listing_categories[$_GET['edit']]['range_increment']) && !empty($listing_categories[$_GET['edit']]['range_increment']) ? $listing_categories[$_GET['edit']]['range_increment'] : '');
        $use_on_listing  = $listing_categories[$_GET['edit']]['use_on_listing'];
        $hide_category   = (isset($listing_categories[$_GET['edit']]['hide_category']) && !empty($listing_categories[$_GET['edit']]['hide_category']) ? $listing_categories[$_GET['edit']]['hide_category'] : "");
        $show_amount     = (isset($listing_categories[$_GET['edit']]['show_amount']) && !empty($listing_categories[$_GET['edit']]['show_amount']) ? $listing_categories[$_GET['edit']]['show_amount'] : "");
        $column          = (isset($listing_categories[$_GET['edit']]['column']) ? $listing_categories[$_GET['edit']]['column'] : "");
        $location_email  = (isset($listing_categories[$_GET['edit']]['location_email']) ? $listing_categories[$_GET['edit']]['location_email'] : "");
        $compare_value   = (isset($listing_categories[$_GET['edit']]['compare_value']) && !empty($listing_categories[$_GET['edit']]['compare_value']) ? $listing_categories[$_GET['edit']]['compare_value'] : "");
        $is_number       = (isset($listing_categories[$_GET['edit']]['is_number']) && !empty($listing_categories[$_GET['edit']]['is_number']) ? $listing_categories[$_GET['edit']]['is_number'] : "");
        $currency        = (isset($listing_categories[$_GET['edit']]['currency']) && !empty($listing_categories[$_GET['edit']]['currency']) ? $listing_categories[$_GET['edit']]['currency'] : "");
        $link_value      = (isset($listing_categories[$_GET['edit']]['link_value']) && !empty($listing_categories[$_GET['edit']]['link_value']) ? $listing_categories[$_GET['edit']]['link_value'] : "");
        $sort_terms      = (isset($listing_categories[$_GET['edit']]['sort_terms']) && !empty($listing_categories[$_GET['edit']]['sort_terms']) ? $listing_categories[$_GET['edit']]['sort_terms'] : "");
				$schema              = (isset($listing_categories[$_GET['edit']]['schema']) && !empty($listing_categories[$_GET['edit']]['schema']) ? $listing_categories[$_GET['edit']]['schema'] : '');

        $is_editing = true;
    } else {
        $is_editing = false;
    } ?>
   <div class="wrap nosubsub">
        <h2><?php _e("Listing Categories", "listings"); ?></h2>

        <?php
			    if (isset($_GET['err']) && $_GET['err'] == "var") {
			        echo '<div class="error"><span class="error_text">';
			        echo __("You cannot create listing categories that use these strings, it can conflict with WordPress: ", "listings");
			        $list = "";
			        foreach ($public_query_vars as $var) {
			            $list .= $var . ", ";
			        }

			        echo substr($list, 0, -2);

			        echo '</span></div>';
			    } ?>

        <div id="ajax-response"></div>

        <div id="col-container">

            <div id="col-right">

                <div class="col-wrap">

                    <form id="posts-filter" action="" method="post">

                        <form method="post" name="change_order">
                            <table class="wp-list-table widefat listing_categories">
                                <thead>
                                <tr>
                                    <th scope="col" id="singular" class="manage-column column-singular" style="">
                                        <span><?php _e("Singular", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" id="plural" class="manage-column column-plural" style="">
                                        <span><?php _e("Plural", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" id="slug" class="manage-column column-plural" style="">
                                        <span><?php _e("Slug", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" id="filterable" class="manage-column column-filterable" style="">
                                        <span><?php _e("Filterable", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" id="use-on-listing" class="manage-column column-use-on-listing" style="">
																			<span><?php _e("Use on listing", "listings"); ?></span>
																			<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>

                                <tfoot>
                                <tr>
                                    <th scope="col" class="manage-column column-singular" style="">
                                        <span><?php _e("Singular", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" class="manage-column column-plural" style="">
                                        <span><?php _e("Plural", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" class="manage-column column-slug" style="">
                                        <span><?php _e("Slug", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" class="manage-column column-filterable" style="">
                                        <span><?php _e("Filterable", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col" class="manage-column column-use-on-listing" style="">
                                        <span><?php _e("Use on listing", "listings"); ?></span>
																				<span class="sorting-indicator"></span>
                                    </th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </tfoot>

                                <tbody>
                                <?php
															    $use_on_listing_i = 0;

															    if (!empty($listing_categories)) {
															        $i = 0;

															        $yes_text = __("Yes", "listings");
															        $no_text  = __("No", "listings");

															        foreach ($listing_categories as $key => $category) {

															            if (!empty($category['singular']) && !empty($category['plural'])) {

															                $category['filterable']     = (isset($category['filterable']) && !empty($category['filterable']) ? $category['filterable'] : 0);
															                $category['use_on_listing'] = (isset($category['use_on_listing']) && !empty($category['use_on_listing']) ? $category['use_on_listing'] : 0);

															                $slug = (isset($category['slug']) && !empty($category['slug']) ? $category['slug'] : "");

															                $i++;

															                if ($category['use_on_listing'] == 1) {
															                    $use_on_listing_i++;
															                }

															                if (isset($category['location_email']) && $category['location_email'] == 1) {
															                    $location_email_inuse = true;
															                } ?>
                                           <tr id="tag-<?php echo $i; ?>" class="<?php echo ($i & 1 ? " " : ""); ?>">
                                                <td class="singular column-singular">
                                                    <strong><?php echo stripslashes($category['singular']); ?></strong>
                                                </td>
                                                <td class="plural column-plural">
                                                    <strong><?php echo stripslashes($category['plural']); ?></strong>
                                                </td>
                                                <td class="slug column-slug"><strong><?php echo $slug; ?></strong></td>
                                                <td class="filterable column-filterable"><?php echo ($category['filterable'] == 1 ? $yes_text : $no_text); ?></td>
                                                <td class="use-on-listing column-use-on-listing"><?php echo ($category['use_on_listing'] == 1 ? $yes_text : $no_text); ?></td>
                                                <td class="">
																									<a href="<?php echo esc_url(add_query_arg("edit", $slug)); ?>"><?php _e("Edit", "listings"); ?></a>
                                                </td>
                                                <td class="">
																									<a href="<?php echo esc_url(add_query_arg("delete", $slug)); ?>"><?php _e("Delete", "listings"); ?></a>
                                                </td>
                                                <td class="">
																									<i class="fa fa-arrows handle" data-name="<?php echo $slug; ?>"></i>
                                                    <input type="hidden" name="categories[<?php echo $slug; ?>]" value="true">
                                                </td>
                                            </tr>
                                            <?php
															            }
															        }
															    } else {
															        echo "<tr><td colspan='2'>" . __("No Categories Yet", "listings") . "</td></tr>";
															    } ?>
                           </table>

                            <br>

                            <div>
                                <input type="submit" class="button-primary" value="<?php _e("Save Order", "listings"); ?>" name="save_listing_order">

                                <button class="resetListingTerms button-primary" data-alert-message="<?php _e("Are you sure you want to DELETE all listing categories terms?", "listings"); ?>"><?php _e("Delete All Listing Terms", "listings"); ?></button>
                            </div>
                        </form>

                        <br><br>

                        <div class="listing-category-actions">

                          <div class="listing-category-row">
                            <div class="first-column">
                              <button class="toggle_seo_options button-primary"><i class="fa fa-search"></i> <?php _e("Listing Meta Description (SEO)", "listings"); ?></button>

                           </div>

                           <div class="second-column">

                             <p><?php esc_html_e("Customize the meta description search engines use with dynamic values that are set on each listing.", "listings"); ?></p>

                             <form method="post" name="seo_listing" style="display: none;"><br>
                                 <?php $current_seo_string = get_option("listing_seo_string"); ?>

                                 <input type='text' style='width: 300px;' placeholder='SEO string' class='seo_string_holder' name='seo_string_holder' <?php echo (!empty($current_seo_string) ? "value='" . $current_seo_string . "'" : ""); ?>>

                                 <input type="submit" value="<?php _e("Save meta description", "listings"); ?>" class='button-primary'><br>
                                 <?php _e("To customize the meta description click the plus icon (<i class='fa fa-plus-square'></i>) on the right of the category to insert it at the end of the text area. The variables will be converted to their values on the listing page. You can add normal text as well, just be sure not to wrap it in the % symbols!", "listings"); ?>
                            </form>

                           </div>

                           <div class="clearfix"></div>
                         </div>

                         <div class="listing-category-row">
                           <div class="first-column">

                              <span class="refresh_listing_category_generate">
                                  <button class="regenerate button-primary">
      															<i class="fa status_indicator fa-refresh"></i> <?php _e("Regenerate Frontend Terms", "listings"); ?>
      														</button>
                              </span>
                            </div>

                            <div class="second-column">
                              <p><?php _e("Notice missing terms on your frontend? Click the button below to regenerate them and have them show up. If you use a custom import plugin you will need to run this in order for the terms to appear.", "listings"); ?></p>
                            </div>

                            <div class="clearfix"></div>
                          </div>

                          <?php if ($Listing->is_wpml_active()) { ?>

                         <div class="listing-category-row">
                           <div class="first-column">

                              <span class="refresh_wpml_listing_categories">
                                  <button class="regenerate_wpml button-primary">
                                      <i class="fa status_indicator_wpml fa-refresh"></i> <?php _e("Register WPML Terms", "listings"); ?>
                                 </button>
                              </span>

                            </div>

                            <div class="second-column">
                               <p><?php _e("Notice missing terms in WPML? Click the button below to force register them in WPML", "listings"); ?></p>
                            </div>

                            <div class="clearfix"></div>
                          </div>

                          <?php } ?>


                          <div class="listing-category-row">
                           <div class="first-column">

                            <span class="delete_unused_terms_container">
                              <button class="delete_unused_terms button-primary" data-message="<?php _e("Are you sure you want to DELETE all unused listing category terms?", "listings"); ?>">
                                <i class="fa fa-trash status_indicator_trash"></i> <?php _e("Delete Unused Listing Terms", "listings"); ?>
                              </button>
                            </span>

                          </div>

                          <div class="second-column">
                            <p><?php _e("Delete any unused terms for all of your listing categories.  This applies only to the backend, by default any unused terms are hidden on the frontend category filters of the website.", "listings"); ?></p>
                          </div>

                          <div class="clearfix"></div>

                        </div>
                      </div>
                    </form>
                </div>
            </div>
            <!-- /col-right -->

            <div id="col-left">
                <div class="col-wrap">


                    <div class="form-wrap">
                        <h3><?php echo $action; ?> <?php _e("Listing Category", "listings"); ?></h3>

                        <form id="add_category" method="post" action="" class="validate">

                            <div class="form-field form-required">
                                <label for="singular-form">
																	<?php _e("Singular Form", "listings"); ?> <span style="color: #F00">*</span>
																</label>
                                <input name="singular-form" id="singular-form" type="text" size="40" aria-required="true"<?php echo ($is_editing ? " value='" . $singular_form . "'" : ""); ?>>

                                <p><?php _e("The singular form of the category name", "listings"); ?>.</p>
                            </div>

                            <div class="form-field form-required">
                                <label for="plural-form">
																	<?php _e("Plural Form", "listings"); ?> <span style="color: #F00">*</span>
																</label>
                                <input name="plural-form" id="plural-form" type="text" size="40" aria-required="true"<?php echo ($is_editing ? " value='" . $plural_form . "'" : ""); ?>>

                                <p><?php _e("The plural form of the category name", "listings"); ?>.</p>
                            </div>

                            <div class="form-field form-required">
                                <label for="url-slug"><?php _e("URL Slug", "listings"); ?></label>
                                <input name="url-slug" id="url-slug" type="text" size="40" aria-required="true"<?php echo ($is_editing ? " value='" . $slug_current . "'" : ""); ?>>

                                <p><?php _e("The URL safe slug of your listing category, can only contain alphanumeric characters or a dash", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="compare_value" style="display: inline-block;"><?php _e("Compare Value", "listings"); ?></label>
                                <select name="compare_value" id="compare_value">
                                    <?php

																		$options = array("=", "<", "<=", ">", ">=");

																    foreach ($options as $option) {
																        echo "<option value='" . $option . "'" . ($is_editing ? selected(html_entity_decode($compare_value), $option, false) : "") . ">" . $option . "</option>";
																    } ?>
                               </select>

                                <p><?php _e("Change the way the value is compared, useful for numbers (price, mileage, fuel economy)", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="is_number" style="display: inline-block;"><?php _e("Is Number", "listings"); ?></label>
                                <input name="is_number" id="is_number" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $is_number == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Set this category as a number value", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="filterable" style="display: inline-block;"><?php _e("Filterable", "listings"); ?></label>
                                <input name="filterable" id="filterable" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $filterable == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Make this category display in filterable spots", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="range" style="display: inline-block;"><?php _e("Range", "listings"); ?></label>
                                <input name="range" id="range" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $range == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("This will display a range control instead of a dropdown for number categories on the inventory page.", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="range_increment" style="display: inline-block;"><?php _e("Range Increment", "listings"); ?></label>
                                <input name="range_increment" id="range_increment" type="text" style="width: auto;"<?php echo ($is_editing && $range_increment ? " value='" . absint($range_increment) . "'" : ""); ?>>

                                <p><?php _e("This will determine how much to increment the range slider by.", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="currency" style="display: inline-block;"><?php _e("Currency", "listings"); ?></label>
                                <input name="currency" id="currency" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $currency == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Check this box if the current category is a currency or price", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="use_on_listing" style="display: inline-block;"><?php _e("Use On Listing", "listings"); ?></label>
                                <input name="use_on_listing" id="use_on_listing" type="checkbox" value="1" style="width: auto;"
																<?php echo ($is_editing && isset($use_on_listing) && $use_on_listing == 1 ? " checked='checked'" : "") . ((!$is_editing && isset($use_on_listing_i) && $use_on_listing_i == 10) || ($is_editing && $use_on_listing == 0 && $use_on_listing_i == 10) ? " disabled='disabled'" : ""); ?>>
                                <i class='fa-info-circle auto_info_tooltip fa' data-title="<img src='<?php echo THUMBNAIL_URL; ?>widget_slider/example-use_on_listing.png' style='opactiy: 1'>" data-placement="right"></i>

                                <p><?php _e("Make this category show on the listing information", "listings"); ?>
                                   (<?php echo (isset($use_on_listing_i) && $use_on_listing_i == 0 ? "10 max" : $use_on_listing_i . "/10"); ?>).
																 </p>
                            </div>

                            <div class="form-field">
                                <label for="hide_category" style="display: inline-block;"><?php _e("Hide Listing Category", "listings"); ?></label>
                                <input name="hide_category" id="hide_category" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $hide_category == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Check this box if you want to hide the listing category on the single listing page sidebar", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="show_amount" style="display: inline-block;">
																	<?php _e("Show Amount", "listings"); ?>
																</label>

																<input name="show_amount" id="show_amount" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && $show_amount == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Check this box if you want the amount of listings in this category displayed in dropdown menus on the frontend", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="column" style="display: inline-block;"><?php _e("Show Column", "listings"); ?></label>
                                <input name="column" id="column" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && isset($column) && $column == 1 ? " checked='checked'" : ""); ?>>

                                <p><?php _e("Show this listing category as a column under the listings post type", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="location_email" style="display: inline-block;"><?php _e("Multiple Location Emails", "listings"); ?></label>
                                <input name="location_email" id="location_email" type="checkbox" value="1" style="width: auto;"<?php echo ($is_editing && isset($location_email) && $location_email == 1 ? " checked='checked'" : "") . (isset($location_email_inuse) && $location_email_inuse && !isset($location_email) ? " disabled='disabled'" : ""); ?>>

                                <p><?php _e("Adds the ability to specify different email addresses on a per location basis for contact forms", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="link_value" style="display: inline-block;"><?php _e("Link Value", "listings"); ?></label>
                                <?php
															    $options = array(
															        "none"  => "None",
															        "price" => "Price",
															        "mpg"   => "MPG"
															    );

															    $link_value = (isset($link_value) && !empty($link_value) ? $link_value : "");

															    echo "<select name='link_value'>";
															    foreach ($options as $option => $label) {
															        echo "<option" . selected($option, $link_value) . " value='" . $option . "'>" . $label . "</option>";
															    }
															    echo "</select>";
																?>
                               <p><?php _e("Choose a value that you want to link values with", "listings"); ?>.</p>
                            </div>

                            <div class="form-field">
                                <label for="sort_terms" style="display: inline-block;"><?php _e("Sort Terms", "listings"); ?></label>
                                <?php
															    $options = array(
															        "asc"  => __("Ascending", "listings"),
															        "desc" => __("Descending", "listings")
															    );

															    $sort_terms = (isset($sort_terms) ? $sort_terms : '');

															    echo "<select name='sort_terms' id='sort_terms'>";
															    foreach ($options as $option => $label) {
															        echo "<option" . selected($option, $sort_terms) . " value='" . $option . "'>" . $label . "</option>";
															    }
															    echo "</select>";

																?>
                               <p><?php _e("Choose what order the terms will be ordered in", "listings"); ?>.</p>
                            </div>

														<div class="form-field">
															<label for="auto_schema" style="display: inline-block;"><?php _e("Schema Value", "listings"); ?></label>
															<?php
																$schema_values = array(
																	"accelerationTime", "bodyType", "cargoVolume", "dateVehicleFirstRegistered", "driveWheelConfiguration", "emissionsCO2", "fuelCapacity", "fuelConsumption", "fuelEfficiency", "fuelType", "knownVehicleDamages", "meetsEmissionStandard", "mileageFromOdometer", "modelDate", "numberOfAirbags",
																	"numberOfAxles", "numberOfDoors", "numberOfForwardGears", "numberOfPreviousOwners", "payload", "productionDate", "purchaseDate", "seatingCapacity", "speed", "steeringPosition", "tongueWeight", "trailerWeight", "vehicleConfiguration", "vehicleEngine", "vehicleIdentificationNumber", "vehicleInteriorColor",
																	"vehicleInteriorType", "vehicleModelDate", "vehicleSeatingCapacity", "vehicleSpecialUsage", "vehicleTransmission", "weightTotal", "wheelbase", "additionalProperty", "aggregateRating", "audience", "award", "brand", "category", "color", "depth", "gtin12", "gtin13", "gtin14", "gtin8", "height",
																	"isAccessoryOrSparePartFor", "isConsumableFor", "isRelatedTo", "isSimilarTo", "itemCondition", "logo", "manufacturer", "material", "model", "mpn", "offers", "productID", "productionDate", "purchaseDate", "releaseDate", "review", "sku", "weight", "width", "additionalType",
																	"alternateName", "description", "disambiguatingDescription", "identifier", "image", "mainEntityOfPage", "name", "potentialAction", "sameAs", "subjectOf", "url", "aircraft"
																);

																sort($schema_values);

																$schema = (isset($schema) ? $schema : ''); ?>

																<select name="schema" id="auto_schema">
																	<option value="none"><?php esc_html_e("None", "listings"); ?></option>
																	<?php foreach($schema_values as $schema_value){ ?>
																	<option value="<?php echo esc_attr($schema_value); ?>" <?php selected($schema_value, $schema); ?>><?php echo esc_html($schema_value); ?></option>
																	<?php } ?>
																</select>

																<p><?php echo sprintf( __("Choose what format this value will be in according to %sVehicle Schema%s", "listings"), "<a href='https://schema.org/Vehicle' target='_blank'>", "</a>"); ?>.</p>
														</div>

                            <p class="submit">
															<input type="submit" name="submit_listing_cat" id="submit" class="button button-primary" value="<?php echo $action; ?> <?php _e("Listing Category", "listings"); ?>">
                            </p>

                        </form>
                    </div>
                </div>
            </div>
            <!-- /col-left -->

        </div>
        <!-- /col-container -->
    </div><!-- /wrap -->

    <div class="clear"></div>

    <?php } ?>