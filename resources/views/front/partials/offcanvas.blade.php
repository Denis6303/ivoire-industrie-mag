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
    </style>
@endpush

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMain" aria-labelledby="offcanvasMainLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMainLabel">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="img-fluid" src="{{ asset('images/logo_2im_couleur.svg') }}" alt="{{ config('app.name') }}">
            </a>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="{{ __('app.close') }}"></button>
    </div>
    <div class="offcanvas-body">
        @php
            $primary = $navPrimaryCategories ?? collect();
            $industry = $navIndustryCategories ?? collect();
            $hidden = $navHiddenCategories ?? collect();
            $innovationChildren = $navInnovationChildren ?? collect();
        @endphp

        <ul class="navbar-nav navbar-nav-style-03 ivm-offcanvas-nav">
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
                    'zones-industrielles' => 'Zones industrielles',
                    'investissement' => 'Investissement',
                    'usines' => 'Usine',
                    'international' => 'International',
                    'agenda' => 'Agenda',
                ] as $slug => $label)
                    @if ($cat = $primary->firstWhere('slug', $slug))
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            @endif

            @if ($innovation = $hidden->firstWhere('slug', 'innovation'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#offcanvasInnovation" role="button" aria-expanded="false" aria-controls="offcanvasInnovation">
                        <span>Innovation</span>
                        <i class="fas fa-chevron-down fa-xs"></i>
                    </a>
                    <div class="collapse" id="offcanvasInnovation">
                        <ul class="list-unstyled ps-3 mb-2">
                            <li><a class="nav-link py-1" href="{{ route('categories.show', ['slug' => $innovation->slug]) }}">Innovation</a></li>
                            @foreach ($innovationChildren as $child)
                                <li><a class="nav-link py-1" href="{{ route('categories.show', ['slug' => $child->slug]) }}">{{ $child->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif

            @foreach ([
                'hommes-et-femmes-industriels-ivoiriens' => 'Hommes et femmes industriels',
                'dossier' => 'Dossier',
                'districts' => 'Districts',
                'made-in-ivory-coast' => 'Made in Ivory Coast',
                '2im-tv' => '2IM TV',
                'magazine' => 'Magazine',
            ] as $slug => $label)
                @if ($cat = $hidden->firstWhere('slug', $slug))
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.show', ['slug' => $cat->slug]) }}">{{ $label }}</a></li>
                @endif
            @endforeach
            <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index') }}">Emploi</a></li>
        </ul>
    </div>
</div>
