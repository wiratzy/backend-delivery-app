<?php

namespace App\Http\Controllers;

use App\Models\RestaurantApplication;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk menghapus gambar jika ditolak/error
use Illuminate\Support\Facades\Mail; // Untuk notifikasi email
use App\Mail\RestaurantApplicationStatusMail; // Model Mailable yang akan dibuat
use Illuminate\Support\Facades\Http; // Untuk notifikasi WhatsApp

class RestaurantApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:restaurant_applications,email',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(08\d{8,11}|\+628\d{8,11})$/',
            ],
            'location' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'restaurant_type' => 'required|string|max:255',
            'food_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('restaurant_applications', 'public');
                $data['image'] = $imagePath;
            }

            // Normalisasi nomor telepon sebelum disimpan
            // $normalizedPhone = $data['phone'];
            // if (str_starts_with($normalizedPhone, '08')) {
            //     $normalizedPhone = '+62' . substr($normalizedPhone, 1);
            // }
            // $data['phone'] = $normalizedPhone;

            // Pastikan nama kolom di $data sesuai dengan kolom di tabel `restaurant_applications`
            // yang sudah ditambahkan melalui migrasi.
            $data['type'] = $data['restaurant_type']; // Ambil dari input form 'restaurant_type'
            unset($data['restaurant_type']); // Hapus dari $data karena tidak ada kolom itu di model

            RestaurantApplication::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan restoran Anda berhasil dikirim! Kami akan segera meninjau permohonan Anda.'
            ], 201);
        } catch (QueryException $e) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            if ($e->getCode() == '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ini sudah pernah digunakan untuk mendaftar. Gunakan email lain atau hubungi admin.'
                ], 409);
            }
            \Log::error("Error storing restaurant application: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. (Kode Error: ' . $e->getCode() . ')'
            ], 500);
        } catch (\Exception $e) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            \Log::error("General error storing restaurant application: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan tak terduga. Silakan coba lagi.'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $statusFilter = $request->get('status');

        $query = RestaurantApplication::orderBy('created_at', 'desc');

        if ($statusFilter && $statusFilter != 'all') {
            $query->where('status', $statusFilter);
        }

        $applications = $query->paginate($limit);

        if ($applications->isEmpty() && (!$statusFilter || $statusFilter == 'all')) {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada pengajuan restoran.',
                'data' => $applications // Tetap kembalikan paginator object
            ], 200);
        } elseif ($applications->isEmpty() && $statusFilter && $statusFilter != 'all') {
            return response()->json([
                'success' => true,
                'message' => "Tidak ada pengajuan restoran dengan status '{$statusFilter}'.",
                'data' => $applications
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan restoran berhasil diambil',
            'data' => $applications
        ], 200);
    }

    public function approve($id)
    {
        try {
            $app = RestaurantApplication::findOrFail($id);

            // Periksa apakah email sudah terdaftar sebagai pengguna lain atau pemilik restoran
            if (User::where('email', $app->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ini sudah terdaftar sebagai pengguna lain atau pemilik restoran. Mohon gunakan email berbeda.'
                ], 409);
            }

            // Buat akun user baru dengan role 'owner'
            $user = User::create([
                'name' => $app->name,
                'email' => $app->email,
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'phone' => $app->phone,
                'address' => $app->location,
            ]);

            $newImagePath = null;
            if ($app->image && Storage::disk('public')->exists($app->image)) {
                $originalExtension = pathinfo($app->image, PATHINFO_EXTENSION);
                $imageFilename = 'restaurant_' . $user->id . '.' . $originalExtension;
                $newImagePath = 'restaurants/' . $imageFilename;

                Storage::disk('public')->move($app->image, $newImagePath);
            } else {
                $newImagePath = 'default_restaurant.png';
            }
            // Buat data restoran
            Restaurant::create([
                'owner_id' => $user->id,
                'name' => $app->name,
                'image' => $app->image,
                'type' => $app->type,
                'food_type' => $app->food_type,
                'location' => $app->location,
                'delivery_fee' => 5000.00,
                'is_most_popular' => false,
                'rate' => 0.0,
                'rating' => 0,
            ]);

            $app->update(['status' => 'approved']);

            Mail::to($app->email)->send(new RestaurantApplicationStatusMail(
                $app->name,
                $app->email,
                $app->phone,
                'approved'
            ));

            $this->sendWhatsAppNotification($app->phone, $app->name, 'approved');

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disetujui, akun restoran dibuat, dan notifikasi terkirim.'
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Error approving restaurant application ID {$id}: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $app = RestaurantApplication::findOrFail($id);

            // Hapus gambar terkait jika pengajuan ditolak untuk menghemat storage
            if ($app->image && Storage::disk('public')->exists($app->image)) {
                Storage::disk('public')->delete($app->image);
            }

            $app->update(['status' => 'rejected']);

            // --- Notifikasi ---
            // 1. Notifikasi Email
            Mail::to($app->email)->send(new RestaurantApplicationStatusMail(
                $app->name,
                $app->email,
                $app->phone,
                'rejected'
            ));

            // 2. Notifikasi WhatsApp (contoh sederhana via API pihak ketiga)
            $this->sendWhatsAppNotification($app->phone, $app->name, 'rejected');

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak dan notifikasi terkirim.'
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Error rejecting restaurant application ID {$id}: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }


    private function sendWhatsAppNotification(string $phoneNumber, string $restaurantName, string $status): bool
    {
        $targetPhoneNumber = str_replace('+', '', $phoneNumber); // Hapus tanda '+'

        $message = '';
        if ($status == 'approved') {
            $message = "Halo *$restaurantName*,\n\nSelamat! Pengajuan akun restoran Anda telah *disetujui*.\n\nAnda dapat login ke aplikasi admin kami menggunakan email Anda (`" . $restaurantName . "` yang terdaftar) dan password default: `password123`.\n\nKami sarankan segera mengubah password Anda setelah login pertama kali. Terima kasih telah bergabung!";
        } else {
            $message = "Halo *$restaurantName*,\n\nKami ingin memberitahukan bahwa pengajuan akun restoran Anda *ditolak*.\n\nMohon maaf, kami tidak dapat memproses pengajuan Anda saat ini. Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim dukungan kami. Terima kasih.";
        }

        // --- INTEGRASI DENGAN FONNTE API ---
        $fonnteApiUrl = env('FONNTE_API_URL', 'https://api.fonnte.com/send');
        $fonnteApiKey = env('FONNTE_API_KEY'); // Pastikan ini diatur di .env

        // Validasi jika API Key atau URL tidak diatur
        if (empty($fonnteApiKey) || empty($fonnteApiUrl)) {
            \Log::warning("Fonnte API Key or URL is not set in .env. WhatsApp notification skipped for {$restaurantName}.");
            return false;
        }

        try {
            $response = Http::asForm()->withHeaders([ // Menggunakan asForm() untuk form-urlencoded
                'Authorization' => $fonnteApiKey, // Header Authorization
            ])->post($fonnteApiUrl, [
                        'target' => $targetPhoneNumber, // Nomor tujuan tanpa '+'
                        'message' => $message,
                    ]);

            if ($response->successful()) {
                \Log::info("WhatsApp notification sent to {$targetPhoneNumber} for {$restaurantName}. Fonnte Response: " . $response->body());
                return true;
            } else {
                \Log::error("Failed to send WhatsApp notification to {$targetPhoneNumber}. Fonnte Response: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            \Log::error("Exception sending WhatsApp notification via Fonnte to {$targetPhoneNumber}: " . $e->getMessage());
            return false;
        }
        // --- AKHIR INTEGRASI FONNTE ---
    }
}
