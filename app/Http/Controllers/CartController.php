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
        $cart = \App\Services\CartService::getCart();
        return view('cart.index', compact('cart'));
    }

    /**
     * Menambah produk ke keranjang.
     * Jika buy_now=1 → simpan ke session('buy_now') TERPISAH → redirect ke checkout.
     * Jika tidak       → simpan ke session('cart') → redirect ke cart.index.
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
        $isPreorder = $variant->product->is_preorder;

        if (! $isPreorder && $variant->stock < $quantityToAdd) {
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

        // ── FIX: Harga = harga dasar + additional_price varian ──
        $finalPrice = $product->price + ($variant->additional_price ?? 0);

        // 5. Cek buy_now DULU sebelum menyentuh session cart
        if ($request->input('buy_now') == '1') {
            Log::info('CART.ADD: buy_now detected');

            if (!auth()->check()) {
                Log::info('CART.ADD: not logged in, redirect to login');
                session()->put('url.intended', route('checkout.index'));
                return redirect()->route('login')
                    ->with('info', 'Login dulu untuk melanjutkan checkout.');
            }

            // Simpan ke session TERPISAH — cart tidak disentuh sama sekali
            session()->put('buy_now', [
                $variantId => [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'variant_id'   => $variant->id,
                    'quantity'     => $quantityToAdd,
                    'price'        => $finalPrice, // ← FIX
                    'size'         => $variant->size,
                    'color'        => $variant->color,
                    'image'        => $imageToDisplay,
                    'slug'         => $product->slug,
                    'is_preorder'  => $isPreorder,
                    'release_date' => $product->release_date?->toDateTimeString(),
                ]
            ]);

            Log::info('CART.ADD: buy_now session saved, redirecting to checkout');
            return redirect()->route('checkout.index', ['mode' => 'buy_now']);
        }

        // 6. Bukan buy_now → masuk ke cart seperti biasa
        $currentQty = 0;
        if (auth()->check()) {
            $dbItem = \App\Models\CartItem::where('user_id', auth()->id())
                ->where('product_variant_id', $variantId)
                ->first();
            $currentQty = $dbItem ? $dbItem->quantity : 0;
        } else {
            $cart = session()->get('cart', []);
            $currentQty = isset($cart[$variantId]) ? $cart[$variantId]['quantity'] : 0;
        }

        if (!$isPreorder && $variant->stock < ($currentQty + $quantityToAdd)) {
            return redirect()->back()->with('error', 'Total di keranjang melebihi stok.');
        }

        \App\Services\CartService::add($variantId, $quantityToAdd);

        Log::info('CART.ADD: item added to cart, redirecting to cart.index');
        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($id)
    {
        \App\Services\CartService::remove($id);
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Update quantity via AJAX.
     */
    public function update(Request $request, $id)
    {
        $res = \App\Services\CartService::update($id, $request->action);

        if ($res) {
            if (!$res['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $res['message'],
                ], 400);
            }

            $cart = \App\Services\CartService::getCart();
            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $item = $cart[$id] ?? null;

            if ($item) {
                return response()->json([
                    'success'      => true,
                    'newQty'       => $res['quantity'],
                    'itemSubtotal' => 'Rp ' . number_format($item['price'] * $res['quantity'], 0, ',', '.'),
                    'cartTotal'    => 'Rp ' . number_format($total, 0, ',', '.'),
                ]);
            }
        }

        return response()->json(['success' => false], 404);
    }
}