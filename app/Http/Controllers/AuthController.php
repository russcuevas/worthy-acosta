<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordMail;

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
     * Send a password reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your registered email address or username.',
        ]);

        $input = trim($request->input('email'));

        // Find user by email or username
        $user = User::where('email', $input)
            ->orWhere('username', $input)
            ->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account was found with that email address or username.',
                ], 404);
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'No account was found with that email address or username.');
        }

        // Generate token and record in password_reset_tokens
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user));
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send reset email: ' . $e->getMessage(),
                ], 500);
            }

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Unable to send email right now. Please verify mail service settings or try again later.');
        }

        $successMsg = "A password reset link has been emailed to {$user->email}. Please check your inbox and spam folder.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Show the password reset form with the given token and email.
     */
    public function showResetPasswordForm(Request $request, ?string $token = null)
    {
        $email = $request->query('email');
        $token = $token ?? $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('login')->with('error', 'Invalid password reset link. Please request a new one.');
        }

        // Check if token exists and is valid
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord || $resetRecord->token !== $token) {
            return redirect()->route('login')->with('error', 'This password reset link is invalid or has already been used.');
        }

        // Check token expiration (valid for 60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('login')->with('error', 'This password reset link has expired. Please request a new one.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 6 characters long.',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');

        // Check token validity in database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord || $resetRecord->token !== $token) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid or expired password reset link. Please request a new link.');
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('login')->with('error', 'This password reset link has expired. Please request a new one.');
        }

        // Find user and update password
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'User account not found.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Delete reset token after successful reset
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been successfully reset! You can now sign in with your new password.');
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
