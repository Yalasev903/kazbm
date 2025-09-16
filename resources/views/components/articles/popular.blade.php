@if($articles->isNotEmpty())
    <div class="block6">
        <div class="left">
            <div class="titles">{{ $title }}</div>
            <div class="arrows">
                <svg class="arrow_left" width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="1">
                        <rect x="50.25" width="50" height="50" rx="5" transform="rotate(90 50.25 0)" fill="#CE2329"></rect>
                        <path d="M32.25 10L17.25 25L32.25 40" stroke="#F0EEE9" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
                <svg class="arrow_right" width="51" height="50" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="1">
                        <rect x="50.25" width="50" height="50" rx="5" transform="rotate(90 50.25 0)" fill="#CE2329"></rect>
                        <path d="M17.25 40L32.25 25L17.25 10" stroke="#F0EEE9" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </div>
        </div>
        <div class="right">
            @foreach($articles as $article)
                <div class="item">
                    <picture>
                        @if($photo = $article->getWebpFormat('small_image'))
                            <source srcset="{{$photo}}" type="image/webp">
                            <source srcset="{{$photo}}" type="image/pjp2">
                        @endif
                        <img src="{{ $article->getRealFormat('small_image') }}" alt="{{ $article->title }}" loading="lazy">
                    </picture>
                    <div class="item_right">
                        <div class="item_title">{{ $article->title }}</div>
                        <div class="item_date">{{ $article->getPublishedAt() }}</div>
                        <div class="item_desc">{{ $article->getShortDesc(47) }}</div>
                        <a class="item_link" href="{{ route('article.show', $article->slug) }}">{{__("Подробнее")}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
