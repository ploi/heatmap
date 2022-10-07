<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Site;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Heatmap',
            'email' => 'info@heatmap.com',
            'password' => bcrypt('password'),
        ]);

        Site::create([
            'domain' => 'http://heatmap.test',
        ]);
    }
}
