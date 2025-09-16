let Sender = {
  footerForm: async (e)=>{
    let fun = this;
    fun.data = {
      name: $('.footerName').val(),
      email: $('.footerEmail').val(),
    };
    // console.log(fun.data)
    // console.log($('.modalFormInput2').val().length)
    if($('.footerName').val() != '' && emailChecker($('.footerEmail').val())) {
      req();
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        url: '/ajax/application/consultation',
        type: 'POST',
        data: fun.data,
        success: function (a) {
          res();
          return 'ok';
        },
        error: function (error) {
          console.log(error)
          errorFun();
          return 'error';
        }
      })
    }
    else {
      if($('.footerName').val() === '') {
        $('.footerName').addClass('error');
      }
      if(!emailChecker($('.footerEmail').val())) {
        $('.footerEmail').addClass('error');
      }
    }
    function req() {
      $('.footer .form input').removeClass('error');
      closeModal()
      $('.modal_bloor').addClass('active');
      $('.loadingZakazZvonok').addClass('active');
      $('.loadingZakazZvonok .loading .loading_box').addClass('active');
    }
    function res() {
      $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
      $('.loadingZakazZvonok .loading .succes').addClass('active');
      // animationSendModal.play()
      setTimeout(()=>{
        $('.loadingZakazZvonok .loading .succes').removeClass('active');
        // animationSendModal.resetSegments()
        closeModal()
      }, 2000)
      setTimer();
    }
    function errorFun() {
      $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
      $('.loadingZakazZvonok .loading .error').addClass('active');
      // animationSendModal.play()
      setTimeout(()=>{
        $('.loadingZakazZvonok .loading .error').removeClass('active');
        // animationSendModal.resetSegments()
        closeModal()
      }, 2000)
      setTimer();
    }
    function setTimer() {
      return setTimeout(()=>{
        //clear inputs
        $('.footerName').val('');
        $('.footerEmail').val('');
        $('.footer .form input').removeClass('error');
      }, 2000)
    }
  },
    contactForm: async (e)=>{
        let fun = this;
        fun.data = {
            name: $('.contactName').val(),
            email: $('.contactEmail').val(),
            message: $('.contactMsg').val(),
        };
        // console.log(fun.data)
        // console.log($('.modalFormInput2').val().length)
        if($('.contactName').val() != '' && emailChecker($('.contactEmail').val())) {
            req();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/ajax/application/consultation',
                type: 'POST',
                data: fun.data,
                success: function (a) {
                    res();
                    return 'ok';
                },
                error: function (error) {
                    console.log(error)
                    errorFun();
                    return 'error';
                }
            })
        }
        else {
            if($('.contactName').val() === '') {
                $('.contactName').addClass('error');
            }
            if(!emailChecker($('.contactEmail').val())) {
                $('.contactEmail').addClass('error');
            }
        }
        function req() {
            $('.contactsPage .form input').removeClass('error');
            closeModal()
            $('.modal_bloor').addClass('active');
            $('.loadingZakazZvonok').addClass('active');
            $('.loadingZakazZvonok .loading .loading_box').addClass('active');
        }
        function res() {
            $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
            $('.loadingZakazZvonok .loading .succes').addClass('active');
            // animationSendModal.play()
            setTimeout(()=>{
                $('.loadingZakazZvonok .loading .succes').removeClass('active');
                closeModal()
            }, 2000)
            setTimer();
        }
        function errorFun() {
            $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
            $('.loadingZakazZvonok .loading .error').addClass('active');
            setTimeout(()=>{
                $('.loadingZakazZvonok .loading .error').removeClass('active');
                closeModal()
            }, 2000)
        }
        function setTimer() {
            return setTimeout(()=>{
                //clear inputs
                $('.contactName').val('');
                $('.contactEmail').val('');
                $('.contactMsg').val('');
            }, 2000)
        }
    },
  modalForm1: async (e)=>{
    let fun = this;
    fun.data = {
      name: $('.zakazatZvanokName').val(),
      phone: $('.zakazatZvanokPhoneNumber').val(),
      message: $('.zakazatZvanokMessage').val(),
    };
    // console.log(fun.data)
    // console.log($('.modalFormInput2').val().length)
    if($('.zakazatZvanokName').val() === '') {
      $('.zakazatZvanokName').addClass('error');
    }
    else {
      $('.zakazatZvanokName').removeClass('error');
    }
    if($('.zakazatZvanokPhoneNumber').val().length != 18) {
      $('.zakazatZvanokPhoneNumber').addClass('error');
    }
    else {
      $('.zakazatZvanokPhoneNumber').removeClass('error');
    }
    if($('.zakazatZvanokName').val() != '' && $('.zakazatZvanokPhoneNumber').val().length == 18) {
      req();
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        url: '/ajax/application/call',
        type: 'POST',
        data: fun.data,
        success: function (a) {
          res();
          return 'ok';
        },
        error: function (error) {
          console.log(error)
          errorFun();
          return 'error';
        }
      })
    }
    function req() {
      $('.zhakazZvanok .input input').removeClass('error');
      closeModal()
      $('.modal_bloor').addClass('active');
      $('.loadingZakazZvonok').addClass('active');
      $('.loadingZakazZvonok .loading .loading_box').addClass('active');
    }
    function res() {
      $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
      $('.loadingZakazZvonok .loading .succes').addClass('active');
      // animationSendModal.play()
      setTimeout(()=>{
        $('.loadingZakazZvonok .loading .succes').removeClass('active');
        closeModal()
      }, 2000)
      setTimer();
    }
    function errorFun() {
      $('.loadingZakazZvonok .loading .loading_box').removeClass('active');
      $('.loadingZakazZvonok .loading .error').addClass('active');
      // animationSendModal.play()
      setTimeout(()=>{
        $('.loadingZakazZvonok .loading .error').removeClass('active');
        closeModal()
      }, 2000)
      // setTimer();
    }
    function setTimer() {
      return setTimeout(()=>{
        //clear inputs
        $('.zakazatZvanokName').val('');
        $('.zakazatZvanokPhoneNumber').val('');
        $('.zakazatZvanokMessage').val('');
        $('.zhakazZvanok .input input').removeClass('error');
      }, 2000)
    }
  },
  modalForm2: async (e)=>{
    let fun = this;
    fun.data = {
      login: $('.signInEmail').val(),
      password: $('.signInPassword').val(),
    };
    // console.log(fun.data)
    // console.log($('.modalFormInput2').val().length)
    if(!emailChecker($('.signInEmail').val())) {
      $('.signInEmail').addClass('error');
    }
    else {
      $('.signInEmail').removeClass('error');
    }
    if($('.signInPassword').val() === '') {
      $('.signInPassword').addClass('error');
    }
    else {
      $('.signInPassword').removeClass('error');
    }
    if(emailChecker($('.signInEmail').val()) && $('.signInPassword').val() != '') {
      if(!$('.signIn .btn').hasClass('inactive')) {
        req();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '/ajax/user/login',
          type: 'POST',
          data: fun.data,
          success: function (a) {
            res();
            return 'ok';
          },
          error: function (error) {
            console.log(error)
            errorFun();
            return 'error';
          }
        })
      }
    }
    function req() {
      $('.signIn .input input').removeClass('error');
      $('.signIn .btn').addClass('inactive')
      // closeModal()
      // showModal(6)
      // $('.loadingModal .loading .loading_box').addClass('active');
    }
    function res() {
      // $('.loadingModal .loading .loading_box').addClass('active');
      // $('.loadingModal .loading .succes').addClass('active');
      $(".questProfileBtn").hide();
      $(".userProfileBtn").show();
      // animationSendModal.play()
      setTimeout(()=>{
        // $('.loadingModal .loading .succes').removeClass('active');
        // animationSendModal.resetSegments()
        closeModal()
        $('.signIn .btn').removeClass('inactive')
        window.location.reload();
      }, 2000)
      setTimer();
    }
    function errorFun() {
      // $('.loadingModal .loading .loading_box').addClass('active');
      // $('.loadingModal .loading .succes').addClass('active');
      setTimeout(()=>{
        // $('.loadingModal .loading .succes').removeClass('active');
        // animationSendModal.resetSegments()
        $('.signIn .btn').removeClass('inactive')
        $('.signInEmail').addClass('error');
        $('.signInPassword').addClass('error');
        $('.signIn .tx.error').addClass('active');
        closeModal()
        showModal(2)
      }, 2000)
      // setTimer();
    }
    function setTimer() {
      return setTimeout(()=>{
        //clear inputs
        $('.signInEmail').val('');
        $('.signInPassword').val('');
        $('.signIn .input input').removeClass('error');
        $('.signIn .tx.error').removeClass('active');
      }, 2000)
    }
  },
  modalForm3: async (e)=>{
    let fun = this;
    fun.data = {
      email: $('.signUpEmail').val(),
      password: $('.signUpPassword').val(),
      password_confirmation: $('.signUpPassword2').val(),
      name: $('.signUpName').val(),
      phone: $('.signUpPhone').val(),
    };
    // console.log(fun.data)
    // console.log($('.modalFormInput2').val().length)
    // console.log($('.signUpPassword').val().length)
    if(!emailChecker($('.signUpEmail').val())) {
      $('.signUpEmail').addClass('error');
    }
    else {
      $('.signUpEmail').removeClass('error');
    }
    if($('.signUpPassword').val().length <= 5) {
      $('.signUpPassword').addClass('error');
      $('.signUp .tx.password.error').addClass('active');
    }
    else {
      $('.signUpPassword').removeClass('error');
      $('.signUp .tx.password.error').removeClass('active');
    }
    if($('.signUpPassword2').val() === '') {
      $('.signUpPassword2').addClass('error');
    }
    else {
      if($('.signUpPassword').val() != $('.signUpPassword2').val()) {
        $('.signUpPassword2').addClass('error');
      }
      else {
        $('.signUpPassword2').removeClass('error');
      }
    }
    if($('.signUpName').val().length === 0) {
      $('.signUpName').addClass('error');
    }
    else {
      $('.signUpName').removeClass('error');
    }
    if($('.signUpPhone').val().length != 18) {
      $('.signUpPhone').addClass('error');
    }
    else {
      $('.signUpPhone').removeClass('error');
    }

    if(emailChecker($('.signUpEmail').val()) && $('.signUpPassword').val().length > 5 && $('.signUpPassword').val() === $('.signUpPassword2').val()
      && $('.signUpName').val().length != 0 && $('.signUpPhone').val().length === 18) {
      if(!$('.signUp .btn').hasClass('inactive')) {
        req();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '/ajax/user/register',
          type: 'POST',
          data: fun.data,
          success: function (a) {
            res();
            return 'ok';
          },
          error: function (error) {
            console.log(error)
            errorFun();
            return 'error';
          }
        })
      }
    }
    function req() {
      closeModal()
      $('.modal_bloor').addClass('active');
      $('.loadingSignUp').addClass('active');
      $('.loadingSignUp .loading .loading_box').addClass('active');
      $('.signUp .btn').addClass('inactive')
    }
    function res() {
      $('.loadingSignUp .loading .loading_box').removeClass('active');
      $('.loadingSignUp .loading .succes').addClass('active');
      setTimeout(()=>{
        $('.signUp .btn').removeClass('inactive')
      }, 2000)
      setTimer();
    }
    function errorFun() {
      $('.loadingSignUp .loading .loading_box').removeClass('active');
      $('.loadingSignUp .loading .error').addClass('active');
      setTimeout(()=>{
        $('.loadingSignUp .loading .error').removeClass('active');
        $('.signUpEmail').addClass('error');
        $('.signUp .tx.email.error').addClass('active');
        closeModal()
        showModal(3);
        $('.signUp .btn').removeClass('inactive')
      }, 2000)
    }
    function setTimer() {
      return setTimeout(()=>{
        //clear inputs
        $('.signUpEmail').val('');
        $('.signUpPassword').val('');
        $('.signUpPassword2').val('');
        $('.signUpName').val('');
        $('.signUpPhone').val('');
        $('.signUp .input input').removeClass('error');
        $('.signUp .tx.email.error').removeClass('active');
      }, 2000)
    }
  },
  modalForm4: async (e)=>{
    let fun = this;
    fun.data = {
      email: $('.vostanovitEmail').val(),
    };

    if(!emailChecker($('.vostanovitEmail').val())) {
      $('.vostanovitEmail').addClass('error');
    }
    else {
      $('.vostanovitEmail').removeClass('error');
    }

    if(emailChecker($('.vostanovitEmail').val())) {
      req();
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
        url: '/ajax/user/recovery',
        type: 'POST',
        data: fun.data,
        success: function (a) {
          res(a);
          return 'ok';
        },
        error: function (error) {
          console.log(error)
          errorFun(error);
          return 'error';
        }
      })
    }
    function req(response) {
      closeModal()
      $('.modal_bloor').addClass('active');
      $('.loadingVosParol').addClass('active');
      $('.loadingVosParol .loading .loading_box').addClass('active');
      $('.loadingVosParol .loading .succes').removeClass('active');
      $('.loadingVosParol .loading .error1').removeClass('active');
      $('.loadingVosParol .loading .error2').removeClass('active');
    }
    function res() {
      $('.loadingVosParol .loading .loading_box').removeClass('active');
      $('.loadingVosParol .loading .succes').addClass('active');
      setTimeout(()=>{
        closeModal()
        showModal(5)
      }, 2000)
      setTimer();
    }
    function errorFun(error) {
      $('.loadingVosParol .loading .loading_box').removeClass('active');
      if(error.responseJSON.message === "The user was not found") {
        $('.loadingVosParol .loading .error2').removeClass('active');
        $('.loadingVosParol .loading .error1').addClass('active');
      }
      else {
        $('.loadingVosParol .loading .error1').removeClass('active');
        $('.loadingVosParol .loading .error2').addClass('active');
      }
      setTimeout(()=>{
        if(error.responseJSON.message === "The user was not found") {
          $('.loadingVosParol .loading .error1').removeClass('active');
          $('.vostanovitEmail').addClass('error');
          $('.vostanovit .tx.error').addClass('active');
          closeModal()
          showModal(5)
        }
        else {
          $('.loadingVosParol .loading .error2').removeClass('active');
          closeModal()
          showModal(5)
        }
      }, 2000)
      // setTimer();
    }
    function setTimer() {
      return setTimeout(()=>{
        //clear inputs
        $('.vostanovitEmail').val('');
        $('.vostanovit .input input').removeClass('error');
        $('.vostanovit .tx.error').removeClass('active');
      }, 2000)
    }
  },
}
function emailChecker(value) {
  let pattern = /^[^ ]+@[^ ]+\.[a-z]{2,10}$/;
  if(value.match(pattern)) {
    return true;
  } else {
    return false;
  }
  if(value == "") {
    return false;
  }
}
