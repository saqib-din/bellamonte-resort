<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class CustomerController extends Controller
{
    private const SORTABLE = ['id', 'name', 'cnic', 'phone', 'city', 'gender', 'status'];

    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('cnic', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('city', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $sort = in_array($request->sort, self::SORTABLE, true) ? $request->sort : 'id';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $perPage = (int) $request->input('per_page', 15);

        $customers = $query->paginate($perPage)->withQueryString()->through(fn ($c) => [
            'id'          => $c->id,
            'uuid'        => $c->uuid,
            'name'        => $c->name,
            'father_name' => $c->father_name,
            'email'       => $c->email,
            'cnic'        => $c->cnic,
            'phone'       => $c->phone,
            'city'        => $c->city,
            'nationality' => $c->nationality,
            'gender'      => $c->gender,
            'status'      => $c->status,
            'statusBadge' => $c->getStatusBadgeClass(),
            'image'       => $c->image ? asset('uploads/customers/' . $c->image) : null,
        ]);

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters'   => [
                'search'   => $request->search,
                'sort'     => $sort,
                'dir'      => $dir,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'cnic'        => 'required|string|max:20|unique:customers,cnic',
            'phone'       => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{7,20}$/'],
            'email'       => ['nullable', 'email', 'max:100', 'regex:/^.+@.+\..+$/'],
            'city'        => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:50',
            'gender'      => 'nullable|in:Male,Female,Other',
            'dob'         => 'nullable|date',
            'address'     => 'nullable|string',
            'status'      => 'required|in:Active,Blacklisted',
            'notes'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'phone.regex' => 'Please enter a valid phone number — digits and + - ( ) only.',
            'email.regex' => 'Please enter a valid email address, e.g. name@example.com.',
            'email.email' => 'Please enter a valid email address, e.g. name@example.com.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file          = $request->file('image');
            $filename      = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['image'] = $filename;
        }

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load('bookings.room');

        return Inertia::render('Customers/Show', [
            'customer' => [
                'uuid'        => $customer->uuid,
                'name'        => $customer->name,
                'father_name' => $customer->father_name,
                'email'       => $customer->email,
                'cnic'        => $customer->cnic,
                'phone'       => $customer->phone,
                'gender'      => $customer->gender,
                'nationality' => $customer->nationality,
                'city'        => $customer->city,
                'address'     => $customer->address,
                'notes'       => $customer->notes,
                'status'      => $customer->status,
                'statusBadge' => $customer->getStatusBadgeClass(),
                'image'       => $customer->image ? asset('uploads/customers/' . $customer->image) : null,
                'totalStays'  => $customer->getTotalStays(),
                'totalSpent'  => $customer->getTotalSpent(),
                'age'         => $customer->getAge(),
                'bookings'    => $customer->bookings->map(fn ($b) => [
                    'uuid'           => $b->uuid ?? $b->id,
                    'booking_number' => $b->booking_number,
                    'room_number'    => $b->room->room_number ?? '—',
                    'room_type'      => $b->room->type ?? '',
                    'check_in'       => optional($b->check_in)->format('d M Y'),
                    'check_out'      => optional($b->check_out)->format('d M Y'),
                    'nights'         => $b->nights,
                    'total_amount'   => $b->total_amount,
                    'status'         => $b->status,
                    'statusBadge'    => method_exists($b, 'getStatusBadgeClass') ? $b->getStatusBadgeClass() : 'bg-light-secondary',
                ]),
            ],
        ]);
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => [
                'uuid'        => $customer->uuid,
                'name'        => $customer->name,
                'father_name' => $customer->father_name,
                'cnic'        => $customer->cnic,
                'phone'       => $customer->phone,
                'email'       => $customer->email,
                'gender'      => $customer->gender,
                'dob'         => optional($customer->dob)->format('Y-m-d'),
                'nationality' => $customer->nationality,
                'city'        => $customer->city,
                'address'     => $customer->address,
                'notes'       => $customer->notes,
                'status'      => $customer->status,
                'image'       => $customer->image ? asset('uploads/customers/' . $customer->image) : null,
            ],
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'cnic'        => 'required|string|max:20|unique:customers,cnic,' . $customer->id,
            'phone'       => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{7,20}$/'],
            'email'       => ['nullable', 'email', 'max:100', 'regex:/^.+@.+\..+$/'],
            'city'        => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:50',
            'gender'      => 'nullable|in:Male,Female,Other',
            'dob'         => 'nullable|date',
            'address'     => 'nullable|string',
            'status'      => 'required|in:Active,Blacklisted',
            'notes'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'phone.regex' => 'Please enter a valid phone number — digits and + - ( ) only.',
            'email.regex' => 'Please enter a valid email address, e.g. name@example.com.',
            'email.email' => 'Please enter a valid email address, e.g. name@example.com.',
        ]);

        $data = $request->except(['image', '_method']);

        if ($request->hasFile('image')) {
            if ($customer->image) {
                $old = public_path('uploads/customers/' . $customer->image);
                if (file_exists($old)) unlink($old);
            }
            $file          = $request->file('image');
            $filename      = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['image'] = $filename;
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->image) {
            $old = public_path('uploads/customers/' . $customer->image);
            if (file_exists($old)) unlink($old);
        }
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
