<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('/', [BlogPostController::class, 'index'])->name('blog-posts.index');
Route::get('/blog/{blog_post}', [BlogPostController::class, 'show'])->name('blog-posts.show');
Route::get('/ajax/blog-posts', [BlogPostController::class, 'ajaxList'])->name('ajax.blog-posts.list');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Comment Routes
    Route::post('/blog-posts/{blog_post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    Route::get('/my-posts', [BlogPostController::class, 'myPosts'])->name('blog-posts.my-posts');
    Route::get('/my-posts/create', [BlogPostController::class, 'create'])->name('blog-posts.create');
    Route::get('/my-posts/{blog_post}/edit', [BlogPostController::class, 'edit'])
        ->middleware('post.owner')
        ->name('blog-posts.edit');
    
    Route::post('/blog-posts', [BlogPostController::class, 'store'])->name('blog-posts.store');  
    Route::put('/blog-posts/{blog_post}', [BlogPostController::class, 'update'])
        ->middleware('post.owner')
        ->name('blog-posts.update');
    Route::delete('/blog-posts/{blog_post}', [BlogPostController::class, 'destroy'])
        ->middleware('post.owner')
        ->name('blog-posts.destroy');
});

require __DIR__.'/auth.php';
