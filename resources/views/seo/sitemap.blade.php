{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

{{-- Pages statiques (accueil, articles, catégories…) --}}
@foreach ($staticPages as $page)
<url>
    <loc>{{ $page['url'] }}</loc>
    @if (!empty($page['updated']))
    <lastmod>{{ \Carbon\Carbon::parse($page['updated'])->toAtomString() }}</lastmod>
    @else
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    @endif
    <changefreq>{{ $page['changefreq'] }}</changefreq>
    <priority>{{ $page['priority'] }}</priority>
</url>
@endforeach

{{-- Articles publiés (toutes les locales) --}}
@foreach ($articles as $article)
    @foreach ($locales as $locale)
<url>
    <loc>{{ $appUrl }}/{{ $locale }}/articles/{{ $article->slug }}</loc>
    <lastmod>{{ ($article->updated_at ?? $article->published_at)->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
    @if ($article->cover_image)
    <image:image>
        <image:loc>{{ $appUrl }}/storage/{{ $article->cover_image }}</image:loc>
        <image:title>{{ htmlspecialchars($article->title, ENT_XML1 | ENT_COMPAT, 'UTF-8') }}</image:title>
    </image:image>
    @endif
</url>
    @endforeach
@endforeach

</urlset>
