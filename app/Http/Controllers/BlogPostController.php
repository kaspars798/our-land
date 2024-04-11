<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Http\Resources\BlogPostResource;
use App\Interfaces\BlogPostRepositoryInterface;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BlogPostController extends Controller
{
    private BlogPostRepositoryInterface $blogPostRepositoryInterface;

    public function __construct(BlogPostRepositoryInterface $blogPostRepositoryInterface)
    {
        $this->blogPostRepositoryInterface = $blogPostRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->blogPostRepositoryInterface->index();
        $request = Container::getInstance()->make('request');

        $blogPosts = [];

        foreach ($data as $blogPost) {
            $blogPosts[] = (new BlogPostResource($blogPost))->toArray($request);
        }

        return ApiResponseClass::sendResponse(['data' => $blogPosts, 'categories' => Category::all()]);
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
    public function store(StoreBlogPostRequest $request)
    {
        $details = [
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'categories' => $request->categories,
        ];

        DB::beginTransaction();
        try {
            $this->blogPostRepositoryInterface->store($details);
            DB::commit();

            return redirect()->route('post.index');
        } catch (\Exception $ex) {
            
            return ApiResponseClass::rollback($ex);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogPostRequest $request, $id)
    {
        if (! Gate::allows('update-post', $id)) {
            abort(403);
        }

        $details = [
            'title' => $request->title,
            'body' => $request->body,
            'categories' => $request->categories,
        ];
    
        DB::beginTransaction();

        try {
            $this->blogPostRepositoryInterface->update($details, $id);
            DB::commit();

            return redirect()->route('post.index');
        } catch (\Exception $ex) {

            return ApiResponseClass::rollback($ex);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (! Gate::allows('update-post', $id)) {
            abort(403);
        }

        $this->blogPostRepositoryInterface->delete($id);

        return redirect()->route('post.index');
    }

}
