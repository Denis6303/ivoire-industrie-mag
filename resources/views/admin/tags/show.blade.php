@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $tag->name }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.tags.edit', $tag) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.tags.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Slug</dt>
                <dd class="col-md-9">{{ $tag->slug }}</dd>
            </dl>
        </div>
    </div>
@endsection

