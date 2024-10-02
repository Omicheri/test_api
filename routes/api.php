<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;


/*Route::get('comments', [CommentController::class, 'index']);
Route::get('comments/{id}', [CommentController::class, 'show']);
*/
Route::post('posts/{post}/comments', [PostController::class, 'storePostComment']);
Route::post('videos/{video}/comments', [VideoController::class, 'storeVideoComment']);
Route::apiResource('post', PostController::class);
Route::apiResource('video', VideoController::class);
Route::apiResource('comment', CommentController::class);
Route::apiResource('user', UserController::class);



