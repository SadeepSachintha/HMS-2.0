<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        // Isolate actively registered users explicitly holding the 'Guest' role.
        // Include their historical reservations for dynamic profile analytics.
        $customers = User::whereHas('role', function ($query) {
            $query->where('slug', 'guest');
        })->withCount('reservations')->latest()->get();

        return view('customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // Enforce boundary logic: Cannot view an Admin account through CRM scopes.
        if ($customer->role->slug !== 'guest') {
            abort(403, 'Entity mapping error: Target user is administrative.');
        }

        $customer->load('reservations.room.hotel');

        return view('customers.show', compact('customer'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
        ]);

        $guestRole = Role::where('slug', 'guest')->firstOrFail();

        // Silent creation paradigm: securely auto-generate a 16-character password
        // to bootstrap the entity. Guests can trigger standard password-reset protocols later.
        $validated['password'] = Hash::make(Str::random(16));
        $validated['role_id'] = $guestRole->id;
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('customers.index')->with('success', 'Walk-In Guest registered globally. Prepared for immediate reservation assignments.');
    }

    // Explicitly omitting the 'destroy' function to satisfy data preservation paradigms.
}
