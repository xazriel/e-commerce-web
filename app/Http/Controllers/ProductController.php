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
            // 1. Simpan data produk utama
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
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

            // 4. Simpan Gambar dengan Color Mapping (FIXED)
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'images.*.max' => 'Ukuran salah satu foto terlalu besar (Maksimal 2MB).',
            'images.*.image' => 'File harus berupa gambar.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data utama produk
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'description' => $request->description,
            ]);

            // 2. Kelola Varian (Update, Create, atau Delete)
            $inputVariantIds = array_filter($request->variant_ids ?? []);
            $product->variants()->whereNotIn('id', $inputVariantIds)->delete();

            if ($request->has('variant_color')) {
                foreach ($request->variant_color as $index => $color) {
                    if (!empty($color)) {
                        $vId = $request->variant_ids[$index] ?? null;
                        $variantData = [
                            'color' => $color,
                            'size'  => $request->variant_size[$index] ?? 'All Size',
                            'stock' => $request->variant_stock[$index] ?? 0,
                        ];

                        if (!empty($vId)) {
                            ProductVariant::where('id', $vId)->where('product_id', $product->id)->update($variantData);
                        } else {
                            $product->variants()->create($variantData);
                        }
                    }
                }
            }

            // 3. Update Tags
            $product->tags()->sync($request->tags ?? []);

            // 4. FIX: Update Warna Foto yang SUDAH ADA (Ini yang sebelumnya belum ada)
            if ($request->has('existing_image_ids')) {
                foreach ($request->existing_image_ids as $index => $imageId) {
                    ProductImage::where('id', $imageId)
                        ->where('product_id', $product->id)
                        ->update([
                            'color' => $request->existing_image_colors[$index] ?? null
                        ]);
                }
            }

            // 5. Upload Foto Baru (jika ada)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    if ($image->isValid()) {
                        $path = $image->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $path,
                            'color' => $request->image_colors_new[$key] ?? null,
                            'is_primary' => false
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk dan varian berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
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
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

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