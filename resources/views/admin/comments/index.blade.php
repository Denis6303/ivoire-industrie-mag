@extends('layouts.admin')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Commentaires</h1>
    <div class="space-y-3">
        @foreach($comments as $comment)
            <article class="rounded bg-white p-4 shadow">
                <p>{{ $comment->content }}</p>
                <div class="mt-2 text-xs text-gray-500">{{ $comment->is_approved ? 'Approuve' : 'En attente' }}</div>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $comments->links() }}</div>
@endsection
