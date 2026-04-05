@extends('layouts.front')

@section('title', 'À propos')

@section('content')
    @include('front.partials.page-header', ['title' => 'À propos'])

    <section class="space-ptb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="mb-4">La référence de l’industrie en Côte d’Ivoire</h2>
                    <p>
                        {{ config('app.name') }} est un média d’information spécialisé dans la vulgarisation de l’industrie ivoirienne.
                        Notre mission est de rendre accessibles les enjeux économiques, technologiques et humains qui façonnent le tissu industriel du pays.
                    </p>
                    <p>
                        Entreprises, secteurs, projets d’investissement et politiques publiques : nous proposons des articles de fond, des reportages et des analyses
                        pour les décideurs, les professionnels et tout citoyen curieux du monde industriel.
                    </p>
                    <h3 class="mt-5 h5">Nos engagements</h3>
                    <ul>
                        <li>Indépendance éditoriale et rigueur factuelle</li>
                        <li>Priorité à la clarté et à la pédagogie</li>
                        <li>Visibilité des acteurs et des territoires</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
