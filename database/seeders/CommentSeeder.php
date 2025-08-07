<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = BlogPost::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        if ($posts->isEmpty()) {
            $this->command->warn('No posts found. Please run PostSeeder first.');
            return;
        }

        Comment::factory(100)->make()->each(function ($comment) use ($users, $posts) {
            $comment->user_id = $users->random()->id;
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
} 