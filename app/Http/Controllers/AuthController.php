<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display the unified single login view.
     * Automatically redirects to the user's portal if already authenticated.
     */
    public function login()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'assistant') {
                return redirect()->route('assistant.electoral');
            }
            return redirect()->route('admin.electoral');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication for any user and automatically route by role.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($credentials['email']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Check if input is formatted as email or username
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'username';

        // Attempt login with determined field
        $authSuccess = Auth::attempt([$field => $loginInput, 'password' => $password], $remember);

        // Fallback attempt: if username failed, try email in case of non-standard email format
        if (!$authSuccess && !$isEmail) {
            $authSuccess = Auth::attempt(['email' => $loginInput, 'password' => $password], $remember);
        }

        if (!$authSuccess) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Invalid credentials. Please verify your username/email and password.',
                ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        session()->flash('success', "Welcome back, {$user->name}!");

        // Automatically verify user's role from database and redirect
        if ($user->role === 'assistant') {
            return redirect()->intended(route('assistant.electoral'));
        }

        return redirect()->intended(route('admin.electoral'));
    }

    /**
     * Handle user logout and session invalidation.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }
}
