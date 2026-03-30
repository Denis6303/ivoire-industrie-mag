@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Editer l'abonnement</h1>
        <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.newsletter.update', $subscription) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" value="{{ $subscription->email }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        @foreach(['pending','active','unsubscribed'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $subscription->status) === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
@endsection

