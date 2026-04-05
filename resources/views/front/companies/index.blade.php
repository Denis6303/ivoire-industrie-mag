@extends('layouts.front')

@section('title', 'Entreprises')

@section('content')
    @include('front.partials.page-header', ['title' => 'Entreprises'])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                @forelse ($companies as $company)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="blog-post post-style-02 h-100 border rounded p-3">
                            <div class="d-flex align-items-start mb-3">
                                @if ($company->logo)
                                    <img src="{{ $company->logo }}" alt="" class="me-3 rounded" style="width:64px;height:64px;object-fit:contain;">
                                @endif
                                <div>
                                    <h4 class="blog-title h6 mb-1">
                                        <a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a>
                                    </h4>
                                    @if ($company->sector)
                                        <span class="badge bg-primary">{{ $company->sector->name }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($company->description)
                                <p class="small">{{ \Illuminate\Support\Str::limit(strip_tags($company->description), 140) }}</p>
                            @endif
                            <a class="btn-link" href="{{ route('companies.show', $company->slug) }}">Fiche entreprise</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Aucune entreprise référencée.</p>
                @endforelse
            </div>
            {{ $companies->links() }}
        </div>
    </section>
@endsection
