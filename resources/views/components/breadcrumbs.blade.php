<div class="route">
    <a class="route__older" href="/">{{__("Главная")}}</a>
    @if(isset($parents) && $parents)
        @foreach($parents as $_title => $link)
            <div class="route__slesh">-</div>
            <a class="route__older" href="{{$link}}">{{__($_title)}}</a>
        @endforeach
    @endif
    <div class="route__slesh">-</div>
    <div class="route__current">{{ $title ?? __($page->title) }}</div>
</div>
