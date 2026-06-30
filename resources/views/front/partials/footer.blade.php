<footer class="footer">
    <div class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-7 mb-4">
                    <div class="footer-about">
                        <a class="footer-logo" href="{{ route('home') }}">
                            <img src="{{ asset('images/2im_blanc.png') }}" alt="Ivoire Industrie Magazine" style="width: 180px; max-width: 100%; height: auto; object-fit: contain;">
                        </a>
                        <p class="text-white small mb-2" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;line-height:1.55;">
                            {{ __('about.intro_p1') }} {{ __('about.intro_p2') }} {{ __('about.intro_p3') }}
                        </p>
                        <p class="mb-5">
                            <a href="{{ route('about') }}" class="text-decoration-none fw-semibold" style="color:#ff7800;">{{ __('front.footer_read_about') }}</a>
                        </p>
                        <div class="footer-social">
                            @include('front.partials.social-links', ['variant' => 'footer'])
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-6 col-md-6 col-sm-5 mb-4">
                    <h4 class="footer-title">{{ __('front.navigation') }}</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('categories.show', ['slug' => 'industrie-story']) }}"><i class="fa-solid fa-chevron-right"></i>Industrie Story</a></li>
                        <li><a href="{{ category_route('industrie') }}"><i class="fa-solid fa-chevron-right"></i>{{ __('nav.industry') }}</a></li>
                        <li><a href="{{ route('categories.show', ['slug' => 'zones-industrielles']) }}"><i class="fa-solid fa-chevron-right"></i>{{ __('nav.industrial_zones') }}</a></li>
                        @if ($intlParent = $navInternationalParent ?? null)
                            <li><a href="{{ category_show_url($intlParent) }}"><i class="fa-solid fa-chevron-right"></i>{{ __('nav.international') }}</a></li>
                            @foreach ($navInternationalChildren ?? collect() as $child)
                                <li class="ps-3"><a href="{{ route('categories.show', ['slug' => $child->slug]) }}"><i class="fa-solid fa-chevron-right"></i>{{ category_i18n($child) }}</a></li>
                            @endforeach
                        @endif
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
