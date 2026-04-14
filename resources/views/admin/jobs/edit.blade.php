@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Éditer une offre d'emploi</h1>
        <a href="{{ route('admin.emplois.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form id="job-form" method="POST" action="{{ route('admin.emplois.update', $job) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-12">
                    <label class="form-label">Titre</label>
                    <input name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Signature</label>
                    <input name="signature" class="form-control" value="{{ old('signature', $job->signature) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Image (optionnel)</label>
                    <input type="file" name="cover_file" id="cover_file" class="form-control" accept="image/*">
                    <label class="form-label mt-2">Texte alternatif (optionnel)</label>
                    <input type="text" name="cover_alt" class="form-control" value="{{ old('cover_alt', $job->cover_alt) }}">
                </div>
                <div class="col-12">
                    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                    <div class="mb-2">Corps de l'offre</div>
                    <div id="quill-editor" style="height: 320px;" class="bg-white"></div>
                    <input type="hidden" name="content" id="content" value="{{ old('content', $job->content) }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#quill-editor', { theme: 'snow' });
            const initial = document.getElementById('content').value;
            if (initial) quill.root.innerHTML = initial;
            document.getElementById('job-form').addEventListener('submit', function () {
                document.getElementById('content').value = quill.root.innerHTML;
            });
        });
    </script>
@endsection
