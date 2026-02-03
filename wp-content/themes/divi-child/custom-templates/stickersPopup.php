<?php
    function divi_child_stickersPopup() {
        $popup = '<div class="function global-sticker-popup window-sticker-popup w-100 h-100 position-fixed d-none align-items-center justify-content-center">'.
                 '<div class="global-sticker-popup__overlay window-sticker-popup__overlay w-100 h-100 position-absolute"></div>'.
                 '<div class="global-sticker-popup__content window-sticker-popup__content position-relative bg-white w-100">'.
                 '<span class="global-sticker-popup__close window-sticker-popup__close global_popup_wrapper_close border_circle d-flex align-items-center justify-content-center cursor-pointer">'.
                 '<i class="fa fa-close"></i>'.
                '</span>'.
                '<div class="global-sticker-popup__iframe window-sticker-popup__iframe">'.
                '<iframe frameborder="0" class="w-100 h-100"></iframe>'.
                '</div></div></div>';
        return $popup;
    }