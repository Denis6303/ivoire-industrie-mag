<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Ivoire Industrie Magazine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-subtle admin-shell">
<div class="container-fluid px-0">
    <div class="d-flex min-vh-100 admin-layout">
        <aside class="bg-dark text-white p-3 p-lg-4 admin-sidebar d-none d-lg-block" style="width: 280px;">
            <a href="{{ route('admin.dashboard') }}" class="d-block mb-4 h5 text-white text-decoration-none fw-bold admin-brand">
                IvoireIndustrieMag
            </a>

            <nav class="nav flex-column gap-2 admin-nav">
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge me-2"></i>Dashboard
                </a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                    <i class="fa-regular fa-newspaper me-2"></i>Articles
                </a>

                @if(in_array(auth()->user()->role, ['super_admin', 'admin'], true))
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-layer-group me-2"></i>Catégories</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}"><i class="fa-solid fa-tags me-2"></i>Tags</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.commentaires.*') ? 'active' : '' }}" href="{{ route('admin.commentaires.index') }}"><i class="fa-regular fa-comments me-2"></i>Commentaires</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.entreprises.*') ? 'active' : '' }}" href="{{ route('admin.entreprises.index') }}"><i class="fa-solid fa-building me-2"></i>Entreprises</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.projets.*') ? 'active' : '' }}" href="{{ route('admin.projets.index') }}"><i class="fa-solid fa-diagram-project me-2"></i>Projets</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}" href="{{ route('admin.newsletter.index') }}"><i class="fa-regular fa-envelope me-2"></i>Newsletter</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="fa-regular fa-user me-2"></i>Utilisateurs</a>
                    <a class="nav-link text-white p-0 {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}"><i class="fa-solid fa-gear me-2"></i>Paramètres</a>
                @endif
            </nav>
        </aside>

        <main class="flex-grow-1 admin-main">
            <div class="admin-mobile-header d-flex d-lg-none align-items-center justify-content-between px-3 py-2">
                <a href="{{ route('admin.dashboard') }}" class="h5 mb-0 text-white text-decoration-none fw-bold admin-brand">IvoireIndustrieMag</a>
                <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="card border-0 shadow-sm mb-3 admin-topbar">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Espace administration</div>
                        <div class="text-muted small">{{ auth()->user()->name }} - {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-none d-lg-block">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm">Déconnexion</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="admin-content p-3 p-md-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<div class="offcanvas offcanvas-start admin-sidebar-offcanvas text-white" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel" style="--bs-offcanvas-bg:#142536;background:#142536;">
    <div class="offcanvas-header border-bottom border-light-subtle">
        <h5 class="offcanvas-title fw-bold mb-0 admin-brand" id="adminSidebarMobileLabel">IvoireIndustrieMag</h5>
        <button type="button" class="btn admin-offcanvas-close text-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body p-3">
        <nav class="nav flex-column gap-2 admin-nav">
            <a class="nav-link text-white p-0 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge me-2"></i>Dashboard
            </a>
            <a class="nav-link text-white p-0 {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                <i class="fa-regular fa-newspaper me-2"></i>Articles
            </a>

            @if(in_array(auth()->user()->role, ['super_admin', 'admin'], true))
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-layer-group me-2"></i>Catégories</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}"><i class="fa-solid fa-tags me-2"></i>Tags</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.commentaires.*') ? 'active' : '' }}" href="{{ route('admin.commentaires.index') }}"><i class="fa-regular fa-comments me-2"></i>Commentaires</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.entreprises.*') ? 'active' : '' }}" href="{{ route('admin.entreprises.index') }}"><i class="fa-solid fa-building me-2"></i>Entreprises</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.projets.*') ? 'active' : '' }}" href="{{ route('admin.projets.index') }}"><i class="fa-solid fa-diagram-project me-2"></i>Projets</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}" href="{{ route('admin.newsletter.index') }}"><i class="fa-regular fa-envelope me-2"></i>Newsletter</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="fa-regular fa-user me-2"></i>Utilisateurs</a>
                <a class="nav-link text-white p-0 {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}"><i class="fa-solid fa-gear me-2"></i>Paramètres</a>
            @endif
        </nav>
        <div class="mt-3 pt-3 border-top border-light-subtle">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm w-100" type="submit">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Déconnexion
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
