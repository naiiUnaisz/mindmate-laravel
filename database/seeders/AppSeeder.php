<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $apps = [
        [
            'title' => 'Spotify', 
            'name' => 'Spotify', 
            'url' => 'https://play.google.com/store/apps/details?id=com.spotify.music', 
            'category' => 'music', 
            'coin_cost' => 20, 
            'duration_minutes' => 60
        ],
        [
            'title' => 'Netflix', 
            'name' => 'Netflix', 
            'url' => 'https://play.google.com/store/apps/details?id=com.netflix.mediaclient', 
            'category' => 'movie', 
            'coin_cost' => 40, 
            'duration_minutes' => 60
        ],
        [
            'title' => 'YouTube', 
            'name' => 'YouTube', 
            'url' => 'https://play.google.com/store/apps/details?id=com.google.android.youtube', 
            'category' => 'movie', 
            'coin_cost' => 30, 
            'duration_minutes' => 30
        ],
        [
            'title' => 'Mobile Legends', 
            'name' => 'Mobile Legends', 
            'url' => 'https://play.google.com/store/apps/details?id=com.mobile.legends', 
            'category' => 'game', 
            'coin_cost' => 45, 
            'duration_minutes' => 30
        ],
        [
            'title' => 'TikTok', 
            'name' => 'TikTok', 
            'url' => 'https://play.google.com/store/apps/details?id=com.zhiliaoapp.musically', 
            'category' => 'social_media', 
            'coin_cost' => 40, 
            'duration_minutes' => 15
        ],
        [
            'title' => 'Instagram', 
            'name' => 'Instagram', 
            'url' => 'https://play.google.com/store/apps/details?id=com.instagram.android', 
            'category' => 'social_media', 
            'coin_cost' => 40, 
            'duration_minutes' => 15
        ],
    ];

    foreach ($apps as $appData) {
        // Menggunakan updateOrCreate agar jika dijalankan ulang tidak menduplikat data
        App::updateOrCreate(['title' => $appData['title']], $appData);
    }
    }
}
