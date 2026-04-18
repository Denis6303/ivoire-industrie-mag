@extends('layouts.front')

@section('title', 'Équipe')

@section('content')
    @include('front.partials.page-header', ['title' => 'Équipe éditoriale'])

    <section class="space-ptb">
        <div class="container">
            <p class="lead text-center mb-5 col-lg-8 mx-auto">
                Journalistes et contributeurs qui couvrent l’industrie ivoirienne avec exigence et pédagogie.
            </p>
            <div class="row">
                @foreach ([
                    ['name' => 'Direction éditoriale', 'role' => 'Pilotage et ligne éditoriale', 'img' => '01.jpg'],
                    ['name' => 'Grands comptes', 'role' => 'Reportages entreprises', 'img' => '02.jpg'],
                    ['name' => 'Secteurs', 'role' => 'Analyse par filière', 'img' => '03.jpg'],
                    ['name' => 'Production', 'role' => 'Multimédia & formats', 'img' => '04.jpg'],
                ] as $member)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="team">
                            <div class="team-img">
                                <img class="img-fluid" src="{{ asset('images/team/'.$member['img']) }}" alt="{{ $member['name'] }}">
                                <ul class="list-unstyled">
                                    <li><a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li>
                                        <a href="{{ config('ivoireindustriemag.social.twitter.url') ?? '#' }}" aria-label="X" target="_blank" rel="noopener noreferrer">
                                            <svg style="width:16px;height:16px;display:inline-block;fill:currentColor;vertical-align:-0.125em;" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.5l-5.1-6.6L5.8 22H2.7l7.3-8.4L1.2 2h6.6l4.6 6L18.9 2Zm-1.1 18h1.7L6.9 3.9H5.1L17.8 20Z"/>
                                            </svg>
                                        </a>
                                    </li>
                                    <li><a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                                </ul>
                            </div>
                            <div class="team-info">
                                <span class="team-name d-block">{{ $member['name'] }}</span>
                                <p>{{ $member['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
