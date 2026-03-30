@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer une entreprise</h1>
        <a href="{{ route('admin.entreprises.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.entreprises.store') }}" enctype="multipart/form-data" class="row g-3">
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
                    <label class="form-label">Secteur</label>
                    <select class="form-select" name="industry_sector_id">
                        <option value="">Aucun</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected(old('industry_sector_id') == $sector->id)>{{ $sector->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo/logo (optionnel)</label>
                    <input class="form-control" type="file" name="logo_file" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Site web</label>
                    <input class="form-control" name="website" value="{{ old('website') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" name="email" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ville</label>
                    <input class="form-control" name="city" value="{{ old('city') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Région</label>
                    <input class="form-control" name="region" value="{{ old('region') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Adresse</label>
                    <input class="form-control" name="address" value="{{ old('address') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', false))>
                        <label class="form-check-label" for="is_featured">À la une</label>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

