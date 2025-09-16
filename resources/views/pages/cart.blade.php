@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="basketPage">
        <div class="container">
            @include('components.breadcrumbs')
            <div class="titles">{{ $page->sub_title ?: $page->title }}</div>
            @php($isEmpty = !$items->isNotEmpty())
            <div class="box {{ $isEmpty ? '' : 'active' }}">
                <div class="box_left">
                    <div class="head">
                        <div class="item">{{__("Товар")}}</div>
                        <div class="item">{{__("Ед. изм.")}}</div>
                        <div class="item">{{__("Цена")}}</div>
                        <div class="item">{{__("Количество")}}</div>
                        <div class="item">{{__("Сумма")}}</div>
                    </div>
                    <div class="body">
                        @php($totalWeight = 0)
                        @php($totalPrice = 0)
                        @foreach($items as $item)
                            @php($product = $item->associatedModel)
                            <div class="item cartItem" data-id="{{ $product->id }}" data-weight="{{ (int)$product->getData('weight') }}">
                                <svg class="delete" xmlns="http://www.w3.org/2000/svg" width="25" height="30" viewBox="0 0 25 30" fill="none">
                                    <path d="M24.5718 5.52157C24.5718 4.87781 24.05 4.35597 23.4062 4.35597H18.9503C18.792 1.92738 16.7658 0 14.2978 0H10.2739C7.80597 0 5.77981 1.92738 5.62146 4.35597H1.1656C0.52184 4.35597 0 4.87781 0 5.52157C0 6.16067 0.514322 6.67959 1.15161 6.68711L2.10717 25.4145C2.23382 27.8957 4.2791 29.8394 6.76352 29.8394H17.8083C20.2928 29.8394 22.3381 27.8957 22.4646 25.4151L22.4653 25.4018C22.4984 24.7589 22.0041 24.2109 21.3612 24.1778C20.7181 24.1451 20.1703 24.6391 20.1372 25.2819L20.1365 25.2958C20.0732 26.5364 19.0505 27.5082 17.8083 27.5082H6.76352C5.52134 27.5082 4.4987 26.5364 4.43534 25.2958L3.4859 6.68717H21.0859L20.4345 19.4545C20.4017 20.0974 20.8963 20.6451 21.5391 20.6779C22.1821 20.7101 22.7299 20.2161 22.7626 19.5732L23.4201 6.68705C24.0575 6.67959 24.5718 6.16067 24.5718 5.52157ZM7.96274 4.35597C8.11328 3.2149 9.09239 2.3312 10.274 2.3312H14.2979C15.4794 2.3312 16.4585 3.2149 16.6091 4.35597H7.96274Z" fill="#525252"></path>
                                    <path d="M15.8877 12.4907L15.4146 21.7634C15.3818 22.4062 15.8764 22.954 16.5193 22.9868C16.5396 22.9879 16.5596 22.9883 16.5796 22.9883C17.1961 22.9883 17.711 22.5048 17.7427 21.8821L18.2159 12.6094C18.2487 11.9665 17.7541 11.4188 17.1112 11.386C16.4676 11.3537 15.9206 11.8478 15.8877 12.4907Z" fill="#525252"></path>
                                    <path d="M7.46068 11.3859C6.81779 11.4187 6.32317 11.9665 6.35604 12.6094L6.82916 21.8821C6.86092 22.5048 7.37577 22.9883 7.99225 22.9883C8.01224 22.9883 8.03241 22.9878 8.05263 22.9868C8.69552 22.954 9.19014 22.4062 9.15727 21.7633L8.68415 12.4906C8.6514 11.8478 8.10351 11.3532 7.46068 11.3859Z" fill="#525252"></path>
                                    <path d="M11.1201 12.55V21.8227C11.1201 22.4665 11.642 22.9883 12.2857 22.9883C12.9295 22.9883 13.4513 22.4665 13.4513 21.8227V12.55C13.4513 11.9063 12.9295 11.3844 12.2857 11.3844C11.642 11.3844 11.1201 11.9063 11.1201 12.55Z" fill="#525252"></path>
                                </svg>
                                <div class="item_left">
                                    <picture>
                                        @if($photoWebp = $item->attributes->image_webp)
                                            <source srcset="{{$photoWebp}}" type="image/webp">
                                            <source srcset="{{$photoWebp}}" type="image/pjp2">
                                        @endif
                                        <img src="{{ $item->attributes->image }}" alt="{{ $item->name }}">
                                    </picture>
                                </div>
                                <div class="item_right">
                                    <div class="top">{{ $item->name }}</div>
                                    <div class="bottom">
                                        <div class="bottom_har">
                                            @if($productSize = $item->attributes->size)
                                                <div class="size">{{ $productSize }}</div>
                                            @endif
                                            <div class="color">{{ $item->attributes->color }}</div>
                                        </div>
                                        <div class="bottom_units">
                                            @php($count = $product->getData('pallet_count') ?: 1)
                                            @php($priceM2 = $product->getData('price_m2'))
                                            @php($priceM3 = $product->getData('price_m3'))
                                            <div class="unit active"
                                                data-class="pricePiece"
                                                data-pallet-count="{{$count}}">шт</div>
                                            @if($priceM2)
                                                <div class="unit"
                                                    data-class="priceM2"
                                                    data-pallet-count="{{ $product->getData('pallet_count_m2') ?: 1 }}">м&#xB2;</div>
                                            @endif
                                            @if($priceM3)
                                                <div class="unit"
                                                    data-class="priceM3"
                                                    data-pallet-count="{{ $product->getData('pallet_count_m3') ?: 1 }}">м&#xB3;</div>
                                            @endif
                                        </div>
                                        <div class="bottom_costPer nums">
                                            <div class="num pricePiece active">{{ $item->price }}
                                                <div class="currency">₸</div>
                                            </div>
                                            @if($priceM2)
                                                <div class="num priceM2">{{$priceM2}}
                                                    <div class="currency">₸</div>
                                                </div>
                                            @endif
                                            @if($priceM3)
                                                <div class="num priceM3">{{$priceM3}}
                                                    <div class="currency">₸</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="bottom_input">
                                            <div class="number">
                                                <button class="number-minus numberBtn" type="button" onclick="this.nextElementSibling.stepDown();">-</button>
                                                <input class="productNumber" type="number" step="{{$count}}" min="{{$count}}" value="{{ $item->quantity }}">
                                                <button class="number-plus numberBtn" type="button" onclick="this.previousElementSibling.stepUp();">+</button>
                                            </div>
                                            <div class="unit">шт</div>
                                        </div>
                                        <div class="bottom_cost">{{ $item->price * $item->quantity }}
                                            <div class="currency">₸</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php($totalWeight += (float)$product->data["weight"] * $item->quantity)

                            @php($totalPrice += $item->price * $item->quantity)
                        @endforeach
                    </div>
                </div>
                <div class="box_right">
                    <div class="item">
                        <div class="item_left">{{__("Вес")}}</div>
                        <div class="item_right totalWeight">{{ $totalWeight }} кг</div>
                    </div>
                    <div class="item cost">
                        <div class="item_left">{{__("Итого")}}</div>
                        <div class="item_right totalPrice">
                            {{ $totalPrice }}
                            <div class="currency">₸</div>
                        </div>
                    </div>
                    <a href="{{ route('pages.get', 'checkout') }}" class="btn btn_red">{{__("Оформить заказ")}}</a>
                    <a href="{{ route('pages.get', 'catalog') }}" class="btn btn_white">{{__("Продолжить покупки")}}</a>
                </div>
            </div>
            <div class="errorPage {{ $isEmpty ? 'active' : '' }}">
                <div id="left">
                    <img src="{{ asset('images/icons/leftError.svg') }}" alt="left-error icon">
                </div>
                <div class="mobile_pic1">
                    <img src="{{ asset('images/icons/basket_p.svg') }}">
                </div>
                <div class="block1">
                    <div class="desc">{{__("В корзине пока нет товаров")}}</div>
                    <div class="btns">
                        <a class="btn red" href="{{ route('pages.get', 'catalog') }}">{{__("В каталог")}}</a>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {

            $(".basketPage .bottom_units .unit").on('click', function (e) {
                e.preventDefault();

                var elementItem = $(this).parents('.item'), number = $(".productNumber"),
                    unit = $(".basketPage .bottom_units .unit.active"), cartTotal = 0,
                    price = parseInt($("." + unit.attr('data-class')).html());

                number.val(unit.attr('data-pallet-count'))
                number.attr('step', unit.attr('data-pallet-count'))
                number.attr('min', unit.attr('data-pallet-count'))
                number.attr('value', unit.attr('data-pallet-count'))
                $('.bottom_cost').html((price * unit.attr('data-pallet-count')) + '<div class="currency">₸</div>')
                $('.totalWeight').html(3.7 * unit.attr('data-pallet-count')) + ' кг')

                $(".cartItem").each(function (key, elem) {
                    cartTotal += parseInt($(elem).find('.bottom_cost').html());
                })
                $('.totalPrice').html(cartTotal + '<div class="currency">₸</div>')
            })

            $(".productNumber").on('blur', function(e) {
                e.preventDefault();

                var unit = $(".basketPage .bottom_units .unit.active"),
                    pallet_count = parseInt(this.value) / parseInt(unit.attr('data-pallet-count'));

                pallet_count = Math.round(pallet_count) > 0 ? Math.round(pallet_count) : 1;
                $(this).parents('.cartItem').find(".productNumber").val(pallet_count * parseInt(unit.attr('data-pallet-count')));

                queryUpdateBasket(this)
            })

            $('.box.active .numberBtn').on('click', function (e) {
                e.preventDefault();
                queryUpdateBasket(this)
            });

            function queryUpdateBasket(that) {
                let elementItem = $(that).parents('.cartItem');
                console.log(elementItem)
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax/cart/update',
                    type: "POST",
                    data: {
                        productId: elementItem.data('id'),
                        qty: elementItem.find('.number input').val()
                    },
                    success: function(response){
                        var totalItem = parseInt(elementItem.find('.bottom_costPer .num.active').html()),
                            countItem = parseInt(elementItem.find('.number input').val());
                        $('.totalWeight').html(response.total_weight + ' кг')
                        $('.totalPrice').html(response.total + '<div class="currency">₸</div>')
                        elementItem.find('.bottom_cost').html((totalItem * countItem) + '<div class="currency">₸</div>')
                        $('.basketBtn .num').html(response.counter > 9 ? '9+' : response.counter)
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }

            $('.box.active .delete').on('click', function (e) {
                e.preventDefault();
                let elementItem = $(this).parents('.item');
                elementItem.remove();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax/cart/remove',
                    type: "POST",
                    data: { productId: elementItem.data('id')},
                    success: function(response){
                        $('.totalWeight').html(response.total_weight + ' кг')
                        $('.totalPrice').html(response.total + '<div class="currency">₸</div>')
                        $('.basketBtn .num').html(response.counter > 9 ? '9+' : response.counter)

                        if (response.counter === 0) {
                            $('.box').removeClass('active')
                            $('.errorPage').addClass('active')
                            $('.basketBtn .num').hide()
                        }
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            })
        })
    </script>
@endsection
