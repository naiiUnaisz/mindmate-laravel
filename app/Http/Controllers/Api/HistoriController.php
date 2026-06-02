<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinHistories;
use App\Models\DailyTaskItem;
use App\Models\EntertainmentLog;
use App\Models\Punishment;
use App\Models\Task;
use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil riwayat koin milik user yang sedang login, urutkan dari yang paling baru
        // Kita gunakan 'with' untuk me-load data asalnya (apakah dari tugas atau hiburan)
        $histori = CoinHistories::where('user_id', $user->id)
            ->with('source') 
            ->orderBy('created_at', 'desc')
            ->get();

        // Kita petakan (map) datanya agar Frontend (Flutter) menerimanya dengan format yang bersih
        $formattedData = $histori->map(function ($item) {
            $description = 'Aktivitas tidak diketahui';

            // Jika koin berasal dari menyelesaikan tugas
            if ($item->source_type === DailyTaskItem::class) {
                $description = "Menyelesaikan tugas: " . ($item->source->title ?? 'Tugas dihapus');
            } 
            // Jika koin keluar untuk membeli hiburan
            elseif ($item->source_type === EntertainmentLog::class) {
                $description = "Membeli hiburan: " . ($item->source->app->title ?? 'Aplikasi');
            }
            // JIKA KOIN KELUAR KARENA DENDA 
            elseif ($item->source_type === Punishment::class) {
                $description = "Denda: Telat absen hiburan";
            }

            return [
                'id' => $item->id,
                'amount' => $item->amount,
                'status' => $item->status, // 'income' atau 'expense'
                'description' => $description,
                'date' => $item->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'current_balance' => $user->coin_balance,
            'data' => $formattedData
        ]);
    }
}