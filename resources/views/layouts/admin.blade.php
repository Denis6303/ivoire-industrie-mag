<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">
    <aside class="w-60 bg-emerald-900 p-4 text-white">
        <a href="{{ route('admin.dashboard') }}" class="mb-6 block text-lg font-bold">ivoireindustriemag</a>
        <nav class="space-y-2 text-sm">
            <a class="block" href="{{ route('admin.articles.index') }}">Articles</a>
            <a class="block" href="{{ route('admin.comments.index') }}">Commentaires</a>
            <a class="block" href="{{ route('admin.settings') }}">Paramètres</a>
        </nav>
    </aside>
    <main class="flex-1 p-6">
        @if(session('success'))
            <div class="mb-4 rounded bg-emerald-100 p-3 text-emerald-900">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
