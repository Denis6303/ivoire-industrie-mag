@extends('layouts.admin')

@section('content')
    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Offres d'emploi</h1>
            <p class="text-muted mb-0">Publiez et pilotez les offres d'emploi du média.</p>
        </div>
        <a href="{{ route('admin.emplois.create') }}" class="btn btn-ivm">
            <i class="fa-solid fa-plus me-2"></i>Nouvelle offre
        </a>
    </div>

    <div class="card card-mag admin-table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Statut</th>
                            <th class="text-end">Mise à jour</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td class="fw-semibold">
                                    <div>{{ $offer->title }}</div>
                                    <div class="small text-muted">{{ $offer->slug }}</div>
                                </td>
                                <td>{{ optional($offer->author)->name ?? '-' }}</td>
                                <td>
                                    @if($offer->status === 'published')
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Publiée</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Brouillon</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ optional($offer->updated_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.emplois.edit', $offer) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        @if($offer->status !== 'published')
                                            <form action="{{ route('admin.jobs.publish', $offer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Publier">
                                                    <i class="fa-solid fa-upload"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.jobs.unpublish', $offer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Passer en brouillon">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.emplois.destroy', $offer) }}" method="POST" onsubmit="return confirm('Supprimer cette offre ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                        @if($offer->status === 'published')
                                            <a href="{{ route('jobs.show', [config('app.locale', 'fr'), $offer->slug]) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucune offre d'emploi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $offers->links() }}</div>
@endsection
