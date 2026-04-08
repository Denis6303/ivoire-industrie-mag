<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="@yield('meta_description', config('app.name') . ' — Actualités et analyses sur l\'industrie en Côte d\'Ivoire.')">
<title>
@hasSection('title')
    @yield('title') — {{ config('app.name') }}
@else
    {{ config('app.name') }}
@endif
</title>
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<style>
    /* Pas de flèches Owl dans l’en-tête (filet de sécurité si le DOM est mal positionné) */
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

    /* Police unique (harmonisation globale) */
    html, body,
    h1, h2, h3, h4, h5, h6,
    .blog-title, .widget-title,
    .news-post .news-post-details .news-title,
    .breaking-news .news-btn,
    .navbar, .topbar, .footer {
        font-family: "Red Hat Display", sans-serif !important;
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
</style>
