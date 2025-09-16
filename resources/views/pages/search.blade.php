@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="searchPage">
        <div class="container">
            @include('.components.breadcrumbs')
            <div class="titles">{{ $page->sub_title ?: $page->title }}</div>
            <div class="block1">
                <form class="headSearch">
                    <input type="text" name="query" placeholder="Кирпич" value="{{ request('query') }}">
                    <button type="submit" class="search">
                        <svg width="30" height="31" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.0044 3.92754C23.7087 7.63184 24.4101 13.2188 22.1097 17.6366L28.9717 24.4985C30.3428 25.8696 30.3428 28.1005 28.9717 29.4716C27.6006 30.8426 25.3697 30.8427 23.9987 29.4716L19.8195 25.2925C19.3617 24.8347 19.3617 24.0926 19.8195 23.6348C20.2773 23.1771 21.0194 23.1771 21.4772 23.6348L25.6563 27.8139C26.1133 28.2709 26.857 28.2709 27.3139 27.8139C27.771 27.3569 27.771 26.6133 27.3139 26.1563L20.7919 19.6342C20.5464 19.9341 20.2841 20.2246 20.0044 20.5044C15.4341 25.0746 7.99783 25.0746 3.42755 20.5044C-1.14262 15.9341 -1.14262 8.49783 3.42755 3.9276C7.99789 -0.642686 15.4341 -0.642686 20.0044 3.92754ZM5.08532 18.8467C8.74145 22.5027 14.6906 22.5028 18.3467 18.8467C22.0028 15.1905 22.0028 9.24144 18.3467 5.58526C14.6905 1.92914 8.74139 1.92908 5.08532 5.58526C1.4292 9.24138 1.4292 15.1904 5.08532 18.8467Z" fill="#3B3535"></path>
                            <path d="M16.6891 7.24288C17.1469 7.70067 17.1469 8.44281 16.6892 8.90054C16.2313 9.35832 15.4892 9.35832 15.0315 8.90054C13.2034 7.07251 10.2289 7.07251 8.40069 8.90054C7.94291 9.35832 7.20076 9.35832 6.74303 8.90054C6.2853 8.44276 6.2853 7.70061 6.74309 7.24288C9.48517 4.50075 13.947 4.50075 16.6891 7.24288Z" fill="#3B3535"></path>
                        </svg>
                    </button>
                </form>
                <div class="resultCount">Найдено товаров по запросу “{{ request('query') }}”: {{ $products->total() }}</div>
                <div class="card_box searchItems">
                    @include('components.catalog.items')
                </div>
            </div>
            <div class="pogination_block">{{ $products->links() }}</div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {

            getPaginations();

            function getPaginations() {
                let items = $(".pogination .pagination_item:not('.active')")
                console.log(items)
                if (items.length > 0) {
                    items.each(function () {
                        $(this).on('click', function (e) {
                            e.preventDefault();
                            let urlParams = $(this).data('href').split('?');
                            var dataType = urlParams[1].split('=')[1];
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/ajax/filter/products?page=' + (dataType ? dataType : 1),
                                type: "POST",
                                success: function(response){

                                    $(".searchItems").html(response.html)
                                    $(".searchPage .pogination_block").html(response.paginate)
                                    getPaginations()

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
                                        const $slider = $(this);
                                        const $activeSlides = $slider.find('.slick-active');
                                        const $nextSlides = $slider.find('.slick-active').next();
                                        const $prevSlides = $slider.find('.slick-active').prev();
                                        
                                        $activeSlides.add($nextSlides).add($prevSlides).find('img[data-lazy]').each(function() {
                                            const $img = $(this);
                                            const src = $img.attr('data-lazy');
                                            if (src && !$img.attr('src').includes('data:image')) {
                                                $img.attr('src', src).removeAttr('data-lazy');
                                            }
                                        });
                                    });
                                },
                                error: function(data) {
                                    console.log(data)
                                }
                            });
                        })
                    })
                }
            }
        })
    </script>
@endsection
