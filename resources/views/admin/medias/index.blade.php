@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Médias</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.medias.create') }}">Uploader</a>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Fichier</th>
                    <th>Affichage</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($medias as $media)
                <tr>
                    <td><span class="badge text-bg-secondary">{{ $media->type }}</span></td>
                    <td>
                        <div class="fw-semibold">{{ $media->original_name }}</div>
                        <div class="small text-muted">{{ $media->filename }}</div>
                    </td>
                    <td>
                        @if(str_starts_with($media->mime_type, 'image') && $media->url)
                            <img src="{{ $media->url }}" alt="{{ $media->alt ?? '' }}" style="max-height:48px" class="rounded">
                        @else
                            <a class="small" href="{{ $media->url }}" target="_blank" rel="noopener">Lien</a>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.medias.show', $media) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.medias.edit', $media) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.medias.destroy', $media) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ?')" type="submit">Suppr.</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $medias->links() }}</div>
@endsection

