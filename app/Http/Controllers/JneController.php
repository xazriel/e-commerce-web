<?php

namespace App\Http\Controllers;

use App\Services\JneService;
use Illuminate\Http\Request;

class JneController extends Controller
{
    public function __construct(protected JneService $jne) {}

    // Cek ongkos kirim
    public function checkPrice(Request $request)
    {
        $request->validate([
            'from'   => 'required|string',
            'thru'   => 'required|string',
            'weight' => 'required|integer|min:1',
        ]);

        try {
            $prices = $this->jne->getPrice(
                $request->from,
                $request->thru,
                (int) $request->weight
            );
            return response()->json(['success' => true, 'data' => $prices]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // Generate AWB saat order dibuat
    public function generateAWB(Request $request)
    {
        $validated = $request->validate([
            'order_id'       => 'required|string',
            'shipper_name'   => 'required|string',
            'shipper_addr'   => 'required|string',
            'shipper_city'   => 'required|string',
            'shipper_zip'    => 'required|string',
            'shipper_phone'  => 'required|string',
            'receiver_name'  => 'required|string',
            'receiver_addr'  => 'required|string',
            'receiver_city'  => 'required|string',
            'receiver_zip'   => 'required|string',
            'receiver_phone' => 'required|string',
            'weight'         => 'required|integer|min:1',
            'goods_desc'     => 'required|string',
            'goods_value'    => 'required|integer|min:0',
            'service'        => 'required|string|in:REG,YES,OKE',
            'dest_code'      => 'required|string',
        ]);

        try {
            $result = $this->jne->generateAirwaybill([
                'OLSHOP_BRANCH'         => config('jne.branch'),
                'OLSHOP_CUST'           => config('jne.cust_no'),
                'OLSHOP_ORDERID'        => $validated['order_id'],
                'OLSHOP_SHIPPER_NAME'   => $validated['shipper_name'],
                'OLSHOP_SHIPPER_ADDR1'  => $validated['shipper_addr'],
                'OLSHOP_SHIPPER_ADDR2'  => '-',
                'OLSHOP_SHIPPER_CITY'   => $validated['shipper_city'],
                'OLSHOP_SHIPPER_ZIP'    => $validated['shipper_zip'],
                'OLSHOP_SHIPPER_PHONE'  => $validated['shipper_phone'],
                'OLSHOP_RECEIVER_NAME'  => $validated['receiver_name'],
                'OLSHOP_RECEIVER_ADDR1' => $validated['receiver_addr'],
                'OLSHOP_RECEIVER_ADDR2' => '-',
                'OLSHOP_RECEIVER_CITY'  => $validated['receiver_city'],
                'OLSHOP_RECEIVER_ZIP'   => $validated['receiver_zip'],
                'OLSHOP_RECEIVER_PHONE' => $validated['receiver_phone'],
                'OLSHOP_QTY'            => 1,
                'OLSHOP_WEIGHT'         => $validated['weight'],
                'OLSHOP_GOODSDESC'      => $validated['goods_desc'],
                'OLSHOP_GOODSVALUE'     => $validated['goods_value'],
                'OLSHOP_GOODSTYPE'      => '2',
                'OLSHOP_INST'           => '',
                'OLSHOP_INS_FLAG'       => 'N',
                'OLSHOP_ORIG'           => config('jne.origin_code'),
                'OLSHOP_DEST'           => $validated['dest_code'],
                'OLSHOP_SERVICE'        => $validated['service'],
                'OLSHOP_COD_FLAG'       => 'N',
                'OLSHOP_COD_AMOUNT'     => 0,
            ]);

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function searchLocation(Request $request)
{
    $keyword = $request->q;

    $results = \DB::table('jne_destinations')
        ->where('name', 'like', "%{$keyword}%")
        ->orWhere('city', 'like', "%{$keyword}%")
        ->orWhere('province', 'like', "%{$keyword}%")
        ->limit(20)
        ->get()
        ->map(fn($d) => [
            'id'       => $d->code,
            'text'     => $d->name . ', ' . $d->province,
            'city'     => $d->city,       // ✅ tambahan
            'zip_code' => $d->zip_code,   // ✅ tambahan
        ]);

    return response()->json(['results' => $results]);
}

    // Tracking paket
    public function track(string $awb)
    {
        try {
            $result = $this->jne->trackPackage($awb);
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}