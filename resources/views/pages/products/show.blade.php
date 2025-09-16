@extends('layouts.app')
@section('page_title',(strlen($product->title) > 1 ? $product->title : ''))
@section('seo_title', (strlen($product->seo_title) > 1 ? $product->seo_title : ''))
@section('meta_keywords',(strlen($product->meta_keywords) > 1 ? $product->meta_keywords : ''))
@section('meta_description', (strlen($product->meta_description) > 1 ? $product->meta_description : ''))
@section('content')
    <main class="productPage">
        <div class="container">
            @php($category = $product->category)
            @include('components.breadcrumbs', [
                'title' => $product->title,
                'parents' => [
                    'Каталог' => route('pages.get', 'catalog'),
                    $category->name => route('category.show', $category->slug)
                ]
            ])
            <div class="block1">
                <div class="left">
                    <div class="banner">
                        @foreach($product->galleries as $i => $img)
                            <div class="item {{ $i == 0 ? 'active' : '' }}">
                                @if($pattern = $product->pattern)
                                    <picture class="fon">
                                        @if($patternImage = $pattern->getWebpFormat('photo'))
                                            <source srcset="{{$patternImage}}" type="image/webp">
                                            <source srcset="{{$patternImage}}" type="image/pjp2">
                                        @endif
                                        <img src="{{ $pattern->getRealFormat('photo') }}" alt="{{ $pattern->name }}" loading="lazy">
                                    </picture>
                                @endif
                                <a href="{{ \App\Helpers\Common::getImage($img) }}" data-fancybox="product-gallery" data-caption="{{ $product->title }} - изображение {{ $i + 1 }}">
                                    <picture>
                                        @if($photo = \App\Helpers\Common::getWebpByImage($img))
                                            <source data-lazy="{{ \App\Helpers\Common::getImage($img) }}" type="image/webp">
                                            <source data-lazy="{{ \App\Helpers\Common::getImage($img) }}" type="image/pjp2">
                                        @endif
                                        <img data-lazy="{{ \App\Helpers\Common::getImage($img) }}" alt="banner image{{$i}}" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='400'%3E%3Crect width='100%25' height='100%25' fill='%23f0f0f0'/%3E%3C/svg%3E">
                                    </picture>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="right">
                    <h1 class="title">{{ $product->title }}</h1>
                    <div class="items">
                        @if($product->size_id)
                            <div class="item">
                                <div class="item_left">{{__("Размер")}}</div>
                                <div class="item_right">{{ $product->size->name }}</div>
                            </div>
                        @endif
                        <div class="item">
                            <div class="item_left">{{__("Вес")}}</div>
                            <div class="item_right">{{ $product->getData('weight') }}</div>
                        </div>
                        <div class="item">
                            <div class="item_left">{{__("Цветовая гамма")}}</div>
                            <div class="item_right">{{ __($product->color->name_w) }}</div>
                        </div>
                    </div>
                    <div class="items">
                        <div class="item">
                            <div class="item_left">
                                <div class="desc">{{__("Цена за")}}:</div>
                                @php($step = $product->getData('pallet_count') ?: 1)
                                @php($priceM2 = $product->getData('price_m2'))
                                @php($priceM3 = $product->getData('price_m3'))
                                <div class="units">
                                    <div class="unit {{ $priceM2 || $priceM3 ? 'active' : '' }}"
                                         data-class="pricePiece"
                                         data-pallet-count="{{ $step }}"
                                         data-pallet-weight="{{ $product->getData('pallet_weight') }}"
                                    >шт</div>
                                    @if($priceM2)
                                        <div class="unit"
                                             data-class="priceM2"
                                             data-pallet-count="{{ $product->getData('pallet_count_m2') ?: 1 }}"
                                             data-pallet-weight="{{ $product->getData('pallet_weight_m2') }}"
                                        >м&#xB2;</div>
                                    @endif
                                    @if($priceM3)
                                        <div class="unit"
                                             data-class="priceM3"
                                             data-pallet-count="{{ $product->getData('pallet_count_m3') ?: 1 }}"
                                             data-pallet-weight="{{ $product->getData('pallet_weight_m3') }}"
                                        >м&#xB3;</div>
                                    @endif
                                </div>
                            </div>
                            <div class="item_right nums">
                                <div class="num active pricePiece">{{ $product->price }}
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
                        </div>
                        @php($user = auth()->guard('site')->user())
                        @if($user && $user->status == \App\Enums\UserStatusEnum::STATUS_WHOLESALER)
                            <div class="item">
                                <div class="item_left">{{__("Количество на складе")}}</div>
                                <div class="item_right">
                                    @if($category->id != 2)
                                        {{ $product->stock }} {{ $product->getPerPieceLabel() }}
                                    @else
                                        @php($stockValue = $product->getStockValue())
                                        <div class="item_have {{ $stockValue == \App\Enums\ProductStockEnum::STOCK_HAVE ? 'active' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px" width="18" height="13" viewBox="0 0 18 13" fill="none">
                                                <path d="M17 1L6 12L1 7" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>Много
                                        </div>
                                        <div class="item_havent {{ $stockValue == \App\Enums\ProductStockEnum::STOCK_HAVENT ? 'active' : '' }}">
                                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.8">
                                                    <path d="M15.2012 1L1.00003 15.2011" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M1 1L15.2011 15.2011" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </g>
                                            </svg>Нет в наличии
                                        </div>
                                        <div class="item_few {{ $stockValue == \App\Enums\ProductStockEnum::STOCK_FEW ? 'active' : '' }}">
                                            <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="4" cy="4" r="4" fill="black"></circle>
                                            </svg>Мало
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="all">
                        <div class="all_left">{{__("Итого")}}</div>
                        <div class="all_right productTotal">{{ $product->price * $step }}
                            <div class="currency">₸</div>
                        </div>
                    </div>
                    <div class="box {{ $category->id == 2 ? 'active' : ''}}">
                        <div class="calcMini">
                            <div class="number">
                                <button class="number-minus" type="button" onclick="this.nextElementSibling.stepDown();">-</button>
                                <input class="productNumber" type="number" step="{{$step}}" min="{{$step}}" value="{{$step}}">
                                <button class="number-plus" type="button" onclick="this.previousElementSibling.stepUp();">+</button>
                            </div>
                            @if ($category->id != 2)
                                <div class="equals">
                                    <div class="top">= {{ $product->getData('pallet_weight') }}</div>
                                    <div class="bottom">1 паллет</div>
                                </div>
                            @endif
                        </div>
<div class="btns" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-start;">
                            <div class="btn left" onclick="showProductModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="30" viewBox="0 0 22 30" fill="none">
                                    <path d="M19.1318 0H2.84628C1.27685 0 0 1.27685 0 2.84628V26.7405C0 28.3099 1.27685 29.5867 2.84628 29.5867H19.1318C20.7012 29.5867 21.9781 28.3099 21.9781 26.7405V2.84628C21.9781 1.27685 20.7012 0 19.1318 0ZM3.90112 4.91741C3.90112 4.43871 4.28921 4.05061 4.76792 4.05061H17.2101C17.6888 4.05061 18.0769 4.43871 18.0769 4.91741V6.62893C18.0769 7.10764 17.6888 7.49573 17.2101 7.49573H4.76792C4.28921 7.49573 3.90112 7.10764 3.90112 6.62893V4.91741ZM6.8194 24.3811C6.8194 24.8598 6.4313 25.2479 5.9526 25.2479H4.76797C4.28927 25.2479 3.90117 24.8598 3.90117 24.3811V23.5598C3.90117 23.0811 4.28927 22.693 4.76797 22.693H5.9526C6.4313 22.693 6.8194 23.0811 6.8194 23.5598V24.3811ZM6.8194 19.2514C6.8194 19.7301 6.4313 20.1182 5.9526 20.1182H4.76797C4.28927 20.1182 3.90117 19.7301 3.90117 19.2514V18.4301C3.90117 17.9514 4.28927 17.5633 4.76797 17.5633H5.9526C6.4313 17.5633 6.8194 17.9514 6.8194 18.4301V19.2514ZM12.5192 24.3811C12.5192 24.8598 12.1311 25.2479 11.6524 25.2479H10.4678C9.98899 25.2479 9.60096 24.8598 9.60096 24.3811V23.5598C9.60096 23.0811 9.98899 22.693 10.4678 22.693H11.6524C12.1311 22.693 12.5192 23.0811 12.5192 23.5598V24.3811ZM12.5192 19.2514C12.5192 19.7301 12.1311 20.1182 11.6524 20.1182H10.4678C9.98899 20.1182 9.60096 19.7301 9.60096 19.2514V18.4301C9.60096 17.9514 9.98899 17.5633 10.4678 17.5633H11.6524C12.1311 17.5633 12.5192 17.9514 12.5192 18.4301V19.2514ZM12.5192 14.1216C12.5192 14.6004 12.1311 14.9884 11.6524 14.9884H4.76792C4.28921 14.9884 3.90112 14.6003 3.90112 14.1216V13.3003C3.90112 12.8216 4.28921 12.4335 4.76792 12.4335H11.6524C12.1311 12.4335 12.5192 12.8216 12.5192 13.3003V14.1216ZM18.219 24.3811C18.219 24.8598 17.8309 25.2479 17.3522 25.2479H16.1675C15.6888 25.2479 15.3007 24.8598 15.3007 24.3811V23.5598C15.3007 23.0811 15.6888 22.693 16.1675 22.693H17.3522C17.8309 22.693 18.219 23.0811 18.219 23.5598V24.3811ZM18.219 19.2514C18.219 19.7301 17.8309 20.1182 17.3522 20.1182H16.1675C15.6888 20.1182 15.3007 19.7301 15.3007 19.2514V18.4301C15.3007 17.9514 15.6888 17.5633 16.1675 17.5633H17.3522C17.8309 17.5633 18.219 17.9514 18.219 18.4301V19.2514ZM18.219 14.1216C18.219 14.6004 17.8309 14.9884 17.3522 14.9884H16.1675C15.6888 14.9884 15.3007 14.6003 15.3007 14.1216V13.3003C15.3007 12.8216 15.6888 12.4335 16.1675 12.4335H17.3522C17.8309 12.4335 18.219 12.8216 18.219 13.3003V14.1216Z" fill="#F0EEE9"></path>
                                </svg>
                                <span>Калькулятор</span>
                            </div>
                            <div class="btn right" onclick="basketAt(this)" data-id="{{ $product->id }}" style="display: {{ $product->hasCart() ? 'none' : 'flex' }}">
                                {{__("Добавить в корзину")}}
                            </div>
		
                            <div class="btn right goBasket" style="display: {{ $product->hasCart() ? 'flex' : 'none' }}">
                                <a class="btn" href="{{ route('pages.get', 'cart') }}">{{__("Перейти в корзину")}}</a>
                            </div>
												@if ($product->brick_texture_file)
<div class="btn" style="display: flex; align-items: center; justify-content: center; padding: 4px 0;">
    <a href="{{ asset('storage/' . $product->brick_texture_file) }}" 
       download
       style="
           display: flex; 
           align-items: center; 
           justify-content: center; 
           padding: 8px 16px; 
           border-radius: 4px; 
           color: white; 
           font-size: 18px; 
           font-style: normal; 
           font-weight: 600; 
           line-height: normal; 
           text-decoration: none;
       ">
        Скачать текстуру кирпича
    </a>
</div>



@endif
                        </div>
                    </div>
                </div>
            </div>
            @if($parameters = $product->getParameters())
                <div class="block2">
                    <div class="title">{{__("Характеристики")}}:</div>
                    <div class="box">
                        @foreach($parameters as $items)
                            <div class="items">
                                @foreach($items as $key => $value)
                                    <div class="item">
                                        <div class="item_left">{{$key}}</div>
                                        <div class="item_center"></div>
                                        <div class="item_right">{{$value}}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="block3">
                @if($description = $product->description)
                    <div class="title">{{__("Описание")}}:</div>
                    <div class="desc">{!! $description !!}</div>
                @endif
            </div>
            <div class="modalCalcProduct_bloor" onclick="closeProductModal()"></div>
            <div class="modalCalcProduct">
                <svg class="close" xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none" onclick="closeProductModal()">
                    <path d="M20.5644 18.5184L36.6129 2.46982C37.1779 1.9048 37.1779 0.98872 36.6129 0.423768C36.0479 -0.141184 35.1318 -0.141256 34.5668 0.423768L18.5183 16.4723L2.46982 0.423768C1.9048 -0.141256 0.98872 -0.141256 0.423768 0.423768C-0.141184 0.988792 -0.141256 1.90487 0.423768 2.46982L16.4722 18.5183L0.423768 34.5668C-0.141256 35.1319 -0.141256 36.0479 0.423768 36.6129C0.706244 36.8954 1.07654 37.0366 1.44683 37.0366C1.81712 37.0366 2.18734 36.8954 2.46989 36.6129L18.5183 20.5644L34.5668 36.6129C34.8492 36.8954 35.2195 37.0366 35.5898 37.0366C35.9601 37.0366 36.3303 36.8954 36.6129 36.6129C37.1779 36.0479 37.1779 35.1318 36.6129 34.5668L20.5644 18.5184Z" fill="#3B3535"></path>
                </svg>
                <div class="homePage">
                    @include('components.blocks.calculator', ['icon_name' => 'home'])
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(function () {

            $(".productNumber").val('{{ $step }}');

            // Change all cost
            $(".calcMini button").on('click', (e) => {
                var price = parseInt($(".pricePiece").html())
                change_numbers(e, price)
            })
            $(".productNumber").on('blur', (e) => {
                var price = parseInt($(".pricePiece").html())
                change_numbers(e, price)
            })
            $(".productPage .unit").on('click', function (e) {

                var element = $(this), number = $(".productNumber"),
                    price = parseInt($("." + element.attr('data-class')).html())

                number.val(element.attr('data-pallet-count'))
                number.attr('step', element.attr('data-pallet-count'))
                number.attr('min', element.attr('data-pallet-count'))
                number.attr('value', element.attr('data-pallet-count'))

                change_numbers(e, price)
            })

            function change_numbers(e, price) {
                e.preventDefault();
                var number = $(".productNumber"),
                    unit = $(".productPage .unit.active").length > 0 ? $(".productPage .unit.active") : $(".productPage .unit");

                if ($(".calcMini .equals").length > 0) {
                    var pallet_weight = parseInt(unit.data('pallet-weight')),
                        pallet_count = parseInt(number.val()) / parseInt(unit.data('pallet-count'));

                    if (e.type === 'blur') {
                        pallet_count = Math.round(pallet_count) > 0 ? Math.round(pallet_count) : 1;
                        $(".productNumber").val(pallet_count * parseInt(unit.data('pallet-count')));
                    }

                    $(".calcMini .equals .top").html('= '+ (pallet_count * pallet_weight) +' кг');
                    $(".calcMini .equals .bottom").html(pallet_count + ' ' + num_word(pallet_count, ['паллет', 'паллета', 'паллетов']))
                }

                $(".productTotal").html(price * parseInt(number.val()) + '<div class="currency">₸</div>');
            }

            function num_word(value, words) {

                value = Math.abs(value) % 100;
                var num = value % 10;

                if(value > 10 && value < 20) return words[2];

                if(num > 1 && num < 5) return words[1];

                if(num === 1) return words[0];

                return words[2];
            }
        })
    </script>
@endsection
