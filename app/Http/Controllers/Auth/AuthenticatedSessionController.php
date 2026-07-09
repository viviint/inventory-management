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
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('User-Agent', ''), 'Postman')) {
            return response()->json([
                'message' => 'Login successful',
                'user'    => $user->load('role'),
            ], 200);
        }

        // Staff land on products list; Admin and Manager land on dashboard
        $default = ($user->isAdmin() || $user->isManager())
            ? route('dashboard', absolute: false)
            : route('products.index', absolute: false);

        return redirect()->intended($default);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('User-Agent', ''), 'Postman')) {
            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);
        }

        return redirect('/');
    }
}
