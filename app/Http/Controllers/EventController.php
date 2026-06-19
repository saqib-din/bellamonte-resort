<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EventController extends Controller
{
    private array $imageFields = ['image', 'detail_image_1', 'detail_image_2', 'detail_image_3'];

    private function rules(bool $imageRequired = false, ?int $ignoreId = null): array
    {
        return [
            'title'             => 'required|string|max:200',
            'tag'               => 'required|string|max:50',
            'event_date'        => 'required|date',
            'image'             => ($imageRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string|max:500',
            'detail_image_1'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_2'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_3'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'section_1_title'   => 'nullable|string|max:200',
            'section_1_text'    => 'nullable|string|max:200',
            'section_2_title'   => 'nullable|string|max:200',
            'section_2_text'    => 'nullable|string|max:200',
            'sort_order'        => ['required', 'integer', 'min:1', 'max:9', Rule::unique('events', 'sort_order')->ignore($ignoreId)],
            'is_active'         => 'nullable|boolean',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer'  => 'Sort order must be a whole number.',
            'sort_order.min'      => 'Sort order must be between 1 and 9.',
            'sort_order.max'      => 'Sort order must be between 1 and 9.',
            'sort_order.unique'   => 'This sort order is already used by another event. Each event must have a unique order (1–9).',
        ];
    }

    private function saveImage($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/events'), $filename);
        return $filename;
    }

    private function deleteImage(?string $filename): void
    {
        if ($filename) {
            $path = public_path('uploads/events/' . $filename);
            if (file_exists($path)) unlink($path);
        }
    }

    // ── PUBLIC (landing — still Blade) ──
    public function show()
    {
        $events = Event::active()->orderBy('sort_order')->orderByDesc('event_date')->get();
        return view('pages.events.event', compact('events'));
    }

    public function details(Event $event)
    {
        abort_if(! $event->is_active, 404);
        $related = Event::active()->where('id', '!=', $event->id)->latest()->take(3)->get();
        return view('pages.events.details', compact('event', 'related'));
    }

    // ── ADMIN (Inertia) ──
    public function index()
    {
        $events = Event::orderBy('sort_order')->latest()->get()->map(fn ($e) => [
            'slug'       => $e->slug,
            'title'      => $e->title,
            'tag'        => $e->tag,
            'event_date' => optional($e->event_date)->format('d M Y'),
            'sort_order' => $e->sort_order,
            'is_active'  => (bool) $e->is_active,
            'image_url'  => $e->image_url,
        ]);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'count'  => $events->count(),
        ]);
    }

    public function create()
    {
        if (Event::count() >= 9) {
            return redirect()->route('events.index')->with('error', 'Maximum 9 events allowed. Please delete one first.');
        }

        return Inertia::render('Events/Create', [
            'count' => Event::count(),
        ]);
    }

    public function store(Request $request)
    {
        if (Event::count() >= 9) {
            return redirect()->route('events.index')->with('error', 'Maximum 9 events allowed. Please delete one first.');
        }

        $request->validate($this->rules(imageRequired: true), $this->validationMessages());

        $data               = $request->except(array_merge($this->imageFields, ['_method']));
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);

        foreach ($this->imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->saveImage($request->file($field));
            }
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return Inertia::render('Events/Edit', [
            'count' => Event::count(),
            'event' => [
                'slug'              => $event->slug,
                'title'             => $event->title,
                'tag'               => $event->tag,
                'event_date'        => optional($event->event_date)->format('Y-m-d'),
                'short_description' => $event->short_description,
                'description'       => $event->description,
                'section_1_title'   => $event->section_1_title,
                'section_1_text'    => $event->section_1_text,
                'section_2_title'   => $event->section_2_title,
                'section_2_text'    => $event->section_2_text,
                'sort_order'        => $event->sort_order,
                'is_active'         => (bool) $event->is_active,
                'image_url'         => $event->image ? $event->image_url : null,
                'detail_image_1_url'=> $event->detail_image_1 ? $event->detail_image_1_url : null,
                'detail_image_2_url'=> $event->detail_image_2 ? $event->detail_image_2_url : null,
                'detail_image_3_url'=> $event->detail_image_3 ? $event->detail_image_3_url : null,
            ],
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate($this->rules(imageRequired: false, ignoreId: $event->id), $this->validationMessages());

        $data              = $request->except(array_merge($this->imageFields, ['_method']));
        $data['is_active'] = $request->boolean('is_active', true);

        foreach ($this->imageFields as $field) {
            if ($request->hasFile($field)) {
                $this->deleteImage($event->$field);
                $data[$field] = $this->saveImage($request->file($field));
            }
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }
}
