<div class="route">
    <a class="route__older" href="{{ url('/') }}">{{__("Главная")}}</a>


    @if(isset($parents) && is_array($parents))
        @foreach($parents as $parent)
            @if(is_array($parent) && isset($parent['name']) && isset($parent['url']))
                <div class="route__slesh">-</div>
                <a class="route__older" href="{{ $parent['url'] }}">{{__($parent['name']) }}</a>
            @endif
        @endforeach
    @endif
    <div class="route__slesh">-</div>
    <div class="route__current">
        @if(isset($title) && is_string($title))
            {{ $title }}
        @elseif(isset($page->title) && is_string($page->title))
            {{ __($page->title) }}
        @else
            {{ __('Страница') }}
        @endif
    </div>
</div>
