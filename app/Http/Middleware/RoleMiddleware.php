<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please sign in to continue.'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Please sign in to access this page.');
        }

        $user = Auth::user();

        // 2. Flatten and sanitize any passed roles (supports 'admin,assistant' or 'admin', 'assistant')
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $subRole) {
                $trimmed = strtolower(trim($subRole));
                if (!empty($trimmed)) {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // If no specific roles required, allow authenticated user through
        if (empty($allowedRoles)) {
            return $next($request);
        }

        $userRole = strtolower($user->role ?? '');

        // 3. Check if user possesses one of the allowed roles
        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // 4. Handle unauthorized role access gracefully
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have permission to perform this action.'
            ], 403);
        }

        // Redirect user to their own portal with a clear message
        if ($userRole === 'assistant') {
            return redirect()->route('assistant.electoral')->with('error', 'Access restricted: Administrator privileges required.');
        }

        if ($userRole === 'admin') {
            return redirect()->route('admin.electoral')->with('info', 'Redirected to Administrator portal.');
        }

        // Fallback for unknown role
        Auth::logout();
        return redirect()->route('login')->with('error', 'Unauthorized role detected. Please contact your system administrator.');
    }
}
