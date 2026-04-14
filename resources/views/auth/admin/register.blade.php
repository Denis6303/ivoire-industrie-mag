<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte Admin - Ivoire Industrie Magazine</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
<div class="container min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100" style="margin-top: -24px;">
        <div class="col-md-7 col-lg-6">
            <div class="text-center mb-3">
                <img src="{{ asset('images/logo_2im_couleur.svg') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-width: 220px;">
            </div>
            <div class="card border-0 shadow-lg" style="box-shadow: 0 22px 50px rgba(17, 24, 39, 0.22) !important;">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3 text-center">Créer un compte utilisateur</h1>
                    <form method="POST" action="{{ route('admin.register.post') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Rôle</label>
                            <select name="role" class="form-select" required>
                                <option value="">Choisir un rôle</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-ivm" type="submit">Créer le compte</button>
                            <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary">Se connecter</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
