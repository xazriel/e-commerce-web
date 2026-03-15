<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // Load variants dan tags agar bisa dimonitor di dashboard admin
        $products = Product::with(['category', 'images', 'variants', 'tags'])->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all(); // Mengambil daftar label yang ada
        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            // Validasi Varian
            'variant_color' => 'required|array',
            'variant_size' => 'required|array',
            'variant_stock' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan data produk utama (tanpa kolom stock lama)
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                // Kolom stock dihilangkan karena pindah ke tabel variants
            ]);

            // 2. Simpan Varian (Stok per Warna & Size)
            foreach ($request->variant_color as $index => $color) {
                if (!empty($color)) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $request->variant_size[$index],
                        'stock' => $request->variant_stock[$index] ?? 0,
                    ]);
                }
            }

            // 3. Simpan Tags (Label seperti New Arrival/Koleksi)
            if ($request->has('tags')) {
                $product->tags()->sync($request->tags);
            }

            // 4. Simpan Gambar dengan Color Mapping
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'color' => $request->image_colors[$key] ?? null, // Simpan warna per gambar
                        'is_primary' => $key === 0
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk, Varian, & Foto berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $product->load(['images', 'variants', 'tags']); 
        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required',
            'variant_color' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data teks utama
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'description' => $request->description,
            ]);

            // 2. Update Varian (Hapus yang lama, simpan yang baru)
            $product->variants()->delete();
            foreach ($request->variant_color as $index => $color) {
                if (!empty($color)) {
                    $product->variants()->create([
                        'color' => $color,
                        'size' => $request->variant_size[$index],
                        'stock' => $request->variant_stock[$index] ?? 0,
                    ]);
                }
            }

            // 3. Update Tags
            $product->tags()->sync($request->tags ?? []);

            // 4. Tambah foto baru jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'color' => $request->image_colors_new[$key] ?? null,
                        'is_primary' => false
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $images = $product->images;
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Karena onDelete('cascade'), variants, images, dan product_tag otomatis terhapus
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // Fitur Management Gambar (Set Primary & Delete) tetap sama
    public function setPrimary($id)
    {
        $image = ProductImage::findOrFail($id);
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        return back()->with('success', 'Gambar utama berhasil diubah!');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $remainingImages = ProductImage::where('product_id', $image->product_id)->count();
        if ($remainingImages <= 1) {
            return back()->with('error', 'Produk minimal harus memiliki satu foto.');
        }

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }
}