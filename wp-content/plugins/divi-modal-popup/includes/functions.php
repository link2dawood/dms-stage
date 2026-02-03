<?php
if ( ! function_exists( 'el_modal_popup_remove_shortcode' ) ) {
    function el_modal_popup_remove_shortcode( $content = '' ) {
        $content = trim( $content );

        if ( '' === $content ) {
            return '';
        }

        $strip_content_shortcodes = array(
            'dipl_modal',
            'el_modal_popup',
        );

        foreach ( $strip_content_shortcodes as $shortcode_name ) {
            $regex = sprintf(
                '(\[%1$s[^\]]*\][^\[]*\[\/%1$s\]|\[%1$s[^\]]*\])',
                esc_html( $shortcode_name )
            );

            $content = preg_replace( $regex, '', $content );
        }

        return et_core_intentionally_unescaped( $content, 'html' );
    }
}