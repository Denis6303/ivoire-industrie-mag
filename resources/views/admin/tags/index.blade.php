@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Tags</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.tags.create') }}">Nouveau tag</a>
    </div>

    <div class="table-responsive card card-mag p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td class="text-muted small">{{ $tag->slug }}</td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.tags.show', $tag) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.tags.edit', $tag) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="d-inline">
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

    <div class="mt-3">
        {{ $tags->links() }}
    </div>
@endsection

