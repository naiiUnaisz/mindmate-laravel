<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinHistories;
use App\Models\DailyRecord;
use App\Models\DailyTaskItem;
use App\Models\PuzzlePieces;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    // menapilkan seluruh tugas user yg sudah login
    public function index(Request $request) {
        $tasks = $request->user()->tasks()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    // tambah tugas
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'is_routine' => 'boolean',
            'description' => 'nullable|string',
            'coin_reward' => 'nullable|integer', 
            'task_type' => 'nullable|string'
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan',
            'data' => $task
        ], 201);

    }


    // 3. Update tugas (Bisa untuk edit judul atau mencoret is_checked)
    public function update(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'is_routine' => 'boolean',
            'is_checked' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task
        ]);
    }

    // 4. Hapus tugas (Soft Delete)
    public function destroy(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus'
        ]);
    }


    // 5. Fungsi khusus saat user mencentang tugas hari ini (Buka Puzzle & Dapat Koin)
    public function checkTask(Request $request, $id)
    {
       $user = $request->user();
        $task = $user->tasks()->findOrFail($id);
        $today = Carbon::today()->toDateString();
        
        // Tangkap parameter dari Flutter (default ke 'cart' jika tidak ada)
        $source = $request->input('source', 'cart'); 

        // 1. Cari atau Buat Daily Record untuk hari ini
        $dailyRecord = DailyRecord::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['mood_level' => 'neutral', 'is_rest_day' => false, 'puzzle_completed_count' => 0]
        );

        // 2. Tandai tugas di Daily Task Item (sebagai bukti penyelesaian hari ini)
        $dailyTaskItem = DailyTaskItem::firstOrCreate(
            ['daily_record_id' => $dailyRecord->id, 'task_id' => $task->id],
            ['is_completed' => false]
        );

        // Mencegah double klik / eksploitasi koin
        if (!$dailyTaskItem->wasRecentlyCreated && $dailyTaskItem->is_completed) {
            return response()->json(['message' => 'Tugas ini sudah diselesaikan hari ini.'], 400);
        }

        // Tandai selesai di tabel pivot harian
        $dailyTaskItem->update(['is_completed' => true]);

        $rewardAmount = 0;
        $puzzleOpened = false;
        $currentPieces = PuzzlePieces::where('daily_record_id', $dailyRecord->id)->count();

        // 3. LOGIKA POINT BERDASARKAN HALAMAN ASAL (KERANJANG vs PUZZLE)
        if ($source === 'puzzle') {
            // TUGAS UTAMA (Dari Halaman Puzzle)
            $rewardAmount = $task->coin_reward;

            // Buka kepingan puzzle (Maksimal 6)
            if ($currentPieces < 6) {
                PuzzlePieces::create([
                    'daily_record_id' => $dailyRecord->id,
                    'daily_task_item_id' => $dailyTaskItem->id,
                    'piece_number' => $currentPieces + 1,
                    'is_opened' => true,
                    'opened_at' => now(),
                ]);

                $dailyRecord->increment('puzzle_completed_count');
                $puzzleOpened = true;
                $currentPieces++; // Update variabel lokal

                //  BONUS FULL PUZZLE & STREAK
                if ($currentPieces == 6) {
                    $rewardAmount += 100; // Bonus +100 Koin
                    $user->increment('current_streak'); // Streak menyala / bertambah 1
                }
            }
        } else {
            // TUGAS KERANJANG (Dari Master To-Do List)
            $rewardAmount = $task->coin_reward ;
            
            // Opsional: Jika tugas keranjang diceklis, ubah status is_checked di master task
            $task->update(['is_checked' => true]); 
        }

        // 4. Tambahkan Koin ke Saldo User
        $user->increment('coin_balance', $rewardAmount);

        // 5. Catat Histori Koin
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