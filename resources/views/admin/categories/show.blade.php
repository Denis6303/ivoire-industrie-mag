@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $category->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.edit', $category) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Slug</dt>
                <dd class="col-md-9">{{ $category->slug }}</dd>

                <dt class="col-md-3 text-muted">Parent</dt>
                <dd class="col-md-9">{{ optional($category->parent)->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Ordre</dt>
                <dd class="col-md-9">{{ $category->order }}</dd>

                <dt class="col-md-3 text-muted">Couleur</dt>
                <dd class="col-md-9">
                    @if($category->color)
                        <span class="badge" style="background: {{ $category->color }}; color: #fff">{{ $category->color }}</span>
                    @else
                        -
                    @endif
                </dd>

                <dt class="col-md-3 text-muted">Description</dt>
                <dd class="col-md-9">{{ $category->description ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Icône</dt>
                <dd class="col-md-9">{{ $category->icon ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection

