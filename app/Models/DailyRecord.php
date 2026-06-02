<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'mood_level',
        'is_rest_day',            
        'puzzle_completed_count',
    ];

    protected $casts = [
        'date' => 'date',
        'is_rest_day' => 'boolean',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function dailyTaskItems() {
        return $this->hasMany(DailyTaskItem::class); 
    }

    public function puzzlePieces() { 
        return $this->hasMany(PuzzlePieces::class); 
    }
}