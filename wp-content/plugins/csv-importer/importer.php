<?php
/*
Plugin Name: Custom Cron Plugin
Description: Import and update vehicles data from CSV file into website
Author: Hirejunaid
Author URI: https://hirejunaid.com
Version: 1.0.0
Plugin URI: https://hirejunaid.com
*/

// Register the function to run on plugin activation
register_activation_hook( __FILE__, 'custom_cron_plugin_activate' );

$GLOBALS['IMPORTER_PACKET_ID'] = get_option('importer_packet_id') !== false ? get_option('importer_packet_id') : 1;

function custom_cron_plugin_activate() {
    // Clear any existing schedules to avoid duplicates
    wp_clear_scheduled_hook('listings_batches_import');
    
    // Calculate the timestamp for the next 11:20 AM UTC
    $timezone = new DateTimeZone('UTC');
    $now = new DateTime('now', $timezone);
    $target_time = new DateTime('today 7:40:00', $timezone);
    
    // If the target time has already passed today, schedule for tomorrow
    if ($now > $target_time) {
        $target_time->modify('+1 day');
    }
    
    $start_timestamp = $target_time->getTimestamp();
    
    // Schedule the cron job to run daily at 11:20 AM UTC
    wp_schedule_event($start_timestamp, 'daily', 'listings_batches_import');
}
add_action('listings_batches_import', 'listingsBatchesImport');

/**
 * Delete previous listings and associated post meta.
 */
function delete_previous_listings($stock)
{
    $args = array(
        'post_type'      => 'listings',
        'meta_query' => array(
            array(
                'key' => 'stock-number',
                'value' => $stock,
                'compare' => '=',
            ),
        ),
    );

    $listings = get_posts($args);

    foreach ($listings as $listing) {
        delete_listing($listing->ID);
    }
}

/**
 * Delete a single listing and its attachments.
 *
 * @param int $listing_id The ID of the listing to delete.
 */
function delete_listing($listing_id)
{
    $attachments = get_posts(array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_parent'    => $listing_id,
    ));

    foreach ($attachments as $attachment) {
        wp_delete_attachment($attachment->ID, true);
    } 

    wp_delete_post($listing_id, true);
}

/**
 * Send email notification.
 *
 * @param string $to               Email recipient.
 * @param string $subject          Email subject.
 * @param string $message          Email message.
 * @param string $attachment_path  Optional. Path to the attachment file.
 */
function send_cron_status_email($subject, $message, $attachment_path = '')
{
    $to = ['bernardo.monge@progreza.com', 'junaid.asghar@progreza.com', 'susana.bermudez@progreza.com'];
    if (!empty($attachment_path)) {
        $attachment = chunk_split(base64_encode(file_get_contents($attachment_path)));

        $boundary = md5(time());
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = "Content-Type: multipart/mixed; boundary=\"$boundary\"";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Disposition: inline\r\n";
        $body .= "\r\n";
        $body .= $message . "\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: text/csv; name=\"" . basename($attachment_path) . "\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"" . basename($attachment_path) . "\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "\r\n";
        $body .= $attachment . "\r\n";
        $body .= "--$boundary--\r\n";

        wp_mail($to, $subject, $body, $headers);
    } else {
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
        );
        wp_mail($to, $subject, $message, $headers);
    }
}

function listCSVFiles() {
    $log_file = WP_CONTENT_DIR . '/cron-log.txt';
    file_put_contents($log_file, "Starting listCSVFiles()\n", FILE_APPEND);
    
    $remote_file = '/public_html/latest-inventory.csv';
    $local_file = WP_CONTENT_DIR . '/Uploads/import-csv/latest-inventory.csv';
    
    file_put_contents($log_file, "Remote file: $remote_file\n", FILE_APPEND);
    file_put_contents($log_file, "Local file path: $local_file\n", FILE_APPEND);

    $sftp_server = "sftp://45.77.6.66$remote_file";
    $username = "csv-file";
    $password = "6ktx77hZns";
    
    file_put_contents($log_file, "SFTP URL: $sftp_server\n", FILE_APPEND);

    $ch = curl_init();
    if (!$ch) {
        file_put_contents($log_file, "Failed to initialize cURL.\n", FILE_APPEND);
        return false;
    }
    
    curl_setopt($ch, CURLOPT_URL, $sftp_server);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    file_put_contents($log_file, "Starting cURL execution\n", FILE_APPEND);
    $data = curl_exec($ch);

    if ($data === false) {
        $error = curl_error($ch);
        file_put_contents(WP_CONTENT_DIR . '/cron-log.txt', "cURL Error: $error\n", FILE_APPEND);
        curl_close($ch);
        return false;
    }

    file_put_contents($log_file, "cURL executed successfully, data length: " . strlen($data) . "\n", FILE_APPEND);
    curl_close($ch);
    
    if (!is_dir(dirname($local_file))) {
        file_put_contents($log_file, "Directory does not exist, creating: " . dirname($local_file) . "\n", FILE_APPEND);
        mkdir(dirname($local_file), 0755, true);
    }

    $result = file_put_contents($local_file, $data);
    if ($result === false) {
        file_put_contents(WP_CONTENT_DIR . '/cron-log.txt', "Failed to save file\n", FILE_APPEND);
        return false;
    }
    
    file_put_contents($log_file, "File saved successfully to $local_file\n", FILE_APPEND);
	
	send_cron_status_email( 'CSV File Found', 'CSV File Found, importer function running' );

    return $local_file;
}

function listingsBatchesImport() {
    // Get the current posts stock number and store in an array
    $presentStockNumbers = array();
    $stockArray = array(
        'post_type' => 'listings',
        'posts_per_page' => -1,
    );
    $stockPosts = get_posts( $stockArray );    
    foreach( $stockPosts as $post ) {
        $stockNumber = get_post_meta($post->ID, 'stock-number', true);
        $presentStockNumbers[] = strtoupper($stockNumber);
    }

    $file = listCSVFiles();
    
    if( !empty($file) ) {
        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle);
            $listingsArray = array();
            $csvStockNumbers = array();
            $alreadyPresentStock = array();
            $totalListings = 0;
            while (($data = fgetcsv($handle)) !== false) {
                $listing_data = [];
                
                foreach ($header as $index => $column) {
                    if (isset($data[$index])) { 
                        $listing_data[$column] = $data[$index];
                    }
                }
            
                if (isset($listing_data['Stock #'])) {
                    if (!isset($listingsArray[$listing_data['Stock #']])) {
                        $listingsArray[$listing_data['Stock #']] = array();
                    }
                    $listingsArray[$listing_data['Stock #']][] = $listing_data;
                    $csvStockNumbers[] = strtoupper($listing_data['Stock #']);
                    $totalListings++;
                }
            }
            
            fclose($handle);

            // Delete Previous Listings
            $tobeDeletedStocks = array_diff($presentStockNumbers, $csvStockNumbers);
            if (!empty($tobeDeletedStocks)) {
                foreach ($tobeDeletedStocks as $stocks) {
                    delete_previous_listings($stocks);
                    $removedIndex = array_search($stocks, $presentStockNumbers);
                    if ($removedIndex !== false) {
                        unset($presentStockNumbers[$removedIndex]);
                    }
                }
            }

            foreach ($listingsArray as $stock => $listing) {
                $csvStock = strtoupper($stock);
                if (in_array($csvStock, $presentStockNumbers)) {
                    $alreadyPresentStock[] = $csvStock;
                    continue; // Skip this iteration if stock number is already present
                }

                create_listing($listing[0]);
                $presentStockNumbers[] = $csvStock;

            }

            updateListingData($alreadyPresentStock, $listingsArray);

            // Send email notification
            $timezone = new DateTimeZone('America/Denver');
            $date = new DateTime('now', $timezone);
            $current_time = $date->format('Y-m-d h:i:s A');
            $subject = 'CSV Import Complete';
            $message = 'CSV import process completed. Total listings '. $totalListings .' were imported on ' . $current_time . ' ' .'And the packet ID for this import is' . ' ' . '(' . $GLOBALS['IMPORTER_PACKET_ID'] . ')';
            send_cron_status_email($subject, $message, $file);

            update_option('importer_packet_id', intval($GLOBALS['IMPORTER_PACKET_ID']) + 1);

            // Remove the processed CSV file and move it to backups folder
            $backup_directory = WP_CONTENT_DIR . '/Uploads/backups/';

            if (!file_exists($backup_directory)) {
                mkdir($backup_directory, 0755, true);
            }

            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $backup_filename = basename($file, '.' . $extension) . '_' . date('Y-m-d_His') . '.' . $extension;
            $backup_path = $backup_directory . $backup_filename;
            if( rename($file, $backup_path) ) {
                unlink($file);
            }
        }
    }
}

/** Create a function to update the updated values of listings */
function updateListingData($alreadyPresentStock, $listingsArray) {
    $listing = array(); // Initialize the array
    if (!empty($alreadyPresentStock)) {
        $args = array(
            'post_type' => 'listings',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'stock-number',
                    'value' => $alreadyPresentStock,
                    'compare' => 'IN',
                ),
            ),
        );
        $updateQuery = get_posts($args);
        $listing = [];
        $listingsStocks = [];

        if (!empty($updateQuery)) {
            foreach ($updateQuery as $query) {
                $listing_data = get_post_meta($query->ID, '', true); // post meta data of each post
                $listingStock = get_post_meta($query->ID, 'stock-number', true); // stock number of current post

                if (!isset($listing[$listingStock])) {
                    $listing[$listingStock] = [];
                }
                $listingsStocks[$listingStock][] = $query->ID; // id of the posts with key of stock number
                $listing[$listingStock][] = $listing_data; // store listing data with key of stock number
            }
        } else {
            $listing = [];
        }

        // Update data
        foreach ($listingsArray as $stock => $listingArr) {
            if (array_key_exists($stock, $listing)) {
                $keysArr = array(
                    'Transmission' => 'transmission',
                    'Series' => 'series',
                    'Colour' => 'exterior-color',
                    'Interior Color' => 'interior-color',
                    'City MPG' => 'city_mpg',
                    'Highway MPG' => 'highway_mpg',
                    'Price' => 'original_price',
                    'Other Price' => 'current_price',
                    'Series Detail' => 'series-detail',
                    'Certification' => 'certification',
                    'Certified' => 'certified',
                    'MiscPrice1' => 'miscprice-1',
                    'MiscPrice2' => 'miscprice-2',
                    'MiscPrice3' => 'miscprice-3',
                    'DealerId' => 'dealer-id',
                    'Odometer' => 'odometer',
                    'Invoice' => 'invoice',
                    'Book Value' => 'book-value',
                    'Cost' => 'cost'
                );
                foreach ($keysArr as $CSVkey => $metaKey) {
                    if ($listingArr[0][$CSVkey] !== $listing[$stock][0][$metaKey][0]) {
                        update_post_meta($listingsStocks[$stock][0], $metaKey, $listingArr[0][$CSVkey]);
						wp_mail('junaid.asghar@progreza.com' ,'post meta updated', serialize([$listingsStocks[$stock][0], $metaKey]));
                        if ($metaKey === 'city_mpg' || $metaKey === 'highway_mpg' ||
                            $metaKey === 'original_price' || $metaKey === 'current_price') {
                            $listing_options = get_post_meta($listingsStocks[$stock][0], 'listing_options', true);
                            $listing_options = !empty($listing_options) ? unserialize($listing_options) : [];
                            $val = $metaKey === 'city_mpg' || $metaKey === 'highway_mpg' || $metaKey === 'original_price' ? 'value' : 'original';
                            $ke = $metaKey === 'original_price' || $metaKey === 'current_price' ? 'price' : $metaKey;
                            $listing_options[$ke][$val] = $listingArr[0][$CSVkey];
                            update_post_meta($listingsStocks[$stock][0], 'listing_options', serialize($listing_options));
                        }
                        // Delete Transient if any post meta value changes
                        delete_transient('product_card_' . $listingsStocks[$stock][0]);
                    }
                }

                if ($listingArr[0]['Last Modified Date'] !== $listing[$stock][0]['photos-last-modified-date'][0]) {
                    update_post_meta($listingsStocks[$stock][0], 'photos-last-modified-date', $listingArr[0]['Last Modified Date']);
                    
                    // Attach images
                    $image_urls = explode('|', $listingArr[0]['Photo Url List']);
                    if (empty($image_urls)) {
                        $image_urls = array();
                    }
                    $image_urls = array_map('trim', $image_urls);
                    $image_urls = array_filter($image_urls, function ($urls) {
                        return filter_var($urls, FILTER_VALIDATE_URL) !== false;
                    });

                    // Save the photo URLs as metadata
                    update_post_meta($listingsStocks[$stock][0], 'photo_urls', $image_urls);

                    // Delete transient if photo gallery changes
                    delete_transient('product_card_' . $listingsStocks[$stock][0]);
                }
            }
        }
        return $listing;
    }
}

/**
 * Create a new listing from the given data.
 *
 * @param array $data The listing data.
 *
 * @return int|false The ID of the created listing, or false on failure.
 */
function create_listing($data)
{
    // Create a new post
    $listing_id = wp_insert_post(array(
        'post_title'   => $data['Year'] . ' ' . $data['Make'] . ' ' . $data['Model'] . ' ' . $data['Series'],
        'post_content' => $data['Description'],
        'post_type'    => 'listings',
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
    ));
    if (is_wp_error($listing_id)) {
        wp_mail('junaid.asghar@progreza.com', 'Post Creation Error', $listing_id->get_error_message());
        return false;
    }

    // Set custom fields
    $post_metas = array(
        'DealerId'               => 'dealer-id',
        'VIN'                    => 'vin-number',
        'Stock #'                => 'stock-number',
        'New/Used'               => 'condition',
        'Year'                   => 'year',
        'Make'                   => 'make',
        'Model'                  => 'model',
        'Model Number'           => 'model-number',
        'Body'                   => 'body-style',
        'Body Type'              => 'type-of-vehicle',
        'Transmission'           => 'transmission',
        'Series'                 => 'series',
        'Body Door Ct'           => 'doors',
        'Odometer'               => 'odometer',
        'Engine Cylinder Ct'     => 'cylinders',
        'Engine Displacement'    => 'engine-displacement',
        'Drivetrain Desc'        => 'drivetrain',
        'Colour'                 => 'exterior-color',
        'Interior Color'         => 'interior-color',
        'Invoice'                => 'invoice',
        'Other Price'            => 'current_price',
        'Book Value'             => 'book-value',
        'Price'                  => 'original_price',
        'Inventory Date'         => 'inventory-date',
        'Certified'              => 'certified',
        'Description'            => 'description',
        'Features'               => 'features',
        'City MPG'               => 'city_mpg',
        'Highway MPG'            => 'highway_mpg',
        'Last Modified Date' => 'photos-last-modified-date',
        'Status Code'            => 'car_sold',
        'Cost'                   => 'cost',
        'Series Detail'          => 'series-detail',
        'Inspection Checklist #' => 'inspection-checklist-number',
        'Engine'                    => 'engine',
        'Certification'          => 'certification',
        'Option Codes'           => 'option-codes',
        'MiscPrice1'             => 'miscprice-1',
        'MiscPrice2'             => 'miscprice-2',
        'MiscPrice3'             => 'miscprice-3',
        'Disposition'            => 'disposition',
        'Fuel Type'              => 'fuel-type',
    );
    foreach ($post_metas as $key => $meta) {
        $meta_value_value = isset($data[$key]) ? trim($data[$key]) : '';
        update_post_meta( $listing_id, $meta, $meta_value_value );
    }
    $post_name =  $data['Year'] . '-' . $data['Make'] . '-' . $data['Model'] . '-' . $data['Stock #'];

    // Update post slug
    $post_data = array(
        'ID' => $listing_id,
        'post_name' => $post_name,
    );
    wp_update_post($post_data);

    // Regenerate permalink
    $permalink = get_permalink($listing_id);
    update_post_meta($listing_id, 'postName', $data['Year'] . ' ' . $data['Make'] . ' ' . $data['Model'] . ' ' . $data['Series']);

    // Attach images
	$image_urls = preg_split('/\||(?=https:\/\/)/', $txt);
	// Clean up
	$image_urls = array_map('trim', $image_urls);
	$image_urls = array_filter($image_urls, function ($url) {
		return filter_var($url, FILTER_VALIDATE_URL);
	});
	$image_urls = array_values($image_urls); // reindex
    if (empty($image_urls)) {
        $image_urls = array();
    }

    // Store photo URLs as meta data
    if (!empty($image_urls)) {
        update_post_meta($listing_id, 'photo_urls', $image_urls);
    }

    // Add listing options meta data
    $listing_options = get_post_meta($listing_id, 'listing_options', true);
    if (empty($listing_options)) {
        $listing_options = [];
    } else {
        $listing_options = unserialize($listing_options);
    }
    $listing_options['price']['value'] = $data['Price'];
    $listing_options['price']['original'] = $data['Other Price'];
    $listing_options['city_mpg']['value'] = $data['City MPG'];
    $listing_options['highway_mpg']['value'] = $data['Highway MPG'];

    update_post_meta($listing_id, 'listing_options', serialize($listing_options));

    return $listing_id;
}

register_deactivation_hook( __FILE__, 'listings_importer_deactivate' ); 
function listings_importer_deactivate() {
    wp_clear_scheduled_hook( 'listings_csv_file_count' );
    wp_clear_scheduled_hook( 'listings_batches_import' );
}
?>