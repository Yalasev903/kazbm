<div class="searchPlatform_result">
    @php
        $currentCity = app('currentCity'); // экземпляр City
    @endphp
    @if($products)
        @foreach($products as $product)
            <a class="link" href="{{ route('product.city.show', ['city' => $currentCity->slug, 'category' => $product->category->slug, 'slug' => $product->slug]) }}">
                {{ $product->title }}
            </a>
        @endforeach
        <a class="all" href="{{ route('pages.city.get', ['city' => $currentCity->slug, 'slug' => 'search', 'query' => $search]) }}">
            {{ __("Показать все результаты") }}
        </a>
    @else
        <div>{{__("Нет данных")}}х</div>
    @endif
</div>
