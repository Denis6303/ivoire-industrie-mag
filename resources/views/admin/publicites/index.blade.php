@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Publicités</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.publicites.create') }}">Nouvelle pub</a>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Position</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Clics</th>
                    <th class="text-center">Vues</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($ads as $ad)
                <tr>
                    <td>{{ $ad->title }}</td>
                    <td class="text-muted small">{{ $ad->position }}</td>
                    <td class="text-center">
                        @if($ad->is_active)
                            <span class="badge text-bg-success">Oui</span>
                        @else
                            <span class="badge text-bg-secondary">Non</span>
                        @endif
                    </td>
                    <td class="text-center small text-muted">{{ $ad->click_count }}</td>
                    <td class="text-center small text-muted">{{ $ad->view_count }}</td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.publicites.show', $ad) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.publicites.edit', $ad) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.publicites.destroy', $ad) }}" class="d-inline">
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

    <div class="mt-3">{{ $ads->links() }}</div>
@endsection

