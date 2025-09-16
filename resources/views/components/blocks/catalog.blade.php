@if($products->isNotEmpty())
    <div class="block2">
        <div class="head">
            <div class="titles">Каталог</div>
            <div class="arrows">
                <svg class="arrow_left" width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="50.75" width="50" height="50" rx="5" transform="rotate(90 50.75 0)" fill="#F0EEE9"></rect>
                    <path d="M32.75 10L17.75 25L32.75 40" stroke="#CE2329" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <svg class="arrow_right" width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="50.75" width="50" height="50" rx="5" transform="rotate(90 50.75 0)" fill="#F0EEE9"></rect>
                    <path d="M17.75 40.0001L32.75 25.0001L17.75 10.0001" stroke="#CE2329" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>
        <div class="slider">
            @foreach($products as $product)
                <div class="item">
                    <a href="{{ $product->getRealFormat('photo') }}" data-fancybox="catalog-gallery" data-caption="{{ $product->title }}">
                        <picture class="bgi">
                            @if($photo = $product->getWebpFormat('photo'))
                                <source data-lazy="{{$photo}}" type="image/webp">
                                <source data-lazy="{{$photo}}" type="image/pjp2">
                            @endif
                            <img data-lazy="{{ $product->getRealFormat('photo') }}" alt="{{ $product->title }}" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200'%3E%3Crect width='100%25' height='100%25' fill='%23f0f0f0'/%3E%3C/svg%3E">
                        </picture>
                    </a>
                    <div class="item_bottom">
                        <div class="item_title">{{ $product->title }}</div>
                        <div class="item_column">
                            <div class="item_row color">
                                <div class="text">{{__("Цвет")}}:</div>
                                <div class="text">{{ $product->color->name_m }}</div>
                            </div>
                            <div class="item_row massa">
                                <div class="text">{{__("Вес")}}:</div>
                                <div class="text">{{ $product->getData('weight') }}</div>
                            </div>
                            @if($product->size_id)
                                <div class="item_row size">
                                    <div class="text">{{__("Размер")}}:</div>
                                    <div class="text">{{ $product->size->name }}</div>
                                </div>
                            @endif
                            <div class="item_row">
                                <div class="text">{{__("Стоимость")}}:</div>
                                <div class="num">{{ $product->price }}
                                    <div class="currency">₸</div>
                                </div>
                            </div>
                            <div class="item_row">
                                <a class="btn" href="{{ route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug]) }}">
                                    {{__("На страницу товара")}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
