<div class="block1">
    @foreach($articles as $article)
        @php
            $url = city_route('article.city.show', ['slug' => $article->slug]);
        @endphp
        <a class="item" href="{{ $url }}">
            <!-- Отладочная информация -->
            <!-- <div style="display:none;">{{ $url }}</div> -->
            <picture>
                @if($photo = $article->getWebpFormat('small_image'))
                    <source srcset="{{$photo}}" type="image/webp">
                    <source srcset="{{$photo}}" type="image/pjp2">
                @endif
                <img src="{{ $article->getRealFormat('small_image') }}" alt="{{ $article->title }}" loading="lazy">
            </picture>
            <div class="date">{{ $article->getPublishedAt() }}</div>
            <div class="title">{{ $article->title }}</div>
            <div class="desc">{{ $article->getShortDesc() }}</div>
            <div class="btn">{{__("Подробнее")}}</div>
        </a>
    @endforeach
</div>
<div class="pogination_block">{{ $articles->links() }}</div>
