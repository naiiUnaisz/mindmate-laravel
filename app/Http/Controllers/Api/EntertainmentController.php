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

        // Rumus kenaikan harga: BasePrice * 1.5^(jumlah beli - 1)
        $actualCoinCost = (int)($app->coin_cost * pow(1.5, $purchaseCountToday));

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


    public function sessionStart(Request $request)
    {
        $request->validate([
            'app_id' => 'required|integer|exists:apps,id',
            'duration' => 'nullable|integer|min:1|max:180',
        ]);

        $user = $request->user();
        $app = App::findOrFail($request->app_id);
        $duration = $request->duration ?? $app->duration_minutes;

        $purchaseCountToday = EntertainmentLog::where('user_id', $user->id)
            ->where('app_id', $app->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $actualCoinCost = (int)($app->coin_cost * pow(1.5, $purchaseCountToday));

        if ($user->coin_balance < $actualCoinCost) {
            return response()->json([
                'success' => false,
                'message' => "Koin tidak cukup! Harga: {$actualCoinCost} koin."
            ], 400);
        }

        $activeSession = EntertainmentLog::where('user_id', $user->id)
            ->where('status', 'playing')
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Masih ada sesi hiburan yang aktif.'
            ], 400);
        }

        $user->decrement('coin_balance', $actualCoinCost);

        $startTime = now();
        $endTime = now()->addMinutes($duration);

        $log = EntertainmentLog::create([
            'user_id' => $user->id,
            'app_id' => $app->id,
            'started_at' => $startTime,
            'expired_at' => $endTime,
            'status' => 'playing',
        ]);

        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $actualCoinCost,
            'status' => 'expense',
            'source_type' => EntertainmentLog::class,
            'source_id' => $log->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesi hiburan dimulai!',
            'data' => [
                'session_id' => $log->id,
                'app_name' => $app->title,
                'app_url' => $app->url,
                'duration_minutes' => $duration,
                'start_time' => $startTime->toDateTimeString(),
                'expired_at' => $endTime->toDateTimeString(),
                'coins_spent' => $actualCoinCost,
                'current_coin_balance' => $user->coin_balance,
            ],
        ]);
    }

    public function sessionEnd(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer|exists:entertainment_logs,id',
            'late_minutes' => 'nullable|integer|min:0',
        ]);

        $user = $request->user();

        $session = EntertainmentLog::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak ditemukan.'
            ], 404);
        }

        if ($session->status !== 'playing') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi sudah diakhiri sebelumnya.'
            ], 400);
        }

        $lateMinutes = $request->late_minutes ?? 0;

        if ($lateMinutes > 0) {
            $fineAmount = $lateMinutes * 5;

            if ($user->coin_balance < $fineAmount) {
                return response()->json([
                    'success' => false,
                    'message' => "Koin tidak cukup untuk denda {$fineAmount}! Saldo kamu: {$user->coin_balance}. Perpanjang sesi atau selesaikan tugas dulu."
                ], 400);
            }

            $user->decrement('coin_balance', $fineAmount);

            $session->update(['status' => 'fined']);

            $punishment = Punishment::create([
                'user_id' => $user->id,
                'entertainment_log_id' => $session->id,
                'fine_amount' => $fineAmount,
                'reason' => "Telat absen {$lateMinutes} menit.",
            ]);

            CoinHistories::create([
                'user_id' => $user->id,
                'amount' => $fineAmount,
                'status' => 'expense',
                'source_type' => Punishment::class,
                'source_id' => $punishment->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Kamu telat {$lateMinutes} menit. Koin dipotong {$fineAmount}.",
                'data' => [
                    'status' => 'fined',
                    'fine_amount' => $fineAmount,
                    'current_coin_balance' => $user->coin_balance,
                ],
            ]);
        }

        $session->update(['status' => 'absen_success']);

        return response()->json([
            'success' => true,
            'message' => 'Sesi selesai tepat waktu!',
            'data' => [
                'status' => 'absen_success',
                'current_coin_balance' => $user->coin_balance,
            ],
        ]);
    }

    public function activeSession(Request $request)
    {
        $user = $request->user();

        $session = EntertainmentLog::with('app')
            ->where('user_id', $user->id)
            ->where('status', 'playing')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'session_id' => $session->id,
                'app_id' => $session->app_id,
                'app_name' => $session->app->title,
                'app_url' => $session->app->url,
                'start_time' => $session->started_at->toDateTimeString(),
                'expired_at' => $session->expired_at->toDateTimeString(),
                'remaining_minutes' => max(0, now()->diffInMinutes($session->expired_at, false)),
                'status' => $session->status,
            ],
        ]);
    }

    public function sessionHistory(Request $request)
    {
        $user = $request->user();

        $sessions = EntertainmentLog::with('app', 'punishment')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = $sessions->map(function ($session) {
            return [
                'session_id' => $session->id,
                'app_name' => $session->app->title ?? 'Unknown',
                'start_time' => $session->started_at?->toDateTimeString(),
                'expired_at' => $session->expired_at?->toDateTimeString(),
                'status' => $session->status,
                'fine_amount' => $session->punishment?->fine_amount,
                'created_at' => $session->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'per_page' => $sessions->perPage(),
                'total' => $sessions->total(),
            ],
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

        if ($now->gt($expiredAt)) {
            $minutesLate = $now->diffInMinutes($expiredAt);

            if ($minutesLate == 0) {
                $activeSession->update(['status' => 'absen_success']);

                return response()->json([
                    'success' => true,
                    'message' => 'Kamu baru telat beberapa detik, masih kami anggap tepat waktu. Sesi bermain ditutup.',
                    'data' => [
                        'status' => 'absen_success',
                        'current_coin_balance' => $user->coin_balance
                    ]
                ]);
            }

            $fineAmount = $minutesLate * 5;

            if ($user->coin_balance < $fineAmount) {
                return response()->json([
                    'success' => false,
                    'message' => "Koin tidak cukup untuk denda {$fineAmount}! Saldo kamu: {$user->coin_balance}."
                ], 400);
            }

            $user->decrement('coin_balance', $fineAmount);

            $activeSession->update(['status' => 'fined']);

            $punishment = Punishment::create([
                'user_id' => $user->id,
                'entertainment_log_id' => $activeSession->id,
                'fine_amount' => $fineAmount,
                'reason' => "Telat absen bermain selama {$minutesLate} menit.",
            ]);

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
