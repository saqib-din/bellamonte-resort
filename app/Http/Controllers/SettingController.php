<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all_settings();
        return view('pages.admin-side.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hotel_name'    => 'required|string|max:100',
            'hotel_email'   => 'nullable|email',
            'hotel_phone'   => 'nullable|string|max:20',
            'default_tax'   => 'nullable|numeric|min:0|max:100',
            'checkin_time'  => 'nullable',
            'checkout_time' => 'nullable',
            'hotel_logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        // Save logo
        if ($request->hasFile('hotel_logo')) {
            $old = Setting::get('hotel_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('hotel_logo')->store('settings', 'public');
            Setting::set('hotel_logo', $path);
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
