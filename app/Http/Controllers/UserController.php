<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('pages.admin-side.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.admin-side.users.create');
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
        return view('pages.admin-side.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'     => 'required|in:manager,receptionist,accountant,staff',
            'phone'    => 'nullable|string|max:20',
            'status'   => 'required|in:active,inactive',
        ]);

        // Admin role cannot be changed!
        if ($user->isAdmin()) {
            return redirect()->back()
                ->with('error', '❌ Admin role cannot be changed!');
        }

        // No one can be assigned the Admin role!
        if ($request->role === 'admin') {
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