<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['user', 'hotel'])->latest()->get();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        // Allow creating staff from any user currently registered
        $users = User::all();
        return view('staff.create', compact('hotels', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:staff,user_id',
            'hotel_id' => 'required|exists:hotels,id',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'shift' => 'required|in:morning,evening,night',
        ]);

        Staff::create($validated);

        return redirect()->route('staff.index')->with('success', 'Staff member and scheduling active.');
    }

    public function edit(Staff $staff)
    {
        $hotels = Hotel::all();
        return view('staff.edit', compact('staff', 'hotels'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'shift' => 'required|in:morning,evening,night',
        ]);

        $staff->update($validated);

        return redirect()->route('staff.index')->with('success', 'Staff parameters updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff clearance revoked successfully.');
    }
}
