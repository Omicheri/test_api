<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentRessource;
use App\Http\Resources\PostResource;
use App\Http\Resources\VideoResource;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
     return  PostResource::collection(Post::with('comments:id,body,commentable_id')->limit(5)->get());
    }

    public function show(Post $post)
    {
        return response()->json(new CommentRessource($post));
    }
    public function store(UpdatePostRequest $request)
    {
        $post = Post::create($request->validated());
        return response()->json(new PostResource($post),201);

    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return response()->json(new PostResource($post),200);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
        public function storePostComment(StorePostCommentRequest $request, Post $post)
    {

        $comment= $post->comments()->create($request->validated());

        return response()->json(new CommentRessource($comment),201) ;
    }
}



