@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Secteurs industriels</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.secteurs.create') }}">Nouveau secteur</a>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th class="text-center">Actif</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sectors as $secteur)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($secteur->color)
                                <span class="badge" style="background: {{ $secteur->color }}; color: #fff">#</span>
                            @endif
                            <span>{{ $secteur->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted small">{{ $secteur->slug }}</td>
                    <td class="text-center">
                        @if($secteur->is_active)
                            <span class="badge text-bg-success">Oui</span>
                        @else
                            <span class="badge text-bg-secondary">Non</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.secteurs.show', $secteur) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.secteurs.edit', $secteur) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.secteurs.destroy', $secteur) }}" class="d-inline">
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

    <div class="mt-3">{{ $sectors->links() }}</div>
@endsection

