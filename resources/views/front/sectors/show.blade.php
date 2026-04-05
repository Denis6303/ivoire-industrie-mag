@extends('layouts.front')

@section('title', e($sector->name))

@section('content')
    @include('front.partials.page-header', [
        'title' => $sector->name,
        'breadcrumbItems' => [['label' => 'Secteurs', 'url' => route('sectors.index')]],
    ])

    <section class="space-ptb">
        <div class="container">
            @if ($sector->description)
                <p class="lead mb-4">{{ $sector->description }}</p>
            @endif
            <div class="row">
                <div class="col-lg-8">
                    @forelse ($articles as $article)
                        <x-article-card :article="$article" style="11" />
                    @empty
                        <p class="text-muted">Aucun article pour ce secteur.</p>
                    @endforelse
                    {{ $articles->links() }}
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget">
                            <h6 class="widget-title">Autres secteurs</h6>
                            @foreach ($otherSectors as $s)
                                <p class="mb-2"><a href="{{ route('sectors.show', $s->slug) }}">{{ $s->name }}</a></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
