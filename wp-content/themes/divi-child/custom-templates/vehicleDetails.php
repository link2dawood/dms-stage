<?php
function divi_child_get_vehicle_title($id) {
    $title = get_the_title();
    return $title;
}
function divi_child_get_vehicle_thumbnail($id) {
    $galleryImages = get_post_meta($id, 'gallery_images', true);
    $galleryImage = wp_get_attachment_url($galleryImages[0]);
    if( getimagesize($galleryImage) ) {
        return $galleryImage;
    } else {
        return 'http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg';
    }
}
function divi_child_get_vehicle_price($id) {
    $mainPrice = get_post_meta($id, 'original_price', true);
    $formattedMainPrice = number_format((int)$mainPrice);
    switch ($formattedMainPrice) {
        case 0:
            return '<a href=\'tel:' . get_field('quick_call_phone_number', 'options') . '\' class=\'quick-call-link\'><i class=\'fa fa-phone\'></i></a>';
        case '':
            return '<a href=\'tel:' . get_field('quick_call_phone_number', 'options') . '\' class=\'quick-call-link\'><i class=\'fa fa-phone\'></i></a>';
        default:
            return '$' . $formattedMainPrice;
    }
}
function divi_child_get_vehicle_desc($id) {
    $postDesc = strip_tags(get_the_content($id));
    $output = '';
    if (!empty($postDesc)) {
        $output .= '<div class="mb-20 text-grey-3 vehicle-description">';
        $postDescWords = explode(' ', $postDesc);
        if(count($postDescWords) > 21){
            $shortDesc = implode(' ', array_slice($postDescWords, 0, 21));
            $output .= '<p class="text-grey-4">' . $shortDesc . ' <a href="' . get_the_permalink() . '">...Read More...</a></p>';
        } else {
            $output .= '<p class="text-grey-4">' . $postDesc . '</p>';
        }        
        $output .= '</div>';
    } else {
        $output .= '<div class="mb-30 text-grey-3 vehicle-description">';
        $output .= '<p>To get more information on this vehicle call us <a href="tel:'.get_field('quick_call_phone_number', 'options').'" class="quick-call-link"><i class="fa fa-phone"></i></a></p>';
        $output .= '</div>';
    }
    return $output;
}
function get_listing_info($meta_key, $name, $id) {
    $value = get_post_meta($id, $meta_key, true);
    if( $value && $value !== 'None' && $value !== null && $value !== '' ) {
    $output = '';
    if( $meta_key != 'odometer' && $meta_key != 'certified' && $meta_key != 'stock-number' && $meta_key != 'drivetrain' ) {
        $output .= '<div class="justify-content-between list-view-flex">';
    }else{
        $output .= '<div class="d-flex justify-content-between">';
    }
    $output .= '<p class="text-grey-4 text-uppercase p-0">'.$name.'</p>';
    if( $meta_key == 'odometer' ) {
        $value = number_format((int)$value);
    }
        if( mb_strlen($value) > 20 ) {
            $output .= '<p class="left-listing-info-text text-uppercase text-grey-4">'.substr($value, 0 , 20).'...</p>';
        }else{
            $output .= '<p class="left-listing-info-text text-uppercase text-grey-4">'.$value.'</p>';
        }
        $output .= '</div>';
    }
    return $output;
}