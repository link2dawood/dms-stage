$(document).ready(function () {
	
	/** Add announcement banner at the top of header */
    let announcementHTML = `<div class="bg-primary py-2 pl-3 pr-5 position-relative announcement-bar-wrapper border border-light shadow-sm" style="display:none;">
    <p class="text-white font-md font-weight-bold" style="line-height: 1.6;">Service Scheduler Updates: Our scheduler now efficiently manages appointments for maintenance and service tailored to
    vehicles in our dealership's inventory. Should you own a vehicle not currently represented in our selection,
    we kindly ask you to contact us directly at <a href="tel:9703854822" class="text-link">970-385-4822</a> to arrange your appointment.</p>
    <span class="text-white position-absolute font-xxl cursor-pointer remove-announcement-bar" style="right: 1rem; top:50%; transform: translatey(-50%);">
    <i class="fa fa-close"></i>
    </span>
    </div>`;
//     $('#header-wrapper').prepend(announcementHTML)
    // Animation to show the announcement bar
//     $('.announcement-bar-wrapper').slideDown('slow');

    /** Remove the announcement bar from the header top  */
//     $(document).on('click', '.remove-announcement-bar', function() {
//         $(document).find('.announcement-bar-wrapper').slideUp('slow', function() {
//             $(this).remove();
//         });
//     })

    // trigger the tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // append business hours container to specified menu
    $(".hours-and-directions-header .sub-menu > li").html($('.header-hours'))
    $('.header-hours').each((index, elem) => {
        $(elem).removeClass('d-none').addClass('d-flex')
    })

    if (typeof Swiper !== 'undefined') {
        var swiper = new Swiper(".mySwiper", {
            //... your options here
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: false,
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 2,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            initialSlide: 0,
            on: {
                init: function (event) {
                    $(".automotive-navbar-pill").removeClass("active");
                    $(".automotive-navbar-pill[data-slide-index='" + 0 + "']").addClass("active");
                },
                slideChange: function (event) {
                    let realIndex = event.realIndex;
                    let activeIndex = event.activeIndex;
                    if (swiper && swiper.initialized) {
                        $(".automotive-navbar-pill").removeClass("active");
                        $(".automotive-navbar-pill[data-slide-index='" + activeIndex + "']").addClass("active");
                        $('.automotive-needs-dropdown-value').text($('.automotive-needs-dropdown-item[data-slide-index="' + activeIndex + '"]').text())
                    }
                },
            },
        });

        $(".automotive-navbar-pill").on("click", function () {
            let slideIndex = $(this).attr("data-slide-index");
            swiper.slideTo(parseInt(slideIndex));
            $(".automotive-navbar-pill").removeClass("active");
            $(this).addClass("active");
        });
        $('.automotive-needs-dropdown-item').click(function (e) {
            $('.automotive-needs-dropdown-value').text($(this).text())
            let slideIndex = $(this).attr("data-slide-index");
            swiper.slideTo(parseInt(slideIndex));
        })
    }
    //   Play the video on play button click in homepage
    $('.company-showcase-video-play').click(function (e) {
        var video = $('.company-showcase-video')[0];
        video.play();
        $('.company-showcase-video-pause').removeClass('d-none').css('opacity', '0').addClass('video-floating-icon');
        $('.company-showcase-video-play').addClass('d-none');
        $('.company-showcase-overlay').addClass('d-none');

        // Add an event listener to the video for when it ends
        video.addEventListener('ended', function () {
            this.currentTime = 0;
            this.pause();
            $('.company-showcase-video-play').removeClass('d-none');
            $('.company-showcase-video-pause').addClass('d-none').css('opacity', '1').removeClass('video-floating-icon');
            $('.company-showcase-overlay').removeClass('d-none')
			// Reset the video counter to 0
            video.currentTime = 0;
        }, false);
    });
    $('.company-showcase-video-pause').click(function (e) {
        $('.company-showcase-video')[0].pause()
        $('.company-showcase-video-pause').addClass('d-none')
        $('.company-showcase-video-play').removeClass('d-none')
        $('.company-showcase-overlay').removeClass('d-none')
    })

    function activeStoreDay() {
        let getDate = new Date();
        let getDay = getDate.getDay();
    
        // Update Sale Hours for the active day
        const activeSaleHours = document.querySelector('.store-hours-wrapper:first-of-type .store-hours-' + getDay);
        if (activeSaleHours) {
            const saleTimeElement = activeSaleHours.querySelector('p:nth-child(2)');
//             if (saleTimeElement) {
//                 saleTimeElement.textContent = 'Closed'; // Set sale hours to 'Closed'
//             }
            activeSaleHours.classList.add('active-day-hours'); // Add 'active-day-hours' class
        }
    
        // Update Service Hours for the active day
        const activeServiceHours = document.querySelector('.store-hours-wrapper:last-of-type .store-hours-' + getDay);
        if (activeServiceHours) {
            const serviceTimeElement = activeServiceHours.querySelector('p:nth-child(2)');
//             if (serviceTimeElement) {
//                 serviceTimeElement.textContent = 'Closed'; // Set service hours to 'Closed'
//             }
            activeServiceHours.classList.add('active-day-hours'); // Add 'active-day-hours' class
        }
    }
    
    // Wait for the DOM to load before executing the function
    setTimeout(() => {
        activeStoreDay();
    }, 500);
    
    let getDate = new Date();
    let getDay = getDate.getDay();
    switch (getDay) {
        case 0:
            $(".hours-wrapper > .d-flex[data-day='0']").addClass("active-day-hours");
            break;
        case 1:
            $(".hours-wrapper > .d-flex[data-day='1']").addClass("active-day-hours");
            break;
        case 2:
            $(".hours-wrapper > .d-flex[data-day='2']").addClass("active-day-hours");
            break;
        case 3:
            $(".hours-wrapper > .d-flex[data-day='3']").addClass("active-day-hours");
            break;
        case 4:
            $(".hours-wrapper > .d-flex[data-day='4']").addClass("active-day-hours");
            break;
        case 5:
            $(".hours-wrapper > .d-flex[data-day='5']").addClass("active-day-hours");
            break;
        case 6:
            $(".hours-wrapper > .d-flex[data-day='6']").addClass("active-day-hours");
    }

    let searchboxTrigger = $("#header-main-area-cont .main-header-search-column  .et_pb_s");
    let searchBox = $("header #custom-header-search-box");
    let searchBoxInput = $("header #wp-block-search__input-1");
    let searchBoxCloseBtn = $("header #custom-header-search-box .header-search-box-close");
    let headerSearchIcon = $(".header-custom-search-field-search-icon .et-pb-icon");
    let searchBoxClickOutside = $(".click-to-hide-search-box-row");
    $(searchboxTrigger).click(function (e) {
        $(e.target).css({
            background: "white",
            border: "2px solid red",
        })
        $('body').addClass('overflow-hidden')
        $(e.target).addClass("bluePlaceholder")
        $(headerSearchIcon).css("color", "#1f4a81")
        $(searchBox).css("display", "block ")
        $(searchBoxClickOutside).css("display", "block");
        $(searchBoxInput).focus();
    })
    $(searchBoxCloseBtn).click(function () {
        $(searchboxTrigger).css({
            background: "transparent",
            border: "2px solid white",
        })
        $('body').removeClass('overflow-hidden')

        $(searchboxTrigger).removeClass("bluePlaceholder");
        $(searchBoxClickOutside).css("display", "none");
        $(headerSearchIcon).css("color", "white")
        $(searchBox).css("display", "none ")
    })
    $(searchBoxClickOutside).click(function (e) {
        $(e.target).css("display", "none")
        $(searchBox).css("display", "none ")
        $(searchboxTrigger).css({
            background: "transparent",
            border: "2px solid white",
        });
        $('body').removeClass('overflow-hidden')
        $(searchboxTrigger).removeClass("bluePlaceholder");
        $(headerSearchIcon).css("color", "white")

    })

    $('.read-all-review-text').click(function (e) {
        let slide = $(e.target).closest('.testimonials-slide');
        let slideClone = slide.clone()
        slideClone.find('.full-review-text').removeClass('d-none');
        slideClone.find('.short-review-text').removeClass('d-block').addClass('d-none')
        slideClone.find('h3').addClass('font-20').removeClass('font-sm')
        slideClone.find('h4').addClass('font-md').removeClass('font-sm')
        slideClone.find('.testimonials-rating-star')
        let slideHTML = slideClone.html()
        $('.reviews-modal.modal-body').html(slideHTML);
    })

    // append inventory dropdown content to inventory menu
    $($("header .menu-menu-01 .inventory-dropdown-parent .sub-menu .header-inventory-dropdown")).append($(".inventory-dropdown-container"))
    $('.inventory-dropdown-container').each((index, elem) => {
        $(elem).removeClass('d-none')
    })

    let managersSpecialsTemplate = $(".manager-special-tempalte");
    let msTarget = $(".m-s-inventory-target")
    $(msTarget).append(managersSpecialsTemplate)
    // 		incentive specials template
    $(".incentive-specials-slider-wrapper").slick({
        centerMode: true,
        slidesToShow: 3,
        centerPadding: '30',
        arrows: true,
        prevArrow: "<button type='button' class='slick-prev pull-left'><img src='https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/08/Group-77.png' /></button>",
        nextArrow: "<button type='button' class='slick-next pull-right'><img src='https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/uploads/2022/08/Group-77.png' /></button>",
        responsive: [
            {
                breakpoint: 980,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '20',
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 450,
                settings: {
                    arrows: false,
                    dots: true,
                    centerMode: true,
                    centerPadding: '15',
                    slidesToShow: 1
                }
            }
        ]
    });

    let swiperSliderDots = $(".swiper-pagination-bullet")
    let swiperMobileDropdownValue = $(".h-n-d-value")
    $(swiperSliderDots).click(function () {
        let clickedOne = $(swiperSliderDots).index(this)
    })

    // 		slice the listings title in incentive specials slider
    let incentiveHeading = $(".incentive-special-slide-content .incentive-special-slide-title h3 ");
    $(incentiveHeading).each(function (ind, cont) {
        if ($(cont).text().length > 15) {
            let actualStr = $(cont).text()
            let newstr = $(cont).text().slice(0, 15) + `...`
            $(cont).html(newstr)
        } else {
            return $(cont).text()
        }

        let iss = $(".incentive-specials-slider-wrapper ");
        let issTarget = $(".incentive-specials-slider-row ");
        $(issTarget).after(iss)
    })



    // 		add autocomplete to the search box search bar
    let searchBar = $(".search-box-popup-input-field");
    $(searchBar).attr("autocomplete", "off")


    // 		append global content business hours to row after form map container
    let globalcontentBusinessHours = $(".global-contact-business-hours-row");
    let globalcontentBusinessHoursTarget = $(".contact-form-target-container");
    $(globalcontentBusinessHoursTarget).after(globalcontentBusinessHours);

    // 		listing offers in single listing page
    $(".listing-offers-options").slick({
        centerMode: true,
        slidesToShow: 2,
        centerPadding: '0',
        autoplay: false,
        arrows: true,
        autoplaySpeed: 4000,
        dots: false,
        prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa-solid fa-play'></i></button>",
        nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa-solid fa-play'></i></button>",
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    arrows: true,
                    dots: false,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 2
                }
            }
        ],
        responsive: [
            {
                breakpoint: 575,
                settings: {
                    arrows: true,
                    dots: false,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 2
                }
            }
        ]
    });

    // 		related listing info tags
    let relatedListingInfo = $(".listing-related-listing-condition")
    $(relatedListingInfo).each(function (ind, cont) {
        if ($(cont).text().length > 6) {
            let actualStr = $(cont).text()
            let newstr = $(cont).text().slice(0, 6) + `...`
            $(cont).html(newstr)
        } else {
            return $(cont).text()
        }
    })
    // 		related listing secondary heading 
    let relatedListingSecondaryHeading = $(".listing-secondary-title")
    $(relatedListingSecondaryHeading).each(function (ind, cont) {
        if ($(cont).text().length > 30) {
            let actualStr = $(cont).text()
            let newstr = $(cont).text().slice(0, 30) + `...`
            $(cont).html(newstr)
        } else {
            return $(cont).text()
        }
    })

    // 		search terms in search box
    let searchBoxTerms = $(".search-box-recent-searches:nth-child(2) a")
    $(searchBoxTerms).each(function (ind, cont) {
        if ($(cont).text().length > 10) {
            let actualStr = $(cont).text()
            let newstr = $(cont).text().slice(0, 10) + `...`
            $(cont).html(newstr)
        } else {
            return $(cont).text()
        }
    })

    // show the appropriate video on button click
    let mediaGalleryIframe = $('.media-gallery-dynamic-iframe');
    let mediaGalleryPopupWrapper = $('.media-gallery-popup-wrapper');
    let mediaGalleryPopupOverlay = $('.media-gallery-popup-overlay');
    let mediaGalleryPopupContent = $('.media-gallery-popup-video-container');
    let mediaGalleryPopupClose = $('.media-gallery-popup-close-icon')

    $(document).on('click', '.media-gallery-popup-trigger', function () {
        let videoURL = $(this).attr('data-url');
        $(mediaGalleryPopupWrapper).css('display', 'flex');
        $('body').addClass('overflow-hidden')
        $(mediaGalleryIframe).attr('src', videoURL)
    })

    $(mediaGalleryPopupOverlay).click(function () {
        $(mediaGalleryPopupWrapper).css('display', 'none')
        $(mediaGalleryIframe).removeAttr('src')
        $('body').removeClass('overflow-hidden')
    })
    $(mediaGalleryPopupClose).click(function () {
        $(mediaGalleryPopupWrapper).css('display', 'none')
        $(mediaGalleryIframe).removeAttr('src')
        $('body').removeClass('overflow-hidden')
    })

    // show all slides


    setTimeout(() => {
        $('.see-all-images').click(function () {
            $('.listing-thumbnail-image-slider').slick('unslick').removeClass('global-slick-slider');
            $(this).parents('.listing-thumbnail-slider-wrapper').removeClass('px-15')
            $(this).removeClass('d-flex').addClass('d-none')
			// Remove existing "Hide All Images" before appending a new one
			$('.hide-all-images').remove();
			$('.listing-thumbnail-image-slider').append(`
<div class="hide-all-images h-100 d-flex align-items-center justify-content-center cursor-pointer w-100">
<p class="p-0 font-inter text-capitalize text-white">
<span class="font-inter font-30 font-weight-normal">
Hide
</span>
</p>
</div>`)
        })
		
		$(document).on('click', '.hide-all-images', function() {
			$('.listing-thumbnail-image-slider').slick({
				asNavFor: '.listing-main-image-slider',
				slidesToShow: 5,
				slidesToScroll: 5,
				centerMode: false,
				centerPadding: 0,
				infinite: false,
				focusOnSelect: true,
				arrows: false,
				lazyLoad: 'progressive',
				prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa-solid fa-chevron-left'></i></button>",
				nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa-solid fa-chevron-right'></i></button>",
				responsive: [
					{
						breakpoint: 768,
						settings: {
							arrows: true,
							centerMode: false,
							centerPadding: '0',
							slidesToShow: 3,
							slidesToScroll: 3,
						}
					}
				],
				responsive: [
					{
						breakpoint: 575,
						settings: {
							arrows: true,
							centerMode: false,
							centerPadding: '0',
							slidesToShow: 3,
							slidesToScroll: 1
						}
					}
				]
			});
			$(this).parents('.listing-thumbnail-slider-wrapper').addClass('px-15')
			$(document).find('.hide-all-images').remove()
			$('.see-all-images').removeClass('d-none').addClass('d-flex')
		})

    }, 1000);

    $('.inventory-filterbar__title').click(function () {
        if ($(this).find('i').hasClass('fa-minus')) {
            $(this).find('i').removeClass('fa-minus');
            $(this).find('i').addClass('fa-plus');
        } else {
            $(this).find('i').addClass('fa-minus');
            $(this).find('i').removeClass('fa-plus');
        }

        if ($(this).next().hasClass('active')) {
            $(this).next().removeClass('active');
        } else {
            $(this).next().addClass('active');
        }
    });
    // inventory sidebar
    let inventoryFitlerSidebar = $(".inventory-filters-mobile-sidebar");
    let inventoryBarToggle = $(".inventory-filterbar-mobile-toggle");
    let inventorysidebarCloseIcon = $(".inventory-filters-mobilebar-close-icon");
    $(inventoryBarToggle).click(function () {
        $(inventoryFitlerSidebar).toggleClass("sidebaractive")
    })
    $(inventorysidebarCloseIcon).click(function () {
        $(inventoryFitlerSidebar).removeClass("sidebaractive")
    })
    // unique vehicle type popup
    let uniqueVehicleClose = $('.unique-vehicle-type__close');
    let recommendVehiclePopupWrapper = $('.recommend-vehicles-popup-wrapper');
    let uniqueVehicleForm = $('.unique-vehicle-type__container form')
    $(uniqueVehicleClose).click(function () {
        $('.unique-vehicle-type__popup').css('transform', 'scale(0)');
    })

    // Display Mobile popup at homepage
    function durango_homepage_mobile_popup() {
        // Get current date and store in local storage if not saved already
        let currentTime = Date.now();
        let usersVisitTime = JSON.parse(localStorage.getItem('userafter24hoursvisit'));
        if(!usersVisitTime) {
            localStorage.setItem('userafter24hoursvisit', JSON.stringify(currentTime))
            durango_homepage_mobile_popup_html();
        }else {
            let timeDifference = currentTime - usersVisitTime;
            let timeDiff = 1000 * 60 * 60 * 24;
            // Check if at least 2 minutes (120,000 milliseconds) have passed
            if (timeDifference >= timeDiff) {
                localStorage.setItem('userafter24hoursvisit', JSON.stringify(currentTime));
                durango_homepage_mobile_popup_html();
            }
        }
    }
    if(window.location.pathname === '/' && window.innerWidth < 575) {
        setTimeout(durango_homepage_mobile_popup, 15000);
    }
    function durango_close_homepage_mobile_popup() {
        $(document).find('.contextual-mobile-popup-wrapper').css('display', 'none');
        $('body').removeClass('overflow-hidden');
    }
    function durango_homepage_mobile_popup_html() {
        let websiteOrigin = window.location.origin;
        let popupHTML = `
        <div class="contextual-mobile-popup-wrapper position-fixed w-100 h-100">
        <div class="contextual-mobile-popup__overlay position-absolute w-100 h-100"></div>
        <div class="contextual-mobile-popup__content position-relative d-flex align-items-center justify-content-center h-100">
        <div class="contextual-mobile-popup__inner-content position-relative">
        <span class="contextual-mobile-popup__close  d-flex align-items-center justify-content-center bg-light rounded-circle-px position-absolute text-dark">
        <i class="fa-solid fa-xmark"></i>
        </span>
        <a href="${websiteOrigin}/beyond-value-listing/?post=Ford%20f-150&showPopup=true" class="d-inline-block position-relative">
        <img src="${websiteOrigin}/wp-content/themes/divi-child/assets/images/contextual-mobile-popup.webp" class="h-100 w-100 img-fluid" loading="lazy" decoding="async" alt="beyond value popup">
        </a>
        </div>
        </div>
        </div>`
        $('html').append(popupHTML);
        $('body').addClass('overflow-hidden')
    }

    $('html').on('click', '.contextual-mobile-popup__overlay, .contextual-mobile-popup__close', function () {
        durango_close_homepage_mobile_popup();
    });
    
    // window sticker popup at inventory page
    $(document).on('click', '.listing-card__cta', function () {
        let vinNumber = $(this).attr('data-vas-vin');
        let popupName = $(this).attr('data-name');
        $('.global-sticker-popup').removeClass('d-none').addClass('d-flex')
        $('body').addClass('overflow-hidden')
        switch (popupName) {
            case 'velocity':
                $('.global-sticker-popup__iframe iframe').attr('src', `https://app.velocityengage.com/${vinNumber}?source=dealerdotcom&accountId=durangomotorcompany&embedded=true`);
                break;
            case 'window':
                // $('.global-sticker-popup__iframe iframe').attr('src', `https://windowsticker.velocityengage.com/vin/${vinNumber}/account/durangomotorcompany?source=Dealer%20Website`);
                $('.global-sticker-popup__iframe iframe').attr('src', `https://windowsticker.velocityengage.com/vin/${vinNumber}/account/durangofordfd?source=Dealer Website`);
                break;
            case 'carfax':
                $('.global-sticker-popup__iframe iframe').attr('src', `http://www.carfax.com/VehicleHistory/p/Report.cfx?partner=DVW_1&vin=${vinNumber}`);
                break;
        }

    })

    $('.global-sticker-popup__close').click(closeWindowStickePopup)
    $('.global-sticker-popup__overlay').click(closeWindowStickePopup)

    function closeWindowStickePopup() {
        $('.global-sticker-popup').addClass('d-none').removeClass('d-flex')
        $('.global-sticker-popup__iframe iframe').removeAttr('src')
        $('body').removeClass('overflow-hidden')
    }

    // 	set timestamp value
    let leadCaptureFormName = $('.custom-lead-capture-form .user-name-field');
    $(leadCaptureFormName).blur(function () {
        let timestampField = $(this).parents('.custom-lead-capture-form').find('.form-submitted-timestamp')
        let timestampDate = new Date();
        let timestampYear = timestampDate.getFullYear();
        let timestampMonth = timestampDate.getMonth();
        let timestampMonthVal = '';
        switch (timestampMonth) {
            case 0:
                timestampMonthVal = 'January';
                break;
            case 1:
                timestampMonthVal = 'February';
                break;
            case 2:
                timestampMonthVal = 'March';
                break;
            case 3:
                timestampMonthVal = 'April';
                break;
            case 4:
                timestampMonthVal = 'May';
                break;
            case 5:
                timestampMonthVal = 'June';
                break;
            case 6:
                timestampMonthVal = 'July';
                break;
            case 7:
                timestampMonthVal = 'August';
                break;
            case 8:
                timestampMonthVal = 'September';
                break;
            case 9:
                timestampMonthVal = 'October';
                break;
            case 10:
                timestampMonthVal = 'November';
                break;
            case 11:
                timestampMonthVal = 'December';
                break;
        }
        let timestampDay = timestampDate.getDate();
        let timestampHours = timestampDate.getHours();
        let timestampHoursText = '';
        if (timestampHours > 12) {
            timestampHoursText = timestampHours - 12;
            if (timestampHoursText <= 9) {
                timestampHoursText = '0' + timestampHoursText;
            }
        } else if (timestampHours >= 9 && timestampHours <= 12) {
            timestampHoursText = timestampHours;
        } else {
            timestampHoursText = '0' + timestampHours;
        }
        let timestampMinutes = timestampDate.getMinutes();
        let timestampMinutesText = '';
        if (timestampMinutes < 10) {
            timestampMinutesText = '0' + timestampMinutes;
        } else if (timestampMinutes == 0) {
            timestampMinutesText = timestampMinutes + 1;
        } else {
            timestampMinutesText = timestampMinutes;
        }
        let timestampPeriod = 'AM';
        if (timestampHours > 12) {
            timestampPeriod = 'PM';
        }
        let timestampValue = timestampYear + ' ' + timestampMonthVal + ' ' + timestampDay + ' ' + '-' + ' ' + timestampHoursText + ':' + timestampMinutesText + ' ' + timestampPeriod;
        $(timestampField).val(timestampValue)
    })

    // global popup opener
    $(document).on('click', '.popup-trigger', function (e) {
        // if a popup is already opened
        $(document).find('.global_popup_wrapper').css('display', 'none')
        $('body').css('overflow', 'hidden')
        // open the targeted popup
        let trigger = $(e.target).attr('data-popup');
        let targetPopup = $(document).find('.global_popup_wrapper')
        $(targetPopup).each(function (index, data) {
            let attr = $(data).attr('data-popup');
            if (trigger == attr) {
                $(data).css('display', 'flex')
            }
        })
    })
    // global popup close
    $(document).on('click', '.popup-close', function (e) {
        let trigger = $(this).attr('data-popup')
        let targetPopup = $(document).find('.global_popup_wrapper')
        $(targetPopup).each(function (index, data) {
            let attr = $(data).attr('data-popup');
            if (trigger == attr) {
                $(data).css('display', 'none')
                $('body').css('overflow', '')
            }
        })
    })

    // Reviews and testimonials page
    $(document).on('click', '.review-read-more', function (e) {
        let starReview = $(e.target).parents('.review-item').find('.review-stars').html()
        let reviewHTML = $(e.target).parents('.review-item').html()
        $('.review__top-stars').html(starReview)
        $('.reviews-inner-content').html(reviewHTML)
    })

    //    open sidebar popup
    $(document).on('click', '.sidebar-popup-trigger', function (e) {
        let trigger = $(this).attr('data-popup');
        if (trigger == 'check-availability' || trigger == 'apply-for-financing' || trigger == 'test-drive' || trigger == 'rotator-slider-form' || trigger == 'quick-email') {
            let thumbnailImg = $(this).attr('data-thumbnail')
            let postTitle = $(this).attr('data-title');
            let postPrice = $(this).attr('data-price');
            let postHeading = $(this).attr('data-heading')
            if ($(this).attr('data-stock')) {
                let postStock = $(this).attr('data-stock');
                $(document).find('.sidebar__form').find('.sidebar__top-content b').html('Stock #: ' + postStock)
            }
            $(document).find('.sidebar__form').find('.sidebar__top-content h3').html(postTitle)
            $(document).find('.sidebar__form').find('.sidebar__top-content strong').html(postPrice)
            $(document).find('.sidebar__form').find('.sidebar__top-img img').attr('src', thumbnailImg)
            $(document).find('.sidebar__form').find('.sidebar__form-title').html(postHeading)
        }
        if (trigger == 'check-availability' || trigger == 'test-drive') {
            $(document).find('.sidebar__form').find('.sidebar__top-content .text_underline').attr('href', $(this).attr('data-permalink'))
        }

        if (trigger == 'quick-email' || trigger == 'check-availability' || trigger == 'test-drive' || trigger == 'sticky-cta' || trigger == 'apply-for-financing' || trigger === 'guest-request-text') {
            $(document).find('.sidebar__form').find('.vehicle_year').val($(this).attr('data-year'))
            $(document).find('.sidebar__form').find('.vehicle_make').val($(this).attr('data-make'))
            $(document).find('.sidebar__form').find('.vehicle_model').val($(this).attr('data-model'))
            $(document).find('.sidebar__form').find('.vehicle_vin').val($(this).attr('data-vin'))
            $(document).find('.sidebar__form').find('.vehicle_stock').val($(this).attr('data-stock'))
        }
        if (trigger === 'guest-request-text') {
            $('.sidebar__form').removeClass('more-vehicle-details').removeClass('share-vehicle-active')
            $('.sidebar__form').find('.sidebar__form-title').html('Guest Request Text Message')
        }
        if( trigger === 'guest-request-text' ) {
            $('.sidebar__form').find('.sidebar__form-subheading').addClass('d-none')
        }else {
            $('.sidebar__form').find('.sidebar__form-subheading').removeClass('d-none')
        }
        if (trigger == 'sticky-cta') {
            let tagName = $(e.target).prop('tagName')
            let textName = $(e.target).children('p').text().trim().toLowerCase()

            if (tagName === 'SPAN') {
                textName = $(e.target).next('p').text().trim().toLowerCase()
            } else if (tagName === 'P') {
                textName = $(e.target).text().trim().toLowerCase()
            }
            if (textName === 'more') {
                $('.sidebar__form').find('.sidebar__form-title').html('More shopping Tools')
                $('.sidebar__form').addClass('more-vehicle-details').removeClass('share-vehicle-active')
                $('.sidebar__form').find('.sidebar__form-subheading').addClass('d-none')
            } else {
                $('.sidebar__form').removeClass('more-vehicle-details').removeClass('share-vehicle-active')
                $('.sidebar__form').find('.sidebar__form-title').html($(e.target).text())
                $('.sidebar__form').find('.sidebar__form-subheading').removeClass('d-none')
            }
            if ($(e.target).data('popup-function') == 'vehicle-share') {
                $('.sidebar__form').find('.sidebar__form-title').html('Share Vehicle')
                $('.sidebar__form').addClass('share-vehicle-active').removeClass('more-vehicle-details')
                $('.sidebar__form').find('.sidebar__form-subheading').addClass('d-none')
            } else if ($(e.target).data('popup-function') == 'vehicle-price-alert') {
                $('.sidebar__form').find('.sidebar__form-title').html('<div class="d-flex align-items-center justify-content-start"><span class="icon-bell font-30 text-fourth cursor-pointer mr-2"></span>Get Price Alerts</div>')
                $('.sidebar__form').find('.sidebar__form-subheading').addClass('d-none')
            }else {
                $('.sidebar__form').find('.sidebar__form-subheading').removeClass('d-none')
            }

            if ($(e.target).data('stock') && $(e.target).data('vin') && $(e.target).data('year') &&
                $(e.target).data('thumbnail') && $(e.target).data('make') && $(e.target).data('model')) {
                $('.sidebar__form').find('.sidebar__top-img img').attr('src', $(e.target).data('thumbnail'))
                $('.sidebar__form').find('.sidebar__top-content h3').html($(e.target).data('year') + ' ' + $(e.target).data('make') + ' ' + $(e.target).data('model'));
                $('.sidebar__form').find('.sidebar__top-content b').html('Stock: ' + $(e.target).data('stock'))
                $('.sidebar__form').find('.sidebar__top-content strong').html(`<span itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content="${$(e.target).data('price')}">${$(e.target).data('price')}</span>`)
            }
        }
        // close any open popups
        let openPopups = $(document).find('.sidebar__form:visible');
        if (openPopups.length > 0) {
            openPopups.find('.sidebar__content').animate({
                right: '-100%'
            }, 500, function () {
                openPopups.hide();
                openTargetPopup(trigger);
            });
        } else {
            openTargetPopup(trigger);
        }
    });

    function openTargetPopup(trigger) {
        // open the target popup
        let targetPopup = $(document).find('.sidebar__form[data-popup="' + trigger + '"]');
        $('.sidebar__form-success').css('display', 'none')
        $('.sidebar__form-first').css('display', 'block')
        $('.sidebar__top-half').removeClass('sidebar__success')
        if (targetPopup.length > 0) {
            $('body').addClass('overflow-hidden')
            targetPopup.show().find('.sidebar__content').animate({
                right: '0'
            }, 500);
        }
    }
    $(document).on('click', '.sidebar-popup-close', function (e) {
        e.preventDefault();
        let trigger = $(this).attr('data-popup');
        let targetPopup = $(document).find('.sidebar__form');
        $(targetPopup).each(function (index, data) {
            let attr = $(data).attr('data-popup');
            if (trigger == attr) {
                $(data).find('.sidebar__content').animate({
                    right: '-100%'
                }, 500, function () {
                    $(data).css('display', 'none');
                });
            }
            $('body').removeClass('overflow-hidden')
        });
    });

    // submit sidebar popup
    $(document).on('wpcf7submit', '.sidebar__form form', function (e) {
        e.preventDefault();  // prevent form submission
        var $form = $(this);  // get form element
        // Check if the form is not vehicle share form in VDP page
        if( !$form.parents('.sidebar__form').find('div').hasClass('vehicle-share-form-active') ) {
            if ('mail_sent' === e.detail.status) {
                // if all validations pass, fade in the next element
                $form.parents('.sidebar__form-content').find('.sidebar__form-first').fadeOut();
                $form.find('.sidebar__top-half').addClass('sidebar__success')
                $form.parents('.sidebar__content-inner').find('.sidebar__top-half').addClass('sidebar__success')
                $form.parents('.sidebar__form-content').find('.sidebar__form-success').fadeIn()
            }
        }else {
            // Means user submit the share vehicle form
            if('mail_sent' === e.detail.status) {
                $form.parents('.sidebar__form').find('.vehicle-share-form-active').removeClass('vehicle-share-form-active')
                $form.parents('.sidebar__form').find('.vehicle-share-form').find('.share-via-email-wrapper').addClass('d-none')
            }
        }
    })
    // Global forms submit
    $(document).on('wpcf7submit', '.global-form-wrapper form', function (e) {
        e.preventDefault();  // prevent form submission
        var $form = $(this);  // get form element
        if ('mail_sent' === e.detail.status) {
            // if all validations pass, fade in the next element
            $form.parents('.global-form-form').fadeOut();
            $form.parents('.global-form-wrapper').find('.global-form-success').fadeIn()
            if ($form.parents('.global-form-wrapper').hasClass('unique-vehicles-form')) {
                // if form is unique vehicles form
                $('.unique-vehicle-type__popup').css('transform', 'scale(0)');
                $(recommendVehiclePopupWrapper).css('display', 'none');
            }
        }
    })

    // custom function for i'm interested form success design
    $(document).on('submit', '.contact-section-form-wrapper', function (e) {
        e.preventDefault();  // prevent form submission
        var $form = $(this);  // get form element
        var $requiredFields = $form.find('input[aria-required="true"], select[aria-required="true"], textarea[aria-required="true"]');  // get all required fields
        var fieldValid = true;
        $($requiredFields).each(function (index, data) {
            if ($(data).val() === '' || $(data).val() === undefined) {
                fieldValid = false;
                return false;
            }
        });
        if (fieldValid) {
            var recaptchaResponse = grecaptcha.getResponse();  // get reCAPTCHA response

            // check if reCAPTCHA is verified
            if (recaptchaResponse.length === 0) {
                $form.find('.form__recaptcha > span').after('<span class="wpcf7-not-valid-tip">Please verify that you are not a robot.</span>')
                return false;
            }

            // if all validations pass, fade in the next element
            $form.children('.form-first').fadeOut();
            $form.children('.sidebar__form-success').fadeIn().removeClass('d-none')
        }
    })

    // Copy clicked value
    $('.copy-value').click(function () {
        var valueToCopy = $(this).data('copy');
        var input = $('<input>').val(valueToCopy).appendTo('body').select();
        document.execCommand('copy');
        input.remove();
        $(this).text('Success: Copied');
        setTimeout(function () {
            $('.copy-value').text($('.copy-value').data('copy'));
        }, 1500);
    });

    // add top property to sidebar form when its triggered using rotator icons
    $('.s-s-cta-icon.sidebar-popup-trigger').click(function () {
        $(document).find('.sidebar__form').addClass('sidebar__form-home')
    })


    $(document).on('click', '.toggle-description', function () {
        let $descriptionContainer = $(this).parents('.description-container');
        let $shortDescription = $descriptionContainer.find('.short-description');
        let $fullDescription = $descriptionContainer.find('.full-description');
        let isFullDescriptionVisible = $fullDescription.hasClass('d-none');
        $shortDescription.toggleClass('d-none', isFullDescriptionVisible);
        $fullDescription.toggleClass('d-none', !isFullDescriptionVisible);
        $(this).text(isFullDescriptionVisible ? 'View less...' : 'View more...');
    });

    // Upgrade Vehicle Tabs in VDP page right sidebar
    $('.vehicle-tab-pill').click(function () {
        $('.upgrade-vehicle-tab').addClass('d-none').removeClass('d-block')
        let x = $(`#${$(this).data('target')}`).removeClass('d-none').addClass('d-block')
        $('.vehicle-tab-pill').removeClass('font-weight-bold').removeClass('text-decoration-underline')
        $(this).addClass('font-weight-bold').addClass('text-decoration-underline')
        let selectedTab = $(this).data('target')
        switch (selectedTab) {
            case 'recommended-vehicles':
                $('.upgrade-vehicle').addClass('recommend-vehicle-active').removeClass('recent-view-active')
                    .removeClass('liked-vehicles-active');
                break;
            case 'recently-viewed':
                $('.upgrade-vehicle').addClass('recent-view-active').removeClass('recommend-vehicle-active')
                    .removeClass('liked-vehicles-active');
                break;
            case 'liked-vehicles':
                $('.upgrade-vehicle').addClass('liked-vehicles-active').removeClass('recommend-vehicle-active')
                    .removeClass('recent-view-active');
                break;
        }

    })

    // show image info card in sticky banner in VDP page
    $(window).scroll(function () {
        if (!$('body').hasClass('upgrade-vehicle-sidebar-active')) {
            if (window.scrollY > 450) {
                $('.vehicle-card-info').removeClass('d-none')
            } else {
                $('.vehicle-card-info').addClass('d-none')
            }
        } else {
            $('.vehicle-card-info').addClass('d-none')
        }
    });
    // Stick the primary searchbar in 'Top Searches' page
    $(window).scroll(function() {
        if($(window).scrollTop() > ( $('body').hasClass('logged-in') ? 100 : 70)) {
            $('.top-search-input').addClass('top-search-sticky').removeClass('position-relative').addClass('position-sticky')
        }else {
            $('.top-search-input').removeClass('top-search-sticky').addClass('position-relative').removeClass('position-sticky')
        }
    })

    // Copy page url
    $('.copy-page-url').click(function (e) {
        let pageUrl = window.location.href;
        let clickedElem = $(this)
        var input = $('<input>').val(pageUrl).appendTo('body').select();
        document.execCommand('copy');
        input.remove();
        $(clickedElem).text('Copied');
        setTimeout(function () {
            $(clickedElem).html('<img src="https://durangovalueautos.com/wp-content/themes/divi-child/assets/images/vehicle-link-share.png" alt="vehicle link share" itemporp="image" width="30" class="mr-2">Copy Link To Share');
        }, 2000);
    })

    // Add placeholder to search form
    $('header .searchform input[type="text"]').attr('placeholder', 'Search')
    $('.main-header-wrapper input[type="text"]').click((e) => {
        $(e.target).parents('form').addClass('searchbox-activated')
        $(e.target).blur()
        $('.header-searchbox').removeClass('d-none')
        $('.header-searchbox input[type="text"]').focus()
        $('.header-searchbox-overlay').removeClass('d-none');
    })

    $('.header-searchbox-overlay').click(closeHeaderBoxPopup)
    $('.header-search-box-close').click(closeHeaderBoxPopup)
    function closeHeaderBoxPopup() {
        $('.header-searchbox-overlay').addClass('d-none');
        $('.header-searchbox').addClass('d-none')
        $('body').removeClass('overflow-hidden')
        $(document).find('.searchbox-activated').removeClass('searchbox-activated')
    }

    // Open quick routes link hidden panel
    $('.view-quick-routes').click(() => {
        $('.quick-routes').toggleClass('quick-routes-links-hidden').toggleClass('quick-routes-links-active')
    })

    // Make Header Sticky
    $(window).scroll(function() {
        let scrollPosition = $(window).scrollTop();
        // If user is logged in then scroll position should be 100
        // If user is not logged in then scroll position should be 70
        if (scrollPosition > ( $('body').hasClass('logged-in') ? $('#wpadminbar').outerHeight() + $('#top-header-wrapper').outerHeight() + $('.mobile-main-header-non-sticky').outerHeight() : $('#top-header-wrapper').outerHeight() + $('.mobile-main-header-non-sticky').outerHeight())) {
            $('.mobile-main-header-sticky').addClass('active');
            $('.toggler-dropdown-wrapper').css('top', $('#mobile-main-header-wrapper').outerHeight() + 'px');
            $('body').addClass('mob-header-sticky');
        } else {
            $('.mobile-main-header-sticky').removeClass('active');
            $('.toggler-dropdown-wrapper').css('top', 'unset');
            $('body').removeClass('mob-header-sticky');
        }
        
        if (scrollPosition > ( $('body').hasClass('logged-in') ? $('#wpadminbar').outerHeight() + $('#top-header-wrapper').outerHeight() + $('.main-header-non-sticky').outerHeight() : $('#top-header-wrapper').outerHeight() + $('.main-header-non-sticky').outerHeight())) {
            $('.main-header-sticky').addClass('active');
        } else {
            $('.main-header-sticky').removeClass('active');
        }
    })

    $('.mobile-toggler-icon').click(function() {
        $('.toggler-dropdown-wrapper').toggleClass('d-none')
        $('body').toggleClass('overflow-hidden')
        $(this).toggleClass('menu-opened')
    })

     // Hidden Card Toggler in mobile header
     $(document).on('click', '.hidden-card-toggler', function(event) {
        event.stopPropagation(); // Corrected this line
        let target = $(this).attr('data-target');
        $(this).toggleClass('page-card-active')
        let cards = $(document).find('.page-spaned-card');
        cards.each(function(index, data) { // Removed unnecessary wrapping
            if ($(data).attr('data-parent') === target) {
                $(data).toggleClass('d-none');
            }
        });
    });  
    // When Focus the mobile header searchbar
    $('.mobile-header-search input').focus(() => {
        $('.header-icon-box-clock').addClass('d-none');
        $('.header-icon-box-phone').addClass('d-none'); 
    });
    $('.mobile-header-search input').blur(() => {
        $('.header-icon-box-clock').removeClass('d-none');
        $('.header-icon-box-phone').removeClass('d-none'); 
    });  

    // Top Searches Page Searchbar
    let typingTimer;
    const doneTypingInterval = 1000; 

    $('.top-searches-search-bar').keyup(function(e) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            let searchQuery = '';
            // First check if user is at top search page or anyother page
            if( window.location.pathname !== '/top-searches/' ) {
                window.location.href = '/top-searches/'
            }
            // Sync the searchbars
            if( $(e.target).hasClass('primary-top-searchbar') ) {
            $('.secondary-top-searchbar').val($(e.target).val());
            }else {
            $('.primary-top-searchbar').val($(e.target).val());
            }
            searchQuery = $(e.target).val()
            if( searchQuery.trim() !== '' ) {
                $('.user-searched-query-wrapper').removeClass('d-none').addClass('d-flex')
                $('.user-searched-query').html(searchQuery)
            }else {
                $('.user-searched-query-wrapper').addClass('d-none').removeClass('d-flex')
                $('.user-searched-query').html('')
            }
            
            if($(e.target).hasClass('secondary-top-searchbar') && window.location.pathname !== '/top-searches/') {
                // User is at a page other than 'vehicles for you' so don't send ajax request
                return false;
            }
            // Send ajax request and load the data
            $.ajax({
                type: 'POST',
                url: '/wp-admin/admin-ajax.php',
                data: {
                    searchQuery: searchQuery,
                    action: 'loadTopSearchesData'
                },
                success: function(response) {
                    let res = jQuery.parseJSON(response);
                    if( res.searchQuery !== null ) {
                        if( res.inventoryCount > 0 ) {
                            if( res.inventoryCardLayout !== '' || res.inventoryCardLayout !== null) {
                                $('.top-search-container').addClass('d-none')
                                $('.inventory-results').removeClass('d-none')
                                $('.inventory-results-wrapper').html(res.inventoryCardLayout)
                                $('.inventory-results-counter').html(res.inventoryCount)
                            }
                        }else {
                            $('.inventory-results').addClass('d-none')
                            $('.inventory-results-wrapper').empty()
                            $('.inventory-results-counter').html('')
                        }
                        if( res.blogCount > 0 ) {
                            if(res.blogsCardLayout !== '' || res.blogsCardLayout !== null) {
                                $('.top-search-container').addClass('d-none')
                                $('.blogs-results').removeClass('d-none')
                                $('.blogs-results-wrapper').html(res.blogsCardLayout)
                                $('.blogs-results-counter').html(res.blogCount)
                            }
                        }else {
                            $('.blogs-results').addClass('d-none')
                            $('.blogs-results-wrapper').empty()
                            $('.blogs-results-counter').html('')
                        }
                        if(res.beyondCount > 0) {
                            if( res.beyondLayout !== '' || res.beyondLayout !== null ) {
                                $('.top-search-container').addClass('d-none')
                                $('.beyondvalue-results').removeClass('d-none')
                                $('.beyondvalue-results-wrapper').html(res.beyondLayout)
                                $('.beyondvalue-results-counter').html(res.beyondCount)
                            }
                        }else {
                            $('.beyondvalue-results').addClass('d-none')
                            $('.beyondvalue-results-wrapper').empty()
                            $('.beyondvalue-results-counter').html('')
                        }
                        // If user searched for something which is not found in any of the post type then hide every container
                        if( res.inventoryCount <= 0 && res.beyondCount <= 0 && res.blogCount <= 0 ) {
                            $('.top-search-container').removeClass('d-none')
                            $('.inventory-results').addClass('d-none')
                            $('.blogs-results').addClass('d-none')
                            $('.beyondvalue-results').addClass('d-none')
                            $('.beyondvalue-results-wrapper').empty()
                            $('.beyondvalue-results-counter').html(0)
                            $('.blogs-results-wrapper').empty()
                            $('.blogs-results-counter').html(0)
                            $('.inventory-results-wrapper').empty()
                            $('.inventory-results-counter').html(0)
                            $('.user-searched-query-wrapper').addClass('d-none').removeClass('d-flex')
                            $('.user-searched-query').html('')
                        }
                    }else {
                        $('.top-search-container').removeClass('d-none')
                        $('.inventory-results').addClass('d-none')
                        $('.blogs-results').addClass('d-none')
                        $('.beyondvalue-results').addClass('d-none')
                        $('.beyondvalue-results-wrapper').empty()
                        $('.beyondvalue-results-counter').html(0)
                        $('.blogs-results-wrapper').empty()
                        $('.blogs-results-counter').html(0)
                        $('.inventory-results-wrapper').empty()
                        $('.inventory-results-counter').html(0)
                        $('.user-searched-query-wrapper').addClass('d-none').removeClass('d-flex')
                        $('.user-searched-query').html('')
                    }
                },
                error: function(XHR, status, error) {
                    alert('Error while searching please refresh the page: ' + ' ' + status)
                    $('.top-search-container').removeClass('d-none')
                    $('.inventory-results').addClass('d-none')
                    $('.blogs-results').addClass('d-none')
                    $('.beyondvalue-results').addClass('d-none')
                    $('.beyondvalue-results-wrapper').empty()
                    $('.beyondvalue-results-counter').html(0)
                    $('.blogs-results-wrapper').empty()
                    $('.blogs-results-counter').html(0)
                    $('.inventory-results-wrapper').empty()
                    $('.inventory-results-counter').html(0)
                    $('.user-searched-query-wrapper').addClass('d-none').removeClass('d-flex')
                    $('.user-searched-query').html('')
                }
            })  
        }, doneTypingInterval);
    })

    // Remove user searched query and reset the results
    $('.remove-user-searched-query').click(() => {
        $('.top-search-container').removeClass('d-none')
        $('.inventory-results').addClass('d-none')
        $('.blogs-results').addClass('d-none')
        $('.beyondvalue-results').addClass('d-none')
        $('.beyondvalue-results-wrapper').empty()
        $('.beyondvalue-results-counter').html(0)
        $('.blogs-results-wrapper').empty()
        $('.blogs-results-counter').html(0)
        $('.inventory-results-wrapper').empty()
        $('.inventory-results-counter').html(0)
        $('.primary-top-searchbar').val('')  
        $('.secondary-top-searchbar').val('')
        $('.user-searched-query-wrapper').addClass('d-none').removeClass('d-flex')
        $('.user-searched-query').html('')
    })

    // Fill the email body field in share vehicle sidebar form via email
    $('.share-via-email').click((e) => {
        $(e.target).parents('.vehicle-share-form').toggleClass('vehicle-share-form-active')
        $('.share-via-email-wrapper').toggleClass('d-none')
        $('.share-via-email-wrapper textarea').val(window.location.href)
    })

    // Toggle active class on clock icon in mobile header
    $(document).on('click', function(event) {
        // Check if the clicked element is not the popover toggle button or its descendants
        if (!$(event.target).closest('.header-icon-box-clock').length) {
            // Close all popovers
            $('[data-toggle="popover"]').popover('hide');
            
            // Remove the "active" class from all elements with the class "header-icon-box-clock"
            $('.header-icon-box-clock').removeClass('active');
        }
    });
      
      $('.header-icon-box-clock').click(function(event) {
        // Toggle the "active" class on the clicked element
        $(this).toggleClass('active');
        let getDate = new Date();
        let getDay = getDate.getDay();
        // Add delay to the popover
        setTimeout(() => {
            $(document).find($('.store-hours-wrapper > .store-hours-'+getDay+'')).addClass('active-day-hours')
        }, 300);
        event.stopPropagation();
      });

    //   Initialize popovers
    $('[data-toggle="popover"]').popover();

    /**
     * Add Iframe in mobile_iframe_popup when user clicks on .mobile-iframe-trigger
     */

    $('.mobile-iframe-trigger').click(function(e) {
        e.preventDefault();
        const iframeSrc = $(this).attr('href');
        $('.mobile_iframe_loader').removeClass('d-none')
        $('#mobile_iframe_element').attr('src', iframeSrc);
    })
    /**
     * Remove Iframe from popup body on popup close
     */
    $('.mobile-iframe-close').click(function() {
        $('#mobile_iframe_element').attr('src', '');
    })
	
	/** Send forms submissions to SQL DB */
    $(document).on('wpcf7submit', '.wpcf7-form', function (e) {
        e.preventDefault();
        var $form = $(this);

        // If the email was sent
        if ('mail_sent' === e.detail.status) {
            // Grab the values of the contact form
            let f_name = $form.find('input.user-name-field').val().trim() || '';
            let l_name = $form.find('input[name="last_name"]').val();
            let email = $form.find('input[type="email"]').val();
            let phone = $form.find('input[type="tel"]').val();
            let comments = $form.find('textarea').val();
            let timestamp = $form.find('input[name="timestamp"]').val();
            let year = $form.find('input[name="vehicle_year"]').val();
            let make = $form.find('input[name="vehicle_make"]').val();
            let model = $form.find('input[name="vehicle_model"]').val();
            let stock = $form.find('input[name="vehicle_stock"]').val();
            let vin = $form.find('input[name="vehicle_vin"]').val();
            let source = 'Durango Value Autos';

            console.log(f_name, l_name, email, phone, comments, timestamp, year, make, model,
                stock, vin, source);

            /** Send ajax request to store the values in SQL DB */
            $.ajax({
                type: 'POST',
                url: '/wp-admin/admin-ajax.php',
                data: {
                 values: [f_name, l_name, email, phone, comments, timestamp, 
                year, make, model, stock, vin, source],
                action: 'insert_lead_to_sql',   
                },
                success: function(response) {
                    console.log(response)
                },
                error: function(XHR, error, status) {
                    console.log(error)
                }
            })
        }
    });

})
















jQuery(document).ready(function() {
    jQuery(".meta-values-container .col-12 > span:last-child").each(function(index) {
        const span = jQuery(this);
        const copyIcon = jQuery('<i class="fa fa-copy copy-icon" style="margin-right: 10px; cursor: pointer;" title="Copy"></i>');
        span.before(copyIcon);
        copyIcon.on("click", function() {
            const textToCopy = span.text().trim();
            
            navigator.clipboard.writeText(textToCopy).then(function() {
                const tooltip = jQuery('<span class="copied-msg" style="margin-left: 5px; color: green; font-size: 12px;">Copied</span>');
                copyIcon.after(tooltip);
                
                setTimeout(function() {
                    tooltip.fadeOut(300, function() {
                        jQuery(this).remove();
                    });
                }, 1000);
            });
        });
    });
});


jQuery(document).ready(function($) {
    document.addEventListener('wpcf7mailsent', function(event) {
        // Target the specific form by ID if needed
        if (event.detail.contactFormId == 1843) {
            // Hide the form block if not already hidden
            $('.sidebar__form-first').addClass('d_none');

            // Show the success message block
            $('.sidebar__form-success').removeClass('d_none');
        }
    }, false);
	
	
	/** Company hours tooltip */
	let saleHours = {
		'monday': '8:00 - 6:00 PM',
        'tuesday': '8:00 - 6:00 PM',
        'wednesday': '8:00 - 6:00 PM',
        'thrusday': '8:00 - 6:00 PM',
        'friday': '8:00 - 6:00 PM',
        'saturday': '9:00 - 5:00 PM',
        'sunday': 'Closed',
	}
	
	let serviceHours = {
		'monday': '7:00 - 6:00 PM',
        'tuesday': '7:00 - 6:00 PM',
        'wednesday': '7:00 - 6:00 PM',
        'thrusday': '7:00 - 6:00 PM',
        'friday': '7:00 - 6:00 PM',
        'saturday': 'Closed',
        'sunday': 'Closed',
	}
	
	let partsHours = {
		'monday': '7:00 - 6:00 PM',
        'tuesday': '7:00 - 6:00 PM',
		'wednesday': '7:00 - 6:00 PM',
		'thrusday': '7:00 - 6:00 PM',
		'friday': '7:00 - 6:00 PM',
		'saturday': 'Closed',
		'sunday': 'Closed',
	}

	const daysOfWeek = ['sunday','monday','tuesday','wednesday','thrusday','friday','saturday'];
	const today = daysOfWeek[new Date().getDay()];

	const hoursMap = {
		sales: saleHours,
		service: serviceHours,
		parts: partsHours
	};

	function createTooltip(hours) {
		let tooltip = document.createElement('div');
		tooltip.className = 'hours-tooltip';

		daysOfWeek.forEach(day => {
			let line = `${day.charAt(0).toUpperCase() + day.slice(1)} - ${hours[day]}`;
			if (day === today) {
				line = `<strong>${line}</strong>`;
			}
			tooltip.innerHTML += `<div>${line}</div>`;
		});

		document.body.appendChild(tooltip);
		return tooltip;
	}

	document.querySelectorAll('.hours-tooltip-trigger').forEach(el => {
		let type = el.dataset.hours;
		let tooltip = createTooltip(hoursMap[type]);

		el.addEventListener('mouseenter', e => {
			tooltip.style.display = 'block';
			tooltip.style.top = e.pageY + 10 + 'px';
			tooltip.style.left = e.pageX + 10 + 'px';
		});

		el.addEventListener('mousemove', e => {
			tooltip.style.top = e.pageY + 10 + 'px';
			tooltip.style.left = e.pageX + 10 + 'px';
		});

		el.addEventListener('mouseleave', () => {
			tooltip.style.display = 'none';
		});
	});

});