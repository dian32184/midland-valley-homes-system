<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Restrict access to one or more roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        $user = $request->user();
        $storedRole = strtolower(trim((string) ($user?->role ?? '')));
        $email = strtolower(trim((string) ($user?->email ?? '')));
        $userRole = $storedRole;

        // Fallback role detection for legacy users without a clean role value.
        if ($userRole === '' || ! in_array($userRole, ['admin', 'manager', 'marketing', 'documentation'], true)) {
            if (str_contains($email, 'documentation@') || str_contains($email, 'docs@')) {
                $userRole = 'documentation';
            } elseif (str_contains($email, 'manager@')) {
                $userRole = 'manager';
            } elseif (str_contains($email, 'admin@')) {
                $userRole = 'admin';
            } else {
                $userRole = 'marketing';
            }
        }

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($userRole, $roles, true)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
