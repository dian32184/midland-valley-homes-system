<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Capture authenticated data-changing actions for audit purposes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldLog($request)) {
            return $response;
        }

        $user = $request->user();
        if (! $user) {
            return $response;
        }

        try {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => $this->buildActionLabel($request),
                'http_method' => $request->method(),
                'route_name' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'target_type' => $this->extractTargetType($request),
                'target_id' => $this->extractTargetId($request),
                'status_code' => $response->getStatusCode(),
                'payload' => $this->sanitizePayload($request),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);
        } catch (QueryException) {
            // Ignore audit log failures to avoid blocking user actions.
        }

        return $response;
    }

    private function shouldLog(Request $request): bool
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        $routeName = (string) ($request->route()?->getName() ?? '');
        if (str_starts_with($routeName, 'audit-logs.')) {
            return false;
        }

        return true;
    }

    private function buildActionLabel(Request $request): string
    {
        $routeName = (string) ($request->route()?->getName() ?? 'unknown');

        return strtolower($request->method()).':'.$routeName;
    }

    private function extractTargetType(Request $request): ?string
    {
        $route = $request->route();
        if (! $route) {
            return null;
        }

        $params = $route->parametersWithoutNulls();
        foreach ($params as $value) {
            if (is_object($value)) {
                return class_basename($value);
            }
        }

        return null;
    }

    private function extractTargetId(Request $request): ?string
    {
        $route = $request->route();
        if (! $route) {
            return null;
        }

        $params = $route->parametersWithoutNulls();
        foreach ($params as $value) {
            if (is_object($value) && method_exists($value, 'getKey')) {
                return (string) $value->getKey();
            }

            if (is_scalar($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    private function sanitizePayload(Request $request): array
    {
        return collect($request->except([
            '_token',
            '_method',
            'password',
            'password_confirmation',
            'current_password',
        ]))
            ->map(function ($value) {
                if (is_array($value)) {
                    return json_encode($value);
                }

                return is_scalar($value) || is_null($value) ? $value : (string) $value;
            })
            ->toArray();
    }
}
