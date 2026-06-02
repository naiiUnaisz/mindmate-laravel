<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyRecordController extends Controller
{
    public function storeMood(Request $request){
        $request->validate([
            'mood_level' => 'required|in:good,neutral,bad',
        ]);

        $user = $request->user();
        $today = Carbon::today()->toDateString();

        // mencari data hari ini, jika belum ada otomatis dibuatkan record baru
        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        // Update level mood sesuai yang ditekan user di Flutter
        $dailyRecord->update([
            'mood_level' => $request->mood_level
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mood kamu hari ini berhasil dicatat!',
            'data' => [
                'date' => $dailyRecord->date,
                'mood_level' => $dailyRecord->mood_level
            ]
        ]);
    }

    // logika rest day
    public function useRestDay(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        // 1. Cari atau buat record harian untuk hari ini
        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        // 2. Cegah jika user mencoba mengaktifkan Rest Day ganda di hari yang sama
        if ($dailyRecord->is_rest_day) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah mengambil jatah Rest Day untuk hari ini.'
            ], 400);
        }

        // 3. Cek apakah kuota Rest Day milik user di tabel users masih tersedia
        if ($user->restday_quota <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota Rest Day kamu sudah habis! Tetap semangat kerjakan tugas ya.'
            ], 400);
        }

        // 4. Potong kuota cuti user sebanyak 1, dan set status hari ini jadi TRUE
        $user->decrement('restday_quota');
        $dailyRecord->update(['is_rest_day' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Rest Day berhasil diaktifkan! Nikmati waktu istirahatmu hari ini.',
            'data' => [
                'current_restday_quota' => $user->restday_quota,
                'is_rest_day' => $dailyRecord->is_rest_day
            ]
        ]);
    }
}
    
