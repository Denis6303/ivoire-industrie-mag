@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Uploader un média</h1>
        <a href="{{ route('admin.medias.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.medias.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Fichier</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type">
                        @foreach(['image','video','pdf','document'] as $t)
                            <option value="{{ $t }}" @selected(old('type') === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Alt (optionnel)</label>
                    <input type="text" name="alt" class="form-control" value="{{ old('alt') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Caption (optionnel)</label>
                    <input type="text" name="caption" class="form-control" value="{{ old('caption') }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Uploader</button>
                </div>
            </form>
        </div>
    </div>
@endsection

