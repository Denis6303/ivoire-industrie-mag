@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Média</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.medias.edit', $media) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.medias.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <div class="row g-3 align-items-start">
                <div class="col-md-6">
                    @if(str_starts_with($media->mime_type, 'image') && $media->url)
                        <img src="{{ $media->url }}" alt="{{ $media->alt ?? '' }}" class="img-fluid">
                    @else
                        <a href="{{ $media->url }}" target="_blank" rel="noopener">Ouvrir</a>
                    @endif
                </div>
                <div class="col-md-6">
                    <dl class="mb-0">
                        <dt>Original</dt>
                        <dd class="mb-2">{{ $media->original_name }}</dd>

                        <dt>Type</dt>
                        <dd class="mb-2">{{ $media->type }}</dd>

                        <dt>Alt</dt>
                        <dd class="mb-2">{{ $media->alt ?? '-' }}</dd>

                        <dt>Caption</dt>
                        <dd class="mb-2">{{ $media->caption ?? '-' }}</dd>

                        <dt>MIME</dt>
                        <dd class="mb-0">{{ $media->mime_type ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

