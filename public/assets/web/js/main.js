$('.hero-slider').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  infinite: true,
  autoplay: true,
  autoplaySpeed: 2000,
  speed: 2000,
  swipeToSlide: true,
  initialSlide: 1,
  fade: true,
  cssEase: 'linear',
  useTransform: true,
  arrows: true,
  dots:false,
});
$('.cargo-slider').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  // infinite: true,
  autoplay: true,
  autoplaySpeed: 2000,
  speed: 500,
  swipeToSlide: true,
  cssEase: 'linear',
  arrows: true,
  dots:true,
  responsive: [
    {
      breakpoint: 1410,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 1195,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 920,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        dots:false,
      }
    },
    {
      breakpoint: 590,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:false,
      }
    }
  ]
});
