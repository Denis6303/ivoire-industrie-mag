@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-mag">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Connexion client</h1>
                <form method="POST" action="{{ route('login.post') }}" class="d-grid gap-3">
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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember-client">
                        <label class="form-check-label" for="remember-client">Se souvenir de moi</label>
                    </div>
                    <button class="btn btn-ivm">Se connecter</button>
                </form>
                <p class="mt-3 mb-0 small">Pas de compte ? <a href="{{ route('register') }}">Inscription</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
