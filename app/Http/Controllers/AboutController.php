<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    // ── Public page ─────────────────────────────
    public function show()
    {
        $data = AboutSetting::getData();
        return view('pages.about-us.about', compact('data'));
    }

    // ── Admin: edit form ─────────────────────────
    public function index()
    {
        $data = AboutSetting::getData();
        return view('pages.admin-side.about.index', compact('data'));
    }

    // ── Admin: save ─────────────────────────────
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

        // ── SAFE SINGLE ROW HANDLING ──
        $about = AboutSetting::first();

        if (!$about) {
            $about = new AboutSetting();
        }

        $data = $about->data ?? [];

        // ── TEXT FIELDS ──
        $textFields = [
            'welcome_title',
            'welcome_description',
            'service_1_title',
            'service_2_title',
            'service_3_title',
            'video_title',
            'video_subtitle',
            'video_url',
            'offer_1',
            'offer_2',
            'offer_3',
            'offer_4',
            'offer_5',
            'gallery_1_title',
            'gallery_2_title',
            'gallery_3_title',
            'gallery_4_title',
            'gallery_5_title',
        ];

        foreach ($textFields as $field) {
            if ($request->filled($field)) {
                $data[$field] = $request->input($field);
            }
        }

        // ── IMAGE FIELDS ──
        $imageFields = [
            'service_1_image',
            'service_2_image',
            'service_3_image',
            'video_bg_image',
            'gallery_1_image',
            'gallery_2_image',
            'gallery_3_image',
            'gallery_4_image',
            'gallery_5_image',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {

                // delete old image
                if (!empty($data[$field])) {
                    $oldPath = public_path('uploads/about/' . $data[$field]);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file = $request->file($field);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/about'), $filename);

                $data[$field] = $filename;
            }
        }

        // ── SAVE FINAL DATA ──
        $about->data = $data;
        $about->save();

        return redirect()->back()->with('success', 'About page updated successfully!');
    }
}
