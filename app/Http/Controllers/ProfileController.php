<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the profile management page or return authenticated user details.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'initials' => strtoupper(substr($user->name, 0, 2)),
                    'role_label' => $user->role === 'assistant' ? 'Assistant Officer' : 'Administrator',
                    'created_at' => $user->created_at ? $user->created_at->format('F d, Y') : null,
                ]
            ]);
        }

        return view('profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update the authenticated user's profile information (Name, Username, Email).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->name = $validated['name'];
        $user->username = strtolower(trim($validated['username']));
        $user->email = strtolower(trim($validated['email']));
        $user->save();

        $initials = strtoupper(substr($user->name, 0, 2));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'initials' => $initials,
                    'role_label' => $user->role === 'assistant' ? 'Assistant Officer' : 'Administrator',
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Please provide your current password.',
            'password.required' => 'Please provide a new password.',
            'password.min' => 'The new password must be at least 6 characters.',
            'password.confirmed' => 'The new password confirmation does not match.',
        ]);

        // Verify that current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The current password you provided is incorrect.',
                    'errors' => [
                        'current_password' => ['The current password you provided is incorrect.']
                    ]
                ], 422);
            }

            return redirect()->back()->withErrors([
                'current_password' => 'The current password you provided is incorrect.'
            ]);
        }

        // Update to new hashed password
        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!',
            ]);
        }

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
