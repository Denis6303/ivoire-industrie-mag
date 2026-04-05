@extends('layouts.front')

@section('title', e($company->name))

@section('content')
    @include('front.partials.page-header', [
        'title' => $company->name,
        'breadcrumbItems' => [['label' => 'Entreprises', 'url' => route('companies.index')]],
    ])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="d-flex align-items-start mb-4">
                        @if ($company->logo)
                            <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="me-4 rounded" style="max-width:120px;">
                        @endif
                        <div>
                            @if ($company->sector)
                                <span class="badge bg-primary mb-2">{{ $company->sector->name }}</span>
                            @endif
                            @if ($company->city || $company->region)
                                <p class="text-muted mb-0"><i class="fa-solid fa-location-dot me-1"></i>{{ $company->city }}@if ($company->region), {{ $company->region }}@endif</p>
                            @endif
                            @if ($company->website)
                                <p class="mt-2 mb-0"><a href="{{ $company->website }}" target="_blank" rel="noopener">Site web</a></p>
                            @endif
                        </div>
                    </div>
                    <div class="article-body">
                        {!! $company->description !!}
                    </div>
                    @if ($company->projects->isNotEmpty())
                        <h3 class="mt-5 h5">Projets</h3>
                        <ul class="list-unstyled">
                            @foreach ($company->projects as $project)
                                <li class="mb-2">
                                    <a href="{{ route('projects.index') }}#{{ $project->slug }}">{{ $project->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="sidebar widget p-3 border rounded">
                        <h6 class="widget-title">Coordonnées</h6>
                        @if ($company->email)
                            <p class="small mb-1"><i class="fa-solid fa-envelope me-2"></i>{{ $company->email }}</p>
                        @endif
                        @if ($company->phone)
                            <p class="small mb-1"><i class="fa-solid fa-phone me-2"></i>{{ $company->phone }}</p>
                        @endif
                        @if ($company->address)
                            <p class="small">{{ $company->address }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
