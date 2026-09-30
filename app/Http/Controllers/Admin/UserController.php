<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the user account management dashboard.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $assistantCount = User::where('role', 'assistant')->count();

        return view('admin.users.index', compact('totalUsers', 'adminCount', 'assistantCount'));
    }

    /**
     * Get user records and statistics via AJAX.
     */
    public function getData(Request $request)
    {
        $search = trim($request->input('search', ''));
        $role = $request->input('role', '');

        $query = User::query();

        if (!empty($role) && $role !== 'all') {
            $query->where('role', $role);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $currentUserId = Auth::id();

        $formattedUsers = $users->map(function ($u) use ($currentUserId) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'username' => $u->username ?? '',
                'email' => $u->email,
                'role' => $u->role,
                'role_label' => $u->role === 'admin' ? 'Administrator' : 'Assistant Officer',
                'is_current_user' => ($u->id === $currentUserId),
                'created_at_formatted' => $u->created_at ? $u->created_at->format('M d, Y h:i A') : 'N/A',
                'initials' => strtoupper(substr($u->name, 0, 2)),
            ];
        });

        // Summary counts
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $assistantCount = User::where('role', 'assistant')->count();

        return response()->json([
            'success' => true,
            'data' => $formattedUsers,
            'stats' => [
                'total' => $totalUsers,
                'admins' => $adminCount,
                'assistants' => $assistantCount,
                'filtered_count' => $formattedUsers->count(),
            ],
        ]);
    }

    /**
     * Store a newly created user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'assistant'])],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters long.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => strtolower(trim($validated['username'])),
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Account for {$user->name} ({$user->role}) has been successfully created!",
            'user' => $user,
        ]);
    }

    /**
     * Show a single user record for editing.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_current_user' => ($user->id === Auth::id()),
            ],
        ]);
    }

    /**
     * Update the specified user account.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'assistant'])],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'username.unique' => 'This username is already taken by another account.',
            'email.unique' => 'This email address is already registered to another account.',
            'password.confirmed' => 'New password confirmation does not match.',
            'password.min' => 'New password must be at least 6 characters long.',
        ]);

        // Safety check: Prevent currently logged in user from demoting themselves if they are the only admin
        if ($user->id === Auth::id() && $user->role === 'admin' && $validated['role'] === 'assistant') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot change your own role to Assistant because you are the only Administrator.',
                ], 422);
            }
        }

        $user->name = trim($validated['name']);
        $user->username = strtolower(trim($validated['username']));
        $user->email = strtolower(trim($validated['email']));
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Account {$user->name} has been updated successfully!",
            'user' => $user,
        ]);
    }

    /**
     * Delete the specified user account.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Safety Check 1: Cannot delete currently authenticated user
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own logged-in account.',
            ], 422);
        }

        // Safety Check 2: Cannot delete the last remaining administrator
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this account. There must be at least one Administrator account.',
                ], 422);
            }
        }

        $userName = $user->name;
        $userRole = $user->role;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "Account {$userName} ({$userRole}) has been deleted successfully.",
        ]);
    }
}
