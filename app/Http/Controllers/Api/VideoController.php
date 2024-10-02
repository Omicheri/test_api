<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\CommentRessource;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      return  VideoResource::collection(Video::with('comments:id,body,commentable_id')->limit(5)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateVideoRequest $request)
    {

        $video = Video::create($request->validated());
        return response()->json(new VideoResource($video),201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return response()->json(new VideoResource($video));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->validated());
        return response()->json(new VideoResource($video));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json(['message' => 'Video deleted successfully'], 200);
    }
    public function storeVideoComment(StoreVideoCommentRequest $request, Video $video)
    {
        $comment = $video->comments()->create($request->validated());

        return response()->json(new CommentRessource($comment),201);
    }
}
