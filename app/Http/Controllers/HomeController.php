<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Event;
use App\Models\HeroSection;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // ── Dummy fallback events (5 only for homepage) 
    private function dummyEvents(): array
    {
        return [
            ['id' => 1, 'title' => 'Explore the Beauty of Shogran Valley',           'tag' => 'Travel',       'image_url' => asset('landing-assets/img/shogran2.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-15')],
            ['id' => 2, 'title' => 'Premium Rooms with Mountain Views',               'tag' => 'Luxury Stay',  'image_url' => asset('landing-assets/img/shogran3.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-18')],
            ['id' => 3, 'title' => 'Bonfire Nights at Bellamonte Resort',             'tag' => 'Events',       'image_url' => asset('landing-assets/img/shogran7.jpg'), 'event_date' => \Carbon\Carbon::parse('2026-05-20')],
            ['id' => 4, 'title' => 'Peaceful Morning Views in Kaghan Valley',         'tag' => 'Adventure',    'image_url' => asset('landing-assets/img/shogran4.webp'), 'event_date' => \Carbon\Carbon::parse('2026-05-22')],
            ['id' => 5, 'title' => 'Best Tourist Attractions Near Bellamonte Resort', 'tag' => 'Travel Guide', 'image_url' => asset('landing-assets/img/shogran6.webp'),  'event_date' => \Carbon\Carbon::parse('2026-05-25')],
        ];
    }

    public function show()
    {
        $rooms = Room::latest()->get();

        // $hero = HeroSection::where('status', 'Active')->latest()->first();

        $dbEvents = Event::active()
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->take(5)
            ->get();

        $useDb = $dbEvents->count() > 0;

        $events = $useDb
            ? $dbEvents
            : collect($this->dummyEvents())->map(fn($e) => (object) $e);

        return view('pages.landing.index', compact(
            'rooms',
            'events',
            'useDb'
            
        ));
    }
}
