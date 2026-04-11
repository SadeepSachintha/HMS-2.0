<?php

namespace App\Http\Controllers;

use App\Models\User;

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

    // Explicitly omitting the 'destroy' function to satisfy data preservation paradigms.
}
