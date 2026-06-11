<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillController extends Controller
{
    private const SORTABLE = ['invoice_number', 'guest_name', 'total_amount', 'status', 'issue_date'];

    public function index(Request $request)
    {
        $query = Bill::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                    ->orWhere('guest_name', 'like', "%{$s}%")
                    ->orWhere('guest_phone', 'like', "%{$s}%");
            });
        }

        $sort = in_array($request->sort, self::SORTABLE, true) ? $request->sort : 'issue_date';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $perPage = (int) $request->input('per_page', 15);

        $bills = $query->paginate($perPage)->withQueryString()->through(fn ($b) => [
            'uuid'           => $b->uuid,
            'invoice_number' => $b->invoice_number,
            'guest_name'     => $b->guest_name,
            'guest_phone'    => $b->guest_phone,
            'room_number'    => $b->room_number,
            'room_type'      => $b->room_type,
            'check_in'       => optional($b->check_in)->format('d M Y'),
            'check_out'      => optional($b->check_out)->format('d M Y'),
            'payment_method' => $b->payment_method,
            'total_amount'   => $b->total_amount,
            'status'         => $b->status,
            'statusBadge'    => $b->getStatusBadgeClass(),
        ]);

        return Inertia::render('Billing/Index', [
            'bills'   => $bills,
            'filters' => [
                'search'   => $request->search,
                'sort'     => $sort,
                'dir'      => $dir,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Billing/Create', [
            'bookings'  => $this->bookingOptions(Booking::with(['room', 'customer'])->whereDoesntHave('bill')->latest()->get()),
            'customers' => $this->customerOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validateBill($request);

        $bill = new Bill();
        $bill->invoice_number = $this->generateInvoiceNumber();
        $this->fillBill($bill, $request);
        $bill->calculate();
        $bill->save();

        return redirect()->route('billing.index')->with('success', 'Invoice added successfully');
    }

    public function show(Bill $bill)
    {
        $bill->load(['booking', 'customer']);

        return Inertia::render('Billing/Show', [
            'bill' => $this->serializeBill($bill),
        ]);
    }

    public function edit(Bill $bill)
    {
        return Inertia::render('Billing/Edit', [
            'bookings'  => $this->bookingOptions(Booking::with(['room', 'customer'])->latest()->get()),
            'customers' => $this->customerOptions(),
            'bill'      => [
                'uuid'            => $bill->uuid,
                'invoice_number'  => $bill->invoice_number,
                'booking_id'      => $bill->booking_id,
                'customer_id'     => $bill->customer_id,
                'guest_name'      => $bill->guest_name,
                'father_name'     => $bill->father_name,
                'guest_phone'     => $bill->guest_phone,
                'room_number'     => $bill->room_number,
                'room_type'       => $bill->room_type,
                'check_in'        => optional($bill->check_in)->format('Y-m-d'),
                'check_out'       => optional($bill->check_out)->format('Y-m-d'),
                'nights'          => $bill->nights,
                'has_vehicle'     => (bool) $bill->has_vehicle,
                'vehicle_number'  => $bill->vehicle_number,
                'vehicle_type'    => $bill->vehicle_type,
                'vehicle_model'   => $bill->vehicle_model,
                'vehicle_color'   => $bill->vehicle_color,
                'driver_name'     => $bill->driver_name,
                'parking_charges' => $bill->parking_charges,
                'room_charges'    => $bill->room_charges,
                'extra_charges'   => $bill->extra_charges,
                'discount'        => $bill->discount,
                'tax_percent'     => $bill->tax_percent,
                'amount_paid'     => $bill->amount_paid,
                'payment_method'  => $bill->payment_method,
                'issue_date'      => optional($bill->issue_date)->format('Y-m-d'),
                'notes'           => $bill->notes,
            ],
        ]);
    }

    public function update(Request $request, Bill $bill)
    {
        $this->validateBill($request);

        $this->fillBill($bill, $request);
        $bill->calculate();
        $bill->save();

        return redirect()->route('billing.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();

        return redirect()->route('billing.index')->with('success', 'Invoice deleted successfully!');
    }

    public function print(Bill $bill)
    {
        $bill->load(['booking', 'customer']);

        return view('pages.admin-side.billing.print', compact('bill'));
    }

    // ── Helpers ──────────────────────────────────────
    private function validateBill(Request $request): void
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
            'has_vehicle'     => 'nullable|boolean',
            'vehicle_number'  => 'nullable|string|max:30',
            'vehicle_type'    => 'nullable|in:Car,SUV,Van,Bike,Jeep,Other',
            'vehicle_model'   => 'nullable|string|max:50',
            'vehicle_color'   => 'nullable|string|max:30',
            'driver_name'     => 'nullable|string|max:100',
            'parking_charges' => 'nullable|numeric|min:0',
        ]);
    }

    private function fillBill(Bill $bill, Request $request): void
    {
        $hasVehicle = $request->boolean('has_vehicle');

        $bill->booking_id  = $request->booking_id ?: null;
        $bill->customer_id = $request->customer_id ?: null;
        $bill->guest_name  = $request->guest_name;
        $bill->father_name = $request->father_name;
        $bill->guest_phone = $request->guest_phone;
        $bill->room_number = $request->room_number;
        $bill->room_type   = $request->room_type;
        $bill->check_in    = $request->check_in;
        $bill->check_out   = $request->check_out;
        $bill->nights      = $request->nights ?? 1;

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
        $bill->notes          = $request->notes;
    }

    private function serializeBill(Bill $b): array
    {
        return [
            'uuid'            => $b->uuid,
            'invoice_number'  => $b->invoice_number,
            'guest_name'      => $b->guest_name,
            'father_name'     => $b->father_name,
            'guest_phone'     => $b->guest_phone,
            'room_number'     => $b->room_number,
            'room_type'       => $b->room_type,
            'check_in'        => optional($b->check_in)->format('d M Y'),
            'check_out'       => optional($b->check_out)->format('d M Y'),
            'nights'          => $b->nights,
            'has_vehicle'     => (bool) $b->has_vehicle,
            'vehicle_number'  => $b->vehicle_number,
            'vehicle_type'    => $b->vehicle_type,
            'vehicle_model'   => $b->vehicle_model,
            'vehicle_color'   => $b->vehicle_color,
            'driver_name'     => $b->driver_name,
            'parking_charges' => $b->parking_charges,
            'room_charges'    => $b->room_charges,
            'extra_charges'   => $b->extra_charges,
            'discount'        => $b->discount,
            'tax_percent'     => $b->tax_percent,
            'tax_amount'      => $b->tax_amount,
            'total_amount'    => $b->total_amount,
            'amount_paid'     => $b->amount_paid,
            'balance_due'     => $b->balance_due,
            'payment_method'  => $b->payment_method,
            'status'          => $b->status,
            'statusBadge'     => $b->getStatusBadgeClass(),
            'notes'           => $b->notes,
            'issue_date'      => optional($b->issue_date)->format('d M Y'),
            'created_at'      => optional($b->created_at)->format('d M Y, h:i A'),
            'customer'        => $b->customer ? [
                'name'  => $b->customer->name,
                'phone' => $b->customer->phone,
                'cnic'  => $b->customer->cnic,
            ] : null,
        ];
    }

    private function bookingOptions($bookings): array
    {
        return $bookings->map(fn ($b) => [
            'id'          => $b->id,
            'label'       => $b->booking_number . ' — ' . $b->guest_name . ' | Room ' . ($b->room->room_number ?? '?')
                . ' | ' . optional($b->check_in)->format('d M') . ' → ' . optional($b->check_out)->format('d M Y'),
            'guest_name'  => $b->guest_name,
            'father_name' => $b->father_name,
            'guest_phone' => $b->guest_phone,
            'room_number' => $b->room->room_number ?? '',
            'room_type'   => $b->room->type ?? '',
            'check_in'    => optional($b->check_in)->format('Y-m-d'),
            'check_out'   => optional($b->check_out)->format('Y-m-d'),
            'nights'      => $b->nights,
            'amount'      => $b->total_amount,
            'customer_id' => $b->customer_id,
        ])->all();
    }

    private function customerOptions(): array
    {
        return Customer::orderBy('name')->get()->map(fn ($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'phone' => $c->phone,
        ])->all();
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Y') . '-';
        $last   = Bill::where('invoice_number', 'like', $prefix . '%')->orderByDesc('id')->value('invoice_number');
        $next   = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
