@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Éditer vidéo 2IM TV</h1>
        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.videos.update', $video) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-8">
                    <label class="form-label">Titre de la vidéo</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $video->title) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date de publication (optionnel)</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($video->published_at)->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Lien YouTube</label>
                    <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $video->youtube_url) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $video->description) }}</textarea>
                </div>

                <div class="col-12 d-flex flex-wrap gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $video->is_published))>
                        <label class="form-check-label" for="is_published">Publier</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $video->is_featured))>
                        <label class="form-check-label" for="is_featured">Mettre à la une</label>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection

