@php
    $locale      = app()->getLocale();
    $altLocale   = $locale === 'fr' ? 'en' : 'fr';
    $appUrl      = rtrim(config('app.url'), '/');
    $siteName    = 'Ivoire Industrie Magazine';
    $defaultDesc = $locale === 'en'
        ? 'Ivoire Industrie Magazine (2IM) — The leading media outlet dedicated to industry in Côte d\'Ivoire: agro-industry, energy, mining, BTP, innovation and investment.'
        : 'Ivoire Industrie Magazine (2IM) — Le premier média dédié à l\'industrie en Côte d\'Ivoire : agro-industrie, énergie, mines, BTP, innovation et investissement.';
    $defaultKeywords = 'industrie Côte d\'Ivoire, actualité industrielle, magazine industrie ivoirien, 2IM, investissement Côte d\'Ivoire, agro-industrie, mines, pétrole gaz, BTP, énergie, innovation, zones industrielles, économie ivoirienne, industrie africaine, Abidjan';
    $metaTitle   = trim(strip_tags($__env->yieldContent('title')));
    $fullTitle   = $metaTitle ? "$metaTitle — $siteName" : $siteName;
    $metaDesc    = trim(strip_tags($__env->yieldContent('meta_description'))) ?: $defaultDesc;
    $metaImage   = trim($__env->yieldContent('meta_image')) ?: asset('images/og-default.jpg');
    $canonicalUrl = trim($__env->yieldContent('canonical')) ?: url()->current();
    // URL de la même page dans l'autre langue
    $altUrl = str_replace("/$locale/", "/$altLocale/", $canonicalUrl);
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

{{-- Titre --}}
<title>{{ $fullTitle }}</title>

{{-- Description & mots-clés --}}
<meta name="description" content="{{ Str::limit($metaDesc, 160) }}">
<meta name="keywords" content="{{ $__env->yieldContent('meta_keywords') ?: $defaultKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="robots" content="{{ $__env->yieldContent('meta_robots') ?: 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' }}">
<meta name="language" content="{{ $locale === 'en' ? 'English' : 'French' }}">
<meta name="revisit-after" content="3 days">
<meta name="geo.region" content="CI">
<meta name="geo.placename" content="Abidjan, Côte d'Ivoire">

{{-- Canonical & hreflang (multilangue) --}}
<link rel="canonical" href="{{ $canonicalUrl }}">
<link rel="alternate" hreflang="fr" href="{{ str_replace("/$altLocale/", '/fr/', $canonicalUrl) }}">
<link rel="alternate" hreflang="en" href="{{ str_replace("/$locale/", '/en/', $canonicalUrl) }}">
<link rel="alternate" hreflang="x-default" href="{{ str_replace("/$locale/", '/fr/', $canonicalUrl) }}">

{{-- Open Graph (Facebook, LinkedIn, WhatsApp…) --}}
<meta property="og:type" content="{{ $__env->yieldContent('og_type') ?: 'website' }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ Str::limit($metaDesc, 200) }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $fullTitle }}">
<meta property="og:locale" content="{{ $locale === 'en' ? 'en_US' : 'fr_FR' }}">
<meta property="og:locale:alternate" content="{{ $locale === 'en' ? 'fr_FR' : 'en_US' }}">

{{-- Twitter / X Cards --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ Str::limit($metaDesc, 200) }}">
<meta name="twitter:image" content="{{ $metaImage }}">
<meta name="twitter:image:alt" content="{{ $fullTitle }}">
<meta name="twitter:site" content="@IvoireIndustrie">

{{-- Favicon & icônes --}}
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

{{-- Sitemap (hint pour crawlers) --}}
<link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

{{-- Polices & CSS --}}
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<style>
    /* Pas de flèches Owl dans l'en-tête (filet de sécurité si le DOM est mal positionné) */
    .header .owl-nav {
        display: none !important;
    }

    /* Badges: padding/align pour éviter le texte collé au bord */
    .badge {
        padding: 0.42em 0.72em;
        line-height: 1.1;
        font-weight: 700;
        letter-spacing: 0.01em;
        border-radius: 999px;
    }
    .badge.badge-medium {
        padding: 0.5em 0.85em;
    }

    /* Police globale */
    html, body,
    h1, h2, h3, h4, h5, h6,
    .blog-title, .widget-title,
    .news-post .news-post-details .news-title,
    .breaking-news .news-btn,
    .navbar, .topbar, .footer {
        font-family: "Red Hat Display", sans-serif !important;
    }

    /* Neutralise le text-transform:capitalize global du thème sur tous les titres et éléments de navigation */
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6,
    .blog-title,
    .blog-title a,
    .widget-title,
    .section-title h1,
    .section-title h2,
    .section-title h3,
    .footer-title,
    .navbar .nav-link,
    .offcanvas .nav-link,
    .inner-header .breadcrumb-item,
    .inner-header .breadcrumb-item span,
    .inner-header .breadcrumb-item a {
        text-transform: none !important;
    }

    /* Images: pas d'animation au survol (zoom/translate du thème) */
    .blog-post .blog-image img,
    .blog-post .post-image img,
    .blog-post-info .blog-post-image img,
    .news-post .news-image img,
    .companies img {
        transition: none !important;
        transform: none !important;
    }
    .blog-post:hover img,
    .blog-post.post-style-07:hover img,
    .blog-post.post-style-11:hover .blog-image img,
    .blog-post.post-style-04:hover img {
        transition: none !important;
        transform: none !important;
    }

    /* Le voile gradient du theme ne doit pas bloquer le clic image */
    .blog-post .blog-image:before {
        pointer-events: none !important;
    }
    .blog-post .blog-image > a {
        position: relative;
        z-index: 2;
    }

    /* Titre des cards d'articles: 1 ligne + ellipsis (hors "a la une") */
    .ivm-card-title-ellipsis {
        width: 100%;
        max-width: 100%;
    }
    .ivm-card-title-ellipsis a {
        display: block;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* Mega menu Industrie (dropdown horizontal) */
    .ivm-mega-menu {
        width: min(980px, calc(100vw - 2rem));
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 10px 30px rgba(2, 6, 23, 0.10);
    }
    @media (min-width: 992px) {
        .navbar .ivm-mega .dropdown-menu {
            left: 50%;
            transform: translateX(-50%);
        }
    }
    .ivm-mega-menu .dropdown-item {
        white-space: normal;
        border-radius: 8px;
        padding: 8px 10px;
    }
    .ivm-mega-menu .dropdown-item:hover {
        background: rgba(255, 120, 0, 0.10);
    }

    /* Homepage sections spacing + dot alignment */
    .ivm-section-sep {
        margin-top: 44px;
        margin-bottom: 26px;
        border-top: 1px solid rgba(2, 6, 23, 0.08);
    }
    .ivm-section-title {
        line-height: 1.2;
    }
    .ivm-section-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        flex: 0 0 12px;
        transform: translateY(1px);
    }

    /* Réduit la largeur du conteneur de l'heure dans le header */
    .header .add-listing .clock {
        min-width: auto !important;
        padding: 5px 8px !important;
        margin-right: 8px !important;
        font-size: 14px !important;
    }
</style>
