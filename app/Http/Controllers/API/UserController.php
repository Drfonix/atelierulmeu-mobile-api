<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(schema="UpdateUserBody",type="object",
 * @OA\Property(property="first_name", type="string", example="George"),
 * @OA\Property(property="last_name", type="string", example="Tepes"),
 * @OA\Property(property="username", type="string", example="g_tepes"),
 * @OA\Property(property="email", type="string", example="tepes@vlad.ro"),
 * )
 *
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Get user data",
     *     description="Returns the current user data.",
     *     tags={"user"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The current user data response.",
     *      @OA\JsonContent(ref="#/components/schemas/User"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentUser(Request $request): JsonResponse
    {
        $response = new UserResource($request->user());

        return response()->json($response);
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     summary="Update user data",
     *     description="Returns the updated user data.",
     *     tags={"user"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="User fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UpdateUserBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="The updated user data response.",
     *      @OA\JsonContent(ref="#/components/schemas/User"),
     *     )
     * )
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function updateUser(UserRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $user->update($validated);

        $response = new UserResource($user->fresh());

        return response()->json($response);
    }

    /**
     * @OA\Delete(
     *     path="/user",
     *     summary="Delete user data",
     *     description="Returns a success meassge from delete user process..",
     *     tags={"user"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The updated user data response.",
     *      @OA\JsonContent(
     *        @OA\Property(property="status", type="string", example="success"),
     *     ),
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteUser(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(["status" => "success"]);
    }
}
