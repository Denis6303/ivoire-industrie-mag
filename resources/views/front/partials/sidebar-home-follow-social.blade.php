<div class="widget mt-4">
    <h6 class="widget-title">{{ __('sidebar.follow_social_heading') }}</h6>
    @push('styles')
        <style>
            .ivm-social-row {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 12px;
            }
            .ivm-social-btn {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(36, 62, 93, 0.18);
                background: #ffffff;
                color: #243e5d;
                box-shadow: 0 8px 18px rgba(16, 24, 40, 0.08);
                transition: transform .15s ease, box-shadow .15s ease, background .15s ease, color .15s ease, border-color .15s ease;
            }
            .ivm-social-btn:hover,
            .ivm-social-btn:focus {
                transform: translateY(-1px);
                background: #243e5d;
                color: #fff;
                border-color: #243e5d;
                box-shadow: 0 12px 24px rgba(36, 62, 93, 0.22);
            }
            .ivm-social-btn i {
                font-size: 18px;
                line-height: 1;
            }
            .ivm-x-svg {
                width: 18px;
                height: 18px;
                display: block;
                fill: currentColor;
            }
        </style>
    @endpush

    @include('front.partials.social-links', ['variant' => 'sidebar'])
</div>
