@extends('layouts.front')

@section('title', __('contact.title'))
@section('meta_description', app()->getLocale() === 'en'
    ? 'Contact Ivoire Industrie Magazine (2IM) — Get in touch with the editorial team for partnerships, press releases or advertising.'
    : 'Contactez Ivoire Industrie Magazine (2IM) — Rejoignez l\'équipe éditoriale pour des partenariats, communiqués de presse ou publicités.')
@section('meta_robots', 'noindex, follow')

@section('content')
    @include('front.partials.page-header', ['title' => __('contact.title')])

    <section class="space-ptb">
        <div class="container">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-white h-100 text-center shadow-sm">
                        <div class="mb-2 text-primary"><i class="fa-solid fa-envelope"></i></div>
                        <div class="small text-muted mb-1">{{ __('contact.email') }}</div>
                        @php $pubEmail = site_setting('contact_email'); @endphp
                        @if($pubEmail !== '')
                            <a href="mailto:{{ $pubEmail }}">{{ $pubEmail }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-white h-100 text-center shadow-sm">
                        <div class="mb-2 text-primary"><i class="fa-solid fa-phone"></i></div>
                        <div class="small text-muted mb-1">{{ __('contact.phone') }}</div>
                        @php $pubPhone = site_setting('contact_phone'); @endphp
                        @if($pubPhone !== '')
                            <a href="tel:{{ preg_replace('/\s+/', '', $pubPhone) }}">{{ $pubPhone }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-white h-100 text-center shadow-sm">
                        <div class="mb-2 text-primary"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="small text-muted mb-1">{{ __('contact.address') }}</div>
                        <span>{{ site_setting('contact_address') }}</span>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="border rounded p-4 bg-light shadow-sm">
                        <h3 class="h5 mb-3">{{ __('contact.form_title') }}</h3>
                        <p class="text-muted small mb-4">{{ __('contact.form_hint') }}</p>
                        <form class="row g-3" method="POST" action="#">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('contact.full_name') }}</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('contact.phone') }}</label>
                                <input type="text" class="form-control" placeholder="00225 01 01 151 908">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('contact.email_label') }}</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('contact.subject') }}</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('contact.message') }}</label>
                                <textarea class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">{{ __('contact.send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
