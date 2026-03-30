@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer un projet</h1>
        <a href="{{ route('admin.projets.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.projets.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Investissement (milliards FCFA)</label>
                    <input class="form-control" name="investment" value="{{ old('investment') }}" inputmode="decimal">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Emplois créés</label>
                    <input class="form-control" name="jobs_created" value="{{ old('jobs_created') }}" type="number">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Localisation</label>
                    <input class="form-control" name="location" value="{{ old('location') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date début</label>
                    <input class="form-control" type="date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date fin (optionnel)</label>
                    <input class="form-control" type="date" name="end_date" value="{{ old('end_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        @foreach(['planned','in_progress','completed','suspended'] as $st)
                            <option value="{{ $st }}" @selected(old('status') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
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
                    <label class="form-label">Société</label>
                    <select class="form-select" name="company_id">
                        <option value="">Aucun</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

