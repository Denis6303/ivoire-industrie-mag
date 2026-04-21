<footer class="footer">
    <div class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-7 mb-4">
                    <div class="footer-about">
                        <a class="footer-logo" href="{{ route('home') }}">
                            <img src="{{ asset('images/logo_2im_blanc.svg') }}" alt="Ivoire Industrie Magazine" style="width: 180px; max-width: 100%; height: auto; object-fit: contain;">
                        </a>
                        <p class="text-white small mb-2" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;line-height:1.55;">
                            {{ __('about.intro_p1') }} {{ __('about.intro_p2') }} {{ __('about.intro_p3') }}
                        </p>
                        <p class="mb-5">
                            <a href="{{ route('about') }}" class="text-decoration-none fw-semibold" style="color:#ff7800;">{{ __('front.footer_read_about') }}</a>
                        </p>
                        <div class="footer-social">
                            <ul class="social-icons">
                                <li>
                                    <a href="{{ site_setting('social_facebook') ?: '#' }}" class="social-icon facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ site_setting('social_linkedin') ?: '#' }}" class="social-icon linkedin" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ site_setting('social_x') ?: '#' }}" class="social-icon twitter" aria-label="X" target="_blank" rel="noopener noreferrer">
                                        <svg style="width:18px;height:18px;display:inline-block;fill:currentColor;vertical-align:middle;" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.5l-5.1-6.6L5.8 22H2.7l7.3-8.4L1.2 2h6.6l4.6 6L18.9 2Zm-1.1 18h1.7L6.9 3.9H5.1L17.8 20Z"/>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ site_setting('social_youtube') ?: '#' }}" class="social-icon youtube" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-5 mb-4">
                    <h4 class="footer-title">{{ __('front.navigation') }}</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('categories.show', ['slug' => 'industrie-story']) }}"><i class="fa-solid fa-chevron-right"></i>Industrie Story</a></li>
                        <li><a href="{{ category_route('industrie') }}"><i class="fa-solid fa-chevron-right"></i>{{ __('nav.industry') }}</a></li>
                        <li><a href="{{ route('categories.show', ['slug' => 'zones-industrielles']) }}"><i class="fa-solid fa-chevron-right"></i>{{ __('nav.industrial_zones') }}</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <h4 class="footer-title">{{ __('front.information') }}</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('about') }}"><i class="fa-solid fa-chevron-right"></i>{{ __('front.about_title') }}</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa-solid fa-chevron-right"></i>{{ __('front.contact_title') }}</a></li>
                    </ul>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <h4 class="footer-title">{{ __('front.newsletter') }}</h4>
                    <div class="newsletter" id="newsletter-subscribe">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <p>{{ __('front.newsletter_pitch') }}</p>
                        <form class="newsletter-box" method="POST" action="{{ route('newsletter.subscribe') }}">
                            @csrf
                            <input type="email" name="email" class="form-control" placeholder="{{ __('front.your_email') }}" required>
                            <button type="submit" class="btn btn-primary">{{ __('front.subscribe') }}</button>
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
                        {!! str_replace(
                            'Build It Agency',
                            '<span style="color:#ff7800;">Build It Agency</span>',
                            str_replace('Ivoire Industrie Magazine', '<span style="color:#ff7800;">Ivoire Industrie Magazine</span>', e(__('front.copyright')))
                        ) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
