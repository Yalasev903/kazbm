@php
    $advantageContent = \App\Services\CityContentService::getOblicAdvantageContent();
    $items = $advantageContent['items'] ?? [];
@endphp
@if($items)
    <div class="block4">
        <div class="left">
            <div class="head">
                @foreach($items as $i => $item)
                    <div class="item {{ $i == 0 ? 'active' : '' }}">
                        @php $image = $item['image'] @endphp
                        <x-webp-image
                            src="{{ $image }}"
                            alt="advantage image{{$i}}"
                            :lazy="true"
                        />
                    </div>
                @endforeach
            </div>
            <x-webp-image
                src="{{ asset('images/hpb4.png') }}"
                alt=""
                class="bgi"
                :lazy="true"
            />
        </div>
        <div class="right">
            <div class="head">
                <div class="span">{{__("Облицовочный")}}</div>
                <div class="b">{{__("Кирпич")}}</div>
                <div class="span">{{__("это")}}:</div>
            </div>
            <div class="body">
                @foreach($items as $i => $item)
                    <div class="item {{ $i == 0 ? 'active' : '' }}">
                        <div class="title">{{ app()->getLocale() == 'kk' ? ($item['title_kk'] ?? $item['title_ru']) : $item['title_ru'] }}</div>
                        <div class="desc">{{ app()->getLocale() == 'kk' ? ($item['desc_kk'] ?? $item['desc_ru']) : $item['desc_ru'] }}</div>
                    </div>
                @endforeach
                <a href="{{ route('pages.city.get', [
                    'city' => app()->get('currentCity')->slug ?? session('current_city_slug'),
                    'slug' => 'oblicovochnyy-kirpich/our-products'
                ]) }}" class="btn">
                    {{ __("Подробнее") }}
                </a>
            </div>
        </div>
    </div>
    <div class="block4_2">
        <div class="titles">Облицовочный кирпич - это</div>
        <div class="items_box">
            <div class="items">
                <picture class="items_bg">
                    <source srcset="{{ asset('images/block4_2.webp') }}" type="image/webp">
                    <source srcset="{{ asset('images/block4_2.webp') }}" type="image/pjp2">
                    <img src="{{ asset('images/block4_2.png') }}" alt="image block4_2" loading="lazy">
                </picture>

                @foreach($items as $i => $item)
                    <div class="item">
                        @php $small_image = $item['small_image'] @endphp
                        <x-webp-image
                            src="{{$small_image}}"
                            alt="item icon{{$i}}"
                            class="item_icon"
                            :lazy="true"
                        />
                        <div class="item_text">{{ app()->getLocale() == 'kk' ? ($item['title_kk'] ?? $item['title_ru']) : $item['title_ru'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
