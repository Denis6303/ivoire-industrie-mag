@extends('layouts.admin')

@section('content')
    @php
        $parts = preg_split('/\s+/', trim(auth()->user()->name ?? ''), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';
    @endphp

    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Paramètres</h1>
            <p class="text-muted mb-0">Gérez votre profil administrateur.</p>
        </div>
    </div>

    <div class="row g-3 justify-content-center">
        <div class="col-12 col-xl-6">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Mon profil</h2>
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
                        @csrf
                        <input type="hidden" name="section" value="profile">

                        <div class="col-12 col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $firstName) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $lastName) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-ivm" type="submit">Mettre à jour mon profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
