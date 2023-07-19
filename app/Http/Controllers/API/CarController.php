<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 * @OA\Schema(type="object",schema="CarBody",
 * required={"name"},
 * @OA\Property(property="name",type="string",example="My Car"),
 * @OA\Property(property="category",type="string",example="Autoturism/Automobil mixt"),
 * @OA\Property(property="subcategory",type="string",example="Autoturism"),
 * @OA\Property(property="registration_type",type="string",example="Inmatriculat"),
 * @OA\Property(property="fuel_type",type="string",example="Motorina"),
 * @OA\Property(property="vin_number",type="string",example="WBAAP71111GL33030"),
 * @OA\Property(property="make",type="string",example="BMW"),
 * @OA\Property(property="model",type="string",example="Seria 3"),
 * @OA\Property(property="manufacture_year",type="string",example="2002"),
 * @OA\Property(property="tyre_size",type="json",example={}),
 * @OA\Property(property="motor_power",type="string",example="110"),
 * @OA\Property(property="cylinder_capacity",type="string",example="1995"),
 * @OA\Property(property="number_places",type="string",example="5"),
 * @OA\Property(property="max_per_mass",type="string",example="1550"),
 * @OA\Property(property="civ_number",type="string",example="K884163"),
 * @OA\Property(property="description",type="string",example="My favorite car"),
 * @OA\Property(property="favorite",type="boolean",example="true"),
 * )
 *
 * @OA\Schema(type="object",schema="CarResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", ref="#/components/schemas/Car"),
 * )
 *
 * @OA\Schema(type="object",schema="DeleteCarResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * @OA\Schema(type="object",schema="UserCarsResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="array",
 * @OA\Items(@OA\Property(property="car", type="object",ref="#/components/schemas/Car"))),
 * )
 *
 * Class CarController
 * @package App\Http\Controllers\API
 *
 */
class CarController extends Controller
{

    /**
     * @OA\Get(
     *     path="/cars/{id}",
     *     summary="Get user car by id",
     *     description="Returns the car detailed data",
     *      tags={"Car"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of car",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Car response by car id.",
     *      @OA\JsonContent(ref="#/components/schemas/CarResponse"),
     *     )
     * )
     * @param Request $request
     * @param Car $car
     * @return JsonResponse
     */
    public function getCarById(Request $request, Car $car): JsonResponse
    {
        $carResource = new CarResource($car);

        return $this->successResponse($carResource);
    }

    /**
     * @OA\Get(
     *     path="/cars",
     *     summary="Get user cars",
     *     description="Returns the user related cars",
     *      tags={"Car"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="The user cars response.",
     *      @OA\JsonContent(ref="#/components/schemas/UserCarsResponse"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserCars(Request $request): JsonResponse
    {
        $user = $request->user();

        $carResources = CarResource::collection(new CarResource($user->cars));

        return $this->successResponse($carResources);
    }

    /**
     * @OA\Post(
     *     path="/cars",
     *     summary="Create new user car",
     *     description="Creates a new user related car",
     *      tags={"Car"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Car fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/CarBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Car response by car id.",
     *      @OA\JsonContent(ref="#/components/schemas/CarResponse"),
     *     )
     * )
     * @param CarRequest $request
     * @return JsonResponse
     */
    public function createUserCar(CarRequest $request): JsonResponse
    {
        $carData = $request->validated();
        $user = $request->user();
        $carData['user_id'] = $user->id;

        $car = Car::create($carData);

        $carResource = new CarResource($car);

        return $this->successResponse($carResource);
    }

    /**
     * @OA\Put(
     *     path="/cars/{id}",
     *     summary="Update the current car",
     *     description="Updates the user car by id",
     *      tags={"Car"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of car",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       description="Car fillable properties",
     *       @OA\JsonContent(ref="#/components/schemas/CarBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Updated car response",
     *      @OA\JsonContent(ref="#/components/schemas/CarResponse"),
     *     )
     * )
     * @param CarRequest $request
     * @param Car $car
     * @return JsonResponse
     */
    public function updateUserCar(CarRequest $request, Car $car): JsonResponse
    {
        $carData = $request->validated();
        $user = $request->user();

        $this->checkResourceOwner($car, $user);

        $car->update($carData);

        $carResource = new CarResource($car->fresh());

        return $this->successResponse($carResource);
    }

    /**
     * @OA\Delete(
     *     path="/cars/{id}",
     *     summary="Delete the user car",
     *     description="Deletes the user car by id",
     *      tags={"Car"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of car",
     *          required=true,
     *         @OA\Schema(type="integer",example="1")
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Delete car response",
     *      @OA\JsonContent(ref="#/components/schemas/DeleteCarResponse"),
     *     )
     * )
     * @param Request $request
     * @param Car $car
     * @return JsonResponse
     */
    public function deleteUserCar(Request $request, Car $car): JsonResponse
    {
        $user = $request->user();

        $this->checkResourceOwner($car, $user);

        $car->delete();

        return $this->successResponse([], "The car was deleted success.");
    }
}
