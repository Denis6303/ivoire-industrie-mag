@extends('layouts.admin')

@section('content')
    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Articles</h1>
            <p class="text-muted mb-0">Pilotez les contenus, le statut de publication et les mises à jour.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.articles.breves.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-bolt me-2"></i>Nouvelle brève
            </a>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-ivm">
                <i class="fa-solid fa-plus me-2"></i>Nouvel article
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-primary">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total articles</div>
                    <div class="display-6 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-success">
                <div class="card-body">
                    <div class="text-muted small mb-1">Publiés</div>
                    <div class="display-6 fw-bold">{{ $stats['published'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-warning">
                <div class="card-body">
                    <div class="text-muted small mb-1">Brouillons</div>
                    <div class="display-6 fw-bold">{{ $stats['draft'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-info">
                <div class="card-body">
                    <div class="text-muted small mb-1">Mis en avant</div>
                    <div class="display-6 fw-bold">{{ $stats['featured'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-mag mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-2 align-items-end admin-filter-row">
                <div class="col-12 col-md-4 col-lg-5">
                    <label class="form-label mb-1">Recherche</label>
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Titre ou slug">
                </div>
                <div class="col-6 col-md-3 col-lg-3">
                    <label class="form-label mb-1">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="published" @selected($status === 'published')>Publié</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <label class="form-label mb-1">Catégorie</label>
                    <select name="category_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) $categoryId === (int) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-1 col-lg-1 d-grid">
                    <button class="btn btn-dark" type="submit">OK</button>
                </div>
                <div class="col-6 col-md-1 col-lg-1 d-grid">
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary" title="Réinitialiser les filtres" aria-label="Réinitialiser les filtres">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-mag admin-table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Auteur</th>
                            <th>Statut</th>
                            <th class="text-end">Mise à jour</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td class="fw-semibold">
                                    <div>{{ $article->title }}</div>
                                    <div class="small text-muted">{{ $article->slug }} · {{ $article->type === 'breve' ? 'Brève' : 'Article' }}</div>
                                </td>
                                <td>{{ optional($article->category)->name ?? '-' }}</td>
                                <td>{{ optional($article->author)->name ?? '-' }}</td>
                                <td>
                                    @if($article->status === 'published')
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Publié</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Brouillon</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ optional($article->updated_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        @if($article->status !== 'published')
                                            <form action="{{ route('admin.articles.publish', $article) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Publier">
                                                    <i class="fa-solid fa-upload"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.articles.unpublish', $article) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Passer en brouillon">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('articles.show', [config('app.locale', 'fr'), $article->slug]) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucun article trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $articles->links() }}</div>
@endsection
