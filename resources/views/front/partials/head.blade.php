<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="@yield('meta_description', 'Ivoire Industrie Magazine - Actualités et analyses sur l\'industrie en Côte d\'Ivoire.')">
<title>
@hasSection('title')
    @yield('title') - Ivoire Industrie Magazine
@else
    Ivoire Industrie Magazine
@endif
</title>
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

    /* Homepage sections spacing + dot alignment + pink CTA */
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
    /* CTA uniformes: on utilise le orange du thème (btn-primary) */
</style>
