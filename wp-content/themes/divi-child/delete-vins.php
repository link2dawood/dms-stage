<?php /** Template Name: Delete Vins */

get_header();

if (current_user_can('administrator')) {
    $vins_to_delete = [
        'JTMMWRFV8RD587500',
        '5TFLA5DB9RX146081',
        'JTME6RFV8RD565355',
        'JTMD6RFV9RD127746',
        '4T3RWRFV1RU149765',
        'JTMRWRFVXRD233452',
        '2T3N1RFV2RW486474',
        'JTDBCMFEXR3029138',
        'JTDADABU6R3022353',
        'JTDAAAAF8R3030322',
        '2T3UWRFV2RW235208',
        'JTDADABU6R3016553',
        '7MUFBABG8RV041626',
        'JTDACAAU6R3027606',
        'JTDACACU1R3020785',
        'JTMFB3FV8RD174140'
    ];

    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<tr><th>Vehicle Name</th><th>Condition</th><th>VIN</th><th>Status</th></tr>';

    foreach ($vins_to_delete as $vin) {
        $posts = get_posts([
            'post_type'   => 'listings',
            'meta_key'    => 'vin-number',
            'meta_value'  => $vin,
            'numberposts' => -1,
        ]);

        if (empty($posts)) {
            echo "<tr><td colspan='4'>No post found for VIN: {$vin}</td></tr>";
            continue;
        }

        foreach ($posts as $post) {
            $vehicle_name = get_the_title($post->ID);
            $condition = get_post_meta($post->ID, 'condition', true); // adjust meta key if different

            $deleted = wp_delete_post($post->ID, true);
// 			$deleted = false; 
			$status = $deleted ? 'Deleted' : 'Failed';

            echo '<tr>';
            echo '<td>' . esc_html($vehicle_name) . '</td>';
            echo '<td>' . esc_html($condition) . '</td>';
            echo '<td>' . esc_html($vin) . '</td>';
            echo '<td>' . esc_html($status) . '</td>';
            echo '</tr>';
        }
    }

    echo '</table>';
}

get_footer();
