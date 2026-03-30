@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Projets</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.projets.create') }}">Nouveau projet</a>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Secteur</th>
                    <th>Société</th>
                    <th class="text-center">Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($projects as $projet)
                <tr>
                    <td>{{ $projet->name }}</td>
                    <td class="text-muted small">{{ optional($projet->sector)->name ?? '-' }}</td>
                    <td class="text-muted small">{{ optional($projet->company)->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge text-bg-info">{{ $projet->status }}</span>
                    </td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.projets.show', $projet) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.projets.edit', $projet) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.projets.destroy', $projet) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ?')" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $projects->links() }}</div>
@endsection

