{{-- scripts.blade.php --}}
<script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('js/mask.min.js?v='. time()) }}"></script>
<script src="{{ asset('js/toastr.min.js?v='. time()) }}"></script>

<script>
// КРИТИЧЕСКИЕ ФУНКЦИИ ДЛЯ ПЕРВОГО ВЗАИМОДЕЙСТВИЯ
window.__CRITICAL_LOADED = true;

// Остальные скрипты загружаем с defer
const deferredScripts = [
    '{{ asset('js/slick.min.js') }}',
    '{{ asset('js/lottie.min.js') }}',
    '{{ asset('js/baguetteBox.min.js') }}',
    '{{ asset('js/fancybox.min.js') }}',
    '{{ asset('js/wow.min.js') }}',
    '{{ asset('js/jquery-ui.min.js') }}',
    '{{ asset('js/jquery.ui.touch-punch.min.js') }}',
    '{{ asset('js/script.js?v='. time()) }}',
    '{{ asset('js/calc.js?v='. time()) }}',
    '{{ asset('js/api.js?v='. time()) }}',
    '{{ asset('js/slides.min.js?v='. time()) }}',
    '{{ asset('js/image-optimization.js?v='. time()) }}'
];

// Функция для загрузки отложенных скриптов
function loadDeferredScripts() {
    deferredScripts.forEach(src => {
        const script = document.createElement('script');
        script.src = src;
        script.defer = true;
        document.head.appendChild(script);
    });
}

// Загружаем когда страница почти готова
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadDeferredScripts);
} else {
    setTimeout(loadDeferredScripts, 500);
}

// Код toastr и поиска (оставляем как было)
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
})

</script>
