<script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>

{{-- ОСНОВНЫЕ СКРИПТЫ В ПРАВИЛЬНОМ ПОРЯДКЕ --}}
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/mask.min.js') }}"></script>

<script>
    // Инициализация toastr опций
    if (typeof toastr !== 'undefined') {
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
        };
    }

    @if (\Session::has('message'))
        setTimeout(() => {
            if (typeof toastr !== 'undefined') toastr.success("{{ \Session::get('message') }}");
        }, 800)
    @elseif(\Session::has('error'))
        setTimeout(() => {
            if (typeof toastr !== 'undefined') toastr.error('{{ \Session::get('error') }}');
        }, 800)
    @endif

    // Search products
    $(function() {
        $(".searchPlatform input").off('input.search').on('input.search', function (e) {
            e.preventDefault();
            var sendForm = $(this).parent();
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

