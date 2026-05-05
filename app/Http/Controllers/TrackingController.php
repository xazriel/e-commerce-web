<?php

namespace App\Http\Controllers;

use App\Services\JneService;

class TrackingController extends Controller
{
    public function __construct(protected JneService $jne) {}

    public function show(string $awb)
    {
        try {
            $data = $this->jne->trackPackage($awb);

            return view('tracking.show', [
                'cnote'   => $data['cnote']      ?? null,
                'detail'  => $data['detail'][0]  ?? null,
                'history' => $data['history']    ?? [],
                'awb'     => $awb,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Nomor resi tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
 * Melacak riwayat pengiriman berdasarkan nomor resi (AWB)
 */
public function traceHistory($awb)
{
    // Ambil konfigurasi dari .env
    $username = env('JNE_USERNAME');
    $apiKey   = env('JNE_API_KEY');
    
    // URL Sandbox dengan parameter AWB di dalam URL
    $url = "https://apiv2.jne.co.id:10202/tracing/api/list/v1/cnote/{$awb}";

    try {
        $response = Http::asForm()
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post($url, [
                'username' => $username,
                'api_key'  => $apiKey,
            ]);

        $result = $response->json();

        // Cek jika API mengembalikan error (Resi tidak ditemukan/salah user)
        if (isset($result['error']) || (isset($result['status']) && $result['status'] === false)) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Resi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'summary' => $result['cnote'] ?? null,
            'detail'  => $result['detail'] ?? null,
            'history' => $result['history'] ?? []
        ]);

    } catch (\Exception $e) {
        \Log::error("JNE Tracking Error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghubungkan ke server JNE.'
        ], 500);
    }
}

    }