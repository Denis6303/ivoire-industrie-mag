<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row min-vh-100">
        <aside class="col-lg-2 col-md-3 bg-dark text-white p-4">
            <a href="{{ route('admin.dashboard') }}" class="d-block mb-4 h5 text-white text-decoration-none">Admin IvoireMag</a>
            <nav class="nav flex-column gap-2">
                <a class="nav-link text-white p-0" href="{{ route('admin.articles.index') }}">Articles</a>
                <a class="nav-link text-white p-0" href="{{ route('admin.commentaires.index') }}">Commentaires</a>
                <a class="nav-link text-white p-0" href="{{ route('admin.settings') }}">Paramètres</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Déconnexion</button>
                </form>
            </nav>
        </aside>
        <main class="col-lg-10 col-md-9 p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
