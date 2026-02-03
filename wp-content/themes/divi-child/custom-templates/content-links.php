<?php
    function divi_child_content_links() {
        $contentLinks = '<h3 class="font-segoe font-weight-bold text-capitalize font-xxl text-grey-3 pb-15 border-bottom">Quick Links</h3>';
        $contentLinksArr = array(
            'Inventory' => '/inventory',
            'Schedule Service' => '/service-and-parts/schedule-express-service-durango-co/',
            'DGO Accessories' => '/service-and-parts/accessories/',
            'About Value Autos' => '/about-us',
            'DMC Blog' => '/blog',
        );
        $contentLinks .= '<ul>';
        foreach( $contentLinksArr as $name => $link ) {
            $contentLinks .= '<li class="mb-15"><a class="text-capitalize font-helvetica text-third font-lg" href="'.$link.'">'.$name.'</a></li>';
        }
        $contentLinks .= '</ul>';

        return $contentLinks;
    }