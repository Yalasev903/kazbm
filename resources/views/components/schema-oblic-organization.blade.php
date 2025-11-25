<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Облицовочный кирпич KAZBM",
    "url": "{{ config('app.url') }}",
    "logo": "{{ Storage::url($generalSettings->logo) }}",
    "description": "Производство и продажа облицовочного кирпича в Казахстане. Высокое качество, доступные цены, доставка.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $generalSettings->address_ru }}",
        "addressLocality": "{{ $currentCity->name['ru'] ?? $currentCity->name }}",
        "addressRegion": "{{ $currentCity->region }}",
        "addressCountry": "KZ"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "{{ $generalSettings->phone }}",
        "email": "{{ $generalSettings->email }}",
        "contactType": "customer service"
    },
    "sameAs": [
        @foreach($generalSettings->socials as $social)
            "{{ $social['link'] }}"@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
