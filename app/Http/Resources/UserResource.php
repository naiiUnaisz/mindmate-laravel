<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'coin_balance' => $this->coin_balance,
            'current_streak' => $this->current_streak,
            'restday_quota' => $this->restday_quota,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
