$(document).ready(function ($) {

	function updateLikedVehicles(ID, Status) {
		$.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			data: {
				vehicleId: ID,
				likeStatus: Status,
				action: 'update_vehicle_liked_status',
			},
			success: function (response) {
				let res = jQuery.parseJSON(response);
				if (res.cardHTML !== '' && (window.location.pathname !== '/used-vehicles-durango-colorado/'|| window.location.pathname !== '/new-vehicles-durango-colorado/')) {
					$('#liked-vehicles').append(res.cardHTML);
					$('.no-liked-vehicles-found').remove();
					$('.star-empty-icon').trigger('click')
					$('.vehicle-tab-pill:last-child').trigger('click')
				} else {
					if (window.location.pathname !== '/used-vehicles-durango-colorado/' && window.location.pathname !== '/new-vehicles-durango-colorado/') {
						$('#liked-vehicles .liked-vehicle-card').each(function (index, data) {
							if (Number($(data).data('id')) === Number(res.cardID)) {
								$(data).remove();
							}
						});
					}
				}

				if (Status) {
					$('.make-vehicle-liked').removeClass('d-none');
					$('.make-vehicle-like').addClass('d-none');
					if (window.location.pathname !== '/used-vehicles-durango-colorado/' || window.location.pathname !== '/new-vehicles-durango-colorado/') {
						$(document).find('.card-vehicle-like[data-id="' + ID + '"]').addClass('d-none');
						$(document).find('.card-vehicle-liked[data-id="' + ID + '"]').removeClass('d-none');
					}
				} else {
					$('.make-vehicle-liked').addClass('d-none');
					$('.make-vehicle-like').removeClass('d-none');
					if (window.location.pathname !== '/used-vehicles-durango-colorado/' || window.location.pathname !== '/new-vehicles-durango-colorado/') {
						$(document).find('.card-vehicle-like[data-id="' + ID + '"]').removeClass('d-none');
						$(document).find('.card-vehicle-liked[data-id="' + ID + '"]').addClass('d-none');
					}
				}

			},
			error: function (xhr, status, error) {
				alert('Error in liking the product' + error)
			}
		})
	}
	

    // Attach event handlers to like/unlike buttons
    $('.make-vehicle-like').click(function () {
        let vehicleId = $(this).data('id');
        updateLikedVehicles(vehicleId, true);
    });

    $('.make-vehicle-liked').click(function () {
        let vehicleId = $(this).data('id');
        updateLikedVehicles(vehicleId, false);
    });
    // Add liked vehicle on heart click on inventory page card
    $(document).on('click', '.card-vehicle-like', function(e) {
        updateLikedVehicles($(e.target).data('id'), true)
    })
    $(document).on('click', '.card-vehicle-liked', function(e) {
        updateLikedVehicles($(e.target).data('id'), false)
    })
    
    $(document).on('click', '.remove-liked-view', function (e) {
        updateLikedVehicles($(this).attr('data-id'), false)
    })

    $('.star-empty-icon').click(function () {
        $('body').addClass('upgrade-vehicle-sidebar-active')
        $('.upgrade-vehicle').removeClass('d-none')
        $('.sticky-lead-form-wrapper').removeClass('position-sticky')
        $('.upgrade-vehicle-active-hidden-elem').addClass('d-none')
        $('.star-active-icon').removeClass('d-none')
        $('.star-empty-icon').addClass('d-none').removeClass('d-flex')
		$('.details-action-icon img.icon-details-tag').addClass('d-none')
        $('.details-action-icon span.icon-details-tag').removeClass('d-none')
    })
    $('.close-upgradeVehicle').click(function () {
        $('body').removeClass('upgrade-vehicle-sidebar-active')
        $('.upgrade-vehicle').addClass('d-none')
        $('.sticky-lead-form-wrapper').addClass('position-sticky')
        $('.upgrade-vehicle-active-hidden-elem').removeClass('d-none')
        $('.star-active-icon').addClass('d-none')
        $('.star-empty-icon').removeClass('d-none').addClass('d-flex')
		$('.details-action-icon img.icon-details-tag').removeClass('d-none')
        $('.details-action-icon span.icon-details-tag').addClass('d-none')
    })
    $('.more-cars-found').click(function (e) {
        e.stopPropagation()
    })
    // Show All filter pills
    $(document).on('click', '.show-all-filters', function () {
        $('.filter-pills-inner-wrapper li:nth-last-child(-n+2)').addClass('d-flex')
        $(this).text('Hide Some Filters');
        $(this).removeClass('show-all-filters').addClass('hide-some-filters')
    })
    $(document).on('click', '.hide-some-filters', function () {
        $('.filter-pills-inner-wrapper li:nth-last-child(-n+2)').removeClass('d-flex')
        $(this).text('Show All Filters');
        $(this).removeClass('hide-some-filters').addClass('show-all-filters')
    })

    // Update Recent View Vehicles Table
    $(document).on('click', '.remove-recent-view', function (e) {
        removeVehicleHistory('recent-view', $(this).data('id'), $(this).parents('.recent-view-vehicle-card'), false);
    })
    if(window.location.pathname.search(/listings/i) !== -1) {
        removeVehicleHistory(null, $('.VDP-content-wrapper').data('listing'), null, true);
    }

    function removeVehicleHistory($triggerElem, $id, $targetedCard, $initialPageLoad = false) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                triggerElem: $triggerElem,
                vehicleId: $id,
                initialPageLoad: $initialPageLoad,
                action: 'trackUserRecentVehicles',
            },
            success: function (response) {
                if ($initialPageLoad) {
                    // Append vehicle to the recent view element
                    let res = jQuery.parseJSON(response)
                    if (res.cardHTML !== null && res.cardHTML !== '') {
                        $('#recently-viewed').append(res.cardHTML)
                        $('.no-recent-vehicles-found').remove()
                    }
                } else {
                    $targetedCard.remove()
                }
            },
            error: function (XHR, status, error) {
//                 alert('Error in removing recent view vehicle' + status)
            }
        })
    }

    // Compare vehicles popup in VDP page
    $('.VDP_vehicles_compare').click(function () {
        $('.compare-vehicles-popup').addClass('d-flex')
        $('.compare-vehicles-popup').find('.compare-vehicles-popup__close').addClass('vdp_compare_close');
        $('.compare-vehicles-popup').find('.compare-vehicles-popup__overlay').addClass('vdp_compare_close');
        // Send ajax request
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                'vdpCompareTrigger': true,
                action: 'rr_compare_vehicles',
            },
            success: function (response) {
                let responseOBJ = jQuery.parseJSON(response)
                if (responseOBJ !== '') {
                    $('.compare-vehicles-popup').find('.compare-result').html(responseOBJ.html)
                    $('body').addClass('overflow-hidden')
                }
            },
            error: function (XHR, status, error) {
                alert('Error while comparing the vehicles please try again')
            }
        })
    })
	
	/** Close compare box */
    $(document).on('click', '.vdp_compare_close', function () {
        $('.compare-vehicles-popup').removeClass('d-flex')

        // Get the selected compare vehicles ids
        let compareVehicleIds = [];
        let selectedCompareVehicles = $('.compare-box-container .compare-body .remove-vdp-compare');
        $(selectedCompareVehicles).each(function (index, data) {
            compareVehicleIds.push($(data).attr('data-remove'));
        })

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                checkedPostId: compareVehicleIds,
                isArr: true,
                checkStatus: false,
                action: 'userComparedVehicles',
            },
            success: function (response) {
                // Handle the response if needed
                let res = jQuery.parseJSON(response)
                if( res.comparedVehicles !== null ) {
                    let cardHTML = `<div class="d-flex mb-30 position-relative">
			        <div class="accordion-card-thumbnail mr-30 position-relative">
				    <a class="d-inline-block w-100" href="/new-vehicles-durango-colorado">
					    <img src="/wp-content/themes/divi-child/assets/images/dummy-compare.png"
                        width="140" height="108" loading="lazy" decoding="async"
					    title="Compare a vehicle" />
							<i class="fa fa-plus position-absolute compare-another-vehicle-icon text-white" aria-hidden="true"></i>
				    </a>
			        </div>
			        <div class="d-flex align-items-start flex-column">
				    <a href="/new-vehicles-durango-colorado" class="font-inter text-primary font-lg">Compare another vehicle</a>
			        </div>
			        </div>
			        </div>`

                    $('.compare-body').html(cardHTML.repeat(3))
                }
                console.log(response)
                $('body').removeClass('overflow-hidden')
				$('.compare-btn').remove()
            },
            error: function (XHR, status, error) {
                alert('Error in removing the compared vehicles' + status)
            }
        })

        $('.compare-vehicles-popup').find('.compare-vehicles-popup__close').removeClass('vdp_compare_close');
    })

    // Light box Show/Hide
    $('.icon-slider-fullscreen').click(function () {
        $(document).find('.lightbox-slider-wrapper').removeClass('lightbox-slider-hidden').addClass('lightbox-slider-active')
    })

    // Load more vehicles recommendations 
    $('.load-more-recommendations').click((e) => {
        let vehicleMake = $(e.target).data('make')
        let seperateRecommendationsTab = false;
        let paged;
        if($(e.target).prev().find('.recommend-vehicles-wrapper').length > 0) {
            seperateRecommendationsTab = true;
        }

        if(seperateRecommendationsTab) {
            paged = $('.recommend-vehicles-wrapper').attr('data-paged')   
        }else {
            paged = $('#recommended-vehicles').attr('data-paged')   
        }

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                vehicleMake: vehicleMake,
                paged: paged,
                action: 'loadMoreRecommendations',
            },
            success: function (response) {
                let res = jQuery.parseJSON(response)
                if(seperateRecommendationsTab) {
                    $('.recommend-vehicles-wrapper').html(res.cardHTML).attr('data-paged', Number(paged) + 1)
                }else {
                    $('#recommended-vehicles .recommendations-vehicles-wrapper').html(res.cardHTML)
                    $('#recommended-vehicles').attr('data-paged', Number(paged) + 1)
                }
                if (Number(paged) === res.totalPages) {
                    if(seperateRecommendationsTab) {
                        $('.load-more-recommendations.bg-white').hide()
                    }else {
                        $('.load-more-recommendations:not(.bg-white)').hide()
                    }
                } else {
                    if(seperateRecommendationsTab) {
                        $('.load-more-recommendations.bg-white').show()
                    }else {
                        $('.load-more-recommendations:not(.bg-white)').show()
                    }
                }
            },
            error: function (XHR, status, error) {
                alert('something went wrong please try again, Error' + status)
            }
        })
    })

    // Lightbox slider
    $('.lightbox-image-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
		lazyLoad: 'ondemand',
        arrows: true,
        infinite: false,
        asNavFor: '.lightbox-thumb-inner-wrapper',
        prevArrow: "<button type='button' class='slick-prev pull-left bg-white p-4 d-flex align-items-center justify-content-center' style='z-index:1;'><i class='fa-solid text-dark lead fa-chevron-left'></i></button>",
        nextArrow: "<button type='button' class='slick-next pull-right bg-white p-4 d-flex align-items-center justify-content-center' style='z-index:1;'><i class='fa-solid text-dark lead fa-chevron-right'></i></button>",
    });
    $('.lightbox-thumb-inner-wrapper').slick({
        slidesToShow: 10,
        slidesToScroll: 1,
        asNavFor: '.lightbox-image-slider',
		lazyLoad: 'ondemand',
        dots: false,
        arrows: false,
        infinite: false,
        centerMode: false,
        focusOnSelect: true,
        responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 7,
                slidesToScroll: 1,
                infinite: false,
                dots: false
              }
            },
            {
              breakpoint: 575,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1
              }
            }
          ]
    });

    // Close lightbox slider
    $('.close-slider-lightbox').click(function () {
        $(document).find('.lightbox-slider-wrapper').addClass('lightbox-slider-hidden').removeClass('lightbox-slider-active')
    })
    $('.lightbox-slider-overlay').click(function () {
        $(document).find('.lightbox-slider-wrapper').addClass('lightbox-slider-hidden').removeClass('lightbox-slider-active')
    })
// Initialize vertical slider only on desktop
// function initVerticalSlider() {
//     // Only initialize if on desktop screen size
//     if ($(window).width() >= 992 && $('.vertical-main-image-slider').length) {
//         // Initialize main vertical slider WITH arrows
// $('.vertical-main-image-slider').slick({
//     slidesToShow: 1,
//     slidesToScroll: 1,
//     arrows: true,
//     prevArrow: $('.vertical-slider-prev'),
//     nextArrow: $('.vertical-slider-next'),
//     fade: true,
//     adaptiveHeight: true,
//     infinite: false,
//     lazyLoad: 'progressive', // Changed from 'ondemand' to 'progressive'
//     speed: 300
// });
        
//         // Thumbnail click functionality
// //         $('.vertical-thumbnail-item').on('click', function() {
// //             var index = $(this).data('index');
// //             $('.vertical-main-image-slider').slick('slickGoTo', index);
            
// //             // Update active class
// //             $('.vertical-thumbnail-item').removeClass('active');
// //             $(this).addClass('active');
            
// //             // Auto-scroll thumbnail into view
// //             var container = $('.vertical-thumbnails-scroll-container');
// //             var item = $(this);
// //             var containerHeight = container.height();
// //             var itemTop = item.position().top;
// //             var itemHeight = item.outerHeight();
            
// //             // Only scroll if item is not fully visible
// //             if (itemTop < 0 || itemTop + itemHeight > containerHeight) {
// //                 var scrollTo = container.scrollTop() + itemTop - (containerHeight / 2) + (itemHeight / 2);
// //                 container.animate({
// //                     scrollTop: scrollTo
// //                 }, 200);
// //             }
// //         });
        

// $('.vertical-thumbnail-item').on('click', function (e) {
//     e.preventDefault();
//     e.stopPropagation();

//     var index = $(this).data('index');
//     $('.vertical-main-image-slider').slick('slickGoTo', index, false);

//     $('.vertical-thumbnail-item').removeClass('active');
//     $(this).addClass('active');

//     return false;
// });


//         // Update thumbnails when main slider changes
//         $('.vertical-main-image-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
//             $('.vertical-thumbnail-item').removeClass('active');
//             $('.vertical-thumbnail-item[data-index="' + nextSlide + '"]').addClass('active');
//         });




//     }
// }

// Root fix: Removed attachVerticalSliderArrowHandlers function - Slick handles arrows natively now
// This function is no longer needed since we pass arrows to Slick during initialization
// Slick automatically binds the arrow buttons when they are passed via prevArrow/nextArrow options

// Root fix: Removed event delegation handlers - Slick handles arrows natively now
// These handlers were causing conflicts with Slick's native arrow handling

// Thumbnail click handling - set up once with event delegation
// Root fix: More specific selector - target vertical slider layout only
// ROOT FIX: Thumbnail click handler with MOST SPECIFIC scoped slider selector
$(document).on('click', '.d-none.d-md-block .vertical-slider-layout .vertical-thumbnail-item', function (e) {
    e.preventDefault();
    e.stopPropagation();
    // ROOT FIX: Use MOST SPECIFIC selector
    var $slider = $('.d-none.d-md-block .vertical-slider-layout .vertical-main-image-slider');
    var $clickedThumbnail = $(this);
    var index = $clickedThumbnail.data('index');
    if ($slider.hasClass('slick-initialized') && typeof index !== 'undefined') {
        $slider.slick('slickGoTo', index, false);
        // ROOT FIX: Use MOST SPECIFIC selector for vertical layout
        var $verticalLayout = $('.d-none.d-md-block .vertical-slider-layout');
        $verticalLayout.find('.vertical-thumbnail-item').removeClass('active');
        $clickedThumbnail.addClass('active');
        // Scroll to clicked thumbnail
        setTimeout(function() {
            scrollToActiveThumbnail($clickedThumbnail);
        }, 50);
    }
    return false;
});

function initVerticalSlider() {
    // Only initialize if on desktop screen size
    // ROOT FIX: Use MOST SPECIFIC selector to avoid duplicate layouts
    if ($(window).width() >= 992 && $('.d-none.d-md-block .vertical-slider-layout .vertical-main-image-slider').length) {
        // Root fix: Wait for Bootstrap responsive classes to apply (d-none d-md-block issue)
        // Check the specific visible container
        var $parentContainer = $('.d-none.d-md-block .listing-slider.d-none.d-lg-block');
        if ($parentContainer.length && !$parentContainer.is(':visible')) {
            // Container is still hidden, wait for Bootstrap to apply d-lg-block
            setTimeout(function() {
                initVerticalSlider();
            }, 100);
            return;
        }
        
        // Root fix: Add CSS for active thumbnail border if not already added
        if (!$('#vertical-thumbnail-active-style').length) {
            $('head').append('<style id="vertical-thumbnail-active-style">.d-none.d-md-block .vertical-thumbnail-item.active { border: 3px solid #007bff !important; box-sizing: border-box; } .d-none.d-md-block .vertical-thumbnail-item.active .vertical-thumbnail-image-container { border: 2px solid #007bff !important; }</style>');
        }
        
        // ROOT FIX: Use MOST SPECIFIC selector - targets ONLY the visible desktop slider
        var $slider = $('.d-none.d-md-block .vertical-slider-layout .vertical-main-image-slider');
        
        // Destroy existing slider if already initialized
        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }
        
        // ROOT FIX: Use MOST SPECIFIC selector for arrow buttons
        var $prevBtn = $('.d-none.d-md-block .vertical-slider-layout .vertical-slider-prev');
        var $nextBtn = $('.d-none.d-md-block .vertical-slider-layout .vertical-slider-next');
        
        var slickOptions = {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true, // Enable Slick's arrow handling
            fade: true,
            adaptiveHeight: true,
            infinite: false,
            lazyLoad: 'progressive',
            speed: 300
        };
        
        // Pass arrow buttons to Slick if they exist
        if ($prevBtn.length && $nextBtn.length) {
            slickOptions.prevArrow = $prevBtn;
            slickOptions.nextArrow = $nextBtn;
        }
        
        $slider.slick(slickOptions);
        
        // Root fix: Slick now handles arrows natively - no custom binding needed!
        // The arrows are passed to Slick in slickOptions above, so Slick binds them automatically
        
        // Update thumbnails when main slider changes - use afterChange for better timing
        $slider.off('beforeChange.verticalSlider afterChange.verticalSlider init.verticalSlider')
            .on('afterChange.verticalSlider', function(event, slick, currentSlide) {
                // ROOT FIX: Use MOST SPECIFIC selector to target ONLY visible desktop layout
                var $verticalLayout = $('.d-none.d-md-block .vertical-slider-layout');
                var $desktopContainer = $verticalLayout.find('.vertical-thumbnails-scroll-container');
                
                // Root fix: Remove ALL active classes ONLY from desktop vertical thumbnails
                $desktopContainer.find('.vertical-thumbnail-item').removeClass('active');
                
                // Root fix: Select thumbnail ONLY from desktop vertical section
                var $activeThumbnail = $desktopContainer.find('.vertical-thumbnail-item[data-index="' + currentSlide + '"]').first();
                if ($activeThumbnail.length > 0) {
                    $activeThumbnail.addClass('active');
                    // Scroll the thumbnail container to show the active thumbnail
                    // Root fix: Wait for container layout AND images before scrolling
                    waitForContainerLayout($activeThumbnail, function() {
                        scrollToActiveThumbnail($activeThumbnail);
                    });
                }
            });
        
        // Set initial active thumbnail and scroll to it after slider is initialized
        $slider.on('init.verticalSlider', function(event, slick) {
            // Root fix: Use a small delay to ensure slider is fully initialized
            setTimeout(function() {
                // ROOT FIX: Use MOST SPECIFIC selector to target ONLY visible desktop layout
                var $verticalLayout = $('.d-none.d-md-block .vertical-slider-layout');
                var $desktopContainer = $verticalLayout.find('.vertical-thumbnails-scroll-container');
                
                // Root fix: Remove ALL active classes ONLY from desktop vertical thumbnails
                $desktopContainer.find('.vertical-thumbnail-item').removeClass('active');
                
                // Root fix: Get current slide directly from slick instance to ensure accuracy
                var currentSlide = typeof slick.currentSlide !== 'undefined' ? slick.currentSlide : ($slider.slick('slickCurrentSlide') || 0);
                
                // Root fix: Select thumbnail ONLY from desktop vertical section
                var $activeThumbnail = $desktopContainer.find('.vertical-thumbnail-item[data-index="' + currentSlide + '"]').first();
                if ($activeThumbnail.length > 0) {
                    $activeThumbnail.addClass('active');
                    // Root fix: Wait for images to load and layout to calculate
                    waitForContainerLayout($activeThumbnail, function() {
                        scrollToActiveThumbnail($activeThumbnail);
                    });
                } else {
                    // Fallback: If no thumbnail found, set first one as active in desktop section
                    var $firstThumbnail = $desktopContainer.find('.vertical-thumbnail-item').first();
                    if ($firstThumbnail.length > 0) {
                        $firstThumbnail.addClass('active');
                        waitForContainerLayout($firstThumbnail, function() {
                            scrollToActiveThumbnail($firstThumbnail);
                        });
                    }
                }
            }, 50);
        });
    }
}

// Root fix: Helper function to wait for container layout AND images to be calculated
function waitForContainerLayout($activeThumbnail, callback, retries) {
    retries = retries || 0;
    var maxRetries = 30; // Increased to 30 retries (3 seconds total) for image loading
    
    if (!$activeThumbnail || !$activeThumbnail.length) {
        if (callback) callback();
        return;
    }
    
    // Root fix: Find container within vertical slider layout only (more specific scope)
    var $thumbnailContainer = $activeThumbnail.closest('.vertical-thumbnails-scroll-container');
    if (!$thumbnailContainer.length) {
        $thumbnailContainer = $activeThumbnail.parents('.vertical-thumbnails-scroll-container');
    }
    
    // Ensure we're in the vertical slider layout specifically
    if ($thumbnailContainer.length) {
        var $verticalLayout = $thumbnailContainer.closest('.vertical-slider-layout');
        if (!$verticalLayout.length) {
            // Not in vertical layout, abort
            if (callback) callback();
            return;
        }
    }
    
    if ($thumbnailContainer && $thumbnailContainer.length) {
        var containerElement = $thumbnailContainer[0];
        
        // Root fix: Check if PARENT container is visible (Bootstrap d-none d-lg-block issue)
        var parentElement = containerElement.parentElement;
        var checkLevel = 0;
        while (parentElement && checkLevel < 5) {
            var parentStyle = window.getComputedStyle(parentElement);
            if (parentStyle.display === 'none') {
                // Parent is hidden, wait and retry
                if (retries < maxRetries) {
                    setTimeout(function() {
                        waitForContainerLayout($activeThumbnail, callback, retries + 1);
                    }, 100);
                    return;
                }
                break;
            }
            // Check up to wrapper level
            if (parentElement.classList.contains('listing-content-wrapper-with-slider')) {
                break;
            }
            parentElement = parentElement.parentElement;
            checkLevel++;
        }
        
        // Check if container itself is visible
        var computedStyle = window.getComputedStyle(containerElement);
        var isVisible = computedStyle.display !== 'none' && computedStyle.visibility !== 'hidden' && computedStyle.opacity !== '0';
        
        if (!isVisible) {
            // Container is hidden, wait a bit and retry
            if (retries < maxRetries) {
                setTimeout(function() {
                    waitForContainerLayout($activeThumbnail, callback, retries + 1);
                }, 100);
                return;
            }
        }
        
        // Root fix: Check if images have loaded by checking thumbnail dimensions
        var thumbnailElement = $activeThumbnail[0];
        var thumbnailHeight = thumbnailElement ? thumbnailElement.offsetHeight : 0;
        
        // Force layout recalculation
        var containerHeight = containerElement.clientHeight;
        var containerScrollHeight = containerElement.scrollHeight;
        
        // Root fix: If dimensions are 0, images haven't loaded yet - wait
        if ((containerHeight === 0 || containerScrollHeight === 0 || thumbnailHeight === 0) && retries < maxRetries) {
            // Check if images exist and are loading
            var $images = $thumbnailContainer.find('img');
            var allLoaded = true;
            $images.each(function() {
                if (!this.complete || this.naturalHeight === 0) {
                    allLoaded = false;
                    return false; // break
                }
            });
            
            if (!allLoaded || containerHeight === 0) {
                requestAnimationFrame(function() {
                    setTimeout(function() {
                        waitForContainerLayout($activeThumbnail, callback, retries + 1);
                    }, 100);
                });
                return;
            }
        }
    }
    
    // Layout is ready, execute callback
    if (callback) callback();
}

function scrollToActiveThumbnail($activeThumbnail) {
    if (!$activeThumbnail || !$activeThumbnail.length) {
        return;
    }
    
    // Root fix: Directly find the scrollable container using the known class from HTML
    var $thumbnailContainer = $activeThumbnail.closest('.vertical-thumbnails-scroll-container');
    
    // If not found, try finding it by traversing up
    if (!$thumbnailContainer.length) {
        $thumbnailContainer = $activeThumbnail.parents('.vertical-thumbnails-scroll-container');
    }
    
    // If container found, scroll to thumbnail
    if ($thumbnailContainer && $thumbnailContainer.length) {
        var containerElement = $thumbnailContainer[0];
        var thumbnailElement = $activeThumbnail[0];
        
        // Root fix: Force layout recalculation by accessing multiple properties
        // This ensures the browser calculates dimensions before we use them
        var forceLayout1 = containerElement.offsetHeight;
        var forceLayout2 = containerElement.scrollHeight;
        var forceLayout3 = thumbnailElement.offsetHeight;
        var forceLayout4 = thumbnailElement.offsetTop;
        
        // Get container dimensions
        var containerHeight = containerElement.clientHeight;
        var containerScrollHeight = containerElement.scrollHeight;
        var containerScrollTop = containerElement.scrollTop;
        
        // Root fix: Check if container has valid dimensions (not 0)
        // If dimensions are 0, layout hasn't been calculated yet - use wait function
        if (containerHeight === 0 || containerScrollHeight === 0) {
            waitForContainerLayout($activeThumbnail, function() {
                scrollToActiveThumbnail($activeThumbnail);
            });
            return;
        }
        
        // Get thumbnail position relative to container
        var thumbnailOffsetTop = thumbnailElement.offsetTop;
        var thumbnailHeight = thumbnailElement.offsetHeight;
        
        // Calculate where thumbnail currently is relative to visible area
        var thumbnailTop = thumbnailOffsetTop;
        var thumbnailBottom = thumbnailTop + thumbnailHeight;
        var visibleTop = containerScrollTop;
        var visibleBottom = containerScrollTop + containerHeight;
        
        // Check if thumbnail is already visible (with some tolerance for edge cases)
        var tolerance = 5; // pixels
        var isVisible = (thumbnailTop >= (visibleTop - tolerance) && thumbnailBottom <= (visibleBottom + tolerance));
        
        if (!isVisible) {
            // Calculate scroll position to center the thumbnail
            var scrollTo = thumbnailOffsetTop - (containerHeight / 2) + (thumbnailHeight / 2);
            
            // Ensure scroll position is within bounds
            var maxScroll = containerElement.scrollHeight - containerHeight;
            scrollTo = Math.max(0, Math.min(scrollTo, maxScroll));
            
            // Smooth scroll using native scrollTo for better compatibility
            if (containerElement.scrollTo) {
                containerElement.scrollTo({
                    top: scrollTo,
                    behavior: 'smooth'
                });
            } else {
                // Fallback to jQuery animate
                $thumbnailContainer.animate({
                    scrollTop: scrollTo
                }, 300);
            }
        }
    }
}

// Root fix: Simple, direct function to bind arrow buttons
// Root fix: Removed bindVerticalSliderArrows function - Slick handles arrows natively now

// Initialize on page load
initVerticalSlider();

// ROOT FIX: Reinitialize slider after AJAX if needed (with MOST SPECIFIC selector)
$(document).on('ajaxComplete', function() {
    setTimeout(function() {
        var $desktopSlider = $('.d-none.d-md-block .vertical-slider-layout .vertical-main-image-slider');
        if ($(window).width() >= 992 && $desktopSlider.length && !$desktopSlider.hasClass('slick-initialized')) {
            initVerticalSlider();
        }
    }, 200);
});

// ROOT FIX: Reinitialize on window resize (only if switching to desktop, with MOST SPECIFIC selector)
$(window).on('resize', function() {
    var $desktopSlider = $('.d-none.d-md-block .vertical-slider-layout .vertical-main-image-slider');
    if ($(window).width() >= 992 && $desktopSlider.length && !$desktopSlider.hasClass('slick-initialized')) {
        initVerticalSlider();
    }
});

// ROOT FIX: Removed duplicate ajaxComplete handler (already handled above with scoped selector)





    // Initialize horizontal slider (original)
    function initHorizontalSlider() {
        if ($('.listing-main-image-slider').length && !$('.listing-main-image-slider').hasClass('slick-initialized')) {
            $('.listing-main-image-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                fade: true,
                infinite: false,
                lazyLoad: 'ondemand',
                asNavFor: '.listing-thumbnail-image-slider',
                prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa-solid fa-chevron-left d_flex__justify-center border_circle d_flex__align-center'></i></button>",
                nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa-solid fa-chevron-right border_circle d_flex__justify-center d_flex__align-center'></i></button>"
            });
            
            if ($('.listing-thumbnail-image-slider').length && !$('.listing-thumbnail-image-slider').hasClass('slick-initialized')) {
                $('.listing-thumbnail-image-slider').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    asNavFor: '.listing-main-image-slider',
                    dots: false,
                    arrows: false,
                    focusOnSelect: true,
                    infinite: false,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            }
        }
    }



    
    // Initialize horizontal slider
    initHorizontalSlider();
    
    // Reinitialize if horizontal slider is loaded dynamically
    $(document).on('ajaxComplete', function() {
        setTimeout(function() {
            initHorizontalSlider();
        }, 100);
    });

    // Keyboard navigation for vertical slider - Root fix: Direct Slick method calls
    $(document).on('keydown', function(e) {
        var $slider = $('.vertical-main-image-slider');
        if ($('.vertical-slider-layout').is(':visible') && $slider.hasClass('slick-initialized')) {
            if (e.keyCode === 37) { // Left arrow - Previous slide
                e.preventDefault();
                e.stopPropagation();
                $slider.slick('slickPrev');
                return false;
            } else if (e.keyCode === 39) { // Right arrow - Next slide
                e.preventDefault();
                e.stopPropagation();
                $slider.slick('slickNext');
                return false;
            } else if (e.keyCode === 38) { // Up arrow - Scroll thumbnails up
                e.preventDefault();
                e.stopPropagation();
                var $scrollUp = $('.vertical-thumb-scroll-up');
                if ($scrollUp.length > 0) {
                    $scrollUp.click();
                }
                return false;
            } else if (e.keyCode === 40) { // Down arrow - Scroll thumbnails down
                e.preventDefault();
                e.stopPropagation();
                var $scrollDown = $('.vertical-thumb-scroll-down');
                if ($scrollDown.length > 0) {
                    $scrollDown.click();
                }
                return false;
            }
        }
    });

    // My Garage Dropdowns
    $('#garage-vehicle-model').select2({
        placeholder: 'Select Model of Vehicle',
        multiple: true,
        dropdownAutoWidth: true,
        width: '100%',
    })
    $('#garage-vehicle-purpose').select2({
        placeholder: 'Select as many that apply',
        multiple: true,
        dropdownAutoWidth: true,
        width: '100%',
    })
    $('#garage-vehicle-musthaves').select2({
        placeholder: 'Select as many that apply',
        multiple: true,
        dropdownAutoWidth: true,
        width: '100%',
    })
    // $('.select2-search__field').css('width', '100%');
    // $('.select2-dropdown').select2({
    //     multiple: true
    // })
    
    // Move to next tab in My garage
    $('.garage-move-next-tab').click(() => {
        let moveToNext = true;
        $('.garage-tab-1').find('.select2-dropdown').each(function (index, data) {
            let val = $(data).val()
            if (!val || val == '') {
                moveToNext = false;
                return false; // exit the loop
            }
        })
        if (moveToNext) {
            $('.garage-tab-1').addClass('d-none')
            $('.garage-tab-2').removeClass('d-none')
        } else {
            $('#garage1-error').removeClass('d-none').text('Please select from all')
            setTimeout(() => {
                $('#garage1-error').addClass('d-none').text('')
            }, 1500);
        }

    })
    
    // Load Must haves on garage model select
    $('#garage-vehicle-model').change(function (e) {
        let selectedTypes = $(this).val()
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                'vehicle': selectedTypes,
                'isVDP': true,
                'action': 'get_selected_musthaves'
            },
            success: function (response) {
                let res = jQuery.parseJSON(response);
                if (typeof res.featureList !== 'undefined' && res.featureList !== '') {
                    let featureListArr = res.featureList.split(',')
                    $('#garage-vehicle-musthaves').empty(); // Clear existing options
                    $(featureListArr).each(function (index, data) {
                        $('#garage-vehicle-musthaves').append(`<option>${data}</option>`)
                    })
                }
                if(typeof res.minPrice !== 'undefined' && res.minPrice !== '') {
                    $('#garage-price-min').attr({'min': res.minPrice, 'max': res.maxPrice, 'value': res.minPrice})
                    $('.VDP_min-price').html(`$ ${res.minPrice.toLocaleString()}`)
                }
                if(typeof res.maxPrice !== 'undefined' && res.maxPrice !== '') {
                    $('#garage-price-max').attr({'min': res.minPrice, 'max': res.maxPrice, 'value': res.maxPrice})
                    $('.VDP_max-price').html(`$ ${res.maxPrice.toLocaleString()}`)
                }
                let minPercent = ((res.minPrice - Number($('#garage-price-min').attr('min'))) / (Number($('#garage-price-max').attr('max')) - Number($('#garage-price-min').attr('min')))) * 100;
                let maxPercent = ((res.maxPrice - Number($('#garage-price-min').attr('min'))) / (Number($('#garage-price-max').attr('max')) - Number($('#garage-price-min').attr('min')))) * 100;

                // Set position of highlighted area
                $('.VDP_range-highlight').css({
                    'left': minPercent + '%',
                    'width': (maxPercent - minPercent) + '%'
                });

                $('.garage-price-range-value').val(`${res.minPrice},${res.maxPrice}`)

            },
            error: function (error) {
                alert('Error in fetching the musthaves and price please reload the page');
            }
        })
    })

    $(document).on('click', '.load-more-garage-results', function() {
        let paged = $(this).attr('data-paged')
        let style = $(this).attr('data-style').split(',');
        $(this).addClass('disabled')
        loadMyGarageVehicles(style, paged)
    })

    $(document).on('wpcf7submit', '.my-garage-tab-wrapper form', function () {
        let vehicleStyle = $('#garage-vehicle-model').val()
        let garageRequiredFields = $('.garage-required-field')
        let garagePaged = $('.garage-response-wrapper').attr('data-paged')
        let allFieldsHaveValues = true;

        $(garageRequiredFields).each(function (index, data) {
            if ($(data).val() == '' || !$(data).val()) {
                allFieldsHaveValues = false;
                return false;
            }
        })
        if( allFieldsHaveValues ) {
            loadMyGarageVehicles(vehicleStyle, garagePaged)
        }
    })
    function loadMyGarageVehicles(vehicleStyle, paged) {
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                vehicleStyle: vehicleStyle,
                garagePaged: paged,
                action: 'loadMyGarageVehicles',
            },
            success: function (response) {
                let res = jQuery.parseJSON(response);
                if( res !== null || res !== '' ) {
                    $('.garage-response-wrapper').html(res.cardLayout)
                    if( res.foundVehicles > 0 ) { // if the total number of vehiclse greater then 0
                        $('.garage-response-wrapper').attr('data-paged', Number(paged) + 1).addClass('vehicles-for-you-grid')
                        if( res.postCount >= 5 ) { // if the returned post count is greater then or equal to 5
                            $('.load-more-garage-results').removeClass('d-none').removeClass('disabled').attr('data-paged', Number(paged) + 1).attr('data-style', vehicleStyle)
                        }else { // if the returned post count is less then 5
                            $('.load-more-garage-results').addClass('d-none').addClass('disabled')
                        }
                    }else {
                        $('.load-more-garage-results').addClass('d-none').addClass('disabled').attr('data-paged', Number(paged)).attr('data-style', vehicleStyle)
                        $('.garage-response-wrapper').attr('data-paged', Number(paged)).removeClass('vehicles-for-you-grid')
                    }
                }
            },
            error: function (XHR, status, error) {
                $('.load-more-garage-results').addClass('disabled').addClass('disabled')
                alert('something went wrong please submitting the form again' + ' ' + status)
            }
        })
    }

    // My garage price filters
    $(document).on('input', '.VDP_range-filters', function (event) {
        let minVal, maxVal, fieldStep, minValue, maxValue;
        minVal = Number($('#garage-price-min').val())
        maxVal = Number($('#garage-price-max').val())
        fieldStep = Number($('#garage-price-min').attr('step'))
        minValue = Number($('#garage-price-min').attr('min'))
        maxValue = Number($('#garage-price-min').attr('max'))
        $('.VDP_min-price').text(`$ ${minVal.toLocaleString()}`)
        $('.VDP_max-price').text(`$ ${maxVal.toLocaleString()}`)

        // Check if min value exceeds max value
        if (minVal >= maxVal) {
            minVal = maxVal - fieldStep; // Set min value to max value - step
            $(event.target).closest('.filter-min-field').val(minVal); // Update min input value
        }

        let minPercent = ((minVal - minValue) / (maxValue - minValue)) * 100;
        let maxPercent = ((maxVal - minValue) / (maxValue - minValue)) * 100;

        // Set position of highlighted area
        $('.VDP_range-highlight').css({
            'left': minPercent + '%',
            'width': (maxPercent - minPercent) + '%'
        });

        $('.garage-price-range-value').val(minVal + ',' + maxVal);
    });

    // Add current time to timestamp field in mygarage tab form
    function myGarageTimestamp() {
        let d = new Date();
        let h = d.getHours();
        let fullYear = d.getFullYear();
        let month = d.getMonth();
        let day = d.getDate();
        let minute = d.getMinutes();
        let monthNames = ['January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'September', 'October', 'November', 'December'];
        let monthName = monthNames[month];
        let timestamp = fullYear + ' ' + monthName + ' ' + day + ' ' + '-' + ' ' + (h<10?'0':'')+ h + ':' + (minute < 10 ? '0': '') + minute + ' ' + (h< 12 ? 'AM' : 'PM');
        $('#mygarage-timestamp').val(timestamp)
    }
    myGarageTimestamp()

    // Compare Function in VDP page
    $(document).on('click', '.remove-vdp-compare', function(event) {
        event.stopPropagation();
        loadCompareVehicles(false, $(this).attr('data-remove'), false)
    })
    $(document).on('click', '.add-current-vehicle-compare', function(event) {
        event.stopPropagation();
        loadCompareVehicles(true, $('.VDP-content-wrapper').attr('data-listing'), false)
    })
    loadCompareVehicles(null, $('.VDP-content-wrapper').attr('data-listing'), true)

    function loadCompareVehicles(addCurrentVehicle, listingID, pageLoad = true) {
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                addCurrentVehicle: addCurrentVehicle,
                VDPListing: listingID,
                pageLoad: pageLoad,
                action: 'loadCompareVehicles'
            },
            success: function(response) {
				console.log('compare response', response)
                if( response !== null && response !== '' ) {
                    $('.compare-body').html(response.data.cardLayout)
                    $('.compare-vehicles-wrapper').html(response.data.cardLayout)
                    $('[data-toggle="tooltip"]').tooltip()
                    if( response.data.foundVehicles !== '' || response.data.foundVehicles !== null ) {
                        if( response.data.foundVehicles < 2 ) {
                            $('.compare-box-container .compare-btn').addClass('d-none')
                            $('.recent-card-compare-btn').addClass('d-none')
                        }else {
                            $('.compare-box-container .compare-btn').removeClass('d-none')
                            $('.recent-card-compare-btn').removeClass('d-none')
                        }
                    }
                }
            }
        })
    }
	
	setTimeout(() => {
        $('.more-cars-found').css({
            minWidth: '265px',
            transition: 'min-width 0.5s',
            flexBasis: 'auto'
        })
        $('.star-empty-icon').addClass('d-none').removeClass('d-flex').css('width', '0')
        $('.star-active-icon').removeClass('d-none').addClass('d-flex')

        setTimeout(() => {
            $('.more-cars-found').css({
                minWidth: 0,
                flexBasis: '0'
            })
            $('.star-empty-icon').removeClass('d-none').addClass('d-flex').css('width', 'auto')
            $('.star-active-icon').addClass('d-none').removeClass('d-flex')
        }, 5000);
    }, 5000);
	
	$(document).on('click', '.listing-thumbnail-image-slider:not(.slick-initialized) img', function() {
		let index = $(this).parent().index(); // Get the index of the clicked thumbnail
		$('.listing-main-image-slider').slick('slickGoTo', index);
	});

	
	/** Lazy Load Images */
function loadImage(img) {
    let $img = $(img);
    let dataSrc = $img.attr('data-src');
    
    if (!dataSrc) return;

    // Add preloader if not already present
    let preloader = $('<div class="preloader d-flex align-items-center justify-content-center position-absolute top-0 left-0 w-100 h-100 bg-light">' +
        '<div class="spinner-border text-primary" role="status">' +
        '<span class="sr-only">Loading...</span></div></div>');

    $img.before(preloader).hide(); // Hide the image until it's loaded

    // Preload image before setting src
    let tempImg = new Image();
    tempImg.src = dataSrc;

    tempImg.onload = function () {
        $img.attr('src', dataSrc).fadeIn(); // Show image after loading
        preloader.remove();
        $img.removeAttr('data-src'); // Remove data-src to prevent re-checking
    };
}

function checkImages() {
    $('img[data-src]').each(function () {
        let img = $(this);
        if (img.is(':visible') && img[0].getBoundingClientRect().top < window.innerHeight) {
            loadImage(img[0]);
        }
    });
}

	$(window).on('scroll resize', checkImages);
	$(document).ready(checkImages); // Run on page load
    
});