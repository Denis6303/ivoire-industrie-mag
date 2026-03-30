@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Abonnement</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.newsletter.edit', $subscription) }}">Editer</a>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.newsletter.index') }}">Retour</a>
        </div>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Email</dt>
                <dd class="col-md-9">{{ $subscription->email }}</dd>

                <dt class="col-md-3 text-muted">Statut</dt>
                <dd class="col-md-9">{{ $subscription->status }}</dd>

                <dt class="col-md-3 text-muted">Token</dt>
                <dd class="col-md-9">{{ $subscription->token }}</dd>

                <dt class="col-md-3 text-muted">Confirmé</dt>
                <dd class="col-md-9">{{ optional($subscription->confirmed_at)->format('d/m/Y H:i') ?? '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection

