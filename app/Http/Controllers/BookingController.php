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
        $query = Booking::with(['room', 'bill']);

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
            'room_price'     => $b->room_price,
            'nights'         => $b->nights,
            'rate_type'      => $b->rate_type,
            'status'         => $b->status,
            'statusBadge'    => $b->getStatusBadgeClass(),
            'locked'         => optional($b->bill)->status === 'Paid',
            'invoice_number' => optional($b->bill)->invoice_number,
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
            'guest_name'       => 'required|string|max:50',
            'father_name'      => 'nullable|string|max:60',
            'guest_phone'      => 'required|string|max:20',
            'guest_cnic'       => 'nullable|string|max:20',
            'guest_email'      => 'nullable|email|max:100',
            'adults'           => 'required|integer|min:1|max:50',
            'children'         => 'nullable|integer|min:0|max:50',
            'check_in'         => 'required|date',
            'check_out'        => 'required|date|after:check_in',
            'rate_type'        => 'required|in:Night,Day,Hourly',
            'rate'             => 'nullable|numeric',
            'payment_method'   => 'required|in:Cash,Card,Bank Transfer,JazzCash,EasyPaisa',
            'payment_status'   => 'required|in:Pending,Paid,Partial,Refunded',
            'status'           => 'required|in:Confirmed,Checked In,Checked Out,Cancelled,No Show',
            'special_requests' => 'nullable|string|max:2000',
            'notes'            => 'nullable|string|max:2000',
        ]);

        if ($this->roomHasConflict($request->room_id, $request->check_in, $request->check_out)) {
            return back()->withInput()->with('error', 'This room is already booked for the selected dates.');
        }

        $room     = Room::findOrFail($request->room_id);
        $checkin  = Carbon::parse($request->check_in);
        $checkout = Carbon::parse($request->check_out);
        [$nights, $total] = $this->computeUnitsTotal($request->rate_type, $this->rateForType($room, $request->rate_type), $checkin, $checkout);

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
            'rate_type'        => $request->rate_type,
            'room_price'       => $this->rateForType($room, $request->rate_type),
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
        $booking->load(['room', 'customer', 'bill']);

        // Booking payment status follows its invoice (Paid / Partial) when one exists
        if ($booking->bill) {
            $booking->payment_status = match ($booking->bill->status) {
                'Paid'    => 'Paid',
                'Partial' => 'Partial',
                default   => 'Pending',
            };
        }

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
                'check_in'         => optional($booking->check_in)->format('d M Y, h:i A'),
                'check_out'        => optional($booking->check_out)->format('d M Y, h:i A'),
                'nights'           => $booking->nights,
                'rate_type'        => $booking->rate_type,
                'unit_label'       => $booking->getUnitLabel(),
                'room_price'       => $booking->room_price,
                'total_amount'     => $booking->total_amount,
                'invoice_paid'     => optional($booking->bill)->status === 'Paid',
                'has_invoice'      => (bool) $booking->bill,
                'invoice_number'   => optional($booking->bill)->invoice_number,
                'bill_uuid'        => optional($booking->bill)->uuid,
                'advance_paid'     => $booking->advance_paid,
                'remaining'        => $booking->getRemainingBalance(),
                'payment_method'   => $booking->payment_method,
                'payment_status'   => $booking->payment_status,
                'paymentBadge'     => $booking->getPaymentBadgeClass(),
                'status'           => $booking->status,
                'statusBadge'      => $booking->getStatusBadgeClass(),
                'locked'           => $booking->isInvoicePaid(),
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
        if ($booking->isInvoicePaid()) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Booking ' . $booking->booking_number . ' has a fully-paid invoice (' . optional($booking->bill)->invoice_number . ') and can no longer be edited.');
        }

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
                'check_in'         => optional($booking->check_in)->format('Y-m-d\TH:i'),
                'check_out'        => optional($booking->check_out)->format('Y-m-d\TH:i'),
                'nights'           => $booking->nights,
                'rate_type'        => $booking->rate_type,
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
        if ($booking->isInvoicePaid()) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'This booking is locked because its invoice is already fully paid.');
        }

        $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'customer_id'      => 'required|exists:customers,id',
            'guest_name'       => 'required|string|max:50',
            'father_name'      => 'nullable|string|max:60',
            'guest_phone'      => 'required|string|max:20',
            'guest_cnic'       => 'nullable|string|max:20',
            'guest_email'      => 'nullable|email|max:100',
            'adults'           => 'required|integer|min:1|max:50',
            'children'         => 'nullable|integer|min:0|max:50',
            'check_in'         => 'required|date',
            'check_out'        => 'required|date|after:check_in',
            'rate_type'        => 'required|in:Night,Day,Hourly',
            'rate'             => 'nullable|numeric',
            'payment_method'   => 'required|in:Cash,Card,Bank Transfer,JazzCash,EasyPaisa',
            'payment_status'   => 'required|in:Pending,Paid,Partial,Refunded',
            'status'           => 'required|in:Confirmed,Checked In,Checked Out,Cancelled,No Show',
            'special_requests' => 'nullable|string|max:2000',
            'notes'            => 'nullable|string|max:2000',
        ]);

        if ($this->roomHasConflict($request->room_id, $request->check_in, $request->check_out, $booking->id)) {
            return back()->withInput()->with('error', 'This room is already booked for the selected dates.');
        }

        $room     = Room::findOrFail($request->room_id);
        $checkin  = Carbon::parse($request->check_in);
        $checkout = Carbon::parse($request->check_out);
        [$nights, $total] = $this->computeUnitsTotal($request->rate_type, $this->rateForType($room, $request->rate_type), $checkin, $checkout);

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
            'rate_type'        => $request->rate_type,
            'room_price'       => $this->rateForType($room, $request->rate_type),
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
        if ($booking->isInvoicePaid()) {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Cannot delete a booking that has a fully-paid invoice.');
        }

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

    private function roomHasConflict($roomId, $checkIn, $checkOut, $ignoreId = null): bool
    {
        return Booking::where('room_id', $roomId)
            ->whereIn('status', ['Confirmed', 'Checked In'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->whereDate('check_in', '<', $checkOut)
            ->whereDate('check_out', '>', $checkIn)
            ->exists();
    }

    /**
     * Billable units + total by rate type.
     * Night/Day → calendar days; Hourly → hours (rounded up). Minimum 1 unit.
     */
    private function computeUnitsTotal(string $rateType, float $rate, Carbon $checkin, Carbon $checkout): array
    {
        if ($rateType === 'Hourly') {
            $seconds = max(0, $checkout->timestamp - $checkin->timestamp);
            $units   = max(1, (int) ceil($seconds / 3600));
        } else {
            $days  = (int) round(($checkout->copy()->startOfDay()->timestamp - $checkin->copy()->startOfDay()->timestamp) / 86400);
            $units = max(1, $days);
        }

        return [$units, round($units * $rate, 2)];
    }

    private function rateForType(Room $room, string $rateType): float
    {
        return match ($rateType) {
            'Day'    => (float) $room->day_rate,
            'Hourly' => (float) $room->hourly_rate,
            default  => (float) $room->price_per_night,
        };
    }

    private function roomOptions($rooms): array
    {
        return $rooms->map(fn ($r) => [
            'id'          => $r->id,
            'room_number' => $r->room_number,
            'type'        => $r->type,
            'price'       => $r->price_per_night,
            'day_rate'    => $r->day_rate,
            'hourly_rate' => $r->hourly_rate,
            'capacity'    => $r->capacity,
            'status'      => $r->status,
        ])->all();
    }

    private function customerOptions(): array
    {
        return Customer::orderBy('name')->get()->map(fn ($c) => [
            'id'          => $c->id,
            'name'        => $c->name,
            'father_name' => $c->father_name,
            'phone'       => $c->phone,
            'cnic'        => $c->cnic,
            'email'       => $c->email,
        ])->all();
    }
}
