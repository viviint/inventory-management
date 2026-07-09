<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Auto-assign the 'staff' role to all self-registered users
        $staffRole = Role::where('name', 'staff')->first();

        $user = User::create([
            'role_id'  => $staffRole?->id,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('User-Agent', ''), 'Postman')) {
            return response()->json([
                'message' => 'Registration successful',
                'user'    => $user->load('role'),
            ], 201);
        }

        // Staff land on products, Admin/Manager on dashboard
        $redirect = $user->isAdmin() || $user->isManager()
            ? route('dashboard', absolute: false)
            : route('products.index', absolute: false);

        return redirect($redirect);
    }
}
