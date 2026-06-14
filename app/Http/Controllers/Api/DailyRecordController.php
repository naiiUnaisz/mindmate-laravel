<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMoodRequest;
use App\Http\Resources\DailyRecordResource;
use App\Models\CoinHistories;
use App\Models\DailyRecord;
use App\Models\PuzzlePieces;
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

    public function moodHistory(Request $request)
    {
        $user = $request->user();

        $records = DailyRecord::where('user_id', $user->id)
            ->whereNotNull('mood_level')
            ->orderBy('date', 'desc')
            ->get(['id', 'date', 'mood_level', 'is_rest_day']);

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    public function puzzles(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $dailyRecord = DailyRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$dailyRecord) {
            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $today,
                    'puzzle_completed_count' => 0,
                    'puzzle_pieces' => [],
                ]
            ]);
        }

        $pieces = PuzzlePieces::where('daily_record_id', $dailyRecord->id)
            ->orderBy('piece_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $today,
                'puzzle_completed_count' => $dailyRecord->puzzle_completed_count,
                'is_rest_day' => $dailyRecord->is_rest_day,
                'puzzle_pieces' => $pieces,
            ]
        ]);
    }

    public function unlockPuzzle(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        $currentPieces = PuzzlePieces::where('daily_record_id', $dailyRecord->id)->count();

        if ($currentPieces >= 6) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah mendapatkan semua potongan puzzle hari ini!'
            ], 400);
        }

        $piece = PuzzlePieces::create([
            'daily_record_id' => $dailyRecord->id,
            'piece_number' => $currentPieces + 1,
            'is_opened' => true,
            'opened_at' => now(),
        ]);

        $dailyRecord->increment('puzzle_completed_count');
        $newCount = $currentPieces + 1;

        $rewardAmount = 25;

        if ($newCount === 6 && !$dailyRecord->is_rest_day) {
            $rewardAmount += 100;
            $user->increment('current_streak');
        }

        $user->increment('coin_balance', $rewardAmount);

        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $rewardAmount,
            'status' => 'reward',
            'source_type' => PuzzlePieces::class,
            'source_id' => $piece->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Potongan puzzle berhasil dibuka!',
            'data' => [
                'piece' => $piece,
                'current_puzzle_count' => $newCount,
                'is_complete' => $newCount === 6,
                'coins_earned' => $rewardAmount,
                'current_coin_balance' => $user->coin_balance,
                'current_streak' => $user->current_streak,
            ]
        ]);
    }
}
