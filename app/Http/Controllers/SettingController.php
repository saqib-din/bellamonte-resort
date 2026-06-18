<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all_settings();
        $logo     = $settings['hotel_logo'] ?? null;

        return Inertia::render('Settings/Index', [
            'settings' => $settings,
            'logoUrl'  => $logo ? asset('uploads/settings/' . $logo) : null,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'hotel_name'    => 'required|string|max:100',
            'hotel_email'   => 'nullable|email|max:150',
            'hotel_phone'   => 'nullable|string|max:30',
            'hotel_country' => 'nullable|string|max:100',
            'hotel_address' => 'nullable|string|max:255',
            'facebook'      => 'nullable|string|max:255',
            'instagram'     => 'nullable|string|max:255',
            'twitter'       => 'nullable|string|max:255',
            'hotel_logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        // Save logo
        if ($request->hasFile('hotel_logo')) {
            $old = Setting::get('hotel_logo');
            if ($old && file_exists(public_path('uploads/settings/' . $old))) {
                unlink(public_path('uploads/settings/' . $old));
            }
            $file = $request->file('hotel_logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            Setting::set('hotel_logo', $filename);
        }

        // Save all other settings
        $keys = [
            'hotel_name',
            // 'hotel_tagline',
            'hotel_email',
            'hotel_phone',
            'hotel_address',
            // 'hotel_city',
            'hotel_country',
            // 'hotel_website',
            // 'invoice_prefix',
            // 'default_tax',
            // 'currency',
            // 'currency_code',
            // 'checkin_time',
            // 'checkout_time',
            'facebook',
            'instagram',
            'twitter',
            // 'footer_text',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings have been saved successfully!');
    }
}
