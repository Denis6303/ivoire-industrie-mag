@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">{{ $publicite->title }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.publicites.edit', $publicite) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.publicites.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Position</dt>
                <dd class="col-md-9">{{ $publicite->position }}</dd>

                <dt class="col-md-3 text-muted">Active</dt>
                <dd class="col-md-9">{{ $publicite->is_active ? 'Oui' : 'Non' }}</dd>

                <dt class="col-md-3 text-muted">Lien</dt>
                <dd class="col-md-9">{{ $publicite->link_url ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Image</dt>
                <dd class="col-md-9">
                    @if($publicite->image_url)
                        <img src="{{ $publicite->image_url }}" alt="{{ $publicite->title }}" style="max-height:80px" class="rounded">
                    @else
                        -
                    @endif
                </dd>

                <dt class="col-md-3 text-muted">Clics</dt>
                <dd class="col-md-9">{{ $publicite->click_count }}</dd>

                <dt class="col-md-3 text-muted">Vues</dt>
                <dd class="col-md-9">{{ $publicite->view_count }}</dd>
            </dl>
        </div>
    </div>
@endsection

