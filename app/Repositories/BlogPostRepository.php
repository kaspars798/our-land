<?php

namespace App\Repositories;

use App\Interfaces\BlogPostRepositoryInterface;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Comment;

class BlogPostRepository implements BlogPostRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function index()
    {
        return BlogPost::with(['author', 'categories', 'comments'])->where('user_id', auth()->user()->id)->get();
    }

    public function getById($id)
    {
        return BlogPost::findOrFail($id);
    }

    public function store(array $data)
    {
        $categories = $data['categories'];
        unset($data['categories']);
        $post = BlogPost::create($data);

        $ctgrs = [];
        foreach ($categories as $category) {    
            if ($category['val'] === true) {
                $ctgrs[] = $category['id'];
            } 
        }

        $post->categories()->attach($ctgrs);
    }

    public function update(array $data, $id)
    {
        $categories = $data['categories'];

        unset($data['categories']);
        $post = BlogPost::findOrFail($id);
        $post->update($data);
        $post->categories()->detach();

        $ctgrs = [];
        foreach ($categories as $category) {    
            if ($category['val'] === true) {
                $ctgrs[] = $category['id'];
            } 
        }

        $post->categories()->attach($ctgrs);
    }

    public function delete($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->categories()->detach();
        $post->comments()->delete();
        $post->delete();
    }

}
