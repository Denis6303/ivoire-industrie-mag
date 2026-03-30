<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-mag">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Connexion Administration</h1>
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
                    <a href="{{ route('home') }}" class="small d-inline-block mt-3">Retour au site public</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
