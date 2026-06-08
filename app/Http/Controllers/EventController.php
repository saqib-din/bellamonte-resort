<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // ── Image fields list ──────────────────────────────
    private array $imageFields = ['image', 'detail_image_1', 'detail_image_2', 'detail_image_3'];

    // ── Validation rules (shared) ──────────────────────
    private function rules(bool $imageRequired = false): array
    {
        return [
            'title'             => 'required|string|max:200',
            'tag'               => 'required|string|max:50',
            'event_date'        => 'required|date',
            'image'             => ($imageRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'detail_image_1'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_2'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_3'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'section_1_title'   => 'nullable|string|max:200',
            'section_1_text'    => 'nullable|string',
            'section_2_title'   => 'nullable|string|max:200',
            'section_2_text'    => 'nullable|string',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ];
    }

    // ── Save image to public/uploads/events ───────────
    private function saveImage($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/events'), $filename);
        return $filename;
    }

    // ── Delete image from public/uploads/events ───────
    private function deleteImage(?string $filename): void
    {
        if ($filename) {
            $path = public_path('uploads/events/' . $filename);
            if (file_exists($path)) unlink($path);
        }
    }

    // ─────────────────────────────────────────────────
    // PUBLIC: Events Grid
    // ─────────────────────────────────────────────────
    public function show()
    {
        $events = Event::active()
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->get();

        return view('pages.events.event', compact('events'));
    }

    public function details(Event $event)
    {
        abort_if(! $event->is_active, 404);

        $related = Event::active()
            ->where('id', '!=', $event->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.events.details', compact('event', 'related'));
    }

    // ─────────────────────────────────────────────────
    // ADMIN: Index
    // ─────────────────────────────────────────────────
    public function index()
    {
        $events = Event::orderBy('sort_order')->latest()->get();
        return view('pages.admin-side.events.index', compact('events'));
    }

    // ADMIN: Create
    public function create()
    {
        if (Event::count() >= 9) {
            return redirect()->route('events.index')
                ->with('error', 'Maximum 9 events allowed. Please delete one first.');
        }
        return view('pages.admin-side.events.create');
    }

    // ADMIN: Store
    public function store(Request $request)
    {
        if (Event::count() >= 9) {
            return redirect()->route('events.index')
                ->with('error', 'Maximum 9 events allowed. Please delete one first.');
        }

        $request->validate($this->rules(imageRequired: true));

        $data               = $request->except($this->imageFields);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);

        foreach ($this->imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->saveImage($request->file($field));
            }
        }

        Event::create($data);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    // ADMIN: Edit
    public function edit(Event $event)
    {
        return view('pages.admin-side.events.edit', compact('event'));
    }

    // ADMIN: Update
    public function update(Request $request, Event $event)
    {
        $request->validate($this->rules(imageRequired: false));

        $data              = $request->except($this->imageFields);
        $data['is_active'] = $request->boolean('is_active', true);

        foreach ($this->imageFields as $field) {
            if ($request->hasFile($field)) {
                $this->deleteImage($event->$field);                      
                $data[$field] = $this->saveImage($request->file($field)); 
            }
        }

        $event->update($data);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    // ADMIN: Delete
    // public function destroy(Event $event)
    // {
    //     foreach ($this->imageFields as $field) {
    //         $this->deleteImage($event->$field);
    //     }

    //     $event->delete();

    //     return redirect()->route('events.index')
    //         ->with('success', 'Event deleted successfully!');
    // }
}
