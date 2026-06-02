<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\CoinHistories;
use App\Models\EntertainmentLog;
use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EntertainmentController extends Controller
{
    // Menampilkan semua daftar aplikasi hiburan 
    public function index()
    {
        $apps = App::all();
        return response()->json([
            'success' => true,
            'data' => $apps
        ]);
    }

    // Membeli waktu hiburan menggunakan koin
    public function purchase(Request $request, $id)
    {
        $user = $request->user();
        $app = App::findOrFail($id);

        // Hitung berapa kali user sudah membeli aplikasi ini pada hari yang sama
        $purchaseCountToday = EntertainmentLog::where('user_id', $user->id)
            ->where('app_id', $app->id)
            // carbon = menghidupkan waktu, yg tadinya berupa catatan teks biasa dengan carbon dia jadi bisa dipake buat itung"an logika
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Rumus kenaikan harga: Harga asli dikali 2 pangkat jumlah pembelian hari ini
        $actualCoinCost = $app->coin_cost * pow(2, $purchaseCountToday);

        // Mengecek apakah koin user cukup dengan harga yang sudah inflasi
        if ($user->coin_balance < $actualCoinCost) {
            return response()->json([
                'success' => false,
                'message' => "Koin kamu tidak cukup! Harga aplikasi ini sudah naik menjadi {$actualCoinCost} koin karena faktor inflasi harian. Selesaikan tugas dulu yuk."
            ], 400);
        }

        // Mngecek apakah user sedang ada sesi bermain yang belum selesai 
        $activeSession = EntertainmentLog::where('user_id', $user->id)
            ->where('status', 'playing')
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu masih punya sesi hiburan yang aktif!'
            ], 400);
        }

        // Potong koin user
        // decrement = mengurangi nilai angka di database secara instan dan aman
        $user->decrement('coin_balance', $actualCoinCost);

        //  Catat sesi bermain di Entertainment Log
        $startTime = now();
        $endTime = now()->addMinutes($app->duration_minutes); // Hitung otomatis ketika waktu main habis

        $log = EntertainmentLog::create([
            'user_id' => $user->id,
            'app_id' => $app->id,
            'started_at' => $startTime,
            'expired_at' => $endTime,
            'status' => 'playing', 
        ]);

        // Catat pengeluaran koin ke CoinHistori
        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $actualCoinCost,
            'status' => 'expense', // Pengeluaran
            'source_type' => EntertainmentLog::class,
            'source_id' => $log->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil! Selamat bersantai.',
            'data' => [
                'app_name' => $app->title,
                'deep_link_url' => $app->url, 
                'duration_minutes' => $app->duration_minutes,
                'expired_at' => $endTime->toDateTimeString(),
                'coins_spent' => $actualCoinCost,
                'current_coin_balance' => $user->coin_balance
            ]
        ]);
    }


    public function completeSession(Request $request)
    {
        $user = $request->user();

        // Cari sesi bermain yang sedang aktif
        $activeSession = EntertainmentLog::where('user_id', $user->id)
            ->where('status', 'playing')
            ->first();

        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak memiliki sesi hiburan yang aktif saat ini.'
            ], 400);
        }

        $now = now();
        $expiredAt = Carbon::parse($activeSession->expired_at);

        // Cek apakah user telat absen (lewat dari waktu expired)
        if ($now->gt($expiredAt)) {
            // Hitung menit keterlambatan
            $minutesLate = $now->diffInMinutes($expiredAt);
            
            // Aturan denda: misal 5 koin per menit telat
            $fineAmount = $minutesLate * 5; 

            // Potong saldo koin user 
            $user->decrement('coin_balance', $fineAmount);

            // Ubah status log jadi kena denda
            $activeSession->update(['status' => 'fined']);

            // Catat ke tabel Punishments
            $punishment = Punishment::create([
                'user_id' => $user->id,
                'entertainment_log_id' => $activeSession->id,
                'fine_amount' => $fineAmount,
                'reason' => "Telat absen bermain selama {$minutesLate} menit.",
            ]);

            // Catat pengeluaran denda ke CoinHistori
            CoinHistories::create([
                'user_id' => $user->id,
                'amount' => $fineAmount,
                'status' => 'expense',
                'source_type' => Punishment::class,
                'source_id' => $punishment->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Waduh! Kamu telat absen {$minutesLate} menit. Koin kamu dipotong {$fineAmount} sebagai denda.",
                'data' => [
                    'status' => 'fined',
                    'fine_amount' => $fineAmount,
                    'current_coin_balance' => $user->coin_balance
                ]
            ]);
        }

        // JIKA TEPAT WAKTU (Aman)
        $activeSession->update(['status' => 'absen_success']);

        return response()->json([
            'success' => true,
            'message' => 'Hebat! Kamu disiplin dan absen tepat waktu. Sesi bermain ditutup.',
            'data' => [
                'status' => 'absen_success',
                'current_coin_balance' => $user->coin_balance
            ]
        ]);
    }
}
