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
                                        <li><a href="{{ route('about') }}">{{ __('nav.about') }}</a></li>
                                        <li><a href="{{ route('contact') }}">{{ __('nav.contact') }}</a></li>
                                        <li><a href="{{ route('team') }}">{{ __('nav.team') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="topbar-right ms-auto justify-content-center">
                                @auth
                                    <span class="user">
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-body">{{ __('nav.logout') }}</button>
                                        </form>
                                    </span>
                                @else
                                    <span class="user">
                                        <a href="{{ route('login') }}"><img src="{{ asset('images/user.png') }}" alt=""> {{ __('nav.login') }}</a>
                                    </span>
                                @endauth
                                <div class="dropdown right-menu d-inline-flex news-language">
                                    <a class="dropdown-toggle" href="#" id="langMenu" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                        <img class="img-fluid country-flag" src="{{ asset('images/flags/'.app()->getLocale().'.svg') }}" width="20" height="14" alt="">
                                        {{ app()->getLocale() === 'en' ? __('lang.en') : __('lang.fr') }}
                                        <i class="fas fa-chevron-down fa-xs"></i>
                                    </a>
                                    <div class="dropdown-menu mt-0" aria-labelledby="langMenu">
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('locale.switch', 'fr') }}">
                                            <img class="img-fluid country-flag me-2" src="{{ asset('images/flags/fr.svg') }}" width="20" height="14" alt=""> {{ __('lang.fr') }}
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('locale.switch', 'en') }}">
                                            <img class="img-fluid country-flag me-2" src="{{ asset('images/flags/en.svg') }}" width="20" height="14" alt=""> {{ __('lang.en') }}
                                        </a>
                                    </div>
                                </div>
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
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('nav.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">{{ __('nav.articles') }}</a>
                    </li>
                    @if (isset($navCategories) && $navCategories->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('nav.categories') }}<i class="fas fa-chevron-down fa-xs"></i>
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
                        <a class="nav-link {{ request()->routeIs('sectors.*') ? 'active' : '' }}" href="{{ route('sectors.index') }}">{{ __('nav.sectors') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">{{ __('nav.companies') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">{{ __('nav.projects') }}</a>
                    </li>
                </ul>
            </div>
            <div class="add-listing">
                <div class="header-search">
                    <div class="search">
                        <a href="#search" aria-label="{{ __('header.search_button') }}"><i class="fa-solid fa-magnifying-glass"></i></a>
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
