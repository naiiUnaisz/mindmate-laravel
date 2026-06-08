<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinHistories;
use App\Models\DailyTaskItem;
use App\Models\EntertainmentLog;
use App\Models\Punishment;
use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $histori = CoinHistories::where('user_id', $user->id)
            ->with('source')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedData = $histori->map(function ($item) {
            $description = 'Aktivitas tidak diketahui';

            if ($item->source_type === DailyTaskItem::class) {
                $dailyTaskItem = $item->source;
                $taskTitle = $dailyTaskItem->task->title ?? 'Tugas dihapus';
                $description = "Menyelesaikan tugas: " . $taskTitle;
            }
            elseif ($item->source_type === EntertainmentLog::class) {
                $appTitle = $item->source->app->title ?? 'Aplikasi';
                $description = "Membeli hiburan: " . $appTitle;
            }
            elseif ($item->source_type === Punishment::class) {
                $description = "Denda: Telat absen hiburan";
            }

            return [
                'id' => $item->id,
                'amount' => $item->amount,
                'status' => $item->status,
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
