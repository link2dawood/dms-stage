<?php
function cardHTML($video) {
    $thumbnail = isset($video[0]) && strlen(trim($video[0])) > 0 ? $video[0] : "http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg";
    $category = isset($video[2]) && strlen(trim($video[2])) > 0 ? "<span class='media-gallery-post-cat-text'>".$video[2]."</span>" : '';
    $title = isset($video[3]) && strlen(trim($video[3])) > 0 ? $video[3] : 'Coming soon';
    $description = isset($video[4]) && strlen(trim($video[4])) > 0 ? "<p class='font-md font-helvetica text-grey-6'>".(count(explode(' ', $video[4])) > 20 ? implode(' ', array_slice(explode(' ', $video[4]), 0, 20))."..." : $video[4])."</p>" : '';
    $videoUrl = isset($video[1]) && strlen(trim($video[1])) > 0 ? $video[1] : 'https://www.youtube.com/embed/uk-mrLV7QXc';

    $html = "<div class='media-card position-relative shadow-third'>";
    $html .= "<div class='media-card-thumbnail position-relative d-flex align-items-center justify-content-center'>";
    $html .= "<img class='media-gallery-popup-trigger w-100 h-100 object_fit_cover' src='$thumbnail' title='iframe title' data-url='$videoUrl' width='349' height='196' loading='lazy'>";
    $html .= "<span class='media-gallery-popup-trigger position-absolute text-white rounded-circle-px d-flex align-items-center justify-content-center cursor-pointer' data-url='$videoUrl'><i class='fa fa-play text-white'></i></span>";
    $html .= "</div>";
    $html .= "<div class='media-card-content px-15'>";
    $html .= "<p class='mt-30 mb-1 font-sm font-helvetica text-grey-6 text-uppercase p-0'>Category: $category</p>";
    $html .= "<h2 class='m-0 font-weight-bold font-helvetica text-uppercase pb-15 text-grey-6 font-xxl'>$title</h2>";
    $html .= $description;
    $html .= "<div class='d-flex w-100 justify-content-center align-items-center position-absolute media-gallery-cta'>";
    $html .= "<a class='media-gallery-popup-trigger bg-secondary text-white rounded-circle-px font-20 font-helvetica px-20 py-15' data-url='$videoUrl'>WATCH NOW</a>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= '</div>';

    return $html;
}
function highlightHTML($highlight) {
    $image = isset($highlight[0]) && !empty(trim($highlight[0])) ? $highlight[0] : site_url() . '';
    $title = isset($highlight[4]) && !empty(trim($highlight[4])) ? $highlight[4] : 'Vehicle highlight';
    $description = isset($highlight[5]) && !empty(trim($highlight[5])) ? $highlight[5] : '';
    $width = isset($highlight[1]) && !empty(trim($highlight[1])) ? $highlight[1] : '';
    $height = isset($highlight[2]) && !empty(trim($highlight[2])) ? $highlight[2] : '';
    $alt = isset($highlight[3]) && !empty(trim($highlight[3])) ? $highlight[3] : '';

    $html = '<div class="col-12 col-lg-4">';
    $html .= '<img src="'.$image.'" class="w-100" width="'.$width.'" height="'.$height.'" alt="'.$alt.'" style="height:360px;" loading="lazy" itemprop="image" />';
    $html .= '<div>';
    $html .= '<h2 class="font-segoe font-20 text-center text-capitalize text-grey-3 mt-20 mb-20">' . $title . '</h2>';
    if (!empty($description)) {
        $html .= '<p class="font-segoe font-md text-grey-3 text-center font-weight-normal">' . $description . '</p>';
    }
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}