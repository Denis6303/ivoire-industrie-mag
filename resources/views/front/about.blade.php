@extends('layouts.front')

@section('title', __('about.title'))

@section('content')
    @include('front.partials.page-header', ['title' => __('about.title')])

    <section class="space-ptb">
        <div class="container">
            <div class="row align-items-start g-4">
                <div class="col-lg-7">
                    <div class="border rounded p-4 p-md-5 bg-white shadow-sm">
                        <span class="badge rounded-pill mb-3" style="background:#243e5d;">{{ __('about.mission_badge') }}</span>
                        <h2 class="mb-4 h3">{{ __('about.headline') }}</h2>
                        <p class="text-muted mb-3">{{ __('about.intro_p1') }}</p>
                        <p class="text-muted mb-3">{{ __('about.intro_p2') }}</p>
                        <p class="text-muted mb-0">{{ __('about.intro_p3') }}</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border rounded p-4 bg-light">
                        <h3 class="h5 mb-3">{{ __('about.commitments') }}</h3>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>{{ __('about.commitment_1') }}</span></li>
                            <li class="mb-3 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>{{ __('about.commitment_2') }}</span></li>
                            <li class="mb-0 d-flex"><i class="fa-solid fa-circle-check text-primary me-2 mt-1"></i><span>{{ __('about.commitment_3') }}</span></li>
                        </ul>
                        <div class="small text-muted">{{ __('about.cta_hint') }}</div>
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm mt-2">{{ __('about.cta') }}</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">100%</div>
                        <p class="mb-0 text-muted">{{ __('about.stat_1') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">Focus</div>
                        <p class="mb-0 text-muted">{{ __('about.stat_2') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 text-center bg-white h-100">
                        <div class="h3 mb-1 text-primary fw-bold">Impact</div>
                        <p class="mb-0 text-muted">{{ __('about.stat_3') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
