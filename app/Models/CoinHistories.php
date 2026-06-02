<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinHistories extends Model
{
   protected $fillable = [
        'user_id',
        'amount',
        'status',
        'source_id',
        'source_type',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    // Fungsi sakti untuk mengambil tabel asal secara otomatis
    public function source()
    {
        return $this->morphTo();
    }
}
