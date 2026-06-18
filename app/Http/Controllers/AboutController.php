<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    // ── Public page (landing — still Blade) ──
    public function show()
    {
        $data = AboutSetting::getData();
        return view('pages.about-us.about', compact('data'));
    }

    // ── Admin: edit form (Inertia) ──
    public function index()
    {
        $data = AboutSetting::getData();

        $offerDefaults = [
            1 => '20% Off On Accommodation.',
            2 => 'Complimentary Daily Breakfast',
            3 => '3 Pcs Laundry Per Day',
            4 => 'Free Wifi.',
            5 => 'Discount 20% On F&B',
        ];
        $serviceDefaults = [1 => 'Restaurants Services', 2 => 'Travel & Camping', 3 => 'Event & Party'];

        $form = [
            'welcome_title'       => $data['welcome_title'] ?? 'Welcome To Bellamonte Resort.',
            'welcome_description' => $data['welcome_description'] ?? '',
            'video_title'         => $data['video_title'] ?? 'Discover Our Hotel & Services.',
            'video_subtitle'      => $data['video_subtitle'] ?? '',
            'video_url'           => $data['video_url'] ?? 'https://www.youtube.com/watch?v=EzKkl64rRbM',
        ];
        foreach ([1, 2, 3, 4, 5] as $n) {
            $form["offer_{$n}"] = $data["offer_{$n}"] ?? $offerDefaults[$n];
        }
        foreach ([1, 2, 3] as $n) {
            $form["service_{$n}_title"] = $data["service_{$n}_title"] ?? $serviceDefaults[$n];
        }
        foreach ([1, 2, 3, 4, 5] as $n) {
            $form["gallery_{$n}_title"] = $data["gallery_{$n}_title"] ?? 'Room Luxury';
        }

        $images = [
            'video_bg' => !empty($data['video_bg_image']) ? AboutSetting::videoBgUrl($data) : null,
        ];
        foreach ([1, 2, 3] as $n) {
            $images["service_{$n}"] = !empty($data["service_{$n}_image"]) ? AboutSetting::serviceImageUrl($data, $n) : null;
        }
        foreach ([1, 2, 3, 4, 5] as $n) {
            $images["gallery_{$n}"] = !empty($data["gallery_{$n}_image"]) ? AboutSetting::galleryImageUrl($data, $n) : null;
        }

        return Inertia::render('About/Index', [
            'form'   => $form,
            'images' => $images,
        ]);
    }

    // ── Admin: save ──
    public function update(Request $request)
    {
        $request->validate([
            'welcome_title'       => 'nullable|string|max:200',
            'welcome_description' => 'nullable|string',
            'service_1_title'     => 'nullable|string|max:100',
            'service_2_title'     => 'nullable|string|max:100',
            'service_3_title'     => 'nullable|string|max:100',
            'service_1_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_2_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_3_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_title'         => 'nullable|string|max:200',
            'video_subtitle'      => 'nullable|string|max:200',
            'video_url'           => 'nullable|url',
            'video_bg_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'offer_1'             => 'nullable|string|max:100',
            'offer_2'             => 'nullable|string|max:100',
            'offer_3'             => 'nullable|string|max:100',
            'offer_4'             => 'nullable|string|max:100',
            'offer_5'             => 'nullable|string|max:100',
            'gallery_1_title'     => 'nullable|string|max:100',
            'gallery_2_title'     => 'nullable|string|max:100',
            'gallery_3_title'     => 'nullable|string|max:100',
            'gallery_4_title'     => 'nullable|string|max:100',
            'gallery_5_title'     => 'nullable|string|max:100',
            'gallery_1_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_2_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_3_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_4_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_5_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $about = AboutSetting::first() ?: new AboutSetting();
        $data  = $about->data ?? [];

        $textFields = [
            'welcome_title', 'welcome_description',
            'service_1_title', 'service_2_title', 'service_3_title',
            'video_title', 'video_subtitle', 'video_url',
            'offer_1', 'offer_2', 'offer_3', 'offer_4', 'offer_5',
            'gallery_1_title', 'gallery_2_title', 'gallery_3_title', 'gallery_4_title', 'gallery_5_title',
        ];

        foreach ($textFields as $field) {
            if ($request->filled($field)) {
                $data[$field] = $request->input($field);
            }
        }

        $imageFields = [
            'service_1_image', 'service_2_image', 'service_3_image', 'video_bg_image',
            'gallery_1_image', 'gallery_2_image', 'gallery_3_image', 'gallery_4_image', 'gallery_5_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if (!empty($data[$field])) {
                    $oldPath = public_path('uploads/about/' . $data[$field]);
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $file     = $request->file($field);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/about'), $filename);

                $data[$field] = $filename;
            }
        }

        $about->data = $data;
        $about->save();

        return redirect()->back()->with('success', 'About page updated successfully!');
    }
}
