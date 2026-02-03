<?php

add_filter( 'cron_schedules', 'reset_after_two_hours' );
function reset_after_two_hours( $schedules ) {
    $schedules['two_hours'] = array(
        'interval' => 7200,
        'display'  => esc_html__( 'Every Two Hours' ), );
    return $schedules;
}
// Schedule the task to run every 2 hours
if (!wp_next_scheduled('reset_recent_view_vehicles')) {
    wp_schedule_event(time(), 'two_hours', 'reset_recent_view_vehicles');
}

// Hook your function to the 'reset_recent_view_vehicles' event
add_action('reset_recent_view_vehicles', 'reset_recent_view_vehicles_callback');

// Define the custom function to perform the task
function reset_recent_view_vehicles_callback() {
    $table_name = accessWPDB()->prefix . 'user_recently_viewed';

    // Calculate the timestamp from 24 hours ago
    $twentyFourHoursAgo = time() - (24 * 3600);

    // Delete entries older than 24 hours and records with NULL timestamp
    accessWPDB()->query(accessWPDB()->prepare("DELETE FROM $table_name WHERE (`timestamp` < %d) OR (`timestamp` IS NULL)", $twentyFourHoursAgo));

    // Check for errors
    if (accessWPDB()->last_error) {
        error_log('Error deleting records: ' . accessWPDB()->last_error);
    }
}

 /*
  *** Function to remove deleted vehicles id's from db
  *** Schedule the task to run weekly */
  
  if (!wp_next_scheduled('durango_remove_deleted_vehicles')) {
      wp_schedule_event(time(), 'weekly', 'durango_remove_deleted_vehicles');
  }
  add_action('durango_remove_deleted_vehicles', 'durango_value_autos_remove_deleted_vehicles_ids');
  
  function durango_value_autos_remove_deleted_vehicles_ids() {
    $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
    $likes_table_name = accessWPDB()->prefix . 'user_liked_vehicles';
    $current_listings = array();
    // Run a WP_Query to get current listings in website
    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => -1,
    );
    $current_listings_query = new WP_Query($args);

    if ($current_listings_query->have_posts()) {
        while ($current_listings_query->have_posts()) {
            $current_listings_query->the_post();
            $current_listings[] = get_the_ID();
        }
        wp_reset_postdata();
    }

    /*
     *** Prepare query and get results from compare table ***
    */
    $prepare_query = accessWPDB()->prepare("SELECT id, user_compared_vehicles FROM $table_name");
    $like_prepare_query = accessWPDB()->prepare("SELECT id, user_liked_vehicles FROM $likes_table_name");

    $get_results = accessWPDB()->get_results($prepare_query, ARRAY_A);
    $like_get_results = accessWPDB()->get_results($like_prepare_query, ARRAY_A);

    if(is_wp_error($get_results)) {
        error_log('Error in preparing query for user_compared_vehicles: ' . $get_results->get_error_message());
        return;
    }
    if (is_wp_error($like_get_results)) {
        error_log('Error in preparing query for user_liked_vehicles: ' . $like_get_results->get_error_message());
        return;
    }

    // Delete ID's from user compared table
    foreach ($get_results as $compare_results) {
        $user_compared_vehicles = unserialize($compare_results['user_compared_vehicles']);
        $filtered_vehicles = array_filter($user_compared_vehicles, function ($vehicle_id) use ($current_listings) {
            return in_array($vehicle_id, $current_listings);
        });

        // If any vehicles were removed, update the database
        if ($user_compared_vehicles !== $filtered_vehicles) {
            $user_compared_vehicles = array_values($filtered_vehicles);

            if (empty($user_compared_vehicles)) {
                accessWPDB()->delete(
                    $table_name,
                    array('id' => $compare_results['id'])
                );
            } else {
                accessWPDB()->update(
                    $table_name,
                    array('user_compared_vehicles' => serialize($user_compared_vehicles)),
                    array('id' => $compare_results['id']),
                );
            }
        }
    }
    // Delete ids from user liked vehicles table
    foreach ($like_get_results as $like_results) {
        $user_liked_vehicles = unserialize($like_results['user_liked_vehicles']);
        $filtered_vehicles = array_filter($user_liked_vehicles, function ($vehicle_id) use ($current_listings) {
            return in_array($vehicle_id, $current_listings);
        });

        // If any vehicles were removed, update the database
        if ($user_liked_vehicles !== $filtered_vehicles) {
            $user_liked_vehicles = array_values($filtered_vehicles);

            if (empty($user_liked_vehicles)) {
                accessWPDB()->delete(
                    $likes_table_name,
                    array('id' => $like_results['id'])
                );
            } else {
                accessWPDB()->update(
                    $likes_table_name,
                    array('user_liked_vehicles' => serialize($user_liked_vehicles)),
                    array('id' => $like_results['id']),
                );
            }
        }
    }

    // Send Email To Developer
    wp_mail(durango_developer_email(), 'Deleted Vehicles Records Removed', 'durango_value_autos_remove_deleted_vehicles_ids() function is completed and successfully removed records of deleted vehicles from database');

}

