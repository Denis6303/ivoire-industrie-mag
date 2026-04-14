@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Créer une brève</h1>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form id="breve-form" method="POST" action="{{ route('admin.articles.breves.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-12">
                    <label class="form-label">Titre</label>
                    <input name="title" class="form-control" placeholder="Titre de la brève" value="{{ old('title') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Signature</label>
                    <input name="signature" class="form-control" placeholder="Ex: Rédaction" value="{{ old('signature') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Image (optionnel)</label>
                    <input type="file" name="cover_file" id="cover_file" class="form-control" accept="image/*">

                    <label class="form-label mt-2">Texte alternatif (optionnel)</label>
                    <input type="text" name="cover_alt" class="form-control" value="{{ old('cover_alt') }}">

                    <div class="mt-2">
                        <img id="cover-preview" alt="" style="display:none; max-height:160px;" class="img-thumbnail">
                    </div>
                </div>

                <div class="col-12">
                    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                    <div class="mb-2">Corps de la brève</div>
                    <div id="quill-editor" style="height: 280px;" class="bg-white"></div>
                    <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                </div>

                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Enregistrer la brève</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote']
                    ]
                }
            });

            const initial = document.getElementById('content').value;
            if (initial) {
                quill.root.innerHTML = initial;
            }

            const form = document.getElementById('breve-form');
            const coverFile = document.getElementById('cover_file');
            const coverPreview = document.getElementById('cover-preview');

            if (coverFile && coverPreview) {
                coverFile.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const url = URL.createObjectURL(this.files[0]);
                    coverPreview.src = url;
                    coverPreview.style.display = 'block';
                });
            }

            form.addEventListener('submit', function () {
                document.getElementById('content').value = quill.root.innerHTML;
            });
        });
    </script>
@endsection
