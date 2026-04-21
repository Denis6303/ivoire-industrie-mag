@push('styles')
    <style>
        #offcanvasMain .offcanvas-body {
            max-height: calc(100vh - 72px);
            overflow-y: auto;
        }
        #offcanvasMain .ivm-offcanvas-nav .nav-link {
            padding: 0.55rem 0;
            font-weight: 600;
            border-bottom: 1px solid #eef1f6;
        }
        #offcanvasMain .ivm-offcanvas-nav .collapse .nav-link {
            font-weight: 500;
            color: #4b5563;
            padding: 0.35rem 0;
            border-bottom: 0;
        }
        #offcanvasMain .ivm-offcanvas-nav .collapse ul {
            border-left: 2px solid #e9edf5;
            margin-left: 0.1rem;
            margin-top: 0.35rem;
            margin-bottom: 0.5rem !important;
        }
        #offcanvasMain .ivm-offcanvas-nav .collapse ul li {
            padding-left: 0.6rem;
        }
        #offcanvasMain .offcanvas-title .navbar-brand {
            display: block;
            line-height: 1;
        }
        #offcanvasMain .offcanvas-title .ivm-offcanvas-logo {
            display: block;
            height: 42px;
            width: auto;
            max-width: 220px;
        }
        #offcanvasMain .offcanvas-title {
            width: calc(100% - 44px);
        }
    </style>
@endpush

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMain" aria-labelledby="offcanvasMainLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMainLabel">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="ivm-offcanvas-logo" src="{{ asset('images/logo_2im_couleur.svg') }}" alt="{{ config('app.name') }}" style="height: 42px; width: auto; max-width: 220px; display:block;" onerror="this.onerror=null;this.src='{{ asset('images/2im_couleur.svg') }}';">
            </a>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
    </div>
    <div class="offcanvas-body">
        @php
            $primary = $navPrimaryCategories ?? collect();
            $industry = $navIndustryCategories ?? collect();
            $industryParent = $navIndustryParent ?? null;
            $hidden = $navHiddenCategories ?? collect();
            $innovationChildren = $navInnovationChildren ?? collect();
        @endphp

        <ul class="navbar-nav navbar-nav-style-03 ivm-offcanvas-nav">
            @if ($primary->isNotEmpty())
                @if ($cat = $primary->firstWhere('slug', 'industrie-story'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ __('nav.industry_story') }}</a></li>
                @endif

                <li class="nav-item">
                    @if ($industryParent)
                        <div class="d-flex align-items-stretch border-bottom">
                            <a class="nav-link flex-grow-1 py-2" href="{{ category_show_url($industryParent) }}">{{ category_i18n($industryParent) }}</a>
                            <a class="nav-link py-2 px-2 text-secondary" data-bs-toggle="collapse" href="#offcanvasIndustry" role="button" aria-expanded="false" aria-controls="offcanvasIndustry" aria-label="{{ __('nav.industry_open_submenu') }}"><i class="fas fa-chevron-down fa-xs"></i></a>
                        </div>
                    @else
                        <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#offcanvasIndustry" role="button" aria-expanded="false" aria-controls="offcanvasIndustry">
                            <span>{{ __('nav.industry') }}</span>
                            <i class="fas fa-chevron-down fa-xs"></i>
                        </a>
                    @endif
                    <div class="collapse" id="offcanvasIndustry">
                        <ul class="list-unstyled ps-3 mb-2">
                            @foreach ($industry as $cat)
                                <li><a class="nav-link py-1" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ category_i18n($cat) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                @foreach ([
                    'zones-industrielles' => __('nav.industrial_zones'),
                    'investissement' => __('nav.investment'),
                    'usines' => __('nav.factory'),
                    'international' => __('nav.international'),
                    'agenda' => __('nav.agenda'),
                ] as $slug => $label)
                    @if ($cat = $primary->firstWhere('slug', $slug))
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            @endif

            @if ($innovation = $hidden->firstWhere('slug', 'innovation'))
                <li class="nav-item">
                    @if ($innovationChildren->isEmpty())
                        <a class="nav-link" href="{{ route('categories.show', ['slug' => $innovation->slug]) }}">{{ __('nav.innovation') }}</a>
                    @else
                        <div class="d-flex align-items-stretch border-bottom">
                            <a class="nav-link flex-grow-1 py-2" href="{{ category_show_url($innovation) }}">{{ __('nav.innovation') }}</a>
                            <a class="nav-link py-2 px-2 text-secondary" data-bs-toggle="collapse" href="#offcanvasInnovation" role="button" aria-expanded="false" aria-controls="offcanvasInnovation" aria-label="{{ __('nav.industry_open_submenu') }}"><i class="fas fa-chevron-down fa-xs"></i></a>
                        </div>
                        <div class="collapse" id="offcanvasInnovation">
                            <ul class="list-unstyled ps-3 mb-2">
                                @foreach ($innovationChildren as $child)
                                    <li><a class="nav-link py-1" href="{{ route('categories.show', ['slug' => $child->slug]) }}">{{ $child->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endif

            @foreach ([
                'hommes-et-femmes-industriels-ivoiriens' => __('nav.industrial_leaders'),
                'dossier' => __('nav.dossier'),
                'districts' => __('nav.districts'),
                'made-in-ivory-coast' => __('nav.made_in_ivory_coast'),
                'etudes' => __('nav.studies'),
                '2im-tv' => __('nav.tv'),
                'magazine' => __('nav.magazine'),
            ] as $slug => $label)
                @if ($cat = $hidden->firstWhere('slug', $slug))
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a></li>
                @endif
            @endforeach
            <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index') }}">{{ __('nav.jobs') }}</a></li>
        </ul>
    </div>
</div>
