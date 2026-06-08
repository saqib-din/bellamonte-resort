<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /*
    | Frontend Pages
    */

    public function list(Request $request)
    {
        $query = Room::query();

        // //  Filter: price
        // if ($request->filled('price')) {
        //     $query->where('price_per_night', '<=', $request->price);
        // }

        // // Filter: capacity
        // if ($request->filled('capacity')) {
        //     $query->where('capacity', '>=', $request->capacity);
        // }

        $rooms = $query->latest()->paginate(9);

        return view('pages.rooms.list', compact('rooms'));
    }

    public function details(Room $room)
    {
        return view('pages.rooms.details', compact('room'));
    }

    /*
    | Admin CRUD
    */

    public function index()
    {
        $rooms = Room::latest()->paginate(10);

        return view('pages.admin-side.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('pages.admin-side.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|integer|min:1|max:9999|unique:rooms,room_number',
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

            // Default image
            $imageName = null;

            // Safe image upload
            if ($request->hasFile('image')) {

                $image = $request->file('image');

                // Unique file name (avoid overwrite)
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

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Room added successfully.');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit(Room $room)
    {
        return view('pages.admin-side.rooms.edit', compact('room'));
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

            // image update safely
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

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating room.');
        }
    }

    public function show(Room $room)
    {
        return view('pages.admin-side.rooms.show', compact('room'));
    }


    public function destroy(Room $room)
    {
        if ($room->image && file_exists(public_path('uploads/rooms/' . $room->image))) {

            unlink(public_path('uploads/rooms/' . $room->image));
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
