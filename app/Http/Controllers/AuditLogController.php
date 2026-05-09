<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('audit_logs')) {
            $logs = new LengthAwarePaginator(
                items: [],
                total: 0,
                perPage: 20,
                currentPage: LengthAwarePaginator::resolveCurrentPage(),
                options: [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('audit-logs.index', compact('logs'))
                ->with('warning', 'Audit log table is missing. Run database migration first.');
        }

        $query = AuditLog::with('user')->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($builder) use ($search): void {
                $builder->where('action', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('target_type', 'like', "%{$search}%")
                    ->orWhere('target_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userBuilder) use ($search): void {
                        $userBuilder->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('method')) {
            $query->where('http_method', strtoupper((string) $request->input('method')));
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('audit-logs.index', compact('logs'));
    }
}
