@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Editer le média</h1>
        <a href="{{ route('admin.medias.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.medias.update', $media) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type">
                        @foreach(['image','video','pdf','document'] as $t)
                            <option value="{{ $t }}" @selected(old('type', $media->type) === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Alt (optionnel)</label>
                    <input class="form-control" name="alt" value="{{ old('alt', $media->alt) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Caption (optionnel)</label>
                    <input class="form-control" name="caption" value="{{ old('caption', $media->caption) }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection

