<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarAppointmentResource;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Http\Request;

/**
 * @OA\Schema(type="object",schema="CalendarData",
 * @OA\Property(property="id",type="integer",example="1"),
 * @OA\Property(property="section",type="string",example="EVENT|APPOINTMENT"),
 * @OA\Property(property="type",type="string",example="ITP"),
 * @OA\Property(property="status",type="string",example="new"),
 * @OA\Property(property="car_plate_number",type="string",example="BV 01 ABC"),
 * @OA\Property(property="car_make_model",type="string",example="BMW Seria 3"),
 * @OA\Property(property="date",type="string",example="2020-10-30"),
 * )
 *
 * @OA\Schema(type="object",schema="CalendarResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",
 *     @OA\Items(ref="#/components/schemas/CalendarData")),
 * )
 *
 * Class CalendarController
 * @package App\Http\Controllers\API
 */
class CalendarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/calendar",
     *     summary="Get user events and appointments",
     *     description="Returns the user related events/appointments",
     *      tags={"Calendar"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="Flat array of evenst and appointments",
     *      @OA\JsonContent(ref="#/components/schemas/CalendarResponse"),
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(Request $request)
    {
        $user = $request->user();
        $alerts = $user->alerts()->whereHas('car')->with("car")->get();
        $appointments = $user->appointments()->where("from","!=", "NULL")->get();

        $eventsCollection = CalendarEventResource::collection(new CalendarEventResource($alerts));
        $appointmentsCollection = CalendarAppointmentResource::collection(new CalendarAppointmentResource($appointments));
        $data = $eventsCollection->merge($appointmentsCollection);

        return $this->successResponse($data);
    }
}
