@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Editer le tag</h1>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name', $tag->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug', $tag->slug) }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection

