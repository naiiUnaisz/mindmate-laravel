<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes; // Karena di migration kamu pakai $table->softDeletes()

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'coin_reward',
        'task_type',
        'is_routine',
        'is_checked',
    ];

    protected $casts = [
        'is_routine' => 'boolean',
        'is_checked' => 'boolean',
        'coin_reward' => 'integer',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    public function dailyTaskItems() { 
        return $this->hasMany(DailyTaskItem::class); 
    }
}