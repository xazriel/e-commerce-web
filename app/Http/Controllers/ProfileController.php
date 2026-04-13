<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order; // Pastikan ini ditambahkan agar tidak error
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display the detail of a specific order.
     */
    public function orderDetail($order_number): View
    {
        // Mengambil data order berdasarkan nomor order dan user yang sedang login
        // Eager loading 'items.product' agar tidak berat saat load data di view
        $order = Order::with(['items.product'])
            ->where('order_number', $order_number)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Update the user's shipping address and Komerce location ID.
     */
    public function updateAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'destination_id' => ['required', 'string'], // ID internal Komerce
            'destination_name' => ['required', 'string'], // Label lokasi (e.g., Senen, Jakarta Pusat)
        ]);

        $request->user()->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'destination_id' => $request->destination_id,
            'destination_name' => $request->destination_name,
        ]);

        // Redirect ke dashboard karena form delivery info ada di sana
        return Redirect::route('dashboard')->with('status', 'address-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}