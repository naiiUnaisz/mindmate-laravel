<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

        if (!$recordYesterday || (!$recordYesterday->is_rest_day && $recordYesterday->puzzle_completed_count < 6)) {
            if ($user->current_streak > 0) {
                $user->current_streak = 0;
                $user->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ]);
    }
}
