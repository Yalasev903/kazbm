<script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('js/image-optimization.js?v='. time()) }}" defer></script>

<script src="{{ asset('js/slick.min.js') }}" defer></script>
<script src="{{ asset('js/lottie.min.js') }}" defer></script>
<script src="{{ asset('js/baguetteBox.min.js') }}" defer></script>
<script src="{{ asset('js/fancybox.min.js') }}" defer></script>
<script src="{{ asset('js/wow.min.js') }}" defer></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}" defer></script>
<script src="{{ asset('js/script.js?v='. time()) }}" defer></script>
<script src="{{ asset('js/calc.js?v='. time()) }}" defer></script>
<script src="{{ asset('js/api.js?v='. time()) }}" defer></script>
<script src="{{ asset('js/mask.min.js?v='. time()) }}" defer></script>
<script src="{{ asset('js/slides.min.js?v='. time()) }}" defer></script>
<script src="{{ asset('js/toastr.min.js?v='. time()) }}"></script>
<script>

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
@yield('scripts')
