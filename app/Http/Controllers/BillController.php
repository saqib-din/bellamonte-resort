<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Bill::query()->select('bills.*');

            return DataTables::eloquent($query)
                ->addColumn('invoice_number', fn($b) => '<strong class="text-primary">' . e($b->invoice_number) . '</strong>')
                ->addColumn(
                    'guest',
                    fn($b) =>
                    '<h6 class="mb-0">' . e($b->guest_name) . '</h6>' .
                        '<small class="text-muted">' . e($b->guest_phone) . '</small>'
                )
                ->addColumn(
                    'room',
                    fn($b) =>
                    $b->room_number
                        ? '<span class="badge bg-light-primary">Room ' . e($b->room_number) . '</span>' .
                        '<small class="text-muted d-block">' . e($b->room_type) . '</small>'
                        : '<span class="text-muted">—</span>'
                )
                ->editColumn('check_in', fn($b) => $b->check_in ? $b->check_in->format('d M Y') : '—')
                ->editColumn('check_out', fn($b) => $b->check_out ? $b->check_out->format('d M Y') : '—')
                ->addColumn('method', fn($b) => '<small>' . e($b->payment_method) . '</small>')
                ->addColumn(
                    'total',
                    fn($b) =>
                    '<strong class="text-muted">₨ ' . number_format($b->total_amount) . '</strong>'
                )
                ->addColumn(
                    'status_badge',
                    fn($b) =>
                    '<span class="badge ' . $b->getStatusBadgeClass() . '">' . e($b->status) . '</span>'
                )
                ->addColumn('action', function ($b) {
                    $html  = '<a href="' . route('billing.print', $b) . '" target="_blank" class="avtar avtar-xs btn-link-secondary" title="Print"><i class="ti ti-printer f-18"></i></a>';
                    $html .= '<a href="' . route('billing.show', $b) . '" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></a>';
                    $html .= '<a href="' . route('billing.edit', $b) . '" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></a>';
                    $html .= '<a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para" data-id="' . $b->id . '" title="Delete"><i class="ti ti-trash f-18"></i></a>';
                    $html .= '<form id="delete-form-' . $b->id . '" action="' . route('billing.destroy', $b) . '" method="POST" style="display:none;">'
                        . csrf_field() . method_field('DELETE') . '</form>';
                    return $html;
                })
                ->rawColumns(['invoice_number', 'guest', 'room', 'method', 'total', 'status_badge', 'action'])
                ->make(true);
        }

        return view('pages.admin-side.billing.index');
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
            'guest_name'      => 'required|string|max:100',
            'father_name'     => 'nullable|string|max:100',
            'room_charges'    => 'required|numeric|min:0',
            'extra_charges'   => 'nullable|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
            'tax_percent'     => 'nullable|numeric|min:0|max:100',
            'amount_paid'     => 'nullable|numeric|min:0',
            'payment_method'  => 'required',
            'issue_date'      => 'required|date',

            // ── Vehicle ──
            'has_vehicle'     => 'nullable|boolean',
            'vehicle_number'  => 'nullable|string|max:30',
            'vehicle_type'    => 'nullable|in:Car,SUV,Van,Bike,Jeep,Other',
            'vehicle_model'   => 'nullable|string|max:50',
            'vehicle_color'   => 'nullable|string|max:30',
            'driver_name'     => 'nullable|string|max:100',
            'parking_charges' => 'nullable|numeric|min:0',
        ]);

        $bill = new Bill();

        $bill->invoice_number = $this->generateInvoiceNumber();
        $bill->booking_id     = $request->booking_id;
        $bill->customer_id    = $request->customer_id;
        $bill->guest_name     = $request->guest_name;
        $bill->father_name    = $request->father_name;
        $bill->guest_phone    = $request->guest_phone;
        $bill->room_number    = $request->room_number;
        $bill->room_type      = $request->room_type;
        $bill->check_in       = $request->check_in;
        $bill->check_out      = $request->check_out;
        $bill->nights         = $request->nights ?? 1;

        // ── Vehicle Details ──
        $hasVehicle = $request->boolean('has_vehicle');
        $bill->has_vehicle     = $hasVehicle;
        $bill->vehicle_number  = $hasVehicle ? $request->vehicle_number : null;
        $bill->vehicle_type    = $hasVehicle ? $request->vehicle_type : null;
        $bill->vehicle_model   = $hasVehicle ? $request->vehicle_model : null;
        $bill->vehicle_color   = $hasVehicle ? $request->vehicle_color : null;
        $bill->driver_name     = $hasVehicle ? $request->driver_name : null;
        $bill->parking_charges = $hasVehicle ? ($request->parking_charges ?? 0) : 0;

        $bill->room_charges   = $request->room_charges;
        $bill->extra_charges  = $request->extra_charges ?? 0;
        $bill->discount       = $request->discount ?? 0;
        $bill->tax_percent    = $request->tax_percent ?? 0;
        $bill->amount_paid    = $request->amount_paid ?? 0;

        $bill->payment_method = $request->payment_method;
        $bill->issue_date     = $request->issue_date;

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
            'guest_name'      => 'required|string|max:100',
            'father_name'     => 'nullable|string|max:100',
            'room_charges'    => 'required|numeric|min:0',
            'extra_charges'   => 'nullable|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
            'tax_percent'     => 'nullable|numeric|min:0|max:100',
            'amount_paid'     => 'nullable|numeric|min:0',
            'payment_method'  => 'required',
            'issue_date'      => 'required|date',

            // ── Vehicle ──
            'has_vehicle'     => 'nullable|boolean',
            'vehicle_number'  => 'nullable|string|max:30',
            'vehicle_type'    => 'nullable|in:Car,SUV,Van,Bike,Jeep,Other',
            'vehicle_model'   => 'nullable|string|max:50',
            'vehicle_color'   => 'nullable|string|max:30',
            'driver_name'     => 'nullable|string|max:100',
            'parking_charges' => 'nullable|numeric|min:0',
        ]);

        $hasVehicle = $request->boolean('has_vehicle');

        $bill->booking_id     = $request->booking_id;
        $bill->customer_id    = $request->customer_id;
        $bill->guest_name     = $request->guest_name;
        $bill->father_name    = $request->father_name;
        $bill->guest_phone    = $request->guest_phone;
        $bill->room_number    = $request->room_number;
        $bill->room_type      = $request->room_type;
        $bill->check_in       = $request->check_in;
        $bill->check_out      = $request->check_out;
        $bill->nights         = $request->nights ?? 1;

        $bill->has_vehicle     = $hasVehicle;
        $bill->vehicle_number  = $hasVehicle ? $request->vehicle_number : null;
        $bill->vehicle_type    = $hasVehicle ? $request->vehicle_type : null;
        $bill->vehicle_model   = $hasVehicle ? $request->vehicle_model : null;
        $bill->vehicle_color   = $hasVehicle ? $request->vehicle_color : null;
        $bill->driver_name     = $hasVehicle ? $request->driver_name : null;
        $bill->parking_charges = $hasVehicle ? ($request->parking_charges ?? 0) : 0;

        $bill->room_charges   = $request->room_charges;
        $bill->extra_charges  = $request->extra_charges ?? 0;
        $bill->discount       = $request->discount ?? 0;
        $bill->tax_percent    = $request->tax_percent ?? 0;
        $bill->amount_paid    = $request->amount_paid ?? 0;
        $bill->payment_method = $request->payment_method;
        $bill->issue_date     = $request->issue_date;

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

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Y') . '-';

        $last = Bill::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
