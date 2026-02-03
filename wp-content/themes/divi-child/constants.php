<?php
    // Constants functions
    function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check if the IP is from a shared client
            $userIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if the IP is a forwarded IP address
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // Use the remote IP address
            $userIP = $_SERVER['REMOTE_ADDR'];
        }
    
        // Handle IPv6 addresses (optional)
        if (filter_var($userIP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // Convert IPv6 to IPv4-mapped IPv6 if necessary
            $userIP = inet_ntop(inet_pton($userIP));
        }
    
        return $userIP;
    }
    function salesPhoneNumber() {
        $number = get_field('quick_call_phone_number', 'options');
        $number = str_replace(array('+', ' ', '(', ')', '-'), '', $number);

        return $number;
    }
    
    // Access Database
    function accessWPDB() {
        global $wpdb;
        return $wpdb;
    }    

    function getImageSizeInfo($imageID) {
        $imageInfo = wp_get_attachment_image_src($imageID, 'full');
        if($imageInfo) {
            $imageSRC = $imageInfo[0];
            $imageWidth = $imageInfo[1];
            $imageHeight = $imageInfo[2];
            $imageAlt = get_post_meta($imageID, '_wp_attachment_image_alt', true);
    
            return array(
                'image' => $imageSRC,
                'alt' => $imageAlt,
                'width' => $imageWidth,
                'height' => $imageHeight,
            );
        }
       return null;
    }
    function durango_developer_email() {
        return 'jasghar186@gmail.com';
    }