<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
    // Kita buat semua field opsional di validasi agar tidak tertahan di sini
    $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'title' => 'nullable|string|max:255',
    ]);

    // Cek apakah ada salah satu file yang diupload
    if ($request->hasFile('image') || $request->hasFile('image_mobile')) {
        
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sliders', 'public');
        }

        $mobilePath = null;
        if ($request->hasFile('image_mobile')) {
            $mobilePath = $request->file('image_mobile')->store('sliders/mobile', 'public');
        }

        Slider::create([
            'image_path' => $path, // Bisa bernilai null jika hanya upload mobile
            'image_mobile_path' => $mobilePath,
            'title' => $request->title,
            'is_active' => true,
            'order' => Slider::count() + 1,
        ]);

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan!');
    }

    // Jika tidak ada file sama sekali yang dipilih
    return redirect()->back()->with('error', 'Pilih setidaknya satu gambar (Desktop atau Mobile).');
    }

    public function destroy(Slider $slider)
    {
        // Hapus file desktop
        if ($slider->image_path) {
            Storage::disk('public')->delete($slider->image_path);
        }
        
        // Hapus file mobile
        if ($slider->image_mobile_path) {
            Storage::disk('public')->delete($slider->image_mobile_path);
        }
        
        $slider->delete();
        return redirect()->back()->with('success', 'Banner berhasil dihapus!');
    }
}