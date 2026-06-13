<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'birthday' => $this->birthday,
            'age' => $this->birthday ? Carbon::parse($this->birthday)->age : null,
            'gender' => $this->gender,
            'avatar' => $this->avatar ? url('storage/' . $this->avatar) : null,
            'coin_balance' => $this->coin_balance,
            'current_streak' => $this->current_streak,
            'restday_quota' => $this->restday_quota,
            'settings' => $this->settings,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
