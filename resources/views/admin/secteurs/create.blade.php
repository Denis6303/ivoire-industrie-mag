@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer un secteur</h1>
        <a href="{{ route('admin.secteurs.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.secteurs.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Couleur</label>
                    <input class="form-control" name="color" value="{{ old('color') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Icône</label>
                    <input class="form-control" name="icon" value="{{ old('icon') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ordre</label>
                    <input class="form-control" type="number" name="order" value="{{ old('order', 0) }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

