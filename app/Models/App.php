<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = [
        'name',
        'title',
        'category',
        'url',
        'coin_cost',
        'duration_minutes',
    ];

    public function entertainmentLogs() { 
        return $this->hasMany(EntertainmentLog::class); 
    }
}