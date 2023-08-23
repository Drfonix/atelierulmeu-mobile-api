<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 * @OA\Schema(type="object",schema="NotificationResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", ref="#/components/schemas/Notification"),
 * )
 *
 * @OA\Schema(type="object",schema="DeleteNotificationResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object"),
 * )
 *
 * @OA\Schema(type="object",schema="UserNotificationsResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",
 *     @OA\Items(ref="#/components/schemas/Notification")),
 * )
 *
 * @OA\Schema(type="object",schema="NotificationBody",
 * required={"car_id", "type", "title", "alert_date"},
 * @OA\Property(property="car_id",type="integer",example=1),
 * @OA\Property(property="type",type="string",example="ITP"),
 * @OA\Property(property="title",type="string",example="Audi ITP"),
 * @OA\Property(property="message",type="string",example="My message"),
 * @OA\Property(property="alert_date",type="string",example="2023-06-05 10:00:00"),
 * @OA\Property(property="expiration_date",type="string",example="2023-06-06 10:00:00"),
 * @OA\Property(property="meta_data",type="object"),
 * )
 *
 * Class NotificationController
 * @package App\Http\Controllers\API
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/notifications/{id}",
     *     summary="Get user notification by id",
     *     description="Returns the notification detailed data",
     *      tags={"Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of notification",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Notification response by notification id.",
     *      @OA\JsonContent(ref="#/components/schemas/NotificationResponse"),
     *     )
     * )
     * @param Request $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function getNotificationById(Request $request, Notification $notification): JsonResponse
    {
        $notificationResource = new NotificationResource($notification);

        return $this->successResponse($notificationResource);
    }

    /**
     * @OA\Get(
     *     path="/notifications",
     *     summary="Get user notifications",
     *     description="Returns the user related notifications",
     *      tags={"Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The user notifications response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserNotificationsResponse"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserNotifications(Request $request): JsonResponse
    {
        $user = $request->user();

        $notificationResources = NotificationResource::collection(new NotificationResource($user->notifications));

        return $this->successResponse($notificationResources);
    }

    /**
     * @OA\Post(
     *     path="/notifications",
     *     summary="Create new user notification",
     *     description="Creates a new user related notification",
     *      tags={"Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Notification fillable properties",
     *       @OA\JsonContent(type="object",ref="#/components/schemas/NotificationBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Notification response.",
     *      @OA\JsonContent(ref="#/components/schemas/NotificationResponse"),
     *     )
     * )
     * @param NotificationRequest $request
     * @return JsonResponse
     */
    public function createUserNotification(NotificationRequest $request): JsonResponse
    {
        $notificationData = $request->validated();
        $user = $request->user();
        $notificationData['user_id'] = $user->id;

        $notification = Notification::create($notificationData);

        $notificationResource = new NotificationResource($notification);

        return $this->successResponse($notificationResource);
    }

    /**
     * @OA\Put(
     *     path="/notifications/{id}",
     *     summary="Update the current notification",
     *     description="Updates the user notification by id",
     *      tags={"Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of notification",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Notification fillable properties",
     *       @OA\JsonContent(type="object",ref="#/components/schemas/NotificationBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Updated notification response",
     *      @OA\JsonContent(ref="#/components/schemas/NotificationResponse"),
     *     )
     * )
     * @param NotificationRequest $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function updateUserNotification(NotificationRequest $request, Notification $notification): JsonResponse
    {
        $notificationData = $request->validated();
        $user = $request->user();

        $this->checkResourceOwner($notification, $user);

        $notification->update($notificationData);

        $notificationResource = new NotificationResource($notification->fresh());

        return $this->successResponse($notificationResource);
    }

    /**
     * @OA\Delete(
     *     path="/notifications/{id}",
     *     summary="Delete the user notification",
     *     description="Deletes the user notification by id",
     *      tags={"Notification"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of notification",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Delete notification response",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteNotificationResponse"),
     *     )
     * )
     * @param Request $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function deleteUserNotification(Request $request, Notification $notification): JsonResponse
    {
        $user = $request->user();

        $this->checkResourceOwner($notification, $user);
        $notification->delete();

        return $this->successResponse([], "The notification was deleted success.");
    }

}
