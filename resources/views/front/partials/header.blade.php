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
                            </div>
                            <div class="topbar-right ms-auto justify-content-center">
                                <div class="dropdown right-menu d-inline-flex news-language">
                                    <a class="dropdown-toggle" href="#" id="langMenu" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                        <img class="img-fluid country-flag" src="{{ asset('images/flags/'.app()->getLocale().'.svg') }}" width="20" height="14" alt="">
                                        {{ app()->getLocale() === 'en' ? __('lang.en') : __('lang.fr') }}
                                        <i class="fas fa-chevron-down fa-xs"></i>
                                    </a>
                                    <div class="dropdown-menu mt-0" aria-labelledby="langMenu">
                                        <a class="dropdown-item d-flex align-items-center" href="{{ switch_locale_url('fr') }}">
                                            <img class="img-fluid country-flag me-2" src="{{ asset('images/flags/fr.svg') }}" width="20" height="14" alt=""> {{ __('lang.fr') }}
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ switch_locale_url('en') }}">
                                            <img class="img-fluid country-flag me-2" src="{{ asset('images/flags/en.svg') }}" width="20" height="14" alt=""> {{ __('lang.en') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="social d-inline-flex">
                                    <ul class="list-unstyled">
                                        <li><a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                                        <li><a href="#" aria-label="X"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>
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
                <img src="{{ asset('images/logo_2im_couleur.svg') }}" alt="{{ config('app.name') }}" style="height: 52px; width: auto; max-width: 100%; object-fit: contain;">
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    @php
                        $primary = $navPrimaryCategories ?? collect();
                        $industry = $navIndustryCategories ?? collect();
                    @endphp

                    @if ($cat = $primary->firstWhere('slug', 'industrie-story'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'active' : '' }}" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ __('nav.industry_story') }}</a>
                        </li>
                    @endif

                    @if ($industry->isNotEmpty())
                        <li class="nav-item dropdown ivm-mega">
                            <a class="nav-link dropdown-toggle" href="#" id="navIndustry" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Industrie <i class="fas fa-chevron-down fa-xs"></i>
                            </a>
                            <div class="dropdown-menu p-3 ivm-mega-menu" aria-labelledby="navIndustry">
                                <div class="row g-2">
                                    @foreach ($industry->chunk((int) ceil($industry->count() / 4)) as $chunk)
                                        <div class="col-6 col-lg-3">
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($chunk as $cat)
                                                    <li><a class="dropdown-item" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endif

                    @foreach ([
                        'zones-industrielles' => __('nav.industrial_zones'),
                        'investissement' => __('nav.investment'),
                        'usines' => __('nav.factory'),
                        'international' => __('nav.international'),
                        'agenda' => __('nav.agenda'),
                    ] as $slug => $label)
                        @if ($cat = $primary->firstWhere('slug', $slug))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'active' : '' }}" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="add-listing">
                <div id="weathertime" class="clock" aria-live="polite"></div>
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
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMain" aria-controls="offcanvasMain" aria-expanded="false" aria-label="Menu">
            <i class="fa-solid fa-align-right"></i>
        </button>
    </nav>
</header>
