@extends('layouts.app')

@section('content')
    <article class="card card-mag mb-4">
        <div class="card-body p-4 p-lg-5">
            <h1 class="h2">{{ $article->title }}</h1>
            <p class="text-secondary small">{{ optional($article->published_at)->format('d/m/Y') }} - {{ $article->view_count }} vues</p>
            <div class="mt-4">{!! $article->content !!}</div>
        </div>
    </article>

    <section class="mt-5">
        <h2 class="h5 mb-3">Commentaires</h2>

        <form method="POST" action="{{ route('comments.store', $article) }}" class="card card-mag p-3 mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Votre commentaire</label>
                <textarea name="content" class="form-control" rows="4" required placeholder="Votre avis..."></textarea>
            </div>

            @auth
                {{-- Les commentaires des comptes connectés seront en attente sauf rôle admin/editor --}}
            @else
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom (optionnel)</label>
                        <input type="text" name="guest_name" class="form-control" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email (optionnel)</label>
                        <input type="email" name="guest_email" class="form-control" maxlength="255">
                    </div>
                </div>
            @endauth

            <button class="btn btn-ivm" type="submit">Envoyer</button>
        </form>

        @if($article->comments->count())
            @foreach($article->comments as $comment)
                <div class="card card-mag mb-3">
                    <div class="card-body p-3">
                        <div class="small text-muted mb-2">
                            {{ $comment->guest_name ?? optional($comment->user)->name ?? 'Anonyme' }}
                        </div>
                        <div class="mb-2">{{ $comment->content }}</div>

                        @if($comment->replies->count())
                            <div class="mt-3 ms-3">
                                @foreach($comment->replies as $reply)
                                    <div class="border-start ps-3 mb-2">
                                        <div class="small text-muted mb-1">
                                            {{ $reply->guest_name ?? optional($reply->user)->name ?? 'Anonyme' }}
                                        </div>
                                        <div class="small">{{ $reply->content }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-muted small mb-0">Aucun commentaire approuvé pour le moment.</p>
        @endif
    </section>

    <section class="mt-4">
        <h2 class="h5 mb-3">Articles similaires</h2>
        <div class="row g-3">
            @foreach($related as $item)
                <div class="col-md-4">
                    <a href="{{ route('articles.show', $item->slug) }}" class="card card-body card-mag text-decoration-none text-dark h-100">{{ $item->title }}</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection
