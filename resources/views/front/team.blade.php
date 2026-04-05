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
                                    <li><a href="#" aria-label="X"><i class="fab fa-twitter"></i></a></li>
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
