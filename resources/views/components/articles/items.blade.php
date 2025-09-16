<div class="block1">
    @foreach($articles as $article)
        <a class="item" href="{{ route('article.show', $article->slug) }}">
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

