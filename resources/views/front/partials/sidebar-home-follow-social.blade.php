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

    <div class="ivm-social-row" aria-label="{{ __('sidebar.follow_social_heading') }}">
        <a href="{{ site_setting('social_facebook') ?: '#' }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
        </a>
        <a href="{{ site_setting('social_linkedin') ?: '#' }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
            <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
        </a>
        <a href="{{ site_setting('social_x') ?: '#' }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="X">
            <svg class="ivm-x-svg" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.5l-5.1-6.6L5.8 22H2.7l7.3-8.4L1.2 2h6.6l4.6 6L18.9 2Zm-1.1 18h1.7L6.9 3.9H5.1L17.8 20Z"/>
            </svg>
        </a>
        <a href="{{ site_setting('social_youtube') ?: '#' }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
            <i class="fa-brands fa-youtube" aria-hidden="true"></i>
        </a>
    </div>
</div>
