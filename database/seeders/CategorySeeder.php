<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'technology' => 'Technology',
            'design' => 'Design',
            'business' => 'Business',
            'health' => 'Health',
            'traveling' => 'Traveling',
            'food' => 'Food',
            'sports' => 'Sports',
            'entertainment' => 'Entertainment',
            'science' => 'Science',
            'politics' => 'Politics',
            'lifestyle' => 'Lifestyle',
            'finance' => 'Finance',
            'news' => 'News'
        ];

        foreach ($categories as $slug => $categoryName) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $categoryName]
            );
        }

        Category::whereNotIn('slug', array_keys($categories))->delete();
    }
}
