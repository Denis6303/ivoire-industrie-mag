@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer un utilisateur</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select" required>
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $roleValue => $roleLabel)
                            <option value="{{ $roleValue }}" @selected(old('role') === $roleValue)>{{ $roleLabel }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>
@endsection
