@php
    $social = config('ivoireindustriemag.social', []);
@endphp

<div class="widget mt-4">
    <h6 class="widget-title">{{ __('sidebar.follow_social') }}</h6>
    <div class="follow-style-01">
        <div class="row g-2">
            <div class="col-6 facebook-fans">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['facebook']['url'] ?? '#' }}" class="fans-icon" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <span>{{ $social['facebook']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn fans"><a href="{{ $social['facebook']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_fans') }}</a></div>
                </div>
            </div>
            <div class="col-6 twitter-follower">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['twitter']['url'] ?? '#' }}" class="twitter-icon" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <span>{{ $social['twitter']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn follower"><a href="{{ $social['twitter']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_followers') }}</a></div>
                </div>
            </div>
            <div class="col-6 you-tube">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['youtube']['url'] ?? '#' }}" class="tube-icon" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <span>{{ $social['youtube']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn subscriber"><a href="{{ $social['youtube']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_subscribers') }}</a></div>
                </div>
            </div>
            <div class="col-6 instagram-Follower">
                <div class="social-box">
                    <div class="social">
                        <a href="{{ $social['instagram']['url'] ?? '#' }}" class="instagram-icon" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <span>{{ $social['instagram']['count'] ?? '—' }}</span>
                    </div>
                    <div class="follower-btn instagrams"><a href="{{ $social['instagram']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ __('sidebar.social_followers') }}</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
