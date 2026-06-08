<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntertainmentLog extends Model
{
    protected $fillable = [
        'user_id',
        'app_id',
        'status',
        'started_at',
        'expired_at',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function app() { 
        return $this->belongsTo(App::class); 
    }

    public function punishment() { 
        
    return $this->hasOne(Punishment::class); 
    }

    // Relasi Polymorphic ke Koin (Mencatat pengeluaran/spend)
    public function coinHistories() { 
        return $this->morphMany(CoinHistories::class, 'source'); 
    }
}