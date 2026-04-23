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
     * Menambah produk ke keranjang dengan deteksi gambar warna yang akurat.
     */
    public function add(Request $request, $id)
    {
        // 1. Ambil variant_id dan quantity
        $variantId = $request->input('variant_id'); 
        $quantityToAdd = (int) $request->input('quantity', 1);

        if (!$variantId) {
            return redirect()->back()->with('error', 'Silakan pilih ukuran/warna terlebih dahulu.');
        }

        // 2. Ambil data varian dan relasi gambar produknya
        $variant = ProductVariant::with('product.images')->findOrFail($variantId);
        $product = $variant->product;

        // Validasi stok
        if ($variant->stock < $quantityToAdd) {
            return redirect()->back()->with('error', "Maaf, stok hanya tersisa {$variant->stock}.");
        }

        $cart = session()->get('cart', []);

        // --- LOGIKA FIX GAMBAR BERDASARKAN WARNA (SENSITIVE FIX) ---
        $selectedColor = trim($variant->color); 

        // Mencari gambar yang memiliki warna sama (Case Insensitive & Trim Spasi)
        $colorSpecificImage = $product->images->filter(function($img) use ($selectedColor) {
            return strtolower(trim($img->color)) === strtolower($selectedColor);
        })->first();

        // Tentukan gambar yang akan disimpan di session
        if ($colorSpecificImage) {
            $imageToDisplay = $colorSpecificImage->image_path;
        } else {
            // Fallback 1: Cari yang is_primary
            $primaryImage = $product->images->where('is_primary', true)->first();
            // Fallback 2: Ambil gambar apa saja yang tersedia jika primary tidak ada
            $imageToDisplay = $primaryImage ? $primaryImage->image_path : ($product->images->first()->image_path ?? null);
        }
        // ----------------------------------------------------------

        // 3. Masukkan ke Session Cart menggunakan variant_id sebagai key
        if(isset($cart[$variantId])) {
            // Cek stok gabungan
            if ($variant->stock < ($cart[$variantId]['quantity'] + $quantityToAdd)) {
                return redirect()->back()->with('error', "Total di keranjang melebihi stok.");
            }
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            $cart[$variantId] = [
                "product_id" => $product->id,
                "name"       => $product->name,
                "variant_id" => $variant->id,
                "quantity"   => $quantityToAdd,
                "price"      => $product->price,
                "size"       => $variant->size,
                "color"      => $variant->color,
                "image"      => $imageToDisplay, // Path gambar hasil deteksi warna
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
     * Update quantity via AJAX (jika ada fitur tambah/kurang di halaman cart)
     */
    // Contoh logic di CartController.php
public function update(Request $request, $id)
{
    $cart = session()->get('cart');

    if(isset($cart[$id])) {
        $variant = \App\Models\ProductVariant::find($id);

        if($request->action == 'increase') {
            if ($variant && $variant->stock > $cart[$id]['quantity']) {
                $cart[$id]['quantity']++;
            } else {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.'], 400);
            }
        } elseif($request->action == 'decrease' && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        }
        
        session()->put('cart', $cart);

        // Hitung total keseluruhan baru untuk dikirim ke JS
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'newQty' => $cart[$id]['quantity'],
            'itemSubtotal' => 'Rp ' . number_format($cart[$id]['price'] * $cart[$id]['quantity'], 0, ',', '.'),
            'cartTotal' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }

    return response()->json(['success' => false], 404);
}
}