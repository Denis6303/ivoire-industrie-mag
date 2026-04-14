@extends('layouts.front')

@section('title', 'À propos')

@section('content')
    @include('front.partials.page-header', ['title' => 'À propos'])

    <section class="space-ptb">
        <div class="container">
            <div class="row align-items-stretch g-4">
                <div class="col-lg-7">
                    <div class="border rounded p-4 p-md-5 h-100 bg-white shadow-sm">
                        <span class="badge rounded-pill mb-3" style="background:#243e5d;">Notre mission</span>
                        <h2 class="mb-3">La référence éditoriale de l’industrie ivoirienne</h2>
                        <p class="text-muted mb-3">
                            Ivoire Industrie Magazine informe, décrypte et valorise les acteurs qui transforment le tissu industriel en Côte d’Ivoire.
                            Nous publions des analyses, des interviews, des dossiers et des formats pratiques pour aider à comprendre les dynamiques du secteur.
                        </p>
                        <p class="text-muted mb-0">
                            Notre ligne éditoriale relie terrain, investissement, innovation et impact social pour donner une vision complète, utile et fiable.
                        </p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border rounded p-4 h-100 bg-light">
                        <h3 class="h5 mb-3">Nos engagements</h3>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>Indépendance éditoriale et rigueur des sources.</span></li>
                            <li class="mb-3 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>Clarté de l’information pour professionnels et grand public.</span></li>
                            <li class="mb-0 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>Mise en valeur des talents, entreprises et territoires.</span></li>
                        </ul>
                        <div class="small text-muted">Vous avez un sujet ou une entreprise à proposer ?</div>
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm mt-2">Contacter la rédaction</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">100%</div>
                        <p class="mb-0 text-muted">Industrie et économie réelle</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">Focus</div>
                        <p class="mb-0 text-muted">Entreprises, investissements, innovation</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">Impact</div>
                        <p class="mb-0 text-muted">Information utile pour agir et décider</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
