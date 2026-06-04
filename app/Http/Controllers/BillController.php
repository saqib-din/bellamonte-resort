<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['booking', 'customer'])->latest()->get();
        return view('pages.admin-side.billing.index', compact('bills'));
    }

    public function create()
    {
        $bookings  = Booking::with(['room', 'customer'])
            ->whereDoesntHave('bill')
            ->latest()->get();
        $customers = Customer::orderBy('name')->get();
        return view('pages.admin-side.billing.create', compact('bookings', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:100',
            'room_charges'   => 'required|numeric|min:0',
            'extra_charges'  => 'nullable|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax_percent'    => 'nullable|numeric|min:0|max:100',
            'amount_paid'    => 'nullable|numeric|min:0',
            'payment_method' => 'required',
            'issue_date'     => 'required|date',
        ]);

        $bill = new Bill();

        $bill->invoice_number = 'INV-' . date('Y') . '-' . str_pad(Bill::count() + 1, 4, '0', STR_PAD_LEFT);
        $bill->booking_id     = $request->booking_id;
        $bill->customer_id    = $request->customer_id;
        $bill->guest_name     = $request->guest_name;
        $bill->guest_phone    = $request->guest_phone;
        $bill->room_number    = $request->room_number;
        $bill->room_type      = $request->room_type;
        $bill->check_in       = $request->check_in;
        $bill->check_out      = $request->check_out;
        $bill->nights         = $request->nights ?? 1;

        $bill->room_charges   = $request->room_charges;
        $bill->extra_charges  = $request->extra_charges ?? 0;
        $bill->discount       = $request->discount ?? 0;
        $bill->tax_percent    = $request->tax_percent ?? 0;
        $bill->amount_paid    = $request->amount_paid ?? 0;

        $bill->payment_method = $request->payment_method;
        $bill->issue_date     = $request->issue_date;

        // calculate correctly (NO static call)
        $bill->calculate();

        $bill->save();

        return redirect()->route('billing.index')
            ->with('success', 'Invoice added successfully');
    }

    public function show(Bill $bill)
    {
        $bill->load(['booking', 'customer']);
        return view('pages.admin-side.billing.show', compact('bill'));
    }

    public function print(Bill $bill)
    {
        $bill->load(['booking', 'customer']);
        return view('pages.admin-side.billing.print', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $bookings  = Booking::with(['room', 'customer'])->latest()->get();
        $customers = Customer::orderBy('name')->get();
        return view('pages.admin-side.billing.edit', compact('bill', 'bookings', 'customers'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'guest_name'     => 'required|string|max:100',
            'room_charges'   => 'required|numeric|min:0',
            'extra_charges'  => 'nullable|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax_percent'    => 'nullable|numeric|min:0|max:100',
            'amount_paid'    => 'nullable|numeric|min:0',
            'payment_method' => 'required',
            'issue_date'     => 'required|date',
        ]);

        $bill->update([
            'booking_id'     => $request->booking_id,
            'customer_id'    => $request->customer_id,
            'guest_name'     => $request->guest_name,
            'guest_phone'    => $request->guest_phone,
            'room_number'    => $request->room_number,
            'room_type'      => $request->room_type,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'nights'         => $request->nights ?? 1,
            'room_charges'   => $request->room_charges,
            'extra_charges'  => $request->extra_charges ?? 0,
            'discount'       => $request->discount ?? 0,
            'tax_percent'    => $request->tax_percent ?? 0,
            'amount_paid'    => $request->amount_paid ?? 0,
            'payment_method' => $request->payment_method,
            'issue_date'     => $request->issue_date,
        ]);

        // reload model values then calculate
        $bill->refresh();
        $bill->calculate();
        $bill->save();

        return redirect()->route('billing.index')
            ->with('success', 'Invoice updated successfully');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('billing.index')
            ->with('success', 'Invoice deleted successfully!');
    }
}
