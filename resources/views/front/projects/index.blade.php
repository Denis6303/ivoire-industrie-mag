@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Projets industriels</h1>
    <div class="row g-3">
        @foreach($projects as $project)
            <div class="col-md-6 col-lg-4">
                <article class="card card-mag h-100">
                    <div class="card-body">
                        <h2 class="h6 mb-2">{{ $project->name }}</h2>
                        <p class="small text-secondary mb-0">{{ $project->location }} - {{ $project->status }}</p>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $projects->links() }}</div>
@endsection
