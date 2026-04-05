@extends('layouts.front')

@section('title', 'Projets industriels')

@section('content')
    @include('front.partials.page-header', ['title' => 'Projets industriels'])

    <section class="space-ptb">
      <div class="container">
            <div class="row">
                @forelse ($projects as $project)
                    <div class="col-md-6 mb-4" id="{{ $project->slug }}">
                        <div class="blog-post post-style-11 border rounded p-4 h-100">
                            <h3 class="h5 blog-title mb-3">{{ $project->name }}</h3>
                            @if ($project->company)
                                <p class="small text-muted mb-2"><a href="{{ route('companies.show', $project->company->slug) }}">{{ $project->company->name }}</a></p>
                            @endif
                            @if ($project->sector)
                                <span class="badge bg-secondary mb-2">{{ $project->sector->name }}</span>
                            @endif
                            @if ($project->location)
                                <p class="small mb-2"><i class="fa-solid fa-location-dot"></i> {{ $project->location }}</p>
                            @endif
                            @if ($project->description)
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 200) }}</p>
                            @endif
                            @if ($project->investment)
                                <p class="small"><strong>Investissement :</strong> {{ formatNumber($project->investment) }} FCFA</p>
                            @endif
                            @if ($project->status)
                                <span class="badge bg-light text-dark border">{{ $project->status }}</span>
                            @endif
                      </div>
                    </div>
                @empty
                    <p class="text-muted">Aucun projet référencé.</p>
                @endforelse
            </div>
            {{ $projects->links() }}
      </div>
    </section>
@endsection
