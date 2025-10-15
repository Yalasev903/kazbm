<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Главная страница без города -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->format('Y-m-d\TH:i:sP') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Главные страницы ВСЕХ городов -->
    @foreach($cities as $city)
    <url>
        <loc>{{ route('home.city', ['city' => $city->slug]) }}</loc>
        <lastmod>{{ $city->updated_at->format('Y-m-d\TH:i:sP') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    <!-- Статические страницы для КАЖДОГО города -->
    @foreach($cities as $city)
        @foreach($pages as $page)
            @if($page->slug !== '/' && !in_array($page->slug, ['profile/index', 'profile/history', 'cart', 'checkout']))
            <url>
                <loc>{{ url($city->slug . '/' . $page->slug) }}</loc>
                <lastmod>{{ $page->updated_at->format('Y-m-d\TH:i:sP') }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
            @endif
        @endforeach
    @endforeach

    <!-- Категории для КАЖДОГО города -->
    @foreach($cities as $city)
        @foreach($categories as $category)
        <url>
            <loc>{{ route('category.city.show', ['city' => $city->slug, 'slug' => $category->slug]) }}</loc>
            <lastmod>{{ $category->updated_at->format('Y-m-d\TH:i:sP') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
        @endforeach
    @endforeach

    <!-- Статьи для КАЖДОГО города -->
    @foreach($cities as $city)
        @foreach($articles as $article)
        <url>
            <loc>{{ route('article.city.show', ['city' => $city->slug, 'slug' => $article->slug]) }}</loc>
            <lastmod>{{ $article->updated_at->format('Y-m-d\TH:i:sP') }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
        @endforeach
    @endforeach

    <!-- Товары для КАЖДОГО города -->
    @foreach($cities as $city)
        @foreach($products as $product)
            @if($product->category)
            <url>
                <loc>{{ route('product.city.show', [
                    'city' => $city->slug,
                    'category' => $product->category->slug,
                    'slug' => $product->slug
                ]) }}</loc>
                <lastmod>{{ $product->updated_at->format('Y-m-d\TH:i:sP') }}</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
            </url>
            @endif
        @endforeach
    @endforeach
</urlset>
