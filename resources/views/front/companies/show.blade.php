@extends('layouts.app')

@section('content')
    <h1 class="mb-2">{{ $company->name }}</h1>
    <p class="mb-4 text-secondary">{{ $company->description }}</p>
    <h2 class="h6 mb-3">Projets</h2>
    <ul class="list-group mb-3">
        @foreach($company->projects as $project)
            <li class="list-group-item">{{ $project->name }}</li>
        @endforeach
    </ul>
@endsection
