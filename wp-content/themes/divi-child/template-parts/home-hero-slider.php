<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<style>
.hero-slider {
    width: 100%;
    padding-bottom: 50px;
}
.hero-slider img {
    width: 100%;
    height: auto;
    object-fit: contain;
    display: block;
}
.hero-slider .swiper-slide {
    max-height: 530px;
    overflow: hidden;
}
.hero-slider .swiper-button-next:after,
.hero-slider .swiper-rtl .swiper-button-prev:after {
    content: "\f105" !important;
}
.hero-slider .swiper-button-prev:after,
.hero-slider .swiper-rtl .swiper-button-next:after {
    content: "\f104" !important;
}
.hero-slider .swiper-button-next:after,
.hero-slider .swiper-rtl .swiper-button-prev:after,
.hero-slider .swiper-button-prev:after,
.hero-slider .swiper-rtl .swiper-button-next:after {
    content: "\f105" !important;
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900;
    display: inline-block;
    color: #000 !important;
    font-size: 16px;
}
.hero-slider .swiper-button-next,
.hero-slider .swiper-rtl .swiper-button-prev,
.hero-slider .swiper-button-prev,
.hero-slider .swiper-rtl .swiper-button-next {
    background-color: white;
    width: 30px !important;
    height: 30px !important;
    border-radius: 100px !important;
}
.hero-slider .swiper-pagination {
    bottom: 0px !important;
}
.hero-slider .swiper-pagination span {
    width: 15px !important;
    height: 15px !important;
    background: #bbb !important;
    opacity: 1 !important;
    border: none !important;
    transition: all ease .5s !important;
}
.hero-slider .swiper-pagination .swiper-pagination-bullet-active {
    background: #444444 !important;
}
@media (min-width: 990px) {
.hero-slider .swiper-button-next {
    right: -40px !important;
    transition: all ease .5s !important;
}
.hero-slider .swiper-button-prev {
    left: -40px !important;
    transition: all ease .5s !important;
}
.hero-slider:hover .swiper-button-prev {
    left: 40px !important;
}
.hero-slider:hover .swiper-button-next {
    right: 40px !important;
}
}
</style>

<?php if (have_rows('home_hero_slider', 'option')) : ?>
    <div class="swiper hero-slider">
        <div class="swiper-wrapper">
            <?php
            $slides = [];
            while (have_rows('home_hero_slider', 'option')) : the_row();
                $desktop_img = get_sub_field('slider_image_desktop');
                $mobile_img  = get_sub_field('slider_image_mobile');
                $link_url    = get_sub_field('image_url');
                $expire_date = get_sub_field('expire_date_time');

                $desktop_url = $desktop_img['url'] ?? '';
                $mobile_url  = $mobile_img['url'] ?? '';
                $alt_text    = $desktop_img['alt'] ?? 'Hero Slide';

                ob_start(); ?>
                <div class="swiper-slide">
                    <?php if ($link_url) : ?><a href="<?php echo esc_url($link_url); ?>"><?php endif; ?>
                        <picture>
                            <source media="(max-width: 767px)" srcset="<?php echo esc_url($mobile_url); ?>">
                            <img src="<?php echo esc_url($desktop_url); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                        </picture>
                    <?php if ($link_url) : ?></a><?php endif; ?>
                </div>
                <?php
                $slides[] = ob_get_clean();
            endwhile;

            shuffle($slides);
            echo implode('', $slides);
            ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing hero Swiper...');

    // Wait a bit to ensure Swiper HTML is rendered
    setTimeout(function() {
        const swiper = new Swiper('.hero-slider', {
            slidesPerView: 1,
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            speed: 1200,
            autoplay: {
                delay: 8000,
                disableOnInteraction: false
            },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });
        console.log('Swiper initialized');
    }, 300);
});
</script>
