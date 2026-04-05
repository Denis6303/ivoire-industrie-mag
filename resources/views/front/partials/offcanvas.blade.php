<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMain" aria-labelledby="offcanvasMainLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMainLabel">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="img-fluid" src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}">
            </a>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav navbar-nav-style-03">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Articles</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('sectors.index') }}">Secteurs</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">Entreprises</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Projets</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('search') }}">Recherche</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">À propos</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
            @endguest
        </ul>
    </div>
</div>
