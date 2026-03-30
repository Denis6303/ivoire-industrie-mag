@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Commentaires en attente</h1>
        <span class="badge text-bg-warning">{{ $comments->total() }}</span>
    </div>

    <div class="space-y-3">
        @foreach($comments as $comment)
            <div class="card card-mag mb-3">
                <div class="card-body p-3">
                    <div class="small text-muted mb-1">
                        Article : {{ optional($comment->article)->title ?? '-' }}
                    </div>
                    <div class="small text-muted mb-2">
                        Auteur : {{ $comment->guest_name ?? optional($comment->user)->name ?? 'Anonyme' }}
                    </div>

                    <div class="mb-3">{{ $comment->content }}</div>

                    <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="text-end">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-ivm btn-sm" type="submit">Approuver</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $comments->links() }}
    </div>
@endsection
