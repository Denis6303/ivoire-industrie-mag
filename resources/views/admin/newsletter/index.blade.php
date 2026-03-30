@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Newsletter</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.newsletter.create') }}">Envoyer</a>
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
            @foreach($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->email }}</td>
                    <td>
                        <span class="badge text-bg-{{ $sub->status === 'active' ? 'success' : 'secondary' }}">{{ $sub->status }}</span>
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
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $subscriptions->links() }}</div>
@endsection

