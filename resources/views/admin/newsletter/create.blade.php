@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Envoyer une newsletter</h1>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.newsletter.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Sujet</label>
                    <input class="form-control" name="subject" value="{{ old('subject') }}" required maxlength="255">
                </div>
                <div class="col-12">
                    <label class="form-label">Message</label>
                    <textarea class="form-control" name="body" rows="8" required>{{ old('body') }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

