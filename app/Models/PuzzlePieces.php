<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuzzlePieces extends Model
{
   protected $fillable = [
        'daily_record_id',
        'daily_task_item_id',
        'piece_number',
        'is_opened',
        'opened_at',
    ];

    protected $casts = [
        'is_opened' => 'boolean',
        'opened_at' => 'datetime',
    ];

    public function dailyRecord() { 
        return $this->belongsTo(DailyRecord::class); 
    }
    public function dailyTaskItem() { 
        return $this->belongsTo(DailyTaskItem::class); 
    }
}
