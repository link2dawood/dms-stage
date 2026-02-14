$(document).ready(function ($) {
    var searchSortApplied = false;
    var priceApplied = false;
    var mileageApplied = false;
    var vConditionApplied = false;
    var vModel = false;
    var vTrim = false;
	let currentFilter = '';
	
	// Check if the URL contains the pricerange parameter	
	function checkPriceMileageFilterStatus() {
		const urlParams = new URLSearchParams(window.location.search);
		if (urlParams.has('price[]')) {
			priceApplied = true;
		}
		if(urlParams.has('mileage')) {
			mileageApplied = true;
		}
	}
	

    /* LIST AND GRID VIEW */
    jQuery('.inventory-products-bar__layout-list').on('click', function (event) {
        durango_toggle_inventory_view(event, 'list')
    });

    jQuery('.inventory-products-bar__layout-grid').on('click', function (event) {
        durango_toggle_inventory_view(event, 'grid')
    });

    /*
     *** Switch between grid and list view
     *** @param string 'trigger' specifies the trigger of the function to apply necessary conditions
     ***/
    function durango_toggle_inventory_view(event, trigger) {
        if (trigger === 'list') {
            // Add and remove active class from the view toggle buttons
            jQuery('.inventory-products-bar__layout-list').addClass('active')
            jQuery('.inventory-products-bar__layout-grid').removeClass('active')

            // Add and remove classes from elements
//             jQuery('#vehicles-container').find('.col-12.col-lg-6').removeClass('col-lg-6').removeClass('col-xl-4')
            jQuery('#vehicles-container').find('.col-12.col-lg-6').removeClass('col-xl-4')
			jQuery('#vehicles-container').addClass('listview-active')
            jQuery('.listing-card-wrapper').addClass('d-flex').addClass('p-20')
            jQuery('.vehcile-cta-wrapper').removeClass('d-none').addClass('d-flex')
            jQuery('.card-image-wrapper').addClass('flex-grow-1').addClass('active').css('padding-bottom', '35px')
            jQuery('.listview-visible').removeClass('d-none').addClass('d-flex')
            jQuery('.listview-hidden').addClass('d-none').removeClass('d-flex')
            jQuery('.card-content-wrapper').addClass('flex-grow-1').addClass('active')
            jQuery('.vehicle-title-wrapper').addClass('active').addClass('mt-3').children('h2').css('line-height', 'normal')
            jQuery('.card-vehicle-like').addClass('d-none')
            jQuery('.card-vehicle-liked').addClass('d-none').removeClass('d-flex')
            jQuery('.vehicle-stickers-container').addClass('flex-column').addClass('align-items-center')
                .addClass('d-flex')
            jQuery('.vehicle-stickers-container.listview-visible a:nth-child(2)').addClass('d-none').removeClass('d-flex')
            jQuery('.fake-recent-view-badge').addClass('d-none')
            jQuery('.fake-managers-specials-badge').addClass('d-none')
            jQuery('.vehcile-cta-wrapper').css('bottom', 0);

            jQuery('.card-content-wrapper').each(function (index, data) {
                let parentElem = jQuery(data).parents('.listing-card-wrapper')
                let appendTo = $(parentElem).find('.vehicle-meta-wrapper > .w-50:last-child')
                $(data).find('.explore-more-cta').prependTo(appendTo)
            })
            jQuery('.explore-more-cta').addClass('mt-20')
            /*
             *** Loop over the vehicle-meta-wrapper elements to add class
             justify-content-start ***/

            jQuery('.vehicle-meta-wrapper').each(function (index, data) {
                $(data).find('.w-50').first().find('.justify-content-between')
                    .removeClass('justify-content-between').addClass('justify-content-start')
            })

            if (event !== undefined) {
                if (jQuery('.listing-image-slider-inner').length > 0 &&
                    jQuery(event.currentTarget).hasClass('inventory-products-bar__layout-list')) {
                    jQuery('.listing-image-slider-inner').each(function (index, slide) {
                        if (jQuery(slide).hasClass('slick-initialized')) {
                            jQuery(slide).slick('unslick')
                        }
                    })
                }
            }

            jQuery('.listing-image-slider-inner').each(function (index, slide) {
                if (!jQuery(slide).hasClass('slick-initialized')) {
                    jQuery(slide).slick({
                        arrows: true,
                        infinite: false,
                        dots: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        prevArrow: "<button type='button' class='slick-prev pull-left inventory-slick-arrow'><i class='fa-solid fa-chevron-left'></i></button>",
                        nextArrow: "<button type='button' class='slick-next pull-right inventory-slick-arrow'><i class='fa-solid fa-chevron-right'></i></button>",
                    })
                }
            })

            jQuery('.vehicle-meta-wrapper > .w-50:first-child .d-flex.align-items-center.justify-content-start').addClass('mb-1').children('span').css('line-height', 'normal')

            // Add active class on the elements to modify them in list view
            jQuery('.card-image-wrapper').addClass('active')
            jQuery('.listing-card-wrapper').addClass('active')
            jQuery('.vehicle-meta-wrapper').addClass('active')
            jQuery('.listing-image-slider').removeClass('overflow-hidden')

            // Copy colors HTML from one column to the second column
            jQuery('.vehicle-meta-wrapper > .w-50:last-child').each(function () {
                let colorsHTML = '<div class="d-flex align-items-center justify-content-start">';
                colorsHTML += '<span class="font-helvetica font-sm text-grey-3 font-weight-normal">' +
                    jQuery(this).find('p.listview-hidden').html() +
                    '</span>';
                colorsHTML += jQuery(this).find('div.listview-hidden').html();
                colorsHTML += '</div>';

                // Append the copied HTML
                jQuery(this).prev().find('.card-colors-wrapper').html(colorsHTML);
            });

        } else if (trigger === 'grid') {
            // Add and remove active class from the view toggle buttons
            jQuery('.inventory-products-bar__layout-list').removeClass('active')
            jQuery('.inventory-products-bar__layout-grid').addClass('active')
            // Add and remove classes from elements
            jQuery('#vehicles-container').find('.col-12').addClass('col-lg-6').addClass('col-xl-4')
			jQuery('#vehicles-container').removeClass('listview-active')
            jQuery('.listing-card-wrapper').removeClass('d-flex').removeClass('p-20')
            jQuery('.vehcile-cta-wrapper').addClass('d-none').removeClass('d-flex')
            jQuery('.card-image-wrapper').removeClass('flex-grow-1').removeClass('active').css('padding-bottom', '0')
            jQuery('.listview-visible').addClass('d-none').removeClass('d-flex')
            jQuery('.listview-hidden').removeClass('d-none').addClass('d-flex')
            jQuery('.card-content-wrapper').removeClass('flex-grow-1').removeClass('active')
            jQuery('.vehicle-title-wrapper').removeClass('active').removeClass('mt-3').children('h2').css('line-height', '2rem')

            jQuery('.card-vehicle-like[data-icon-show="true"]').removeClass('d-none')
            jQuery('.card-vehicle-liked[data-icon-show="true"]').removeClass('d-none').addClass('d-flex')

            jQuery('.vehicle-stickers-container').removeClass('flex-column').removeClass('align-items-center')
                .removeClass('d-flex')

            jQuery('.fake-recent-view-badge').removeClass('d-none')
            jQuery('.vehicle-colors-text').removeClass('d-flex')
            jQuery('.fake-managers-specials-badge').removeClass('d-none')

            jQuery('.vehcile-cta-wrapper').css('bottom', 0);
            jQuery('.card-content-wrapper').each(function (index, data) {
                $(data).find('.vehicle-price-wrapper').after($(data).find($('.explore-more-cta')))
                $(data).find('.vehicle-meta-wrapper .explore-more-cta').remove()
            })

            jQuery('.explore-more-cta').removeClass('mt-20')

            /*
             *** Loop over the vehicle-meta-wrapper elements to add class
             justify-content-start ***/

            jQuery('.vehicle-meta-wrapper').each(function (index, data) {
                $(data).find('.w-50').first().find('.justify-content-start')
                    .addClass('justify-content-between').removeClass('justify-content-start')
            })

            if (event !== undefined) {
                if (jQuery('.listing-image-slider-inner').length > 0 &&
                    jQuery(event.currentTarget).hasClass('inventory-products-bar__layout-grid')) {
                    jQuery('.listing-image-slider-inner').each(function (index, slide) {
                        if (jQuery(slide).hasClass('slick-initialized')) {
                            $(slide).slick('unslick')
                        }
                    })
                }
            }

            // Re initiate slick slider
            jQuery('.listing-image-slider-inner').each(function (index, slide) {
                if (!jQuery(slide).hasClass('slick-initialized')) {
                    $(slide).slick({
                        arrows: true,
                        infinite: false,
                        dots: true,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        prevArrow: "<button type='button' class='slick-prev pull-left inventory-slick-arrow'><i class='fa-solid fa-chevron-left'></i></button>",
                        nextArrow: "<button type='button' class='slick-next pull-right inventory-slick-arrow'><i class='fa-solid fa-chevron-right'></i></button>",
                    })
                }
            })

            jQuery('.vehicle-meta-wrapper > .w-50:first-child .d-flex.align-items-center.justify-content-start').removeClass('mb-1').children('span').css('line-height', '')
            // Add active class on the elements to modify them in list view
            jQuery('.card-image-wrapper').removeClass('active')
            jQuery('.listing-card-wrapper').removeClass('active')
            jQuery('.vehicle-meta-wrapper').removeClass('active')
            jQuery('.listing-image-slider').addClass('overflow-hidden')
            // Copy colors HTML from one column to the second column
            jQuery('.vehicle-meta-wrapper > .w-50:last-child').each(function () {
                let colorsHTML = '';
                jQuery(this).prev().find('.card-colors-wrapper').html(colorsHTML);
            });
        }

        durango_hide_slider_dot_pagination();
    }


    /* Enable compare button */
	$(document).on('click change', '.compare-listings__card-remove span, .compare-listings__remove-all, .compare-vehicles-popup__overlay, .compare-vehicles-popup__close, .compare-listings__close, .compare-listings__minimize', function (e) {
		compareVehicles($(this));
	});
	
	$(document).on('change', '.chk-compare', function (e) {
		compareVehicles($(this));
	});

    function compareVehicles(e) {
        let compareBoxWrapper = $('.compare-listings__wrapper');
        let listingsWrapper = $('.inventory-products-bar__listings-wrapper');
        let compareTrigger = $('.compare-listings__compare');
        let compareCardsWrapper = $('.compare-listings__cards')
        // if the element is not popup overlay or popup close button
        if (!$(e).hasClass('compare-vehicles-popup__close')) {
            // check if element is checkbox
            if ($(e).hasClass('chk-compare')) {
                if ($(e).is(':checked')) {
                    updateCompareVehicles($(e).val(), true, 'checkbox', $(e))
                } else {
                    updateCompareVehicles($(e).val(), false, 'checkbox', $(e))
                }
            } else if ($(e).parent().hasClass('compare-listings__card-remove')) {
                // User clicked on remove icon of compare card in compare box
                console.log('Card Remove', $(e).attr('data-remove'))
                updateCompareVehicles($(e).attr('data-remove'), false, 'cardRemove', $(e))
            } else if ($(e).hasClass('compare-listings__remove-all')
                || $(e).hasClass('compare-listings__close') ||
                $(e).hasClass('compare-vehicles-popup__close') ||
                $(e).hasClass('compare-vehicles-popup__overlay')) {
                let productsArr = []
                $('.compare-listings__wrapper .compare-listings__card').map(function (index, data) {
                    productsArr.push($(data).data('remove'))
                })
                updateCompareVehicles(productsArr, false, 'productsRemoveAll', $(e));
            } else if( $(e).hasClass('compare-listings__minimize') ) {
				$(compareBoxWrapper).animate({
                    bottom: '-100%'
                }, 500);
			}
            return false;
        }
        let productsArr = []
        $('.compare-listings__wrapper .compare-listings__card').map(function (index, data) {
            productsArr.push($(data).data('remove'))
        })
        updateCompareVehicles(productsArr, false, 'productsRemoveAll', $(e));
        $('.compare-vehicles-popup').css('display', 'none')
        $('body').removeClass('overflow-hidden').removeClass('hideCompareCheckbox')
        $(compareTrigger).addClass('disabled')
        $('.inventory-products-bar__listings-wrapper').find('.chk-compare:checked').prop('checked', false);
        $(listingsWrapper).find('.chk-compare').css('display', 'block')
        $(compareCardsWrapper).children().each(function (index, data) {
            $(data).fadeOut(500, function () {
                $(data).remove();
                $(compareBoxWrapper).animate({
                    bottom: '-100%'
                }, 500);
            });
        })
    }
    // Update user_compare_vehicles option and remove the product transient
    function updateCompareVehicles(postId, checkedStatus, elemType = undefined, targetElem = null) {
        let isArr = Array.isArray(postId);
        $(document).find('.chk-compare').attr('disabled', true);
        $(document).find('.compare-listings__card-remove span').attr('disabled', true);
        $(document).find('.compare-listings__remove-all').attr('disabled', true)
        $(document).find('.compare-listings__close').attr('disabled', true)

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                checkedPostId: postId,
                isArr: isArr,
                checkStatus: checkedStatus,
                action: 'userComparedVehicles',
            },
            success: function (response) {
                $(document).find('.chk-compare').attr('disabled', false);
                $(document).find('.compare-listings__card-remove span').attr('disabled', false);
                $(document).find('.compare-listings__remove-all').attr('disabled', false)
                $(document).find('.compare-listings__close').attr('disabled', false)
				
				
                let res = jQuery.parseJSON(response);
				console.log('compare response', res, elemType, targetElem)
                if (res.comparedVehicles !== null) {
                    let checkedLength = res.comparedVehicles;

                    // elemType === 'checkbox' means ajax call was called by checkbox element
                    if (elemType === 'checkbox') {
                        let compareCardImage = $(targetElem).parent().parent().prev().find('.slick-slide:first-child img').attr('src')
                        let compareCardHTML = `
                        <div class="compare-listings__card" data-remove="${$(targetElem).val()}">
                            <div class="compare-listings__card-img">
                                <img src="${compareCardImage}" alt="products image">
                            </div>
                            <div class="compare-listings__card-remove">
                                <span class="fa fa-times" aria-hidden="true" data-remove="${$(targetElem).val()}"></span>
                            </div>
                        </div>`;

                        /* Add or remove compare card from compare box wrapper */
                        if (checkedStatus) {
                            $('.compare-listings__cards').append(compareCardHTML)
                        } else {
                            $('.compare-listings__cards').find('.compare-listings__card').each(function (index, data) {
                                if ($(data).data('remove') === Number($(targetElem).val())) {
                                    $(data).remove()
                                }
                            })
                        }

                        // Logic if user unchecked a checkbox
                        $(targetElem).parent().parent().append(`<div class="compare-guide-modal">
                        <p class="p_0 font_helvetica text_uppercase">you selected ${checkedLength} out of 3 </p> </div>`)
                        setTimeout(function () {
                            $(document).find('.compare-guide-modal').remove()
                        }, 3000);
                    } else if (elemType === 'cardRemove') {
						console.log('card remove success')
                        // User removed the card from compare box
                        let listingsWrapper = $('.inventory-products-bar__listings-wrapper');
                        $(targetElem).parent().parent().remove()
                        let listingsChilds = $(listingsWrapper).children('.col-12').children('.listing-card-wrapper')
						console.log(listingsWrapper, listingsChilds)
                        $(listingsChilds).each(function (index, data) {
                            let inputBox = $(data).find('.inventory-products-bar__compare-listing-form .chk-compare');
							console.log('input box', inputBox, $(inputBox).val(), $(targetElem).data('remove'), $(targetElem))
                            if ($(inputBox).val() == $(targetElem).data('remove')) {
                                if ($(inputBox).is(':checked')) {
                                    $(inputBox).prop('checked', false)
                                }
                            }
                        })
                    } else if (elemType === 'productsRemoveAll') {
                        $('.compare-listings__compare').addClass('disabled')
                        $('body').removeClass('hideCompareCheckbox');
                        $('.inventory-products-bar__listings-wrapper').find('.chk-compare:checked').prop('checked', false);
                        $('.compare-listings__cards').children().each(function (index, data) {
                            $(data).fadeOut(500, function () {
                                $(data).remove();
                                $('.compare-listings__wrapper').animate({
                                    bottom: '-100%'
                                }, 500);
                            });
                        })
						
						console.log('target elemento', targetElem, $(targetElem))

                        if ($(targetElem).hasClass('compare-vehicles-popup__overlay') || $(targetElem).hasClass('compare-vehicles-popup__close')) {
                            $('.compare-vehicles-popup').css('display', 'none')
                            $('body').removeClass('overflow-hidden')
                        }
                    }
                    // run other statements
                    let compareTrigger = $('.compare-listings__compare');
                    if (checkedLength >= 3) {
                        $('body').addClass('hideCompareCheckbox');
                    } else {
                        $('body').removeClass('hideCompareCheckbox');
                    }
                    if (checkedLength >= 2) {
                        $(compareTrigger).removeClass('disabled');
                    } else {
                        $(compareTrigger).addClass('disabled');
                    }
                    if (checkedLength >= 1 && checkedLength <= 3) {
                        $('.compare-listings__wrapper').animate({
                            bottom: '15px'
                        }, 500);
                    } else {
                        $('.compare-listings__wrapper').animate({
                            bottom: '-100%'
                        }, 500);
                    }
                } else {
                    // If response is null and element is checkbox
                    // Logic if user unchecked a checkbox
                    if (targetElem !== undefined) {
                        $(targetElem).parent().parent().append(`<div class="compare-guide-modal">
                        <p class="p_0 font_helvetica text_uppercase">you selected ${$(res.comparedVehicles).length} out of 3 </p> </div>`)
                        setTimeout(function () {
                            $(document).find('.compare-guide-modal').remove()
                        }, 3000);
                    }
                    // Also remove the product card from the compare box
                    $('.compare-listings__cards').children().each(function (index, data) {
                        $(data).fadeOut(500, function () {
                            $(data).remove();
                            $('.compare-listings__wrapper').animate({
                                bottom: '-100%'
                            }, 500);
                        });
                    })
                    // Make the compare button disabled
                    $('.compare-listings__compare').addClass('disabled')
                }
            },
            error: function (XHR, status, error) {
                alert('something went wrong please try again' + status)
                $(document).find('.chk-compare').attr('disabled', false);
                $(document).find('.compare-listings__card-remove span').attr('disabled', false);
                $(document).find('.compare-listings__remove-all').attr('disabled', false)
                $(document).find('.compare-listings__close').attr('disabled', false)
            }
        })
    }

    // hide the compare box body on header click
    $('.compare-listings__header').click(function () {
        $(this).parent().toggleClass('closed')
        $(".compare-listings__body").animate({
            height: "toggle"
        }, 500);
        $('.compare-listings-shrink').toggleClass('arrowUP')
    })
    /* Compare feature ajax script */
    jQuery(".compare-btn a").on("click", function () {
        var $this = jQuery(this);
        $this.text('Comparing...');

        jQuery.ajax({
            url: ajax_object.ajax_url,
            type: "POST",
            data: { "action": "rr_compare_vehicles" },
            success: function (resp) {
                $this.text("Compare");
                var obj = jQuery.parseJSON(resp);
                if (obj.html != '') {
                    jQuery("#compareModal .compare-result").html(obj.html);
                    // jQuery("#compareModal").modal("show");
                    jQuery("#compareModal").css('display', 'flex');
                    $('body').addClass('overflow-hidden')
                }
            },
            error: function () {
                alert('error in comparing');
                $this.text("Compare");
            }
        });
    });

    // ajax filters setup
    var appliedFilters = {
        "year": false,
        "make": false,
        "model": false,
        "body-style": false,
        "type-of-vehicle": false,
        "doors": false,
        "mileage-filter": false,
        "cylinders": false,
        "drivetrain": false,
        "transmission": false,
        "exterior-color": false,
        "interior-color": false,
        "price": false,
		"original_price": false,
		"miscprice-1": false,
        "certified": false,
        "fuel-type": false,
        "certified-pre-owned-ford": false,
        "certified-pre-owned-toyota": false,
        "certified-pre-owned-kia": false,
        "certified-pre-owned": false,
		"series": false,
		"trim": false
    };
    let dropdownFilters = $('.dropdown-filters');
    let searchFilterIcon = $('.search-filter-icon')
    $(document).on('change', '.input-pagination', function (e) {
        var inputValue = parseInt($(e.target).val());
        var dataTotal = parseInt($(e.target).attr('data-total'));
        if (inputValue > dataTotal) {
            alert('Please enter a page number smaller than total pages number.');
            return;
        } else if (inputValue < 1) {
            alert('Please enter a page number greater than 1.');
            return;
        } else {
            clickedShowMore = false;
            isScroll = false;
            searchSortApplied = false;
            priceApplied = false;
            mileageApplied = false;
            vConditionApplied = false;
			checkPriceMileageFilterStatus();
			sessionStorage.setItem('inline_banner_index', 0)
            ajaxFilters(e, undefined, inputValue);
        }
    });
    $(document).on('click', '.all-pages', function (e) {
        const currentPage = parseInt($('#vehicles-container').attr('data-current-page'), 10) || 1;
        const maxPages = parseInt($('#vehicles-container').attr('data-max-pages'), 10) || 1;

        if (currentPage >= maxPages) {
            $(document).find('span.all-pages').removeClass('d-md-inline-block');
            return;
        }

        clickedShowMore = true;
        isScroll = true;
        searchSortApplied = false;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = false;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        ajaxFilters(undefined, 6, currentPage + 1, 'true');
    })

    $('.search-filters').keyup(function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            if ($(e.target).val() != '') {
                e.preventDefault()
                // Only pass value of main search bar
                $(e.target).hasClass('main-search-filters') ? $('.secondary-search-filters').val($(e.target).val()) : $('.main-search-filters').val($(e.target).val());
                clickedShowMore = false;
                isScroll = false;
                searchSortApplied = true;
                priceApplied = false;
                mileageApplied = false;
                vConditionApplied = false;
				checkPriceMileageFilterStatus();
				sessionStorage.setItem('inline_banner_index', 0)
                return ajaxFilters(e)
            }
        }
    })

    var selectedState = {};
    $(document).on('change', '.checkbox-filters', function (e) {
        var filterType = $(e.target).attr('data-type');
        let filterValue = $(e.target).val()
		
        // Set all other filter types to false
        for (var key in appliedFilters) {
            if (key !== filterType) {
                appliedFilters[key] = false;
            }
        }
        if (!$(e.target).is(':checked')) {
            const url = new URL(window.location.href);
        	const params = new URLSearchParams(url.search);
			
			const queryParam = `${filterType.toLowerCase()}[]=${encodeURIComponent(filterValue)}`.toLowerCase();
			if (params.toString().toLowerCase().includes(queryParam)) {
				params.delete(filterType.toLowerCase() + '[]');
				url.search = params.toString();
				window.history.replaceState({}, '', url.toString());
			}

			const certifiedValues = ['yes', 'toyota', 'kia', 'ford'];
			const certifiedPaths = ['certified-pre-owned-toyota', 'certified-pre-owned-ford', 'certified-pre-owned-kia', 'certified-pre-owned'];

			if (certifiedValues.includes(filterValue)) {
				certifiedPaths.forEach(path => {
					if (url.pathname.toLowerCase().includes(path)) {
						url.pathname = url.pathname.replace(path, '');
						window.history.replaceState({}, '', url.toString());
					}
				});
			}
			
        }
		
        // Update the appliedFilters object
        clickedShowMore = false;
        isScroll = false;
        searchSortApplied = false;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = false;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        ajaxFilters(e);
    });
	
    $(document).on('input', '.range-filters', function (event) {

        let type = $(event.target).data('filter');
        appliedFilters[type] = true;
        for (var key in appliedFilters) {
            if (key !== type) {
                appliedFilters[key] = false;
            }
        }
        let minVal, maxVal, fieldStep, minValue, maxValue;
				
        if ($(event.target).data('filter') === 'mileage') {
            minVal = Number($('#mileage-min').val());
            maxVal = Number($('#mileage-max').val());
            fieldStep = Number($('#mileage-min').attr('step'));
            minValue = Number($('#mileage-min').attr('min'));
            maxValue = Number($('#mileage-min').attr('max'));
            $('.mileage-min-text').html(minVal.toLocaleString());
            $('.mileage-max-text').html(maxVal.toLocaleString());
        } else if ($(event.target).data('filter') === 'price') {
			minVal = Number($('#price-min').length ? $('#price-min').val() : 0);
			maxVal = Number($('#price-max').length ? $('#price-max').val() : 100);
			fieldStep = Number($('#price-min').length ? $('#price-min').attr('step') : 10);
			minValue = Number($('#price-min').length ? $('#price-min').attr('min') : 0);
			maxValue = Number($('#price-min').length ? $('#price-min').attr('max') : 0);
						
            $('.price-min-text').html('$ ' + minVal.toLocaleString());
            $('.price-max-text').html('$ ' + maxVal.toLocaleString());
        }
		
        // Check if min value exceeds max value
        if (minVal >= maxVal) {
            minVal = maxVal - fieldStep; // Set min value to max value - step
            $(event.target).closest('.filter-min-field').val(minVal); // Update min input value
        }

        let minPercent = ((minVal - minValue) / (maxValue - minValue)) * 100;
        let maxPercent = ((maxVal - minValue) / (maxValue - minValue)) * 100;

        // Set position of highlighted area
        $(event.target).closest('.range-container').find('.range-highlight').css({
            'left': minPercent + '%',
            'width': (maxPercent - minPercent) + '%'
        });

        $(event.target).closest('.range-container').find('.range-value').val(minVal + ',' + maxVal);
    });
    $(document).on('mouseup', '.range-filters', function (data) {
        if ($(data.target).data('filter') == 'mileage') {
            mileageApplied = true;
            priceApplied = false;
        } else {
            priceApplied = true;
            mileageApplied = false;
        }
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        ajaxFilters(data)
    });

    $(document).on('change', '.dropdown-filters', function (e) {
        let filterType = $(e.target).attr('data-type')
        let filterValue = $(e.target).val()
        appliedFilters[filterType] = true;
        // Set all other filter types to false
        for (var key in appliedFilters) {
            if (key !== filterType) {
                appliedFilters[key] = false;
            }
        }

        $(e.target).hasClass('main-sort-filter') ?
            $('.secondary-sort-filter').val($(e.target).val()) :
            $('.main-sort-filter').val($(e.target).val())

        clickedShowMore = false;
        isScroll = false;
        searchSortApplied = true;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = false;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        /** Ajax call to update inventory and filters */
        ajaxFilters(e)
    })

    // boolean flag to keep track of event call
    var isScroll = false;
    var clickedShowMore = false;

    $(document).on('click', '.page-numbers', function (e) {
        e.preventDefault();
        let x = $(this).attr('data-page');
        clickedShowMore = false;
        isScroll = false;
        searchSortApplied = false;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = false;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        ajaxFilters(e, undefined, x)
    })
    $('.search-filter-icon').click(function (e) {
        ($(e.target).hasClass('main-search-icon') ? $('.secondary-search-filters').val($('.main-search-filters').val()) : $('.main-search-filters').val($('.secondary-search-filters').val()))
        clickedShowMore = false;
        isScroll = false;
        searchSortApplied = true;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = false;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
        ajaxFilters(e, undefined, undefined)
    })

    // Vehicle Type filter
    $('.vehicle-condition-filter').change(function (e) {
        clickedShowMore = false;
        isScroll = false;
        searchSortApplied = false;
        priceApplied = false;
        mileageApplied = false;
        vConditionApplied = true;
		checkPriceMileageFilterStatus();
		sessionStorage.setItem('inline_banner_index', 0)
		/** Reset Filters */
		$('.checkbox-filters').each(function() {
			if( $(this).attr('data-type') !== 'make' ) {
				$(this).prop('checked', false)
			}
		})
		
		$('.dropdown-filters').each(function() {
			$(this).val('')
		})
		
		$('.search-filters').val('')
		$('.range-value').val('')
		
        ajaxFilters(e, undefined, undefined)
    })

    let windowResizeScale = false;
    let windowResizeDown = false;
    let initialWidth = window.innerWidth; // Store initial width

    $(window).on("resize", function (e) {
        let currentWidth = window.innerWidth;

        if (currentWidth === initialWidth) return; // Prevent running on page load

        if (currentWidth >= 1800 && !windowResizeScale) {
            clickedShowMore = false;
            isScroll = false;
            searchSortApplied = false;
            priceApplied = false;
            mileageApplied = false;
            vConditionApplied = false;
            windowResizeScale = true;
            windowResizeDown = false;
			checkPriceMileageFilterStatus();
			sessionStorage.setItem('inline_banner_index', 0)
            ajaxFilters(e, 12, undefined);
        } else if (currentWidth <= 1799 && !windowResizeDown) {
            clickedShowMore = false;
            isScroll = false;
            searchSortApplied = false;
            priceApplied = false;
            mileageApplied = false;
            vConditionApplied = false;
            windowResizeDown = true;
            windowResizeScale = false;
			checkPriceMileageFilterStatus();
			sessionStorage.setItem('inline_banner_index', 0)
            ajaxFilters(e, 12, undefined);
        } else if (currentWidth >= 990 && !windowResizeScale) {
            clickedShowMore = false;
            isScroll = false;
            searchSortApplied = false;
            priceApplied = false;
            mileageApplied = false;
            vConditionApplied = false;
            windowResizeDown = true;
            windowResizeScale = false;
			checkPriceMileageFilterStatus();
			sessionStorage.setItem('inline_banner_index', 0)
            ajaxFilters(e, 12, undefined);
        } else if (currentWidth <= 989 && !windowResizeDown) {
            clickedShowMore = false;
            isScroll = false;
            searchSortApplied = false;
            priceApplied = false;
            mileageApplied = false;
            vConditionApplied = false;
            windowResizeDown = true;
            windowResizeScale = false;
			checkPriceMileageFilterStatus();
			sessionStorage.setItem('inline_banner_index', 0)
            ajaxFilters(e, 12, undefined);
        }
    });

    if (window.location.pathname === '/used-vehicles-durango-colorado/' || window.location.pathname === '/new-vehicles-durango-colorado/' || window.location.pathname === '/kia/') {
        $(window).on('scroll', function () {
            let container = $('#vehicles-container');
            let containerHeight = container.height();
            let containerTop = container.offset().top;
            let windowHeight = $(window).height();
            let scrollTop = $(window).scrollTop();

            // Calculate the distance from the top of the container to the bottom of the viewport
            let distanceFromTopToBottomOfViewport = scrollTop + windowHeight - containerTop;

            // If the distance from the top of the container to the bottom of the viewport
            // is greater than or equal to the height of the container, and the function has
            // not already been called, call the ajaxFilters function with the appropriate arguments
            if (distanceFromTopToBottomOfViewport >= containerHeight && !window.ajaxFiltersCalled) {
                let currentPage = parseInt($('#vehicles-container').attr('data-current-page'));
                let maxPages = parseInt($('#vehicles-container').attr('data-max-pages'));

                if (clickedShowMore) {
                    isScroll = true;
                    if (currentPage !== maxPages) {
                        priceApplied = false;
                        mileageApplied = false;
                        if (currentPage && currentPage !== NaN) {
                            ajaxFilters(undefined, 6, currentPage + 1, 'true');
                        } else {
                            ajaxFilters(undefined, 6, 2, 'true');
                        }
                    } else {
                        // If user is at last page
                        $(document).find('span.all-pages').removeClass('d-md-inline-block')
                    }
                }
                window.ajaxFiltersCalled = true;
            }

            // If the distance from the top of the container to the bottom of the viewport
            // is less than the height of the container, reset the flag so the function can
            // be called again when the user scrolls back down to the end of the element
            if (distanceFromTopToBottomOfViewport < containerHeight) {
                window.ajaxFiltersCalled = false;
            }
        });
    }

    /** Optimized ajax filters function */

function ajaxFilters(e, posts_per_page = 12, paged = 1, scroll = 'false') {
        if (e) e.preventDefault();

        const $overlay = $('.inventory-products__overlay');
        const $filterTriggers = $('.inventory-filterbar__year, .dropdown-filters, .search-filters, .search-filter-icon, .filter-remove, .inventory-products-bar__clear-selected-filters-button, .vehicle-condition-filter');
        const $vehiclesContainer = $('#vehicles-container');
        const $noListingsBanner = $('.no-listings-banner');
        const $pagination = $('.vehicles_pagination');
        const $metaInfoText = $('.inventory-products-bar__meta-info-text');
        const $selectedFilters = $('.inventory-products-bar__selected-filters');
        const $selectedFiltersWrapper = $('.inventory-products-bar__selected-filters-wrapper');
        const appliedFilter = e ? $(e.target).attr('data-type') : null;

        const filterArrays = {
            year: collectFilterValues('.year-filter-input:checked'),
            make: collectFilterValues('.make-filter-input:checked'),
            model: collectFilterValues('.model-filter-input:checked'),
			trim: collectFilterValues('.series-filter-input:checked'),
            transmission: collectFilterValues('.transmission-filter-input:checked'),
            doors: collectFilterValues('.doors-filter-input:checked'),
            cylinders: collectFilterValues('.cylinders-filter-input:checked'),
            drivetrain: collectFilterValues('.drivetrain-filter-input:checked'),
            certified: collectFilterValues('.certified-filter-input:checked'),
            'fuel-type': collectFilterValues('.fuel-type-filter-input:checked'),
            'exterior-color': collectFilterValues('.exterior-color-filter-input:checked'),
            'interior-color': collectFilterValues('.interior-color-filter-input:checked'),
            features: collectFilterValues('.features-filter-input:checked'),
            engine: collectFilterValues('.engine-filter-input:checked'),
            'body-style': collectFilterValues('.body-style-filter-input:checked'),
            'type-of-vehicle': collectFilterValues('.type-of-vehicle-filter-input:checked')
        };

        const urlParams = new URLSearchParams(window.location.search);
        handleUrlParameters(urlParams, filterArrays);

//         const trimValue = $('select.dropdown-filters[data-type="series"]').val();
        let pricerange = $('.range-value-price-value').val()?.trim() || '';
        let mileageRange = $('.range-value-mileage-value').val()?.trim() || '';

        if (typeof priceApplied !== 'undefined' && !priceApplied && !urlParams.has('price[]')) {
            pricerange = '';
        }

        if (typeof mileageApplied !== 'undefined' && !mileageApplied && !urlParams.has('mileage')) {
            mileageRange = '';
        }

        const data = {
            ...filterArrays,
            search: $('.main-search-filters').val() || '',
            sort: $('.main-sort-filter').val() || '',
            vehicleCondition: $('.vehicle-condition-filter:checked').val() || '',
            posts_per_page,
            paged,
            scroll,
            appliedFilter,
            path: window.location.pathname,
            banner_index: parseInt(sessionStorage.getItem('inline_banner_index')) || 0,
			windowWidth: window.innerWidth,
            action: 'Get_Ajax_Filters',
        };

//         if (trimValue && trimValue !== 'Select a value') {
//             data.trim = trimValue;
//         }

        if (pricerange && pricerange !== '') {
            data.priceRange = pricerange;
        }

        if (mileageRange && mileageRange !== '') {
            data.mileageRange = mileageRange;
        }

        toggleLoadingState(true);

        sendAjaxRequest(data, handleSuccessResponse, handleErrorResponse);
    }

    function collectFilterValues(selector) {
        let values = [];
        $(selector).each(function () {
            const value = $(this).val();
            if (value && !values.includes(value)) {
                values.push(value);
            }
        });
        return values;
    }

    function handleUrlParameters(urlParams, filterArrays) {
        for (const [key, value] of urlParams) {
            const lowercaseKey = key.toLowerCase();
            if (filterArrays[lowercaseKey] && !filterArrays[lowercaseKey].includes(value)) {
                filterArrays[lowercaseKey].push(value);
            }
        }
    }

    function sendAjaxRequest(data, successCallback, errorCallback) {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: ajax_object.ajax_url,
            data: data,
            success: successCallback.bind(null, data),
            error: errorCallback,
        });
    }

    function handleSuccessResponse(boundData, response) {
        try {
            const obj = $.parseJSON(response);
            if (!obj) {
                throw new Error('Invalid response from server');
            }
			
			console.log('ajax response', obj)

            // Update URL
            updateUrlWithFilters(obj.urlQuery);

            // Update DOM elements
            updateDomElements(obj);

            // Apply smart filters
            applySmartFilters(obj.filterHTML, boundData);

            // Hide loading state
            toggleLoadingState(false);
			
			if( obj.banner_index ) {
				sessionStorage.setItem( 'inline_banner_index', JSON.stringify(obj.banner_index) )
			}
        } catch (error) {
            console.error('Error processing response:', error);
            handleErrorResponse();
			sessionStorage.removeItem('inline_banner_index')
        }
    }

    function updateDomElements(obj) {
        const $vehiclesContainer = $('#vehicles-container');
        const $noListingsBanner = $('.no-listings-banner');
        const $pagination = $('.vehicles_pagination');
        const $metaInfoText = $('.inventory-products-bar__meta-info-text');
        const $selectedFiltersWrapper = $('.inventory-products-bar__selected-filters-wrapper');

		if (Array.isArray(obj.listingContent)) {
			let htmlContent = obj.listingContent.join(""); // Combine array elements into a string
			if('false' === obj.scroll) {
			$vehiclesContainer.html(htmlContent);	
			} else if( 'true' === obj.scroll ) {
				$vehiclesContainer.append(htmlContent);
			}
			
			if ($('.inventory-products-bar__layout-list').hasClass('active')) {
				durango_toggle_inventory_view(undefined, 'list')
			} else if ($('.inventory-products-bar__layout-grid').hasClass('active')) {
				durango_toggle_inventory_view(undefined, 'grid')
			}
			
			setTimeout(() => {
				initializeSlickSlider();
			durango_hide_slider_dot_pagination();
			lazyLoadImages();
			}, 100)
		}
		
		if (obj.maxPages !== null && obj.maxPages !== '') {
			$('#vehicles-container').attr('data-max-pages', obj.maxPages)
		}
		if (obj.currentPage !== null && obj.currentPage !== '') {
			$('#vehicles-container').attr('data-current-page', obj.currentPage)
		} else if (obj.args.paged !== null && obj.args.paged !== '') {
			$('#vehicles-container').attr('data-current-page', obj.args.paged)
		}
		
        if (obj.noListingsBanner) {
			$noListingsBanner.html(obj.noListingsBanner)
            $noListingsBanner.show();
        } else {
			$noListingsBanner.html('')
            $noListingsBanner.hide();
        }

        if (obj.paginationHtml) {
            $pagination.html(obj.paginationHtml);
        }

        if (obj.foundposts) {
            $metaInfoText.text(obj.foundposts + ' ' + 'Vehicles Matching');
        }

        // Keep "Viewing X - Y of Z" accurate when "Show All" appends results.
        if (obj.scroll === 'true') {
            const totalVehicles = parseInt(obj.foundposts, 10) || 0;
            const loadedVehicles = $('#vehicles-container .listing-card-wrapper')
                .not('.inline-banner')
                .length;
            const viewingEnd = totalVehicles > 0 ? Math.min(loadedVehicles, totalVehicles) : loadedVehicles;
            $(document).find('.postCounts').text(`Viewing 1 - ${viewingEnd} of ${totalVehicles}`);
        }

        if (obj.filter) {
            $selectedFiltersWrapper.html(obj.filter);
			if($selectedFiltersWrapper.children().length > 0) {
				$selectedFiltersWrapper.addClass('d-flex').removeClass('d-none');
			} else {
				$selectedFiltersWrapper.addClass('d-none').removeClass('d-flex');
			}
        }
		
		/** Scroll to top */
		if( !clickedShowMore ) {
			$('html, body').animate({
				scrollTop: $vehiclesContainer.offset().top - 100
			}, 'smooth');	
		}
		
		/** Initialize bootstrap tooltip */
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		})
    }

    function handleErrorResponse() {
        alert('Sorry, something went wrong. Please try again.');
        toggleLoadingState(false);
    }

    function updateUrlWithFilters(urlQuery) {
        // urlQuery is an object of array
        let url = window.location.href.toLowerCase();
        const urlObj = new URL(url);
        const searchParams = new URLSearchParams(urlObj.search);

        // Clear existing parameters to avoid duplicates
        searchParams.forEach((value, key) => {
            searchParams.delete(key);
        });

        for (const [key, value] of Object.entries(urlQuery)) {
            if (key === 'path' || key === 'scroll' || key === 'vehicleCondition' || key === 'applied_filter') continue;

            if (value && value.length > 0) {
                const encodedKey = encodeURIComponent(key);
                if (['search', 'sort', 'mileage', 'final_price', 'vehicleCondition', 'scroll', 'path', 'searchbar', 'sortBy'].includes(key)) {
                    const encodedValue = encodeURIComponent(value).toLowerCase();
                    searchParams.set(encodedKey, encodedValue);
                } else {
                    const values = Array.isArray(value) ? value : [value];
                    values.forEach(val => {
                        if (val) {
                            const encodedValue = encodeURIComponent(val).toLowerCase();
                            searchParams.append(`${encodedKey}[]`, encodedValue);
                        }
                    });
                }
            }
        }
        urlObj.search = searchParams.toString();
        window.history.replaceState({}, '', urlObj.toString());
    }

    function updateUrlParameter(url, key, value) {
        const regex = new RegExp(`(${key}=)[^&]+`, 'i');
        if (url.includes(key)) {
            return url.replace(regex, `$1${value}`);
        }
        return `${url}${url.includes('?') ? '&' : '?'}${key}=${value}`;
    }

    function applySmartFilters(filterHTML, appliedFilters) {
        if (!filterHTML) {
            console.error("filterHTML is missing or undefined");
            return;
        }

		const filterMap = {
			year: '.inventory-filterbar__year-search-wrapper',
			make: '.inventory-filterbar__make-search-wrapper',
			model: '.inventory-filterbar__model-search-wrapper',
			trim: '.inventory-filterbar__trim-search-wrapper',
			'body-style': '.inventory-filterbar__body-style-search-wrapper',
			'type-of-vehicle': '.inventory-filterbar__type-of-vehicle-search-wrapper',
			doors: '.inventory-filterbar__doors-search-wrapper',
			cylinders: '.inventory-filterbar__cylinders-search-wrapper',
			drivetrain: '.inventory-filterbar__drivetrain-search-wrapper',
			transmission: '.inventory-filterbar__transmission-search-wrapper',
			certified: '.inventory-filterbar__certified-search-wrapper',
			'fuel-type': '.inventory-filterbar__fuel-type-search-wrapper',
			'exterior-color': '.inventory-filterbar__exteriorColor-search-wrapper',
			'interior-color': '.inventory-filterbar__interior_color-search-wrapper',
			'miscprice-1': '.inventory-filterbar__price',
			'original_price': '.inventory-filterbar__price',
			'odometer': '.inventory-filterbar__mileage',
			engine: '.inventory-filterbar__engine-search-wrapper'
		};

		const currentAppliedKey = Object.keys(appliedFilters).find(k => appliedFilters[k] === true);
		
        Object.entries(filterMap).forEach(([key, selector]) => {
			if( key === 'trim' ) {
				key = 'series';
			}
			
			let tempKey = key;
			if (tempKey === 'miscprice-1' || tempKey === 'original_price') {
				tempKey = 'price';
			}
			
			if (tempKey === appliedFilters['appliedFilter']) return;
			
            if (filterHTML[key]) {				
				const appliedValues = appliedFilters[key] || [];
								
				$(selector).html(generateFilterHTML(key, filterHTML[key], appliedValues));
            } else {
                console.warn(`Missing filter data for ${key}`);
            }
        });
    }

    function toggleLoadingState(isLoading) {
        const opacity = isLoading ? 0.7 : 1;
        const pointerEvents = isLoading ? 'none' : 'auto';
        const $overlay = $('.inventory-products__overlay');
        const $filterTriggers = $('.inventory-filterbar__year, .dropdown-filters, .search-filters, .search-filter-icon, .filter-remove, .inventory-products-bar__clear-selected-filters-button, .vehicle-condition-filter');
        $overlay.css('display', isLoading ? 'block' : 'none');
        $filterTriggers.css({ opacity, pointerEvents });
    }

	function generateFilterHTML(key, filterOptions, appliedValues = []) {
		if (!filterOptions || !Array.isArray(filterOptions)) return '';
		
		// Ensure appliedArray is an array and exclude string values
		if (!Array.isArray(appliedValues) && key !== 'model' && key !== 'series') {
			console.warn(`appliedValues for ${key} is not an array:`, appliedValues);
			appliedValues = []; // Default to an empty array
		}
		
		 // Handle dropdown filters (model and trim)
// 		if (key === 'trim' || key === 'series') {
// 			let html = `<select class="form-controls w-100 p-3 rounded border font-weight-bold dropdown-filters text-capitalize" data-type="${key}" name="${key}">`;
			
// 			// Add a default "Select" option
// 			html += `<option value="Select a value">Select a value</option>`;
			
// 			// Add options
// 			filterOptions.forEach(option => {
// 				const lowerOption = option.toLowerCase().trim();
// 				if (lowerOption === '') return;
// 				// Ensure appliedValues is always a string
// 				const lowerAppliedValue = typeof appliedValues === 'string' ? appliedValues.toLowerCase().trim() : '';
// 				const isSelected = lowerAppliedValue === lowerOption ? 'selected' : '';
// 				html += `<option class="text-capitalize ${key} ${option}" value="${option}" ${isSelected}>${option}</option>`;
// 			});
			
// 			// Close the select tag
// 			html += `</select>`;
// 			return html;
// 		}
				
		// Handle color filters (exterior, interior)
		if (key === 'exterior-color' || key === 'interior-color') {
			return filterOptions.map(option => {
				const optionLower = option.color.toLowerCase();
				const isChecked = appliedValues.includes(optionLower) ? 'checked' : '';
				const background = option.keycode;

				if( optionLower === '' ) return;
				
				return `
<div class="inventory-filterbar__${key} ${key} inventory-filterbar__year col-4 col-lg-3">
<input type="checkbox" class="d-none checkbox-filters ${key}-filter-input" data-type="${key}" name="${key}[]" id="inventory-filter-${key}_${optionLower}" value="${optionLower}" ${isChecked}>
<label for="inventory-filter-${key}_${optionLower}">
<span class="d-inline-block color-filter-pills rounded-circle-px cursor-pointer" data-color="${optionLower}" data-color-code="${background}" data-toggle="tooltip" data-placement="top" title="${optionLower}" style="background: #${background};"></span>
</label>
</div>
`;
			}).join('');
		}
		
		// Handle range filters (Price, Mileage)
		if (key === 'miscprice-1' || key === 'original_price' || key === 'odometer') {
			const minValue = Math.min(...filterOptions);
			const maxValue = Math.max(...filterOptions);
			const stepValue = Math.ceil((maxValue - minValue) / 10); // Adjust step size as needed
			
			if( key === 'miscprice-1' || key === 'original_price' ) {
				$('.price-min-text').html('$ ' + minValue.toLocaleString())
				$('.price-max-text').html('$ ' + maxValue.toLocaleString())
				key = 'price';
			} else if( key === 'odometer' ) {
				$('.mileage-min-text').html('$ ' + minValue.toLocaleString())
				$('.mileage-max-text').html('$' + maxValue.toLocaleString())
				key = 'mileage';
			}
			
			return `
<div class="${minValue} ${maxValue} range-container position-relative">
<div class="range-slider position-absolute w-100">
<div class="range-highlight highlighted-area"></div>
</div>
<input type="range" name="${key}-filter" id="${key}-min" class="range-filters filter-min-field position-absolute w-100 m-0 p-0 bg-transparent h-auto" min="${minValue}" value="${minValue}" max="${maxValue}" step="${stepValue}" data-filter="${key}" data-type="${key}">
<input type="range" name="${key}" id="${key}-max" class="range-filters position-absolute w-100 m-0 p-0 bg-transparent h-auto" min="${minValue}" value="${maxValue}" max="${maxValue}" step="${stepValue}" data-filter="${key}" data-type="${key}">
<input type="text" name="range-value" class="range-value range-value-${key}-value d-none" data-include="false" value="${minValue+','+maxValue}">
</div>
`;
		}

		return filterOptions.map(option => {
			option = option.toLowerCase();
			if( option === '' ) return;
			// Check if the current option is in the applied filters
			const isChecked = appliedValues.includes(option) ? 'checked' : ''; 

			return `
<div class="${key} ${option} inventory-filterbar__${key}-search-wrapper inventory-filterbar__year">
<input type="checkbox" class="checkbox-filters ${key}-filter-input" data-type="${key}"
name="listing_${key}[]" id="inventory-filter-${key}-checkbox_${option}" value="${option}" ${isChecked} />
<label for="inventory-filter-${key}-checkbox_${option}" class="inventory-filterbar">${option}</label>
</div>
`;
		}).join('');
	}

    $(document).on('click', '.filter-remove', function (e) {
        e.preventDefault();
        let type = $(e.target).data('type');
        let val = (typeof $(e.target).data('val') === 'number' ?
            $(e.target).data('val') :
            $(e.target).data('val').toLowerCase());
        let urlQuery = window.location.href.toLowerCase();
        let x = type.toLowerCase() + encodeURIComponent('[]').toLowerCase() + '=' + (typeof val === 'number' ? encodeURIComponent(val) : encodeURIComponent(val.toLowerCase()).toLowerCase());
        // Logic if user is viewing the CPO listings and tried to remove the make or certified filter
        if (type === 'make' || type === "certified") {
            let certifiedArray = ['certified-pre-owned-ford',
                'certified-pre-owned-toyota',
                'certified-pre-owned-kia',
                'certified-pre-owned'];

            $.each(certifiedArray, function (index, data) {
                if (urlQuery.includes(data)) {
                    urlQuery = urlQuery.replace(data, '');
                }
            })
        }

        // Remove parameter from URL
        if (type !== 'certified-pre-owned-ford' &&
            type !== 'certified-pre-owned-kia' &&
            type !== 'certified-pre-owned-toyota' &&
            type !== 'certified-pre-owned' && type !== 'certification') {
            if (urlQuery.includes(x)) {
                urlQuery = urlQuery.replace(x, '');
                urlQuery = urlQuery.replace(/[&?]+$/, '');
            }
        }

        // Logic for certified pre owned filters
        if (type == 'certified-pre-owned-ford' ||
            type == 'certified-pre-owned-kia' ||
            type == 'certified-pre-owned-toyota' ||
            (val == 'yes' && type == 'certified-pre-owned') ||
            type === 'certification') {
            let certifiedArr = ['certified-pre-owned-toyota', 'certified-pre-owned-ford', 'certified-pre-owned-kia', 'certified-pre-owned'];
            $.each(certifiedArr, function (index, data) {
                if (urlQuery.includes(data)) {
                    urlQuery = urlQuery.replace(data, '');
                    return false;
                }
            })
        }

        // Update the URL only if it has changed
        if (window.location.search !== urlQuery) {
            window.history.replaceState({}, '', urlQuery);
        }

        // Make the key to false in appliedFilter object
        appliedFilters[type] = false;

        // Remove checked values if theres a match
        $('.checkbox-filters').map(function (index, data) {
            if ($(data).val() == val) {
                $(data).prop('checked', false)
            }
        })
        $('.dropdown-filters').map(function (index, data) {
            let dropdownValue = $(data).val();
			if (dropdownValue && dropdownValue.toLowerCase() == val) {
				$(data).val('');
			}
        })
        $('.search-filters').map(function (index, data) {
            if ($(data).val().toLowerCase() == val) {
                $(data).val('')
            }
        })
        $('.range-filters').map(function (index, data) {
            let min = $(data).attr('min')
            let max = $(data).attr('max')
            let step = $(data).attr('step');
          
            if ($(data).attr('data-filter').toLowerCase().includes(type.toLowerCase())) {
                $(data).attr({
                    'min': min,
                    'max': max,
                    'step': step,
                })
                if ($(data).hasClass('filter-min-field')) {
                    $(data).val(min)
                } else {
                    $(data).val(max)
                }
                let minPercent = ((min - min) / (max - min)) * 100;
                let maxPercent = ((max - min) / (max - min)) * 100;

                // Set position of highlighted area
                $(data).closest('.range-container').find('.range-highlight').css({
                    'left': minPercent + '%',
                    'width': (maxPercent - minPercent) + '%'
                });
                $(data).closest('.range-container').find('.range-value').val('')
                $(`.range-value-${type}-value`).val('')
                $(`.${type}-min-text`).html(min);
                $(`.${type}-max-text`).html(max);
            }
        })

        searchSortApplied = false;
        vConditionApplied = false;
        ajaxFilters(e)
    });

    $(document).on('click', '.filter-remove', function (e) {
        e.preventDefault();
        let type = $(e.target).data('type');
        let val = $(e.target).data('val').split(',').map(Number);
        if (type !== 'price' && type !== 'mileage') {
            return;
        }

        let urlQuery = window.location.href;
        const urlObj = new URL(urlQuery);
        const searchParams = new URLSearchParams(urlObj.search);

        if (type === 'price') {
            searchParams.delete('price[]');
        } else if (type === 'mileage') {
            searchParams.delete('mileage');
        }

        urlObj.search = searchParams.toString();
        if (window.location.search !== urlObj.search) {
            window.history.replaceState({}, '', urlObj.toString());
        }
        $('.range-filters').map(function (index, data) {
            let min = parseFloat($(data).attr('min'));
            let max = parseFloat($(data).attr('max'));
            let step = parseFloat($(data).attr('step'));

            if ($(data).attr('data-filter').toLowerCase().includes(type.toLowerCase())) {
                $(data).attr({
                    'min': min,
                    'max': max,
                    'step': step,
                });
                if ($(data).hasClass('filter-min-field')) {
                    $(data).val(min);
                } else {
                    $(data).val(max);
                }
                let minPercent = ((min - min) / (max - min)) * 100;
                let maxPercent = ((max - min) / (max - min)) * 100;
                $(data).closest('.range-container').find('.range-highlight').css({
                    'left': minPercent + '%',
                    'width': (maxPercent - minPercent) + '%'
                });

                $(data).closest('.range-container').find('.range-value').val(min + ',' + max);
                $(`.range-value-${type}-value`).val('');
                if (type === 'price') {
                    $('.price-min-text').text('$ ' + min.toLocaleString());
                    $('.price-max-text').text('$ ' + max.toLocaleString());
                } else if (type === 'mileage') {
                    $('.mileage-min-text').text(min.toLocaleString());
                    $('.mileage-max-text').text(max.toLocaleString());
                }
            }
        });
        appliedFilters[type] = false;
        ajaxFilters(e);
    });


    $('.inventory-products-bar__clear-selected-filters-button').click(function (e) {
        // Get the current URL
        let url = window.location.href;

        // Check if URL contains query parameters
        if (url.indexOf('?') !== -1) {
            // Remove query parameters using regex
            url = url.replace(/(\?|\&).+$/, '');
        }

        // Use replaceState to update the URL
        window.history.replaceState({}, document.title, url);
        $('.checkbox-filters:checked').map(function (index, data) {
            $(data).prop('checked', false)
        })
        $('.dropdown-filters').map(function (index, data) {
            if ($(data).val()) {
                $(data).val('').change();
            }
        })
        $('.search-filters').map(function (index, data) {
            if ($(data).val()) {
                $(data).val('')
            }
        })
        $('.range-filters').map(function (index, data) {
            let min = $(data).attr('min')
            let max = $(data).attr('max')
            let step = $(data).attr('step');
            let type = $(data).data('filter');
            $(data).attr({
                'min': min,
                'max': max,
                'step': step,
            })
            if ($(data).hasClass('filter-min-field')) {
                $(data).val(min)
            } else {
                $(data).val(max)
            }
            let minPercent = ((min - min) / (max - min)) * 100;
            let maxPercent = ((max - min) / (max - min)) * 100;

            // Set position of highlighted area
            $(data).closest('.range-container').find('.range-highlight').css({
                'left': minPercent + '%',
                'width': (maxPercent - minPercent) + '%'
            });
            $(data).closest('.range-container').find('.range-value').val('')
            $('.range-value-mileage-value').val('');
            if (type == 'mileage-filter') {
                $('.mileage-min-text').html(min)
                $('.mileage-max-text').html(max)
            } else if (type == 'price-filter') {
                $('.final_price-min-text').html(min)
                $('.final_price-max-text').html(max)
            }
        })

        $('.inventory-products-bar__selected-filters').children().remove()
        selectedState = {};
        searchSortApplied = false
        vConditionApplied = false
        ajaxFilters(e, undefined, undefined)
    })

    if ($('.inventory-products-bar__selected-filters').children().length > 0) {
        $('.inventory-products-bar__selected-filters-wrapper').removeClass('d-none').addClass('d-flex')
    }

    // Load inventory vehicles on page load
    // Load inventory vehicles on page load
    function dmcLoadInventoryVehicles() {
        const $overlay = $('.inventory-products__overlay');
        const $selectedFilters = $('.inventory-products-bar__selected-filters');
        const $selectedFiltersWrapper = $('.inventory-products-bar__selected-filters-wrapper');
        const $vehiclesContainer = $('#vehicles-container');
        const $pagination = $('.vehicles_pagination');
        const $metaInfoText = $('.inventory-products-bar__meta-info-text');
        const $noListingsBanner = $('.no-listings-banner');
		sessionStorage.setItem('inline_banner_index', 0)

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            timeout: 15000,
            dataType: 'json',
            beforeSend: function () {
                $overlay.show();
            },
            data: {
                url: window.location.search.substring(1),
                paged: '1',
                path: window.location.pathname,
                windowWidth: window.innerWidth,
                action: 'loadInventoryVehicles'
            },
            success: function (result) {
                if (!result) {
                    alert('Something went wrong with the data. Please refresh the page.');
                    return;
                }
				
				console.log('load inventory vehicles', result)

                updateSelectedFilters(result.filterHTML);
                updateCompareBoxVisibility(result.comparedVehiclesCount);
                updatePaginationAndProductCards(result.response);
                updateFilters(result.filters);
				toggleLoadingState(false);
				
				// Populate search and sort values from URL (Kia page: no params, keep empty)
				if (window.location.pathname !== '/kia/') {
					let queryURL = new URL(window.location.href);
					let searchParam = queryURL.searchParams.get("search");
					let sortParam 	= queryURL.searchParams.get('sort');

					if (searchParam) {
						$('.inventory-search-filters').val(searchParam)
					}
					
					if( sortParam ) {
						$('.dropdown-filters[data-type="sort-by"]').val(sortParam)
					}
				}

            },
            error: function () {
                alert('Something went wrong. Reloading the page.');
//                 window.location.reload();
            }
        });
    }

    dmcLoadInventoryVehicles();

    function updateSelectedFilters(filterHTML) {
        const $selectedFilters = $('.inventory-products-bar__selected-filters');
        const $selectedFiltersWrapper = $('.inventory-products-bar__selected-filters-wrapper');

        $selectedFilters.html(filterHTML);
        $selectedFiltersWrapper.toggleClass('d-flex', $selectedFilters.children().length > 0)
            .toggleClass('d-none', $selectedFilters.children().length === 0);
    }

    function updateCompareBoxVisibility(comparedVehiclesCount) {
        $('body').toggleClass('hideCompareCheckbox', comparedVehiclesCount >= 3);
    }

    function updatePaginationAndProductCards(response) {
        const $vehiclesContainer = $('#vehicles-container');
        const $pagination = $('.vehicles_pagination');
        const $metaInfoText = $('.inventory-products-bar__meta-info-text');
        const $overlay = $('.inventory-products__overlay');
		let $noListingsBanner = $('.no-listings-banner');
		
		if( response.noListingsBanner !== '' ) {
			$noListingsBanner.html(response.noListingsBanner)
			$noListingsBanner.show()
		} else {
			$noListingsBanner.html('')
			$noListingsBanner.hide()
		}

        if (response.maxPages) {
            const initialPage = parseInt(response.args?.paged, 10) || 1;
            $vehiclesContainer.attr({ 'data-max-pages': response.maxPages, 'data-current-page': initialPage });
        }
        if (response.productCards) {
            $vehiclesContainer.html(response.productCards);
            initializeSlickSlider();
            durango_hide_slider_dot_pagination();
            lazyLoadImages();
            $overlay.hide();
        }
        if (response.pagination) {
            $pagination.html(response.pagination);
        }
        if (response.postCount) {
            $metaInfoText.text(`${response.postCount} Vehicles Matching`);
        }
    }

    function initializeSlickSlider() {
        $('.listing-image-slider-inner').slick({
            arrows: true,
            infinite: false,
            dots: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: "<button type='button' class='slick-prev pull-left inventory-slick-arrow'><i class='fa-solid fa-chevron-left'></i></button>",
            nextArrow: "<button type='button' class='slick-next pull-right inventory-slick-arrow'><i class='fa-solid fa-chevron-right'></i></button>",
        });
    }

    function updateFilters(filters) {
        if (!filters) return;

        Object.entries(filters).forEach(([key, data]) => {
			if( data !== null ) {
			$(`.fake-${key}`).html(data.checkboxFilter || data.color_filter || data);
				if (data.color_filter) {
					$(`.fake-${key}`).find('.color-filter-pills').each(function () {
						$(this).css('background', `#${$(this).data('color-code')}`);
					});
					$('[data-toggle="tooltip"]').tooltip()
				}	
			}
        });
		
		// Update range filters text labels
		let priceMin = parseFloat($('#price-min').attr('min')); // Convert to number
		let priceMax = parseFloat($('#price-min').attr('max')); // Convert to number
		let priceVal = parseFloat($('#price-min').attr('value')); // Convert to number
		let mileageMin = parseFloat($('#mileage-min').attr('min'));
		let mileageMax = parseFloat($('#mileage-max').attr('max'));
		let mileageVal = parseFloat($('#mileage-min').attr('value'));
		
		if (isNaN(priceMin)) priceMin = 0;
		if (isNaN(priceMax)) priceMax = priceMin;
		if( isNaN(mileageMin) ) mileageMin = 0;
		if( isNaN(mileageMax) ) mileageMax = mileageMin;

		if (!isNaN(priceMin)) $('.price-min-text').text('$' + ' ' + priceMin.toLocaleString());
		if (!isNaN(priceMax)) $('.price-max-text').text('$' + ' ' + priceMax.toLocaleString());
		if(!isNaN(mileageMin)) $('.mileage-min-text').text(mileageMin.toLocaleString());
		if( ! isNaN(mileageMax) ) $('.mileage-max-text').text(mileageMax.toLocaleString());
		
		// Update the range value input
		$('.range-value-price-value').val(priceMin + ',' + priceMax);
		$('.range-value-mileage-value').val(mileageMin + ',' + mileageMax)
		
    }

    // Slick slider event to show and hide the next and prev 2 dots of slick dots
    $(document).on('init', '.listing-image-slider-inner', function (event, slick) {
        let slickDots = $(event.target).find('.slick-dots li');
        slickDots.addClass('d-none');

        // Calculate the range of dots to display, ensuring that there are 5 dots
        let start = Math.max(0, slick.currentSlide - 2);
        let end = Math.min(slickDots.length - 1, start + 4); // Display 5 dots, so end = start + 4

        slickDots.slice(start, end + 1).removeClass('d-none');
    });

    $(document).on('beforeChange', '.listing-image-slider-inner', function (event, slick, currentSlide, nextSlide) {
        let slickDots = $(event.target).find('.slick-dots li');
        slickDots.addClass('d-none');

        let totalDots = slickDots.length;
        let start, end;

        // Calculate the range of dots to display, centered around the next slide
        if (nextSlide <= 2) {
            // If nextSlide is within the first three slides, display 5 dots starting from the first dot
            start = 0;
            end = Math.min(4, totalDots - 1);
        } else if (nextSlide >= totalDots - 3) {
            // If nextSlide is within the last three slides, display 5 dots ending at the last dot
            start = Math.max(0, totalDots - 5);
            end = totalDots - 1;
        } else {
            // For other cases, display 5 dots centered around the next slide
            start = nextSlide - 2;
            end = nextSlide + 2;
        }

        slickDots.slice(start, end + 1).removeClass('d-none');
    });

    /** On vehicle card slide change */
    $(document).on('beforeChange', '.listing-image-slider-inner', function (event, slick, currentSlide, nextSlide) {
        if (nextSlide === slick.slideCount - 1) {
            let $lastSlide = $(slick.$slides.eq(nextSlide));
            let permalink = $(this).closest('.listing-card-wrapper').attr('data-permalink');
            if (permalink && !$lastSlide.find('.listing-image-slider-overlay').length) {
                let slideOverlay = `<div class="listing-image-slider-overlay">
			<h3 class="text-uppercase mb-3 text-white text-center font-lg font-weight-bold font-segoe">
			Want to see <br /> more photos?</h3>
			<a href="${permalink}" class="text-uppercase bg-white font-weight-bold">Click Here</a>
			</div>`;
                $(slick.$slides.eq(nextSlide)).append(slideOverlay);
            }
        }
    });

    // Hide slick dots in image slider if it only have 1 image
    function durango_hide_slider_dot_pagination() {
        let slickDots = $(document).find('.listing-image-slider-inner .slick-dots')
        $(slickDots).each((index, data) => {
            let length = $(data).find('li').length;
            if (length <= 1) {
                $(data).attr('style', 'display:none !important')
            }
        })
    }

	/** Lazy Load Images */
	function loadImage(img) {
		let $img = $(img);
		let preloader = $('<div class="preloader d-flex align-items-center justify-content-center position-absolute top-0 left-0 w-100 h-100 bg-light">' +
						  '<div class="spinner-border text-primary" role="status">' +
						  '<span class="sr-only">Loading...</span></div></div>');
		$img.before(preloader);

		$('<img>').attr('src', $img.data('src')).on('load', function () {
			$img.attr('src', $img.data('src')).fadeIn();
			preloader.remove();
			$img.removeAttr('data-src'); // Remove data-src to prevent re-checking
		});
	}

	function checkImages() {
		let images = $('img[data-src]');
		images.each(function () {
			let img = $(this);
			if (img.is(':visible') && img[0].getBoundingClientRect().top < window.innerHeight) {
				loadImage(img[0]);
			}
		});
	}

	function lazyLoadImages() {
		checkImages(); // Initial check on page load
	}

	$(window).on('scroll resize', checkImages);
	lazyLoadImages(); // Run on page load
	
    function lazyLoadImagess() {
        $('img[data-src]').each(function () {
            let img = $(this);
            let preloader = $('<div class="preloader d-flex align-items-center justify-content-center position-absolute top-0 left-0 w-100 h-100 bg-light">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="sr-only">Loading...</span></div></div>');
            img.before(preloader); // Insert preloader before the image

            $('<img>').attr('src', img.data('src')).on('load', function () {
                img.attr('src', img.data('src')).fadeIn(); // Set image source & fade in
                preloader.remove(); // Remove preloader
            });
        });
    }

})
