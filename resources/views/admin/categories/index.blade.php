@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h1 class="h4 mb-0">Catégories</h1>
        <a class="btn btn-ivm btn-sm" href="{{ route('admin.categories.create') }}">Nouvelle catégorie</a>
    </div>

    <div class="table-responsive bg-white card-mag p-0 p-lg-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="text-muted small">{{ $category->slug }}</td>
                    <td class="small text-muted">{{ optional($category->parent)->name }}</td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.show', $category) }}">Voir</a>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.categories.edit', $category) }}">Editer</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
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
        {{ $categories->links() }}
    </div>
@endsection

