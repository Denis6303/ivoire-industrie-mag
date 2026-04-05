@extends('layouts.front')

@section('title', 'Secteurs industriels')

@section('content')
    @include('front.partials.page-header', ['title' => 'Secteurs industriels'])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                @forelse ($sectors as $sector)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="blog-post post-style-02 h-100 border rounded p-3">
                            <div class="blog-post-details">
                                <h4 class="blog-title">
                                    <a href="{{ route('sectors.show', $sector->slug) }}">{{ $sector->name }}</a>
                                </h4>
                                @if ($sector->description)
                                    <p class="small text-muted">{{ \Illuminate\Support\Str::limit($sector->description, 120) }}</p>
                                @endif
                                <a class="btn-link" href="{{ route('sectors.show', $sector->slug) }}">Voir les articles</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Aucun secteur publié.</p>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $sectors->links() }}
            </div>
        </div>
    </section>
@endsection
