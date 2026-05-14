<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('address.index', compact('addresses'));
    }

    public function create()
    {
        return view('address.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'destination_id' => 'required|string',
            'address_label'  => 'nullable|string|max:255',
            'city_name'      => 'nullable|string|max:255',
            'zip_code'       => 'nullable|string|max:10',
            'postal_code'    => 'nullable|string|max:10',
        ]);

        $user    = Auth::user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'recipient_name' => $request->recipient_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'destination_id' => $request->destination_id,
            'address_label'  => $request->address_label,
            'city_name'      => $request->city_name,
            'zip_code'       => $request->zip_code,
            'postal_code'    => $request->postal_code ?? $request->zip_code,
            'is_default'     => $isFirst,
        ]);

        if ($request->has('from_checkout')) {
            return redirect()->route('checkout.index')->with('success', 'Alamat berhasil ditambahkan.');
        }

        return redirect()->route('address.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function select($id)
    {
        $user    = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        if (request()->has('from_checkout')) {
            return redirect()->route('checkout.index')->with('success', 'Alamat pengiriman diperbarui.');
        }

        return redirect()->back()->with('success', 'Alamat utama diperbarui.');
    }

    public function edit($id)
{
    $address = Auth::user()->addresses()->findOrFail($id);
    return response()->json($address);
}

public function update(Request $request, $id)
{
    $request->validate([
        'recipient_name' => 'required|string|max:255',
        'phone'          => 'required|string|max:20',
        'address'        => 'required|string',
        'destination_id' => 'required|string',
        'address_label'  => 'nullable|string|max:255',
        'city_name'      => 'nullable|string|max:255',
        'zip_code'       => 'nullable|string|max:10',
    ]);

    $address = Auth::user()->addresses()->findOrFail($id);

    $address->update([
        'recipient_name' => $request->recipient_name,
        'phone'          => $request->phone,
        'address'        => $request->address,
        'destination_id' => $request->destination_id,
        'address_label'  => $request->address_label,
        'city_name'      => $request->city_name,
        'zip_code'       => $request->zip_code,
        'postal_code'    => $request->zip_code,
    ]);

    if ($request->boolean('is_default')) {
        Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);
    }

    return response()->json(['success' => true, 'address' => $address->fresh()]);
    }

    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);

        if ($address->is_default) {
            $address->delete();
            $next = Auth::user()->addresses()->first();
            if ($next) $next->update(['is_default' => true]);
        } else {
            $address->delete();
        }

        return redirect()->back()->with('success', 'Alamat dihapus.');
    }
}