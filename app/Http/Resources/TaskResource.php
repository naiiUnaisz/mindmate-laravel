<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'coin_reward' => $this->coin_reward,
            'task_type' => $this->task_type,
            'is_routine' => $this->is_routine,
            'is_checked' => $this->is_checked,
            'is_completed_today' => $this->whenLoaded('dailyTaskItems', function () {
                return $this->dailyTaskItems->first()?->is_completed ?? false;
            }, false),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
