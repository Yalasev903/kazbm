@if($products->isNotEmpty())
    @foreach($products as $product)
        @php($stock = $product->getStockValue())
        <div class="card {{$stock}}">
            @if($pattern = $product->pattern)
                <picture class="fon">
                    @if($patternImage = $pattern->getWebpFormat('photo'))
                        <source srcset="{{$patternImage}}" type="image/webp">
                        <source srcset="{{$patternImage}}" type="image/pjp2">
                    @endif
                    <img src="{{ $pattern->getRealFormat('photo') }}" alt="{{ $pattern->name }}" loading="lazy">
                </picture>
            @endif
            <div class="card_slider">
                @foreach($product->galleries as $k => $img)
                    <div class="slider-item">
                        <a href="{{ \App\Helpers\Common::getImage($img) }}" data-fancybox="gallery-{{ $product->id }}" data-caption="{{ $product->title }} - изображение {{ $k + 1 }}">
                            <picture>
                                @if($photo = \App\Helpers\Common::getWebpByImage($img))
                                    <source data-lazy="{{ \App\Helpers\Common::getImage($photo) }}" type="image/webp">
                                    <source data-lazy="{{ \App\Helpers\Common::getImage($photo) }}" type="image/pjp2">
                                @endif
                                <img data-lazy="{{ \App\Helpers\Common::getImage($img) }}" alt="slider image{{$k}}" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200'%3E%3Crect width='100%25' height='100%25' fill='%23f0f0f0'/%3E%3C/svg%3E">
                            </picture>
                        </a>
                        <a href="{{ route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug]) }}" class="product-link" style="position: absolute; bottom: 0; left: 0; right: 0; height: 30%; z-index: 2; background: transparent;"></a>
                    </div>
                @endforeach
            </div>
            <div class="block">
                <div class="top">
                    <a class="block_title" href="{{ route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug]) }}">
                        <p>{{ $product->title }}</p>
                    </a>
                    <div class="status">
                        <div class="block_status {{$stock}}">
                            <img src="{{ asset("images/icons/$stock.svg") }}" alt="{{$stock}} icon{{$product->id}}">
                            <p>{{ \App\Enums\ProductStockEnum::label($stock) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bottom">
                    @if($product->size_id)
                        <div class="size"><span>{{__("Размер")}}</span>
                            <p>{{ $product->size->name }}</p></div>
                    @endif
                    <div class="color"><span>{{__("Цветовая гамма")}}</span>
                        <p>{{ __($product->color->name_w) }}</p>
                    </div>
                    @php($user = auth()->guard('site')->user())
                    @if($user && $user->status == \App\Enums\UserStatusEnum::STATUS_WHOLESALER)
                        <div class="count"><span>{{__("Остаток на складе:")}}</span>
                            <p>{{ $product->stock }} {{ $product->getPerPieceLabel() }}</p>
                        </div>
                    @endif
                    @if ($product->stock > 0)
                        <div class="btn_row have few {{ !$product->hasCart() ? 'active' : ''}}">
                            <a class="btn" href="{{ route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug]) }}">
                                {{ $product->price }}&nbsp;
                                <div class="currency">₸</div>
                            </a>
                            <div class="basket" onclick="basketAt(this)" data-id="{{ $product->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="51" height="51" viewBox="0 0 51 51"
                                     fill="none">
                                    <rect width="51" height="51" rx="10" fill="#CE2329"></rect>
                                    <path
                                        d="M41 21.0781V25.7656C41 26.4129 40.4754 26.9375 39.8281 26.9375H39.0575L38.5638 31.3313C38.4965 31.9301 37.9893 32.3723 37.4006 32.3725C37.3569 32.3725 37.3129 32.37 37.2683 32.365C36.6251 32.2929 36.1623 31.7127 36.2346 31.0697L36.8453 25.6349C36.9119 25.0421 37.4132 24.5937 38.0099 24.5937H38.6565V22.25H13.3438V24.5937H33.3244C33.9717 24.5937 34.4963 25.1186 34.4963 25.7656C34.4963 26.4129 33.9717 26.9375 33.3244 26.9375H15.3227L16.4799 36.5915C16.6209 37.7686 17.6214 38.6562 18.807 38.6562H33.2871C34.4814 38.6562 35.4828 37.7611 35.6162 36.5741C35.6883 35.9309 36.2683 35.4684 36.9115 35.5405C37.5546 35.6128 38.0174 36.1928 37.9453 36.8359C37.6784 39.2097 35.6759 41 33.2871 41H18.807C16.4358 41 14.4349 39.2246 14.1526 36.8703L12.9622 26.9375H12.1719C11.5246 26.9375 11 26.4129 11 25.7656V21.0781C11 20.4308 11.5246 19.9062 12.1719 19.9062H16.3284L22.5209 11.4781C22.904 10.9567 23.6373 10.8443 24.159 11.2277C24.6806 11.6109 24.7928 12.3442 24.4096 12.8658L19.2368 19.9062H32.8067L27.6337 12.8658C27.2505 12.3442 27.3627 11.6109 27.8843 11.2277C28.4059 10.8443 29.1393 10.9565 29.5226 11.4781L35.7148 19.9062H39.8281C40.4754 19.9062 41 20.4311 41 21.0781ZM24.8282 30.4531V35.1406C24.8282 35.7879 25.3528 36.3125 26.0001 36.3125C26.6472 36.3125 27.172 35.7879 27.172 35.1406V30.4531C27.172 29.8058 26.6472 29.2812 26.0001 29.2812C25.3528 29.2812 24.8282 29.8058 24.8282 30.4531ZM29.5158 30.4531V35.1406C29.5158 35.7879 30.0404 36.3125 30.6877 36.3125C31.3347 36.3125 31.8595 35.7879 31.8595 35.1406V30.4531C31.8595 29.8058 31.3347 29.2812 30.6877 29.2812C30.0404 29.2812 29.5158 29.8058 29.5158 30.4531ZM20.1407 30.4531V35.1406C20.1407 35.7879 20.6653 36.3125 21.3126 36.3125C21.9596 36.3125 22.4845 35.7879 22.4845 35.1406V30.4531C22.4845 29.8058 21.9596 29.2812 21.3126 29.2812C20.6653 29.2812 20.1407 29.8058 20.1407 30.4531Z"
                                        fill="#F0EEE9"></path>
                                </svg>
                            </div>
                        </div>
                    @else
                        <div class="btn_row havent">
                            <div class="btn">{{__("Нет в наличии")}}</div>
                        </div>
                    @endif
                    <div class="btn_row goBasket {{ $product->hasCart() ? 'active' : '' }}">
                        <a class="btn" href="{{ route('pages.get', 'cart') }}">{{__("Перейти в корзину")}}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
