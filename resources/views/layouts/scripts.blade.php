<script src="{{ asset(path: 'js/jquery-3.6.3.min.js') }}"></script>

{{-- ОСНОВНЫЕ СКРИПТЫ В ПРАВИЛЬНОМ ПОРЯДКЕ --}}
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/mask.min.js') }}"></script>

<script>
    // Убедимся, что jQuery загружен
if (typeof jQuery === 'undefined') {
    console.error('jQuery не загружен! Перезагружаем...');
    document.write('<script src="{{ asset('js/jquery-3.6.3.min.js') }}"><\/script>');
    // Перезагружаем зависимые скрипты
    document.write('<script src="{{ asset('js/jquery-ui.min.js') }}"><\/script>');
    document.write('<script src="{{ asset('js/toastr.min.js') }}"><\/script>');
}
// Инициализация toastr ДО использования
toastr.options = {
    "closeButton": true,
    "newestOnTop": false,
    "progressBar": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

@if (\Session::has('message'))
    setTimeout(() => {
        toastr.success("{{ \Session::get('message') }}");
    }, 2000)
@elseif(\Session::has('error'))
    setTimeout(() => {
        toastr.error('{{ \Session::get('error') }}');
    }, 2000)
@endif

// Search products
$(document).ready(function() {
    $(".searchPlatform input").on('input', function (e) {
        e.preventDefault();
        let sendForm = $(this).parent();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ajax.product.search') }}',
            type: "POST",
            data: sendForm.serialize(),
            success: function(response){
                $(".searchPlatform_result").html('')
                $(".searchPlatform").append(response.html);
            },
            error: function(data) {
                console.log(data)
            }
        });
    });
});

if (typeof jQuery === 'undefined') {
    console.error('jQuery не загружен!');
    // Перезагружаем jQuery
    document.write('<script src="{{ asset(path: 'js/jquery-3.6.3.min.js') }}"><\/script>');
}

</script>

{{-- ОСТАЛЬНЫЕ СКРИПТЫ С DEFER --}}
<script defer src="{{ asset('js/slick.min.js') }}"></script>
<script defer src="{{ asset('js/lottie.min.js') }}"></script>
<script defer src="{{ asset('js/baguetteBox.min.js') }}"></script>
<script defer src="{{ asset('js/fancybox.min.js') }}"></script>
<script defer src="{{ asset('js/wow.min.js') }}"></script>
<script defer src="{{ asset('js/script.js?v='. time()) }}"></script>
<script defer src="{{ asset('js/calc.js?v='. time()) }}"></script>
<script defer src="{{ asset('js/api.js?v='. time()) }}"></script>
<script defer src="{{ asset('js/slides.min.js?v='. time()) }}"></script>
<script defer src="{{ asset('js/image-optimization.js?v='. time()) }}"></script>

