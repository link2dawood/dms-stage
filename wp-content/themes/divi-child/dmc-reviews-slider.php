<?php ob_start(); ?>
<div class="review__container d-block" id="reviews-container"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDojVTsW5d0HAgkaamv9iyRc0HVFDNVwAs&libraries=places"></script>

<script>
(function($) {
    const initGoogleReviews = () => {
        const map = new google.maps.Map(document.createElement('div'));
        const service = new google.maps.places.PlacesService(map);

        const request = {
            placeId: 'ChIJ1xuRjDIDPIcRukhqde0w0mw',
            fields: ['reviews', 'rating', 'name']
        };

        service.getDetails(request, (place, status) => {
            if (status !== google.maps.places.PlacesServiceStatus.OK) return;
            const container = $('#reviews-container');
            const reviews = place.reviews || [];

            let html = '<div class="review-slider-container">';
            reviews.slice(0, 10).forEach(review => {
                html += `
                    <div class='review-item'>
                        <p class='review-text'>${review.text}</p>
                        <p><strong>By:</strong> ${review.author_name}</p>
                        <p><strong>Rating:</strong> ${review.rating}</p>
                    </div>
                `;
            });
            html += '</div>';
            container.html(html);

            $('.review-slider-container').slick({
                slidesToShow: 1,
                autoplay: true,
                arrows: true,
                prevArrow: '<button class="slick-prev slick-arrow"><i class="fa-solid fa-chevron-left"></i></button>',
                nextArrow: '<button class="slick-next slick-arrow"><i class="fa-solid fa-chevron-right"></i></button>'
            });
        });
    };

    $(window).on('load', initGoogleReviews);
})(jQuery);
</script>

<style>
.review__container { width: 90%; margin: 0 auto; }
.review-item { padding: 15px; border: 1px solid #ddd; margin: 10px; border-radius: 5px; background: #f9f9f9; }
.slick-arrow { font-size: 30px; color: #00463c !important; }
</style>
<?php return ob_get_clean(); ?>
