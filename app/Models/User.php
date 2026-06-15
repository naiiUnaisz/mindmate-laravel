<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'birthday',
        'gender',
        'avatar',
        'coin_balance',
        'current_streak',
        'restday_quota',
        'settings',
    ];

    // --- RELASI ---
    public function tasks() { 
        return $this->hasMany(Task::class); 
    }

    public function dailyRecords() { return $this->hasMany(DailyRecord::class); }
    public function entertainmentLogs() { return $this->hasMany(EntertainmentLog::class); }
    public function punishments() { return $this->hasMany(Punishment::class); }
    public function coinHistories() { return $this->hasMany(CoinHistories::class); }
    public function notes() { return $this->hasMany(Note::class); }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date:Y-m-d',
            'settings' => 'array',
        ];
    }
}
