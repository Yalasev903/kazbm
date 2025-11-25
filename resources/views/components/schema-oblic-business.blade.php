@if(!request()->is('admin*') && !request()->is('filament*'))
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $generalSettings->site_name }} - Облицовочный кирпич {{ $currentCity->name['ru'] ?? $currentCity->name }}",
    "description": "Производство и продажа облицовочного кирпича в {{ $currentCity->name['ru'] ?? $currentCity->name }}. Высокое качество, доступные цены, доставка.",
    "url": "{{ city_route('oblic.city') }}",
    "telephone": "{{ $generalSettings->phone }}",
    "email": "{{ $generalSettings->email }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $generalSettings->address_ru }}",
        "addressLocality": "{{ $currentCity->name['ru'] ?? $currentCity->name }}",
        "addressRegion": "{{ $currentCity->region }}",
        "addressCountry": "KZ"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "{{ $currentCity->latitude ?? '52.3001' }}",
        "longitude": "{{ $currentCity->longitude ?? '76.9492' }}"
    },
    "openingHours": "Mo-Fr 09:00-18:00",
    "priceRange": "₸",
    "areaServed": "{{ $currentCity->name['ru'] ?? $currentCity->name }}"
    @if(isset($products) && count($products) > 0)
    ,"makesOffer": [
        @foreach($products as $product)
        {
            "@type": "Offer",
            "itemOffered": {
                "@type": "Product",
                "name": "{{ $product->title['ru'] ?? $product->title }}",
                "description": "{{ strip_tags($product->description['ru'] ?? $product->description ?? '') }}",
                "category": "Облицовочный кирпич"
            },
            "price": "{{ $product->price }}",
            "priceCurrency": "KZT",
            "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
    @endif
}
</script>
@endif
