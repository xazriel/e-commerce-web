<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        $postMaxBytes  = $this->parseIniSize(ini_get('post_max_size'));

        if ($contentLength > 0 && empty($_FILES) && $contentLength > $postMaxBytes) {
            $sentMB = round($contentLength / 1024 / 1024, 1);
            $maxMB  = round($postMaxBytes / 1024 / 1024, 1);

            Log::warning('Upload ditolak: melampaui post_max_size', [
                'content_length_mb' => $sentMB,
                'post_max_size_mb'  => $maxMB,
            ]);

            return redirect()->back()
                ->with('error', "❌ File terlalu besar! Kamu mengirim {$sentMB}MB, batas server adalah {$maxMB}MB.")
                ->withInput();
        }

        if ($request->hasFile('image') && !$request->file('image')->isValid()) {
            $errorCode = $request->file('image')->getError();

            Log::warning('Upload file image tidak valid', [
                'error_code' => $errorCode,
                'error_msg'  => $this->getUploadErrorMessage($errorCode),
            ]);

            return redirect()->back()
                ->with('error', '❌ ' . $this->getUploadErrorMessage($errorCode))
                ->withInput();
        }

        if ($request->hasFile('image_mobile') && !$request->file('image_mobile')->isValid()) {
            $errorCode = $request->file('image_mobile')->getError();

            return redirect()->back()
                ->with('error', '❌ Mobile: ' . $this->getUploadErrorMessage($errorCode))
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4|max:102400',
            'image_mobile' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4|max:102400',
            'title'        => 'nullable|string|max:255',
        ], [
            'image.required' => 'Pilih file video atau gambar terlebih dahulu.',
            'image.max'      => 'Ukuran file melebihi 100MB. Kompres video kamu terlebih dahulu.',
            'image.mimes'    => 'Format tidak didukung! Gunakan MP4, JPG, PNG, WebP, atau GIF.',
            'image.file'     => 'File tidak valid atau korup.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!$request->hasFile('image') && !$request->hasFile('image_mobile')) {
            return redirect()->back()
                ->with('error', '❌ Pilih minimal satu file (desktop atau mobile) terlebih dahulu.')
                ->withInput();
        }

        try {
            $imagePath  = null;
            $videoPath  = null;
            $mobilePath = null;
            $type       = 'image';

            // --- Proses file utama (Desktop) ---
            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $extension = strtolower($file->getClientOriginalExtension());

                $storedPath = $file->store('sliders', 'public');

                if (!$storedPath) {
                    throw new \RuntimeException('Gagal menyimpan file utama ke storage. Cek permission folder storage/app/public/');
                }

                // FIX: Pisahkan path video dan image
                if ($extension === 'mp4') {
                    $type      = 'video';
                    $videoPath = $storedPath; // MP4 masuk ke video_path
                } else {
                    $type      = 'image';
                    $imagePath = $storedPath; // Gambar masuk ke image_path
                }

                Log::info('File utama berhasil disimpan', [
                    'path' => $storedPath,
                    'type' => $type,
                    'size' => $file->getSize(),
                ]);
            }

            // --- Proses file mobile (opsional) ---
            if ($request->hasFile('image_mobile')) {
                $mobileFile = $request->file('image_mobile');
                $mobilePath = $mobileFile->store('sliders/mobile', 'public');

                if (!$mobilePath) {
                    throw new \RuntimeException('Gagal menyimpan file mobile ke storage.');
                }

                Log::info('File mobile berhasil disimpan', ['path' => $mobilePath]);
            }

            // --- Simpan ke database ---
            Slider::create([
                'image_path'        => $imagePath,  // null jika upload video
                'video_path'        => $videoPath,  // null jika upload gambar
                'image_mobile_path' => $mobilePath,
                'title'             => $request->input('title'),
                'type'              => $type,
                'is_active'         => true,
                'order'             => Slider::count() + 1,
            ]);

            return redirect()->back()->with('success', '✅ Konten berhasil diunggah!');

        } catch (\RuntimeException $e) {
            Log::error('Runtime error saat upload: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', '❌ ' . $e->getMessage())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Exception tidak terduga saat upload slider', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->back()
                ->with('error', '❌ Kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Slider $slider)
    {
        try {
            if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
                Storage::disk('public')->delete($slider->image_path);
            }

            // FIX: Tambah hapus video_path saat delete
            if ($slider->video_path && Storage::disk('public')->exists($slider->video_path)) {
                Storage::disk('public')->delete($slider->video_path);
            }

            if ($slider->image_mobile_path && Storage::disk('public')->exists($slider->image_mobile_path)) {
                Storage::disk('public')->delete($slider->image_mobile_path);
            }

            $slider->delete();

            Log::info('Slider dihapus', ['id' => $slider->id, 'title' => $slider->title]);

            return redirect()->back()->with('success', '✅ Banner berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Gagal menghapus slider', [
                'id'      => $slider->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', '❌ Gagal menghapus: ' . $e->getMessage());
        }
    }

    private function parseIniSize(string $size): int
    {
        $size = trim($size);
        $unit = strtoupper(substr($size, -1));
        $val  = (int) $size;

        return match ($unit) {
            'G' => $val * 1024 * 1024 * 1024,
            'M' => $val * 1024 * 1024,
            'K' => $val * 1024,
            default => $val,
        };
    }

    private function getUploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE   => "File terlalu besar (melampaui batas upload_max_filesize di php.ini).",
            UPLOAD_ERR_FORM_SIZE  => "File terlalu besar (melampaui MAX_FILE_SIZE di form HTML).",
            UPLOAD_ERR_PARTIAL    => "File hanya terunggah sebagian — koneksi internet tidak stabil, coba lagi.",
            UPLOAD_ERR_NO_FILE    => "Tidak ada file yang dipilih.",
            UPLOAD_ERR_NO_TMP_DIR => "Server tidak memiliki folder sementara untuk upload. Hubungi admin server.",
            UPLOAD_ERR_CANT_WRITE => "Gagal menulis file ke disk server. Cek permission folder storage.",
            UPLOAD_ERR_EXTENSION  => "Upload dihentikan oleh ekstensi PHP di server.",
            default               => "Kesalahan upload tidak dikenal (kode error: {$code}).",
        };
    }
}