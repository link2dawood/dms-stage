<?php
/*
Plugin Name: CSV Import Plug-in (Replicated Listings Import)
Description: Manual CSV import tool with configurable SFTP settings and email notifications. Fixed: All CSV fields now update properly on re-import.
Version: 1.0.1
Author: Progreza
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants
define( 'CSVIPL_VERSION', '1.0.1' );
define( 'CSVIPL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CSVIPL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CSVIPL_CONFIG_FILE', CSVIPL_PLUGIN_PATH . 'config.json' );
define( 'CSVIPL_HISTORY_FILE', CSVIPL_PLUGIN_PATH . 'import-history.json' );

// Packet counter option key
$GLOBALS['CSVIPL_PACKET_ID'] = get_option( 'csvipl_packet_id' ) !== false ? (int) get_option( 'csvipl_packet_id' ) : 1;

/**
 * Get import history from JSON file
 */
function csvipl_get_import_history() {
	if ( ! file_exists( CSVIPL_HISTORY_FILE ) ) {
		return array();
	}
	
	$content = file_get_contents( CSVIPL_HISTORY_FILE );
	$history = json_decode( $content, true );
	
	return is_array( $history ) ? $history : array();
}

/**
 * Save import history to JSON file
 */
function csvipl_save_import_history( $history ) {
	// Keep only last 20 entries
	if ( count( $history ) > 20 ) {
		$history = array_slice( $history, -20 );
	}
	
	$json = json_encode( $history, JSON_PRETTY_PRINT );
	return file_put_contents( CSVIPL_HISTORY_FILE, $json ) !== false;
}

/**
 * Add entry to import history
 */
function csvipl_add_import_history( $filename, $vehicle_count, $import_type, $status = 'success', $original_filename = '' ) {
	$history = csvipl_get_import_history();
	
	// Set Mountain Time timezone
	date_default_timezone_set( 'America/Denver' );
	$mountain_time = date( 'Y-m-d H:i:s' );
	
	$entry = array(
		'id' => uniqid(),
		'filename' => $filename,
		'original_filename' => ! empty( $original_filename ) ? $original_filename : $filename,
		'vehicle_count' => $vehicle_count,
		'import_type' => $import_type, // 'sftp' or 'upload'
		'status' => $status, // 'success' or 'error'
		'date_time' => $mountain_time
	);
	
	$history[] = $entry;
	
	return csvipl_save_import_history( $history );
}

/**
 * Get plugin settings from JSON file
 */
function csvipl_get_settings() {
	$defaults = array(
		'sftp_server' => '45.77.6.66',
		'sftp_username' => 'csv-file',
		'sftp_password' => '6ktx77hZns',
		'remote_file_path' => '/public_html/latest-inventory.csv',
		'email_recipients' => "bernardo.monge@progreza.com\njunaid.asghar@progreza.com\nsusana.bermudez@progreza.com",
		'email_subject' => 'CSV Import Complete',
		'email_message' => 'CSV import process completed successfully.',
		'upload_location' => 'csv-imports',
		'log_enabled' => 1,
	);
	
	if ( ! file_exists( CSVIPL_CONFIG_FILE ) ) {
		return $defaults;
	}
	
	$json_content = file_get_contents( CSVIPL_CONFIG_FILE );
	if ( $json_content === false ) {
		return $defaults;
	}
	
	$settings = json_decode( $json_content, true );
	if ( json_last_error() !== JSON_ERROR_NONE ) {
		return $defaults;
	}
	
	return wp_parse_args( $settings, $defaults );
}

/**
 * Save plugin settings to JSON file
 */
function csvipl_save_settings( $settings ) {
	$json_content = json_encode( $settings, JSON_PRETTY_PRINT );
	if ( $json_content === false ) {
		return false;
	}
	
	$result = file_put_contents( CSVIPL_CONFIG_FILE, $json_content );
	return $result !== false;
}

// Add admin menu
add_action( 'admin_menu', 'csvipl_add_admin_menu' );
function csvipl_add_admin_menu() {
	add_menu_page(
		'CSV Import Tool',           // Page title
		'CSV Import',                // Menu title
		'manage_options',            // Capability
		'csv-import-tool',           // Menu slug
		'csvipl_admin_page',         // Function
		'dashicons-upload',          // Icon (upload icon)
		2                            // Position (right after Dashboard)
	);
}

// Enqueue admin scripts
add_action( 'admin_enqueue_scripts', 'csvipl_admin_scripts' );
function csvipl_admin_scripts( $hook ) {
	if ( 'toplevel_page_csv-import-tool' !== $hook ) {
		return;
	}
	wp_enqueue_script( 'jquery' );
	wp_add_inline_script( 'jquery', '
		jQuery(document).ready(function($) {
			// SFTP Import button
			$("#csvipl-run-import").click(function(e) {
				e.preventDefault();
				var button = $(this);
				var originalText = button.text();
				
				button.prop("disabled", true).text("Importing...");
				$("#csvipl-status").html("<p>Starting SFTP import process...</p>");
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: "csvipl_run_import",
						nonce: "' . wp_create_nonce( 'csvipl_import_nonce' ) . '"
					},
					success: function(response) {
						$("#csvipl-status").html(response);
						// Refresh Vehicle Inventory Summary
						csvipl_refresh_inventory_summary();
						// Refresh Import History
						csvipl_refresh_import_history();
					},
					error: function() {
						$("#csvipl-status").html("<p style=\"color: red;\">Error occurred during SFTP import.</p>");
					},
					complete: function() {
						button.prop("disabled", false).text(originalText);
					}
				});
			});
			
			// Upload & Import form
			$("#csvipl-upload-form").submit(function(e) {
				e.preventDefault();
				var button = $("#csvipl-run-upload-import");
				var originalText = button.text();
				var fileInput = $("#csvipl-csv-file")[0];
				
				if (!fileInput.files.length) {
					$("#csvipl-upload-status").html("<p style=\"color: red;\">Please select a CSV file to upload.</p>");
					return;
				}
				
				var formData = new FormData();
				formData.append("action", "csvipl_upload_import");
				formData.append("nonce", "' . wp_create_nonce( 'csvipl_upload_nonce' ) . '");
				formData.append("csv_file", fileInput.files[0]);
				
				button.prop("disabled", true).text("Uploading & Importing...");
				$("#csvipl-upload-status").html("<p>Starting upload and import process...</p>");
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function(response) {
						$("#csvipl-upload-status").html(response);
						fileInput.value = ""; // Clear file input
						// Refresh Vehicle Inventory Summary
						csvipl_refresh_inventory_summary();
						// Refresh Import History
						csvipl_refresh_import_history();
					},
					error: function() {
						$("#csvipl-upload-status").html("<p style=\"color: red;\">Error occurred during upload and import.</p>");
					},
					complete: function() {
						button.prop("disabled", false).text(originalText);
					}
				});
			});
			
			// Function to refresh Vehicle Inventory Summary
			function csvipl_refresh_inventory_summary() {
				console.log("Refreshing inventory summary...");
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: "csvipl_refresh_summary",
						nonce: "' . wp_create_nonce( 'csvipl_summary_nonce' ) . '"
					},
					success: function(response) {
						console.log("Summary refresh response:", response);
						$("#csvipl-inventory-summary").html(response);
					},
					error: function(xhr, status, error) {
						console.log("Failed to refresh inventory summary:", error);
						console.log("Response:", xhr.responseText);
					}
				});
			}
			
			// Function to refresh Import History
			function csvipl_refresh_import_history() {
				console.log("Refreshing import history...");
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: "csvipl_refresh_history",
						nonce: "' . wp_create_nonce( 'csvipl_history_nonce' ) . '"
					},
					success: function(response) {
						console.log("History refresh response:", response);
						$("#csvipl-import-history").html(response);
					},
					error: function(xhr, status, error) {
						console.log("Failed to refresh import history:", error);
						console.log("Response:", xhr.responseText);
					}
				});
			}
			
			// Test email button
			$("#csvipl-test-email").click(function(e) {
				e.preventDefault();
				var button = $(this);
				var originalText = button.text();
				
				button.prop("disabled", true).text("Sending...");
				
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: "csvipl_test_email",
						nonce: "' . wp_create_nonce( 'csvipl_test_nonce' ) . '"
					},
					success: function(response) {
						$("#csvipl-status").html("<p style=\"color: green;\">Test email sent! Check your inbox and the log file.</p>");
					},
					error: function() {
						$("#csvipl-status").html("<p style=\"color: red;\">Failed to send test email. Check the log file for details.</p>");
					},
					complete: function() {
						button.prop("disabled", false).text(originalText);
					}
				});
			});
		});
	' );
}

// AJAX handler for SFTP import
add_action( 'wp_ajax_csvipl_run_import', 'csvipl_ajax_run_import' );
function csvipl_ajax_run_import() {
	check_ajax_referer( 'csvipl_import_nonce', 'nonce' );
	
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions' );
	}
	
	ob_start();
	csvipl_listings_batches_import();
	$output = ob_get_clean();
	
	echo $output;
	wp_die();
}

// AJAX handler for file upload import
add_action( 'wp_ajax_csvipl_upload_import', 'csvipl_ajax_upload_import' );
function csvipl_ajax_upload_import() {
	check_ajax_referer( 'csvipl_upload_nonce', 'nonce' );
	
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions' );
	}
	
	if ( ! isset( $_FILES['csv_file'] ) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK ) {
		wp_send_json_error( 'File upload failed.' );
	}
	
	$uploaded_file = $_FILES['csv_file'];
	
	// Validate file type
	$file_type = wp_check_filetype( $uploaded_file['name'] );
	if ( $file_type['ext'] !== 'csv' ) {
		wp_send_json_error( 'Please upload a CSV file.' );
	}
	
	// Move uploaded file to configured location
	$settings = csvipl_get_settings();
	$upload_location = isset( $settings['upload_location'] ) ? $settings['upload_location'] : 'csv-imports';
	$upload_dir = wp_upload_dir();
	$target_dir = $upload_dir['basedir'] . '/' . $upload_location;
	
	// Create directory if it doesn't exist
	if ( ! file_exists( $target_dir ) ) {
		wp_mkdir_p( $target_dir );
	}
	
	$temp_file = $target_dir . '/temp_' . time() . '_' . $uploaded_file['name'];
	
	if ( ! move_uploaded_file( $uploaded_file['tmp_name'], $temp_file ) ) {
		wp_send_json_error( 'Failed to save uploaded file.' );
	}
	
	ob_start();
	csvipl_listings_batches_import_from_file( $temp_file, $uploaded_file['name'] );
	$output = ob_get_clean();
	
	// Clean up temporary file
	if ( file_exists( $temp_file ) ) {
		unlink( $temp_file );
	}
	
	echo $output;
	wp_die();
}

// AJAX handler for refreshing inventory summary
add_action( 'wp_ajax_csvipl_refresh_summary', 'csvipl_ajax_refresh_summary' );
function csvipl_ajax_refresh_summary() {
	check_ajax_referer( 'csvipl_summary_nonce', 'nonce' );
	
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions' );
	}
	
	ob_start();
	csvipl_render_inventory_summary();
	$output = ob_get_clean();
	
	echo $output;
	wp_die();
}

// AJAX handler for test email
add_action( 'wp_ajax_csvipl_test_email', 'csvipl_ajax_test_email' );
function csvipl_ajax_test_email() {
	check_ajax_referer( 'csvipl_test_nonce', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions' );
	}
	
	$subject = 'CSV Import Plugin - Test Email';
	$message = 'This is a test email from the CSV Import Plugin. If you receive this, email notifications are working correctly.';
	
	$result = csvipl_send_status_email( $subject, $message );
	
	if ( $result ) {
		wp_send_json_success( 'Test email sent successfully' );
	} else {
		wp_send_json_error( 'Failed to send test email' );
	}
}

// AJAX handler for refreshing import history
add_action( 'wp_ajax_csvipl_refresh_history', 'csvipl_ajax_refresh_history' );
function csvipl_ajax_refresh_history() {
	check_ajax_referer( 'csvipl_history_nonce', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Insufficient permissions' );
	}
	
	ob_start();
	csvipl_render_import_history();
	$output = ob_get_clean();
	
	echo $output;
	wp_die();
}

/**
 * Render Vehicle Inventory Summary table
 */
function csvipl_render_inventory_summary() {
	?>
	<table style="width: 100%; border-collapse: collapse; font-family: monospace; font-size: 14px;">
		<thead>
			<tr>
				<th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">DealerId</th>
				<th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Vehicle Count</th>
			</tr>
		</thead>
		<tbody>
			<?php
			// Get vehicle counts by DealerId
			$vehicle_counts = array();
			$total_vehicles = 0;

			$listings = get_posts( array(
				'post_type'      => 'listings',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => 'dealer-id',
						'compare' => 'EXISTS',
					),
				),
			) );

			foreach ( $listings as $listing_id ) {
				$dealer_id = get_post_meta( $listing_id, 'dealer-id', true );
				if ( ! empty( $dealer_id ) ) {
					$dealer_id = trim( $dealer_id );
					if ( ! isset( $vehicle_counts[ $dealer_id ] ) ) {
						$vehicle_counts[ $dealer_id ] = 0;
					}
					$vehicle_counts[ $dealer_id ]++;
					$total_vehicles++;
				}
			}

			// Sort by count descending, then by dealer id ascending
			uksort( $vehicle_counts, function( $a, $b ) use ( $vehicle_counts ) {
				if ( $vehicle_counts[ $a ] == $vehicle_counts[ $b ] ) {
					return strcasecmp( $a, $b );
				}
				return ( $vehicle_counts[ $a ] < $vehicle_counts[ $b ] ) ? 1 : -1;
			} );

			if ( empty( $vehicle_counts ) ) {
				echo '<tr><td colspan="2" style="padding: 8px; text-align: center;">No vehicle listings found.</td></tr>';
			} else {
				foreach ( $vehicle_counts as $dealer_id => $count ) {
					echo '<tr>';
					echo '<td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html( $dealer_id ) . '</td>';
					echo '<td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html( $count ) . '</td>';
					echo '</tr>';
				}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td style="padding: 8px; border-top: 2px solid #ddd; font-weight: bold;">TOTAL</td>
				<td style="padding: 8px; border-top: 2px solid #ddd; font-weight: bold;"><?php echo esc_html( $total_vehicles ); ?></td>
			</tr>
		</tfoot>
	</table>
	<?php
}

/**
 * Render Import History table
 */
function csvipl_render_import_history() {
	$history = csvipl_get_import_history();
	
	// Sort by date_time descending (most recent first)
	usort( $history, function( $a, $b ) {
		return strtotime( $b['date_time'] ) - strtotime( $a['date_time'] );
	} );
	
	?>
	<table style="width: auto; border-collapse: separate; border-spacing: 6px; font-family: monospace; font-size: 12px; table-layout: auto;">
		<thead>
			<tr>
				<th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #ddd; font-weight: bold; white-space: nowrap;">Filename</th>
				<th style="text-align: center; padding: 6px 10px; border-bottom: 1px solid #ddd; font-weight: bold; white-space: nowrap;">Count</th>
				<th style="text-align: left; padding: 6px 10px; border-bottom: 1px solid #ddd; font-weight: bold; white-space: nowrap;">Date & Time</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( empty( $history ) ) {
				echo '<tr><td colspan="3" style="padding: 8px; text-align: center; color: #666;">No import history found.</td></tr>';
			} else {
				foreach ( $history as $entry ) {
					echo '<tr>';
					// Show original filename if available, otherwise show the stored filename
					$display_filename = isset( $entry['original_filename'] ) && ! empty( $entry['original_filename'] ) ? $entry['original_filename'] : $entry['filename'];
					echo '<td style="padding: 6px 10px; border-bottom: 1px solid #eee; white-space: nowrap; overflow: visible;" title="' . esc_attr( $entry['filename'] ) . '">' . esc_html( $display_filename ) . '</td>';
					echo '<td style="padding: 6px 10px; border-bottom: 1px solid #eee; text-align: center; white-space: nowrap;">' . esc_html( $entry['vehicle_count'] ) . '</td>';
					echo '<td style="padding: 6px 10px; border-bottom: 1px solid #eee; white-space: nowrap; overflow: visible;">' . esc_html( $entry['date_time'] ) . ' MDT</td>';
					echo '</tr>';
				}
			}
			?>
		</tbody>
	</table>
	<?php
}

// Handle form submission
add_action( 'admin_init', 'csvipl_handle_form_submission' );
function csvipl_handle_form_submission() {
	if ( ! isset( $_POST['csvipl_save_settings'] ) || ! wp_verify_nonce( $_POST['csvipl_settings_nonce'], 'csvipl_save_settings' ) ) {
		return;
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	$settings = array(
		'sftp_server' => sanitize_text_field( $_POST['sftp_server'] ),
		'sftp_username' => sanitize_text_field( $_POST['sftp_username'] ),
		'sftp_password' => sanitize_text_field( $_POST['sftp_password'] ),
		'remote_file_path' => sanitize_text_field( $_POST['remote_file_path'] ),
		'email_recipients' => sanitize_textarea_field( $_POST['email_recipients'] ),
		'email_subject' => sanitize_text_field( $_POST['email_subject'] ),
		'email_message' => sanitize_textarea_field( $_POST['email_message'] ),
		'upload_location' => sanitize_text_field( $_POST['upload_location'] ),
		'log_enabled' => isset( $_POST['log_enabled'] ) ? 1 : 0,
	);
	
	$saved = csvipl_save_settings( $settings );
	
	if ( $saved ) {
		add_action( 'admin_notices', function() {
			echo '<div class="notice notice-success"><p>Settings saved successfully to config.json!</p></div>';
		} );
	} else {
		add_action( 'admin_notices', function() {
			echo '<div class="notice notice-error"><p>Error saving settings to config.json. Please check file permissions.</p></div>';
		} );
	}
}

/**
 * Admin page display
 */
function csvipl_admin_page() {
	$settings = csvipl_get_settings();
	?>
	<div class="wrap">
		<h1>CSV Import Tool</h1>
		
		<div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
			<!-- Left Column: Configuration -->
			<div class="card" style="flex: 1; min-width: 500px; max-width: 500px;">
				<h2>Configuration</h2>
				<form method="post" action="">
					<?php wp_nonce_field( 'csvipl_save_settings', 'csvipl_settings_nonce' ); ?>
					
					<table class="form-table">
						<tr>
							<th scope="row">SFTP Server</th>
							<td><input type="text" name="sftp_server" value="<?php echo esc_attr( $settings['sftp_server'] ); ?>" class="regular-text" /></td>
						</tr>
						<tr>
							<th scope="row">SFTP Username</th>
							<td><input type="text" name="sftp_username" value="<?php echo esc_attr( $settings['sftp_username'] ); ?>" class="regular-text" /></td>
						</tr>
						<tr>
							<th scope="row">SFTP Password</th>
							<td><input type="password" name="sftp_password" value="<?php echo esc_attr( $settings['sftp_password'] ); ?>" class="regular-text" /></td>
						</tr>
						<tr>
							<th scope="row">Remote File Path</th>
							<td><input type="text" name="remote_file_path" value="<?php echo esc_attr( $settings['remote_file_path'] ); ?>" class="regular-text" /></td>
						</tr>
						<tr>
							<th scope="row">Email Recipients</th>
							<td><textarea name="email_recipients" rows="3" class="large-text"><?php echo esc_textarea( $settings['email_recipients'] ); ?></textarea><br>
							<small>One email per line</small></td>
						</tr>
						<tr>
							<th scope="row">Email Subject</th>
							<td><input type="text" name="email_subject" value="<?php echo esc_attr( $settings['email_subject'] ); ?>" class="regular-text" /></td>
						</tr>
						<tr>
							<th scope="row">Email Message</th>
							<td><textarea name="email_message" rows="3" class="large-text"><?php echo esc_textarea( $settings['email_message'] ); ?></textarea></td>
						</tr>
					<tr>
						<th scope="row">File Upload Location</th>
						<td><input type="text" name="upload_location" value="<?php echo esc_attr( $settings['upload_location'] ); ?>" class="regular-text" />
						<p class="description">Path where uploaded CSV files will be stored (relative to wp-content/uploads/)</p></td>
					</tr>
					<tr>
						<th scope="row">Enable Logging</th>
						<td><input type="checkbox" name="log_enabled" value="1" <?php checked( $settings['log_enabled'], 1 ); ?> /> Log import activities to cron-log.txt</td>
					</tr>
					</table>
					
					<?php submit_button( 'Save Settings', 'primary', 'csvipl_save_settings' ); ?>
				</form>
			</div>
			
			<!-- Middle Column: Import Options -->
			<div style="flex: 1; min-width: 350px; max-width: 350px;">
				<div class="card" style="margin-bottom: 20px;">
					<h2>SFTP Import</h2>
					<p>Click the button below to download CSV from SFTP and run the import process.</p>
					<button id="csvipl-run-import" class="button button-primary button-large">Run SFTP Import Now</button>
					<button id="csvipl-test-email" class="button button-secondary" style="margin-left: 10px;">Test Email</button>
					<div id="csvipl-status" style="margin-top: 15px;"></div>
				</div>
				
				<div class="card">
					<h2>Upload & Import CSV File</h2>
					<p>Upload a CSV file from your computer and run the import process.</p>
					<form id="csvipl-upload-form" enctype="multipart/form-data">
						<table class="form-table">
							<tr>
								<th scope="row">CSV File</th>
								<td>
									<input type="file" id="csvipl-csv-file" name="csv_file" accept=".csv" required />
									<p class="description">Select a CSV file to upload and import.</p>
								</td>
							</tr>
						</table>
						<button type="submit" id="csvipl-run-upload-import" class="button button-primary button-large">Upload & Import CSV</button>
						<div id="csvipl-upload-status" style="margin-top: 15px;"></div>
					</form>
				</div>
				
				<!-- Vehicle Inventory Summary Section -->
				<div class="card" style="margin-top: 20px;">
					<h2>Vehicle Inventory Summary</h2>
					<div id="csvipl-inventory-summary">
						<?php csvipl_render_inventory_summary(); ?>
					</div>
				</div>
			</div>
			
			<!-- Right Column: Import History -->
			<div style="flex: 1; min-width: 800px;">
				<div class="card">
					<h2>Import History</h2>
					<p>Last 20 imports from most recent to oldest.</p>
					<div id="csvipl-import-history" style="overflow-x: visible; overflow-y: visible;">
						<?php csvipl_render_import_history(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Send email notification (multipart when attachment provided).
 */
function csvipl_send_status_email( $subject, $message, $attachment_path = '' ) {
	$settings = csvipl_get_settings();
	$recipients = isset( $settings['email_recipients'] ) ? $settings['email_recipients'] : "bernardo.monge@progreza.com\njunaid.asghar@progreza.com\nsusana.bermudez@progreza.com";
	$to = array_filter( array_map( 'trim', explode( "\n", $recipients ) ) );
	
	// Debug logging
	$log_enabled = isset( $settings['log_enabled'] ) ? $settings['log_enabled'] : 1;
	if ( $log_enabled ) {
		$log_file = WP_CONTENT_DIR . '/cron-log.txt';
		file_put_contents( $log_file, "CSVIPL: Attempting to send email to: " . implode( ', ', $to ) . "\n", FILE_APPEND );
		file_put_contents( $log_file, "CSVIPL: Subject: " . $subject . "\n", FILE_APPEND );
		file_put_contents( $log_file, "CSVIPL: Message: " . substr( $message, 0, 100 ) . "...\n", FILE_APPEND );
	}
	
	// Validate email addresses
	$valid_emails = array();
	foreach ( $to as $email ) {
		if ( is_email( $email ) ) {
			$valid_emails[] = $email;
		} else {
			if ( $log_enabled ) {
				file_put_contents( $log_file, "CSVIPL: Invalid email address: " . $email . "\n", FILE_APPEND );
			}
		}
	}
	
	if ( empty( $valid_emails ) ) {
		if ( $log_enabled ) {
			file_put_contents( $log_file, "CSVIPL: No valid email addresses found\n", FILE_APPEND );
		}
		return false;
	}
	
	if ( ! empty( $attachment_path ) && file_exists( $attachment_path ) ) {
		$attachment = chunk_split( base64_encode( file_get_contents( $attachment_path ) ) );
		$boundary   = md5( time() );
		$headers    = array();
		$headers[]  = 'MIME-Version: 1.0';
		$headers[]  = "Content-Type: multipart/mixed; boundary=\"$boundary\"";
		$body  = "--$boundary\r\n";
		$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
		$body .= "Content-Disposition: inline\r\n\r\n";
		$body .= $message . "\r\n";
		$body .= "--$boundary\r\n";
		$body .= "Content-Type: text/csv; name=\"" . basename( $attachment_path ) . "\"\r\n";
		$body .= "Content-Disposition: attachment; filename=\"" . basename( $attachment_path ) . "\"\r\n";
		$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
		$body .= $attachment . "\r\n";
		$body .= "--$boundary--\r\n";
		$result = wp_mail( $valid_emails, $subject, $body, $headers );
	} else {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$result = wp_mail( $valid_emails, $subject, $message, $headers );
	}
	
	// Log result
	if ( $log_enabled ) {
		$log_file = WP_CONTENT_DIR . '/cron-log.txt';
		if ( $result ) {
			file_put_contents( $log_file, "CSVIPL: Email sent successfully\n", FILE_APPEND );
		} else {
			file_put_contents( $log_file, "CSVIPL: Email failed to send\n", FILE_APPEND );
			// Try alternative method if wp_mail fails
			file_put_contents( $log_file, "CSVIPL: Attempting alternative email method\n", FILE_APPEND );
			$result = csvipl_send_email_alternative( $valid_emails, $subject, $message, $attachment_path );
			if ( $result ) {
				file_put_contents( $log_file, "CSVIPL: Alternative email method succeeded\n", FILE_APPEND );
			} else {
				file_put_contents( $log_file, "CSVIPL: Alternative email method also failed\n", FILE_APPEND );
			}
		}
	}
	
	return $result;
}

/**
 * Alternative email sending method using PHP mail() function
 */
function csvipl_send_email_alternative( $to, $subject, $message, $attachment_path = '' ) {
	// Get WordPress admin email as sender
	$from_email = get_option( 'admin_email' );
	$from_name = get_option( 'blogname' );
	
	$headers = array();
	$headers[] = "From: {$from_name} <{$from_email}>";
	$headers[] = "Reply-To: {$from_email}";
	$headers[] = "X-Mailer: WordPress CSV Import Plugin";
	
	if ( ! empty( $attachment_path ) && file_exists( $attachment_path ) ) {
		// For attachments, we'll use a simpler approach
		$boundary = md5( time() );
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";
		
		$body = "--{$boundary}\r\n";
		$body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
		$body .= $message . "\r\n\r\n";
		$body .= "--{$boundary}\r\n";
		$body .= "Content-Type: application/octet-stream; name=\"" . basename( $attachment_path ) . "\"\r\n";
		$body .= "Content-Transfer-Encoding: base64\r\n";
		$body .= "Content-Disposition: attachment; filename=\"" . basename( $attachment_path ) . "\"\r\n\r\n";
		$body .= chunk_split( base64_encode( file_get_contents( $attachment_path ) ) ) . "\r\n";
		$body .= "--{$boundary}--\r\n";
	} else {
		$headers[] = "Content-Type: text/html; charset=UTF-8";
		$body = $message;
	}
	
	$success = true;
	foreach ( $to as $email ) {
		$result = mail( $email, $subject, $body, implode( "\r\n", $headers ) );
		if ( ! $result ) {
			$success = false;
		}
	}
	
	return $success;
}

/**
 * Download the CSV file over SFTP to local wp-content folder and return path.
 */
function csvipl_fetch_csv() {
	$settings = csvipl_get_settings();
	$log_enabled = isset( $settings['log_enabled'] ) ? $settings['log_enabled'] : 1;
	
	if ( $log_enabled ) {
		$log_file = WP_CONTENT_DIR . '/cron-log.txt';
		file_put_contents( $log_file, "CSVIPL: Starting csvipl_fetch_csv()\n", FILE_APPEND );
	}

	$remote_file = isset( $settings['remote_file_path'] ) ? $settings['remote_file_path'] : '/public_html/latest-inventory.csv';
	$local_file  = WP_CONTENT_DIR . '/Uploads/import-csv/latest-inventory.csv';

	$sftp_server = isset( $settings['sftp_server'] ) ? $settings['sftp_server'] : '45.77.6.66';
	$username    = isset( $settings['sftp_username'] ) ? $settings['sftp_username'] : 'csv-file';
	$password    = isset( $settings['sftp_password'] ) ? $settings['sftp_password'] : '6ktx77hZns';

	$sftp_url = "sftp://{$sftp_server}{$remote_file}";

	$ch = curl_init();
	if ( ! $ch ) {
		if ( $log_enabled ) {
			file_put_contents( $log_file, "CSVIPL: Failed to initialize cURL.\n", FILE_APPEND );
		}
		return false;
	}

	curl_setopt( $ch, CURLOPT_URL, $sftp_url );
	curl_setopt( $ch, CURLOPT_USERPWD, "$username:$password" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

	$data = curl_exec( $ch );
	if ( $data === false ) {
		$error = curl_error( $ch );
		if ( $log_enabled ) {
			file_put_contents( $log_file, "CSVIPL: cURL Error: $error\n", FILE_APPEND );
		}
		curl_close( $ch );
		return false;
	}
	curl_close( $ch );

	if ( ! is_dir( dirname( $local_file ) ) ) {
		mkdir( dirname( $local_file ), 0755, true );
	}

	$result = file_put_contents( $local_file, $data );
	if ( $result === false ) {
		if ( $log_enabled ) {
			file_put_contents( $log_file, "CSVIPL: Failed to save file\n", FILE_APPEND );
		}
		return false;
	}

	$settings = csvipl_get_settings();
	$subject = isset( $settings['email_subject'] ) ? $settings['email_subject'] : 'CSV File Found';
	$message = isset( $settings['email_message'] ) ? $settings['email_message'] : 'CSV File Found, importer function running';
	csvipl_send_status_email( $subject, $message );
	
	return $local_file;
}

/**
 * Delete all listings with matching stock-number and dealer-id.
 */
function csvipl_delete_previous_listings( $stock, $dealer_id = '' ) {
	$args = array(
		'post_type'      => 'listings',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => 'stock-number',
				'value'   => $stock,
				'compare' => '=',
			),
		),
	);
	
	// If dealer_id is provided, add it to the meta query
	if ( ! empty( $dealer_id ) ) {
		$args['meta_query'][] = array(
			'key'     => 'dealer-id',
			'value'   => $dealer_id,
			'compare' => '=',
		);
		$args['meta_query']['relation'] = 'AND';
	}

	$listings = get_posts( $args );
	foreach ( $listings as $listing ) {
		csvipl_delete_listing( $listing->ID );
	}
}

/**
 * Delete one listing and attachments.
 */
function csvipl_delete_listing( $listing_id ) {
	$attachments = get_posts( array(
		'post_type'      => 'attachment',
		'posts_per_page' => -1,
		'post_parent'    => $listing_id,
	) );
	foreach ( $attachments as $attachment ) {
		wp_delete_attachment( $attachment->ID, true );
	}
	wp_delete_post( $listing_id, true );
}

/**
 * Parse photo URLs with dealer-specific rules.
 * - DMC-Toyota: values are comma-separated, but individual URLs may contain 
 *   commas in the size parameter (e.g., ?size=1200,663). We split only on
 *   commas that are immediately followed by a new URL (http/https).
 * - Others: values are pipe-separated.
 */
function csvipl_parse_photo_urls( $dealer_id, $photo_url_list_raw ) {
    $urls = array();
    $raw  = is_string( $photo_url_list_raw ) ? trim( $photo_url_list_raw ) : '';
    if ( $raw === '' ) {
        return $urls;
    }

    if ( strcasecmp( $dealer_id, 'DMC-Toyota' ) === 0 ) {
        // Split on commas that are followed by a URL start, preserving commas inside size params
        $parts = preg_split( '/,(?=https?:\\/\\/)/', $raw );
    } else {
        $parts = explode( '|', $raw );
    }

    foreach ( $parts as $part ) {
        $candidate = trim( $part );
        if ( $candidate !== '' && filter_var( $candidate, FILTER_VALIDATE_URL ) !== false ) {
            $urls[] = $candidate;
        }
    }

    return $urls;
}

/**
 * Create new listing from CSV row.
 */
function csvipl_create_listing( $data ) {
	$listing_id = wp_insert_post( array(
		'post_title'   => $data['Year'] . ' ' . $data['Make'] . ' ' . $data['Model'] . ' ' . $data['Series'],
		'post_content' => isset( $data['Description'] ) ? $data['Description'] : '',
		'post_type'    => 'listings',
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
	) );
	if ( is_wp_error( $listing_id ) ) {
		return false;
	}

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
		'Photo Url List'         => 'photo-url-list',
		'City MPG'               => 'city_mpg',
		'Highway MPG'            => 'highway_mpg',
		'Photos'                 => 'photos',
		'Last Modified Date'     => 'photos-last-modified-date',
		'Status Code'            => 'car_sold',
		'Cost'                   => 'cost',
		'Series Detail'          => 'series-detail',
		'Inspection Checklist #' => 'inspection-checklist-number',
		'Engine'                 => 'engine',
		'Certification'          => 'certification',
		'Option Codes'           => 'option-codes',
		'MiscPrice1'             => 'miscprice-1',
		'MiscPrice2'             => 'miscprice-2',
		'MiscPrice3'             => 'miscprice-3',
		'Disposition'            => 'disposition',
		'Fuel Type'              => 'fuel-type',
	);
	foreach ( $post_metas as $key => $meta_key ) {
		$value = isset( $data[ $key ] ) ? trim( $data[ $key ] ) : '';
		update_post_meta( $listing_id, $meta_key, $value );
	}

	$post_name = $data['Year'] . '-' . $data['Make'] . '-' . $data['Model'] . '-' . $data['Stock #'];
	wp_update_post( array( 'ID' => $listing_id, 'post_name' => $post_name ) );
	update_post_meta( $listing_id, 'postName', $data['Year'] . ' ' . $data['Make'] . ' ' . $data['Model'] . ' ' . $data['Series'] );

    $image_urls = array();
    if ( isset( $data['Photo Url List'] ) ) {
        $dealer_for_photos = isset( $data['DealerId'] ) ? trim( $data['DealerId'] ) : '';
        $image_urls = csvipl_parse_photo_urls( $dealer_for_photos, $data['Photo Url List'] );
    }
	if ( ! empty( $image_urls ) ) {
		update_post_meta( $listing_id, 'photo_urls', $image_urls );
	}
	
	// Handle Photos field separately
	if ( isset( $data['Photos'] ) && ! empty( $data['Photos'] ) ) {
		update_post_meta( $listing_id, 'photos', $data['Photos'] );
	}

	$listing_options = get_post_meta( $listing_id, 'listing_options', true );
	$listing_options = ! empty( $listing_options ) ? unserialize( $listing_options ) : array();
	$listing_options['price']['value']     = isset( $data['Price'] ) ? $data['Price'] : '';
	$listing_options['price']['original']  = isset( $data['Other Price'] ) ? $data['Other Price'] : '';
	$listing_options['city_mpg']['value']  = isset( $data['City MPG'] ) ? $data['City MPG'] : '';
	$listing_options['highway_mpg']['value'] = isset( $data['Highway MPG'] ) ? $data['Highway MPG'] : '';
	update_post_meta( $listing_id, 'listing_options', serialize( $listing_options ) );

	return $listing_id;
}

/**
 * Update meta for listings that already exist (by stock-number), including photos and options.
 */
function csvipl_update_listing_data( $already_present_stock, $listings_array ) {
	if ( empty( $already_present_stock ) ) {
		return array();
	}
	$args = array(
		'post_type'      => 'listings',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => 'stock-number',
				'value'   => $already_present_stock,
				'compare' => 'IN',
			),
		),
	);
	$update_query   = get_posts( $args );
	$listing        = array();
	$listingsStocks = array();
	foreach ( $update_query as $query ) {
		$listing_data  = get_post_meta( $query->ID, '', true );
		$listingStock  = get_post_meta( $query->ID, 'stock-number', true );
		if ( ! isset( $listing[ $listingStock ] ) ) {
			$listing[ $listingStock ] = array();
		}
		$listingsStocks[ $listingStock ][] = $query->ID;
		$listing[ $listingStock ][]        = $listing_data;
	}

	foreach ( $listings_array as $stock => $listingArr ) {
		if ( array_key_exists( $stock, $listing ) ) {
			$keys_map = array(
				// Existing fields
				'DealerId'              => 'dealer-id',
				'Transmission'          => 'transmission',
				'Series'                => 'series',
				'Colour'                => 'exterior-color',
				'Interior Color'        => 'interior-color',
				'City MPG'              => 'city_mpg',
				'Highway MPG'           => 'highway_mpg',
				'Price'                 => 'original_price',
				'Other Price'           => 'current_price',
				'Invoice'               => 'invoice',
				'Book Value'            => 'book-value',
				'Cost'                  => 'cost',
				'Series Detail'         => 'series-detail',
				'Certification'         => 'certification',
				'Certified'             => 'certified',
				'MiscPrice1'            => 'miscprice-1',
				'MiscPrice2'            => 'miscprice-2',
				'MiscPrice3'            => 'miscprice-3',
				'Odometer'              => 'odometer',
				'Photos'                => 'photos',
				// Previously missing fields - now included
				'VIN'                   => 'vin-number',
				'New/Used'              => 'condition',
				'Year'                  => 'year',
				'Make'                  => 'make',
				'Model'                 => 'model',
				'Model Number'          => 'model-number',
				'Body'                  => 'body-style',
				'Body Type'             => 'type-of-vehicle',
				'Body Door Ct'          => 'doors',
				'Engine Cylinder Ct'    => 'cylinders',
				'Engine Displacement'   => 'engine-displacement',
				'Drivetrain Desc'       => 'drivetrain',
				'Inventory Date'        => 'inventory-date',
				'Description'           => 'description',
				'Features'              => 'features',
				'Status Code'           => 'car_sold',
				'Inspection Checklist #'=> 'inspection-checklist-number',
				'Engine'                => 'engine',
				'Option Codes'          => 'option-codes',
				'Disposition'           => 'disposition',
				'Fuel Type'             => 'fuel-type',
			);
			foreach ( $keys_map as $csv_key => $meta_key ) {
				$current_val = isset( $listing[ $stock ][0][ $meta_key ][0] ) ? $listing[ $stock ][0][ $meta_key ][0] : null;
				$new_val     = isset( $listingArr[0][ $csv_key ] ) ? $listingArr[0][ $csv_key ] : null;
				if ( $new_val !== $current_val ) {
					update_post_meta( $listingsStocks[ $stock ][0], $meta_key, $new_val );
					
					// Update post_content when Description changes
					if ( $csv_key === 'Description' ) {
						wp_update_post( array(
							'ID'           => $listingsStocks[ $stock ][0],
							'post_content' => $new_val
						) );
					}
					
					if ( in_array( $meta_key, array( 'city_mpg', 'highway_mpg', 'original_price', 'current_price' ), true ) ) {
						$listing_options = get_post_meta( $listingsStocks[ $stock ][0], 'listing_options', true );
						$listing_options = ! empty( $listing_options ) ? unserialize( $listing_options ) : array();
						$valKey = ( $meta_key === 'current_price' ) ? 'original' : 'value';
						$optKey = ( $meta_key === 'original_price' || $meta_key === 'current_price' ) ? 'price' : $meta_key;
						$listing_options[ $optKey ][ $valKey ] = $new_val;
						update_post_meta( $listingsStocks[ $stock ][0], 'listing_options', serialize( $listing_options ) );
					}
					delete_transient( 'product_card_' . $listingsStocks[ $stock ][0] );
				}
			}
			
			// Update post title and post_name if Year, Make, Model, or Series changed
			if ( isset( $listingArr[0]['Year'] ) && isset( $listingArr[0]['Make'] ) && 
			     isset( $listingArr[0]['Model'] ) && isset( $listingArr[0]['Series'] ) ) {
				$year   = $listingArr[0]['Year'];
				$make   = $listingArr[0]['Make'];
				$model  = $listingArr[0]['Model'];
				$series = $listingArr[0]['Series'];
				$stock_num = $listingArr[0]['Stock #'];
				
				$new_title = $year . ' ' . $make . ' ' . $model . ' ' . $series;
				$new_slug  = $year . '-' . $make . '-' . $model . '-' . $stock_num;
				
				// Get current post title
				$current_post = get_post( $listingsStocks[ $stock ][0] );
				if ( $current_post && $current_post->post_title !== $new_title ) {
					wp_update_post( array(
						'ID'         => $listingsStocks[ $stock ][0],
						'post_title' => $new_title,
						'post_name'  => $new_slug
					) );
					update_post_meta( $listingsStocks[ $stock ][0], 'postName', $new_title );
				}
			}

			// Photos
			$existing_last_modified = isset( $listing[ $stock ][0]['photos-last-modified-date'][0] ) ? $listing[ $stock ][0]['photos-last-modified-date'][0] : '';
			$new_last_modified      = isset( $listingArr[0]['Last Modified Date'] ) ? $listingArr[0]['Last Modified Date'] : '';
            if ( $new_last_modified !== $existing_last_modified ) {
				update_post_meta( $listingsStocks[ $stock ][0], 'photos-last-modified-date', $new_last_modified );
				$image_urls = array();
				if ( isset( $listingArr[0]['Photo Url List'] ) ) {
                    $dealer_for_photos = isset( $listingArr[0]['DealerId'] ) ? trim( $listingArr[0]['DealerId'] ) : '';
                    $image_urls = csvipl_parse_photo_urls( $dealer_for_photos, $listingArr[0]['Photo Url List'] );
				}
				update_post_meta( $listingsStocks[ $stock ][0], 'photo_urls', $image_urls );
				delete_transient( 'product_card_' . $listingsStocks[ $stock ][0] );
			}
			
			// Handle Photos field update
			if ( isset( $listingArr[0]['Photos'] ) && ! empty( $listingArr[0]['Photos'] ) ) {
				update_post_meta( $listingsStocks[ $stock ][0], 'photos', $listingArr[0]['Photos'] );
				delete_transient( 'product_card_' . $listingsStocks[ $stock ][0] );
			}
		}
	}
	return $listing;
}

/**
 * Main cron job: replicate listingsBatchesImport()
 */
function csvipl_listings_batches_import() {
	$file = csvipl_fetch_csv();
	if ( empty( $file ) ) {
		return;
	}

	if ( ( $handle = fopen( $file, 'r' ) ) !== false ) {
		$header              = fgetcsv( $handle );
		$listingsArray       = array();
		$csvStockNumbers     = array();
		$csvDealerId         = '';
		$totalListings       = 0;
		
		// Get dealer-id from first data row
		$firstRow = fgetcsv( $handle );
		if ( $firstRow && isset( $firstRow[0] ) ) {
			$csvDealerId = trim( $firstRow[0] );
		}
		
		// Reset file pointer to beginning
		rewind( $handle );
		$header = fgetcsv( $handle ); // Skip header again
		
		// Get existing stock numbers for this specific dealer only
		$presentStockNumbers = array();
		$stockPosts = get_posts( array( 
			'post_type' => 'listings', 
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'dealer-id',
					'value' => $csvDealerId,
					'compare' => '='
				)
			)
		) );
		foreach ( $stockPosts as $post ) {
			$stockNumber = get_post_meta( $post->ID, 'stock-number', true );
			$presentStockNumbers[] = strtoupper( $stockNumber );
		}
		
		while ( ( $data = fgetcsv( $handle ) ) !== false ) {
			$listing_data = array();
			foreach ( $header as $index => $column ) {
				if ( isset( $data[ $index ] ) ) {
					$listing_data[ $column ] = $data[ $index ];
				}
			}
			if ( isset( $listing_data['Stock #'] ) ) {
				if ( ! isset( $listingsArray[ $listing_data['Stock #'] ] ) ) {
					$listingsArray[ $listing_data['Stock #'] ] = array();
				}
				$listingsArray[ $listing_data['Stock #'] ][] = $listing_data;
				$csvStockNumbers[] = strtoupper( $listing_data['Stock #'] );
				$totalListings++;
			}
		}
		fclose( $handle );

		// Only delete stocks that are no longer in CSV for this specific dealer
		$tobeDeletedStocks = array_diff( $presentStockNumbers, $csvStockNumbers );
		if ( ! empty( $tobeDeletedStocks ) ) {
			foreach ( $tobeDeletedStocks as $stocks ) {
				csvipl_delete_previous_listings( $stocks, $csvDealerId );
				$removedIndex = array_search( $stocks, $presentStockNumbers, true );
				if ( $removedIndex !== false ) {
					unset( $presentStockNumbers[ $removedIndex ] );
				}
			}
		}

		$alreadyPresentStock = array();
		foreach ( $listingsArray as $stock => $listing ) {
			$csvStock = strtoupper( $stock );
			if ( in_array( $csvStock, $presentStockNumbers, true ) ) {
				$alreadyPresentStock[] = $csvStock;
				continue;
			}
			csvipl_create_listing( $listing[0] );
			$presentStockNumbers[] = $csvStock;
		}

		csvipl_update_listing_data( $alreadyPresentStock, $listingsArray );

		$timezone     = new DateTimeZone( 'America/Denver' );
		$date         = new DateTime( 'now', $timezone );
		$current_time = $date->format( 'Y-m-d h:i:s A' );
		
		$settings = csvipl_get_settings();
		$subject  = isset( $settings['email_subject'] ) ? $settings['email_subject'] : 'CSV Import Complete';
		$message  = isset( $settings['email_message'] ) ? $settings['email_message'] : 'CSV import process completed.';
		$message .= ' Total listings ' . $totalListings . ' were imported on ' . $current_time . '. Packet ID: ' . $GLOBALS['CSVIPL_PACKET_ID'];
		
		csvipl_send_status_email( $subject, $message, $file );

		// Add to import history
		csvipl_add_import_history( basename( $file ), $totalListings, 'sftp', 'success' );

		update_option( 'csvipl_packet_id', (int) $GLOBALS['CSVIPL_PACKET_ID'] + 1 );

		$backup_directory = WP_CONTENT_DIR . '/Uploads/backups/';
		if ( ! file_exists( $backup_directory ) ) {
			mkdir( $backup_directory, 0755, true );
		}
		$extension       = pathinfo( $file, PATHINFO_EXTENSION );
		$backup_filename = basename( $file, '.' . $extension ) . '_' . date( 'Y-m-d_His' ) . '.' . $extension;
		$backup_path     = $backup_directory . $backup_filename;
		if ( rename( $file, $backup_path ) ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}
	}
	
	echo '<div class="notice notice-success"><p>Import completed successfully! Total listings processed: ' . $totalListings . '</p></div>';
}

/**
 * Main import function for uploaded CSV files
 */
function csvipl_listings_batches_import_from_file( $file_path, $original_filename = '' ) {
	if ( ! file_exists( $file_path ) ) {
		// Add to import history with error status
		csvipl_add_import_history( basename( $file_path ), 0, 'upload', 'error', $original_filename );
		echo '<div class="notice notice-error"><p>CSV file not found.</p></div>';
		return;
	}

	if ( ( $handle = fopen( $file_path, 'r' ) ) !== false ) {
		$header              = fgetcsv( $handle );
		$listingsArray       = array();
		$csvStockNumbers     = array();
		$csvDealerId         = '';
		$totalListings       = 0;
		
		// Get dealer-id from first data row
		$firstRow = fgetcsv( $handle );
		if ( $firstRow && isset( $firstRow[0] ) ) {
			$csvDealerId = trim( $firstRow[0] );
		}
		
		// Reset file pointer to beginning
		rewind( $handle );
		$header = fgetcsv( $handle ); // Skip header again
		
		// Get existing stock numbers for this specific dealer only
		$presentStockNumbers = array();
		$stockPosts = get_posts( array( 
			'post_type' => 'listings', 
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'dealer-id',
					'value' => $csvDealerId,
					'compare' => '='
				)
			)
		) );
		foreach ( $stockPosts as $post ) {
			$stockNumber = get_post_meta( $post->ID, 'stock-number', true );
			$presentStockNumbers[] = strtoupper( $stockNumber );
		}
		
		while ( ( $data = fgetcsv( $handle ) ) !== false ) {
			$listing_data = array();
			foreach ( $header as $index => $column ) {
				if ( isset( $data[ $index ] ) ) {
					$listing_data[ $column ] = $data[ $index ];
				}
			}
			if ( isset( $listing_data['Stock #'] ) ) {
				if ( ! isset( $listingsArray[ $listing_data['Stock #'] ] ) ) {
					$listingsArray[ $listing_data['Stock #'] ] = array();
				}
				$listingsArray[ $listing_data['Stock #'] ][] = $listing_data;
				$csvStockNumbers[] = strtoupper( $listing_data['Stock #'] );
				$totalListings++;
			}
		}
		fclose( $handle );

		// Only delete stocks that are no longer in CSV for this specific dealer
		$tobeDeletedStocks = array_diff( $presentStockNumbers, $csvStockNumbers );
		if ( ! empty( $tobeDeletedStocks ) ) {
			foreach ( $tobeDeletedStocks as $stocks ) {
				csvipl_delete_previous_listings( $stocks, $csvDealerId );
				$removedIndex = array_search( $stocks, $presentStockNumbers, true );
				if ( $removedIndex !== false ) {
					unset( $presentStockNumbers[ $removedIndex ] );
				}
			}
		}

		$alreadyPresentStock = array();
		foreach ( $listingsArray as $stock => $listing ) {
			$csvStock = strtoupper( $stock );
			if ( in_array( $csvStock, $presentStockNumbers, true ) ) {
				$alreadyPresentStock[] = $csvStock;
				continue;
			}
			csvipl_create_listing( $listing[0] );
			$presentStockNumbers[] = $csvStock;
		}

		csvipl_update_listing_data( $alreadyPresentStock, $listingsArray );

		$timezone     = new DateTimeZone( 'America/Denver' );
		$date         = new DateTime( 'now', $timezone );
		$current_time = $date->format( 'Y-m-d h:i:s A' );
		
		$settings = csvipl_get_settings();
		$subject  = isset( $settings['email_subject'] ) ? $settings['email_subject'] : 'CSV Import Complete';
		$message  = isset( $settings['email_message'] ) ? $settings['email_message'] : 'CSV import process completed.';
		$message .= ' Total listings ' . $totalListings . ' were imported on ' . $current_time . '. Packet ID: ' . $GLOBALS['CSVIPL_PACKET_ID'];
		
		// Send email with uploaded file as attachment
		csvipl_send_status_email( $subject, $message, $file_path );

		// Add to import history
		csvipl_add_import_history( basename( $file_path ), $totalListings, 'upload', 'success', $original_filename );

		update_option( 'csvipl_packet_id', (int) $GLOBALS['CSVIPL_PACKET_ID'] + 1 );

		echo '<div class="notice notice-success"><p>Upload import completed successfully! Total listings processed: ' . $totalListings . '</p></div>';
	} else {
		// Add to import history with error status
		csvipl_add_import_history( basename( $file_path ), 0, 'upload', 'error', $original_filename );
		echo '<div class="notice notice-error"><p>Failed to read CSV file.</p></div>';
	}
}

?>


