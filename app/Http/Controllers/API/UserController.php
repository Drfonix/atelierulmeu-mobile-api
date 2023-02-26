<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getCurrentUser(Request $request): JsonResponse
    {
        $response = new UserResource($request->user());

        return response()->json($response);
    }


    public function updateUser(UserRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $user->update($validated);

        $response = new UserResource($user->fresh());

        return response()->json($response);
    }


    public function deleteUser(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(["status" => "success"]);
    }
}
