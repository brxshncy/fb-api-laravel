<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::userNewsFeed()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create(
            $request->merge([
                'user_id' => auth()->id(), 
            ])->toArray()
        );

        $post->load('user');

        return (new PostResource($post));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return (new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
       if(auth()->user()->cannot('update', $post)) {
            abort(403);
       }
       $post->update(attributes: $request->all());
       return (new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
       abort_if(auth()->user()->cannot('delete', $post), 403);

        // Delete the post
        $post->delete();

        // Return a response (redirect or JSON)
        return response()->json([
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
