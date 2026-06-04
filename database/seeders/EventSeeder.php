<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Pehle se events hain to seed mat karo
        if (Event::count() > 0) {
            $this->command->info('Events already exist. Skipping seeder.');
            return;
        }

        $events = [
            [
                'title'      => 'Explore the Beauty of Shogran Valley',
                'tag'        => 'Travel Trip',
                'event_date' => '2026-05-15',
                'sort_order' => 1,
            ],
            [
                'title'      => 'Premium Rooms with Mountain Views',
                'tag'        => 'Luxury Stay',
                'event_date' => '2026-05-18',
                'sort_order' => 2,
            ],
            [
                'title'      => 'Bonfire Nights at Bellamonte Resort',
                'tag'        => 'Event',
                'event_date' => '2026-05-20',
                'sort_order' => 3,
            ],
            [
                'title'      => 'Peaceful Morning Views in Kaghan Valley',
                'tag'        => 'Nature',
                'event_date' => '2026-05-22',
                'sort_order' => 4,
            ],
            [
                'title'      => 'Comfortable Family Suites for Guests',
                'tag'        => 'Family Stay',
                'event_date' => '2026-05-25',
                'sort_order' => 5,
            ],
            [
                'title'      => 'Discover the Hidden Beauty of Northern Pakistan',
                'tag'        => 'Tourism',
                'event_date' => '2026-05-28',
                'sort_order' => 6,
            ],
            [
                'title'      => 'Hiking Adventures Around Shogran',
                'tag'        => 'Adventure',
                'event_date' => '2026-05-29',
                'sort_order' => 7,
            ],
            [
                'title'      => 'Enjoy Memorable Resort Events with Family',
                'tag'        => 'Events & Travel',
                'event_date' => '2026-05-30',
                'sort_order' => 8,
            ],
            [
                'title'      => 'Outdoor Camping Experience Near Bellamonte Resort',
                'tag'        => 'Camping',
                'event_date' => '2026-05-31',
                'sort_order' => 9,
            ],
        ];

        foreach ($events as $event) {
            Event::create([
                ...$event,
                'image'      => null,
                'is_active'  => true,
            ]);
        }

        $this->command->info('9 events seeded successfully!');
    }
}
