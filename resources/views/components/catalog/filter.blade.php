<div class="block2_filter_bloor" onclick="closeFilter()"></div>
<div class="block2_filter">
    <div class="close" onclick="closeFilter()">{{__("Отмена")}}</div>
    <div class="box">
        <div class="title">{{__("Категории")}}</div>
        @if($categories)
            <div class="items">
                @foreach($categories as $slug => $name)
                    <a class="item {{ isset($category) && $category->slug == $slug ? 'active' : '' }}"
                       href="{{ route('category.show', $slug) }}">{{ __($name) }}</a>
                @endforeach
            </div>
        @endif
        <div class="title filter_title">{{__("Фильтр")}}</div>
        <div class="filter">
            <div class="items cost_items">
                <div class="item cost">
                    <div class="item_title">{{__("Цена")}}</div>
                    <div class="item_row cost_row">
                        <input class="minVal" type="number" value="0" min="0">
                        <input class="maxVal" type="number" value="{{ $maxPrice }}" min="0">
                    </div>
                    <div class="item_row item_range">
                        <div class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" id="slider-range" data-min="0" data-max="{{ $maxPrice }}">
                            <div class="ui-slider-handle ui-corner-all ui-state-default" id="custom-handle" tabindex="0" style="left: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @if($sizes->isNotEmpty())
                <div class="items checkbox_items">
                    <div class="item">
                        <div class="item_title">{{__("Размеры")}}</div>
                        <div class="item_row checkbox_row">
                            @foreach($sizes as $size)
                                <div class="checkbox">
                                    <input id="id{{ $size->id }}" type="checkbox" value="{{ $size->id }}">
                                    <label for="id{{ $size->id }}">{{ $size->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if($colors)
                <div class="items elmt_items">
                    <div class="item">
                        <div class="item_title">{{__("Цветовая гамма")}}</div>
                        <div class="item_row elmts">
                            @foreach($colors as $color)
                                <div class="elmt" data-id="{{ $color->id }}">
                                    <picture>
                                        @if($photo = $color->getWebpFormat('image'))
                                            <source srcset="{{$photo}}" type="image/webp">
                                            <source srcset="{{$photo}}" type="image/pjp2">
                                        @endif
                                        <img src="{{ $color->getRealFormat('image') }}" alt="{{ $color->name }}" loading="lazy">
                                    </picture>
                                    <div class="text" title="{{ __($color->name) }}">{{ __($color->name) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="btns">
{{--        <div class="btn applyFilterBtn" onclick="closeFilter()">Применить</div>--}}
        <div class="btn clearFilterBtn" onclick="clearFilter()">{{__("Очистить фильтр")}}</div>
    </div>
</div>
