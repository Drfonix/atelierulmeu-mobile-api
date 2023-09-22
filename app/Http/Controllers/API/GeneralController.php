<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertType;
use App\Models\AppointmentRequest;
use App\Models\CarCategory;
use App\Models\CarFuelType;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarRegistrationType;
use App\Models\CarSubCategory;
use App\Models\DocumentType;
use App\Models\RecurrentType;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 * @OA\Schema(type="object",schema="GeneralInfoResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example="Constants information"),
 * @OA\Property(property="data", type="object",
 * @OA\Property(property="appointment_statuses", type="array", example={"new","accepted"},@OA\Items()),
 * @OA\Property(property="car_registration_types", type="array", example={"Inmatriculat","Inregistrat"},@OA\Items()),
 * @OA\Property(property="car_fuel_types", type="array", example={"Benzina","Motorina"},@OA\Items()),
 * @OA\Property(property="alert_types", type="array", example={"ITP","RCA"},@OA\Items()),
 * @OA\Property(property="document_types", type="array", example={"Asigurare","CASCO"},@OA\Items()),
 * @OA\Property(property="car_categories", type="array", example={{"id": 10, "name": "Autoturism"},{"id": 9, "name": "Autotractor"}},@OA\Items()),
 * @OA\Property(property="car_sub_categories", type="array", example={{"parent_id": 3, "name": "Automobil mixt"},{"parent_id": 3, "name": "SUV"}},@OA\Items()),
 * @OA\Property(property="car_makes", type="array", example={{"id": 10, "name":"Audi"},{"id": 5, "name":"BMW"}},@OA\Items()),
 * @OA\Property(property="car_models", type="array", example={{"make_id": 10, "name":"A6"}, {"make_id": 5, "name":"Seria 3"}},@OA\Items()),
 * ))
 *
 * Class GeneralController
 * @package App\Http\Controllers\API
 */
class GeneralController extends Controller
{

    /**
     * @var ImageService
     */
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @OA\Get(
     *     path="/information",
     *     summary="Get constant informations",
     *     description="Returns a set of data to user in app",
     *      tags={"General"},
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

        $user = $request->user();
        $generalAlertTypes = AlertType::all()->pluck('name');

        $userCustomAlertTypes = Alert::query()->where('user_id', $user->id)
            ->distinct()->pluck('type');

        $alertTypes = $generalAlertTypes->merge($userCustomAlertTypes)->unique()->sort()->values();

        $response = [
            "appointment_statuses" => AppointmentRequest::STATUS,
            "car_registration_types" => CarRegistrationType::all()->pluck('name'),
            "car_fuel_types" => CarFuelType::all()->pluck('name'),
            "alert_types" => $alertTypes,
            "document_types" => DocumentType::all()->pluck('name'),
            "car_categories" => CarCategory::all(),
            "car_sub_categories" => CarSubCategory::all(),
            "recurrent_types" => RecurrentType::all(),
            "car_makes" => $carMakes,
            "car_models" => $carModels,
        ];

//        $userFilesDiskInfo = $this->imageService->getUserFilesAndSizes($request->user());

        return $this->successResponse($response);
    }

}
