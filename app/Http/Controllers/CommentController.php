<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        return  CommentResource::collection(Comment::where('post_id', $id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, $id)
    {
        $request->validated();

        $post = Post::findOrfail($id);

        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
        ]);

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentStoreRequest $request, $id)
    {
        $comment = Comment::findOrfail($id);

        if ($comment->user_id !== auth()->user()->id) {
            return response([
                'message' => 'Unable to update other Comments'
            ]);
        }
        $request->validated();

        $comment->update($request->all());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findorFail($id);
        if ($comment->user_id !== auth()->user()->id) {
            return response([
               'message' => 'unable to delete'
            ]);
        }
        $comment->delete();
        return response([
            'message' => 'deleted!'
        ]);
    }
}
