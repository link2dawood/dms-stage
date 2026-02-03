<?php
function testimonial_slider_shortcode() {
    // Start output buffering
    ob_start();
    ?>
    <div class="testimonial-slider">
        <?php
        $dealer_id_logo = site_url('/wp-content/uploads/2025/02/dealer-e7199bad63631880ff5ebde06e29b0e3.png');
        $google_logo = site_url('/wp-content/uploads/2025/02/google-364c5721069c77a9df0bf9cc4332eb71.png');
        // Array of testimonials
        $testimonials = [
            [
                'logo' =>$dealer_id_logo ,
                'rating' => 5,
                'comment' => 'Chris Chism got me into Tacoma I needed automatic and letme drive the manual.',
                'author' => 'by Mr Pete',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $dealer_id_logo,
                'rating' => 5,
                'comment' => 'My entire experience with Durango Toyota has beenexcellent! I am on my second lease and will wholeheartedly recommend their services 100%',
                'author' => 'by Flyfishwithjs',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $dealer_id_logo,
                'rating' => 5,
                'comment' => 'My son bought his first vehicle, Toyota Tacoma, with EricSimon and it was a great buying experience for him. Eric took care of us during the purchase and made sure we were totally satisfied. I would definitely buy another vehicle from Durango Motor Company. We did have a few minor issues with the battery, which was causing long starts and dead upon shut off. The service departme4nt was great... they took the truck in immediately corrected the issues and did it all with a smile. Thank you all for the assistance and friendly experience!!!',
                'author' => 'by areval90',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $google_logo,
                'rating' => 5,
                'comment' => 'Another great oil change and tire rotation. Will be back. Follow up- Well it seems the 5 bolts were rusted holding the skid plate. They initially wanted to charge me for new parts including bolts. No mention of this in the receipt from this trip. Once the receipt was provided they gladly made the repair at no cost. So hats of to the team for standing behind their work. Pro tip - save your receipts people. And yes, I plan to use them in the future. Busy place but great to have the service in Du... 
                ',
                'author' => 'Brad Helton',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $google_logo,
                'rating' => 5,
                'comment' => 'Bought a truck from Derek Romero. He is a young kid working hard to come up in the sales world and is doing a great job. Helped with the easy stuff like showing the truck but also walked us through the paperwork like a professional and asked all the right questions. As someone who started selling when I was his age I was very pleased with the whole experience. Truck was clean, tank was full when we picked it up. Thanks Derek!',
                'author' => 'Travis Rosado',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $dealer_id_logo,
                'rating' => 5,
                'comment' => 'I’ve fought in three wars across four continents and thentransitioned to sales where I built a practice of more than 300 clients from 0 in under five years. I say that to say that I have a high standard of both service and salesmanship. After this few short hours with Eric Simin I’d confidently say that I’d go to battle any day with this man in any arena. A family man who’s honest and follows through. Thanks for the new truck at a new time in my life and all you and your teams did.',
                'author' => 'by Dhillandpersonal',
                'time' => 'Over a month ago',
            ],          
            [
                'logo' => $dealer_id_logo,
                'rating' => 5,
                'comment' => 'Jimmy and Eric are the best!Eric got me into the dealership and Jimmy got me a great deal on a new 2024 RAV4! Extremely friendly and reliable, I’d suggest Durango Motor Company to anyone looking to buy a new or used car.',
                'author' => 'jenniferlarkin95',
                'time' => 'Over a month ago',
            ],
           [
                'logo' => $google_logo,
                'rating' => 5,
                'comment' => 'By far THE smoothest and easiest process I’ve had with getting a vehicle! 5 STARS FOR SURE!!!!! My sales rep was Caleb Newman and he’s made today very easy for me. He made me feel welcome and reassured me of everything I was unsure of, as well as promptly answered any questions I had! He helped me find/get into the best vehicle for my situation and it all went so smooth that I thought I was dreaming. Thank you so much, Caleb!! This dealership is amazing and treats everyone with respect and care....',
                'author' => 'David Adams',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $dealer_id_logo,
                'rating' => 5,
                'comment' => 'I worked with Garrett Black on a new Tundra.He’s very knowledgeable and professional and made the whole process very simple.',
                'author' => 'by HG',
                'time' => 'Over a month ago',
            ],
            [
                'logo' => $google_logo,
                'rating' => 5,
                'comment' => 'I purchased a 4-Runner this week-end and I’m so very pleased with the decision. I feel more secure and safe sitting up higher and having 4 wheel drive versus my old SUV. Eric was a perfect match with his calm demeanor and professional knowledge in assisting us in the process. Thank you Eric for making sure we had everything the way we wanted. Durango Motor Company and all the staff we encountered exceeded our expectations from star to finish.',
                'author' => 'Dana Snyder',
                'time' => 'Over a month ago',
            ],
           
        ];

        // Loop through testimonials and generate HTML
        foreach ($testimonials as $testimonial) {
            ?>
            <div class="carousel__item">
                <div class="testimonial__wrapper testimonial__wrapper_center">
                    <div class="testimonial__logo">
                        <div class="testimonial__logo-circle">
                            <img src="<?php echo esc_url($testimonial['logo']); ?>" alt="dealer" class="img-responsive" draggable="false">
                        </div>
                    </div>
                    <div class="testimonial__content">
                        <div class="testimonial__rating">
                            <?php for ($i = 0; $i < $testimonial['rating']; $i++) : ?>
                                <span class="testimonial__rating-star __active"><i class="fa fa-star"></i></span>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial__comment">
                            <span><?php echo esc_html($testimonial['comment']); ?></span>
                        </div>
                        <div class="testimonial__author"><?php echo esc_html($testimonial['author']); ?></div>
                        <div class="testimonial__time text-small"><?php echo esc_html($testimonial['time']); ?></div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
<style>
.testimonial__rating-star.__active {
    color: #ff7c01 !important;
}
.page-id-195 .slick-prev, .page-id-195 .slick-next {
    position: absolute;
    top: 50% !important;
    visibility: visible;
    width: 40px;
    height: 40px;
    cursor: pointer;
    color: transparent !important;
    background: transparent !important;
    margin: 0px 20px !important;
}

	.page-id-195 .slick-prev{
			z-index: 10 !important;
	}
.page-id-195 .slick-prev:after {
    content: "\f104" !important; /* Chevron left */
    font-family: 'FontAwesome';
    cursor: pointer !important;
    color: #444444 !important;
    font-size: 50px !important;
}

.page-id-195 .slick-next:after {
    content: "\f105" !important; /* Chevron right */
    font-family:'FontAwesome';
    cursor: pointer !important;
    color: #444444 !important;
    font-size: 50px !important;
}

.testimonial__logo{
	    display: flex !important;
    justify-content: center !important;
	margin-bottom: 12px !important;
}
.testimonial__content{
	text-align: center !important;
	width: 70% !important;
	margin: auto !important;
}
	.testimonial__logo-circle{
		border-radius: 50% !important;
		background: #ececec !important;
	}
	.testimonial__author{
	color: #00463c !important;
    font-weight: 700 !important;
	}
</style>
<script>
jQuery(document).ready(function(jQuery) {
    jQuery('.testimonial-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        infinite: true,
        autoplay: false
    });
});

</script>
    <?php
    // End output buffering and return content
    return ob_get_clean();
}
add_shortcode('testimonial_slider', 'testimonial_slider_shortcode');
?>



