<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // ── Dummy fallback data 
    private function dummyEvents(): array
    {
        return [
            ['id' => 1, 'title' => 'Explore the Beauty of Shogran Valley', 'tag' => 'Travel Trip', 'image_url' => asset('landing-assets/img/blog/blog-1.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-15')],
            ['id' => 2, 'title' => 'Premium Rooms with Mountain Views', 'tag' => 'Luxury Stay', 'image_url' => asset('landing-assets/img/blog/blog-2.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-18')],
            ['id' => 3, 'title' => 'Bonfire Nights at Bellamonte Resort', 'tag' => 'Event', 'image_url' => asset('landing-assets/img/blog/blog-3.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-20')],
            ['id' => 4, 'title' => 'Peaceful Morning Views in Kaghan Valley', 'tag' => 'Nature', 'image_url' => asset('landing-assets/img/blog/blog-4.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-22')],
            ['id' => 5, 'title' => 'Comfortable Family Suites for Guests', 'tag' => 'Family Stay', 'image_url' => asset('landing-assets/img/blog/blog-5.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-25')],
            ['id' => 6, 'title' => 'Discover the Hidden Beauty of Northern Pakistan', 'tag' => 'Tourism', 'image_url' => asset('landing-assets/img/blog/blog-6.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-28')],
            ['id' => 7, 'title' => 'Hiking Adventures Around Shogran', 'tag' => 'Adventure', 'image_url' => asset('landing-assets/img/blog/blog-7.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-29')],
            ['id' => 8, 'title' => 'Enjoy Memorable Resort Events with Family', 'tag' => 'Events & Travel', 'image_url' => asset('landing-assets/img/blog/blog-8.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-30')],
            ['id' => 9, 'title' => 'Outdoor Camping Experience Near Bellamonte Resort', 'tag' => 'Camping', 'image_url' => asset('landing-assets/img/blog/blog-9.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-31')],
        ];
    }

    // 
    // PUBLIC: Events Grid
    public function show()
    {
        $dbEvents = Event::active()->orderBy('sort_order')->orderByDesc('event_date')->get();
        $useDb    = $dbEvents->count() > 0;
        $events   = $useDb ? $dbEvents : collect($this->dummyEvents());

        return view('pages.events.event', compact('events', 'useDb'));
    }

    // PUBLIC: Event Details
    public function details($id = null)
    {
        if ($id) {
            $event   = Event::active()->findOrFail($id);
            $related = Event::active()->where('id', '!=', $id)->latest()->take(3)->get();
            $useDb   = true;
        } else {
            // Fallback — show first dummy
            $event   = (object) [
                'title'          => 'Experience the Beauty of Shogran at Bellamonte Resort',
                'tag'            => 'Travel, Nature & Luxury Stay',
                'event_date'     => \Carbon\Carbon::parse('2026-05-17'),
                'description'    => 'Nestled in the breathtaking hills of Shogran, Bellamonte Resort offers a perfect combination of luxury, comfort, and natural beauty.',
                'short_description' => 'Whether you are planning a family vacation, honeymoon trip, corporate retreat, or weekend getaway, Bellamonte Resort provides premium rooms, exceptional hospitality, and unforgettable experiences.',
                'detail_image_1_url' => asset('landing-assets/img/blog/blog-details/blog-details-1.jpg'),
                'detail_image_2_url' => asset('landing-assets/img/blog/blog-details/blog-details-2.jpg'),
                'detail_image_3_url' => asset('landing-assets/img/blog/blog-details/blog-details-3.jpg'),
                'section_1_title' => 'Luxury & Comfort',
                'section_1_text'  => 'Bellamonte Resort offers spacious and comfortable rooms with premium interiors, mountain-facing balconies, quality room service, and peaceful surroundings.',
                'section_2_title' => 'Perfect Destination in Northern Pakistan',
                'section_2_text'  => 'Shogran is one of the most beautiful tourist destinations in Pakistan. Staying at Bellamonte Resort allows visitors to experience nature, adventure, and luxury together.',
            ];
            $related = collect($this->dummyEvents())->take(3)->map(fn($e) => (object)$e);
            $useDb   = false;
        }

        return view('pages.events.details', compact('event', 'related', 'useDb'));
    }

    // ADMIN: Index
    public function index()
    {
        $events = Event::orderBy('sort_order')->latest()->get();
        return view('pages.admin-side.events.index', compact('events'));
    }

    // ADMIN: Create
    public function create()
    {
        $count = Event::count();
        if ($count >= 9) {
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

        $request->validate([
            'title'          => 'required|string|max:200',
            'tag'            => 'required|string|max:50',
            'event_date'     => 'required|date',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description'    => 'nullable|string',
            'detail_image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'section_1_title' => 'nullable|string|max:200',
            'section_1_text'  => 'nullable|string',
            'section_2_title' => 'nullable|string|max:200',
            'section_2_text'  => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'detail_image_1', 'detail_image_2', 'detail_image_3']);
        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', 0);

        // Upload images
        foreach (['image', 'detail_image_1', 'detail_image_2', 'detail_image_3'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('events', 'public');
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
        $request->validate([
            'title'          => 'required|string|max:200',
            'tag'            => 'required|string|max:50',
            'event_date'     => 'required|date',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description'    => 'nullable|string',
            'detail_image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_image_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'section_1_title' => 'nullable|string|max:200',
            'section_1_text'  => 'nullable|string',
            'section_2_title' => 'nullable|string|max:200',
            'section_2_text'  => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'detail_image_1', 'detail_image_2', 'detail_image_3']);
        $data['is_active'] = $request->boolean('is_active', true);

        foreach (['image', 'detail_image_1', 'detail_image_2', 'detail_image_3'] as $field) {
            if ($request->hasFile($field)) {
                if ($event->$field) Storage::disk('public')->delete($event->$field);
                $data[$field] = $request->file($field)->store('events', 'public');
            }
        }

        $event->update($data);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    // ADMIN: Delete
    public function destroy(Event $event)
    {
        foreach (['image', 'detail_image_1', 'detail_image_2', 'detail_image_3'] as $field) {
            if ($event->$field) Storage::disk('public')->delete($event->$field);
        }
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
