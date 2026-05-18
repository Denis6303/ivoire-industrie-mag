{{-- Ordre : Facebook — LinkedIn — X — Instagram — TikTok — YouTube --}}
@props(['variant' => 'header'])

@php
    $url = static fn (string $key): string => site_setting($key) ?: '#';
@endphp

@if ($variant === 'header')
    <ul class="list-unstyled">
        <li><a href="{{ $url('social_facebook') }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a></li>
        <li><a href="{{ $url('social_linkedin') }}" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-linkedin-in"></i></a></li>
        <li><a href="{{ $url('social_x') }}" aria-label="X" target="_blank" rel="noopener noreferrer">@include('front.partials.icon-social-x', ['size' => 16])</a></li>
        <li><a href="{{ $url('social_instagram') }}" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a></li>
        <li><a href="{{ $url('social_tiktok') }}" aria-label="TikTok" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-tiktok"></i></a></li>
        <li><a href="{{ $url('social_youtube') }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i></a></li>
    </ul>
@elseif ($variant === 'footer')
    <ul class="social-icons">
        <li><a href="{{ $url('social_facebook') }}" class="social-icon facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a></li>
        <li><a href="{{ $url('social_linkedin') }}" class="social-icon linkedin" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></a></li>
        <li><a href="{{ $url('social_x') }}" class="social-icon twitter" aria-label="X" target="_blank" rel="noopener noreferrer">@include('front.partials.icon-social-x', ['size' => 18])</a></li>
        <li><a href="{{ $url('social_instagram') }}" class="social-icon ivm-social-instagram" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a></li>
        <li><a href="{{ $url('social_tiktok') }}" class="social-icon ivm-social-tiktok" aria-label="TikTok" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a></li>
        <li><a href="{{ $url('social_youtube') }}" class="social-icon youtube" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a></li>
    </ul>
@elseif ($variant === 'sidebar')
    <div class="ivm-social-row" aria-label="{{ __('sidebar.follow_social_heading') }}">
        <a href="{{ $url('social_facebook') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
        <a href="{{ $url('social_linkedin') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></a>
        <a href="{{ $url('social_x') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="X">@include('front.partials.icon-social-x', ['size' => 18])</a>
        <a href="{{ $url('social_instagram') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
        <a href="{{ $url('social_tiktok') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
        <a href="{{ $url('social_youtube') }}" class="ivm-social-btn" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
    </div>
@endif
