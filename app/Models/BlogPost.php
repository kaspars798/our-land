<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    protected $with = [
        'author', 
        'comments',
        'categories',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'blog_post_category', 'blog_post_id', 'category_id');
    }
}
