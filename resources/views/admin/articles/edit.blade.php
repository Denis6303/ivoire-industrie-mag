@extends('layouts.admin')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Éditer l'article</h1>
    <form method="POST" action="{{ route('admin.articles.update', $article) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <input name="title" value="{{ $article->title }}" class="w-full rounded border p-2" />
        <textarea name="excerpt" class="w-full rounded border p-2">{{ $article->excerpt }}</textarea>
        <textarea name="content" class="w-full rounded border p-2">{{ $article->content }}</textarea>
        <select name="category_id" class="w-full rounded border p-2">
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}" @selected($article->category_id === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="rounded bg-emerald-700 px-4 py-2 text-white">Mettre à jour</button>
    </form>
@endsection
