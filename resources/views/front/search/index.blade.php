@extends('layouts.front')

@section('title', 'Recherche')

@section('content')
    @include('front.partials.page-header', ['title' => 'Recherche'])

    <section class="space-ptb">
        <div class="container">
            <form action="{{ route('search') }}" method="get" class="row g-2 mb-5">
                <div class="col-md-8">
                    <input type="search" name="q" value="{{ $q }}" class="form-control form-control-lg" placeholder="Rechercher un article…" minlength="2">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Rechercher</button>
                </div>
            </form>

            @if ($q === '')
                <p class="text-muted">Saisissez au moins 2 caractères pour lancer une recherche.</p>
            @elseif ($articles === null)
                <p class="text-muted">La recherche nécessite au moins 2 caractères.</p>
            @elseif ($articles->isEmpty())
                <p>Aucun article ne correspond à « {{ $q }} ».</p>
            @else
                <p class="mb-4">{{ $articles->total() }} résultat(s) pour « {{ $q }} »</p>
                @foreach ($articles as $article)
                    <x-article-card :article="$article" style="11" />
                @endforeach
                <div class="mt-4">{{ $articles->links() }}</div>
            @endif
        </div>
    </section>
@endsection
