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
        @php
            $primary = $navPrimaryCategories ?? collect();
            $industry = $navIndustryCategories ?? collect();
        @endphp

        <ul class="navbar-nav navbar-nav-style-03">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>

            @if ($primary->isNotEmpty())
                @if ($cat = $primary->firstWhere('slug', 'industrie-story'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">Industrie Story</a></li>
                @endif

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#offcanvasIndustry" role="button" aria-expanded="false" aria-controls="offcanvasIndustry">
                        <span>Industrie</span>
                        <i class="fas fa-chevron-down fa-xs"></i>
                    </a>
                    <div class="collapse" id="offcanvasIndustry">
                        <ul class="list-unstyled ps-3 mb-2">
                            @foreach ($industry as $cat)
                                <li><a class="nav-link py-1" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                @foreach ([
                    'investissement' => 'Investissement',
                    'zones-industrielles' => 'Zones industrielles',
                    'usines' => 'Usines',
                    'innovation' => 'Innovation',
                    'international' => 'International',
                    'districts' => 'Districts',
                    'agenda' => 'Agenda',
                    'made-in-ivory-coast' => 'Made In Ivory Coast',
                    '2im-tv' => '2IM TV',
                    'hommes-et-femmes-industriels-ivoiriens' => 'Hommes et Femmes',
                ] as $slug => $label)
                    @if ($cat = $primary->firstWhere('slug', $slug))
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            @endif

            <hr class="my-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Tous les articles</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('sectors.index') }}">Secteurs</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">Entreprises</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Projets</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Recherche</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">À propos</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('team') }}">Équipe</a></li>

            <hr class="my-3">
            <li class="nav-item"><a class="nav-link" href="{{ switch_locale_url('fr') }}">{{ __('lang.fr') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ switch_locale_url('en') }}">{{ __('lang.en') }}</a></li>
        </ul>
    </div>
</div>
