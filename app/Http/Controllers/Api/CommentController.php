<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCommentRequest;
use App\Http\Requests\StoreVideoCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Comment;
use App\Http\Resources\CommentRessource;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return CommentRessource::collection(Comment::with('commentable:id,title')->limit(5)->get());
    }

    public function show(Comment $comment)
    {
        return response()->json(new CommentRessource($comment));
    }


    public function update(UpdateCommentRequest $request, Comment $comment)
    {
            $comment->update($request->validated());
            return response()->json(new CommentRessource($comment));
        }


    public function destroy(Comment $comment)
    {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted']);
    }
}

