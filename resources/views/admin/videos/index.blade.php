@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">2IM TV</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.videos.create') }}">Nouvelle vidéo</a>
    </div>

    <div class="table-responsive bg-white card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>YouTube</th>
                    <th>Statut</th>
                    <th>Publication</th>
                    <th>Auteur</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($videos as $video)
                <tr>
                    <td class="fw-semibold">{{ $video->title }}</td>
                    <td>
                        <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener noreferrer" class="small">
                            {{ $video->youtube_video_id }}
                        </a>
                    </td>
                    <td>
                        @if($video->is_published)
                            <span class="badge bg-success">Publié</span>
                        @else
                            <span class="badge bg-secondary">Brouillon</span>
                        @endif
                        @if($video->is_featured)
                            <span class="badge bg-warning text-dark ms-1">À la une</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ optional($video->published_at)->format('d/m/Y H:i') ?? '-' }}</td>
                    <td class="small text-muted">{{ optional($video->author)->name ?? '-' }}</td>
                    <td class="text-end">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.videos.edit', $video) }}">Éditer</a>
                        <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Supprimer cette vidéo ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Aucune vidéo 2IM TV pour le moment.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $videos->links() }}
    </div>
@endsection

