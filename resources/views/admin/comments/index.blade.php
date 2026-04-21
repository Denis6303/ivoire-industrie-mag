@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
            <h1 class="h4 mb-1">Commentaires <span class="badge text-bg-secondary ms-1">{{ $total }}</span></h1>
            <p class="text-muted mb-0 small">Gérez les commentaires des lecteurs.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse ($comments as $comment)
        <div class="card card-mag mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                        <span class="fw-semibold">{{ $comment->user?->name ?? $comment->guest_name ?? 'Anonyme' }}</span>
                        @if ($comment->guest_email)
                            <small class="text-muted ms-2">{{ $comment->guest_email }}</small>
                        @endif
                        <small class="text-muted d-block">{{ $comment->created_at?->translatedFormat('j M Y à H:i') }}</small>
                    </div>
                </div>

                <div class="small text-muted mb-2">
                    Article :
                    @if ($comment->article)
                        <a href="{{ route('articles.show', ['slug' => $comment->article->slug]) }}" target="_blank" class="text-decoration-none">
                            {{ $comment->article->title }}
                        </a>
                    @else
                        <span class="fst-italic">article supprimé</span>
                    @endif
                </div>

                <p class="mb-3">{{ $comment->content }}</p>

                <form method="POST" action="{{ route('admin.commentaires.destroy', $comment) }}"
                      onsubmit="return confirm('Supprimer ce commentaire ?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="fa-solid fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-comment-dots fa-2x mb-2 d-block"></i>
            Aucun commentaire pour le moment.
        </div>
    @endforelse

    <div class="mt-3">
        {{ $comments->links() }}
    </div>
@endsection
