<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    public function index()
    {
        $bookings = Booking::with(['room', 'customer'])->latest()->get();
        return view('pages.admin-side.booking.index', compact('bookings'));
    }

    public function create()
    {
        $rooms     = Room::where('status', 'Available')->orderBy('room_number')->get();
        $customers = Customer::orderBy('name')->get();
        return view('pages.admin-side.booking.create', compact('rooms', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'customer_id'    => 'required|exists:customers,id',
            'guest_name'     => 'required|string|max:100',
            'guest_phone'    => 'required|string|max:20',
            'guest_cnic'     => 'nullable|string|max:20',
            'guest_email'    => 'nullable|email|max:100',
            'adults'         => 'required|integer|min:1',
            'children'       => 'nullable|integer|min:0',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date|after:check_in',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'advance_paid'   => 'nullable|numeric|min:0',
            'status'         => 'required',
            'special_requests' => 'nullable|string',
            'notes'          => 'nullable|string',
        ]);

        $room   = Room::findOrFail($request->room_id);
        $checkin  = Carbon::parse($request->check_in);
        $checkout = Carbon::parse($request->check_out);
        $nights   = $checkin->diffInDays($checkout);
        $total    = $nights * $room->price_per_night;

        $booking = Booking::create([
            'booking_number'  => Booking::generateBookingNumber(),
            'room_id'         => $request->room_id,
            'customer_id'     => $request->customer_id,
            'guest_name'      => $request->guest_name,
            'guest_phone'     => $request->guest_phone,
            'guest_cnic'      => $request->guest_cnic,
            'guest_email'     => $request->guest_email,
            'adults'          => $request->adults,
            'children'        => $request->children ?? 0,
            'check_in'        => $request->check_in,
            'check_out'       => $request->check_out,
            'nights'          => $nights,
            'room_price'      => $room->price_per_night,
            'total_amount'    => $total,
            'payment_status'  => $request->payment_status,
            'payment_method'  => $request->payment_method,
            'advance_paid'    => $request->advance_paid ?? 0,
            'status'          => $request->status,
            'special_requests' => $request->special_requests,
            'notes'           => $request->notes,
        ]);

        // Mark room as occupied
        if (in_array($request->status, ['Confirmed', 'Checked In'])) {
            $room->update(['status' => 'Occupied']);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been confirmed successfully! ' . $booking->booking_number . ' ✅');
    }

    public function show(Booking $booking)
    {
        $booking->load(['room', 'customer']);
        return view('pages.admin-side.booking.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $rooms     = Room::orderBy('room_number')->get();
        $customers = Customer::orderBy('name')->get();
        return view('pages.admin-side.booking.edit', compact('booking', 'rooms', 'customers'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'customer_id'    => 'required|exists:customers,id',
            'guest_name'     => 'required|string|max:100',
            'guest_phone'    => 'required|string|max:20',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date|after:check_in',
            'payment_status' => 'required',
            'payment_method' => 'required',
            'advance_paid'   => 'nullable|numeric|min:0',
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
            'room_id'         => $request->room_id,
            'customer_id'     => $request->customer_id,
            'guest_name'      => $request->guest_name,
            'guest_phone'     => $request->guest_phone,
            'guest_cnic'      => $request->guest_cnic,
            'guest_email'     => $request->guest_email,
            'adults'          => $request->adults,
            'children'        => $request->children ?? 0,
            'check_in'        => $request->check_in,
            'check_out'       => $request->check_out,
            'nights'          => $nights,
            'room_price'      => $room->price_per_night,
            'total_amount'    => $total,
            'payment_status'  => $request->payment_status,
            'payment_method'  => $request->payment_method,
            'advance_paid'    => $request->advance_paid ?? 0,
            'status'          => $request->status,
            'special_requests' => $request->special_requests,
            'notes'           => $request->notes,
        ]);

        // Room status update
        if (in_array($request->status, ['Confirmed', 'Checked In'])) {
            $room->update(['status' => 'Occupied']);
        } elseif (in_array($request->status, ['Checked Out', 'Cancelled', 'No Show'])) {
            $room->update(['status' => 'Available']);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        // Free room
        if ($booking->status === 'Checked In') {
            $booking->room->update(['status' => 'Available']);
        }



        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking has been deleted successfully!');
    }

    // Quick checkout
    public function checkout(Booking $booking)
    {
        $booking->update(['status' => 'Checked Out']);
        $booking->room->update(['status' => 'Available']);
        return redirect()->back()->with('success', 'Guest has been checked out successfully!');
    }

    // Quick checkin
    public function checkin(Booking $booking)
    {
        $booking->update(['status' => 'Checked In']);
        $booking->room->update(['status' => 'Occupied']);
        return redirect()->back()->with('success', 'Guest has been checked in successfully!');
    }
}
