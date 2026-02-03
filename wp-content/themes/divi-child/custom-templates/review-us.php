<?php /* Template Name: Review us Tempalte */ ?>
<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
?>
<div id="main-content">
  <?php 
	echo divi_child_page_banner(site_url().'/wp-content/themes/divi-child/assets/images/reviews.webp', 'reviews and testimonials', '1200', '350', 'Review Us & Testimonials', 'Do you read online reviews? We do, and so do other guests who are in the market to buy, lease, service, detail or customize their vehicles. That\'s why we\'d love it if you take a few moments to review your experience at Durango Motor Company.', null); ?>
    <!-- review us content section started -->
    <div class="global-content-row review-us-row">
      <div class="global-content-column-first">
        <div class="reviews__banner">
          <div class="reviews__banner-inner d_flex d_flex__align-center d_flex__justify-around">
              <h3 class="p_0 m_0 font_bold color_white">
                Would you like to review us on google? 
              </h3>
              <div class="d_flex d_flex__align-center">
                <button class="reviews__btn-primary reviews__btn" style="margin-right:30px;" data-toggle="modal" data-target="#leaveReviewModal">
                  YES
                </button>
                <button class="reviews__btn-secondary reviews__btn color_white">
                  NO
                </button>
              </div>
            </div>
          </div>
        <!-- reviews will be here -->
        <div class="company-reviews">
            <div class="company-reviews__inner position_relative">
                <div class="loader position_absolute">
                    <div class="loader-spinner"></div>
                </div>
                <div id="reviews-container" class="review__container">
                </div>
                <div class="load__reviews">
                  <a href="javascript:void(0)" class="popup-trigger d_block font_segoe text_uppercase font_bold text_center" data-popup="leave-a-review">Load more reviews</a>
                </div>
            </div>
        </div>                  
    </div>
    <div class="global-content-columns-second">
      <div class="global-side-block">
        <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold global-content-right-primary-heading d_block">Contact US</h3>
        <strong class="d-inline-block mt-3 mb-4">
          Tel: <a href="tel:8558941386" class="global-content-right-lists-item font-helvetica text-capitalize">(855) 894-1386</a>
        </strong>
        <strong class="d-inline-block mt-3 mb-4">
          Address: <a href="https://goo.gl/maps/68KoNVgMMnb7dQ2E9" class="global-content-right-lists-item font-helvetica text-capitalize">1240 Escalante Dr., Durango,CO</a>
        </strong>
      </div>
      <div class="global-side-block">
        <h3 class="font_helvetica global-content-primary-heading  p_0 font_bold global-content-right-primary-heading d_block">Review us</h3>
        <span class="company-logo-image mx-auto mb-3 d_flex d_flex__justify-center d_flex__justify-center">
            <a href="https://www.google.com/search?rlz=1C1CHZL_enUS744US744&biw=1920&bih=937&ei=tpynXJ_4BYeCjwTC5oq4Dw&q=durango+value+autos&oq=durango+value+autos&gs_l=psy-ab.3..0j38.443552.446836..446938...2.0..0.102.1056.14j1......0....1..gws-wiz.......35i39j0i131j0i131i20i263j0i20i263j0i22i30j0i13j0i8i13i30.JTa94Zj0EWY#lrd=0x873c03328c911bd7:0x6cd230ed756a48ba,1,,," target="_blank">
              <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/google.webp?kjsd" alt="google.com" class="h-100 w-100">
            </a>
        </span>
        <span class="company-logo-image mx-auto mb-3 d_flex d_flex__justify-center d_flex__justify-center">
            <a href="https://www.yelp.com/biz/durango-motor-company-durango" target="_blank">
              <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/yelp.webp?kjsd" alt="yelp.com" class="h-100 w-100">  
            </a>
        </span>
        <span class="company-logo-image mx-auto mb-3 d_flex d_flex__justify-center d_flex__justify-center">
            <a href="https://www.facebook.com/DGOValueAutos/reviews/?ref=page_internal" target="_blank">
              <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/facebook.webp?kjsd" alt="facebook.com" class="h-100 w-100">
            </a>
        </span>
        <span class="company-logo-image mx-auto mb-3 d_flex d_flex__justify-center d_flex__justify-center">
            <a href="https://www.dealerrater.com/consumer/writereviews/43276?service=true&source=drp" target="_blank">
              <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/dealerrated.webp?kjsd" alt="dealer.com" class="h-100 w-100">
            </a>
        </span>
      </div>
      <div class="global-side-block">
        <h3 class="font-helvetica global-content-primary-heading p-0 font-weight-bold global-content-right-primary-heading d-block">Review US Offline</h3>
        <div class="mb-3">
          <strong class="mb-2">Tom Ondrako</strong>
          <span class="d-block mb-2">General Manager</span>
          <a href="mailto:tondrako@durangomotorcompany.com" class="text-link d-block">tondrako@durangomotorcompany.com</a>
          <a href="tel:8558941386" class="text-link">(855) 894-1386</a>
        </div>
        <div>
          <strong class="mb-2">Dustin Burns</strong>
          <span class="d-block mb-2">Fixed Operations Director</span>
          <a href="mailto:dburns@durangomotorcompany.com" class="text-link d-block">dburns@durangomotorcompany.com</a>
          <a href="tel:8558941396" class="text-link">(855) 894 -1396</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- review us content section ended -->
</div>
<!-- view full review -->
<div class="global_popup_wrapper" data-popup="read-full-review">
    <div class="global_popup_wrapper_overlay popup-close" data-popup="read-full-review"></div>
    <div class="global_popup_wrapper_content-wrapper overflow_y">
        <div class="d-flex justify-content-between">
            <div class="review-stars review__top-stars"></div>
            <span class="popup-close global_popup_wrapper_close border_circle d-flex justify-content-center align-items-center cursor-pointer" data-popup="read-full-review">
                <i class="fa fa-close"></i>
            </span>
        </div>
        <div class="global_popup_content_inner">
          <div class="reviews-inner-content"></div>
          <div class="load__reviews">
            <a href="javascript:void(0)" class="text-center font-weight-bold font-segoe text-uppercase d-block" data-toggle="modal" data-target="#leaveReviewModal">Leave a review</a>
          </div>
        </div>
    </div>
</div>
  <?php get_footer(); ?>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyAABC1D87T_1LPgm6oH_C1cNbz2EEYgl9w&reviewsLimit=20&maxRows=20"></script>
<script>
    $(document).ready(function(){
      // jQuery(document).ready(function() {
      //   const apiKey = 'AIzaSyAABC1D87T_1LPgm6oH_C1cNbz2EEYgl9w';
      //   const placeId = 'ChIJ1xuRjDIDPIcRukhqde0w0mw'; 
      //   const apiUrl = `https://maps.googleapis.com/maps/api/place/details/json?place_id=${placeId}&key=${apiKey}`;
      //   fetch(apiUrl)
      //   .then(response => response.json())
      //   .then(data => {
      //     if (data.result && data.result.reviews) {
      //       const reviews = data.result.reviews;

      //       // Display the reviews on your website
      //       reviews.forEach(review => {
      //         const reviewText = review.text;
      //         const reviewRating = review.rating;
      //         // Display the reviewText and reviewRating on your website
      //         // You can use HTML to format the display as needed
      //         console.log('reviews', reviewText, reviewRating)
      //       });
      //     }
      //   })
      //   .catch(error => {
      //     console.error('Error fetching place details:', error);
      //   });
      // })
        jQuery(document).ready(function() {
          $("#reviews-container").googlePlaces({
              placeId: 'ChIJ1xuRjDIDPIcRukhqde0w0mw',
              render: ['reviews'],
              min_rating: 0,
              max_rows: 20
          });
       });
    (function($) {
      var namespace = 'googlePlaces';
      $.googlePlaces = function(element, options) {
      var defaults = {
            placeId: 'ChIJ1xuRjDIDPIcRukhqde0w0mw' // placeId provided by google api documentation
          , render: ['reviews']
          , min_rating: 0
          , max_rows: 0
          , map_plug_id: 'map-plug'
          , rotateTime: false
          , shorten_names: true
          , schema:{
                displayElement: '#schema'
              , image: null
              , priceRange: null
          }
      };

    var plugin = this;

    plugin.settings = {}
    var $element = $(element),
         element = element;

    plugin.init = function() {
      plugin.settings = $.extend({}, defaults, options);
      plugin.settings.schema = $.extend({}, defaults.schema, options.schema);
      $element.html("<div id='" + plugin.settings.map_plug_id + "'></div>"); // create a plug for google to load data into
      initialize_place(function(place){
        plugin.place_data = place;

        // Trigger event before render
        $element.trigger('beforeRender.' + namespace);

        if(plugin.settings.render.indexOf('rating') > -1){
          renderRating(plugin.place_data.rating);
        }
        // render specified sections
        if(plugin.settings.render.indexOf('reviews') > -1){
          renderReviews(plugin.place_data.reviews);
          if(!!plugin.settings.rotateTime) {
              initRotation();
          }
        }
        // render schema markup
        addSchemaMarkup(
            capture_element(plugin.settings.schema.displayElement)
          , plugin.place_data
        );

        // Trigger event after render
        $element.trigger('afterRender.' + namespace);

      });
    }

    var capture_element = function(element){
      if(element instanceof jQuery){
        return element;
      }else if(typeof element == 'string'){
        try{
          var ele = $(element);
          if( ele.length ){
            return ele;
          }else{
            throw 'Element [' + element + '] couldnt be found in the DOM. Skipping '+element+' markup generation.';
          }
        }catch(e){
          console.warn(e);
        }
      }
    }
    var initialize_place = function(c){
          var map = new google.maps.Map(document.getElementById(plugin.settings.map_plug_id));

          var request = {
            placeId: plugin.settings.placeId
          };

          var service = new google.maps.places.PlacesService(map);

          service.getDetails(request, function(place, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
              c(place);
            }
          });
        }

    var renderRating = function(rating){
        var html = "";
        var star = renderAverageStars(rating);
        html = "<div class='average-rating'><h4>"+star+"</h4></div>";
        $element.append(html);
    }

    var shorten_name = function(name) {
      if (name.split(" ").length > 1) {
        var xname = "";
        xname = name.split(" ");
        return xname[0] + " " + xname[1][0] + ".";
      }
    }

    var renderReviews = function(reviews){
      var html = "";
      var row_count = (plugin.settings.max_rows > 0)? plugin.settings.max_rows - 1 : reviews.length - 1;
      // make sure the row_count is not greater than available records
      row_count = (row_count > reviews.length-1)? reviews.length -1 : row_count;
      for (var i = row_count; i >= 0; i--) {
        var stars = renderStars(reviews[i].rating);
        var date = convertTime(reviews[i].time);
        if(plugin.settings.shorten_names == true) {
          var name = shorten_name(reviews[i].author_name);
        } else {
          var name = reviews[i].author_name + "</span><span class='review-sep'>, </span>";
        };
        html = html+"<div class='review-item'>"+ stars +"<h3 class='review-meta m_0 font_bold p_0 font_helvetica color_black'>"+ name +"</h3><p class='review-date font_segoe'>"+ date +"</p><p class='review-text'>"+reviews[i].text+"</p><p class='review-text-full d_none'>"+reviews[i].text+"</p></div>"
      };
      $element.append(html);
    }

    var renderStars = function(rating){
      var stars = "<div class='review-stars'><ul>";

      // fill in gold stars
      for (var i = 0; i < rating; i++) {
        stars = stars+"<li><i class='fa fa-star'></i></li>";
      };

      // fill in empty stars
      if(rating < 5){
        for (var i = 0; i < (5 - rating); i++) {
          stars = stars+"<li><i class='fa fa-star inactive'></i></li>";
        };
      }
      stars = stars+"</ul></div>";
      return stars;
    }

    var renderAverageStars = function(rating){
        var stars = "<div class='review-stars'><ul><li><i>"+rating+"&nbsp;</i></li>";
        var activeStars = parseInt(rating);
        var inactiveStars = 5 - activeStars;
        var width = (rating - activeStars) * 100 + '%';

        // fill in gold stars
        for (var i = 0; i < activeStars; i++) {
          stars += "<li><i class='fa fa-star'></i></li>";
        };

        // fill in empty stars
        if(inactiveStars > 0){
          for (var i = 0; i < inactiveStars; i++) {
              if (i === 0) {
                  stars += "<li style='position: relative;'><i class='fa fa-star inactive'></i><i class='fa fa-star' style='position: absolute;top: 0;left: 0;overflow: hidden;width: "+width+"'></i></li>";
              } else {
                  stars += "<li><i class='fa fa-star inactive'></i></li>";
              }
          };
        }
        stars += "</ul></div>";
        return stars;
    }
    var convertTime = function(UNIX_timestamp){
          var a = new Date(UNIX_timestamp * 1000);
          var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
          var time = months[a.getMonth()] + ' ' + a.getDate() + ', ' + a.getFullYear();
          return time;
        }

    var addSchemaMarkup = function(element, placeData) {

      if(element instanceof jQuery){
        var schema = plugin.settings.schema;
        var schemaMarkup = '<span itemscope="" itemtype="http://schema.org/' + schema.type + '">';

        if(schema.image !== null) {
          schemaMarkup += generateSchemaItemMarkup('image', schema.image);
        } else {
          console.warn('Image is required for some schema types. Visit https://search.google.com/structured-data/testing-tool to test your schema output.');
        }

        if(schema.priceRange !== null) {
          schemaMarkup += generateSchemaItemMarkup('priceRange', schema.priceRange);
        }

        schemaMarkup += generateSchemaRatingMarkup(placeData, schema);
        schemaMarkup += '</span>';

        element.append(schemaMarkup);
      }
    }

    var generateSchemaRatingMarkup = function(placeData, schema) {
      var reviews = placeData.reviews;
      var lastIndex = reviews.length - 1;
      var reviewPointTotal = 0;

      for (var i = lastIndex; i >= 0; i--) {
        reviewPointTotal += reviews[i].rating;
      };

      var averageReview = reviewPointTotal / ( reviews.length );

      return schema.beforeText + ' <span itemprop="name">' + placeData.name + '</span> '
      +  '<span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">'
      +    '<span itemprop="ratingValue">' + averageReview.toFixed(2) + '</span>/<span itemprop="bestRating">5</span> '
      +  schema.middleText + ' <span itemprop="ratingCount">' + reviews.length + '</span> '
      +  schema.afterText
      +  '</span>'
    }

    var generateSchemaItemMarkup = function(name, value) {
      return '<meta itemprop="' + name + '" content="' + value + '">'
    }

    plugin.init();

}

$.fn.googlePlaces = function(options) {

    return this.each(function() {
        if (undefined == $(this).data(namespace)) {
            var plugin = new $.googlePlaces(this, options);
            $(this).data(namespace, plugin);
        }
    });

}

setTimeout(() => {
  if( $('.review__container').html() ) {
    viewMore()
  }else{
    setTimeout(() => {
      viewMore()
    }, 2000);
  }
}, 3000);
$(window).load(function(){
  $(document).find('.review-text').each(function(index, data) {
        var string = $(data).text();
        var words = string.split(' ');
        if (words.length > 30) {
          var newString = words.slice(0, 30).join(' ');
          var readMore = `<a href="javascript:void(0)" class="review-read-more popup-trigger" data-popup="read-full-review"> View More</a>`;
          var newContent = newString + readMore ;
          $(this).html(newContent);
        }
      });
})


})(jQuery);

       
    })
</script>
<script>
  $(document).ready(function() {
    $('.reviews__btn-secondary').click(function() {
      $(this).parents('.reviews__banner').fadeOut()
    })
  })
</script>