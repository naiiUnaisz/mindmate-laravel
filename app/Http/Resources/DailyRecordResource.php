<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date' => $this->date,
            'mood_level' => $this->mood_level,
            'is_rest_day' => $this->is_rest_day,
            'puzzle_completed_count' => $this->puzzle_completed_count,
            'daily_task_items' => DailyTaskItemResource::collection($this->whenLoaded('dailyTaskItems')),
            'puzzle_pieces' => $this->whenLoaded('puzzlePieces'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
