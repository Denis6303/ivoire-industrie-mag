@php
    $appUrl  = rtrim(config('app.url'), '/');
    $locale  = app()->getLocale();
    $fbUrl   = site_setting('social_facebook') ?: config('ivoireindustriemag.social.facebook.url', '#');
    $liUrl   = site_setting('social_linkedin') ?: config('ivoireindustriemag.social.linkedin.url', '#');
    $ytUrl   = site_setting('social_youtube') ?: config('ivoireindustriemag.social.youtube.url', '#');
    $xUrl    = site_setting('social_x') ?: '#';
    $email   = site_setting('contact_email') ?: '';
    $phone   = site_setting('contact_phone') ?: '';
@endphp

{{-- Organisation : présente sur toutes les pages --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsMediaOrganization",
    "@id": "{{ $appUrl }}/#organization",
    "name": "Ivoire Industrie Magazine",
    "alternateName": "2IM",
    "url": "{{ $appUrl }}/{{ $locale }}/",
    "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('images/logo_2im_couleur.svg') }}",
        "width": 250,
        "height": 80
    },
    "description": "Le premier média dédié à l'industrie en Côte d'Ivoire : agro-industrie, énergie, mines, BTP, innovation, investissement et zones industrielles.",
    "foundingDate": "2024",
    "areaServed": [
        { "@type": "Country", "name": "Côte d'Ivoire" },
        { "@type": "Continent", "name": "Africa" }
    ],
    "knowsAbout": [
        "Industrie", "Agro-industrie", "Mines", "Énergie", "BTP",
        "Innovation", "Investissement", "Zones industrielles", "Économie ivoirienne"
    ],
    "inLanguage": ["fr", "en"],
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer support",
        "email": "{{ $email }}",
        "telephone": "{{ $phone }}",
        "availableLanguage": ["French", "English"]
    },
    "sameAs": [
        "{{ $fbUrl }}",
        "{{ $liUrl }}",
        "{{ $ytUrl }}",
        "{{ $xUrl }}"
    ]
}
</script>

{{-- WebSite avec SearchAction (sitelinks searchbox Google) --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "@id": "{{ $appUrl }}/#website",
    "name": "Ivoire Industrie Magazine",
    "url": "{{ $appUrl }}/{{ $locale }}/",
    "inLanguage": "{{ $locale }}",
    "publisher": { "@id": "{{ $appUrl }}/#organization" },
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ url("/$locale/recherche") }}?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

@yield('jsonld')
