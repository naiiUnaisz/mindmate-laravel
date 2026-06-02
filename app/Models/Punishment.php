<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
{
    protected $fillable = [
        'user_id',
        'entertainment_log_id',
        'fine_amount',
        'reason',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function entertainmentLog() { 
        return $this->belongsTo(EntertainmentLog::class); 
    }
    
    // Relasi Polymorphic ke Koin (Mencatat pengeluaran/punishment)
    public function coinHistories() { 
        return $this->morphMany(CoinHistories::class, 'source'); 
    }
}