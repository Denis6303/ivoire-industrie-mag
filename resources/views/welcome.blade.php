@extends('layouts.front')

@section('title', 'Bienvenue')

@section('content')
    <section class="space-ptb">
        <div class="container text-center">
            <p class="lead">Redirection vers l’accueil du site.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Accueil</a>
        </div>
    </section>
@endsection
