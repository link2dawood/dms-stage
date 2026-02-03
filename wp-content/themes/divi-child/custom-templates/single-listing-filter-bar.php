<?php
function get_unique_post_meta_values($meta_key) {
    $posts = get_posts(array(
        'post_type' => 'listings',
        'meta_key' => $meta_key,
        'posts_per_page' => -1 // Set to -1 to retrieve all posts with the meta key
    ));
    
    $values = array();
    foreach ($posts as $post) {
        $value = get_post_meta($post->ID, $meta_key, true);
        if (!in_array($value, $values)) {
            $values[] = $value;
        }
    }
    
    return $values;
}
$years = get_unique_post_meta_values('year');
$makes = get_unique_post_meta_values('make');
$models = get_unique_post_meta_values('model');
$bodyStyle = get_unique_post_meta_values('body-style');
$engines = get_unique_post_meta_values('engine');
$bodyType = get_unique_post_meta_values('type-of-vehicle');
$doors = get_unique_post_meta_values('doors');
$cylinders = get_unique_post_meta_values('cylinders');
$drivetrains = get_unique_post_meta_values('drivetrain');
$exteriorColors = get_unique_post_meta_values('exterior-color');
$interiorColors = get_unique_post_meta_values('interior-color');
$certifieds = get_unique_post_meta_values('certified');
$fuelTypes = get_unique_post_meta_values('fuel-type');
$transmissions = get_unique_post_meta_values('transmission');
?>
<div class="inventory-filters-main-wrapper">
    <div class="inventory-filters-mobilebar-heading d_flex d_flex__justify-between inventory-filterbar__border-bottom pb-2">
        <h2 class="inventory-filters-mobile-heading m_0">filters</h2>
        <span class="single-filters-bar-close inventory-filters-mobilebar-close-icon">
            <i class="fa fa-times" aria-hidden="true"></i>
        </span>
    </div>
    <form class="inventory-filterbar__modal-searchbar inventory-filterbar__border-bottom">
        <div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Make</h2>
            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
        </div>
        <div class="expanding-section">
            <?php foreach ($makes as $make) : ?>
                <?php  
                    if(isset($make) && !empty($make) && $make !== 'None') {
                        echo '<div class="inventory-filterbar__year make"><input class="checkbox-filters make-filter-input" data-type="make" type="checkbox" name="listing_make[]" id="inventory-filter-make-checkbox_'.$make.'" value="'.$make.'"><label for="inventory-filter-make-checkbox_'.$make.'" class="inventory-filterbar">'. $make .'</label></div>';
                    }
                ?>
            <?php endforeach; ?>
        </div>
    </form>
    <form class="inventory-filterbar__modal-searchbar inventory-filterbar__border-bottom">
        <div
            class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Model</h2>
            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
        </div>
        <div class="expanding-section">
            <?php foreach ($models as $model) : ?>
                <?php  
                    if(isset($model) && !empty($model) && $model !== 'None') {
                        echo '<div class="inventory-filterbar__year model"><input class="checkbox-filters model-filter-input" data-type="model" type="checkbox" name="listing_model[]" id="inventory-filter-model-checkbox_'.$model.'" value="'.$model.'"><label for="inventory-filter-model-checkbox_'.$model.'" class="inventory-filterbar">'. $model .'</label></div>';
                    }
                    ?>
            <?php endforeach; ?>
        </div>
    </form>
    <form class="inventory-filterbar__modal-searchbar inventory-filterbar__border-bottom">
        <div
            class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Year</h2>
            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
        </div>
        <div class="expanding-section">
            <?php foreach ($years as $year) : ?>
                <?php  
                    if(isset($year) && !empty($year) && $year !== 'None') {
                        echo '<div class="inventory-filterbar__year year"><input class="checkbox-filters year-filter-input" data-type="year" type="checkbox" name="listing_year[]" id="inventory-filter-year-checkbox_'.$year.'" value="'.$year.'"><label for="inventory-filter-year-checkbox_'.$year.'" class="inventory-filterbar">'. $year .'</label></div>';
                    }
                    ?>
            <?php endforeach; ?>
        </div>
    </form>
    <div class="inventory-filterbar__filter-by-price inventory-filterbar__border-bottom">
        <div
            class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Price</h2>
            <div class="inventory-filterbar__img-wrapper">
                <i class="fa-solid fa-plus"></i>
            </div>
        </div>
        <form class="inventory-filterbar__price-filter-wrapper expanding-section">
            <div class="inventory-filterbar__min-price"><label for="min price" value="Min">Min</label>
            <span class="currency">$</span> <input type="number" name="filter by min price" class="filter-by-min-price" min="0" placeholder="0" /></div>
            <div class="inventory-filterbar__text-divider">to</div>
            <div class="inventory-filterbar__max-price"><label for="min price" value="Max">Max</label>
            <span class="currency">$</span> <input type="number" name="filter by max price" class="filter-by-max-price" min="1000" placeholder="30,000" /></div>
            <div class="row align-items-center w-100 p-0" style="grid-column:1 /span 3;">
                <div class="col-6 pl-0">
                    <input type="submit" name="apply_price" value="Apply" class="apply_price btn btn-primary rounded-10 font-weight-bold" data-filter="price">
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a class="text text-link text-primary clear-input" data-filter="price">Reset</a>
                </div>
            </div>
        </form>
    </div>
    <div class="inventory-filterbar__fitler-by-engine">
        <div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Engine</h2>
            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
        </div>
        <form class="inventory-filterbar__year-search-wrapper engine expanding-section">
            <?php foreach ($engines as $engine) : ?>
                <?php  
                    if(isset($engine) && !empty($engine) && $engine !== 'None') {
                        echo '<div class="inventory-filterbar__year engine"><input class="checkbox-filters engine-filter-input" data-type="engine" type="checkbox" name="listing_engine[]" id="inventory-filter-engine-checkbox_'.$engine.'" value="'.$engine.'"><label for="inventory-filter-engine-checkbox_'.$engine.'" class="inventory-filterbar">'. $engine .'</label></div>';
                    }
                ?>
            <?php endforeach; ?>
        </form>
    </div>

                        </div>