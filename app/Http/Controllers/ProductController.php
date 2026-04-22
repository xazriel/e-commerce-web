<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\SizeGuideTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Tampilan untuk Admin: Daftar Produk
     */
    public function index()
    {
        $products = Product::with(['category', 'images', 'variants', 'sizeGuide'])->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Tampilan untuk User: Detail Produk (Frontend)
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variants', 'sizeGuide'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('details', compact('product')); 
    }

    /**
     * Form Tambah Produk (Admin)
     */
    public function create()
    {
        $categories = Category::all();
        $templates = SizeGuideTemplate::orderBy('name')->get(); 
        
        // Kita kirimkan dua variabel agar cocok dengan file create maupun edit jika berbeda
        $sizeGuides = $templates; 
        
        return view('admin.products.create', compact('categories', 'templates', 'sizeGuides'));
    }

    /**
     * Simpan Produk Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'variant_color' => 'required|array',
            'variant_size' => 'required|array',
            'variant_stock' => 'required|array',
            'release_date' => 'nullable|date',
            'size_guide_template_id' => 'nullable|exists:size_guide_templates,id',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'category_id' => $request->category_id,
                'size_guide_template_id' => $request->size_guide_template_id,
                'custom_size_guide' => $request->custom_size_guide,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'is_preorder' => $request->has('is_preorder'),
                'is_limited' => $request->has('is_limited'),
                'release_date' => $request->release_date,
                'custom_tag' => $request->custom_tag,
            ]);

            foreach ($request->variant_color as $index => $color) {
                if (!empty($color)) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'size' => $request->variant_size[$index],
                        'stock' => $request->variant_stock[$index] ?? 0,
                        'additional_price' => $request->additional_price[$index] ?? 0,
                    ]);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'color' => $request->image_colors[$key] ?? null,
                        'is_primary' => $key === 0
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk Berhasil Disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form Edit Produk (Admin)
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $templates = SizeGuideTemplate::orderBy('name', 'asc')->get();
        
        // Di file edit.blade.php kamu baris 37 memanggil $sizeGuides
        // Jadi kita definisikan variabel $sizeGuides di sini
        $sizeGuides = $templates;

        $product->load(['images', 'variants', 'sizeGuide']);
        
        return view('admin.products.edit', compact('product', 'categories', 'templates', 'sizeGuides'));
    }

    /**
     * Update Produk
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required',
            'release_date' => 'nullable|date',
            'size_guide_template_id' => 'nullable|exists:size_guide_templates,id',
        ]);

        DB::beginTransaction();
        try {
            $product->update([
                'category_id' => $request->category_id,
                'size_guide_template_id' => $request->size_guide_template_id,
                'custom_size_guide' => $request->custom_size_guide,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'description' => $request->description,
                'is_preorder' => $request->has('is_preorder'),
                'is_limited' => $request->has('is_limited'),
                'release_date' => $request->release_date,
                'custom_tag' => $request->custom_tag,
            ]);

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
                            'additional_price' => $request->additional_price[$index] ?? 0,
                        ];

                        if (!empty($vId)) {
                            ProductVariant::where('id', $vId)->where('product_id', $product->id)->update($variantData);
                        } else {
                            $product->variants()->create($variantData);
                        }
                    }
                }
            }

            if ($request->has('existing_image_ids')) {
                foreach ($request->existing_image_ids as $index => $imageId) {
                    ProductImage::where('id', $imageId)->where('product_id', $product->id)->update([
                        'color' => $request->existing_image_colors[$index] ?? null
                    ]);
                }
            }

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
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Produk
     */
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

    /**
     * Set Gambar Utama
     */
    public function setPrimary($id)
    {
        $image = ProductImage::findOrFail($id);
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        return back()->with('success', 'Gambar utama berhasil diubah!');
    }

    /**
     * Hapus Salah Satu Gambar
     */
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