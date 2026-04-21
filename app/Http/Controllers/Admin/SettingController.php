<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    private const SITE_SETTING_KEYS = [
        'contact_email' => 'contact',
        'contact_phone' => 'contact',
        'contact_address' => 'contact',
        'social_facebook' => 'social',
        'social_x' => 'social',
        'social_linkedin' => 'social',
        'social_instagram' => 'social',
        'social_youtube' => 'social',
    ];

    public function index()
    {
        $siteSettings = SiteSetting::query()
            ->whereIn('key', array_keys(self::SITE_SETTING_KEYS))
            ->pluck('value', 'key');

        return view('admin.settings.index', compact('siteSettings'));
    }

    public function update(Request $request)
    {
        $section = (string) $request->input('section', 'site');

        if ($section === 'profile') {
            $data = $request->validate([
                'first_name' => ['required', 'string', 'max:120'],
                'last_name' => ['nullable', 'string', 'max:120'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            $fullName = trim($data['first_name'].' '.($data['last_name'] ?? ''));
            $user = $request->user();
            $user->name = $fullName !== '' ? $fullName : $user->name;

            if (! empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return back()->with('success', 'Profil mis à jour.');
        }

        if ($section === 'site') {
            $data = $request->validate([
                'settings' => ['required', 'array'],
                'settings.contact_email' => ['nullable', 'string', 'max:255'],
                'settings.contact_phone' => ['nullable', 'string', 'max:120'],
                'settings.contact_address' => ['nullable', 'string', 'max:500'],
                'settings.social_facebook' => ['nullable', 'string', 'max:500'],
                'settings.social_x' => ['nullable', 'string', 'max:500'],
                'settings.social_linkedin' => ['nullable', 'string', 'max:500'],
                'settings.social_instagram' => ['nullable', 'string', 'max:500'],
                'settings.social_youtube' => ['nullable', 'string', 'max:500'],
            ]);

            $email = trim((string) ($data['settings']['contact_email'] ?? ''));
            if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->withErrors(['settings.contact_email' => 'Adresse e-mail invalide.'])->withInput();
            }

            $settings = $data['settings'] ?? [];

            foreach (array_keys(self::SITE_SETTING_KEYS) as $key) {
                if (! array_key_exists($key, $settings)) {
                    continue;
                }
                $value = $settings[$key];
                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => is_string($value) ? $value : '',
                        'group' => self::SITE_SETTING_KEYS[$key],
                    ]
                );
            }

            flush_site_settings_cache();

            return back()->with('success', 'Informations du site mises à jour.');
        }

        return back()->with('error', 'Section inconnue.');
    }
}
