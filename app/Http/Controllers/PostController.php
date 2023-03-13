<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::orderby('created_at', 'desc')
        ->with('user:id,name,image')->withcount('comments', 'likes')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $request->validated();

        $image = $this->saveimage($request->image, 'posts');

        $post = Post::create([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostStoreRequest $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response([
                'message' => 'Unable to update other Posts'
            ]);
        }
        $request->validated();

        $post->update($request->all());

        return new PostResource($post);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (auth()->user()->id !== $post->user_id) {
            return response([
                'unable to delete'
            ]);
        }
        $post->likes()->delete();

        $post->comments()->delete();

        $post->delete();

        return response([
            'message' => 'Deleted!'
        ]);
    }
}
