<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMain" aria-labelledby="offcanvasMainLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMainLabel">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="img-fluid" src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}">
            </a>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav navbar-nav-style-03">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">{{ __('nav.articles') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('sectors.index') }}">{{ __('nav.sectors') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">{{ __('nav.companies') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">{{ __('nav.projects') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">{{ __('nav.search') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">{{ __('nav.about') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('nav.contact') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('locale.switch', 'fr') }}">{{ __('lang.fr') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('locale.switch', 'en') }}">{{ __('lang.en') }}</a></li>
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('nav.login') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('nav.register') }}</a></li>
            @endguest
        </ul>
    </div>
</div>
