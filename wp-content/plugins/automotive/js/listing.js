var Listing;

function automotiveInitRecaptcha(element) {
  /**
   * @param {string} grecaptcha.focus_response_field
   */
  grecaptcha.render(element, {
    'sitekey': listing_ajax.recaptcha_public,
    'theme': 'red',
    'callback': grecaptcha.focus_response_field
  });
}


/**
 * @param {string} listing_ajax.ajaxurl
 * @param {string} listing_ajax.currency_separator
 * @param {string} listing_ajax.currency_symbol
 * @param {string} listing_ajax.current_url
 * @param {string} listing_ajax.google_maps_api
 * @param {boolean} listing_ajax.is_ssl
 * @param {int} listing_ajax.listing_id
 * @param {string} listing_ajax.pdf.primary_text
 * @param {string} listing_ajax.pdf.secondary_text
 * @param {string} listing_ajax.pdf.email_success
 * @param {string} listing_ajax.permalink_set
 * @param {string} listing_ajax.plural_vehicles
 * @param {string} listing_ajax.recaptcha_public
 * @param {string} listing_ajax.singular_vehicles
 */


// flipping cards
jQuery.initFlippingCards = function initFlippingCards() {
  var $ = jQuery;

  $('.card-container').each(function () {
    var autoHeightWidth = {
      width: 'auto',
      height: 'auto'
    };

    $(this).css(autoHeightWidth);
    $(this).find('.flipping-card .side').css(autoHeightWidth);
    $(this).find('.flipping-card img').css(autoHeightWidth);

    var imgWidth = $(this).find('.flipping-card .front img').width();
    var imgHeight = $(this).find('.flipping-card .front img').height();

    var heightWidthCSS = {
      width: imgWidth,
      height: imgHeight
    };

    $(this).css(heightWidthCSS);
    $(this).find('.flipping-card .side').css(heightWidthCSS);
    $(this).addClass('card-loaded');
  });
};

// animate progress bars
jQuery.initProgressBars = function initProgressBars() {
  var $ = jQuery;

  $('.progress-bar[data-width]').each(function () {
    $(this).css('width', $(this).data('width') + '%');
  });
};


// google map
jQuery.initGoogleMap = function initGoogleMap() {
  var $ = jQuery;

  $('.google_map_init').each(function () {
    var latitude = $(this).data('latitude');
    var longitude = $(this).data('longitude');
    var zoom = $(this).data('zoom');
    var scrollWheel = $(this).data('scroll');
    var style = $(this).data('style');
    var parallax = $(this).data('parallax');
    var scrolling = $(this).data('scrolling');
    var infoContent = $(this).data('info-content');
    var mapType = $(this).data('map-type') || 'roadmap';

    var directionsButton = $(this).data('directions_button');
    var directionsText = $(this).data('directions_text');

    if (typeof google !== 'undefined') {
      if (latitude && longitude && typeof google.maps !== 'undefined') {
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
          zoom: zoom,
          center: myLatlng,
          popup: true,
          mapTypeId: mapType
        };

        if (parallax !== false && typeof parallax === 'undefined') {
          myOptions.scroll = {
            x: $(window).scrollLeft(),
            y: $(window).scrollTop()
          }
        } else {
          var setCenter = true;
        }

        if (scrollWheel === false && typeof scrollWheel !== 'undefined') {
          myOptions.scrollwheel = false;
        }

        if (scrolling === false && typeof scrolling !== 'undefined') {
          myOptions.draggable = false;
        }

        if (typeof style !== 'undefined') {
          myOptions.styles = style;
        }

        var map = new google.maps.Map(this, myOptions);

        marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title: 'Our Location'
        });

        if (parallax !== false && typeof parallax === 'undefined') {
          var $map = $(this);

          google.maps.event.addListenerOnce(map, 'tilesloaded', function () {
            var offset = $map.offset();
            var heightOffset = (offset.top/3);

            map.panBy(0, -Math.abs(heightOffset));
          })

          window.addEventListener('scroll', function () {
            //google.maps.event.addDomListener(window, 'scroll', function () {
            var scrollY = $(window).scrollTop();
            var scrollX = $(window).scrollLeft();
            var scroll = map.get('scroll');

            if (scroll) {
              map.panBy(-((scroll.x - scrollX) / 3), -((scroll.y - scrollY) / 3));
            }

            map.set('scroll', {
              x: scrollX,
              y: scrollY
            });
          })
        }

        if (infoContent) {
          var contentString = decodeURIComponent(Base64.decode(infoContent));

          if (directionsButton === true) {
            contentString += "<br><br><a href='https://www.google.ca/maps/dir//" + latitude + ',' + longitude + '/@' + latitude + ',' + longitude + ",8z' target='_blank'><button>" + directionsText + '</button></a>';
          }

          var infowindow = new google.maps.InfoWindow({
            content: contentString
          });
        }

        google.maps.event.addListener(marker, 'click', function () {
          map.setZoom(zoom);
          infowindow.open(map, marker);
        });

        if (typeof setCenter !== 'undefined') {
          map.setCenter(myLatlng);
        }
      }
    } else {
      console.log('Google Maps Not Found');
    }
  });
};

// even out all comparison table rows for easier viewing
jQuery.automotiveComparisonRowHeights = function comparisonRowHeights() {
  var $ = jQuery;
  var $comparison = $('.comparison');

  if ($comparison.length) {
    // title block
    var sizes = [];

    // header area
    $('.comparison-container .comparison-header').each(function () {
      $(this).height('auto');
      sizes.push($(this).height());
    });

    $('.comparison-container .comparison-header').height(Math.max.apply(Math, sizes));

    // table rows
    var tables = $comparison.length;
    var rows = (parseInt($('.comparison:eq(0) tr').length) - 1);

    for (var i = 0; i < rows; i++) {
      sizes = [];

      for (var ii = 0; ii < tables; ii++) {
        var rowHeight = $comparison.eq(ii).find('tr').eq(i).height();

        sizes.push(rowHeight);
      }

      var biggestHeight = Math.max.apply(Math, sizes);

      $('table.comparison').each(function () {
        $(this).find('tr').eq(i).height('auto');
        $(this).find('tr').eq(i).height(biggestHeight);
      });
    }

    // comparison options
    sizes = [];

    $('.comparison-container .option-tick-list').each(function () {
      sizes.push($(this).height());
    });

    $('.comparison-container .option-tick-list').height(Math.max.apply(Math, sizes));
  }
};


// portfolio filter
jQuery.initPortfolio = function initPortfolio() {
  var $ = jQuery;
  var $portfolioContainer = $('.portfolioContainer');

  if ($portfolioContainer.length && typeof $.fn.mixItUp !== 'undefined') {
    var portfolioOptions = {load: {}};

    if (!$('.portfolioFilter li.active').length) {
      var sortBy = $('.portfolioFilter li:first a').data('filter');

      $('.portfolioFilter li:first').addClass('active');

      portfolioOptions.load.filter = sortBy;
    }

    $portfolioContainer.mixItUp(portfolioOptions);
  }
};

(function ($) {
  (function () {
    var blockPopstateEvent = true;
    window.addEventListener('load', function () {
      setTimeout(function () {
        blockPopstateEvent = false;
      }, 0);
    }, false);
    window.addEventListener('popstate', function (evt) {
      if (blockPopstateEvent && document.readyState === 'complete') {
        evt.preventDefault();
        evt.stopImmediatePropagation();
      }
    }, false);
  })();

  Listing = {
    resultCallbacks: [],
    formCallbacks: [],
    vehicleSliders: [],
    filters: {},
    page: {
      current: 1
    },
    pdfPreloadActions: {
      logo: false,
      map: false,
      tabs: false,
      images: false
    }
  };

  // add pre-filtered conditions to our js object
  if (typeof inventoryCurrentFilters !== 'undefined') {
    Listing.filters = inventoryCurrentFilters;
  } else if (typeof listing_ajax.currentFilters !== 'undefined') {
    Listing.filters = listing_ajax.currentFilters;
  }

  // get current inventory page
  if ($('.controls.page_of span.current_page').length) {
    Listing.page.current = parseInt($('.controls.page_of span.current_page').text());
  }

  $(document).ready(function ($) {
    if (typeof window.TomSelect !== 'undefined') {
      window.TomSelect.define('no_close', function () {
        this.close = function () {
        };
      });
    }

    $.fn.evenElements = function () {
      var heights = [];

      $(this).removeAttr('style').height('auto');

      this.each(function () {
        var height = $(this).height('auto').outerHeight();

        heights.push(height);
      });

      var largest = Math.max.apply(Math, heights);

      return this.each(function () {
        $(this).height(largest);
      });
    };

    var $body = $('body');

    // keep comma (or other separator) between numbers
    function commaSeparateNumber(val, separator) {
      return val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + separator);
      // while (/(\d+)(\d{3})/.test(val.toString())) {
      //   val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1' + separator);
      // }
      // return val;
    }

    // animate number function
    function animateNumber(el, value) {
      var original = value;
      var separator = el.data('separator');

      value = parseInt(value);

      $(this).prop('Counter', 0).animate({
        Counter: value
      }, {
        duration: 1000,
        easing: 'swing',
        step: function (now) {
          if (now < value) {
            el.text(commaSeparateNumber(Math.ceil(now), separator));
          }
        },
        complete: function () {
          el.text(commaSeparateNumber(Math.ceil(this.Counter), separator));

          this.Counter = 0;
        }
      });
    }

    function scrollToTopPage() {
      $('html,body').animate({
        scrollTop: ($('.listing-view').offset().top - 150)
      });
    }

    // animate numbers
    $.initAnimatedNumbers = function initAnimatedNumbers() {
      var $animateNumber = $('.animate_number');

      if ($animateNumber.length) {
        $animateNumber.each(function () {
          var el = $(this).find('.number');

          el.data('value', el.text());
          el.text(0);

          $(this).one('inview', function (event, isInView) {
            var value = el.data('value').replace(/[^0-9]/gi, '');

            if (isInView) {
              setTimeout(function () {
                animateNumber(el, value);
              }, 500);
            }
          });
        });
      }
    }

    $.initAnimatedNumbers();


    // disable link jumps on portfolio filter links
    $(document).on('click', '.portfolioFilter li a', function (e) {
      e.preventDefault();
    });

    $.initPortfolio();


    $('button[data-hover]').on({
      mouseenter: function () {
        $(this).css({
          'backgroundColor': $(this).data('hover'),
          'background': $(this).data('hover')
        });
      },
      mouseleave: function () {
        $(this).css({
          'backgroundColor': $(this).data('color'),
          'background': $(this).data('color')
        });
      }
    });

    $('.card-container').on({
      mouseenter: function () {
        $(this).find('.flipping-card').addClass('flip-card');
      },
      mouseleave: function () {
        $(this).find('.flipping-card').removeClass('flip-card');
      }
    });

    $('.recent_listings li.even_elements .desc').evenElements();

    if ($('.woocommerce-featured-scroller ul.products').length && $.fn.bxSlider) {
      $('.woocommerce-featured-scroller').each(function () {
        var $next = $(this).find('.slide_controls .next-btn');
        var $prev = $(this).find('.slide_controls .prev-btn');

        $(this).find('.products').bxSlider({
          slideWidth: 230,
          minSlides: 1,
          maxSlides: 4,
          slideMargin: 80,
          infiniteLoop: true,
          pager: false,
          touchEnabled: false,
          nextSelector: $next,
          prevSelector: $prev,
          nextText: '',
          prevText: ''
        });
      });
    }

    // select view buttons
    $(document).on('click', '.page-view li', function (e) {
      e.preventDefault();

      var layout = $(this).data('layout');

      $('.select_view').data('layout', layout);

      $('.page-view li.active').removeClass('active');
      $(this).addClass('active');

      var params = getQueryStringAsObject();

      $.each(Listing.filters, function (categorySlug, categoryInfo) {
        params[categorySlug] = Object.keys(categoryInfo.data).join(",");
      });

      params['sold_only'] = $('.listing_select').data('sold_only');

      params = JSON.stringify(params);

      $.ajax({
        type: 'post',
        url: listing_ajax.ajaxurl,
        data: {
          action: 'generate_new_view',
          layout: layout,
          params: params,
          page: $('.page_of').data('page'),
          page_id: listing_ajax.post_id
        },
        dataType: 'json',
        /**
         * @param {string} response.html
         * @param {string} response.bottom_page
         * @param {string} response.top_page
         */
        success: function (response) {
          var $selectorHandle = $('.generate_new > .sidebar').length ? $('.generate_new > .sidebar') : $('.generate_new');

          $selectorHandle.slideUp(400, function () {
            $(this).html(response.html);

            if (typeof Listing.resultCallbacks !== 'undefined' && Listing.resultCallbacks.length > 0) {
              $.each(Listing.resultCallbacks, function (key, value) {
                value();
              });
            }

            $(this).slideDown(400, function () {
              var paginationClasses = '';

              if (layout === 'wide_left' || layout === 'boxed_left') {
                paginationClasses = 'col-lg-9 col-md-9 col-sm-12 col-xs-12 col-lg-offset-3';
              } else if (layout === 'wide_right' || layout === 'boxed_right') {
                paginationClasses = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
              } else {
                paginationClasses = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
              }

              // pagination bottom
              $(this).append("<div class='" + paginationClasses + " pagination_container'>" + response.bottom_page + '</div>');
            });

            pushUrlVars($('.page_of').data('page'));

            // pagination top
            $('.page_of').parent().html(response.top_page);

            $.initListingFilters();
            $.initSearchListingFilters();
            $.initListingRangeFilters();
          });
        }
      });
    });

    // Inventory Listings Filter
    // $('select.listing_filter').change(function () {
    //     var $ulLi = $('ul.filter li');
    //     var items = $ulLi.length;
    //     var chose = $(this).val();
    //     var type = ($(this).prop('name') === 'year' ? 'yr' : $(this).prop('name'));
    //     var id = $(this).prop('id');
    //     var slug = $('#' + id + ' option:selected').data('slug');
    //
    //     var noOptions = $("select[name='" + type + "'] option:selected").data('no-options');
    //     var $liType = $("ul.filter li[data-type='" + type + "']");
    //
    //     if (noOptions) {
    //         return;
    //     }
    //
    //
    //     var $option = $(this).find('option:selected');
    //
    //     // If no filters are set
    //     if (items === 1 && $ulLi.eq(0).data('filter') === 'All') {
    //         $ulLi.eq(0).remove();
    //     }
    //
    //     if ($liType.length) {
    //         $liType.html("<li data-type='" + type + "' data-slug='" + slug + "'><a href=''><i class='fa fa-times-circle'></i> <span data-key='" + $option.data('key') + "'>" + $(this).data('label-singular') + ': ' + chose + '</span></a></li>').fadeIn();
    //     } else {
    //         $("<li data-type='" + type + "' data-slug='" + slug + "'><a href=''><i class='fa fa-times-circle'></i> " + $(this).data('label-singular') + ': <span data-key="' + $option.data('key') + '">' + chose + '</span></a></li>').appendTo('ul.filter').hide().fadeIn();
    //     }
    //
    //     // $("select[name='" + type + "']").val(chose);
    //
    //     // updateResults(1);
    //     // $.updateListingResults(1);
    // });

    // Deselect Vehicles
    var $topButtons = $('.top_buttons');

    $topButtons.on('click', '.deselect', function (e) {
      e.preventDefault();

      var $compare = $('.compare');
      var cookieKey = 'compare_vehicles' + ($compare.data('lang') ? '_' + $compare.data('lang') : '');

      $('input:checkbox').removeAttr('checked');

      $.removeCookie(cookieKey, {path: '/'});
      compareVehicles();
    });

    // tooltip
    if ($('.tooltip_js').length) {
      $('.tooltip_js').tooltip();
    }

    if ($('.lazy-loading-image').length) {
      $('.lazy-loading-image').Lazy({
        afterLoad: function (element) {
          $.each(Listing.vehicleSliders, function (key, slider) {
            slider.reloadSlider();
          });

          $.initFlippingCards();
        }
      });
    }

    if ($('.lazy-loading-background-image').length) {
      $('.lazy-loading-background-image').lazy();
    }

    $.initFlippingCards();

    var $popover = $("*[data-toggle='popover']");

    if ($popover.length) {
      $popover.each(function () {
        $(this).popover();
      });

      $popover.click(function (e) {
        e.preventDefault();
      });
    }

    $('.tabs_shortcode li').click(function (e) {
      e.preventDefault();
      $(this).find('a').tab('show');

      $('.tabs_shortcode .active').removeClass('active');

      $(this).addClass('active');
    });

    // reset filters
    $topButtons.on('click', '.reset', function (e) {
      e.preventDefault();

      var $filterLi = $('ul.filter li');

      $filterLi.each(function () {
        var name = ($(this).data('type') === 'year' ? 'yr' : $(this).data('type'));

        $("select.listing_filter[name='" + name + "']").each(function () {
          $(this).prop('selected', false);
        });

        $(this).remove();
      });

      $.resetRangeFilters();

      $.removeAllListingFilters();

      // updateResults(1);
      $.updateListingResults(1);
      $('.currentPage').text('1');
    });

    // function resetRangeFilters(rangeSelector){
    $.resetRangeFilters = function resetRangeFilters(rangeSelector) {
      rangeSelector = rangeSelector || $('.listing-range-slider');

      if (rangeSelector.length) {
        rangeSelector.each(function () {
          var min = $(this).data('min');
          var max = $(this).data('max');
          var isCurrency = $(this).data('currency');

          if (isCurrency) {
            var currencySymbol = $(this).data('currency-symbol');
            var thousandSeparator = $(this).data('thousand-sep');
            var decimalSeparator = $(this).data('decimal-sep');
            var decimals = $(this).data('decimals');
          }

          $(this).slider('option', 'values', [min, max]);

          if (isCurrency) {
            min = currencySymbol + number_format(min, decimals, decimalSeparator, thousandSeparator);
            max = currencySymbol + number_format(max, decimals, decimalSeparator, thousandSeparator);
          }

          $(this).closest('.listing-range-slider-container').find('.min-label').val(min);
          $(this).closest('.listing-range-slider-container').find('.max-label').val(max);
        });
      }
    }

    function pushUrlVars(currentPage) {
      var parameters;
      var $pageOf = $('.page_of');

      // init data
      if (!$pageOf.data('page') && $pageOf.prop('data-page')) {
        $pageOf.data('page', $pageOf.prop('data-page'));
      }

      var queryVars = window.location.search;

      // if permalinks are set
      if (listing_ajax.permalink_set === 'true') {
        parameters = '?';
      } else {
        parameters = (queryVars.charAt(0) === '?' ? '&' : '?');
      }

      $.each(Listing.filters, function (categorySlug, categoryInfo) {
        var totalTerms = Object.keys(categoryInfo.data).length;
        var isMinMax = (typeof categoryInfo.min_max !== 'undefined' && categoryInfo.min_max);
        var termURL = categorySlug + (!isMinMax && totalTerms > 1 ? "[]" : "");

        if (isMinMax) {
          parameters += termURL + "=" + Object.keys(categoryInfo.data).join(",") + "&";
        } else {
          $.each(categoryInfo.data, function (termSlug, termLabel) {
            parameters += termURL + "=" + termSlug + "&";
          });
        }

      });

      // order parameter
      var $priceOrder = $("select[name='price_order']");

      if ($priceOrder.length) {
        var orderCategory = $priceOrder.val();

        if (orderCategory !== 'none') {
          var orderbyParams = $priceOrder.val().split('|');
          parameters += 'listing_order=' + orderbyParams[0] + '&';

          if (orderbyParams[0] !== 'random') {
            parameters += 'listing_orderby=' + orderbyParams[1] + '&';
          }
        }
      }

      // page parameter
      parameters += (currentPage === 1 || !currentPage ? '' : 'paged=' + currentPage + '&');

      if (/[?&]show_only_sold/.test(location.href)) {
        parameters += 'show_only_sold&';
      }

      if (/[?&]show_sold/.test(location.href)) {
        parameters += 'show_sold&';
      }

      if (/[?&]user_id/.test(location.href)) {
        var url = new URL(location.href);
        var user_id = url.searchParams.get("user_id");

        parameters += '&user_id=' + user_id + '&';
      }

      history.pushState('', '', listing_ajax.current_url + parameters.slice(0, -1));

      $body.addClass('historypushed');
    }

    var getQueryStringAsObject = function () {
      var b;
      var cv;
      var e;
      var k;
      var ma;
      var sk;
      var v;
      var r = {};
      var d = function (v) {
        return decodeURIComponent(v).replace(/\+/g, ' ');
      };
      var q = window.location.search.substring(1);
      var s = /([^&;=]+)=?([^&;]*)/g;

      ma = function (v) {
        if (typeof v !== 'object') {
          cv = v;
          v = {};
          v.length = 0;

          if (cv) {
            Array.prototype.push.call(v, cv);
          }
        }
        return v;
      };

      while (e = s.exec(q)) {
        b = e[1].indexOf('[');
        v = d(e[2]);

        if (b < 0) {
          k = d(e[1]);

          if (r[k]) {
            r[k] = ma(r[k]);
            Array.prototype.push.call(r[k], v);
          } else {
            r[k] = v;
          }
        } else {
          k = d(e[1].slice(0, b));
          sk = d(e[1].slice(b + 1, e[1].indexOf(']', b)));

          r[k] = ma(r[k]);

          if (sk) {
            r[k][sk] = v;
          } else {
            Array.prototype.push.call(r[k], v);
          }
        }
      }

      // remove page id
      delete r['page_id'];
      // delete r['paged'];

      return r;
    };

    function arraysEqual(a, b) {
      if (a === b) return true;
      if (a === null || b === null) return false;
      if (a.length !== b.length) return false;

      // If you don't care about the order of the elements inside
      // the array, you should sort both arrays here.

      for (var i = 0; i < a.length; ++i) {
        if (a[i] !== b[i]) return false;
      }
      return true;
    }

    var theParameters = getQueryStringAsObject();

    if ($('.car_listings').length) {
      $(window).on('popstate', function (e) {
        e.preventDefault();

        if ($body.hasClass('historypushed')) {
          var newParameters = getQueryStringAsObject();
          var $listingView = $('.listing-view');

          // if using shortcode
          var tempTestNewParameters = newParameters;
          delete tempTestNewParameters.order;

          if ($.isEmptyObject(tempTestNewParameters) && $listingView.data('selected-categories')) {
            newParameters = $listingView.data('selected-categories');
          }

          if (!$.isEmptyObject(newParameters)) {
            $('ul.filter li').each(function () {
              var type = $(this).data('type');

              type = (type === 'year' ? 'yr' : type);

              if (!(type in newParameters)) {
                $(this).remove();
              }
            });
          } else {
            $('ul.filter li').each(function () {
              $(this).remove();
            });
          }

          if (arraysEqual(theParameters, newParameters)) {
            // updateAjaxResults();
            var newPage = (typeof newParameters.paged !== 'undefined' ? newParameters.paged : 1);

            $.updateListingResults(newPage);
          }
        }
      });
    }

    // check box vehicles
    $body.on('click', '.compare_vehicle', function () {
      var $compare = $('.compare');
      var cookieKey = 'compare_vehicles' + ($compare.data('lang') ? '_' + $compare.data('lang') : '');
      var action = '';

      if ($(this).prop('checked')) {
        action = 'checked';
      } else {
        action = 'unchecked';
      }

      var cookie = decodeURIComponent($.cookie(cookieKey));
      var cookiet = $.cookie(cookieKey);
      var vehiclesSafe = '';

      if (typeof cookiet === 'undefined' || !cookiet) {
        var vehicles = [];

        $('.compare_vehicle:checked').each(function () {
          var id = $(this).data('id');

          vehicles.push(id);
        });

        vehiclesSafe = encodeURIComponent(vehicles);// .join(','));
      } else {
        var ids = cookie.split(',');

        if (action === 'checked') {
          ids.push($(this).data('id'));
        } else {
          var index = ids.indexOf(String($(this).data('id')));
          ids.splice(index, 1);
        }

        vehiclesSafe = encodeURIComponent(ids.join(','));
      }

      $.cookie(cookieKey, vehiclesSafe, {path: '/'});

      compareVehicles();
    });

    // remove a filter
    $('ul.filter').on('click', 'li', function (e) {
      e.preventDefault();

      if ($(this).data('type') === 'All') {
        return false;
      }

      $(this).fadeOut(400, function () {
        var name = $(this).data('type').replace('[]', '');
        var key = $(this).find('span').data('key');

        $.removeListingFilter(name, key);

        // $(this).remove();
        $.updateListingResults();
      });
    });

    // pagination
    $(document).on('click', '.page_of .right-arrow', function (e) {
      e.preventDefault();

      var currentPage = parseInt($('.page_of').data('page'));
      var totalPages = parseInt($('.page .total_pages').text());

      if ($(this).hasClass('disabled')) {
        return false;
      }

      if (currentPage < totalPages) {
        // updateResults("next");
        $('.page_of .currentPage').text(currentPage + 1);
        // $('.page_of').data('page', (currentPage + 1));

        $.updateListingResults('next');
      }
    });

    $(document).on('click', '.page_of .left-arrow', function (e) {
      e.preventDefault();

      var currentPage = parseInt($('.page_of').data('page'));

      if ($(this).hasClass('disabled')) {
        return false;
      }

      // updateResults("prev");
      $('.page_of .currentPage').text(currentPage - 1);
      // $('.page_of').data('page', (currentPage - 1));

      $.updateListingResults('prev');
    });

    $(document).on('click', '.bottom_pagination li[data-page]', function () {
      if (!$(this).hasClass('nojs')) {
        var page = $(this).data('page');
        var currentPage = parseInt($('.page_of').data('page'));
        var totalPages = parseInt($('.total_pages').text());

        if (page === 'next' && (currentPage === totalPages)) {
          return false;
        }

        if (page !== 'next' && page !== 'previous') {
          $.updateListingResults(page);
          $('.currentPage').html(page);
        } else {
          if (page === 'next') {
            $.updateListingResults(currentPage + 1);
          } else if (page === 'previous') {
            $.updateListingResults(currentPage - 1);
          }
        }

        $([document.documentElement, document.body]).animate({
          scrollTop: $(".car_listings ").offset().top - 200
        }, 500);
      }
    });

    $(document).on('click', '.bottom_pagination li[data-page] a', function (e) {
      e.preventDefault();
    });

    function compareVehicles() {
      var $compare = $('.compare');
      var cookieKey = 'compare_vehicles' + ($compare.data('lang') ? '_' + $compare.data('lang') : '');
      var vehicles = decodeURIComponent($.cookie(cookieKey));
      vehicles = vehicles.split(',').length;

      if (typeof ($.cookie(cookieKey)) === 'undefined' || (typeof ($.cookie(cookieKey)) === 'string' && !$.cookie(cookieKey))) {
        vehicles = 0;
      }

      if (vehicles === 1) {
        $('.compare_grammar').html(listing_ajax.singular_vehicles);
      } else {
        $('.compare_grammar').html(listing_ajax.plural_vehicles);
      }

      $('.number_of_vehicles').html(vehicles);
    }

    function filterResults(page) {
      var parameters = 'action=filter_listing';

      // if layout is set
      parameters += '&layout=' + $('.select_view').data('layout');

      // storing the listing filter data globally in a browser near you!
      // $.each(Listing.filters, function(index, key){
      // if(typeof key.value === 'object'){
      //   if(index === 'features'){
      //     var tempParams = [];
      //
      //     $.each(key.value, function(keyKey, keyValue){
      //       tempParams.push(keyKey);
      //     });
      //
      //     parameters += '&' + index + '=' + tempParams.join(',');
      //   } else {
      //     parameters += '&' + index + '[]=' + key.value[0] + '&' + index + '[]=' + key.value[1];
      //   }
      // } else {
      //   parameters += '&' + index + '=' + key.value;
      // }
      // });

      $.each(Listing.filters, function (categorySlug, categoryInfo) {
        var totalTerms = Object.keys(categoryInfo.data).length;
        var isMinMax = (typeof categoryInfo.min_max !== 'undefined' && categoryInfo.min_max);
        var termURL = categorySlug + (!isMinMax && totalTerms > 1 ? "[]" : "");

        if (isMinMax) {
          parameters += '&' + termURL + "=" + Object.keys(categoryInfo.data).join(",");
        } else {
          $.each(categoryInfo.data, function (termSlug, termLabel) {
            parameters += '&' + termURL + "=" + termSlug;
          });
        }

      });


      // keywords
      var $keyword = $("ul.filter li[data-type='keywords'] span");

      if ($keyword.length) {
        parameters += "&keywords=" + $keyword.text();
      }

      // showing only sold?
      if (/[?&]show_only_sold/.test(location.href)) {
        parameters += '&show_only_sold=true';
      }

      // showing sold?
      if (/[?&]show_sold/.test(location.href)) {
        parameters += '&show_sold=true';
      }

      // user_id
      if (/[?&]user_id/.test(location.href)) {
        var url = new URL(location.href);
        var user_id = url.searchParams.get("user_id");

        parameters += '&user_id=' + user_id;
      }

      // sold only = true
      if ($('.listing_select').data('sold_only') === true) {
        parameters += '&sold_only=true';
      }

      if (page !== false) {
        parameters += '&paged=' + page;

        // set new page
        $('.page_of').data('page', page);
        $('.currentPage').text(page);
      }

      // order by params
      var $priceOrder = $("select[name='price_order']");
      if ($priceOrder.length && $priceOrder.val() !== 'none') {
        var orderbyParams = $priceOrder.val().split('|');
        parameters += '&listing_order=' + orderbyParams[0];

        if (orderbyParams[0] !== 'random') {
          parameters += '&listing_orderby=' + orderbyParams[1];
        }
      }

      return parameters;
    }

    $(document).on('click', '.find_new_vehicle', function () {
      $('.find_new_vehicle .loading_results').css('display', 'inline-block');
    });

    // jQuery functions
    $.addListingFilter = function addListingFilter(type, singular, value, label, isMinMax) {
      isMinMax = (typeof isMinMax !== 'undefined' ? isMinMax : false);
      // year workaround
      type = (type === 'year' ? 'yr' : type);
      // add the filter data to our global object
      if (type && singular && value && label) {
        // this means we can have multiple selected values at once, not to be confused with min/max multi values
        var isMulti = $("select.listing_filter[name='" + type + "']").prop('multiple');

        if (type == "features") {
          isMulti = true;
        }

        var addAnother = false; // used to check if we need to add a new term or replace an existing one
        if (typeof Listing.filters[type] === 'undefined') {
          Listing.filters[type] = {
            singular: singular,
            data: {}
          };

          Listing.filters[type].data[value] = label;
        } else if (!isMulti) {
          // replacing since you can only have 1 selected value
          Listing.filters[type].data = {};
          Listing.filters[type].data[value] = label;
        } else {
          Listing.filters[type].data[value] = label;

          addAnother = true;
        }

        var $filter = $('.filter');
        var fade = true;

        // remove the all term if exists
        if ($filter.find("li[data-type='All']").length) {
          $filter.find("li[data-type='All']").remove();
        }

        // remove existing
        if (!addAnother && $filter.find("li[data-type='" + type + "']").length) {
          $filter.find("li[data-type='" + type + "']").remove();
        }
      }

      if (addAnother && $filter.find("li[data-type='" + type + "'] span[data-key='" + value + "']").length) {
        return;
      }

      var filterHTML = "<li data-type='" + type + "' style='display: none;'";

      if (isMinMax) {
        filterHTML += " data-min='" + value[0] + "' data-max='" + value[1] + "'";
      }

      filterHTML += "><a href=''><i class='fa fa-times-circle'></i> " + singular + ": <span data-key='" + value + "'>" + label + '</span></a></li>';

      if ($filter) {
        $filter.append(filterHTML);

        if (fade) {
          $filter.find('li:last-of-type').fadeIn();
        } else {
          $filter.find('li:last-of-type').show();
        }
      }
      // }
    };

    $.removeListingFilter = function removeListingFilter(key, value, label) {
      var type = (key === 'year' ? 'yr' : key);
      var $filter = $('ul.filter');
      var $filterLi = $filter.find('li');
      var $singleLi = $filter.find('li[data-type^="' + type + '"]');

      // features
      if (key == 'features') {
        var $filterValue = $filter.find('li[data-type="' + type + '"] span[data-key="' + value + '"]');

        $singleLi = $filterValue.closest('li');


        // remove the filter data from our global object
        if (typeof Listing.filters[type] !== 'undefined' && typeof Listing.filters[type].data[value] !== 'undefined') {
          delete Listing.filters[type].data[value];
        }
      } else {

        // specifying a value so we only want to remove a single value
        if (typeof value !== 'undefined') {

          if (typeof Listing.filters[type] !== 'undefined') {
            delete Listing.filters[type].data[value];

            var $filterValue = $filter.find('li[data-type="' + type + '"] span[data-key="' + value + '"]');

            $singleLi = $filterValue.closest('li');

          }

        } else {

          // remove the filter data from our global object
          if (typeof Listing.filters[type] !== 'undefined') {
            delete Listing.filters[type];
          }

        }

      }

      if ($('.listing-range-slider[data-type="' + (type === 'yr' ? 'year' : type) + '"]').length) {
        $.resetRangeFilters($('.listing-range-slider[data-type="' + (type === 'yr' ? 'year' : type) + '"]'));
      }


      if ($singleLi.length) {
        $singleLi.fadeOut(function () {
          var $li = $(this);
          var isMinMax = $(this).data('min') && $(this).data('max');

          if (typeof listingDropdowns[type] !== 'undefined') {
            for (var i = 0; i < listingDropdowns[type].length; i++) {
              var dropdown = listingDropdowns[type][i];

              if (isMinMax) {
                dropdown.removeItem($li.data('min'), true);
                dropdown.removeItem($li.data('max'), true);
              } else {
                dropdown.removeItem($li.find('span[data-key]').data('key'), true);
                dropdown.removeItem($li.find('span[data-key]').text(), true);
              }
            }
          }

          $(this).remove();

          if ($filterLi.length === 1 || $filterLi.length === 0) {
            $filter.html("<li data-type='All' data-filter='All'>" + $filter.data('all-listings') + '</li>');
          }
        });
      } else {
        if ($filterLi.length === 0) {
          $filter.html("<li data-type='All' data-filter='All'>" + $filter.data('all-listings') + '</li>');
        }
      }
    }

    $.addListingAction = function addListingAction(callback) {
      Listing.resultCallbacks.push(callback);
    };

    $.removeAllListingFilters = function removeAllListingFilters() {
      var $filter = $('ul.filter');
      var $filterLi = $filter.find('li');

      Listing.filters = {};

      $.each(listingDropdowns, function (slug, dropdowns) {
        if (slug !== 'price_order') {
          $.each(dropdowns, function (dropdownI, dropdown) {
            dropdown.clear(true);
          });
        }
      });

      if ($filterLi.length === 0) {
        $filter.html("<li data-type='All' data-filter='All'>" + $filter.data('all-listings') + '</li>');
      }
    }

    function updateDropdownTerms(key, value, isInventoryElement, countedTerms, currentOption) {
      var originalKey = key; // year workaround
      key = (key === 'year' ? 'yr' : key);
      // currentOption = currentOption || $("ul.filter li[data-type='" + key + "'] span").data('key');

      var currentOptions = [];

      if (!currentOption) {
        $("ul.filter li[data-type='" + key + "'] span").each(function () {
          currentOptions.push($(this).data('key').toString());
        });
      } else {
        if (typeof currentOption === 'object') {
          currentOptions = currentOption;
        } else {
          currentOptions.push(currentOption.toString());
        }
      }

      var selects = document.querySelectorAll('select.listing_filter[name="' + key + '"], select.css-dropdowns[name^="' + key + '"]:not(.trade-in-dropdown)');
      var searchI = 0;
      var listingI = 0;

      selects.forEach(function (select, selectI) {
        var isListingDropdown = !select.classList.contains('css-dropdowns');
        var selectTitle = select.dataset.prefix + ' ' + select.dataset.labelPlural;//($(this).hasClass('css-dropdowns') ? $(this).data('prefix') + ' ' + $(this).data('label-singular') : $(this).data('prefix') + ' ' + $(this).data('label-plural'));
        var newOptions = (!$.isEmptyObject(value) && typeof value.auto_term_order !== 'undefined' && value.auto_term_order === 'desc' ? '' : "<option value=''>" + selectTitle + '</option>');
        var listingDropdown = (!isListingDropdown ? searchDropdowns[key][searchI] : listingDropdowns[key][listingI]);
        var setValues = [];

        if (isListingDropdown) {
          listingI++;
        } else {
          searchI++;
        }

        if (typeof currentOptions === 'string') {
          setValues = [currentOptions];
        }

        if (typeof value === 'object' && !$.isEmptyObject(value)) {
          $.each(value, function (valueKey, valueValue) {
            if (valueKey !== 'auto_term_order') {
              var valueLabel = valueValue;
              var isCurrentOption = currentOptions.indexOf(valueKey) > -1;//currentOption === valueKey;

              // if desc terms
              if (typeof countedTerms !== 'undefined' && typeof countedTerms[originalKey] !== 'undefined' && typeof countedTerms[originalKey][valueKey] !== 'undefined') {
                valueLabel += ' (' + countedTerms[originalKey][valueKey] + ')';
              }

              if (typeof value.auto_term_order !== 'undefined' && value.auto_term_order === 'desc') {
                newOptions = "<option value='" + htmlEscape(valueValue) + "' data-key='" + valueKey + "'" + (isCurrentOption ? "selected='selected'" : '') + '>' + valueLabel + '</option>' + newOptions;
              } else {
                newOptions += "<option value='" + htmlEscape(valueValue) + "' data-key='" + valueKey + "'" + (isCurrentOption ? "selected='selected'" : '') + '>' + valueLabel + '</option>';
              }

              if (isCurrentOption) {
                setValues.push(valueValue);
              }
            }
          });
        } else {
          newOptions += '<option>' + select.dataset.noOptions + '</option>';
        }

        if (typeof value !== 'undefined' && !$.isEmptyObject(value) && typeof value.auto_term_order !== 'undefined' && value.auto_term_order === 'desc') {
          newOptions = "<option value=''>" + selectTitle + '</option>' + newOptions;
        }

        $(listingDropdown.input).html(newOptions);

        $.each(setValues, function (i, e) {
          $(select).find("option[value='" + e + "']").prop("selected", true);
        });

        listingDropdown.clear(true);
        listingDropdown.clearOptions();
        listingDropdown.sync();

        if (setValues) {
          listingDropdown.setValue(setValues, true);
        }

        if (select.classList.contains('automotive-inline-dropdown')) {
          listingDropdown.refreshOptions();

          listingDropdown.open();
        }
      });
    }

    $.updateListingResults = function updateResults(nextPage, isInventoryElement) {
      nextPage = (typeof nextPage === 'undefined') ? false : nextPage;
      isInventoryElement = isInventoryElement || false;

      var $topPagination = $('.page_of');
      var $bottomPagination = $('.bottom_pagination');
      var hasSidebar = $('.car_listings > .sidebar').length;
      var resultsHeight = $('.row.generate_new').height();

      $('.loading_results').css('display', 'inline-block');

      if (nextPage === 'prev') {
        // nextPage = parseInt($pageOf.data('page')) - 1;
        nextPage = Listing.page.current - 1;
      } else if (nextPage === 'next') {
        // nextPage = parseInt($pageOf.data('page')) + 1;
        nextPage = Listing.page.current + 1;
      }

      // update listings
      $.ajax({
        type: 'post',
        url: listing_ajax.ajaxurl,
        data: filterResults(nextPage),
        dataType: 'json',
        /**
         * @param {string} response.content
         * @param {string} response.number
         * @param {string} response.top_page
         * @param {string} response.bottom_page
         * @param {string} response.dependancies
         */
        success: function (response) {
          $('.row.generate_new').height(resultsHeight);

          $('.car_listings').slideUp(400, function () {
            pushUrlVars(nextPage);
            // scrollToTopPage();

            $(this).html((!hasSidebar ? response.content : '<div class="sidebar">' + response.content + '</div>'));

            $(this).slideDown(400, function () {
              $('.row.generate_new').height('auto');

              // update number of listings
              var listings = response.number;
              var grammar = (listings === 1 ? listing_ajax.singular_vehicles : listing_ajax.plural_vehicles);

              var nextButton = false;
              var prevButton = false;

              $('.number_of_listings').html(listings);
              $('.listings_grammar').html(grammar);

              Listing.page.current = response.pagination.current;

              if ($topPagination.length || $bottomPagination.length) {
                var paginationData = response.pagination; // current & total

                if (paginationData.current !== paginationData.total && paginationData.current < paginationData.total) {
                  nextButton = true;
                }

                if (paginationData.current !== 1) {
                  prevButton = true;
                }
              }

              // update top pagination area if it exists
              if ($topPagination.length) {
                $topPagination.data('page', response.pagination.current);

                $topPagination.find('.current_page').text(paginationData.current);
                $topPagination.find('.total_pages').text(paginationData.total);

                if (nextButton) {
                  $topPagination.find('.right-arrow').removeClass('disabled');
                } else {
                  $topPagination.find('.right-arrow').addClass('disabled');
                }

                if (prevButton) {
                  $topPagination.find('.left-arrow').removeClass('disabled');
                } else {
                  $topPagination.find('.left-arrow').addClass('disabled');
                }
              }

              // update bottom pagination area if it exists
              if ($bottomPagination.length) {
                $('div.pagination_container').html(response.bottom_page);
              }

              if (typeof response.dependancies !== 'undefined' && typeof response.dependancies[0] === 'object') {
                // update dropdowns with new values

                $.each(response.dependancies[0], function (key, value) {
                  if (typeof listing_ajax.listing_terms !== 'undefined') {
                    var testKey = (key == 'year' ? 'yr' : key);
                    var existingValues = [];

                    if (typeof Listing.filters[testKey] !== 'undefined' && typeof Listing.filters[testKey].data !== 'undefined') {
                      existingValues = Object.values(Listing.filters[testKey].data);
                    }

                    // if the value is selected we don't want to overwrite the terms
                    if (typeof Listing.filters[testKey] === 'undefined') {
                      updateDropdownTerms(key, value, isInventoryElement, response.dependancies[1], existingValues);
                    }

                  } else {
                    updateDropdownTerms(key, value, isInventoryElement, response.dependancies[1], existingValues);
                  }

                });

                $.initListingFilters();
                $.initSearchListingFilters();
              }

              $.each(Listing.filters, function (slug, data) {
                $.each(data.data, function (termKey, termLabel) {
                  $.each(listingDropdowns[slug], function (dropdownI, dropdown) {
                    dropdown.addItem(termLabel, true);
                  });
                });
              });

              if (typeof Listing.resultCallbacks !== 'undefined' && Listing.resultCallbacks.length > 0) {
                $.each(Listing.resultCallbacks, function (key, value) {
                  value(response);
                });
              }

              $('.loading_results').hide();
            });
          });
        },
        error: function (response) {
          console.log(response);
        }
      });
    };

    $(document).delegate('.view-video', 'click', function () {
      var ele = $(this);
      var $youtubeVideo = $('#youtube_video');
      var $iframe = $youtubeVideo.find('iframe');

      $.fancybox({
        'href': '#youtube_video',
        'height': '320',
        'width': '560',
        'fitToView': false,
        'autoSize': false,
        'maxWidth': '90%',
        'beforeLoad': function () {
          var httpPrefix = (listing_ajax.is_ssl ? 'https' : 'http');
          var videoType = ele.data('video');

          if (videoType === 'self_hosted') {
            var divId = ele.data('div');

            $youtubeVideo.append('<div>' + $('#' + divId).html() + '</div>');
            $('#youtube_video .wp-video, #youtube_video .wp-video-shortcode').css({
              width: '545px',
              height: '305px'
            });
            $iframe.hide();
          } else {
            var videoUrl = '';

            if (videoType === 'vimeo') {
              videoUrl = httpPrefix + '://player.vimeo.com/video/' + ele.data('youtube-id');
            } else if (videoType === 'rumble') {
              videoUrl = httpPrefix + '://rumble.com/embed/' + ele.data('youtube-id');
            } else {
              videoUrl = httpPrefix + '://www.youtube.com/embed/' + ele.data('youtube-id') + '?vq=hd720&autoplay=1&rel=0';
            }

            $iframe.prop('src', videoUrl);
          }
        },
        'afterClose': function () {
          $iframe.show();
          // $("#youtube_video iframe").prop("src", "");

          $iframe.remove();
          $youtubeVideo.append("<iframe width='560' height='315' style='width: 560px; height: 315px; border: 0;'></iframe>");

          var $youtubeVideoDiv = $youtubeVideo.find('> div');

          if ($youtubeVideoDiv.length) {
            $youtubeVideoDiv.remove();
          }
        }
      });
    });


    // Mobile slideout sidebar
    if ($("body.automotive-msb-active").length) {
      function showSidebarLoader() {
        $(".automotive-mobile-filter-sidebar-loading").addClass('is-loading');
      }

      function hideSidebarLoader() {
        setTimeout(function () {
          $(".automotive-mobile-filter-sidebar-loading").removeClass('is-loading');
        }, 100);
      }


      $('.automotive-mobile-filter-sidebar').on('change', 'select.listing_filter', function () {
        showSidebarLoader();
      });

      $.addListingAction(hideSidebarLoader);

      $(document).on('click', '.refine-search', function (e) {
        e.preventDefault();

        resetAccordionDropdowns();

        $("body").toggleClass('automotive-mobile-filter-show');
      });

      $(document).on('click', '.automotive-mobile-sidebar-close', function (e) {
        e.preventDefault();

        $("body").removeClass('automotive-mobile-filter-show');
      });

      $(document).on('click', '.automotive-mobile-filter-sidebar-bg', function (e) {
        e.preventDefault();

        $("body").removeClass('automotive-mobile-filter-show');
      });

      if ($(".automotive-mobile-widget-accordion").length) {
        $(".automotive-mobile-widget-accordion").accordion({
          collapsible: true,
          heightStyle: "content"
        });
      }

      $(document).on('click', '.automotive-mobile-filter-sidebar button.clear-all', function (e) {
        showSidebarLoader();

        $('a.reset').trigger('click');

        $(".lightbox-filter.selected").removeClass("selected");

        resetAccordionDropdowns();
      });

      function resetAccordionDropdowns() {
        var $accordion = $(".automotive-mobile-widget-accordion");

        if ($accordion.length) {
          $accordion.find("> h3.ui-accordion-header.ui-state-active").each(function () {
            $(this).trigger('click');
          });
        }
      }


      function countTotalFilters() {
        var total = 0;

        var $button = $(".refine-search");
        var $clearAll = $(".clear-all.custom-button").show();

        $.each(Listing.filters, function (key, value) {
          if (typeof value.data !== 'undefined') {
            total += Object.keys(value.data).length;
          }
        });

        if (total === 0) {
          $button.find("span.total-filter-count").remove();

          $clearAll.fadeOut();
        } else {
          $clearAll.fadeIn();

          if (!$button.find("span.total-filter-count").length) {
            $button.append("<span class='total-filter-count'>" + total + "</span>");
          } else {
            $button.find("span.total-filter-count").text(total);
          }
        }
      }

      function loadCurrentlyApplied() {
        var $filter = $("ul.filter");
        var html = '';

        $filter.find("li").each(function () {
          if ($(this).data('type') !== "All") {
            var type = $(this).data('type');

            if (type === 'year') {
              type = 'yr';
            }

            type = type.replace("[]", "");

            html += '<li data-type="' + type + '"><i class="fa fa-times-circle"></i> ' + $(this).text() + '</li>';
          }
        });

        if (html.length) {
          $(".mobile-currently-applied").show()
        } else {
          $(".mobile-currently-applied").hide();
        }

        $(".currently-applied-list").html(html);

        hideSidebarLoader();
      }

      $(document).on('click', ".currently-applied-list li", function (e) {
        e.preventDefault();

        var type = $(this).data('type');

        showSidebarLoader();

        if ($(".automotive-toggle-filter[data-category='" + type + "']").length) {
          $(".automotive-toggle-filter[data-category='" + type + "'] input").prop('checked', false);
        }

        $("ul.filter li[data-type='" + type + "']").trigger('click');
      });


      loadCurrentlyApplied();
      countTotalFilters();

      $.addListingAction(countTotalFilters);
      $.addListingAction(loadCurrentlyApplied);
    }


    // Single Listing Tabs
    $('.listing_content').not(':first').hide();
    $('ul.listing_tabs li').click(function () {
      var datab = $(this).data('tab');

      $('ul.listing_tabs li.current').removeClass('current');
      $(this).addClass('current');

      var tab = $(this).index();
      $('.listing_content:visible').fadeOut(400, function () {
        $('.listing_content').eq(tab).fadeIn(400, function () {
          if (typeof datab !== 'undefined' && datab === 'map') {
            $.initGoogleMap();
          }
        });
      });
    });

    $.initGoogleMap();

    var $portfolioFlexslider = $('.portfolio_flexslider');

    if ($portfolioFlexslider.length) {
      $portfolioFlexslider.flexslider({
        animation: 'slide',
        controlNav: false,
        prevText: '',
        nextText: '',
        rtl: $body.hasClass('rtl')
      });
    }

    var $flexsliderThumb = $('.flexslider_thumb');

    if ($flexsliderThumb.length) {
      $flexsliderThumb.flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: true,
        animationLoop: false,
        slideshow: false,
        itemWidth: 167,
        itemMargin: 5,
        asNavFor: '.flexslider_slider',
        prevText: '',
        nextText: '',
        rtl: $body.hasClass('rtl')
      });

      var $flexsliderSlider = $('.flexslider_slider');

      $flexsliderSlider.flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: false,
        animationLoop: false,
        slideshow: false,
        sync: '.flexslider_thumb',
        rtl: $body.hasClass('rtl')
      });
    }

    $.initListingScroller = function initListingScroller() {
      var $carouselSlider = $('.carousel-slider3');

      if ($carouselSlider.length) {
        $carouselSlider.each(function () {
          var slideWidth = $(this).data('slide-width') || 167;
          var minSlides = $(this).data('min-slides') || 1;
          var maxSlides = $(this).data('max-slides') || 6;
          var slideMargin = $(this).data('slide-margin') || 27;

          var bxOptions = {
            slideWidth: slideWidth,
            minSlides: minSlides,
            maxSlides: maxSlides,
            slideMargin: slideMargin,
            infiniteLoop: false,
            pager: false,
            // touchEnabled: false,
            prevSelector: $(this).closest('.recent-vehicles-wrap').find('#slideControls3>.prev-btn'),
            nextSelector: $(this).closest('.recent-vehicles-wrap').find('#slideControls3>.next-btn'),
            autoHover: true
          };

          // firefox 59 workaround
          if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
            bxOptions.touchEnabled = false;
          }

          if ($(this).data('autoscroll') === true) {
            bxOptions.infiniteLoop = true;
            bxOptions.auto = true;
            bxOptions.autoStart = true;
          }

          var slider = $(this).bxSlider(bxOptions);

          Listing.vehicleSliders.push(slider);
        });
      }
    }

    $.initListingScroller();

    if (typeof window.automotiveCalculate === 'undefined') {
      window.automotiveCalculate = function (calculator) {
        console.log('automotiveCalculate does not exist');
      }
    }

    // Financing Calculator
    $(document).on('click', '.financing_calculator .calculate', function () {
      var calculator = $(this).closest('.financing_calculator');

      window.automotiveCalculate(calculator);
    });

    window.automotiveCalculate($('.financing_calculator'));

    // widget
    $(document).on('click', '.reset_widget_filter', function () {
      $('.listing_sidebar_widget select').each(function () {
        var id = $(this).prop('id');

        $('#' + id).find('option:first-child').prop('selected', true).end().trigger('liszt:updated');
      });

      var $filterLi = $('ul.filter li');

      $filterLi.each(function () {
        var name = $(this).data('type');

        $("select.listing_filter[name='" + name + "']").each(function () {
          $(this).prop('selected', false);
        });

        $(this).remove();
      });

      $.resetRangeFilters();

      // $.updateListingResults();
      $.removeAllListingFilters();

      // updateResults(1);
      $.updateListingResults(1);
      $('.currentPage').text('1');
    });

    function printTabs() {
      if (!$('.print_tabs').length) {
        // generate google map
        var $googleMap = $('.google_map_init');

        var longitude = $googleMap.data('longitude');
        var latitude = $googleMap.data('latitude');
        var zoom = $googleMap.data('zoom');

        var httpPrefix = (listing_ajax.is_ssl ? 'https' : 'http');

        var googleMap = "<img src='" + httpPrefix + '://maps.googleapis.com/maps/api/staticmap?center=' + latitude + ',' + longitude + '&zoom=' + zoom + '&size=700x200&markers=color:blue|label:S|' + latitude + ',' + longitude + '&key=' + listing_ajax.google_maps_api + "'>";

        $('.single-listing-tabs').each(function () {
          var tabsHtml = '';
          $(this).find('.nav-tabs li').each(function () {
            var currentHref = $(this).find('a').attr('href');

            tabsHtml += "<div class='" + currentHref.replace('#', '') + "'><h2>" + $(this).text() + '</h2><br />';
            tabsHtml += (currentHref === '#location' ? googleMap : $(".tab-content .tab-pane[id='" + currentHref.replace('#', '') + "']").html()) + '</div><br />';
          });

          $('.inner-page.inventory-listing').append("<div class='print_friendly print_tabs'>" + tabsHtml + '</div>');
        });
      }
    }

    function printHeader() {
      if (!$('.print_header').length) {
        var $companyInfo = $('.company_info');
        var headerHtml = '';

        headerHtml += $('.logo').html();
        headerHtml += ($companyInfo.length ? $companyInfo.html() : '');

        $('.inner-page.inventory-listing').prepend("<div class='print_friendly print_header'>" + headerHtml + '</div>');

        $('.inventory-heading').append("<div style='clear: both;'></div>");
      }
    }

    function printImages() {
      if (!$('.print_image').length) {
        var imagesHtml = '';
        var $homeSliderThumbs = $('#home-slider-thumbs');

        $homeSliderThumbs.find('li').slice(0, 6).each(function (index) {
          imagesHtml += $(this).html() + (index === 1 || index === 3 ? '<br>' : '');
        });

        var carInfo = $('.car-info').clone().html();

        $('.print_tabs').prepend("<div class='print_friendly print_image'>" + imagesHtml + "<br></div><div class='car-info'>" + carInfo + "</div><div style='clear: both;'></div>");
      }
    }

    $(document).on('click', '.add_mailchimp', function () {
      var $this = $(this);
      var email = $this.parent().find('.email').val();
      var list = $this.data('list');
      var nonce = $this.data('nonce');

      $.ajax({
        type: 'POST',
        url: listing_ajax.ajaxurl,
        data: {action: 'add_mailchimp', email: email, list: list, nonce: nonce},
        success: function (data) {
          $this.parent().find('.response').hide().html(data).fadeIn();
          $this.parent().find('.email').val('');
        }
      });
    });

    $(document).on('click', '.reset-search-form', function (e) {
      e.preventDefault();

      var $form = $(this).closest('form');

      var minMax = [];

      $form.find('.loading_results').show();

      $form.find('select').each(function () {
        if ($(this).prop('name').indexOf('[]') !== -1) {
          // create array for min/max values
          var name = $(this).prop('name').replace('[]', '');

          if (minMax.indexOf(name) === -1) {
            minMax.push(name);
          }
        }
      });

      $form.find("input[type='checkbox']").prop('checked', false);

      $.ajax({
        type: 'post',
        url: listing_ajax.ajaxurl,
        dataType: 'json',
        data: {action: 'search_box_shortcode_update_options', current: {}, min_max: minMax},
        success: function (response) {
          if (typeof response[0] === 'object' && !$.isEmptyObject(response[0]) && (
            typeof listing_ajax.filter_showall == 'undefined'
          )) {
            // update dropdowns with new values
            $.each(response[0], function (key, value) {
              // year workaround
              var originalKey = key;
              key = (key === 'year' ? 'yr' : key);

              var $select = $form.find("select[name^='" + key + "']");
              var prefix = (typeof $select.data('prefix') !== 'undefined' ? $select.data('prefix') + ' ' : '');

              // min and max
              if (typeof $select.prop('name') !== 'undefined' && $select.length === 2) {
                $select.each(function (selectIndex) {
                  var newOptions = "<option value=''>" + $select.eq(selectIndex).find('option').eq(0).text() + '</option>';
                  var currentOption = $(this).find('option:selected').data('key');

                  // $(this).selectbox('detach');

                  if (typeof value === 'object' && !$.isEmptyObject(value)) {
                    $.each(value, function (valueKey, valueValue) {
                      var valueLabel = valueValue;

                      if (typeof response[1][originalKey] !== 'undefined' && typeof response[1][originalKey][valueKey] !== 'undefined') {
                        valueLabel += ' (' + response[1][originalKey][valueKey] + ')';
                      }

                      newOptions += "<option value='" + htmlEscape(valueKey) + "' data-key='" + valueKey + "'" + (currentOption === valueKey ? "selected='selected'" : '') + '>' + valueLabel + '</option>';
                    });
                  } else {
                    newOptions += "<option value=''>" + $(this).data('no-options') + '</option>';
                  }

                  $(this).html(newOptions);
                });
              } else {
                var newOptions = "<option value=''>" + prefix + ($form.data('form') === 'singular' ? $select.data('label-singular') : $select.data('label-plural')) + '</option>';

                // $select.selectbox('detach');

                if (typeof value === 'object' && !$.isEmptyObject(value)) {
                  $.each(value, function (valueKey, valueValue) {
                    var valueLabel = valueValue;

                    if (typeof response[1][originalKey] !== 'undefined' && typeof response[1][originalKey][valueKey] !== 'undefined') {
                      valueLabel += ' (' + response[1][originalKey][valueKey] + ')';
                    }

                    newOptions += "<option value='" + htmlEscape(valueValue) + "' data-key='" + valueKey + "'>" + valueLabel + '</option>';
                  });
                } else {
                  newOptions += "<option value=''>" + $select.data('no-options') + '</option>';
                }

                $select.html(newOptions);

                if (typeof searchDropdowns[key] !== 'undefined') {

                  $.each(searchDropdowns[key], function (index, dropdown) {
                    dropdown.clear(true);
                    dropdown.sync();
                  });
                }
              }
            });
          } else {
            $.each(response[0], function (key, value) {
              key = (key === "year" ? "yr" : key);
              var $select = $form.find("select[name^='" + key + "']");

              if ($select.val()) {
                $select.prop('selectedIndex', false);
              }
            });
          }

          $.initSearchListingFilters();

          $form.find('.loading_results').hide();
        }
      });
    });

    function htmlEscape(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    function isEmpty(str) {
      return (!str || str.length === 0);
    }

    var listingDropdowns = {};
    var currentDropdownId = '';
    var isDeleting = false;

    function calculatePlaceholderWidth(placeholderText) {
      var _$tmpSpan = $('<span/>').html(placeholderText).css({
            position: 'absolute',
            left: -9999,
            top: -9999
          }).appendTo('body'),
          textWidth = _$tmpSpan.width();

      _$tmpSpan.remove();

      return textWidth;
    }

    $.initListingFilters = function initListingFilters(type, singular, value, label) {

      $('select.listing_filter:not(.tomselected)').each(function () {
        var name = $(this).attr('name');
        var isPrice = $(this).attr('name') === 'price_order';
        var isInline = $(this).hasClass('automotive-inline-dropdown');

        var selectAttrs = {
          create: false,
          closeAfterSelect: false,
          sortField: [{field: '$order'}, {field: '$score'}],
          maxOptions: null,

          onDelete: function (values, event) {
            for (var i = 0; i < values.length; i++) {
              $.removeListingFilter(name, values[i]);
            }

            isDeleting = true;

            $.updateListingResults(1, true);
          },
          plugins: {
            input_autogrow: {}
          },
          onInitialize: function () {
            var searchInput = this.focus_node;
            var text = searchInput.getAttribute('placeholder');

            searchInput.style.width = calculatePlaceholderWidth(text) + 'px';
            searchInput.style.minWidth = calculatePlaceholderWidth(text) + 'px';

            searchInput.disabled = true;

            if (typeof inventoryCurrentFilters !== 'undefined' && typeof inventoryCurrentFilters[name] !== 'undefined') {
              var allValues = Object.values(inventoryCurrentFilters[name].data);

              this.setValue(allValues, true);
            }
          }
        };

        if (typeof listing_ajax.filter_multiselect !== 'undefined' && listing_ajax.filter_multiselect) {
          if (!isPrice) {
            selectAttrs.plugins.checkbox_options = {};
          }
        }

        if (isInline) {
          selectAttrs.plugins.no_close = {};
        }

        var select = new window.TomSelect(this, selectAttrs);

        if (isInline) {
          select.open();
        }

        select.on("dropdown_open", function (dropdown) {
          currentDropdownId = $(dropdown).closest('.ts-wrapper').parent().find('select').attr('id');//dropdown.inputId;

          dropdown.style.width = 'auto';

          // Set a minimum dropdown width if the dropdown is thinner than the parent
          if (dropdown.offsetWidth < dropdown.parentElement.offsetWidth) {
            dropdown.style.width = dropdown.parentElement.offsetWidth + 'px';
          }
        });

        select.on('dropdown_close', function (dropdown) {
          this.focus_node.disabled = true;
        })

        // Disable the item select
        select.on("item_select", function (item) {
          item.classList.remove('active', 'last-active');
          item.parentElement.querySelector('input[type="select-multiple"]').blur();
        });

        select.on("change", function (value) {
          if (isDeleting) {
            isDeleting = false;

            return;
          }

          var values = (typeof value !== 'object' ? [value] : value);

          var input = this.input;
          var name = input.getAttribute('name');
          var singular = input.dataset.labelSingular;

          var existingTerms = (typeof Listing.filters[name] !== 'undefined' && Listing.filters[name].data !== 'undefined' ? Object.keys(Listing.filters[name].data) : []);

          if (values.length) {
            for (var i = 0; i < values.length; i++) {
              var singleValue = values[i];
              var option = input.querySelector('option[value="' + singleValue + '"]:checked');
              var urlValue = option.dataset.key;

              if (isEmpty(urlValue)) {
                $.removeListingFilter(name);
              } else {
                $.addListingFilter(name, singular, urlValue, htmlEscape(singleValue)); // last is label

                var foundKey = existingTerms.indexOf(urlValue);

                if (foundKey >= 0) {
                  existingTerms.splice(foundKey, 1);
                }
              }
            }

            if (existingTerms.length) {
              for (var termI = 0; termI < existingTerms.length; termI++) {
                $.removeListingFilter(name, existingTerms[termI]);
              }
            }
          } else {
            $.removeListingFilter(name);
          }

          $.updateListingResults(1, true);

          // This is for when dependancy is empty and we still need to set the current term
          $("select[name='" + name + "']").val(values);

          select.sync();
        });

        if (typeof listingDropdowns[name] === 'undefined') {
          listingDropdowns[name] = [];
        }

        listingDropdowns[name].push(select);
      });
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
      // Strip all characters but numerical ones.
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
      var n          = !isFinite(+number) ? 0 : +number,
          prec       = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep        = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec        = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s          = '',
          toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
          };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    }

    // function initListingRangeFilters() {
    $.initListingRangeFilters = function initListingRangeFilters() {
      if ($('.listing-range-slider').length) {

        $('.listing-range-slider').each(function () {
          var $container = $(this).closest(".listing-range-slider-container");

          var singular = $container.find('.singular-label').text();
          var type = $(this).data('type');
          var isCurrency = $(this).data('currency');

          var min = $(this).data('min');
          var max = $(this).data('max');
          var increment = $(this).data('increment');
          var values = [$(this).data('current-min'), $(this).data('current-max')];

          if ($('ul.filter li[data-type="' + (type === 'year' ? 'yr' : type) + '[]"]').length) {
            var $li = $('ul.filter li[data-type="' + (type === 'year' ? 'yr' : type) + '[]"]');

            values = [$li.data('min'), $li.data('max')]
          }

          var minLabel = values[0];
          var maxLabel = values[1];

          if (isCurrency) {
            var currencySymbol = $(this).data('currency-symbol');
            var thousandSeparator = $(this).data('thousand-sep');
            var decimalSeparator = $(this).data('decimal-sep');
            var decimals = $(this).data('decimals');

            minLabel = currencySymbol + number_format(minLabel, decimals, decimalSeparator, thousandSeparator);
            maxLabel = currencySymbol + number_format(maxLabel, decimals, decimalSeparator, thousandSeparator);
          }

          $(this).slider({
            range: true,
            step: increment,
            min: min,
            max: max,
            values: values,
            create: function (event, ui) {
              if (!$container.find('.min-label input').length) {
                $container.find('.min-label').html('<input type="text" class="min-label" value="' + minLabel + '">');
              }

              if (!$container.find('.max-label input').length) {
                $container.find('.max-label').html('<input type="text" class="max-label" value="' + maxLabel + '">');
              }
            },
            slide: function (event, ui) {
              var minLabel = ui.values[0];
              var maxLabel = ui.values[1];

              if (isCurrency) {
                minLabel = currencySymbol + number_format(minLabel, decimals, decimalSeparator, thousandSeparator);
                maxLabel = currencySymbol + number_format(maxLabel, decimals, decimalSeparator, thousandSeparator);
              }

              $container.find(".min-label").val(minLabel);
              $container.find(".max-label").val(maxLabel);

              if ($('.listing-range-slider[data-type="' + type + '"]')) {
                $('.listing-range-slider[data-type="' + type + '"]').slider("option", "values", [ui.values[0], ui.values[1]]);
                $('.listing-range-slider[data-type="' + type + '"]').parent().find('.min-label').val(minLabel);
                $('.listing-range-slider[data-type="' + type + '"]').parent().find('.max-label').val(maxLabel);
              }
            },
            stop: function (event, ui) {
              var minLabel = ui.values[0];
              var maxLabel = ui.values[1];

              if (isCurrency) {
                minLabel = currencySymbol + number_format(minLabel, decimals, decimalSeparator, thousandSeparator);
                maxLabel = currencySymbol + number_format(maxLabel, decimals, decimalSeparator, thousandSeparator);
              }

              $.addListingFilter(type, singular, [ui.values[0], ui.values[1]], minLabel + ' - ' + maxLabel);

              $container.find(".min-label").val(minLabel);
              $container.find(".max-label").val(maxLabel);

              $.updateListingResults();
            }
          })
        });
      }
    }

    $(document).on('input', 'input.max-label, input.min-label', function (e) {
      e.preventDefault();

      var $container = $(this).closest('.listing-range-slider-container');
      var maxLabel = origMaxLabel = $container.find('input.max-label').val();
      var minLabel = origMinLabel = $container.find('input.min-label').val();
      var $this = $container.find('.listing-range-slider');

      origMaxLabel = origMaxLabel.replace(/\D/g, '');
      origMinLabel = origMinLabel.replace(/\D/g, '');

      var singular = $container.find('.singular-label').text();
      var type = $this.data('type');
      var isCurrency = $this.data('currency');
      var min = $this.data('min');
      var max = $this.data('max');

      // if(parseInt(minLabel) <= min && parseInt(maxLabel) >= max){
      if (isCurrency) {
        var currencySymbol = $this.data('currency-symbol');
        var thousandSeparator = $this.data('thousand-sep');
        var decimalSeparator = $this.data('decimal-sep');
        var decimals = $this.data('decimals');
      }


      if (isCurrency) {
        minLabel = currencySymbol + number_format(minLabel, decimals, decimalSeparator, thousandSeparator);
        maxLabel = currencySymbol + number_format(maxLabel, decimals, decimalSeparator, thousandSeparator);
      }

      var sliderValue = $this.slider("option", "values");

      $.addListingFilter(type, singular, [origMinLabel, origMaxLabel], minLabel + ' - ' + maxLabel);

      $(".listing-range-slider-container .listing-range-slider[data-type='" + type + "'] input.min-label").val(minLabel);
      $(".listing-range-slider-container .listing-range-slider[data-type='" + type + "'] input.max-label").val(maxLabel);

      if ($('.listing-range-slider[data-type="' + type + '"]')) {
        $('.listing-range-slider[data-type="' + type + '"]').slider("option", "values", [origMinLabel, origMaxLabel]);
        $('.listing-range-slider[data-type="' + type + '"]').parent().find('.min-label').val(minLabel);
        $('.listing-range-slider[data-type="' + type + '"]').parent().find('.max-label').val(maxLabel);
      }

      $.updateListingResults();
      // }
    });

    document.addEventListener('wpcf7mailsent', function (event) {
      setTimeout(function () {
        $.fancybox.close();
      }, 2000);
      $(window).trigger('resize');
    }, false);


    document.addEventListener('wpcf7invalid', function (event) {
      $(window).trigger('resize');
    }, false);

    // select box
    if ($('.listing_filter').length) {
      $.initListingFilters();
    }

    $.initListingRangeFilters();

    $.automotiveComparisonRowHeights();

    $.initProgressBars();


    $.initFeaturedBrands = function initFeaturedBrands() {
      var $featuredSlider = $('.featured-brand');

      if ($featuredSlider.length) {
        $featuredSlider.each(function () {
          var $next = $(this).find('.slideControls>.next-btn');
          var $prev = $(this).find('.slideControls>.prev-btn');

          $(this).find('.featured_slider').bxSlider({
            slideWidth: 155,
            minSlides: 1,
            maxSlides: 6,
            slideMargin: 30,
            infiniteLoop: true,
            pager: false,
            touchEnabled: false,
            nextSelector: $next,
            prevSelector: $prev
          });
        });
      }
    }

    $.initFeaturedBrands();


    //* *******************************************
    //  Inview
    //* **********************************************************
    $('i.fa[data-animated]').css('opacity', 0);

    var $fancyboxDiv = $('a.fancybox_div, .fancybox_div_menu a, .elementor-element.fancybox_div a');

    if ($fancyboxDiv.length) {
      $fancyboxDiv.each(function () {
        $(this).fancybox({
          'width': '620',
          'autoDimensions': false
        });
      });
    }

    // testimonial slider
    $.initTestimonialSlider = function initTestimonialSlider() {
      var $testimonialSlider = $('.testimonial_slider');

      if ($testimonialSlider.length) {
        $testimonialSlider.bxSlider({
          mode: 'horizontal',
          slideMargin: 3,
          minSlides: 1,
          maxSlides: 1,
          auto: true,
          autoHover: true,
          speed: 500,
          pager: false,
          touchEnabled: false,
          controls: false
        });
      }
    }

    $.initTestimonialSlider();


    $.initRecentBlogPosts = function initRecentBlogPosts() {
      var $recentBlogPosts = $('.recent_blog_posts');

      if ($recentBlogPosts.length) {
        $recentBlogPosts.each(function () {
          var controls = $(this).data('controls');
          var showPosts = $(this).data('showposts');

          $(this).bxSlider({
            mode: 'vertical',
            moveSlides: 1,
            auto: false,
            speed: 500,
            pager: false,
            minSlides: showPosts,
            maxSlides: showPosts,
            nextSelector: '.' + controls,
            prevSelector: '.' + controls,
            nextText: '<i class="fa fa-chevron-up"></i>',
            prevText: '<i class="fa fa-chevron-down"></i>',
            touchEnabled: false,
            adaptiveHeight: true
          });
        });
      }
    }

    $.initRecentBlogPosts();


    var $flexslider2 = $('.flexslider2');

    if ($flexslider2.length) {
      $flexslider2.flexslider({
        animation: 'slide',
        directionNav: true,
        controlNav: false,
        prevText: '',
        nextText: '',
        rtl: $('body').hasClass('rtl'),
        smoothHeight: true
      });
    }

    $.initFAQ = function initFAQ() {
      if ($('.faq').length) {
        var hash = window.location.hash.substring(1); // Puts hash in variable, and removes the # character

        // faq sort
        if ($('.faq-sort').length) {
          // faq shortcode
          if (window.location.hash) {
            $('.list_faq a').each(function () {
              if ($(this).text().indexOf(hash) !== -1) {
                $(this).parent().addClass('active');
              }
            });

            if (hash !== 'All') {
              $('.faq-sort div.panel').each(function () {
                var inCategories = $(this).data('categories');

                if (inCategories.indexOf(hash) === -1) {
                  $(this).hide({effect: 'fold', duration: 600});
                } else {
                  $(this).show({effect: 'fold', duration: 600});
                }
              });
            }
          } else {
            $('.list_faq li').first().addClass('active');
          }
        }

        // faq shortcode
        if (window.location.hash) {
          $('.sort_container a').each(function () {
            if ($(this).text().indexOf(hash) !== -1) {
              // $(this).css('font-weight', 'bold');
            }
          });

          if (hash !== 'All') {
            $('.faq .accordion-group').each(function () {
              var inCategories = $(this).data('categories');

              if (inCategories.indexOf(hash) === -1) {
                $(this).hide({effect: 'fold', duration: 600});
              } else {
                $(this).show({effect: 'fold', duration: 600});
              }
            });
          }
        }

        $('.faq .accordion-toggle').click(function () {
          var href = $(this).prop('href');
          var $grayButtonI = $("a[href='" + href + "'] .gray_button i");

          if ($grayButtonI.hasClass('fa-minus')) {
            $grayButtonI.removeClass('fa-minus').addClass('fa-plus');
          } else {
            $('.faq .gray_button i.fa-minus').removeClass('fa-minus').addClass('fa-plus');
            $grayButtonI.removeClass('fa-plus').addClass('fa-minus');
          }
        });

        var $aSort = $("a[data-action='sort']");

        $aSort.click(function () {
          var category = $(this).attr('href').replace('#', '');
          var faqs = $('.faq .accordion-group');

          $('.sort_container a').each(function () {
            $(this).css('font-weight', 'normal');
          });

          if (category === 'All') {
            faqs.each(function () {
              $(this).show({effect: 'fold', duration: 600});
            });
          } else {
            faqs.each(function () {
              var inCategories = $(this).data('categories');

              if (inCategories.indexOf(category) === -1) {
                $(this).hide({effect: 'fold', duration: 600});
              } else {
                $(this).show({effect: 'fold', duration: 600});
              }
            });
          }
        });

        $aSort.click(function (e) {
          e.preventDefault();

          var category = $(this).attr('href').replace('#', '');
          var faqs = $('.faq div.panel');

          $('.list_faq li.active').removeClass('active');

          $(this).parent().addClass('active');

          if (category === 'All') {
            faqs.each(function () {
              $(this).show({effect: 'fold', duration: 600});
            });
          } else {
            faqs.each(function () {
              var inCategories = $(this).data('categories');

              if (inCategories.indexOf(category) === -1) {
                $(this).hide({effect: 'fold', duration: 600});
              } else {
                $(this).show({effect: 'fold', duration: 600});
              }
            });
          }
        });
      }
    }

    $.initFAQ();

    // social likes
    var $listingShare = $('.social-likes.listing_share');
    if ($listingShare.length) {
      $listingShare.socialLikes({
        zeroes: 'yes'
      });
    }

    $('.search_inventory_box form').submit(function () {
      $(this).find('select option:selected').each(function () {
        var key = $(this).data('key');

        if (key) {
          $(this).val(key);
        }
      });

      $(this).find('select,input:not([type="select-one"])').each(function () {
        if ($(this).val() === '' && $(this).prop('name').slice(-2) !== '[]') {
          $(this).prop('disabled', true);
        }
      });
    });

    // select box
    var searchDropdowns = {};
    $.initSearchListingFilters = function initSearchListingFilters() {
      var showAllTerms = (typeof listing_ajax.filter_showall !== 'undefined');

      $('select.css-dropdowns:not(.tomselected)').each(function () {
        var name = $(this).attr('name');
        var isMulti = (typeof listing_ajax.filter_multiselect !== 'undefined' && listing_ajax.filter_multiselect);
        var isMinMax = ($(this).attr('name') && $(this).attr('name').indexOf('[]') >= 0 && !$(this).attr('multiple'));
        var selectAttrs = {
          create: false,
          closeAfterSelect: !isMulti,
          // searchField: null,
          // controlInput: null,
          sortField: [{field: '$order'}, {field: '$score'}],
          plugins: {},
          maxOptions: null,
          onInitialize: function () {
            var searchInput = this.focus_node;
            var length = searchInput.getAttribute('placeholder');

            if (length) {
              searchInput.setAttribute('size',
                length.length - 1
              );
            }

            searchInput.disabled = true;
          }
        };

        if (isMulti && !isMinMax) {
          selectAttrs.plugins.checkbox_options = {};
        } else if (isMinMax) {
          selectAttrs.closeAfterSelect = true;
          selectAttrs.hidePlaceholder = true;
        }

        var select = new window.TomSelect(this, selectAttrs);

        select.on("dropdown_open", function (dropdown) {
          currentDropdownId = $(dropdown).closest('.ts-wrapper').parent().find('select').attr('id');//dropdown.inputId;

          dropdown.style.width = 'auto';

          // Set a minimum dropdown width if the dropdown is thinner than the parent
          if (dropdown.offsetWidth < dropdown.parentElement.offsetWidth) {
            dropdown.style.width = dropdown.parentElement.offsetWidth + 'px';
          }
        });

        // Disable the item select
        select.on("item_select", function (item) {
          item.classList.remove('active', 'last-active');
          item.parentElement.querySelector('input[type="select-multiple"]').blur();
        });

        select.on("change", function (value) {
          if (isDeleting) {
            isDeleting = false;

            return;
          }

          var $input = $(this.input);

          if ($input.data('update') !== false) {
            var $form = $input.closest('form');
            var currentlySelected = {};

            var minMax = [];

            if (!showAllTerms) {
              $form.find('.loading_results').show();
            }

            $form.find('select').each(function () {
              var selectName = $(this).prop('name');
              var $selectedOptions = $(this).find('option:selected');

              $selectedOptions.each(function () {
                var selectedOption = $(this);

                if (selectedOption.val()) {
                  if (selectName.indexOf('[]') === -1) {
                    currentlySelected[selectName] = selectedOption.data('key');
                  } else {
                    // create array for min/max values
                    var name = selectName.replace('[]', '');

                    if (typeof currentlySelected[name] !== 'object') {
                      currentlySelected[name] = [];
                    }

                    currentlySelected[name].push(selectedOption.data('key'))

                    if (minMax.indexOf(name) === -1 && $('select[name^="' + name + '"]').length === 2) {
                      minMax.push(name);
                    }
                  }
                }
              });
            });

            $.ajax({
              type: 'post',
              url: listing_ajax.ajaxurl,
              dataType: 'json',
              data: {
                action: 'search_box_shortcode_update_options',
                current: currentlySelected,
                min_max: minMax
              },
              success: function (response) {
                if (typeof response[0] === 'object' && !$.isEmptyObject(response[0]) && (
                  !showAllTerms || typeof automotiveCustomDisableSearchUpdater !== 'undefined'
                )) {
                  if (typeof response !== 'undefined' && typeof response[0] === 'object') {
                    // update dropdowns with new values

                    $.each(response[0], function (key, value) {
                      if (typeof listing_ajax.listing_terms !== 'undefined') {
                        var testKey = (key == 'year' ? 'yr' : key);

                        // if the value is selected we don't want to overwrite the terms
                        if (typeof currentlySelected[testKey] === 'undefined') {
                          updateDropdownTerms(testKey, value, false, response[1]);
                        }

                      } else {
                        var testKey = (key == 'year' ? 'yr' : key);

                        updateDropdownTerms(testKey, value, false, response[1], (
                          typeof currentlySelected[testKey] !== 'undefined' ? currentlySelected[testKey] : false
                        ));
                      }

                    });
                  }
                }

                $.initSearchListingFilters();

                $form.find('.loading_results').hide();
              }
            });
          }
        });

        if (typeof name !== 'undefined') {
          name = name.replace("[]", "");
        }

        if (typeof searchDropdowns[name] === 'undefined') {
          searchDropdowns[name] = [];
        }

        searchDropdowns[name].push(select);

      });
    }

    $.initSearchListingFilters();

    var $myTab = $('#myTab');
    var $myTabA = $myTab.find('a');

    if ($myTabA.length) {
      $myTab.find('a:first').tab('show');
    }

    $.convertImgToBase64 = function convertImgToBase64(url, callback, outputFormat) {
      var canvas = document.createElement('CANVAS');
      var ctx = canvas.getContext('2d');
      var img = new Image();

      img.crossOrigin = 'Anonymous';
      img.onload = function () {
        var dataURL;
        canvas.height = img.height;
        canvas.width = img.width;
        ctx.drawImage(img, 0, 0);
        dataURL = canvas.toDataURL(outputFormat);
        callback.call(this, dataURL);
        canvas = null;
      };

      img.src = url;
    }

    // pregenerate base images


    $.automotiveQuoteReplace = function smarten(text) {
      text = text.replace('″', '"');
      text = text.replace('“', '"');
      text = text.replace('”', '"');
      text = text.replace('‘', "'");
      text = text.replace('’', "'");

      return text;
    }

    var pdfTimer = false;

    function checkIfPDFReady() {
      var ready = true;

      $.each(Listing.pdfPreloadActions, function (action, loaded) {
        if (!loaded) {
          ready = false;

          return false;
        }
      });

      return ready;
    }


    // generate pdf
    $('.generate_pdf').click(function (e) {
      e.preventDefault();

      $.preloadPDFImages();

      var $parent = $(this).parent();
      var maxGenTime = 30;
      var currentGenTime = 0;

      $parent.addClass('generating_pdf');

      // check if all images are preloaded
      if (!pdfTimer) {
        pdfTimer = setInterval(function () {

          if (checkIfPDFReady()) {
            clearInterval(pdfTimer);
            pdfTimer = false;

            // generate pdf
            var doc = new jsPDF();

            // properties
            doc.setProperties({
              title: 'Inventory Listing',
              creator: 'ThemeSuite'
            });

            $.printListingPDF(doc);

            $parent.removeClass('generating_pdf');

            return;
          } else {
            console.log('preloading pdf...');

            currentGenTime++;
          }

          // kill check after 30s, log preload results
          if (currentGenTime >= maxGenTime) {
            alert('error generating PDF');
            clearInterval(pdfTimer);
            pdfTimer = false;

            $parent.removeClass('generating_pdf');
            $parent.addClass('error_generating_pdf');

            console.log('pdf preload results', Listing.pdfPreloadActions);
          }

        }, 1000);
      }
    });

    var gallery;
    var showAllPhotos = (typeof listing_ajax.show_all_photos !== 'undefined' && listing_ajax.show_all_photos);

    var initPhotoSwipeFromDOM = function (gallerySelector) {

      // parse slide data (url, title, size ...) from DOM elements
      // (children of gallerySelector)
      var parseThumbnailElements = function (el) {
        var thumbElements = el.childNodes,
            numNodes      = thumbElements.length,
            items         = [],
            figureEl,
            linkEl,
            size,
            item,
            width,
            height;

        var isInventory = (gallerySelector.charAt(0) !== '#' ? true : false);

        if (isInventory) {
          var imagePreview = el.getElementsByClassName('preview')[0];
          items = JSON.parse(imagePreview.getAttribute('data-gallery-images'));

        } else {
          for (var i = 0; i < numNodes; i++) {

            figureEl = thumbElements[i]; // <figure> element

            // include only element nodes
            if (figureEl.nodeType !== 1) {
              continue;
            }


            linkEl = figureEl.children[0]; // <a> element


            width = linkEl.getAttribute('data-full-image-width');
            height = linkEl.getAttribute('data-full-image-height');

            // create slide object
            item = {
              src: linkEl.getAttribute('data-full-image'),
              w: width,
              h: height
            };

            if (figureEl.children.length > 1) {
              // <figcaption> content
              item.title = figureEl.children[1].innerHTML;
            }

            if (linkEl.children.length > 0) {
              // <img> thumbnail element, retrieving thumbnail url
              // item.msrc = linkEl.children[0].getAttribute('src');
              item.msrc = linkEl.getAttribute('full-image');
            }

            item.el = figureEl; // save link to element for getThumbBoundsFn
            items.push(item);
          }

        }
        return items;
      };

      // find nearest parent element
      var closest = function closest(el, fn) {
        return el && (fn(el) ? el : closest(el.parentNode, fn));
      };

      // triggers when user clicks on thumbnail
      var onThumbnailsClick = function (e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var isInventory = (gallerySelector.charAt(0) !== '#' ? true : false);

        var eTarget = e.target || e.srcElement;

        // find root element of slide
        if (isInventory) {
          var clickedListItem = closest(eTarget, function (el) {
            return (el.tagName && el.tagName.toUpperCase() === 'IMG');
          });
        } else {
          var clickedListItem = closest(eTarget, function (el) {
            return (el.tagName && (el.className != 'flex-nav-prev' && el.className != 'flex-nav-next') && el.tagName.toUpperCase() === 'LI');
          });
        }

        if (!clickedListItem) {
          return;
        }

        // find index of clicked item by looping through all child nodes
        // alternatively, you may define index via data- attribute
        var clickedGallery = clickedListItem.parentNode;

        if (isInventory) {
          index = 0;
        } else {
          var childNodes    = clickedListItem.parentNode.childNodes,
              numChildNodes = childNodes.length,
              nodeIndex     = 0,
              index;

          for (var i = 0; i < numChildNodes; i++) {
            if (childNodes[i].nodeType !== 1) {
              continue;
            }

            if (childNodes[i] === clickedListItem) {
              index = nodeIndex;
              break;
            }
            nodeIndex++;
          }
        }

        if (index >= 0) {
          // open PhotoSwipe if valid index found
          openPhotoSwipe(index, clickedGallery);
        }
        return false;
      };

      // parse picture index and gallery index from URL (#&pid=1&gid=2)
      var photoswipeParseHash = function () {
        var hash   = window.location.hash.substring(1),
            params = {};

        if (hash.length < 5) {
          return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
          if (!vars[i]) {
            continue;
          }
          var pair = vars[i].split('=');
          if (pair.length < 2) {
            continue;
          }
          params[pair[0]] = pair[1];
        }

        if (params.gid) {
          params.gid = parseInt(params.gid, 10);
        }

        return params;
      };

      var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            options,
            items;

        var isInventory = (gallerySelector.charAt(0) !== '#' ? true : false);

        items = parseThumbnailElements(galleryElement);

        // pre-load the images to get height and widths
        var deferreds = [];

        $.each(items, function (index, value) {
          var deferred = $.Deferred();
          deferreds.push(deferred);

          $("<img/>", {
            load: function () {
              items[index].w = this.naturalWidth;
              items[index].h = this.naturalHeight;

              deferred.resolve();
            },
            src: value.src
          });
        });

        $.when.apply($, deferreds).done(function () {
          setTimeout(function () {
            window.dispatchEvent(new Event('resize'));
          }, 500);
        });

        // define options (if needed)
        options = {
          history: false,
          galleryUID: galleryElement.getAttribute('data-pswp-uid'),
          getThumbBoundsFn: function (index) {
            // See Options -> getThumbBoundsFn section of documentation for more info
            if (isInventory) {
              var thumbnail = galleryElement.getElementsByTagName('img')[0];
            } else if (typeof items[index].el !== 'undefined') {
              var thumbnail = items[index].el.getElementsByTagName('img')[0]; // find thumbnail
            }

            if (typeof thumbnail !== 'undefined') {
              var pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                  rect        = thumbnail.getBoundingClientRect();

              return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
            }
          }

        };

        if (showAllPhotos) {
          options.closeOnScroll = false;
          options.closeOnVerticalDrag = false;
          options.preventDragEvent = false;
        }

        // PhotoSwipe opened from URL
        if (fromURL) {
          if (options.galleryPIDs) {
            // parse real index when custom PIDs are used
            // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
            for (var j = 0; j < items.length; j++) {
              if (items[j].pid == index) {
                options.index = j;
                break;
              }
            }
          } else {
            // in URL indexes start from 1
            options.index = parseInt(index, 10) - 1;
          }
        } else {
          options.index = parseInt(index, 10);
        }

        // exit if index not found
        if (isNaN(options.index)) {
          return;
        }

        if (showAllPhotos) {
          options.showAnimationDuration = 0;
        }

        // first image is always shown
        if (isInventory) {
          options.index = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);

        if (showAllPhotos) {
          gallery.listen('afterInit', function (index, item) {
            var allPhotoHTML = '';
            var galleryItems = gallery.items;

            if (galleryItems.length) {
              for (var i = 0; i < galleryItems.length; i++) {
                allPhotoHTML += '<li class="single-photo"><a href="#" data-index="' + i + '"><img src="' + galleryItems[i].src + '"></a></li>';
              }
            }

            allPhotoHTML = '<ul class="all-photos-container">' + allPhotoHTML + '</ul>';

            gallery.items.push({
              html: allPhotoHTML
            });
            gallery.invalidateCurrItems();
            gallery.updateSize(true);

            $('.photoswipe-all-photos .total-photos').text(galleryItems.length - 1);
          });

          gallery.listen('afterChange', function () {
            var currentIndex = gallery.getCurrentIndex();
            var totalIndex = (gallery.items.length - 1);

            if (currentIndex == totalIndex) {
              $('.photoswipe-all-photos').addClass('bold');
            } else {
              $('.photoswipe-all-photos').removeClass('bold');
            }
          });
        }

        gallery.init();
      };

      // loop through all gallery elements and bind events
      if (gallerySelector.charAt(0) == '#') {
        var galleryElements = document.querySelectorAll(gallerySelector);
      } else {
        var galleryElements = $('.' + gallerySelector + ' img.preview');
      }

      for (var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i + 1);
        galleryElements[i].onclick = onThumbnailsClick;
      }

      // Parse URL and open gallery if it contains #&pid=3&gid=1
      var hashData = photoswipeParseHash();
      if (hashData.pid && hashData.gid) {
        openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
      }
    };

    $('.pswp__container').on('wheel mousewheel DOMMouseScroll', '.all-photos-container', function (e) {
      // Stop the event propagation bubbling up to the existing photoswipe listener.
      e.stopPropagation();
    });

    $(document).on('click tap', '.all-photos-container .single-photo a', function (e) {
      e.preventDefault();

      gallery.goTo($(this).data('index'));
    });

    $(document).on('click tap', '.photoswipe-all-photos', function (e) {
      e.preventDefault();

      gallery.goTo(gallery.items.length - 1);
    });

    $(document).on('click tap', '.show-all-photos', function (e) {
      e.preventDefault();

      $("#home-slider-canvas li:first-of-type img").trigger('click');

      gallery.goTo(gallery.items.length - 1);
    });

    // execute above function
    initPhotoSwipeFromDOM('#home-slider-canvas');

    function initializePhotoswipeGalleries() {
      if ($('.has-photoswipe').length) {
        initPhotoSwipeFromDOM('has-photoswipe');
      }
    }

    initializePhotoswipeGalleries();

    $.addListingAction(initializePhotoswipeGalleries);


    $.addFormAction = function addListingAction(callback) {
      Listing.formCallbacks.push(callback);
    };

    $(document).on('submit', '.ajax_form', function (e) {
      e.preventDefault();

      var emptyInput = false;
      var isRecaptcha = ('#recaptcha_area').length;
      var borderCSS = '1px solid #F00';
      var formName = $(this).attr('name');

      // required fields check
      $(this).find("*:not(input[type='submit']):not(input[type='select-one'])").filter(':input').each(function () {
        if (!$(this).val()) {
          emptyInput = true;

          $(this).css('border', borderCSS);
        } else {
          $(this).prop('style', '');
        }
      });

      // check recaptcha
      if (isRecaptcha) {
        var recaptchaResponseField = $(this).find("textarea[name='g-recaptcha-response']").val();

        // add red error border on visible recaptcha element
        if (!recaptchaResponseField) {
          $(this).find('.recaptcha_holder > div').css('border', borderCSS)
        } else {
          $(this).find('.recaptcha_holder > div').css('border', 'none');
        }
      }

      if (typeof listing_ajax.gdpr_form !== 'undefined' && formName !== 'email_friend') {
        var $gdprCheckbox = $(this).find('input[name="gdpr"]');

        if (!$gdprCheckbox.is(":checked")) {
          $gdprCheckbox.closest('.styled_input').css('border', borderCSS);

          emptyInput = true;
        } else {
          $gdprCheckbox.closest('.styled_input').prop('style', '');
        }
      }

      if (emptyInput === false) {
        var formNonce = $(this).data('nonce');
        var formValues = $(this).serializeArray();
        var currentForm = $(this);

        var formData = {
          action: 'listing_form',
          nonce: formNonce,
          form: formName
        };

        // form values into object
        $(formValues).each(function (index, obj) {
          formData[obj.name] = obj.value;
        });

        if (typeof listing_ajax.listing_id !== 'undefined') {
          formData['id'] = listing_ajax.listing_id;
        }

        $('.loading_icon_form').css('display', 'inline-block');

        $.ajax({
          type: 'POST',
          url: listing_ajax.ajaxurl,
          dataType: 'json',
          data: formData,
          success: function (data) {
            if (data.status === 'success') {
              $.fancybox.close();

              $('.inventory-listing .listing-slider').before("<div id='email_success_sent' class='alert alert-success' style='display: none;'>" + listing_ajax.email_success + '</div>');

              $('#email_success_sent').slideDown();

              setTimeout(function () {
                $('#email_success_sent').slideUp(function () {
                  $(this).remove();
                });
              }, 3000);
            } else {
              var $errorList = $('.error_list');

              if ($errorList.length) {
                $errorList.remove();
              }

              currentForm.prepend(data.message);
            }

            $('.loading_icon_form').fadeOut();

            // run any form callbacks
            if (typeof Listing.formCallbacks !== 'undefined' && Listing.formCallbacks.length > 0) {
              $.each(Listing.formCallbacks, function (key, value) {
                value({formData: formData, response: data});
              });
            }

          }
        });
      } else {
        $('.loading_icon_form').fadeOut();
      }
    });

    // used in CTA area
    $(document).on('click', '.action_button', function () {
      if ($(this).hasClass('fancybox_div')) {
        var form_id = $(this).prop('href');

        if ($(form_id).find('.recaptcha_holder').length) {
          automotiveInitRecaptcha($(form_id).find('.recaptcha_holder').prop('id'));
        }
      }
    });

    if (typeof listing_ajax.listing_id !== 'undefined') {
      var hiddenInputId = "<input type='hidden' name='_listing_id' value='" + listing_ajax.listing_id + "'>";

      // if a form is in the footer...
      $('form.wpcf7-form').append(hiddenInputId);
    }

    $('.content-nav .gradient_button').click(function (e) {
      if (!$(this).hasClass('print')) {
        var form_id = $(this).find('a').prop('href');

        if (form_id.charAt(0) === '#' && $(form_id).find('.recaptcha_holder').length) {
          automotiveInitRecaptcha($(form_id).find('.recaptcha_holder').prop('id'));
        }
      } else {
        e.preventDefault();

        window.print();
      }
    });

    // generate print before click todo: change to use async
    if ($('.content-nav .gradient_button.print')) {
      printTabs();
      printHeader();
      printImages();

      // remove any sort of duplicate schema from print friendly versions of page
      if ($('.print_friendly [itemprop]').length) {
        $('.print_friendly [itemprop]').each(function () {
          $(this).removeAttr('itemprop');
        });
      }
    }

    // hover image stuff.
    var imageSwap = function () {
      var $this = $(this);
      var newSource = $this.data('hoverimg');
      $this.data('hoverimg', $this.css('background-image'));
      $this.css('background-image', (newSource.indexOf('url(') > -1 ? newSource : 'url(' + newSource + ')'));
    };

    $('.hoverimg [data-hoverimg]').hover(imageSwap, imageSwap);

    // featured panels needs a different hover
    var featuredSwap = function () {
      var $this = $(this).find('[data-hoverimg]');
      var newSource = $this.data('hoverimg');
      $this.data('hoverimg', $this.prop('src'));
      $this.prop('src', newSource);
    };

    $('.automotive-featured-panel').hover(featuredSwap, featuredSwap);

    $(document).on('click', '.carousel-slider3 .slide img', function () {
      window.location = $(this).parent().find('a').prop('href');
    });

    $(document).on({
      mouseenter: function () {
        $(this).stop(true, true).animate({height: 215}, 400);

        $(this).find('.hover_hint').stop(true, true).fadeOut(200);
      },
      mouseleave: function () {
        $(this).stop(true, true).animate({height: 90}, 400);

        $(this).find('.hover_hint').stop(true, true).delay(400).fadeIn();
      }
    }, '#featured_vehicles_widget');

    $(window).trigger('resize');

    /* Featured Vehicle Slider */
    var $fvs = $('#featured_vehicles_widget');
    var $fvsUl = $fvs.find('ul.listings');
    var $fvsNext = $fvs.find('.next');

    if ($fvsUl.length) {
      $fvsUl.bxSlider({
        mode: 'vertical',
        pager: false,
        minSlides: 2,
        maxSlides: 2,
        controls: true,
        nextSelector: $fvsNext,
        touchEnabled: false,
        nextText: $fvsNext.data('next-text')
      });
    }

    $(document).on('click', '.vc_tta-tab', function () {
      $.each(Listing.vehicleSliders, function () {
        this.redrawSlider();
      })
    });

    $('.twitterfeed').each(function () {
      var modpath = $(this).data('modpath');
      var count = $(this).data('count');
      var loading_text = $(this).data('loading-text');
      var username = $(this).data('username');

      $(this).tweet({
        modpath: modpath,
        count: count,
        loading_text: loading_text,
        username: username
      });

    });


    // we can grab image height and width for photoswipe once they've loaded without making extra calls
    if (typeof listing_ajax.is_hotlink !== 'undefined') {
      $('#home-slider-canvas li img').on('load', function () {
        $(this).prop('data-full-image-width', this.naturalWidth);
        $(this).prop('data-full-image-height', this.naturalHeight);
      }).each(function () {
        if (this.complete) $(this).load();
      });
    }
  });

  // have to wait until DOM is fully loaded (images too)
  $(window).on('load', function ($) {
    $ = jQuery;
    var $body = $('body');

    // parallax effect
    $.initParallax = function initParallax() {
      var $parallaxScroll = $('.parallax_scroll');

      if ($parallaxScroll.length) {
        $parallaxScroll.parallax({speed: 0.15});

        $('.parallax_parent').each(function () {
          $(this).height($(this).find('.parallax_scroll').height());
        });
      }

      if ($('.elementor-parallax').length) {
        $('.elementor-parallax').parallax({speed: 0.15, offset: -300});
      }
    }

    // comparison image
    var sizes = [];

    if ($('.comparison-container').length) {
      $('.comparison-container .comparison-img').each(function () {
        sizes.push($(this).height());
      });

      $('.comparison-container .comparison-img').height(Math.max.apply(Math, sizes));
    }

    $.initParallax();

    // fullwidth content
    var $fullwidthElement = $('.fullwidth_element');

    if ($fullwidthElement.length) {
      $fullwidthElement.each(function () {
        $(this).height(($(this).hasClass('bottom_element') ? ($(this).find('>:first-child').height() - 70) : $(this).find('>:first-child').height()));

        // var windowWidth = $(window).width();
        var windowWidth = $(document).width();
        if ($('.boxed_layout').length) {
          windowWidth = $('.boxed_layout').width();
        }

        var fullwidthCSS = {
          'width': windowWidth
        };

        if ($('body').hasClass('rtl')) {
          fullwidthCSS['margin-right'] = -(windowWidth / 2);
          fullwidthCSS['right'] = '50%';
        } else {
          fullwidthCSS['margin-left'] = -(windowWidth / 2);
          fullwidthCSS['left'] = '50%';
        }

        $(this).find('> .wpb_column').css(fullwidthCSS);
      });
    }

    $.initFlippingCards();

    // throttle for parallax resize
    function debounce(func, wait, immediate) {
      var timeout;
      return function () {
        var context = this;
        var args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    }

    var resizeParallax = debounce(function () {
      $.automotiveComparisonRowHeights();

      // also, for fullwidth elements
      $('.fullwidth_element').each(function () {
        $(this).height(($(this).hasClass('bottom_element') ? ($(this).find('>:first-child').height() - 70) : $(this).find('>:first-child').height()));

        var windowWidth = $(document).width();
        if ($('.boxed_layout').length) {
          windowWidth = $('.boxed_layout').width();
        }

        var fullwidthCSS = {
          'width': windowWidth,
          'left': '50%'
        };

        if ($('body').hasClass('rtl')) {
          fullwidthCSS['margin-right'] = -(windowWidth / 2)
          fullwidthCSS['right'] = '50%';
        } else {
          fullwidthCSS['margin-left'] = -(windowWidth / 2)
          fullwidthCSS['left'] = '50%';
        }

        $(this).find('> .wpb_column').css(fullwidthCSS);

        // PARALLAX HEIGHT
        if ($(this).find('.parallax_parent')) {
          $(this).find('.parallax_parent').height($(this).find('.parallax_scroll').height());
        }
      });

      $('.parallax_parent').each(function () {
        $(this).height($(this).find('.parallax_scroll').height());
      });

      $.initFlippingCards();

      var $optionTickList = $('.option-tick-list');

      if ($optionTickList.length) {
        $optionTickList.evenElements();
      }
    }, 250);

    window.addEventListener('resize', resizeParallax);
    window.addEventListener('orientationchange', resizeParallax);// orientationchange

    // inventory listing slider
    var $homeSliderThumbs = $('#home-slider-thumbs');
    var $homeSliderCanvas = $('#home-slider-canvas');
    var $listingSlider = $('.listing-slider');

    if ($homeSliderThumbs.length) {
      var itemWidth = $homeSliderThumbs.data('item-width') || 171;
      var itemMargin = $homeSliderThumbs.data('item-margin') || 0;

      var thumbDirectionNav = $listingSlider.data('thumb-direction-nav') || true;

      $homeSliderThumbs.flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: thumbDirectionNav,
        animationLoop: false,
        slideshow: false,
        itemWidth: itemWidth,
        itemMargin: itemMargin,
        prevText: '',
        nextText: '',
        asNavFor: '#home-slider-canvas',
        rtl: $body.hasClass('rtl')
      });
    }

    if ($homeSliderCanvas.length) {
      var canvasDirectionNav = $listingSlider.data('canvas-direction-nav') || false;

      $homeSliderCanvas.flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: canvasDirectionNav,
        animationLoop: false,
        slideshow: false,
        smoothHeight: true,
        sync: '#home-slider-thumbs',
        rtl: $body.hasClass('rtl'),
        start: function (slider) {
          var $homeSliderCanvas = $('#home-slider-canvas');

          slider.removeClass('loading');
        }
      });
    }

    if ($('.fancybox').length && $.fn.fancybox) {
      $('.fancybox').fancybox({
        tpl: {
          next: '<a class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
          prev: '<a class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
        }
      });
    }

    var map, marker;

    var $myTab = $('#myTab');

    $myTab.find('a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');

      if ($(this).prop('href') === '#location') {
        setTimeout(function () {
          $.initGoogleMap();
          google.maps.event.trigger(map, 'resize');
        }, 500);
      }
    });

    $.initGoogleMap();

    // preload images
    $('.hoverimg [data-hoverimg]').each(function () {
      $('<img/>')[0].src = $(this).data('hoverimg');
    });
  });
})(jQuery);


var Base64 = {
  _keyStr: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
  encode: function (e) {
    var t = '';
    var n, r, i, s, o, u, a;
    var f = 0;
    e = Base64._utf8_encode(e);
    while (f < e.length) {
      n = e.charCodeAt(f++);
      r = e.charCodeAt(f++);
      i = e.charCodeAt(f++);
      s = n >> 2;
      o = (n & 3) << 4 | r >> 4;
      u = (r & 15) << 2 | i >> 6;
      a = i & 63;
      if (isNaN(r)) {
        u = a = 64
      } else if (isNaN(i)) {
        a = 64
      }
      t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
    }
    return t
  },
  decode: function (e) {
    var t = '';
    var n, r, i;
    var s, o, u, a;
    var f = 0;
    e = e.replace(/[^A-Za-z0-9\+\/\=]/g, '');
    while (f < e.length) {
      s = this._keyStr.indexOf(e.charAt(f++));
      o = this._keyStr.indexOf(e.charAt(f++));
      u = this._keyStr.indexOf(e.charAt(f++));
      a = this._keyStr.indexOf(e.charAt(f++));
      n = s << 2 | o >> 4;
      r = (o & 15) << 4 | u >> 2;
      i = (u & 3) << 6 | a;
      t = t + String.fromCharCode(n);
      if (u !== 64) {
        t = t + String.fromCharCode(r)
      }
      if (a !== 64) {
        t = t + String.fromCharCode(i)
      }
    }
    t = Base64._utf8_decode(t);
    return t
  },
  _utf8_encode: function (e) {
    e = e.replace(/\r\n/g, '\n');
    var t = '';
    for (var n = 0; n < e.length; n++) {
      var r = e.charCodeAt(n);
      if (r < 128) {
        t += String.fromCharCode(r)
      } else if (r > 127 && r < 2048) {
        t += String.fromCharCode(r >> 6 | 192);
        t += String.fromCharCode(r & 63 | 128)
      } else {
        t += String.fromCharCode(r >> 12 | 224);
        t += String.fromCharCode(r >> 6 & 63 | 128);
        t += String.fromCharCode(r & 63 | 128)
      }
    }
    return t
  },
  _utf8_decode: function (e) {
    var t = '';
    var n = 0;
    var r = 0;
    // var c1 = 0;
    var c2 = 0;
    var c3 = 0;

    while (n < e.length) {
      r = e.charCodeAt(n);
      if (r < 128) {
        t += String.fromCharCode(r);
        n++
      } else if (r > 191 && r < 224) {
        c2 = e.charCodeAt(n + 1);
        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
        n += 2
      } else {
        c2 = e.charCodeAt(n + 1);
        c3 = e.charCodeAt(n + 2);
        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
        n += 3
      }
    }
    return t
  }
};

!function (a) {
  function f(a, b) {
    if (!(a.originalEvent.touches.length > 1)) {
      a.preventDefault();
      var c = a.originalEvent.changedTouches[0], d = document.createEvent("MouseEvents");
      d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null), a.target.dispatchEvent(d)
    }
  }

  if (a.support.touch = "ontouchend" in document, a.support.touch) {
    var e, b = a.ui.mouse.prototype, c = b._mouseInit, d = b._mouseDestroy;
    b._touchStart = function (a) {
      var b = this;
      !e && b._mouseCapture(a.originalEvent.changedTouches[0]) && (e = !0, b._touchMoved = !1, f(a, "mouseover"), f(a, "mousemove"), f(a, "mousedown"))
    }, b._touchMove = function (a) {
      e && (this._touchMoved = !0, f(a, "mousemove"))
    }, b._touchEnd = function (a) {
      e && (f(a, "mouseup"), f(a, "mouseout"), this._touchMoved || f(a, "click"), e = !1)
    }, b._mouseInit = function () {
      var b = this;
      b.element.bind({
        touchstart: a.proxy(b, "_touchStart"),
        touchmove: a.proxy(b, "_touchMove"),
        touchend: a.proxy(b, "_touchEnd")
      }), c.call(b)
    }, b._mouseDestroy = function () {
      var b = this;
      b.element.unbind({
        touchstart: a.proxy(b, "_touchStart"),
        touchmove: a.proxy(b, "_touchMove"),
        touchend: a.proxy(b, "_touchEnd")
      }), d.call(b)
    }
  }
}(jQuery);
