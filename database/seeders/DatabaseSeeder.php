<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::environment('production')) {
            $this->call([
                CategorySeeder::class,
            ]);
        } else {
            $this->call([
                CategorySeeder::class,
                UserSeeder::class,
                BlogPostSeeder::class,
                CommentSeeder::class,
            ]);
        }
    }
}
