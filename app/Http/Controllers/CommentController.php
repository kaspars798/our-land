<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        foreach ($request->fields as $field) {

            if (empty($field['text'])) {
                continue;
            }

            $details = [
                'text' => $field['text'],
                'blog_post_id' => $field['blog_post_id'],
                'user_id' => $request->user()->id,
            ];

            DB::beginTransaction();

            try {
                Comment::create($details);
                DB::commit();
            } catch (\Exception $ex) {

                return ApiResponseClass::rollback($ex);
            }
        }

        return redirect()->route('welcome');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (! Gate::allows('update-comment', $id)) {
            abort(403);
        }

        Comment::destroy($id);

        return redirect()->route('welcome');
    }

}
