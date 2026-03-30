@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $projet->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.projets.edit', $projet) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.projets.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Slug</dt>
                <dd class="col-md-9">{{ $projet->slug }}</dd>

                <dt class="col-md-3 text-muted">Secteur</dt>
                <dd class="col-md-9">{{ optional($projet->sector)->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Société</dt>
                <dd class="col-md-9">{{ optional($projet->company)->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Statut</dt>
                <dd class="col-md-9">{{ $projet->status }}</dd>

                <dt class="col-md-3 text-muted">Investissement</dt>
                <dd class="col-md-9">{{ $projet->investment ?? '-' }}</dd>
            </dl>
            @if($projet->description)
                <p class="mt-3 mb-0">{{ $projet->description }}</p>
            @endif
        </div>
    </div>
@endsection

