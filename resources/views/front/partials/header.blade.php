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
                                        <li>
                                            <a href="{{ site_setting('social_facebook') ?: '#' }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a>
                                        </li>
                                        <li>
                                            <a href="{{ site_setting('social_x') ?: '#' }}" aria-label="X" target="_blank" rel="noopener noreferrer">
                                                <svg style="width:16px;height:16px;display:inline-block;fill:currentColor;vertical-align:-0.125em;" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.5l-5.1-6.6L5.8 22H2.7l7.3-8.4L1.2 2h6.6l4.6 6L18.9 2Zm-1.1 18h1.7L6.9 3.9H5.1L17.8 20Z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ site_setting('social_linkedin') ?: '#' }}" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-linkedin-in"></i></a>
                                        </li>
                                        <li>
                                            <a href="{{ site_setting('social_youtube') ?: '#' }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i></a>
                                        </li>
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
                        @php
                            $industryHub = $navIndustryParent ?? null;
                        @endphp
                        <li class="nav-item ivm-mega">
                            <div class="dropdown">
                                <div class="d-flex align-items-center flex-wrap">
                                    @if ($industryHub)
                                        <a class="nav-link py-2 {{ request()->routeIs('categories.show') && request()->route('slug') === $industryHub->slug ? 'active' : '' }}" href="{{ category_show_url($industryHub) }}">{{ category_i18n($industryHub) }}</a>
                                        <a class="nav-link dropdown-toggle py-2 ps-1 pe-2 border-0" href="#" id="navIndustry" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="{{ __('nav.industry_open_submenu') }}"><i class="fas fa-chevron-down fa-xs" aria-hidden="true"></i></a>
                                    @else
                                        <a class="nav-link dropdown-toggle py-2" href="#" id="navIndustry" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __('nav.industry') }} <i class="fas fa-chevron-down fa-xs"></i></a>
                                    @endif
                                </div>
                                <div class="dropdown-menu p-3 ivm-mega-menu" aria-labelledby="navIndustry">
                                    <div class="row g-2">
                                        @foreach ($industry->chunk((int) ceil($industry->count() / 4)) as $chunk)
                                            <div class="col-6 col-lg-3">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($chunk as $cat)
                                                        <li><a class="dropdown-item" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ category_i18n($cat) }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}" href="{{ route('jobs.index') }}">{{ __('nav.jobs') }}</a>
                    </li>
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
