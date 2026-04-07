<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
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

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            return back()->with('success', 'Profil mis à jour.');
        }

        $data = $request->validate([
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string'],
        ]);

        foreach (($data['settings'] ?? []) as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Paramètres mis à jour.');
    }
}
