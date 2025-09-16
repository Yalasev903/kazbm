<div class="after"></div>
<div class="basketAt_left">
    <picture>
        @if($photo = $product->getWebpFormat('photo'))
            <source srcset="{{$photo}}" type="image/webp">
            <source srcset="{{$photo}}" type="image/pjp2">
        @endif
        <img src="{{ $product->getRealFormat('photo') }}" alt="{{ $product->title }}" loading="lazy">
    </picture>
</div>
<div class="basketAt_right">
    <div class="desc">{{__("Добавлено в корзину:")}}</div>
    <div class="title">{{ $product->title }}</div>
    <div class="cost">{{ $product->price }} &nbsp<span>₸</span></div>
</div>
