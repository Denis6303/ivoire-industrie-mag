<header class="header header-sticky">
    <div class="topbar d-none d-md-block">
        <div class="container">
            <div class="topbar-inner">
                <div class="row">
                    <div class="col-12">
                        <div class="d-lg-flex align-items-center text-center">
                            <div class="topbar-left mb-2 mb-lg-0">
                                <div class="topbar-date d-inline-flex">
                                    <span class="date"><i class="fa-solid fa-calendar-days"></i> {{ now()->translatedFormat('l j F Y') }}</span>
                                </div>
                                <div class="me-auto d-inline-flex">
                                    <ul class="list-unstyled top-menu">
                                        <li><a href="{{ route('about') }}">À propos</a></li>
                                        <li><a href="{{ route('contact') }}">Contact</a></li>
                                        <li><a href="{{ route('team') }}">Équipe</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="topbar-right ms-auto justify-content-center">
                                @auth
                                    <span class="user">
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-body">Déconnexion</button>
                                        </form>
                                    </span>
                                @else
                                    <span class="user">
                                        <a href="{{ route('login') }}"><img src="{{ asset('images/user.png') }}" alt=""> Connexion</a>
                                    </span>
                                @endauth
                                <div class="social d-inline-flex">
                                    <ul class="list-unstyled">
                                        <li><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li><a href="#" aria-label="X"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                        <li><a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container position-relative">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="img-fluid" src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Articles</a>
                    </li>
                    @if (isset($navCategories) && $navCategories->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Catégories<i class="fas fa-chevron-down fa-xs"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navCategories">
                                @foreach ($navCategories as $cat)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sectors.*') ? 'active' : '' }}" href="{{ route('sectors.index') }}">Secteurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">Entreprises</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">Projets</a>
                    </li>
                </ul>
            </div>
            <div class="add-listing">
                <div class="header-search">
                    <div class="search">
                        <a href="#search" aria-label="Recherche"><i class="fa-solid fa-magnifying-glass"></i></a>
                    </div>
                </div>
                <div class="side-menu d-none d-lg-inline-block">
                    <a data-bs-toggle="offcanvas" href="#offcanvasMain" role="button" aria-controls="offcanvasMain" aria-label="Menu">
                        <i class="fa-solid fa-align-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Menu">
            <i class="fa-solid fa-align-right"></i>
        </button>
    </nav>
</header>
