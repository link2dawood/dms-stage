<?php
// Register the custom shortcode for browsing inventory
function browse_inventory_shortcode() {
    ob_start();
    
    // Fetch dynamic values for the dropdowns
    $years = get_unique_meta_values_browse('year');
    $makes = get_unique_meta_values_browse('make');
    $models = get_unique_meta_values_browse('model');

    ?>
<div class="container py-3 bg-white shadow-sm" id="browse-inventory-section" style="max-width: 1200px; margin: auto; border-radius: 8px; border: 1px solid #e0e0e0;">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 style="letter-spacing: 0.1em; text-transform: uppercase; font-weight: bold;">Browse Inventory</h2>
    </div>

    <!-- Inventory Type Selection -->
    <div class="row justify-content-center mb-4 options-container">
        <div class="col-auto types d-flex justify-content-center">
            <label class="custom-radio-label checked">
                <input type="radio" name="inventory_type" value="new" checked> New
            </label>
            <label class="custom-radio-label">
                <input type="radio" name="inventory_type" value="used"> Used
            </label>
            <label class="custom-radio-label">
                <input type="radio" name="inventory_type" value="certified"> Manufacturer Certified
            </label>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row justify-content-center filters-container">
        <div class="col-md-2 col-sm-6 col-12 form-section mb-3">
            <select class="custom-select inventory-select" id="inventory-year" name="year">
                <option value="">All Years</option>
                <?php if (!empty($years)) : ?>
                    <?php foreach ($years as $year) : ?>
                        <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6 col-12 form-section mb-3">
            <select class="custom-select inventory-select" id="inventory-make" name="make">
                <option value="">All Makes</option>
                <?php if (!empty($makes)) : ?>
                    <?php foreach ($makes as $make) : ?>
                        <option value="<?php echo esc_attr($make); ?>"><?php echo esc_html($make); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6 col-12 form-section mb-3">
            <select class="custom-select inventory-select" id="inventory-model" name="model">
                <option value="">All Models</option>
                <?php if (!empty($models)) : ?>
                    <?php foreach ($models as $model) : ?>
                        <option value="<?php echo esc_attr($model); ?>"><?php echo esc_html($model); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2 col-sm-6 col-12 form-section mb-3">
            <select class="custom-select inventory-select" id="inventory-price" name="price">
                <option value="">Max Price</option>
                <?php foreach (get_price_ranges() as $price) : ?>
                    <option value="<?php echo esc_attr($price['value']); ?>"><?php echo esc_html($price['label']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Find Matches Button -->
    <div class="text-center mt-4 button-container">
        <button id="inventory-find-matches" class="btn btn-dark btn-find-matches">Find Matches</button>
    </div>
</div>

<style>
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #000;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading { cursor: not-allowed; }
    .custom-radio-label.checked { font-weight: bold; color: #000; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
jQuery(document).ready(function($) {
    // Initialize with default values
    var currentCondition = 'new';
    
    // Load inventory data on page load
    loadInventoryData(currentCondition);

    // Function to load inventory data based on condition
    function loadInventoryData(condition) {
        currentCondition = condition;
        
        // Show loading state
        $('.inventory-select, #inventory-find-matches').prop('disabled', true);
        $('#inventory-find-matches').text('Loading...').addClass('loading');
        $('#browse-inventory-section').append('<div class="loading-overlay"><div class="loader"></div></div>');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'filter_inventory_by_condition',
                condition: condition
            },
            success: function(response) {
                if (response.success) {
                    // Update dropdowns with fresh data
                    $('#inventory-year').html(response.data.years);
                    $('#inventory-make').html(response.data.makes);
                    $('#inventory-model').html(response.data.models);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            },
            complete: function() {
                $('.inventory-select, #inventory-find-matches').prop('disabled', false);
                $('#inventory-find-matches').text('Find Matches').removeClass('loading');
                $('.loading-overlay').remove();
            }
        });
    }

    // Function to update dropdowns based on selections
    function updateDropdowns() {
        var year = $('#inventory-year').val();
        var make = $('#inventory-make').val();
        var model = $('#inventory-model').val();

        // Show loading state
        $('.inventory-select, #inventory-find-matches').prop('disabled', true);
        $('#inventory-find-matches').text('Loading...').addClass('loading');
        $('#browse-inventory-section').append('<div class="loading-overlay"><div class="loader"></div></div>');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'update_inventory_filters',
                year: year,
                make: make,
                model: model,
                condition: currentCondition
            },
            success: function(response) {
                if (response.success) {
                    // Store current selections
                    var selectedYear = $('#inventory-year').val();
                    var selectedMake = $('#inventory-make').val();
                    var selectedModel = $('#inventory-model').val();

                    // Update dropdowns
                    $('#inventory-year').html(response.data.years);
                    $('#inventory-make').html(response.data.makes);
                    $('#inventory-model').html(response.data.models);

                    // Restore selections if they still exist
                    if (selectedYear && $('#inventory-year option[value="'+selectedYear+'"]').length) {
                        $('#inventory-year').val(selectedYear);
                    }
                    if (selectedMake && $('#inventory-make option[value="'+selectedMake+'"]').length) {
                        $('#inventory-make').val(selectedMake);
                    }
                    if (selectedModel && $('#inventory-model option[value="'+selectedModel+'"]').length) {
                        $('#inventory-model').val(selectedModel);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            },
            complete: function() {
                $('.inventory-select, #inventory-find-matches').prop('disabled', false);
                $('#inventory-find-matches').text('Find Matches').removeClass('loading');
                $('.loading-overlay').remove();
            }
        });
    }

    // Handle inventory type changes
    $('input[name="inventory_type"]').on('change', function() {
        $('label.custom-radio-label').removeClass('checked');
        $(this).closest('label').addClass('checked');
        loadInventoryData($(this).val());
    });

    // Handle dropdown changes
    $('#inventory-year, #inventory-make, #inventory-model').on('change', function() {
        updateDropdowns();
    });

    // Handle Find Matches button click
    $('#inventory-find-matches').on('click', function() {
        var condition = $('input[name="inventory_type"]:checked').val();
        var priceValue = $('#inventory-price').val();
        
        // Build base URL
        var baseURL;
        if (condition === 'new') {
            baseURL = '<?php echo site_url("/new-vehicles-durango-colorado/"); ?>';
        } else if (condition === 'used') {
            baseURL = '<?php echo site_url("/used-vehicles-durango-colorado/"); ?>';
        } else if (condition === 'certified') {
            baseURL = '<?php echo site_url("/used-vehicles-durango-colorado/?certified%5B%5D=yes"); ?>';
        }

        // Build query parameters
        var params = [];
        
        // Add year filter if selected
        var year = $('#inventory-year').val();
        if (year) params.push('year%5B%5D=' + encodeURIComponent(year));
        
        // Add make filter if selected
        var make = $('#inventory-make').val();
        if (make) params.push('make%5B%5D=' + encodeURIComponent(make));
        
        // Add model filter if selected
        var model = $('#inventory-model').val();
        if (model) params.push('model%5B%5D=' + encodeURIComponent(model));
        
        // Add price filter if selected
        if (priceValue) {
            params.push('price%5B%5D=0');
            params.push('price%5B%5D=' + encodeURIComponent(priceValue));
        }

        // Combine parameters with base URL
        if (params.length > 0) {
            baseURL += (baseURL.indexOf('?') === -1 ? '?' : '&') + params.join('&');
        }

        window.location.href = baseURL;
    });
});
</script>
<?php
    return ob_get_clean();
}
add_shortcode('browse_inventory', 'browse_inventory_shortcode');

// Helper functions
if (!function_exists('get_unique_meta_values_browse')) {
    function get_unique_meta_values_browse($meta_key) {
        global $wpdb;
        $meta_values = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT meta_value FROM $wpdb->postmeta 
                WHERE meta_key = %s AND meta_value != '' 
                ORDER BY meta_value ASC",
                $meta_key
            )
        );
        return $meta_values ?: [];
    }
}

if (!function_exists('get_price_ranges')) {
    function get_price_ranges() {
        return [
            ['value' => '10000', 'label' => 'Under $10,000'],
            ['value' => '20000', 'label' => 'Under $20,000'],
            ['value' => '30000', 'label' => 'Under $30,000'],
            ['value' => '40000', 'label' => 'Under $40,000'],
            ['value' => '50000', 'label' => 'Under $50,000'],
            ['value' => '60000', 'label' => 'Under $60,000'],
            ['value' => '70000', 'label' => 'Under $70,000'],
            ['value' => '80000', 'label' => 'Under $80,000'],
            ['value' => '90000', 'label' => 'Under $90,000'],
            ['value' => '100000', 'label' => 'Under $100,000'],
            ['value' => '100000', 'label' => 'over $100,000'],
        ];
    }
}

// AJAX handlers
add_action('wp_ajax_filter_inventory_by_condition', 'filter_inventory_by_condition');
add_action('wp_ajax_nopriv_filter_inventory_by_condition', 'filter_inventory_by_condition');
add_action('wp_ajax_update_inventory_filters', 'update_inventory_filters');
add_action('wp_ajax_nopriv_update_inventory_filters', 'update_inventory_filters');

function filter_inventory_by_condition() {
    $selected_condition = isset($_POST['condition']) ? sanitize_text_field($_POST['condition']) : '';
    
    $args = [
        'post_type' => 'listings',
        'posts_per_page' => -1,
        'meta_query' => ['relation' => 'AND'],
    ];

    if ($selected_condition === 'new') {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'N', 'compare' => '='];
    } elseif ($selected_condition === 'used') {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'U', 'compare' => '='];
    } elseif ($selected_condition === 'certified') {
        $args['meta_query'][] = ['key' => 'certified', 'value' => 'yes', 'compare' => '='];
    }

    $query = new WP_Query($args);
    $years = $makes = $models = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $year = get_post_meta(get_the_ID(), 'year', true);
            $make = get_post_meta(get_the_ID(), 'make', true);
            $model = get_post_meta(get_the_ID(), 'model', true);

            if ($year && !in_array($year, $years)) $years[] = $year;
            if ($make && !in_array($make, $makes)) $makes[] = $make;
            if ($model && !in_array($model, $models)) $models[] = $model;
        }
    }

    sort($years);
    sort($makes);
    sort($models);

    wp_send_json_success([
        'years' => '<option value="">All Years</option>' . implode('', array_map(function($y) {
            return '<option value="' . esc_attr($y) . '">' . esc_html($y) . '</option>';
        }, $years)),
        'makes' => '<option value="">All Makes</option>' . implode('', array_map(function($m) {
            return '<option value="' . esc_attr($m) . '">' . esc_html($m) . '</option>';
        }, $makes)),
        'models' => '<option value="">All Models</option>' . implode('', array_map(function($mo) {
            return '<option value="' . esc_attr($mo) . '">' . esc_html($mo) . '</option>';
        }, $models)),
    ]);
}

function update_inventory_filters() {
    $selected_year = isset($_POST['year']) ? sanitize_text_field($_POST['year']) : '';
    $selected_make = isset($_POST['make']) ? sanitize_text_field($_POST['make']) : '';
    $selected_model = isset($_POST['model']) ? sanitize_text_field($_POST['model']) : '';
    $selected_condition = isset($_POST['condition']) ? sanitize_text_field($_POST['condition']) : 'new';

    $args = [
        'post_type' => 'listings',
        'posts_per_page' => -1,
        'meta_query' => ['relation' => 'AND'],
    ];

    if ($selected_condition === 'new') {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'N', 'compare' => '='];
    } elseif ($selected_condition === 'used') {
        $args['meta_query'][] = ['key' => 'condition', 'value' => 'U', 'compare' => '='];
    } elseif ($selected_condition === 'certified') {
        $args['meta_query'][] = ['key' => 'certified', 'value' => 'yes', 'compare' => '='];
    }

    if (!empty($selected_year)) $args['meta_query'][] = ['key' => 'year', 'value' => $selected_year, 'compare' => '='];
    if (!empty($selected_make)) $args['meta_query'][] = ['key' => 'make', 'value' => $selected_make, 'compare' => '='];
    if (!empty($selected_model)) $args['meta_query'][] = ['key' => 'model', 'value' => $selected_model, 'compare' => '='];

    $query = new WP_Query($args);
    $years = $makes = $models = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $year = get_post_meta(get_the_ID(), 'year', true);
            $make = get_post_meta(get_the_ID(), 'make', true);
            $model = get_post_meta(get_the_ID(), 'model', true);

            if ($year && !in_array($year, $years)) $years[] = $year;
            if ($make && !in_array($make, $makes)) $makes[] = $make;
            if ($model && !in_array($model, $models)) $models[] = $model;
        }
    }

    sort($years);
    sort($makes);
    sort($models);

    $years_output = '<option value="">All Years</option>';
    $makes_output = '<option value="">All Makes</option>';
    $models_output = '<option value="">All Models</option>';

    foreach ($years as $year) {
        $selected = ($year === $selected_year) ? 'selected' : '';
        $years_output .= '<option value="' . esc_attr($year) . '" ' . $selected . '>' . esc_html($year) . '</option>';
    }

    foreach ($makes as $make) {
        $selected = ($make === $selected_make) ? 'selected' : '';
        $makes_output .= '<option value="' . esc_attr($make) . '" ' . $selected . '>' . esc_html($make) . '</option>';
    }

    foreach ($models as $model) {
        $selected = ($model === $selected_model) ? 'selected' : '';
        $models_output .= '<option value="' . esc_attr($model) . '" ' . $selected . '>' . esc_html($model) . '</option>';
    }

    wp_send_json_success([
        'years' => $years_output,
        'makes' => $makes_output,
        'models' => $models_output,
    ]);
}