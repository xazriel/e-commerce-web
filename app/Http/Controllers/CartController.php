<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * Jika buy_now=1 → redirect ke checkout.
     * Jika tidak       → redirect ke cart.index.
     */
    public function add(Request $request, $id)
    {
        Log::info('CART.ADD STEP 1', [
            'buy_now'    => $request->input('buy_now'),
            'variant_id' => $request->input('variant_id'),
            'quantity'   => $request->input('quantity'),
            'auth_id'    => auth()->id(),
        ]);

        // 1. Ambil input
        $variantId     = $request->input('variant_id');
        $quantityToAdd = (int) $request->input('quantity', 1);

        if (!$variantId) {
            return redirect()->back()->with('error', 'Silakan pilih ukuran/warna terlebih dahulu.');
        }

        // 2. Ambil variant & product
        $variant = ProductVariant::with('product.images')->findOrFail($variantId);
        $product = $variant->product;

        Log::info('CART.ADD STEP 2', ['variant' => $variantId, 'stock' => $variant->stock]);

        // 3. Validasi stok
        if ($variant->stock < $quantityToAdd) {
            return redirect()->back()->with('error', "Maaf, stok hanya tersisa {$variant->stock}.");
        }

        // 4. Tentukan gambar berdasarkan warna
        $selectedColor      = trim($variant->color);
        $colorSpecificImage = $product->images->first(function ($img) use ($selectedColor) {
            return strtolower(trim($img->color)) === strtolower($selectedColor);
        });

        if ($colorSpecificImage) {
            $imageToDisplay = $colorSpecificImage->image_path;
        } else {
            $primaryImage   = $product->images->where('is_primary', true)->first();
            $imageToDisplay = $primaryImage
                ? $primaryImage->image_path
                : ($product->images->first()->image_path ?? null);
        }

        // 5. Masukkan ke session cart
        $cart = session()->get('cart', []);

        if (isset($cart[$variantId])) {
            if ($variant->stock < ($cart[$variantId]['quantity'] + $quantityToAdd)) {
                return redirect()->back()->with('error', 'Total di keranjang melebihi stok.');
            }
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            $cart[$variantId] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'variant_id' => $variant->id,
                'quantity'   => $quantityToAdd,
                'price'      => $product->price,
                'size'       => $variant->size,
                'color'      => $variant->color,
                'image'      => $imageToDisplay,
                'slug'       => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        Log::info('CART.ADD STEP 3: cart saved', ['buy_now' => $request->input('buy_now')]);

        // 6. Arahkan sesuai tipe tombol
        if ($request->input('buy_now') == '1') {
            Log::info('CART.ADD STEP 4: buy_now detected');

            if (!auth()->check()) {
                Log::info('CART.ADD STEP 4a: not logged in, redirect to login');
                session()->put('url.intended', route('checkout.index'));
                return redirect()->route('login')
                    ->with('info', 'Login dulu untuk melanjutkan checkout.');
            }

            Log::info('CART.ADD STEP 4b: logged in, redirect to checkout', ['user' => auth()->id()]);
            return redirect()->route('checkout.index');
        }

        Log::info('CART.ADD STEP 5: redirect to cart.index');
        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Update quantity via AJAX.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $variant = ProductVariant::find($id);

            if ($request->action == 'increase') {
                if ($variant && $variant->stock > $cart[$id]['quantity']) {
                    $cart[$id]['quantity']++;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi.',
                    ], 400);
                }
            } elseif ($request->action == 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }

            session()->put('cart', $cart);

            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            return response()->json([
                'success'      => true,
                'newQty'       => $cart[$id]['quantity'],
                'itemSubtotal' => 'Rp ' . number_format($cart[$id]['price'] * $cart[$id]['quantity'], 0, ',', '.'),
                'cartTotal'    => 'Rp ' . number_format($total, 0, ',', '.'),
            ]);
        }

        return response()->json(['success' => false], 404);
    }
}