<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $generalSettings->site_name }} - {{ $currentCity->name['ru'] ?? $currentCity->name }}",
    "image": "{{ Storage::url($generalSettings->logo) }}",
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
    "url": "{{ config('app.url') }}",
    "telephone": "{{ $generalSettings->phone }}",
    "priceRange": "₸",
    "openingHours": "Mo-Fr 09:00-18:00",
    "areaServed": "{{ $currentCity->name['ru'] ?? $currentCity->name }}"
}
</script>
