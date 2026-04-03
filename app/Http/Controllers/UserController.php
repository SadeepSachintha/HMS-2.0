<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Anti-Lockout Validation Layer
        if (Auth::id() === $user->id && $request->role_id != $user->role_id) {
            return back()->with('error', 'Anti-Lockout Protocol: You cannot demote your own administrator clearance.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User identity updated successfully.');
    }

    public function destroy(User $user)
    {
        // Anti-Lockout Validation Layer
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anti-Lockout Protocol: You cannot eradicate your active system session.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User successfully excised from the system.');
    }

    public function forcePasswordReset(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'User credentials forcefully overridden.');
    }
}
