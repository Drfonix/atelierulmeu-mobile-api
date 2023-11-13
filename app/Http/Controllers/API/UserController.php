<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceNotificationRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\FirebaseService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\Notification;

/**
 * @OA\Schema(schema="UpdateUserBody",type="object",
 * @OA\Property(property="first_name", type="string", example="George"),
 * @OA\Property(property="last_name", type="string", example="Tepes"),
 * @OA\Property(property="username", type="string", example="g_tepes"),
 * @OA\Property(property="email", type="string", example="tepes@vlad.ro"),
 * )
 *
 * @OA\Schema(type="object",schema="DeleteUserResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserController extends Controller
{

    /**
     * @var FirebaseService
     */
    protected FirebaseService $firebaseService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserController constructor.
     * @param FirebaseService $firebaseService
     * @param UserService $userService
     */
    public function __construct(FirebaseService $firebaseService, UserService $userService)
    {
        $this->firebaseService = $firebaseService;
        $this->userService = $userService;

    }

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Get user data",
     *     description="Returns the current user data.",
     *      tags={"User"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The current user data response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getCurrentUser(Request $request): JsonResponse
    {
        $response = new UserResource($request->user());

        return $this->successResponse($response);
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     summary="Update user data",
     *     description="Returns the updated user data.",
     *      tags={"User"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="User fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/UpdateUserBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="The updated user data response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserResponse")
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

        return $this->successResponse($response);
    }

    /**
     * @OA\Delete(
     *     path="/user",
     *     summary="Delete user data",
     *     description="Returns a success meassge from delete user process..",
     *      tags={"User"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The updated user data response.",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteUserResponse")
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
        $this->userService->deleteUserWithAllData($user);

        return $this->successResponse();
    }

    /**
     * @OA\Post(
     *     path="/trigger-notification",
     *     summary="Send notification to a device",
     *     description="This is for testing. It will be removed from production",
     *      tags={"Device Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="User fillable properties",
     *       @OA\JsonContent(required={"device_token", "title", "body"},
     *          @OA\Property(property="device_token", type="string", example="UYKBSFSD562SDFSDF"),
     *          @OA\Property(property="title", type="string", example="Expired something"),
     *          @OA\Property(property="body", type="string", example="This is the message , lorem ipsum blsDfsdfsjdfskdfsdf"),
     *          @OA\Property(property="event_id", type="integer", example="9"),
     *          @OA\Property(property="car_id", type="integer", example="10"),
     *     ),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="The updated user data response.",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(property="message", type="string", example=""),
     *          @OA\Property(property="data", type="object", example={}),
     *     )
     *     )
     * )
     * @param DeviceNotificationRequest $request
     * @return JsonResponse
     */
    public function postSendNotification(DeviceNotificationRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $payloadData = [
            'event_id' => $validated["event_id"],
            'car_id' => $validated["car_id"],
            'title' => $validated["title"],
            'body' => $validated["body"],
        ];
        $message = $this->firebaseService->createMessage($validated["device_token"], $validated, $payloadData);
        $messageResponse = $this->firebaseService->sendMessage($message);

        return $this->successResponse($messageResponse, "Notification sent successfully!");
    }
}
