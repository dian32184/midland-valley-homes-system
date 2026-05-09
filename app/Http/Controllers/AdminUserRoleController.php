<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserRoleController extends Controller
{
    private const ROLES = ['admin', 'manager', 'marketing', 'documentation'];

    public function index()
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roleCounts = [];
        foreach (self::ROLES as $role) {
            $roleCounts[$role] = User::where('role', $role)->count();
        }

        return view('admin.user-roles.index', compact('users', 'roleCounts'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        $newRole = $validated['role'];

        if ($user->role === 'admin' && $newRole !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->route('user-roles.index')
                    ->withErrors(['role' => 'Assign another administrator before removing admin access from this account.']);
            }
        }

        $user->update(['role' => $newRole]);

        return redirect()
            ->route('user-roles.index')
            ->with('status', 'User role updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        $currentUser = $request->user();

        if ($currentUser && $currentUser->id === $user->id) {
            return redirect()
                ->route('user-roles.index')
                ->withErrors(['user' => 'You cannot delete your own account.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()
                ->route('user-roles.index')
                ->withErrors(['user' => 'Assign another administrator before deleting the last admin account.']);
        }

        $user->delete();

        return redirect()
            ->route('user-roles.index')
            ->with('status', 'User deleted successfully.');
    }
}
