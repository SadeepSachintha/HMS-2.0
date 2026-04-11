<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 *
 * Provides an Absolute Admin Governance engine allowing highest-tier administrators
 * to mandate system hierarchies, supervise core user identity arrays, and enforce
 * emergency credential modifications via anti-lockout protocols.
 */
class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,suspended',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User completely registered and mapped to requested tier.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Process an entity update request.
     * Contains built-in Anti-Lockout Validation Layer to prevent self-demotion.
     *
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Anti-Lockout Validation Layer
        if (Auth::id() === $user->id && $request->role_id != $user->role_id) {
            return back()->with('error', 'Anti-Lockout Protocol: You cannot demote your own administrator clearance.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,suspended',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User identity updated successfully.');
    }

    /**
     * Terminate and excise a user from the system.
     * Contains built-in Anti-Lockout Validation Layer to prevent self-eradication.
     *
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        // Anti-Lockout Validation Layer
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anti-Lockout Protocol: You cannot eradicate your active system session.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User successfully excised from the system.');
    }

    /**
     * Forcefully overrides the authentication credentials of an entity.
     * Often utilized when an entity is compromised or locked out.
     *
     * @return RedirectResponse
     */
    public function forcePasswordReset(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'User credentials forcefully overridden.');
    }
}
