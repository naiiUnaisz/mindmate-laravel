<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyTaskItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'daily_record_id' => $this->daily_record_id,
            'task_id' => $this->task_id,
            'is_completed' => $this->is_completed,
            'task' => new TaskResource($this->whenLoaded('task')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
