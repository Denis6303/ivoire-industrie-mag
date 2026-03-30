@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $entreprise->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.entreprises.edit', $entreprise) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.entreprises.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Slug</dt>
                <dd class="col-md-9">{{ $entreprise->slug }}</dd>

                <dt class="col-md-3 text-muted">Secteur</dt>
                <dd class="col-md-9">{{ optional($entreprise->sector)->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">À la une</dt>
                <dd class="col-md-9">{{ $entreprise->is_featured ? 'Oui' : 'Non' }}</dd>

                <dt class="col-md-3 text-muted">Actif</dt>
                <dd class="col-md-9">{{ $entreprise->is_active ? 'Oui' : 'Non' }}</dd>

                <dt class="col-md-3 text-muted">Description</dt>
                <dd class="col-md-9">{{ $entreprise->description ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Site web</dt>
                <dd class="col-md-9">{{ $entreprise->website ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection

