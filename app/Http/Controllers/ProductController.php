<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil produk beserta kategori dan gambar utamanya saja agar ringan
        $products = Product::with(['category', 'images'])->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required',
            'stock' => 'required|integer',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 1. Simpan data produk
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // 2. Simpan banyak gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $key === 0 // Foto pertama otomatis jadi foto utama
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk & Foto berhasil diunggah!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        // Memuat relasi images agar bisa dikelola di halaman edit
        $product->load('images'); 
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 1. Update data teks
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        // 2. Jika ada upload foto baru, tambahkan ke galeri produk tersebut
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false // Default false, bisa diubah manual lewat tombol 'Set Main'
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // 1. Ambil semua gambar yang terkait dengan produk ini
        $images = $product->images;

        foreach ($images as $image) {
            // 2. Hapus file fisik dari storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // 3. Hapus data produk (dan otomatis images jika onDelete cascade aktif)
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk dan semua fotonya berhasil dihapus!');
    }

    /**
     * Fitur Manajemen Gambar Spesifik
     */

    public function setPrimary($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Reset semua gambar produk ini menjadi false (bukan utama)
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        
        // Set gambar yang dipilih menjadi true (utama)
        $image->update(['is_primary' => true]);
        
        return back()->with('success', 'Gambar utama berhasil diubah!');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Jangan hapus jika ini adalah foto satu-satunya
        $remainingImages = ProductImage::where('product_id', $image->product_id)->count();
        if ($remainingImages <= 1) {
            return back()->with('error', 'Produk minimal harus memiliki satu foto.');
        }

        // Hapus file fisik
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Hapus data database
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
}