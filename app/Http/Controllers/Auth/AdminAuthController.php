<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const REGISTERABLE_ROLES = [
        'admin' => 'Admin',
        'editor' => 'Rédacteur',
        'author' => 'Auteur',
    ];

    public function showLogin()
    {
        return view('auth.admin.login');
    }

    public function showRegister()
    {
        return view('auth.admin.register', ['roles' => self::REGISTERABLE_ROLES]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Identifiants invalides.',
            ]);
        }

        $request->session()->regenerate();

        if (! in_array(auth()->user()->role, ['super_admin', 'admin', 'editor'], true)) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Accès admin refusé pour ce compte.',
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys(self::REGISTERABLE_ROLES))],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Compte administrateur créé.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
