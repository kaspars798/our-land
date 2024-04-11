<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $request = Container::getInstance()->make('request');
    $blogPosts = [];
    foreach (BlogPost::all() as $post) {
        $blogPosts[] = (new BlogPostResource($post))->toArray($request);
    }

    $categories = [];
    foreach (Category::all() as $category) {
        $categories[] = $category->toArray($request);
    }

    return Inertia::render('Welcome', [
        'blogPosts' => $blogPosts,
        'categories' => $categories,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::apiResource('/post', BlogPostController::class);
    Route::apiResource('/comment', CommentController::class);
    Route::apiResource('/category', CategoryController::class);
});


require __DIR__.'/auth.php';
