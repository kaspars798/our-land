<?php

namespace App\Providers;

use App\Interfaces\BlogPostRepositoryInterface;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Repositories\BlogPostRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BlogPostRepositoryInterface::class, BlogPostRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('update-post', function (User $user, $id) {
            $post = BlogPost::findOrFail($id);

            return $user->id === $post->user_id;
        });

        Gate::define('update-comment', function (User $user, $id) {
            $comment = Comment::findOrFail($id);

            return $user->id === $comment->user_id;
        });
    }
    
}
