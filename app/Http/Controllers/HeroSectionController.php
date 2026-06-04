<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeroSection;

class HeroSectionController extends Controller
// {
//     const MAX_HERO_SECTIONS = 3;

//     public function index()
//     {
//         $heroSections = HeroSection::all();
//         $maxReached = $heroSections->count() >= self::MAX_HERO_SECTIONS;

//         return view('pages.admin-side.hero-section.index', compact('heroSections', 'maxReached'));
//     }

//     public function form($id = null)
//     {
//         $hero = $id ? HeroSection::findOrFail($id) : null;

//         // Check limit only for new records
//         if (!$id) {
//             $currentCount = HeroSection::count();
//             if ($currentCount >= self::MAX_HERO_SECTIONS) {
//                 return redirect()
//                     ->route('hero-section.index')
//                     ->with('error', 'Maximum limit reached! You can only have ' . self::MAX_HERO_SECTIONS . ' hero sections.');
//             }
//         }

//         return view('pages.admin-side.hero-section.createorupdate', compact('hero'));
//     }

//     public function save(Request $request, $id = null)
//     {
//         // Check limit for new records
//         if (!$id) {
//             $currentCount = HeroSection::count();
//             if ($currentCount >= self::MAX_HERO_SECTIONS) {
//                 return redirect()
//                     ->route('hero-section.index')
//                     ->with('error', 'Cannot add more hero sections. Maximum limit of ' . self::MAX_HERO_SECTIONS . ' reached!');
//             }
//         }

//         $hero = $id ? HeroSection::findOrFail($id) : new HeroSection();

//         $request->validate([
//             'hero_title'     => 'required|string|max:100',
//             'description'    => 'required|string|max:255',
//             'status'         => 'required',
//             'image'          => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
//         ]);

//         if ($request->hasFile('image')) {
//             if ($id && $hero->image && file_exists(public_path('uploads/hero/' . $hero->image))) {
//                 unlink(public_path('uploads/hero/' . $hero->image));
//             }
//             $imageName = time() . '.' . $request->image->extension();
//             $request->image->move(public_path('uploads/hero'), $imageName);
//             $hero->image = $imageName;
//         }

//         $hero->hero_title    = $request->hero_title;
//         $hero->description   = $request->description;
//         $hero->status        = $request->status;
//         $hero->save();

//         $message = $id ? 'Hero Section Updated Successfully' : 'Hero Section Added Successfully';
//         return redirect()->route('hero-section.index')->with('success', $message);
//     }

//     public function destroy($id)
//     {
//         $hero = HeroSection::findOrFail($id);

//         if ($hero->image && file_exists(public_path('uploads/hero/' . $hero->image))) {
//             unlink(public_path('uploads/hero/' . $hero->image));
//         }

//         $hero->delete();

//         return redirect()->route('hero-section.index')->with('success', 'Hero Section Deleted Successfully');
//     }
// }
