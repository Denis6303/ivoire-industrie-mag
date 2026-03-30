@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $secteur->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.secteurs.edit', $secteur) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.secteurs.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Slug</dt>
                <dd class="col-md-9">{{ $secteur->slug }}</dd>

                <dt class="col-md-3 text-muted">Actif</dt>
                <dd class="col-md-9">
                    @if($secteur->is_active)
                        <span class="badge text-bg-success">Oui</span>
                    @else
                        <span class="badge text-bg-secondary">Non</span>
                    @endif
                </dd>

                <dt class="col-md-3 text-muted">Ordre</dt>
                <dd class="col-md-9">{{ $secteur->order }}</dd>

                <dt class="col-md-3 text-muted">Couleur</dt>
                <dd class="col-md-9">
                    @if($secteur->color)
                        <span class="badge" style="background: {{ $secteur->color }}; color: #fff"> {{ $secteur->color }}</span>
                    @else
                        -
                    @endif
                </dd>

                <dt class="col-md-3 text-muted">Icon</dt>
                <dd class="col-md-9">{{ $secteur->icon ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Description</dt>
                <dd class="col-md-9">{{ $secteur->description ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection

