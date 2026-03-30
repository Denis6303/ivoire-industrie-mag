@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Editer la catégorie</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug', $category->slug) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Couleur (hex)</label>
                    <input class="form-control" name="color" value="{{ old('color', $category->color) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Icône (optionnel)</label>
                    <input class="form-control" name="icon" value="{{ old('icon', $category->icon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Parent (optionnel)</label>
                    <select class="form-select" name="parent_id">
                        <option value="">Aucun</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ordre</label>
                    <input class="form-control" type="number" name="order" value="{{ old('order', $category->order) }}">
                </div>
                <div class="col-md-9">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection

