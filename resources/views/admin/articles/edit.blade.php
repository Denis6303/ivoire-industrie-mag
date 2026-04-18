@extends('layouts.admin')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Éditer l'article</h1>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form id="article-form" method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input name="title" class="form-control" value="{{ old('title', $article->title) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" @selected((int) old('category_id', $article->category_id) === (int) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Châpeau</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tags</label>
                    @php
                        $selectedTags = old('tags', $article->tags->pluck('id')->map(fn ($id) => (string) $id)->all());
                    @endphp
                    <select id="article-tags" name="tags[]" class="form-select" multiple>
                        @foreach(($tags ?? []) as $tag)
                            <option value="{{ $tag->id }}" @selected(in_array((string) $tag->id, (array) $selectedTags, true))>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Recherche rapide et sélection multiple.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image de couverture (optionnel)</label>
                    <div class="admin-upload-zone">
                        <input type="file" name="cover_file" id="cover_file" class="form-control" accept="image/*">
                        <div class="small text-muted mt-2">Formats recommandés : JPG/PNG/WebP, max 10 MB.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Texte alternatif (optionnel)</label>
                    <input type="text" name="cover_alt" class="form-control" value="{{ old('cover_alt', $article->cover_alt) }}">

                    <div class="mt-2">
                        <img
                            id="cover-preview"
                            alt="{{ $article->cover_alt ?? '' }}"
                            src="{{ article_cover($article->cover_image) }}"
                            style="{{ $article->cover_image ? 'display:block; max-height:160px;' : 'display:none; max-height:160px;' }}"
                            class="img-thumbnail"
                        >
                    </div>
                    @if($article->cover_image)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="remove_cover_image" name="remove_cover_image">
                            <label class="form-check-label" for="remove_cover_image">Supprimer l'image principale</label>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image 2 (après 2 paragraphes)</label>
                    <div class="admin-upload-zone">
                        <input type="file" name="cover_file_secondary" id="cover_file_secondary" class="form-control" accept="image/*">
                        <div class="small text-muted mt-2">Insertion automatique après 2 paragraphes.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Texte alternatif image 2 (optionnel)</label>
                    <input type="text" name="cover_alt_secondary" class="form-control" value="{{ old('cover_alt_secondary', $article->secondary_alt) }}">
                    <div class="mt-2">
                        <img
                            id="cover-preview-secondary"
                            alt="{{ $article->secondary_alt ?? '' }}"
                            src="{{ article_cover($article->secondary_image) }}"
                            style="{{ $article->secondary_image ? 'display:block; max-height:160px;' : 'display:none; max-height:160px;' }}"
                            class="img-thumbnail"
                        >
                    </div>
                    @if(!empty($article->secondary_image))
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="remove_secondary_image" name="remove_secondary_image">
                            <label class="form-check-label" for="remove_secondary_image">Supprimer l'image 2</label>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Image 3 (après 4 paragraphes)</label>
                    <div class="admin-upload-zone">
                        <input type="file" name="cover_file_tertiary" id="cover_file_tertiary" class="form-control" accept="image/*">
                        <div class="small text-muted mt-2">Insertion automatique après 4 paragraphes.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Texte alternatif image 3 (optionnel)</label>
                    <input type="text" name="cover_alt_tertiary" class="form-control" value="{{ old('cover_alt_tertiary', $article->tertiary_alt) }}">
                    <div class="mt-2">
                        <img
                            id="cover-preview-tertiary"
                            alt="{{ $article->tertiary_alt ?? '' }}"
                            src="{{ article_cover($article->tertiary_image) }}"
                            style="{{ $article->tertiary_image ? 'display:block; max-height:160px;' : 'display:none; max-height:160px;' }}"
                            class="img-thumbnail"
                        >
                    </div>
                    @if(!empty($article->tertiary_image))
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="remove_tertiary_image" name="remove_tertiary_image">
                            <label class="form-check-label" for="remove_tertiary_image">Supprimer l'image 3</label>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                    <div class="mb-2">Contenu (HTML riche)</div>
                    <div id="quill-editor" style="height: 320px;" class="bg-white"></div>
                    <input type="hidden" name="content" id="content" value="{{ old('content', $article->content) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Signature auteur</label>
                    <input name="signature" class="form-control" value="{{ old('signature', $article->signature) }}">
                </div>

                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#article-tags', {
                plugins: ['remove_button'],
                hideSelected: true,
                closeAfterSelect: false,
                placeholder: 'Choisir un ou plusieurs tags...'
            });

            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote', 'code-block']
                    ]
                }
            });

            const initial = document.getElementById('content').value;
            if (initial) {
                quill.root.innerHTML = initial;
            }

            const form = document.getElementById('article-form');
            const coverFile = document.getElementById('cover_file');
            const coverPreview = document.getElementById('cover-preview');
            const coverFileSecondary = document.getElementById('cover_file_secondary');
            const coverPreviewSecondary = document.getElementById('cover-preview-secondary');
            const coverFileTertiary = document.getElementById('cover_file_tertiary');
            const coverPreviewTertiary = document.getElementById('cover-preview-tertiary');
            if (coverFile && coverPreview) {
                coverFile.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const url = URL.createObjectURL(this.files[0]);
                    coverPreview.src = url;
                    coverPreview.style.display = 'block';
                });
            }
            if (coverFileSecondary && coverPreviewSecondary) {
                coverFileSecondary.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const url = URL.createObjectURL(this.files[0]);
                    coverPreviewSecondary.src = url;
                    coverPreviewSecondary.style.display = 'block';
                });
            }
            if (coverFileTertiary && coverPreviewTertiary) {
                coverFileTertiary.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const url = URL.createObjectURL(this.files[0]);
                    coverPreviewTertiary.src = url;
                    coverPreviewTertiary.style.display = 'block';
                });
            }

            form.addEventListener('submit', function () {
                document.getElementById('content').value = quill.root.innerHTML;
            });
        });
    </script>
@endsection
