<?php

use App\Http\Controllers\Blog\CategoryController as BlogCategoryController;
use App\Http\Controllers\Blog\HomeController as BlogHomeController;
use App\Http\Controllers\Blog\PostController as BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;

Route::get('/', [BlogHomeController::class, 'index'])->name('home');

Route::get('/blog/categories/{category:slug}', [BlogCategoryController::class, 'show'])->name('blog.category');

Route::get('/blog/{post:slug}', [BlogPostController::class, 'show'])->name('blog.post');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Posts
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/toggle-publish', [PostController::class, 'togglePublish'])->name('posts.toggle-publish');

    // Comments
    Route::resource('comments', CommentController::class);
    Route::post('/comments/{comment}/toggle-approval', [CommentController::class, 'toggleApproval'])->name('comments.toggle-approval');
});

Route::get('/teste', function (){
    Bugsnag::notifyException(new RuntimeException("Test error"));
});

require __DIR__.'/auth.php';
