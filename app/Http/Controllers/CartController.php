<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    /**
     * Menambah produk ke keranjang
     */
    public function add(Request $request, $id)
    {
        $product = Product::with(['images' => function($q) {
            $q->where('is_primary', true);
        }])->findOrFail($id);

        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambah quantity-nya
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika produk baru, masukkan ke session
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->images->first()->image_path ?? null,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity atau hapus item (opsional untuk nanti)
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
}