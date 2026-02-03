<?php

    function durango_remove_deleted_vehicles_from_users_records() {

        /*
        * Table Names */
        $compareTableName = accessWPDB()->prefix . 'user_compared_vehicles';
        $likedTableName = accessWPDB()->prefix . 'user_liked_vehicles';
        $recentlyViewedTableName = accessWPDB()->prefix . 'user_recently_viewed';

        /*
        *** Prepare the query **/
        $compareTableQuery = accessWPDB()->prepare("SELECT * FROM $compareTableName");
        $likedTableQuery = accessWPDB()->prepare("SELECT * FROM $likedTableName");
        $recentlyViewedQuery = accessWPDB()->prepare("SELECT * FROM $recentlyViewedTableName");

        /*
        *** Get results from the prepared query */
        $compareQueryResult = accessWPDB()->get_results($compareTableQuery, ARRAY_A);
        $likedQueryResult = accessWPDB()->get_results($likedTableQuery, ARRAY_A);
        $recentlyViewedQueryResult = accessWPDB()->get_results($recentlyViewedQuery, ARRAY_A);

        /*
        *** Create 1 array and store current listings IDs in it
        *** and 1 more array to store the user IP and vehicles IDS */
        $presentListings = array();
        $userRecords = array();
        $args = array(
            'post_type' => 'listings',
            'posts_per_page' => '-1',
            'post_status' => 'published',
        );

        $presentListingsQuery = get_posts($args);
        foreach($presentListingsQuery as $listing) {
            $presentListings[] = $listing->ID;
        }

        /*
        *** Loop through the current present listings array */
        // foreach($compareQueryResult['user_compared_vehicles'] as $id) {
        //     if()
        // }
        
        return $compareQueryResult;

    }
    

    // Schedule the cron event to run daily
    // if (!wp_next_scheduled('remove_deleted_vehicles_from_users_records')) {
    //     wp_schedule_event(current_time('timestamp'), 'daily', 'remove_deleted_vehicles_from_users_records');
    // }

    // add_action('remove_deleted_vehicles_from_users_records', 'durango_remove_deleted_vehicles_from_users_records');