@isset($product)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->title['ru'] ?? $product->title }}",
    "description": "{{ strip_tags($product->description['ru'] ?? $product->description ?? '') }}",
    "sku": "{{ $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "KAZBM"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "KZT",
        "price": "{{ $product->price }}",
        "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "{{ $generalSettings->site_name }}"
        }
    }
    @php
        // Безопасная обработка parameters
        $parameters = [];
        if (isset($product->parameters)) {
            if (is_string($product->parameters)) {
                $parameters = json_decode($product->parameters, true) ?? [];
            } elseif (is_array($product->parameters)) {
                $parameters = $product->parameters;
            }
        }
    @endphp
    @if(!empty($parameters))
    ,"additionalProperty": [
        @foreach($parameters as $key => $value)
        {
            "@type": "PropertyValue",
            "name": "{{ $key }}",
            "value": "{{ $value }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
    @endif
}
</script>
@endisset
