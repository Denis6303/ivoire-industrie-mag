<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
<div class="container min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100" style="margin-top: -24px;">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-width: 220px;">
            </div>
            <div class="card border-0 shadow-lg" style="box-shadow: 0 22px 50px rgba(17, 24, 39, 0.22) !important;">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3 text-center">Connexion Administration</h1>
                    <form method="POST" action="{{ route('admin.login.post') }}" class="d-grid gap-3">
                        @csrf
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-dark">Se connecter</button>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('admin.register') }}" class="small d-inline-block mt-3 me-3">Créer un compte admin</a>
                        <a href="{{ route('home', ['locale' => config('app.locale', 'fr')]) }}" class="small d-inline-block mt-3">Retour au site public</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
