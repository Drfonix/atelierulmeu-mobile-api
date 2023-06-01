<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 * @OA\Schema(type="object",schema="GeneralInfoResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example="Constants information"),
 * @OA\Property(property="data", type="object",
 * @OA\Property(property="car_categories", type="array", example={"Autoturism","Autotractor"},@OA\Items()),
 * @OA\Property(property="car_sub_categories", type="array", example={"Automobil mixt","SUV"},@OA\Items()),
 * @OA\Property(property="car_registration_types", type="array", example={"Inmatriculat","Inregistrat"},@OA\Items()),
 * @OA\Property(property="car_fuel_types", type="array", example={"Benzina","Motorina"},@OA\Items()),
 * @OA\Property(property="car_makes", type="array", example={"Audi","BMW"},@OA\Items()),
 * @OA\Property(property="car_models", type="array", example={"A6", "Seria 3"},@OA\Items()),
 * @OA\Property(property="notification_types", type="array", example={"ITP","RCA"},@OA\Items()),
 * )
 * )
 *
 * Class GeneralController
 * @package App\Http\Controllers\API
 */
class GeneralController extends Controller
{

    /**
     * @OA\Get(
     *     path="/information",
     *     summary="Get constant informations",
     *     description="Returns a set of data to user in app",
     *     tags={"general"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="Returns the constants informations",
     *      @OA\JsonContent(ref="#/components/schemas/GeneralInfoResponse"),
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInformation(Request $request): JsonResponse
    {
        $carMakes = CarMake::query()->select("id","name")->get();
        $carModels = CarModel::query()->select("make_id","name")->get();

        $response = [
            "car_categories" => Car::CAR_CATEGORIES,
            "car_sub_categories" => Car::CAR_SUB_CATEGORIES,
            "car_registration_types" => Car::CAR_REGISTRATION_TYPES,
            "car_fuel_types" => Car::CAR_FUEL_TYPES,
            "notification_types" => Car::NOTIFICATION_TYPES,
            "car_makes" => $carMakes,
            "car_models" => $carModels,
        ];

        return $this->successResponse($response);
    }

}
