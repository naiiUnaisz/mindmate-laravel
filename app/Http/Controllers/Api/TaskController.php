<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\CoinHistories;
use App\Models\DailyRecord;
use App\Models\DailyTaskItem;
use App\Models\PuzzlePieces;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    public function index(Request $request) {
        $tasks = $request->user()->tasks()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks)
        ]);
    }

    public function show(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new TaskResource($task)
        ]);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['coin_reward'] = $validated['coin_reward'] ?? 10;

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan',
            'data' => new TaskResource($task)
        ], 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => new TaskResource($task)
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus'
        ]);
    }

    public function checkTask(Request $request, $id)
    {
        $user = $request->user();
        $task = $user->tasks()->findOrFail($id);
        $today = Carbon::today()->toDateString();

        $source = $request->input('source', 'cart');

        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        $dailyTaskItem = DailyTaskItem::firstOrCreate(
            ['daily_record_id' => $dailyRecord->id, 'task_id' => $task->id],
            ['is_completed' => false]
        );

        if (!$dailyTaskItem->wasRecentlyCreated && $dailyTaskItem->is_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas ini sudah diselesaikan hari ini.'
            ], 400);
        }

        $dailyTaskItem->update(['is_completed' => true]);

        $rewardAmount = 0;
        $puzzleOpened = false;
        $currentPieces = PuzzlePieces::where('daily_record_id', $dailyRecord->id)->count();

        if ($source === 'puzzle') {
            if ($currentPieces < 6) {
                $rewardAmount = 25;

                PuzzlePieces::create([
                    'daily_record_id' => $dailyRecord->id,
                    'daily_task_item_id' => $dailyTaskItem->id,
                    'piece_number' => $currentPieces + 1,
                    'is_opened' => true,
                    'opened_at' => now(),
                ]);

                $dailyRecord->increment('puzzle_completed_count');
                $puzzleOpened = true;
                $currentPieces++;

                if ($currentPieces == 6) {
                    $rewardAmount += 100;
                    $user->increment('current_streak');
                }
            }
        } else {
            $rewardAmount = 10;
            $task->update(['is_checked' => true]);
        }

        $user->increment('coin_balance', $rewardAmount);

        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $rewardAmount,
            'status' => 'reward',
            'source_type' => DailyTaskItem::class,
            'source_id' => $dailyTaskItem->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diselesaikan!',
            'data' => [
                'source' => $source,
                'puzzle_opened' => $puzzleOpened,
                'current_puzzle_count' => $currentPieces,
                'coins_earned' => $rewardAmount,
                'current_coin_balance' => $user->coin_balance,
                'current_streak' => $user->current_streak
            ]
        ]);
    }
}
