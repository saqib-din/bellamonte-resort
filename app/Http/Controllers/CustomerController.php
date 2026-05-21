<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('bookings')->latest()->get();
        return view('pages.admin-side.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('pages.admin-side.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'cnic'        => 'required|unique:customers,cnic',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:100',
            'city'        => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:50',
            'gender'      => 'nullable|in:Male,Female,Other',
            'dob'         => 'nullable|date',
            'address'     => 'nullable|string',
            'status'      => 'required|in:Active,Blacklisted',
            'notes'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('customers', 'public');
        }

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load('bookings.room');
        return view('pages.admin-side.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('pages.admin-side.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'cnic'        => 'required|unique:customers,cnic,' . $customer->id,
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:100',
            'city'        => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:50',
            'gender'      => 'nullable|in:Male,Female,Other',
            'dob'         => 'nullable|date',
            'address'     => 'nullable|string',
            'status'      => 'required|in:Active,Blacklisted',
            'notes'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }
            $data['image'] = $request->file('image')->store('customers', 'public');
        }

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}
