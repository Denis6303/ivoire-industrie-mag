@extends('layouts.app')

@section('content')
    <h1 class="mb-2 text-2xl font-bold">{{ $company->name }}</h1>
    <p class="mb-6 text-gray-600">{{ $company->description }}</p>
    <h2 class="mb-3 font-semibold">Projets</h2>
    <ul class="space-y-2">
        @foreach($company->projects as $project)
            <li class="rounded bg-white p-3 shadow">{{ $project->name }}</li>
        @endforeach
    </ul>
@endsection
