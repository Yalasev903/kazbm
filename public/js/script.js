// preloader
$(document).ready(()=>{
  setTimeout(() => {
    $(".preloader").css("display", "none");
  }, 1000);
  // let wow = new WOW(
  //   {
  //     animateClass: 'animate__animated' //updated default animate 4.+
  //   }
  // )
  // wow.init();
});

// let animationLoadingModal = bodymovin.loadAnimation({
//   container: document.querySelector('.loadingModal .loading .loading_box'),
//   path: 'assets/images/icons/loadingSend2.json',
//   render: 'svg',
//   loop: true,
//   autoplay: true,
// })
// let animationSendModal = bodymovin.loadAnimation({
//   container: document.querySelector('.loadingModal .loading .succes'),
//   path: 'assets/images/icons/send2.json',
//   render: 'svg',
//   loop: true,
//   autoplay: true,
// })

// searchPlatform
function openSearch() {
  $('.searchPlatform').addClass('active');
  $('.searchPlatform_bloor').addClass('active');
}
function closeSearch() {
  $('.searchPlatform').removeClass('active');
  $('.searchPlatform_bloor').removeClass('active');
}
// basketAt
function basketAt(el) {
  // $('.basketAt').addClass('active');
  // console.log('.goBasket')
  if(el != undefined) {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/ajax/cart/add',
      type: 'POST',
      data: {
        productId: $(el).data('id'),
        qty: ($('main').hasClass('productPage')) ? parseInt($('.productNumber').val()) : 0
      },
      success: function (response) {

        $('.basketBtn .num').show();
        $('.basketBtn .num').html(response.counter > 9 ? '9+' : response.counter)
        $('.basketAt').html(response.basket_item).addClass('active');

        if ($('main').hasClass('productPage')) {
          $(el).hide();
          $(el).next('.goBasket').show();
        } else {
          $(el).parent().removeClass('active');
          $(el).parent().parent().children('.goBasket').addClass('active');
        }

        setTimeout(()=>{
          $('.basketAt').removeClass('active');
        }, 2000);
      },
      error: function (error) {
        console.log(error)
      }
    })
  }
}

function openMobileMenu() {
  $('.mobileMenuBtn .icon_menu').removeClass('active');
  $('.mobileMenuBtn .icon_close').addClass('active');
  $('.mobileCatalog').removeClass('active');
  $('.mobileMenu').addClass('active');
  $('.mobileMenu_bloor').addClass('active');
  $('.mobileInteractive').addClass('active');
  $('.mobileInteractive .icon').eq(0).children('.link').removeClass('active');
  $('html').css('overflow', 'hidden');
}
function closeOnlyMenuOpenCatalog() {
  $('.mobileMenu').removeClass('active');
  $('.mobileMenuBtn .icon_menu').addClass('active');
  $('.mobileMenuBtn .icon_close').removeClass('active');
  if($('.mobileCatalog').hasClass('active')) {
    $('.mobileCatalog').removeClass('active');
    $('.mobileInteractive .icon').eq(0).children('.link').removeClass('active');
  }
  else {
    $('.mobileCatalog').addClass('active');
    $('.mobileInteractive .icon').eq(0).children('.link').addClass('active');
  }
  if($('.block2_filter').length != 0) {
    if(!$('.block2_filter').hasClass('active')) {
      onlyOpenFilter()
    }
    else {
      onlyCloseFilter()
    }
  }
}
function closeMobileMenu() {
  $('.mobileMenuBtn .icon_close').removeClass('active');
  $('.mobileMenuBtn .icon_menu').addClass('active');
  $('.mobileMenu').removeClass('active');
  $('.mobileMenu_bloor').removeClass('active');
  $('.mobileInteractive').removeClass('active');
  $('.mobileCatalog').removeClass('active');
  $('.mobileInteractive .icon').eq(0).children('.link').removeClass('active');
  $('html').css('overflow', 'auto');
}
let positionScrollHeader = 0;
if($(window).width() > 767) {
  $(window).scroll(function(){
    let scrollTopHeader = false;
    if($(window).scrollTop() > positionScrollHeader) {
      scrollTopHeader = false;
    }
    else {
      scrollTopHeader = true;
    }
    if (!scrollTopHeader) {
      $('.header').css('top', '-100px');
    }
    else {
      $('.header').css('top', '0');
    }
    positionScrollHeader = $(window).scrollTop();
  });
}
function showModal(nth) {
  $('.modal_bloor').addClass('active');
  $('.modal').eq(nth-1).addClass('active');
  // animationSendModal.playCount = 1;
  if($(window).width() < 577) {
    $('.mobileInteractive').css('display', 'none');
  }
}

function closeModal() {
  $('.modal_bloor').removeClass('active');
  $('.modal').each((index, elm)=>{
    $('.modal').eq(index).removeClass('active');
  })
  if($(window).width() < 577) {
    $('.mobileInteractive').css('display', 'grid');
  }
}

// function for remove class
function removerClassAll(elClass, path) {
  for (let j = 0; j < $(path).length; j++) {
    if($(path).eq(j).hasClass(elClass)) {
      $(path).eq(j).removeClass(elClass)
    }
  }
}

// home page
if($('main').hasClass('homePage').length != 0) {
  // home page block3
  $('.block3 .type.radio').each((i, el)=>{
    if($(el).hasClass('disabled')) {
      $(el).children('input').attr('disabled', true);
    }
    $(el).click(()=>{
      if(!$(el).hasClass('disabled')) {
        $(el).children('input').prop("checked", true);
      }
    })
  });
  $('.block3 .size.radio').each((i, el)=>{
    if($(el).hasClass('disabled')) {
      $(el).children('input').attr('disabled', true);
    }
    $(el).click(()=>{
      if(!$(el).hasClass('disabled')) {
        removerClassAll('active', '.block3 .size.radio');
        $(el).addClass('active');
        $(el).children('input').prop("checked", true);
        console.log($(el).children('input'))
      }
    })
  });
// home page block4
  $('.block4 .left .head .item').each((i, el)=>{
    $(el).click(()=>{
      // left
      removerClassAll('active', '.block4 .left .head .item');
      $(el).addClass('active');
      // right
      removerClassAll('active', '.block4 .right .body .item');
      $('.block4 .right .body .item').eq(i).addClass('active');
    });
  });
  if($(window).width() > 576) {
    let intervalIndex = 1;
    let intervalSlide = setInterval(()=>{
      removerClassAll('active', '.block4 .left .head .item');
      removerClassAll('active', '.block4 .right .body .item');
      $('.block4 .left .head .item').eq(intervalIndex).addClass('active');
      $('.block4 .right .body .item').eq(intervalIndex).addClass('active');
      if($('.block4 .left .head .item').length - 1 > intervalIndex) {
        intervalIndex++;
      }
      else {
        intervalIndex = 0;
      }
    }, 5000)
  }
}
// home page focus .block3
if($('.homePage .block3').length != 0) {
  let arrayText = ["Измерьте длину всех внешних стен.", "Измерьте высоту стен в углах здания.", "Введите суммарную длину всех дверей и окон.", "Введите суммарную высоту всех дверей и окон."]
  $('.homePage .block3 .IWR .inputsWithRadio input').each((i, el)=>{
    $(el).focus(()=>{
      removerClassAll('active', '.homePage .block3 .box_right .head .head_item');
      $('.homePage .block3 .box_right .head .head_item').eq(i).addClass('active')
      $('.homePage .block3 .box_right .head .desc').html(arrayText[i]);
    })
  })
}

// product page
if($('.productPage .units').length != 0) {
  $('.productPage .units .unit').each((i, el)=>{
    $(el).click(()=>{
      removerClassAll('active', '.productPage .units .unit');
      removerClassAll('active', '.productPage .nums .num');
      $('.productPage .units .unit').eq(i).addClass('active');
      $('.productPage .nums .num').eq(i).addClass('active');
    })
  })
}

// basket page

if($('.basketPage .item .bottom_units').length != 0) {
  $('.basketPage .item .bottom_units .unit').each((i, el)=>{
    $(el).click((e)=>{
      $(e.currentTarget).parent().children('.unit').removeClass('active');
      $(e.currentTarget).parent().parent().children('.bottom_costPer').children('.num').removeClass('active');
      $('.basketPage .item .bottom_units .unit').eq(i).addClass('active');
      $('.basketPage .item .nums .num').eq(i).addClass('active');
      $(e.currentTarget).parent().parent().children('.bottom_input').children('.unit').html($('.basketPage .item .bottom_units .unit').eq(i).html())
    })
  })

  // $('.basketPage .item').each((i, el)=>{
  //   $(el).children('.item_right').children('.bottom').children('.bottom_units').children('.unit').click(()=>{
  //     for (let j = 0; j < $('.basketPage .item .bottom_units .unit').length; j++) {
  //       if(i != j) {
  //         $('.basketPage .item .bottom_units .unit').eq(i).removeClass('active');
  //       }
  //     }
  //     for (let j = 0; j < $('.basketPage .item .nums .num').length; j++) {
  //       if(i != j) {
  //         $('.basketPage .item .nums .num').eq(i).removeClass('active');
  //       }
  //     }
  //     $('.basketPage .item .bottom_units .unit').eq(i).addClass('active');
  //     $('.basketPage .item .nums .num').eq(i).addClass('active');
  //     $('.basketPage .item .unit').eq(i).html($('.basketPage .item .bottom_units .unit').eq(i).html())
  //   })
  // })
}

// catalog page
// catalog handle slider
$( function() {
  $( "#slider-range" ).slider({
    range: true,
    min: parseInt($( "#slider-range" ).data('min')),
    max: parseInt($( "#slider-range" ).data('max')),
    values: [ parseInt($( ".minVal" ).val()), parseInt($( ".maxVal" ).val()) ],
    slide: function( event, ui ) {
      $( ".minVal" ).val(ui.values[0]);
      $( ".maxVal" ).val(ui.values[1]);
    }
  });
  $(".minVal, .maxVal").on('input', function (e) {
    $( "#slider-range" ).slider( "values", 0, $( ".minVal" ).val());
    $( "#slider-range" ).slider( "values", 1, $( ".maxVal" ).val());
  });

  if($('.homePage').length != 0) {
    $( "#slider-length" ).slider({
      orientation: "horizontal",
      range: "min",
      min: parseInt($( "#slider-length" ).data('min')),
      max: parseInt($( "#slider-length" ).data('max')),
      value: [ $( ".inputsWithRadio .fi" ).val() ],
      slide: function( event, ui ) {
        $( ".inputsWithRadio .fi" ).val(ui.value);
      }
    });
    $(".inputsWithRadio .fi").on('input', function (e) {
      $( "#slider-length" ).slider( "value", $( ".inputsWithRadio .fi" ).val());
    });

    $( "#slider-height" ).slider({
      orientation: "horizontal",
      range: "min",
      min: parseInt($( "#slider-height" ).data('min')),
      max: parseInt($( "#slider-height" ).data('max')),
      value: [ $( ".inputsWithRadio .se" ).val() ],
      slide: function( event, ui ) {
        $( ".inputsWithRadio .se" ).val(ui.value);
      }
    });
    $(".inputsWithRadio .se").on('input', function (e) {
      $( "#slider-height" ).slider( "value", $( ".inputsWithRadio .se" ).val());
    });

    $( "#slider-paramWidth" ).slider({
      orientation: "horizontal",
      range: "min",
      min: parseInt($( "#slider-paramWidth" ).data('min')),
      max: parseInt($( "#slider-paramWidth" ).data('max')),
      value: [ $( ".inputsWithRadio .th" ).val() ],
      slide: function( event, ui ) {
        $( ".inputsWithRadio .th" ).val(ui.value);
      }
    });
    $(".inputsWithRadio .th").on('input', function (e) {
      $( "#slider-paramWidth" ).slider( "value", $( ".inputsWithRadio .th" ).val());
    });

    $( "#slider-paramHeight" ).slider({
      orientation: "horizontal",
      range: "min",
      min: parseInt($( "#slider-paramHeight" ).data('min')),
      max: parseInt($( "#slider-paramHeight" ).data('max')),
      value: [ $( ".inputsWithRadio .fo" ).val() ],
      slide: function( event, ui ) {
        $( ".inputsWithRadio .fo" ).val(ui.value);
      }
    });
    $(".inputsWithRadio .fo").on('input', function (e) {
      $( "#slider-paramHeight" ).slider( "value", $( ".inputsWithRadio .fo" ).val());
    });
  }
} );
// catalog add param filter
$('.catalogPage .block2 .filter .elmt').each((i, el)=>{
  $(el).click(()=>{
    if(!$(el).hasClass('active')) {
      removerClassAll('active', '.catalogPage .block2 .filter .elmt');
      $(el).addClass('active');
    }
    else {
      $(el).removeClass('active');
    }
    if(checkerFilterText('.catalogPage .block2 .block2_prod .head .item', $(el).children('.text').text(), '.catalogPage .block2 .filter .elmt')) {
      $('.catalogPage .block2 .block2_prod .head').append('<div class="item"><div class="text">'+ $(el).children('.text').text() +'</div><svg class="delete" onclick="removerFilter(this)" xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none"><path d="M8.32867 7.00003L14.8284 0.933604C15.0572 0.720022 15.0572 0.373741 14.8284 0.160186C14.5995 -0.0533681 14.2285 -0.0533955 13.9997 0.160186L7.49998 6.22661L1.00029 0.160186C0.77145 -0.0533955 0.400436 -0.0533955 0.171628 0.160186C-0.05718 0.373768 -0.0572093 0.720049 0.171628 0.933604L6.67133 7L0.171628 13.0664C-0.0572093 13.28 -0.0572093 13.6263 0.171628 13.8398C0.286032 13.9466 0.436002 14 0.585972 14C0.735943 14 0.885884 13.9466 1.00032 13.8398L7.49998 7.77345L13.9997 13.8398C14.1141 13.9466 14.2641 14 14.414 14C14.564 14 14.7139 13.9466 14.8284 13.8398C15.0572 13.6263 15.0572 13.28 14.8284 13.0664L8.32867 7.00003Z" fill="#3B3535"/></svg></div>')
    }
  })
})
$('.catalogPage .block2 .filter .checkbox label').each((i, el)=>{
  $(el).click(()=>{
    if(checkerFilterCheckboxText('.catalogPage .block2 .block2_prod .head .item', $(el).text())) {
      $('.catalogPage .block2 .block2_prod .head').append('<div class="item"><div class="text">'+ $(el).text() +'</div><svg class="delete" onclick="removerFilter(this)" xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none"><path d="M8.32867 7.00003L14.8284 0.933604C15.0572 0.720022 15.0572 0.373741 14.8284 0.160186C14.5995 -0.0533681 14.2285 -0.0533955 13.9997 0.160186L7.49998 6.22661L1.00029 0.160186C0.77145 -0.0533955 0.400436 -0.0533955 0.171628 0.160186C-0.05718 0.373768 -0.0572093 0.720049 0.171628 0.933604L6.67133 7L0.171628 13.0664C-0.0572093 13.28 -0.0572093 13.6263 0.171628 13.8398C0.286032 13.9466 0.436002 14 0.585972 14C0.735943 14 0.885884 13.9466 1.00032 13.8398L7.49998 7.77345L13.9997 13.8398C14.1141 13.9466 14.2641 14 14.414 14C14.564 14 14.7139 13.9466 14.8284 13.8398C15.0572 13.6263 15.0572 13.28 14.8284 13.0664L8.32867 7.00003Z" fill="#3B3535"/></svg></div>')
    }
    // if(checkerFilterText('.catalogPage .block2 .block2_prod .head .item', $(el).children('.text').text(), '.catalogPage .block2 .filter .elmt')) {
    //   $('.catalogPage .block2 .block2_prod .head').append('<div class="item"><div class="text">'+ $(el).children('.text').text() +'</div><svg class="delete" onclick="removerFilter(this)" xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none"><path d="M8.32867 7.00003L14.8284 0.933604C15.0572 0.720022 15.0572 0.373741 14.8284 0.160186C14.5995 -0.0533681 14.2285 -0.0533955 13.9997 0.160186L7.49998 6.22661L1.00029 0.160186C0.77145 -0.0533955 0.400436 -0.0533955 0.171628 0.160186C-0.05718 0.373768 -0.0572093 0.720049 0.171628 0.933604L6.67133 7L0.171628 13.0664C-0.0572093 13.28 -0.0572093 13.6263 0.171628 13.8398C0.286032 13.9466 0.436002 14 0.585972 14C0.735943 14 0.885884 13.9466 1.00032 13.8398L7.49998 7.77345L13.9997 13.8398C14.1141 13.9466 14.2641 14 14.414 14C14.564 14 14.7139 13.9466 14.8284 13.8398C15.0572 13.6263 15.0572 13.28 14.8284 13.0664L8.32867 7.00003Z" fill="#3B3535"/></svg></div>')
    // }
  })
})

//colors
function checkerFilterText(path, text, filterElmtsPath) {
  let result = true;
  $(path).each((i, el)=>{
    if($(el).children('.text').text() === text) {
      result = false;
    }
  })
  if(filterElmtsPath != undefined) {
    removerFilterText(path, filterElmtsPath)
  }
  return result;
}
function removerFilterText(pathFilter, pathEmts) {
  $(pathFilter).each((i, el)=>{
    $(pathEmts).each((j, elm)=>{
      if($(elm).children('.text').length != 0) {
        if($(el).children('.text').text() === $(elm).children('.text').text()) {
          $(el).remove();
        }
      }
      else {
        if($(el).children('.text').text() === $(elm).text()) {
          $(el).remove();
        }
      }
    })
  })
}
// checkbox`s
function checkerFilterCheckboxText(path, text) {
  let result = true;
  $(path).each((i, el)=>{
    if($(el).children('.text').text() === text) {
      result = false;
      $(el).remove();
    }
  })
  return result;
}
function removerFilter(el) {
  $(el).parent().remove()
}
//tooltip global
// Use a closure to keep vars out of global scope
(function () {
  var ID = "tooltip", CLS_ON = "tooltip_ON", FOLLOW = true,
    DATA = "_tooltip", OFFSET_X = 10, OFFSET_Y = 10,
    showAt = function (e) {
      var ntop = e.pageY + OFFSET_Y, nleft = e.pageX + OFFSET_X;
      // console.log($(e.target))
      // console.log($(e.target).data(DATA))
      if($(window).width() > 576) {
        $("#" + ID).html($(e.target).data(DATA)).css({
          position: "absolute", top: ntop, left: nleft
        }).show();
      }
      else {
        $("#" + ID).html($(e.target).data(DATA)).css({
          position: "absolute", top: ntop, left: '50%', transform: "translate(-50%, 0)"
        }).show();
      }
    };
  $(document).on("mouseenter", "*[title]", function (e) {
    $(this).data(DATA, $(this).attr("title"));
    $(this).removeAttr("title").addClass(CLS_ON);
    $("<div id='" + ID + "' />").appendTo("main");
    showAt(e);
  });
  $(document).on("mouseleave", "." + CLS_ON, function (e) {
    $(this).attr("title", $(this).data(DATA)).removeClass(CLS_ON);
    $("#" + ID).remove();
  });
  if (FOLLOW) { $(document).on("mousemove", "." + CLS_ON, showAt); }
}());

// product page
$('.productPage .block1 .slider .item').each((i, el)=>{
  $(el).click(()=>{
    removerClassAll('active', '.productPage .block1 .slider .item');
    removerClassAll('active', '.productPage .block1 .banner .item');
    $(el).addClass('active');
    $('.productPage .block1 .banner .item').eq(i).addClass('active');
  });
});
// product modal
function showProductModal() {
  $('.modalCalcProduct_bloor').addClass('active');
  $('.modalCalcProduct').addClass('active');
}
function closeProductModal() {
  $('.modalCalcProduct_bloor').removeClass('active');
  $('.modalCalcProduct').removeClass('active');
}
// error page
if($('.errorPage').length != 0) {
  let leftError= $('.errorPage #left');
  var rightError= $('.errorPage #right');

  let layer= $('.errorPage');

  if($(window).width() > 1200) {
    layer.mousemove(function(e){
      let ivalueX= (e.pageX * -1 / 20);
      let ivalueY= (e.pageY * -1 / 20);
      let cvalueX= (e.pageX * -1 / 30);
      let cvalueY= (e.pageY * -1 / 50);
      // console.log('ok');
      leftError.css('transform', 'translate3d('+ivalueX+'px,'+ivalueY+'px, 0)');
      rightError.css('transform', 'translate3d('+cvalueX+'px,'+cvalueY+'px, 0)');
    });
  }
}
// about page
if($('.aboutPage').length != 0) {
  let leftError= $('.aboutPage #left');
  let layer= $('.aboutPage');
  if($(window).width() > 1200) {
    layer.mousemove(function(e){
      let ivalueX= (e.pageX * -1 / 20);
      let ivalueY= (e.pageY * -1 / 20);
      let cvalueX= (e.pageX * -1 / 30);
      let cvalueY= (e.pageY * -1 / 50);
      // console.log('ok');
      leftError.css('transform', 'translate3d('+ivalueX+'px,'+ivalueY+'px, 0)');
    });
  }
}
// calc page
if($('.calcPage').length != 0) {
  var rightError= $('.calcPage #right');

  let layer= $('.calcPage');

  if($(window).width() > 1200) {
    layer.mousemove(function(e){
      let ivalueX= (e.pageX * -1 / 20);
      let ivalueY= (e.pageY * -1 / 20);
      let cvalueX= (e.pageX * -1 / 30);
      let cvalueY= (e.pageY * -1 / 50);
      // console.log('ok');
      rightError.css('transform', 'translate3d('+cvalueX+'px,'+cvalueY+'px, 0)');
    });
  }
}
//checkout page
if($('.checkoutPage').length != 0) {
  $('.dostavka input').each((i, el)=>{
    $(el).change(()=>{
      if($('.dostavka input:checked').parent().children('label').children('.top').children('.top_left').text() === 'Самовывоз') {
        $('.input.address').css('display','none');
      }
      else {
        $('.input.address').css('display','flex');
      }
    })
  })
  if($('.dostavka input:checked').parent().children('label').children('.top').children('.top_left').text() === 'Самовывоз') {
    $('.input.address').css('display','none');
  }
  else {
    $('.input.address').css('display','flex');
  }

  $('.liso input').each((i, el)=>{
    $(el).change(()=>{
      if($('.fiz input').is(':checked')) {
        $('.input.iin').css('display','none');
      }
      else {
        $('.input.iin').css('display','flex');
      }
    })
  })
  if($('.fiz input').is(':checked')) {
    $('.input.iin').css('display','none');
  }
  else {
    $('.input.iin').css('display','flex');
  }
}

//about page

if($('.aboutPage').length != 0){
  baguetteBox.run('.block3_sert .items', {
    buttons: true
  });
}

// profile Page
function openDetailModal(modalId) {
  var modalElem = $('.profilePage .orderModal' + modalId);
  modalElem.addClass('active');
  modalElem.prev().addClass('active');
}
function closeDetailModal() {
  $('.profilePage .detailModal.active').removeClass('active');
  $('.profilePage .detailModal_bloor.active').removeClass('active');
}

function openFilter() {
  $('.block2_filter').addClass('active');
  $('.block2_filter_bloor').addClass('active');
  if($(window).width() < 1201) {
    $('.mobileInteractive').addClass('active');
    $('.mobileInteractive .icon').eq(0).children('.link').removeClass('active');
  }
  $('html').css('overflow', 'hidden');
}
function closeFilter() {
  $('.block2_filter').removeClass('active');
  $('.block2_filter_bloor').removeClass('active');
  if($(window).width() < 1201) {
    $('.mobileInteractive').removeClass('active');
    $('.mobileInteractive .icon').eq(0).children('.link').removeClass('active');
  }
  $('html').css('overflow', 'auto');
}
function onlyOpenFilter() {
  $('.block2_filter').addClass('active');
}
function onlyCloseFilter() {
  $('.block2_filter').removeClass('active');
}
function clearFilter() {
  $( "#slider-range" ).slider( "values", 0, $( ".minVal" ).attr("value"));
  $( "#slider-range" ).slider( "values", 1, $( ".maxVal" ).attr("value"));
  $( ".minVal" ).val($( ".minVal" ).attr("value"))
  $( ".maxVal" ).val($( ".maxVal" ).attr("value"))
  $( ".filter .checkbox input").each((i, el) => {
    $(el).prop('checked', false);
  })
  $( ".filter .elmts .elmt").each((i, el) => {
    $(el).removeClass('active');
  })
  $('.block2_prod .head .item').remove();
}

// Fancybox initialization
$(document).ready(function() {
  // Initialize Fancybox for all galleries
  if (typeof Fancybox !== 'undefined') {
    Fancybox.bind('[data-fancybox]', {
      // Options
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [
            'zoomIn',
            'zoomOut',
            'toggle1to1',
            'rotateCCW',
            'rotateCW',
            'flipX',
            'flipY',
          ],
          right: ['slideshow', 'close'],
        },
      },
      Thumbs: {
        autoStart: false,
        showOnStart: false,
      },
      // Prevent conflicts with sliders
      on: {
        init: (fancybox) => {
          // Stop slider autoplay when fancybox opens
          $('.slick-slider').slick('slickPause');
        },
        destroy: (fancybox) => {
          // Resume slider autoplay when fancybox closes
          $('.slick-slider').slick('slickPlay');
        }
      }
    });
  }
});

// Handle clicks on slider items to prevent navigation when clicking on image
$(document).on('click', '.slider-item a[data-fancybox]', function(e) {
  e.stopPropagation();
});

// Handle clicks on product gallery images
$(document).on('click', '.productPage .banner a[data-fancybox], .productPage .slider a[data-fancybox]', function(e) {
  e.stopPropagation();
});
