<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) 
    {
        // 1. Ambil data slider untuk banner di welcome.blade
        $sliders = Slider::where('is_active', true)
                         ->orderBy('order', 'asc')
                         ->get();
                         
        // 2. Ambil semua kategori untuk menu navigasi/filter
        $categories = Category::all();
        
        // 3. Inisialisasi Query Produk dengan gambar utama
        $query = Product::with(['images' => function($query) {
            $query->where('is_primary', true);
        }]);

        // 4. Logika Filter: Jika user klik kategori tertentu
        if ($request->has('category') && $request->category != null) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 5. Eksekusi query (ambil data terbaru)
        $products = $query->latest()->get();

        // FIX: Menambahkan 'sliders' ke dalam compact agar bisa dibaca oleh Blade
        return view('welcome', compact('products', 'categories', 'sliders'));
    }

    public function show($slug)
    {
        // Cari produk berdasarkan slug beserta semua gambarnya
        $product = Product::with('images', 'category')->where('slug', $slug)->firstOrFail();
        
        // Ambil produk lain dari kategori yang sama sebagai rekomendasi
        $relatedProducts = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->limit(4)
                            ->get();

        return view('details', compact('product', 'relatedProducts'));
    }
}