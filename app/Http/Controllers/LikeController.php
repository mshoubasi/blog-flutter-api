<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
   public function likeOrunlike($id)
   {
       $post = Post::findOrfail($id);

       if (!$post) {
          return response([
            'message' => 'post not found'
          ]);
       }
       $like = $post->likes()->where('user_id', auth()->user()->id)->first();

       if (!$like) {
          Like::create([
            'post_id' => $id,
            'user_id' => auth()->user()->id
          ]);
          return response([
            'liked'
          ]);
       }
       $like->delete();

       return response([
        'unliked'
      ]);
   }
}
