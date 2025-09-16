<div class="searchPlatform_result">
    @if($products)
        @foreach($products as $product)
            <a class="link" href="{{ route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug]) }}">
                {{ $product->title }}
            </a>
        @endforeach
        <a class="all" href="{{ route('pages.get', 'search?query='. $search) }}">{{__("Показать все результаты")}}</a>
    @else
        <div>{{__("Нет данны")}}х</div>
    @endif
</div>
