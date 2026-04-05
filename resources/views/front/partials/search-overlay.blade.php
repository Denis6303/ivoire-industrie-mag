<div id="search">
    <button type="button" class="close" aria-label="Fermer"><i class="fa-solid fa-xmark"></i></button>
    <form action="{{ route('search') }}" method="get">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-8 position-relative">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Mot-clé, secteur, entreprise…" autocomplete="off">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </div>
    </form>
</div>
