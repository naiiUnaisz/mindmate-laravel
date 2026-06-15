<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinHistories;
use App\Models\DailyTaskItem;
use App\Models\EntertainmentLog;
use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function earnCoins(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $amount = (int) $request->amount;

        $user->increment('coin_balance', $amount);

        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'reward',
            'source_type' => 'manual',
            'source_id' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => "$amount koin berhasil ditambahkan",
            'current_balance' => $user->coin_balance,
        ]);
    }

    public function spendCoins(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $amount = (int) $request->amount;

        if ($user->coin_balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Koin tidak mencukupi! Saldo kamu: ' . $user->coin_balance
            ], 400);
        }

        $user->decrement('coin_balance', $amount);

        CoinHistories::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'expense',
            'source_type' => 'manual',
            'source_id' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => "$amount koin berhasil digunakan",
            'current_balance' => $user->coin_balance,
        ]);
    }
}
