@extends('layouts.front')

@section('title', 'Contact')

@section('content')
    @include('front.partials.page-header', ['title' => 'Contact'])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="h4 mb-4">Écrire à la rédaction</h2>
                    <p class="text-muted">
                        Pour les suggestions de sujets, les partenariats médias ou les demandes professionnelles,
                        utilisez les coordonnées ci-dessous. Les messages sont traités sous deux à cinq jours ouvrés.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fa-solid fa-envelope me-2 text-primary"></i><a href="mailto:contact@ivoireindustriemag.com">contact@ivoireindustriemag.com</a></li>
                        <li class="mb-3"><i class="fa-solid fa-location-dot me-2 text-primary"></i>Abidjan, Côte d’Ivoire</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="border rounded p-4 bg-light">
                        <h3 class="h5 mb-3">Formulaire (bientôt disponible)</h3>
                        <p class="text-muted small mb-0">
                            Un formulaire de contact sécurisé sera branché ici (validation, anti-spam, notification équipe).
                            En attendant, privilégiez l’e-mail.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
