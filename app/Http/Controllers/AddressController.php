<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Tampilkan daftar alamat user.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('address.index', compact('addresses'));
    }

    /**
     * Tampilkan form tambah alamat.
     */
    public function create()
    {
        return view('address.create');
    }

    /**
     * Simpan alamat baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'province_name'  => 'nullable|string|max:255', // Persiapan JNE
            'city_name'      => 'required|string|max:255',
            'district_name'  => 'nullable|string|max:255', // Persiapan JNE
            'postal_code'    => 'required|string|max:10',
        ]);

        $user = Auth::user();
        
        // Alamat jadi default jika ini adalah alamat pertama user
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'province_name'  => $request->province_name,
            'city_name'      => $request->city_name,
            'district_name'  => $request->district_name,
            'postal_code'    => $request->postal_code,
            'is_default'     => $isFirst,
        ]);

        // Jika user datang dari halaman checkout, kembalikan ke checkout
        if ($request->has('from_checkout')) {
            return redirect()->route('checkout.index')->with('success', 'Address added successfully!');
        }

        return redirect()->route('address.index')->with('success', 'Address added successfully!');
    }

    /**
     * Set alamat tertentu menjadi alamat utama (default).
     */
    public function select($id)
    {
        $user = Auth::user();

        // Pastikan alamat yang dipilih milik user yang login (Security check)
        $address = $user->addresses()->findOrFail($id);

        // Reset semua alamat user ini jadi tidak default
        $user->addresses()->update(['is_default' => false]);
        
        // Set alamat yang dipilih jadi default
        $address->update(['is_default' => true]);

        return redirect()->route('checkout.index')->with('success', 'Shipping address updated.');
    }

    /**
     * Hapus alamat.
     */
    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        
        // Jika yang dihapus adalah alamat default, set alamat lain jadi default jika ada
        if ($address->is_default) {
            $address->delete();
            $nextAddress = Auth::user()->addresses()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        } else {
            $address->delete();
        }

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
}