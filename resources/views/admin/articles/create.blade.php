@extends('layouts.admin')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Créer un article</h1>
    <form method="POST" action="{{ route('admin.articles.store') }}" class="space-y-4">
        @csrf
        <input name="title" class="w-full rounded border p-2" placeholder="Titre" />
        <textarea name="excerpt" class="w-full rounded border p-2" placeholder="Excerpt"></textarea>
        <textarea name="content" class="w-full rounded border p-2" placeholder="Contenu"></textarea>
        <select name="category_id" class="w-full rounded border p-2">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="rounded bg-emerald-700 px-4 py-2 text-white">Enregistrer</button>
    </form>
@endsection
