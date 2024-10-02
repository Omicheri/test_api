<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserRessource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserRessource::collection(User::with('comments')->limit(5)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(new UserRessource($user),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(new UserRessource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json(new UserRessource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message'=>'User deleted']);
    }
}
