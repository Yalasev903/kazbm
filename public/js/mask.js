[].forEach.call(document.querySelectorAll('input[type="tel"]'), function (input) {
  let keyCode;
  function mask(event) {
    event.keyCode && (keyCode = event.keyCode);
    let pos = this.selectionStart;
    if (pos < 3) event.preventDefault();
    let matrix = "+7 (___) ___-__-__",
      i = 0,
      def = matrix.replace(/\D/g, ""),
      val = this.value.replace(/\D/g, ""),
      newValue = matrix.replace(/[_\d]/g, function (a) {
        return i < val.length ? val.charAt(i++) || def.charAt(i) : a;
      });
    i = newValue.indexOf("_");
    if (i != -1) {
      i < 5 && (i = 3);
      newValue = newValue.slice(0, i);
    }
    let reg = matrix.substr(0, this.value.length).replace(/_+/g,
      function (a) {
        return "\\d{1," + a.length + "}";
      }).replace(/[+()]/g, "\\$&");
    reg = new RegExp("^" + reg + "$");
    // if(this.value.length >= 18) {
    //   this.setAttribute('style', 'border:none');
    // } else {
    //   this.setAttribute('style', 'border:2px solid #FF4645');
    // }
    if (!reg.test(this.value) || this.value.length < 5 || keyCode > 47 && keyCode < 58) this.value = newValue;
    if (event.type == "blur" && this.value.length < 5) this.value = "";
  }

  input.addEventListener("input", mask, false);
  input.addEventListener("focus", mask, false);
  input.addEventListener("blur", mask, false);
  input.addEventListener("keydown", mask, false);
});
[].forEach.call(document.querySelectorAll('input[type="text"]'), function (input) {
  let keyCode;
  function mask3(event) {
    // if(this.value.length != 0) this.setAttribute('style', 'border:none');
    // else this.setAttribute('style', 'border:2px solid #FF4645');
  }
  input.addEventListener("input", mask3, false);
  input.addEventListener("focus", mask3, false);
  input.addEventListener("blur", mask3, false);
  input.addEventListener("keydown", mask3, false);
});

[].forEach.call(document.querySelectorAll('input[type="iin"]'), function (input) {
  function mask2(event) {
    let val = this.value.replace(/\D/g, "");
    let foo = val.split("-").join("");
    if (foo.length > 0) {
      foo = foo.match(new RegExp('.{1,3}', 'g')).join("-");
    }
    this.value = foo;
    this.value = this.value.slice(0, 15);
  }

  input.addEventListener("input", mask2, false);
  input.addEventListener("focus", mask2, false);
  input.addEventListener("blur", mask2, false);
  input.addEventListener("keydown", mask2, false);
});

const element =document.querySelectorAll("input[type='number']");
element.forEach((el) => {
  el.addEventListener('keypress', (evt) => {
    const regex = /(^\d*[.]?\d*$)|(Backspace|Control|Delete|-|Meta|Arrow|e|ctrlKey + a)/;
    const value = evt.target.value;
    const isValid = regex.test(evt.key)
    const keyString = evt.key.toString();
    if (!isValid) {                     evt.preventDefault();
    }
    if ((value.includes('.') && keyString == '.') ||
      (value.includes('e') && keyString == 'e') ||
      ( (value.includes('-') || value.startswith('-')) && keyString == '-')
    ){
      evt.preventDefault();
    }

  })
  el.addEventListener('paste', (evt) => {
    !/(^\d*\.?\d*$)|(Backspace|Control|Delete|Meta|Arrow|-|ctrlKey + a)/.test(evt.key) && evt.preventDefault();
  })
})
