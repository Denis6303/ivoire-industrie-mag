<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="@yield('meta_description', config('app.name') . ' — Actualités et analyses sur l\'industrie en Côte d\'Ivoire.')">
<title>
@hasSection('title')
    @yield('title') — {{ config('app.name') }}
@else
    {{ config('app.name') }}
@endif
</title>
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
