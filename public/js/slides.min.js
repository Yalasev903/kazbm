$(document).ready(function () {
  let timered;
  
  // Lazy loading function for slider images
  function lazyLoadSliderImages(slider) {
    const $slider = $(slider);
    const $activeSlides = $slider.find('.slick-active');
    const $nextSlides = $slider.find('.slick-active').next();
    const $prevSlides = $slider.find('.slick-active').prev();
    
    // Load active slides and adjacent slides
    $activeSlides.add($nextSlides).add($prevSlides).find('img[data-lazy]').each(function() {
      const $img = $(this);
      const src = $img.attr('data-lazy');
      if (src && !$img.attr('src')) {
        $img.attr('src', src).removeAttr('data-lazy');
      }
    });
  }
  
  setTimeout(()=>{
    $('.block2 .slider').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      prevArrow: $('.block2 .arrow_left'),
      nextArrow: $('.block2 .arrow_right'),
      infinite: true,
      focusOnSelect: true,
      variableWidth: true,
      lazyLoad: 'ondemand',
      // speed: 500,
      responsive: [
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 2,
            variableWidth: false,
            arrows: true,
            speed: 500,
            useTransform: true,
            cssEase: 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
          },
        },
      ],
    }).on('init afterChange', function(event, slick) {
      lazyLoadSliderImages(this);
    })
    //   .on('beforeChange', function(){
    //   if($(window).width() > 576) {
    //     if($('.block2 .slider .slick-slide').length > 4) {
    //       $('.block2 .slider').slick('setPosition')
    //       timered = setInterval(function () {
    //         $('.block2 .slider').slick('setPosition');
    //       }, 1)
    //     }
    //   }
    // }).on("afterChange", function (slider, index){
    //   if($(window).width() > 576) {
    //     if($('.block2 .slider .slick-slide').length > 4) {
    //       $('.block2 .slider').slick('setPosition')
    //       clearInterval(timered)
    //     }
    //   }
    // });
    $('.block6 .right').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      prevArrow: $('.block6 .arrow_left'),
      nextArrow: $('.block6 .arrow_right'),
      infinite: false,
      focusOnSelect: false,
      variableWidth: false,
      lazyLoad: 'ondemand',
      responsive: [
        {
          breakpoint: 576,
          settings: {
            arrows: false
          },
        },
      ],
    }).on('init afterChange', function(event, slick) {
      lazyLoadSliderImages(this);
    });
    $('.card .card_slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: true,
      arrows: false,
      infinite: false,
      focusOnSelect: false,
      variableWidth: false,
      lazyLoad: 'ondemand',
      responsive: [
        {
          breakpoint: 576,
          settings: {
            arrows: false
          },
        },
      ],
    }).on('init afterChange', function(event, slick) {
      lazyLoadSliderImages(this);
    });
    if($('.profilePage .box_right .zakaz .items .item').length != 0) {
      $('.profilePage .box_right .zakaz .items .item').each((i, el)=>{
        if($(el).children('.item_bottom').children('.right').children('a').length > 2) {
          $(el).children('.item_bottom').children('.right').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false,
            arrows: false,
            infinite: false,
            focusOnSelect: false,
            variableWidth: false,
            responsive: [
              {
                breakpoint: 769,
                settings: {
                  arrows: false,
                  slidesToShow: 4,
                }
              },
              {
                breakpoint: 576,
                settings: {
                  arrows: false,
                  slidesToShow: 4
                },
              },
            ],
          });
        }
      })
    }

    if($('.productPage .block1 .banner').length != 0){
      $('.productPage .block1 .banner').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
        infinite: true,
        lazyLoad: 'ondemand',
      }).on('init afterChange', function(event, slick) {
        lazyLoadSliderImages(this);
      }).on("afterChange", function (slider, index){
        removerClassAll('active', '.productPage .block1 .left .slider .item');
        $('.productPage .block1 .left .slider .item').eq(index.currentSlide).addClass('active')
      });
      $('.productPage .block1 .left .slider .item').click((index)=>{
        $('.productPage .block1 .banner').slick('slickGoTo', $(index.currentTarget).index(),  true)
      })
    }

    // $('.block2 .slider').on('mouseenter', '.slick-slide', function (e) {
    //   $(this).click();
    // });
    // $('.block5 .items').slick({
    //   slidesToShow: 3,
    //   slidesToScroll: 1,
    //   asNavFor: '.block7 .left',
    //   dots: false,
    //   arrows: true,
    //   prevArrow: '<svg width="57" height="56" viewBox="0 0 57 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="slick-prev"><g opacity="0.5"><rect x="0.5" width="56" height="56" rx="28" fill="#F36B4D"/><path d="M35.5 19.4764V17.2327C35.5 17.0382 35.2735 16.9308 35.1206 17.0498L21.8618 27.27C21.7492 27.3564 21.658 27.4672 21.5953 27.5937C21.5326 27.7202 21.5 27.8592 21.5 28C21.5 28.1408 21.5326 28.2798 21.5953 28.4063C21.658 28.5328 21.7492 28.6436 21.8618 28.73L35.1206 38.9502C35.2765 39.0692 35.5 38.9618 35.5 38.7673V36.5236C35.5 36.3814 35.4324 36.2449 35.3206 36.1579L24.7324 28.0015L35.3206 19.8421C35.4324 19.7551 35.5 19.6186 35.5 19.4764Z" fill="white"/></g></svg>',
    //   nextArrow: '<svg width="57" height="56" viewBox="0 0 57 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="slick-next"><g opacity="0.5"><rect x="0.5" width="56" height="56" rx="28" fill="#F36B4D"/><path d="M21.5 19.4764V17.2327C21.5 17.0382 21.7265 16.9308 21.8794 17.0498L35.1382 27.27C35.2508 27.3564 35.342 27.4672 35.4047 27.5937C35.4674 27.7202 35.5 27.8592 35.5 28C35.5 28.1408 35.4674 28.2798 35.4047 28.4063C35.342 28.5328 35.2508 28.6436 35.1382 28.73L21.8794 38.9502C21.7235 39.0692 21.5 38.9618 21.5 38.7673V36.5236C21.5 36.3814 21.5676 36.2449 21.6794 36.1579L32.2676 28.0015L21.6794 19.8421C21.5676 19.7551 21.5 19.6186 21.5 19.4764Z" fill="white"/></g></svg>',
    //   infinite: true,
    //   focusOnSelect: true,
    //   responsive: [
    //     {
    //       breakpoint: 576,
    //       settings: {
    //         arrows: false
    //       },
    //     },
    //   ],
    // });
  }, 50)
});
