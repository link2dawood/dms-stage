<?php
    // Add a custom admin menu
    function add_custom_admin_page() {
        add_menu_page(
            'User\'s Liked Vehicles Info', // Page title
            'Liked Vehicles Info',       // Menu title
            'manage_options',    // Capability required to access
            'users-liked-vehicles-info', // Menu slug
            'printUsersLikedVehiclesInfo', // Callback function to display content
            'dashicons-heart',   // Icon URL or dashicon name
            25  // Menu position (adjust as needed)
        );
    }
    add_action('admin_menu', 'add_custom_admin_page');

    // Callback function to display content on the admin page
    function printUsersLikedVehiclesInfo() {
       $likedVehicles = '<div class="wrap">'.
                        '<h1>Welcome</h1>'.
                        '<p>This is a breif info about the vehicles users liked on website</p>'.
                        '<table class="table" style="width:50%;">'.
                        '<thead>'.
                        '<tr>'.
                        '<th>Stock #</th>'.
                        '<th>Vehicle Title</th>'.
                        '<th>Like Count</th>'.
                        '</tr>'.
                        '</thead>'.
                        '<tbody>';
        $table_name = accessWPDB()->prefix . 'user_liked_vehicles';
        $recentQuery = accessWPDB()->prepare("SELECT * FROM $table_name");
        $updateResult = accessWPDB()->get_row($recentQuery, ARRAY_A);
        if( !$updateResult ) {
            $recentListingsIDs = array(0);
        }else {
            $recentListingsIDs = !empty($updateResult['user_liked_vehicles']) ? unserialize($updateResult['user_liked_vehicles']) : array();
        }

       // Create an array to keep track of like counts for each post
        $likeCounts = array();

        foreach ($recentListingsIDs as $likedPosts) {
            if (!isset($likeCounts[$likedPosts])) {
                $likeCounts[$likedPosts] = 1;
            } else {
                $likeCounts[$likedPosts]++;
            }
        }
        // Display the liked vehicles in the table
        foreach ($likeCounts as $postID => $likeCount) {
            $post = get_post($postID);
            if ($post) {
                $stock_number = get_post_meta($postID, 'stock-number', true); // Replace 'stock_number' with the actual meta key for stock number
                $vehicle_title = get_the_title($postID);

                $likedVehicles .= '<tr>'.
                                '<td style="border:1px solid;padding:.5rem;">' . $stock_number . '</td>'.
                                '<td style="border:1px solid;padding:.5rem;">' . $vehicle_title . '</td>'.
                                '<td style="border:1px solid;padding:.5rem;">' . $likeCount . '</td>'.
                                '</tr>';
            }
        }

        $likedVehicles .= '</tbody>'.
                        '</table>'.

       '</div>';

       echo $likedVehicles;
    }
