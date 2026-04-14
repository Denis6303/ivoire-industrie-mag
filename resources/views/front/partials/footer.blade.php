<footer class="footer">
    <div class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-7 mb-4">
                    <div class="footer-about">
                        <a class="footer-logo" href="{{ route('home') }}">
                            <img src="{{ asset('images/logo_2im_blanc.svg') }}" alt="Ivoire Industrie Magazine" style="width: 180px; max-width: 100%; height: auto; object-fit: contain;">
                        </a>
                        <p>Ivoire Industrie Magazine vulgarise l’industrie ivoirienne : actualités, analyses et portraits d’entreprises pour décideurs et citoyens.</p>
                        <div class="footer-social">
                            <ul class="social-icons">
                                <li><a href="#" class="social-icon facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#" class="social-icon twitter" aria-label="X"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a href="#" class="social-icon linkedin" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-5 mb-4">
                    <h4 class="footer-title">Navigation</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('categories.show', ['slug' => 'industrie-story']) }}"><i class="fa-solid fa-chevron-right"></i>Industrie Story</a></li>
                        <li><a href="{{ route('categories.show', ['slug' => 'industrie']) }}"><i class="fa-solid fa-chevron-right"></i>Industrie</a></li>
                        <li><a href="{{ route('categories.show', ['slug' => 'zones-industrielles']) }}"><i class="fa-solid fa-chevron-right"></i>Zones industrielles</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <h4 class="footer-title">Informations</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('about') }}"><i class="fa-solid fa-chevron-right"></i>À propos</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa-solid fa-chevron-right"></i>Contact</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <h4 class="footer-title">Newsletter</h4>
                    <div class="newsletter">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <p>Recevez les temps forts de l’industrie en Côte d’Ivoire.</p>
                        <form class="newsletter-box" method="POST" action="{{ route('newsletter.subscribe') }}">
                            @csrf
                            <input type="email" name="email" class="form-control" placeholder="Votre e-mail" required>
                            <button type="submit" class="btn btn-primary">S’abonner</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row copyright justify-content-center">
                <div class="col-md-12 text-center">
                    <p class="mb-0">
                        © 2026 <a href="{{ route('home') }}">Ivoire Industrie Magazine</a> propulsé par Build It Agency - Tous les droits réservés
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
