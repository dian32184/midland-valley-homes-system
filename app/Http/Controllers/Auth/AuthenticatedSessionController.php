<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $role = $user->role ?? 'marketing';
        $email = strtolower((string) ($user->email ?? ''));

        if ($role === 'documentation' || str_contains($email, 'docs@') || str_contains($email, 'documentation@')) {
            return redirect()->route('documentationdashboard');
        }

        if ($role === 'manager' || str_contains($email, 'manager@')) {
            return redirect()->route('managerdashboard');
        }

        if ($role === 'admin' || str_contains($email, 'admin@')) {
            return redirect()->route('admindashboard');
        }

        return redirect()->route('marketingdashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
