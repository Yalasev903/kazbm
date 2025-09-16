@if ($paginator->hasPages())
    <div class="pogination">

        @if (!$paginator->onFirstPage())
            <a class="pogination_left pagination_item" data-href="{{ $paginator->previousPageUrl() }}">
                <img src="{{ asset('images/icons/pog_left.svg') }}" alt="pog left" loading="lazy">
            </a>
        @endif

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            {{--            @if (is_string($element))--}}
            {{--                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>--}}
            {{--            @endif--}}

            {{-- Array Of Links --}}
            @if (is_array($element))
                <div class="pogination_inner">
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <div class="item active">{{ $page }}</div>
                        @else
                            <a class="item pagination_item" data-href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pogination_right pagination_item" data-href="{{ $paginator->nextPageUrl() }}">
                <img src="{{ asset('images/icons/pog_right.svg') }}" alt="pog right" loading="lazy">
            </a>
        @endif

    </div>
@endif
