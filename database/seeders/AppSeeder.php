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
            // 🎬 MOVIE (60 menit, 100 koin)
            ['title' => 'Netflix', 'name' => 'Netflix', 'url' => 'https://play.google.com/store/apps/details?id=com.netflix.mediaclient', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],
            ['title' => 'Disney+', 'name' => 'Disney+', 'url' => 'https://play.google.com/store/apps/details?id=com.disney.disneyplus', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],
            ['title' => 'iQiyi', 'name' => 'iQiyi', 'url' => 'https://play.google.com/store/apps/details?id=com.iqiyi.i18n', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],
            ['title' => 'YouTube', 'name' => 'YouTube', 'url' => 'https://play.google.com/store/apps/details?id=com.google.android.youtube', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],
            ['title' => 'Prime Video', 'name' => 'Prime Video', 'url' => 'https://play.google.com/store/apps/details?id=com.amazon.avod.thirdpartyclient', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],
            ['title' => 'Viu', 'name' => 'Viu', 'url' => 'https://play.google.com/store/apps/details?id=com.viu.pc', 'category' => 'movie', 'coin_cost' => 100, 'duration_minutes' => 60],

            // 🎵 MUSIC (15 menit, 20 koin)
            ['title' => 'Spotify', 'name' => 'Spotify', 'url' => 'https://play.google.com/store/apps/details?id=com.spotify.music', 'category' => 'music', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'Joox', 'name' => 'Joox', 'url' => 'https://play.google.com/store/apps/details?id=com.tencent.ibg.joox', 'category' => 'music', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'YouTube Music', 'name' => 'YouTube Music', 'url' => 'https://play.google.com/store/apps/details?id=com.google.android.apps.youtube.music', 'category' => 'music', 'coin_cost' => 20, 'duration_minutes' => 15],

            // 📱 SOCIAL MEDIA (15 menit, 20 koin)
            ['title' => 'TikTok', 'name' => 'TikTok', 'url' => 'https://play.google.com/store/apps/details?id=com.zhiliaoapp.musically', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'Instagram', 'name' => 'Instagram', 'url' => 'https://play.google.com/store/apps/details?id=com.instagram.android', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'Snapchat', 'name' => 'Snapchat', 'url' => 'https://play.google.com/store/apps/details?id=com.snapchat.android', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'Line', 'name' => 'Line', 'url' => 'https://play.google.com/store/apps/details?id=jp.naver.line.android', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'Facebook', 'name' => 'Facebook', 'url' => 'https://play.google.com/store/apps/details?id=com.facebook.katana', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],
            ['title' => 'X', 'name' => 'X', 'url' => 'https://play.google.com/store/apps/details?id=com.twitter.android', 'category' => 'social_media', 'coin_cost' => 20, 'duration_minutes' => 15],

            // 🎮 GAME (30 menit, 50 koin)
            ['title' => 'Minecraft', 'name' => 'Minecraft', 'url' => 'https://play.google.com/store/apps/details?id=com.mojang.minecraftpe', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Mobile Legends', 'name' => 'Mobile Legends', 'url' => 'https://play.google.com/store/apps/details?id=com.mobile.legends', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Robbery Bob', 'name' => 'Robbery Bob', 'url' => 'https://play.google.com/store/apps/details?id=com.level8.robberybob', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Genshin Impact', 'name' => 'Genshin Impact', 'url' => 'https://play.google.com/store/apps/details?id=com.miHoYo.GenshinImpact', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Block Blast', 'name' => 'Block Blast', 'url' => 'https://play.google.com/store/apps/details?id=com.block.juggle', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Roblox', 'name' => 'Roblox', 'url' => 'https://play.google.com/store/apps/details?id=com.roblox.client', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Subway Surfers', 'name' => 'Subway Surfers', 'url' => 'https://play.google.com/store/apps/details?id=com.kiloo.subwaysurf', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
            ['title' => 'Hay Day', 'name' => 'Hay Day', 'url' => 'https://play.google.com/store/apps/details?id=com.supercell.hayday', 'category' => 'game', 'coin_cost' => 50, 'duration_minutes' => 30],
    ];

    foreach ($apps as $appData) {
        // Menggunakan updateOrCreate agar jika dijalankan ulang tidak menduplikat data
        App::updateOrCreate(['title' => $appData['title']], $appData);
    }
    }
}
