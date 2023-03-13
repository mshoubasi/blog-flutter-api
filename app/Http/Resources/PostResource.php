<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'image' => $this->image,
            'user_id' => $this->user_id,
            'likes' => $this->likes->count(),
            'comments' =>  CommentResource::collection($this->comments)->count()
        ];
    }
}
