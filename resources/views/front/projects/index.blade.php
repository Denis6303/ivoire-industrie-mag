@extends('layouts.front')

@section('title', 'Projets industriels')

@section('content')
    @include('front.partials.page-header', ['title' => 'Projets industriels'])

    <section class="space-ptb">
      <div class="container">
            <div class="row">
                @forelse ($projects as $project)
                    <div class="col-md-6 mb-4" id="{{ $project->slug }}">
                        @php
                            $statusLabels = [
                                'planned' => ['label' => 'Prévu', 'class' => 'bg-light text-dark border'],
                                'in_progress' => ['label' => 'En cours', 'class' => 'bg-warning text-dark'],
                                'completed' => ['label' => 'Terminé', 'class' => 'bg-success'],
                                'suspended' => ['label' => 'Suspendu', 'class' => 'bg-danger'],
                            ];
                            $status = $project->status ?: 'planned';
                            $statusMeta = $statusLabels[$status] ?? ['label' => $status, 'class' => 'bg-light text-dark border'];
                        @endphp

                        <div class="border rounded h-100 bg-white overflow-hidden">
                            <div class="p-4 pb-3">
                                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                    <span class="badge {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
                                    @if ($project->sector)
                                        <span class="badge bg-secondary">{{ $project->sector->name }}</span>
                                    @endif
                                </div>

                                <h3 class="h5 mb-2">{{ $project->name }}</h3>

                                <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                                    @if ($project->location)
                                        <span><i class="fa-solid fa-location-dot me-1"></i>{{ $project->location }}</span>
                                    @endif
                                    @if ($project->company)
                                        <span><i class="fa-solid fa-building me-1"></i><a class="text-muted text-decoration-none" href="{{ route('companies.show', $project->company->slug) }}">{{ $project->company->name }}</a></span>
                                    @endif
                                </div>
                            </div>

                            <div class="px-4 pb-3">
                                @if ($project->description)
                                    <p class="mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 240) }}</p>
                                @endif
                                <div class="row g-2 small">
                                    @if ($project->investment)
                                        <div class="col-12 col-sm-6">
                                            <div class="border rounded p-2 h-100">
                                                <div class="text-muted">Investissement</div>
                                                <div class="fw-semibold">{{ formatNumber($project->investment) }} FCFA</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($project->jobs_created)
                                        <div class="col-12 col-sm-6">
                                            <div class="border rounded p-2 h-100">
                                                <div class="text-muted">Emplois</div>
                                                <div class="fw-semibold">{{ formatNumber($project->jobs_created) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="px-4 pb-4">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('projects.index') }}#{{ $project->slug }}">Voir le projet</a>
                                @if ($project->company)
                                    <a class="btn btn-sm btn-primary ms-2" href="{{ route('companies.show', $project->company->slug) }}">Voir l’entreprise</a>
                                @endif
                            </div>
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
