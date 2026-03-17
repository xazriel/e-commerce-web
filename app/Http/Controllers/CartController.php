<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Menambah produk ke keranjang.
     * Mendukung penambahan jumlah (quantity) secara dinamis.
     */
    public function add(Request $request, $id)
    {
        // 1. Ambil variant_id dan quantity dari input form
        $variantId = $request->input('variant_id'); 
        $quantityToAdd = $request->input('quantity', 1); // Default ke 1 jika tidak ada input quantity

        if (!$variantId) {
            return redirect()->back()->with('error', 'Silakan pilih ukuran/warna terlebih dahulu.');
        }

        // 2. Cari data variant beserta produknya
        $variant = ProductVariant::with('product.images')->findOrFail($variantId);
        $product = $variant->product;

        // Cek apakah stok variant mencukupi sebelum masuk ke keranjang
        if ($variant->stock < $quantityToAdd) {
            return redirect()->back()->with('error', "Maaf, stok hanya tersisa {$variant->stock}.");
        }

        $cart = session()->get('cart', []);

        // 3. Gunakan $variantId sebagai KEY session
        if(isset($cart[$variantId])) {
            // JIKA SUDAH ADA: Tambahkan quantity sesuai input (bukan cuma +1)
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            // JIKA BELUM ADA: Masukkan data baru dengan quantity sesuai input
            $cart[$variantId] = [
                "product_id" => $product->id,
                "name"       => $product->name,
                "variant_id" => $variant->id,
                "quantity"   => $quantityToAdd,
                "price"      => $product->price,
                "size"       => $variant->size,
                "color"      => $variant->color,
                "image"      => $product->images->where('is_primary', true)->first()->image_path ?? null,
                "slug"       => $product->slug
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * (Tambahan) Update quantity langsung di halaman Cart
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity) {
            $cart = session()->get('cart');
            
            // Validasi stok real-time saat update di keranjang
            $variant = ProductVariant::find($request->id);
            if($variant->stock < $request->quantity) {
                return response()->json(['error' => "Stok tidak mencukupi"], 400);
            }

            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => "Keranjang diperbarui"]);
        }
    }
}