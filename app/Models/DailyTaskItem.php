<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTaskItem extends Model
{
    protected $fillable = [
        'daily_record_id',
        'task_id',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function dailyRecord() {
        return $this->belongsTo(DailyRecord::class); 
    }

    public function task() { 
        return $this->belongsTo(Task::class); 
    }

    public function puzzlePiece() { 
        return $this->hasOne(PuzzlePieces::class); 
    }
    
    // Relasi Polymorphic ke Koin (Mencatat pemasukan/reward)
    public function coinHistories() { 
        return $this->morphMany(CoinHistories::class, 'source'); 
    }
}