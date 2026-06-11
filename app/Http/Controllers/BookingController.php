<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    private const SORTABLE = ['id', 'booking_number', 'guest_name', 'check_in', 'check_out', 'total_amount', 'status'];

    public function index(Request $request)
    {
        $query = Booking::with('room');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_number', 'like', "%{$s}%")
                    ->orWhere('guest_name', 'like', "%{$s}%")
                    ->orWhere('guest_phone', 'like', "%{$s}%");
            });
        }

        $sort = in_array($request->sort, self::SORTABLE, true) ? $request->sort : 'id';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $perPage = (int) $request->input('per_page', 15);

        $bookings = $query->paginate($perPage)->withQueryString()->through(fn ($b) => [
            'uuid'           => $b->uuid,
            'booking_number' => $b->booking_number,
            'guest_name'     => $b->guest_name,
            'guest_phone'    => $b->guest_phone,
            'room_number'    => $b->room->room_number ?? '—',
            'room_type'      => $b->room->type ?? '',
            'check_in'       => optional($b->check_in)->format('d M Y'),
            'check_out'      => optional($b->check_out)->format('d M Y'),
            'total_amount'   => $b->total_amount,
            'status'         => $b->status,
            'statusBadge'    => $b->getStatusBadgeClass(),
        ]);

        return Inertia::render('Bookings/Index', [
            'bookings' => $bookings,
            'filters'  => [
                'search'   => $request->search,
                'sort'     => $sort,
                'dir'      => $dir,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Bookings/Create', [
            'rooms'     => $this->roomOptions(Room::where('status', 'Available')->orderBy('room_number')->get()),
            'customers' => $this->customerOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'customer_id'      => 'required|exists:customers,id',
            'guest_name'       => 'required|string|max:100',
            'father_name'      => 'nullable|string|max:100',
            'guest_phone'      => 'required|string|max:20',
            'guest_cnic'       => 'nullable|string|max:20',
            'guest_email'      => 'nullable|email|max:100',
            'adults'           => 'required|integer|min:1',
            'children'         => 'nullable|integer|min:0',
            'check_in'         => 'required|date',
            'check_out'        => 'required|date|after:check_in',
            'payment_method'   => 'required',
            'payment_status'   => 'required',
            'status'           => 'required',
            'special_requests' => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        $room     = Room::findOrFail($request->room_id);
        $checkin  = Carbon::parse($request->check_in);
        $checkout = Carbon::parse($request->check_out);
        $nights   = $checkin->diffInDays($checkout);
        $total    = $nights * $room->price_per_night;

        $booking = Booking::create([
            'booking_number'   => Booking::generateBookingNumber(),
            'room_id'          => $request->room_id,
            'customer_id'      => $request->customer_id,
            'guest_name'       => $request->guest_name,
            'father_name'      => $request->father_name,
            'guest_phone'      => $request->guest_phone,
            'guest_cnic'       => $request->guest_cnic,
            'guest_email'      => $request->guest_email,
            'adults'           => $request->adults,
            'children'         => $request->children ?? 0,
            'check_in'         => $request->check_in,
            'check_out'        => $request->check_out,
            'nights'           => $nights,
            'room_price'       => $room->price_per_night,
            'total_amount'     => $total,
            'payment_status'   => $request->payment_status,
            'payment_method'   => $request->payment_method,
            'status'           => $request->status,
            'special_requests' => $request->special_requests,
            'notes'            => $request->notes,
        ]);

        if (in_array($request->status, ['Confirmed', 'Checked In'])) {
            $room->update(['status' => 'Occupied']);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been confirmed successfully! ' . $booking->booking_number . ' ✅');
    }

    public function show(Booking $booking)
    {
        $booking->load(['room', 'customer']);

        return Inertia::render('Bookings/Show', [
            'booking' => [
                'uuid'             => $booking->uuid,
                'booking_number'   => $booking->booking_number,
                'guest_name'       => $booking->guest_name,
                'father_name'      => $booking->father_name,
                'guest_phone'      => $booking->guest_phone,
                'guest_cnic'       => $booking->guest_cnic,
                'guest_email'      => $booking->guest_email,
                'adults'           => $booking->adults,
                'children'         => $booking->children,
                'check_in'         => optional($booking->check_in)->format('d M Y'),
                'check_out'        => optional($booking->check_out)->format('d M Y'),
                'nights'           => $booking->nights,
                'room_price'       => $booking->room_price,
                'total_amount'     => $booking->total_amount,
                'advance_paid'     => $booking->advance_paid,
                'remaining'        => $booking->getRemainingBalance(),
                'payment_method'   => $booking->payment_method,
                'payment_status'   => $booking->payment_status,
                'paymentBadge'     => $booking->getPaymentBadgeClass(),
                'status'           => $booking->status,
                'statusBadge'      => $booking->getStatusBadgeClass(),
                'special_requests' => $booking->special_requests,
                'notes'            => $booking->notes,
                'room_number'      => $booking->room->room_number ?? '—',
                'room_type'        => $booking->room->type ?? '',
                'room_floor'       => $booking->room->floor ?? '',
                'check_in_time'    => $booking->room && $booking->room->check_in_time ? Carbon::parse($booking->room->check_in_time)->format('h:i A') : null,
                'check_out_time'   => $booking->room && $booking->room->check_out_time ? Carbon::parse($booking->room->check_out_time)->format('h:i A') : null,
            ],
        ]);
    }

    public function edit(Booking $booking)
    {
        return Inertia::render('Bookings/Edit', [
            'rooms'     => $this->roomOptions(Room::orderBy('room_number')->get()),
            'customers' => $this->customerOptions(),
            'booking'   => [
                'uuid'             => $booking->uuid,
                'booking_number'   => $booking->booking_number,
                'room_id'          => $booking->room_id,
                'customer_id'      => $booking->customer_id,
                'guest_name'       => $booking->guest_name,
                'father_name'      => $booking->father_name,
                'guest_phone'      => $booking->guest_phone,
                'guest_cnic'       => $booking->guest_cnic,
                'guest_email'      => $booking->guest_email,
                'adults'           => $booking->adults,
                'children'         => $booking->children,
                'check_in'         => optional($booking->check_in)->format('Y-m-d'),
                'check_out'        => optional($booking->check_out)->format('Y-m-d'),
                'nights'           => $booking->nights,
                'room_price'       => $booking->room_price,
                'total_amount'     => $booking->total_amount,
                'remaining'        => $booking->getRemainingBalance(),
                'payment_status'   => $booking->payment_status,
                'payment_method'   => $booking->payment_method,
                'status'           => $booking->status,
                'special_requests' => $booking->special_requests,
                'notes'            => $booking->notes,
                'room_label'       => 'Room ' . ($booking->room->room_number ?? '') . ' — ' . ($booking->room->type ?? ''),
            ],
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'customer_id'    => 'required|exists:customers,id',
            'guest_name'     => 'required|string|max:100',
            'father_name'    => 'nullable|string|max:100',
            'guest_phone'    => 'required|string|max:20',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date|after:check_in',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'status'         => 'required',
        ]);

        $room     = Room::findOrFail($request->room_id);
        $checkin  = Carbon::parse($request->check_in);
        $checkout = Carbon::parse($request->check_out);
        $nights   = $checkin->diffInDays($checkout);
        $total    = $nights * $room->price_per_night;

        if ($booking->room_id != $request->room_id) {
            $booking->room->update(['status' => 'Available']);
        }

        $booking->update([
            'room_id'          => $request->room_id,
            'customer_id'      => $request->customer_id,
            'guest_name'       => $request->guest_name,
            'father_name'      => $request->father_name,
            'guest_phone'      => $request->guest_phone,
            'guest_cnic'       => $request->guest_cnic,
            'guest_email'      => $request->guest_email,
            'adults'           => $request->adults,
            'children'         => $request->children ?? 0,
            'check_in'         => $request->check_in,
            'check_out'        => $request->check_out,
            'nights'           => $nights,
            'room_price'       => $room->price_per_night,
            'total_amount'     => $total,
            'payment_status'   => $request->payment_status,
            'payment_method'   => $request->payment_method,
            'status'           => $request->status,
            'special_requests' => $request->special_requests,
            'notes'            => $request->notes,
        ]);

        if (in_array($request->status, ['Confirmed', 'Checked In'])) {
            $room->update(['status' => 'Occupied']);
        } elseif (in_array($request->status, ['Checked Out', 'Cancelled', 'No Show'])) {
            $room->update(['status' => 'Available']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking has been updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status === 'Checked In') {
            $booking->room->update(['status' => 'Available']);
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking has been deleted successfully!');
    }

    public function checkout(Booking $booking)
    {
        $booking->update(['status' => 'Checked Out']);
        $booking->room->update(['status' => 'Available']);

        return back()->with('success', 'Guest has been checked out successfully!');
    }

    public function checkin(Booking $booking)
    {
        $booking->update(['status' => 'Checked In']);
        $booking->room->update(['status' => 'Occupied']);

        return back()->with('success', 'Guest has been checked in successfully!');
    }

    // ── Helpers: serialize dropdown options for the Vue form ──
    private function roomOptions($rooms): array
    {
        return $rooms->map(fn ($r) => [
            'id'          => $r->id,
            'room_number' => $r->room_number,
            'type'        => $r->type,
            'price'       => $r->price_per_night,
            'capacity'    => $r->capacity,
            'status'      => $r->status,
        ])->all();
    }

    private function customerOptions(): array
    {
        return Customer::orderBy('name')->get()->map(fn ($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'phone' => $c->phone,
            'cnic'  => $c->cnic,
            'email' => $c->email,
        ])->all();
    }
}
