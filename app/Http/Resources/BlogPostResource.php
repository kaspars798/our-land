<?php

namespace App\Http\Resources;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   

        $comments = [];

        foreach ($this->comments as $comment) {
            try {
                $commentAuthor = User::find($comment->author->id);
            } catch (\Exception $ex) {
                $commentAuthor = null;
            }

            $comments [] = [
                'id' => $comment->id,
                'text' => $comment->text,
                'author' => [
                    'id' => $commentAuthor?->id,
                    'name' => $commentAuthor?->name,
                ],
                'created_at' => (new DateTime($comment->created_at))->format('Y-m-d H:i'),
            ];
        }

        $categories = [];

       foreach (Category::all() as $category) {
            if (in_array($category->id, array_map( fn($item) => $item['id'], $this->categories->toArray()))) {
                $categories[] = ['id' => $category->id, 'val' => true];
            } else {
                $categories[] = ['id' => $category->id, 'val' => false];
            }
        }

        try {
            $author = User::find($this->author->id);
        } catch (\Exception $ex) {
            $author = null;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'author' => ['name' => $author?->name],
            'comments' => $comments,
            'categories' => $categories,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i'),
        ];
    }
}
