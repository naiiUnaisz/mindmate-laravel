<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $yesterday = Carbon::yesterday()->toDateString();


        $recordYesterday = DailyRecord::where('user_id', $user->id)
            ->where('date', $yesterday)
            ->first();

        // Jika tidak ada record kemarin, atau jika kemarin bukan Rest Day dan puzzle belum lengkap, maka reset streak
        if (!$recordYesterday || (!$recordYesterday->is_rest_day && $recordYesterday->puzzle_completed_count < 6)) {
            
            // Jika streak-nya masih lebih dari 0, kita reset jadi 0
            if ($user->current_streak > 0) {
                $user->update(['current_streak' => 0]);
            }
        }


        // Mengembalikan data user yang sedang login saat ini secara real-time
        return response()->json([
            'success' => true,
            'data' => $request->user() 
        ]);
    }
}
