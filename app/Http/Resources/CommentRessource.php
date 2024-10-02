<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserRessource as UserResource;

class CommentRessource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'commentable_id' => $this->whenLoaded('commentable'),
            'commentable_type' => $this->commentable_type,
        ];
    }
}
