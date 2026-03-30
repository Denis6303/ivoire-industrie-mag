@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card card-mag">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Inscription client</h1>
                <form method="POST" action="{{ route('register.post') }}" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-ivm">Créer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
