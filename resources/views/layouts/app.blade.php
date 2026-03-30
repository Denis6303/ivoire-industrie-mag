<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ivoireindustriemag') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="topbar sticky-top">
        <div class="container py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <a href="{{ route('home') }}" class="brand-title h4 mb-0 text-decoration-none">ivoireindustrie<span>mag</span></a>
            <nav class="d-flex align-items-center gap-3">
                <a class="text-decoration-none text-dark" href="{{ route('articles.index') }}">Articles</a>
                <a class="text-decoration-none text-dark" href="{{ route('sectors.index') }}">Secteurs</a>
                <a class="text-decoration-none text-dark" href="{{ route('companies.index') }}">Entreprises</a>
                <a class="text-decoration-none text-dark" href="{{ route('projects.index') }}">Projets</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary">Déconnexion</button>
                    </form>
                @else
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('login') }}">Connexion</a>
                @endauth
            </nav>
        </div>
    </header>
    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
