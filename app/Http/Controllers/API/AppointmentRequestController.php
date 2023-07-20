<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequestRequest;
use App\Http\Resources\AppointmentRequestResource;
use App\Models\AppointmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(type="object",schema="AppointmentRequestBody",
 * required={"car_plate_number", "client_name", "phone", "service_data"},
 * @OA\Property(property="title",type="string",example="For tyre change"),
 * @OA\Property(property="status",type="string",example="new"),
 * @OA\Property(property="car_plate_number",type="string",example="BV 01 ABC"),
 * @OA\Property(property="client_name",type="string",example="Jhon Doe"),
 * @OA\Property(property="car_make_model",type="string",example="BMW 320D E46"),
 * @OA\Property(property="phone",type="string",example="0740123456"),
 * @OA\Property(property="from",type="string",example="2023-07-25 10:00"),
 * @OA\Property(property="to",type="string",example="2023-07-25 18:00"),
 * @OA\Property(property="duration",type="double",example="6.5"),
 * @OA\Property(property="requested_services",type="array",example={"Tyre change", "Oil change"}, @OA\Items()),
 * @OA\Property(property="meta_data",type="object",example={"serviceStatus": "accepted"}),
 * @OA\Property(property="service_data",type="object",example={"user": {"name": "Jhon"}, "service": {"name": "Demo Tyre Service"}}),
 * )
 *
 * @OA\Schema(type="object",schema="AppointmentRequestResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", ref="#/components/schemas/AppointmentRequest"),
 * )
 *
 * @OA\Schema(type="object",schema="DeleteAppointmentRequestResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserAppointmentRequestResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",
 * @OA\Items(type="object",ref="#/components/schemas/AppointmentRequest"))
 * )
 *
 * Class AppointmentRequestController
 * @package App\Http\Controllers\API
 */
class AppointmentRequestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/appointments/{id}",
     *     summary="Get user appointment by id",
     *     description="Returns the appointment detailed data",
     *      tags={"Appointment"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of appointment",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Appointment response by id.",
     *      @OA\JsonContent(ref="#/components/schemas/AppointmentRequestResponse"),
     *     )
     * )
     * @param Request $request
     * @param AppointmentRequest $appointment
     * @return JsonResponse
     */
    public function getById(Request $request, AppointmentRequest $appointment): JsonResponse
    {

        $appointmentResource = new AppointmentRequestResource($appointment);

        return $this->successResponse($appointmentResource);
    }

    /**
     * @OA\Get(
     *     path="/appointments",
     *     summary="Get user appointments",
     *     description="Returns the user related appointments",
     *      tags={"Appointment"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The user appointments response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserAppointmentRequestResponse"),
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAppointments(Request $request): JsonResponse
    {
        $user = $request->user();

        $appointmentResources = AppointmentRequestResource::collection(new AppointmentRequestResource($user->appointments));

        return $this->successResponse($appointmentResources);
    }

    /**
     * @OA\Post(
     *     path="/appointments",
     *     summary="Create new user appointment",
     *     description="Creates a new user related appointment",
     *      tags={"Appointment"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Appointment fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/AppointmentRequestBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Appointment response.",
     *      @OA\JsonContent(ref="#/components/schemas/AppointmentRequestResponse"),
     *     )
     * )
     *
     * @param AppointmentRequestRequest $request
     * @return JsonResponse
     */
    public function createUserAppointment(AppointmentRequestRequest $request): JsonResponse
    {
        $appointmentData = $request->validated();
        $user = $request->user();
        $appointmentData['user_id'] = $user->id;

        $appointment = AppointmentRequest::create($appointmentData);

        $appointmentResource = new AppointmentRequestResource($appointment);

        return $this->successResponse($appointmentResource);
    }

    /**
     * @OA\Put(
     *     path="/appointments/{id}",
     *     summary="Update the current appointment",
     *     description="Updates the user appointment by id",
     *      tags={"Appointment"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of appointment",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Appointment fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/AppointmentRequestBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Updated appointment response",
     *      @OA\JsonContent(ref="#/components/schemas/AppointmentRequestResponse"),
     *     )
     * )
     *
     * @param AppointmentRequestRequest $request
     * @param AppointmentRequest $appointment
     * @return JsonResponse
     */
    public function updateUserAppointment(AppointmentRequestRequest $request, AppointmentRequest $appointment): JsonResponse
    {
        $appointmentData = $request->validated();
        $user = $request->user();

        $this->checkResourceOwner($appointment, $user);

        $appointment->update($appointmentData);

        $appointmentResource = new AppointmentRequestResource($appointment->fresh());

        return $this->successResponse($appointmentResource);
    }

    /**
     * @OA\Delete(
     *     path="/appointments/{id}",
     *     summary="Delete the user appointment",
     *     description="Deletes the user appointment by id",
     *      tags={"Appointment"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of appointment",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Delete appointment response",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteAppointmentRequestResponse"),
     *     )
     * )
     * @param Request $request
     * @param AppointmentRequest $appointment
     * @return JsonResponse
     */
    public function deleteUserAppointment(Request $request, AppointmentRequest $appointment): JsonResponse
    {
        $user = $request->user();

        $this->checkResourceOwner($appointment, $user);

        $appointment->delete();

        return $this->successResponse([], "The appointment was deleted success.");
    }
}
