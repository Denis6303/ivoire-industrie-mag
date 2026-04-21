@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer une publicité</h1>
        <a href="{{ route('admin.publicites.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.publicites.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <input class="form-control" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Position</label>
                    <select class="form-select" name="position" required>
                        @foreach([
                            'header' => 'En-tête',
                            'sidebar' => 'Colonne latérale (haut)',
                            'sidebar_secondary' => 'Colonne latérale (entre Entreprises qui bougent & Focus)',
                            'in_article' => 'Dans l’article',
                            'footer' => 'Pied de page',
                        ] as $pos => $posLabel)
                            <option value="{{ $pos }}" @selected(old('position') === $pos)>{{ $posLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image (fichier)</label>
                    <input class="form-control" type="file" name="image_file" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image URL (optionnel)</label>
                    <input class="form-control" name="image_url" value="{{ old('image_url') }}" placeholder="https://...">
                </div>
                <div class="col-12">
                    <label class="form-label">Lien URL</label>
                    <input class="form-control" name="link_url" value="{{ old('link_url') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Début</label>
                    <input class="form-control" type="date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fin (optionnel)</label>
                    <input class="form-control" type="date" name="end_date" value="{{ old('end_date') }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

