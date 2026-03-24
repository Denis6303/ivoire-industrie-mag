<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function trackClick(Advertisement $ad): RedirectResponse
    {
        $ad->increment('click_count');
        return redirect()->away($ad->link_url);
    }
}
