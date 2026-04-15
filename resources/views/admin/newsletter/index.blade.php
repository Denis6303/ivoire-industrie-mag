@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Newsletter</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.newsletter.create') }}">Envoyer</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card card-mag h-100">
                <div class="card-body py-3">
                    <div class="small text-muted">Abonnés totaux</div>
                    <div class="h4 mb-0">{{ $counts['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-mag h-100">
                <div class="card-body py-3">
                    <div class="small text-muted">Confirmés</div>
                    <div class="h4 mb-0">{{ $counts['active'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-mag h-100">
                <div class="card-body py-3">
                    <div class="small text-muted">En attente</div>
                    <div class="h4 mb-0">{{ $counts['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-mag h-100">
                <div class="card-body py-3">
                    <div class="small text-muted">Désabonnés</div>
                    <div class="h4 mb-0">{{ $counts['unsubscribed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Confirmé</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($subscriptions as $sub)
                    <tr>
                        <td>{{ $sub->email }}</td>
                        <td>
                            <span class="badge text-bg-{{ $sub->status === 'active' ? 'success' : ($sub->status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ optional($sub->confirmed_at)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.newsletter.show', $sub) }}">Voir</a>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.newsletter.edit', $sub) }}">Editer</a>
                            <form method="POST" action="{{ route('admin.newsletter.destroy', $sub) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ?')" type="submit">Suppr.</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Aucun abonné newsletter pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $subscriptions->links() }}</div>
@endsection

