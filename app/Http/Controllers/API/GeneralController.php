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
use App\Models\DefaultSelect;
use App\Models\DocumentType;
use App\Models\MobileAppData;
use App\Models\RecurrentType;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $user = $request->user();

        $carMakes = Cache::remember('car-make', 604800, static function(){
           return CarMake::query()->select("id","name")->get();
        });
        $carModels = Cache::remember('car-model', 604800, static function(){
            return CarModel::query()->select("make_id","name")->get();
        });
        $generalAlertTypes =  Cache::remember('alert-types', 604800,static function(){
            return AlertType::all()->pluck('name');
        });

        $carRegistrationTypes =  Cache::remember('car-reg-types', 604800,static function(){
            return CarRegistrationType::all()->pluck('name');
        });

        $carFuelTypes =  Cache::remember('car-fuel-types', 604800,static function(){
            return CarFuelType::all()->pluck('name');
        });

        $documentTypes =  Cache::remember('document-types', 604800, static function(){
            return DocumentType::all()->pluck('name');
        });

        $carCategories =  Cache::remember('car-categories', 604800, static function(){
            return CarCategory::all();
        });

        $carSubCategories =  Cache::remember('car-sub-categories', 604800, static function(){
            return CarSubCategory::all();
        });

        $recurrentTypes =  Cache::remember('recurrent-types', 604800, static function(){
            return RecurrentType::all();
        });

        $defaultSelects =  Cache::remember('default-selects', 604800, static function(){
            return DefaultSelect::all()->pluck("value", "key");
        });

        $desiredAppVersion = MobileAppData::where("key", "desired_app_version")->pluck("value");

        $userCustomAlertTypes = Alert::query()->where('user_id', $user->id)
            ->distinct()->pluck('type');

        $alertTypes = $generalAlertTypes->merge($userCustomAlertTypes)->unique()->sort()->values();

        $response = [
            "desired_app_version" => $desiredAppVersion[0] ?? null,
            "default_selects" => $defaultSelects,
            "appointment_statuses" => AppointmentRequest::STATUS,
            "car_registration_types" => $carRegistrationTypes,
            "car_fuel_types" => $carFuelTypes,
            "alert_types" => $alertTypes,
            "document_types" => $documentTypes,
            "car_categories" => $carCategories,
            "car_sub_categories" => $carSubCategories,
            "recurrent_types" => $recurrentTypes,
            "car_makes" => $carMakes,
            "car_models" => $carModels,
        ];

//        $userFilesDiskInfo = $this->imageService->getUserFilesAndSizes($request->user());

        return $this->successResponse($response);
    }

}
