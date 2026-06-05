<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // ---- Aggregate stats: fast indexed COUNT queries (collection load NAHI) ----
        $stats = [
            'total'       => Customer::count(),
            'active'      => Customer::where('status', 'Active')->count(),
            'blacklisted' => Customer::where('status', 'Blacklisted')->count(),
            'bookings'    => Booking::count(),
        ];

        // ---- AJAX request → yajra DataTables server-side response ----
        if ($request->ajax()) {
            $query = Customer::query()->select([
                'id',
                'name',
                'email',
                'image',
                'cnic',
                'phone',
                'city',
                'nationality',
                'gender',
                'status',
            ]);

            return datatables()->eloquent($query)
                ->addColumn('customer', fn($c) =>
                view('pages.admin-side.customers.partials.customer-cell', ['c' => $c])->render())
                ->addColumn('location', fn($c) =>
                view('pages.admin-side.customers.partials.location-cell', ['c' => $c])->render())
                ->editColumn('gender', fn($c) => $c->gender ?? '—')
                ->addColumn('status_badge', fn($c) =>
                '<span class="badge ' . $c->getStatusBadgeClass() . '">' . e($c->status) . '</span>')
                ->addColumn('action', fn($c) =>
                view('pages.admin-side.customers.partials.actions', ['c' => $c])->render())
                ->rawColumns(['customer', 'location', 'status_badge', 'action'])
                ->make(true);
        }

        return view('pages.admin-side.customers.index', compact('stats'));
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
            $file          = $request->file('image');
            $filename      = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['image'] = $filename;
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
                $old = public_path('uploads/customers/' . $customer->image);
                if (file_exists($old)) unlink($old);
            }
            $file          = $request->file('image');
            $filename      = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $data['image'] = $filename;
        }

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->image) {
            $old = public_path('uploads/customers/' . $customer->image);
            if (file_exists($old)) unlink($old);
        }
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}
