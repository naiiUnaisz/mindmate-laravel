<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMoodRequest;
use App\Http\Resources\DailyRecordResource;
use App\Models\DailyRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyRecordController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $dailyRecord = DailyRecord::with([
            'dailyTaskItems.task',
            'puzzlePieces'
        ])->where('user_id', $user->id)
          ->where('date', $today)
          ->first();

        if (!$dailyRecord) {
            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $today,
                    'mood_level' => null,
                    'is_rest_day' => false,
                    'puzzle_completed_count' => 0,
                    'daily_task_items' => [],
                    'puzzle_pieces' => [],
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new DailyRecordResource($dailyRecord)
        ]);
    }

    public function storeMood(StoreMoodRequest $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

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

    public function useRestDay(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        if ($dailyRecord->is_rest_day) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah mengambil jatah Rest Day untuk hari ini.'
            ], 400);
        }

        if ($user->restday_quota <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota Rest Day kamu sudah habis! Tetap semangat kerjakan tugas ya.'
            ], 400);
        }

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
