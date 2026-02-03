$(document).ready(function () {
  var $customReviewsSlider = $('.testimonials-slider');
  var $homeHeroSlider = $('.home-hero-slider');

  $customReviewsSlider.slick({
    centerMode: true,
    slidesToShow: 3,
    centerPadding: 0,
    autoplay: true,
    arrows: true,
    autoplaySpeed: 4000,
    prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa-solid fa-play'></i></button>",
    nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa-solid fa-play'></i></button>",
    responsive: [{
      breakpoint: 450,
      settings: {
        arrows: false,
        dots: true,
        centerMode: true,
        slidesToShow: 1
      }
    },
    {
      breakpoint: 980,
      settings: {
        arrows: true,
        centerMode: true,
        slidesToShow: 2
      }
    }]
  });

  $homeHeroSlider.slick({
    autoplay: true,
    autoplaySpeed: 8000,
    arrows: false,
    dots: true,
    responsive: [{
      breakpoint: 450,
      settings: {
        centerMode: true,
        slidesToShow: 1
      }
    }]
  });

  // image slider in VDP pages

  $('.listing-main-image-slider').slick({
    asNavFor: '.listing-thumbnail-image-slider',
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    lazyLoad: 'progressive',
    prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa-solid fa-chevron-left d_flex__justify-center border_circle d_flex__align-center'></i></button>",
    nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa-solid fa-chevron-right border_circle d_flex__justify-center d_flex__align-center'></i></button>",
  })
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
  })
  // listing recommend brands made sliders
  let lrb = $('.l-r-b-w');
  $(lrb).slick({
    autoplay: true,
    autoplaySpeed: 5000,
    pauseOnHover: false
  })

  // 		make the listings tab sliders in tab and mobile
  let recommendPopupTabsContainer = $('.recommend-vehicles-popup-selection-tabs');
  if (window.innerWidth < 980) {
    $(recommendPopupTabsContainer).slick({
      centerMode: true,
      centerPadding: '40px',
      arrows: true,
      dots: true,
      slidesToScroll: 1,
      infinite: true
    })
  }

  // blogs and highlights cards
  if (window.innerWidth < 786) {
    $('.beyond-value-highlights-card-container').slick({
      dots: true
    })
    $('.blogs-card-container').slick({
      dots: true
    })
    $('.beyond-value-highlights-card-container').addClass('global-slick-slider')
  } else {
    $('.beyond-value-highlights-card-container').slick('unslick')
    $('.blogs-card-container').slick('unslick')
    $('.beyond-value-highlights-card-container').removeClass('global-slick-slider')
  }


  // ready function ends here

});  