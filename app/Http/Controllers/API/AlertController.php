<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlertRequest;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 * @OA\Schema(type="object",schema="AlertResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", ref="#/components/schemas/Alert"),
 * )
 *
 * @OA\Schema(type="object",schema="DeleteAlertResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object"),
 * )
 *
 * @OA\Schema(type="object",schema="UserAlertsResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",
 *     @OA\Items(ref="#/components/schemas/Alert")),
 * )
 *
 * @OA\Schema(type="object",schema="AlertBody",
 * required={"car_id", "type", "alert_date"},
 * @OA\Property(property="car_id",type="integer",example=1),
 * @OA\Property(property="type",type="string",example="ITP"),
 * @OA\Property(property="title",type="string",example="Audi ITP"),
 * @OA\Property(property="message",type="string",example="My message"),
 * @OA\Property(property="alert_date",type="string",example="2023-06-05"),
 * @OA\Property(property="recurrent",type="string",example="no"),
 * @OA\Property(property="meta_data",type="object"),
 * @OA\Property(property="price", type="integer", example="10.5"),
 * )
 *
 * Class AlertController
 * @package App\Http\Controllers\API
 */
class AlertController extends Controller
{
    /**
     * @OA\Get(
     *     path="/alerts/{id}",
     *     summary="Get user alert by id",
     *     description="Returns the alert detailed data",
     *      tags={"Alert"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of alert",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Alert response by alert id.",
     *      @OA\JsonContent(ref="#/components/schemas/AlertResponse"),
     *     )
     * )
     * @param Request $request
     * @param Alert $alert
     * @return JsonResponse
     */
    public function getAlertById(Request $request, Alert $alert): JsonResponse
    {
        $alertResource = new AlertResource($alert);

        return $this->successResponse($alertResource);
    }

    /**
     * @OA\Get(
     *     path="/alerts",
     *     summary="Get user alerts",
     *     description="Returns the user related alerts",
     *      tags={"Alert"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The user alerts response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserAlertsResponse"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAlerts(Request $request): JsonResponse
    {
        $user = $request->user();

        $alertResources = AlertResource::collection(new AlertResource($user->alerts));

        return $this->successResponse($alertResources);
    }

    /**
     * @OA\Post(
     *     path="/alerts",
     *     summary="Create new user alert",
     *     description="Creates a new user related alert",
     *      tags={"Alert"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Alert fillable properties",
     *       @OA\JsonContent(type="object",ref="#/components/schemas/AlertBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Alert response.",
     *      @OA\JsonContent(ref="#/components/schemas/AlertResponse"),
     *     )
     * )
     * @param AlertRequest $request
     * @return JsonResponse
     */
    public function createUserAlert(AlertRequest $request): JsonResponse
    {
        $alertData = $request->validated();
        $user = $request->user();
        $alertData['user_id'] = $user->id;

        $alert = Alert::create($alertData);

        $alertResource = new AlertResource($alert->fresh());

        return $this->successResponse($alertResource);
    }

    /**
     * @OA\Put(
     *     path="/alerts/{id}",
     *     summary="Update the current alert",
     *     description="Updates the user alert by id",
     *      tags={"Alert"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of alert",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Alert fillable properties",
     *       @OA\JsonContent(type="object",ref="#/components/schemas/AlertBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Updated alert response",
     *      @OA\JsonContent(ref="#/components/schemas/AlertResponse"),
     *     )
     * )
     * @param AlertRequest $request
     * @param Alert $alert
     * @return JsonResponse
     */
    public function updateUserAlert(AlertRequest $request, Alert $alert): JsonResponse
    {
        $alertData = $request->validated();
        $user = $request->user();

        $this->checkResourceOwner($alert, $user);

        $alert->update($alertData);

        $alertResource = new AlertResource($alert->fresh());

        return $this->successResponse($alertResource);
    }

    /**
     * @OA\Delete(
     *     path="/alerts/{id}",
     *     summary="Delete the user alert",
     *     description="Deletes the user alert by id",
     *      tags={"Alert"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of alert",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Delete alert response",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteAlertResponse"),
     *     )
     * )
     * @param Request $request
     * @param Alert $alert
     * @return JsonResponse
     */
    public function deleteUserAlert(Request $request, Alert $alert): JsonResponse
    {
        $user = $request->user();

        $this->checkResourceOwner($alert, $user);
        $alert->delete();

        return $this->successResponse([], "The alert was deleted success.");
    }

}
