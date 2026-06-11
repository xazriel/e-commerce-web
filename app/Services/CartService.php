<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get the cart items formatted consistently.
     */
    public static function getCart(): array
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = CartItem::with(['productVariant.product.images'])
                ->where('user_id', $userId)
                ->get();

            $cart = [];
            foreach ($cartItems as $item) {
                $variant = $item->productVariant;
                if (!$variant) continue;
                $product = $variant->product;
                if (!$product) continue;

                // Determine image based on variant color
                $selectedColor = trim($variant->color);
                $colorSpecificImage = $product->images->first(function ($img) use ($selectedColor) {
                    return strtolower(trim($img->color)) === strtolower($selectedColor);
                });

                if ($colorSpecificImage) {
                    $imageToDisplay = $colorSpecificImage->image_path;
                } else {
                    $primaryImage = $product->images->where('is_primary', true)->first();
                    $imageToDisplay = $primaryImage
                        ? $primaryImage->image_path
                        : ($product->images->first()->image_path ?? null);
                }

                $finalPrice = $product->price + ($variant->additional_price ?? 0);

                $cart[$variant->id] = [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'variant_id'   => $variant->id,
                    'quantity'     => $item->quantity,
                    'price'        => $finalPrice,
                    'size'         => $variant->size,
                    'color'        => $variant->color,
                    'image'        => $imageToDisplay,
                    'slug'         => $product->slug,
                    'is_preorder'  => (bool) $product->is_preorder,
                    'release_date' => $product->release_date?->toDateTimeString(),
                ];
            }
            return $cart;
        }

        return session()->get('cart', []);
    }

    /**
     * Add an item to the cart.
     */
    public static function add(int $variantId, int $quantityToAdd): bool
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $item = CartItem::where('user_id', $userId)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($item) {
                $item->quantity += $quantityToAdd;
                $item->save();
            } else {
                CartItem::create([
                    'user_id'            => $userId,
                    'product_variant_id' => $variantId,
                    'quantity'           => $quantityToAdd,
                ]);
            }
            return true;
        }

        $cart = session()->get('cart', []);
        $variant = ProductVariant::with('product')->findOrFail($variantId);
        $product = $variant->product;
        
        $selectedColor = trim($variant->color);
        $colorSpecificImage = $product->images->first(function ($img) use ($selectedColor) {
            return strtolower(trim($img->color)) === strtolower($selectedColor);
        });

        if ($colorSpecificImage) {
            $imageToDisplay = $colorSpecificImage->image_path;
        } else {
            $primaryImage = $product->images->where('is_primary', true)->first();
            $imageToDisplay = $primaryImage
                ? $primaryImage->image_path
                : ($product->images->first()->image_path ?? null);
        }

        $finalPrice = $product->price + ($variant->additional_price ?? 0);

        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            $cart[$variantId] = [
                'product_id'   => $product->id,
                'name'         => $product->name,
                'variant_id'   => $variantId,
                'quantity'     => $quantityToAdd,
                'price'        => $finalPrice,
                'size'         => $variant->size,
                'color'        => $variant->color,
                'image'        => $imageToDisplay,
                'slug'         => $product->slug,
                'is_preorder'  => (bool) $product->is_preorder,
                'release_date' => $product->release_date?->toDateTimeString(),
            ];
        }

        session()->put('cart', $cart);
        return true;
    }

    /**
     * Update an item's quantity in the cart.
     */
    public static function update(int $variantId, string $action): ?array
    {
        $variant = ProductVariant::find($variantId);
        if (!$variant) return null;

        if (Auth::check()) {
            $userId = Auth::id();
            $item = CartItem::where('user_id', $userId)
                ->where('product_variant_id', $variantId)
                ->first();

            if (!$item) return null;

            if ($action === 'increase') {
                $isPreorder = $variant->product->is_preorder ?? false;
                if ($isPreorder || $variant->stock > $item->quantity) {
                    $item->quantity++;
                    $item->save();
                } else {
                    return ['success' => false, 'message' => 'Stok tidak mencukupi.'];
                }
            } elseif ($action === 'decrease' && $item->quantity > 1) {
                $item->quantity--;
                $item->save();
            }

            return ['success' => true, 'quantity' => $item->quantity];
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$variantId])) {
            if ($action === 'increase') {
                $isPreorder = $cart[$variantId]['is_preorder'] ?? false;
                if ($isPreorder || $variant->stock > $cart[$variantId]['quantity']) {
                    $cart[$variantId]['quantity']++;
                } else {
                    return ['success' => false, 'message' => 'Stok tidak mencukupi.'];
                }
            } elseif ($action === 'decrease' && $cart[$variantId]['quantity'] > 1) {
                $cart[$variantId]['quantity']--;
            }

            session()->put('cart', $cart);
            return ['success' => true, 'quantity' => $cart[$variantId]['quantity']];
        }

        return null;
    }

    /**
     * Remove an item from the cart.
     */
    public static function remove(int $variantId): void
    {
        if (Auth::check()) {
            $userId = Auth::id();
            CartItem::where('user_id', $userId)
                ->where('product_variant_id', $variantId)
                ->delete();
            return;
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }
    }

    /**
     * Clear the cart.
     */
    public static function clear(): void
    {
        if (Auth::check()) {
            $userId = Auth::id();
            CartItem::where('user_id', $userId)->delete();
            return;
        }

        session()->forget('cart');
    }

    /**
     * Merge the session cart to database cart on login.
     */
    public static function mergeSessionToDb(int $userId): void
    {
        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $variantId => $details) {
                $dbItem = CartItem::where('user_id', $userId)
                    ->where('product_variant_id', $variantId)
                    ->first();

                if ($dbItem) {
                    // Accumulate or take the larger quantity? Accumulate is standard.
                    $dbItem->quantity += $details['quantity'];
                    $dbItem->save();
                } else {
                    CartItem::create([
                        'user_id'            => $userId,
                        'product_variant_id' => $variantId,
                        'quantity'           => $details['quantity'],
                    ]);
                }
            }
            session()->forget('cart');
        }
    }
}
