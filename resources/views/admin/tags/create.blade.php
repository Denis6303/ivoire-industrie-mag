@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer un tag</h1>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tags.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug') }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

