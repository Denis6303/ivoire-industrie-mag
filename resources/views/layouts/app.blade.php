<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ivoireindustriemag') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="sticky top-0 z-50 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-900">ivoireindustrie<span class="text-amber-600">mag</span></a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('articles.index') }}">Articles</a>
                <a href="{{ route('sectors.index') }}">Secteurs</a>
                <a href="{{ route('companies.index') }}">Entreprises</a>
                <a href="{{ route('projects.index') }}">Projets</a>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded bg-emerald-100 p-3 text-emerald-900">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
