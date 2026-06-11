<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class RoomController extends Controller
{
    /*
    | Frontend Pages (landing — still Blade for now)
    */

    public function list(Request $request)
    {
        $rooms = Room::query()->latest()->paginate(9);

        return view('pages.rooms.list', compact('rooms'));
    }

    public function details(Room $room)
    {
        return view('pages.rooms.details', compact('room'));
    }

    /*
    | Admin CRUD (Inertia)
    */

    public function index()
    {
        $rooms = Room::latest()->get()->map(fn ($room) => [
            'id'             => $room->id,
            'uuid'           => $room->uuid,
            'room_number'    => $room->room_number,
            'type'           => $room->type,
            'floor'          => $room->floor,
            'price_per_night'=> $room->price_per_night,
            'status'         => $room->status,
            'statusBadge'    => $room->getStatusBadgeClass(),
            'check_in_time'  => $room->check_in_time ? Carbon::parse($room->check_in_time)->format('h:i A') : null,
            'check_out_time' => $room->check_out_time ? Carbon::parse($room->check_out_time)->format('h:i A') : null,
            'image'          => $room->image ? asset('uploads/rooms/' . $room->image) : null,
        ]);

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
        ]);
    }

    public function create()
    {
        return Inertia::render('Rooms/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number'     => 'required|integer|min:1|max:9999|unique:rooms,room_number',
            'type'            => 'required|string|max:100',
            'floor'           => 'required|integer|min:0|max:10',
            'price_per_night' => 'required|numeric|min:0|max:999999',
            'capacity'        => 'required|integer|min:1|max:100',
            'size'            => 'nullable|string|max:100',
            'bed_type'        => 'nullable|string|max:100',
            'services'        => 'nullable|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'check_in_time'   => 'nullable|date_format:H:i',
            'check_out_time'  => 'nullable|date_format:H:i',
            'status'          => 'required|in:Available,Occupied,Maintenance',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $imageName = null;

            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/rooms'), $imageName);
            }

            Room::create([
                'room_number'     => $validated['room_number'],
                'type'            => $validated['type'],
                'floor'           => $validated['floor'],
                'price_per_night' => $validated['price_per_night'],
                'capacity'        => $validated['capacity'],
                'size'            => $validated['size'] ?? null,
                'bed_type'        => $validated['bed_type'] ?? null,
                'services'        => $validated['services'] ?? null,
                'description'     => $validated['description'] ?? null,
                'check_in_time'   => $validated['check_in_time'] ?? null,
                'check_out_time'  => $validated['check_out_time'] ?? null,
                'status'          => $validated['status'],
                'image'           => $imageName,
            ]);

            return redirect()->route('admin.rooms.index')->with('success', 'Room added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit(Room $room)
    {
        return Inertia::render('Rooms/Edit', [
            'room' => [
                'uuid'            => $room->uuid,
                'room_number'     => $room->room_number,
                'type'            => $room->type,
                'floor'           => $room->floor,
                'price_per_night' => $room->price_per_night,
                'capacity'        => $room->capacity,
                'size'            => $room->size,
                'bed_type'        => $room->bed_type,
                'services'        => $room->services,
                'description'     => $room->description,
                'check_in_time'   => $room->check_in_time ? Carbon::parse($room->check_in_time)->format('H:i') : '14:00',
                'check_out_time'  => $room->check_out_time ? Carbon::parse($room->check_out_time)->format('H:i') : '12:00',
                'status'          => $room->status,
                'image'           => $room->image ? asset('uploads/rooms/' . $room->image) : null,
            ],
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number'     => 'required|integer|min:1|max:9999|unique:rooms,room_number,' . $room->id,
            'type'            => 'required|string|max:100',
            'floor'           => 'required|integer|min:0|max:10',
            'price_per_night' => 'required|numeric|min:0|max:999999',
            'capacity'        => 'required|integer|min:1|max:100',
            'size'            => 'nullable|string|max:100',
            'bed_type'        => 'nullable|string|max:100',
            'services'        => 'nullable|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'check_in_time'   => 'nullable|date_format:H:i',
            'check_out_time'  => 'nullable|date_format:H:i',
            'status'          => 'required|in:Available,Occupied,Maintenance',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $imageName = $room->image;

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                if ($room->image && file_exists(public_path('uploads/rooms/' . $room->image))) {
                    unlink(public_path('uploads/rooms/' . $room->image));
                }

                $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/rooms'), $imageName);
            }

            $room->update([
                'room_number'     => $validated['room_number'],
                'type'            => $validated['type'],
                'floor'           => $validated['floor'],
                'price_per_night' => $validated['price_per_night'],
                'capacity'        => $validated['capacity'],
                'size'            => $validated['size'] ?? null,
                'bed_type'        => $validated['bed_type'] ?? null,
                'services'        => $validated['services'] ?? null,
                'description'     => $validated['description'] ?? null,
                'check_in_time'   => $validated['check_in_time'] ?? null,
                'check_out_time'  => $validated['check_out_time'] ?? null,
                'status'          => $validated['status'],
                'image'           => $imageName,
            ]);

            return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while updating room.');
        }
    }

    public function show(Room $room)
    {
        return Inertia::render('Rooms/Show', [
            'room' => [
                'uuid'            => $room->uuid,
                'room_number'     => $room->room_number,
                'type'            => $room->type,
                'floor'           => $room->floor,
                'price_per_night' => $room->price_per_night,
                'capacity'        => $room->capacity,
                'size'            => $room->size,
                'bed_type'        => $room->bed_type,
                'services'        => $room->getServicesArray(),
                'description'     => $room->description,
                'check_in_time'   => $room->check_in_time ? Carbon::parse($room->check_in_time)->format('h:i A') : '2:00 PM',
                'check_out_time'  => $room->check_out_time ? Carbon::parse($room->check_out_time)->format('h:i A') : '12:00 PM',
                'status'          => $room->status,
                'statusBadge'     => $room->getStatusBadgeClass(),
                'image'           => $room->image ? asset('uploads/rooms/' . $room->image) : null,
            ],
        ]);
    }

    public function destroy(Room $room)
    {
        if ($room->image && file_exists(public_path('uploads/rooms/' . $room->image))) {
            unlink(public_path('uploads/rooms/' . $room->image));
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
