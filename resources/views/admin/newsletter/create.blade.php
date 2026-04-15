@extends('layouts.admin')

@section('content')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Envoyer une newsletter</h1>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form id="newsletter-form" method="POST" action="{{ route('admin.newsletter.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Édition</label>
                    <input class="form-control" name="edition" value="{{ old('edition') }}" required maxlength="100" placeholder="Ex: Avril 2026">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sujet</label>
                    <input class="form-control" name="subject" value="{{ old('subject') }}" required maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Message</label>
                    <div id="newsletter-editor" style="height: 320px;" class="bg-white"></div>
                    <input type="hidden" name="body" id="newsletter-body" value="{{ old('body') }}">
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#newsletter-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote'],
                        ['clean']
                    ]
                }
            });

            const bodyInput = document.getElementById('newsletter-body');
            if (bodyInput && bodyInput.value) {
                quill.root.innerHTML = bodyInput.value;
            }

            const form = document.getElementById('newsletter-form');
            if (form) {
                form.addEventListener('submit', function () {
                    bodyInput.value = quill.root.innerHTML;
                });
            }
        });
    </script>
@endsection

