@extends('layouts.admin')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="rounded bg-emerald-700 px-4 py-2 text-white">Nouveau</a>
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="p-3 text-left">Titre</th><th class="p-3">Statut</th><th></th></tr></thead>
            <tbody>
            @foreach($articles as $article)
                <tr class="border-t">
                    <td class="p-3">{{ $article->title }}</td>
                    <td class="p-3 text-center">{{ $article->status }}</td>
                    <td class="p-3 text-right"><a href="{{ route('admin.articles.edit', $article) }}">Editer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
