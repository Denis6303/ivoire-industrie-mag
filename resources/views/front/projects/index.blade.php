@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Projets industriels</h1>
    <div class="space-y-3">
        @foreach($projects as $project)
            <article class="rounded bg-white p-4 shadow">
                <h2 class="font-semibold">{{ $project->name }}</h2>
                <p class="text-sm text-gray-600">{{ $project->location }} - {{ $project->status }}</p>
            </article>
        @endforeach
    </div>
    <div class="mt-6">{{ $projects->links() }}</div>
@endsection
