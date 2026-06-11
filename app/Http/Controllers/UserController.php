<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $all = User::latest()->get();

        $users = $all->map(fn ($u) => [
            'id'           => $u->id,
            'uuid'         => $u->uuid,
            'name'         => $u->name,
            'email'        => $u->email,
            'phone'        => $u->phone,
            'role'         => $u->role,
            'role_label'   => $u->getRoleLabel(),
            'role_badge'   => $u->getRoleBadgeClass(),
            'status'       => $u->status,
            'status_badge' => $u->getStatusBadgeClass(),
            'created_at'   => optional($u->created_at)->format('d M Y'),
            'is_me'        => $u->id === Auth::id(),
            'is_admin'     => $u->isAdmin(),
        ]);

        $counts = [
            'total'        => $all->count(),
            'admin'        => $all->where('role', 'admin')->count(),
            'manager'      => $all->where('role', 'manager')->count(),
            'receptionist' => $all->where('role', 'receptionist')->count(),
            'accountant'   => $all->where('role', 'accountant')->count(),
            'staff'        => $all->where('role', 'staff')->count(),
        ];

        return Inertia::render('Users/Index', [
            'users'  => $users,
            'counts' => $counts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:manager,receptionist,accountant,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
        ]);

        // Another admin account cannot be created!
        if ($request->role === 'admin') {
            return redirect()->back()
                ->with('error', '❌ Another admin account cannot be created!');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'status'   => $request->status,
        ]);

        return redirect()->route('users.index')
            ->with('success', $request->name . ' Account has been created successfully!');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id'     => $user->id,
                'uuid'   => $user->uuid,
                'name'   => $user->name,
                'email'  => $user->email,
                'phone'  => $user->phone,
                'role'   => $user->role,
                'status' => $user->status,
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'     => 'required|in:admin,manager,receptionist,accountant,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
        ]);

        if (!$user->isAdmin() && $request->role === 'admin') {
            return redirect()->back()
                ->with('error', '❌ No one can be assigned the Admin role!');
        }

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'phone'  => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', $user->name . ' Updated successfully!');
    }

    public function destroy(User $user)
    {
        // Admin account cannot be deleted!
        if ($user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', '❌ Admin account cannot be deleted!');
        }

        // You cannot delete your own account!
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', '❌ You cannot delete your own account!');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', $name . ' Account has been deleted successfully!');
    }

    public function toggleStatus(User $user)
    {
        // Admin status cannot be changed!
        if ($user->isAdmin()) {
            return redirect()->back()
                ->with('error', '❌ Admin status cannot be changed!!');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()->back()
            ->with('success', $user->name . ' Status has been updated successfully!');
    }
}
