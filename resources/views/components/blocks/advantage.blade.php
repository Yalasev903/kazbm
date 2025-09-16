@php $advantageSettings = app(\App\Filament\Settings\AdvantageSettings::class) @endphp
@if($items = $advantageSettings->items)
    <div class="block4">
        <div class="left">
            <div class="head">
                @foreach($items as $i => $item)
                    <div class="item {{ $i == 0 ? 'active' : '' }}">
                        @php $image = \App\Helpers\Common::getImage($item['image']) @endphp
                        <img src="{{ $image }}" alt="advantage image{{$i}}" loading="lazy">
                    </div>
                @endforeach
            </div>
            <picture class="bgi">
                <source srcset="{{ asset('images/hpb4.webp') }}" type="image/webp">
                <source srcset="{{ asset('images/hpb4.webp') }}" type="image/pjp2">
                <img src="{{ asset('images/hpb4.png') }}" alt="" loading="lazy">
            </picture>
        </div>
        <div class="right">
            <div class="head">
                <div class="span">{{__("Гиперпрессованный")}}</div>
                <div class="b">{{__("Кирпич")}}</div>
                <div class="span">{{__("это")}}:</div>
            </div>
            <div class="body">
                @foreach($items as $i => $item)
                    <div class="item {{ $i == 0 ? 'active' : '' }}">
                        <div class="title">{{ __($item['title']) }}</div>
                        <div class="desc">{{ $item['desc'] }}</div>
                    </div>
                @endforeach
                <a href="/our-products" class="btn">{{__("Подробнее")}}</a>
            </div>
        </div>
    </div>
    <div class="block4_2">
        <div class="titles">Гиперпрессованный кирпич - это</div>
        <div class="items_box">
            <div class="items">
                <picture class="items_bg">
                    <source srcset="{{ asset('images/block4_2.webp') }}" type="image/webp">
                    <source srcset="{{ asset('images/block4_2.webp') }}" type="image/pjp2">
                    <img src="{{ asset('images/block4_2.png') }}" alt="image block4_2" loading="lazy">
                </picture>
                @foreach($items as $i => $item)
                    <div class="item">
                        @php $small_image = \App\Helpers\Common::getImage($item['small_image']) @endphp
                        <img class="item_icon" src="{{$small_image}}" alt="item icon{{$i}}">
                        <div class="item_text">{{ $item['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
