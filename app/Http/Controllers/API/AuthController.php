<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\ValidateCredentialChangeRequest;
use App\Http\Requests\ValidationRequest;
use App\Http\Resources\UserResource;
use App\Models\AuthRequest;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @OA\Schema(schema="RegisterBody",type="object",required={"phone"},
 * @OA\Property(property="phone", type="string", example="0741234567"),
 * @OA\Property(property="device_token", type="string", example="8d5c4ff5-de64-43a8-80bd-bf81cddb0250"),
 * )
 *
 * @OA\Schema(schema="ValidateBody",type="object",required={"phone","code","type"},
 * @OA\Property(property="code", type="string", example="glYyo"),
 * @OA\Property(property="phone", type="string", example="0741234567"),
 * @OA\Property(property="type", type="string", example="registration"),
 * )
 *
 * @OA\Schema(schema="PhoneChangeBody",type="object",required={"old_phone","new_phone"},
 * @OA\Property(property="old_phone", type="string", example="0741600285"),
 * @OA\Property(property="new_phone", type="string", example="0748931362"),
 * )
 *
 * @OA\Schema(schema="PhoneChangeValidateBody",type="object",required={"phone","code","type"},
 * @OA\Property(property="code", type="string", example="rHYzV"),
 * @OA\Property(property="phone", type="string", example="0741200358"),
 * @OA\Property(property="type", type="string", example="change"),
 * )
 *
 * @OA\Schema(type="object",schema="RegisterResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example="Code sent successfully."),
 * @OA\Property(property="data", type="object",
 *  @OA\Property(property="code_type", type="string", example="registration"),
 * )
 * )
 *
 * @OA\Schema(type="object",schema="UserChangeResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example="Code sent successfully."),
 * @OA\Property(property="data", type="object",
 *  @OA\Property(property="code_type", type="string", example="change"),
 * )
 * )
 *
 * @OA\Schema(type="object",schema="UserResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(type="object",schema="ValidateResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example="Code sent successfully."),
 * @OA\Property(property="data", type="object",
 * @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 * @OA\Property(property="access_token", type="string", example="1|QU2sClAvTlQ1n1IFDyRsBV4pBvbd4utpy3Il59qK"),
 * @OA\Property(property="token_type", type="string", example="bearer"),
 * @OA\Property(property="expires_in", type="integer", example="1679836683"),
 * )
 * )
 *
 * @OA\Schema(type="object",schema="LogoutResponse",
 * @OA\Property(property="status", type="string", example="success"),
 * @OA\Property(property="message", type="string", example=""),
 * @OA\Property(property="data", type="object", example={}),
 * )
 *
 * Class AuthController
 * @package App\Http\Controllers\API
 */
class AuthController extends Controller
{

    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register or log in",
     *     description="Register or log in by users phone number",
     *      tags={"Auth"},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Pass phone number",
     *       @OA\JsonContent(ref="#/components/schemas/RegisterBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="The code was sent!",
     *      @OA\JsonContent(ref="#/components/schemas/RegisterResponse"),
     *     )
     * )
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated["uuid"] = Str::uuid();
        $user = User::query()->where("phone", "=", $validated["phone"])->first();
        if(!$user) {
            $user = User::create($validated);
        } else if(array_key_exists("device_token", $validated) && $user) {
            $user->device_token = $validated["device_token"];
            $user->save();
        }

        $authRequest = $this->checkOrCreateAuthRequest($validated["phone"], AuthRequest::TYPE_REGISTRATION, $user->id);

        $response = [
            "code_type" => AuthRequest::TYPE_REGISTRATION
        ];
        $this->smsService->sendTextMessage($authRequest);

        return $this->successResponse($response, "Code sent successfully");
    }


//    public function login(LoginRequest $request): JsonResponse
//    {
//        $phoneNr = $request->get("phone");
//        $user = $this->getUserByPhoneOrThrowError($phoneNr);
//        $newAuthRequest = $this->checkOrCreateAuthRequest($phoneNr, AuthRequest::TYPE_LOGIN, $user->id);
//
//        $responseData = [
//            "status" => "success",
//            "message" => "Code sent successfully.",
//            "code_type" => AuthRequest::TYPE_LOGIN,
//        ];
//
//        $this->smsService->sendTextMessage($newAuthRequest);
//
//        return response()->json($responseData);
//    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Log out",
     *     description="Log out user from app. Delete the acces token.",
     *      tags={"Auth"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\Response(
     *      response="200",
     *      description="Successfully logged out.",
     *      @OA\JsonContent(ref="#/components/schemas/LogoutResponse"),
     *     )
     * )
     * @param LogoutRequest $request
     * @return JsonResponse
     */
    public function logout(LogoutRequest $request)
    {
        $user = $request->user();
        if(!$user) {
            return $this->errorResponse([],"User not found");
        }
        $user->tokens()->delete();

        return $this->successResponse();
    }

    /**
     * @OA\Post(
     *     path="/validate",
     *     summary="Validate code",
     *     description="Validate the code sent to the users phone number.",
     *      tags={"Auth"},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Pass the code, phone number and code type.",
     *       @OA\JsonContent(ref="#/components/schemas/ValidateBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="the user data and acces token.(Bearer)",
     *      @OA\JsonContent(ref="#/components/schemas/ValidateResponse"),
     *     )
     * )
     * @param ValidationRequest $request
     * @return JsonResponse
     */
    public function validateCode(ValidationRequest $request): JsonResponse
    {
        $validated = $request->validated(); //tNs8JSE2
        $authRequest = $this->getAuthRequestByParams($validated);
        $user = $this->getUserByPhoneOrThrowError($validated["phone"]);

        $authRequest->update(["confirmed" => true, "user_id" => $user->id]);

        return $this->successResponse($this->validateCodeResponse($user));
    }

    /**
     * @OA\Get(
     *     path="/refresh-token",
     *     summary="Refresh token",
     *     description="Delete the current user token and returns the new one",
     *      tags={"Auth"},
     *     @OA\Response(
     *      response="200",
     *      description="The user data and acces token.(Bearer)",
     *      @OA\JsonContent(ref="#/components/schemas/ValidateResponse"),
     *     )
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getRefreshToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->successResponse($this->validateCodeResponse($user), "Token refresh done");
    }

    /**
     * @OA\Post(
     *     path="/user/change/validate",
     *     summary="Validate the new phone number.",
     *     description="Validates the new user phone number with code sent to phone.",
     *      tags={"User"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Sends the code, phone number and code type for validation.",
     *       @OA\JsonContent(ref="#/components/schemas/PhoneChangeValidateBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns updated user data.",
     *      @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     )
     * )
     * @param ValidateCredentialChangeRequest $request
     * @return JsonResponse
     */
    public function validateNewCredentials(ValidateCredentialChangeRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['user_id'] = $user->id;
        $authRequest = $this->getAuthRequestByParams($validated);

        $authRequest->update(["confirmed" => true, "user_id" => $user->id]);

        $user->update(["phone" => $validated["phone"]]);

        $response = new UserResource($user->fresh());

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/user/change",
     *     summary="Change user phone number.",
     *     description="Requests a code for change to the new number.",
     *      tags={"User"},
     *     security={{"bearer_Auth":{}}},
     *     @OA\RequestBody(
     *       required=true,
     *       description="Pass the old and new phone number for validation.",
     *       @OA\JsonContent(ref="#/components/schemas/PhoneChangeBody"),
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the success message + code type.",
     *      @OA\JsonContent(ref="#/components/schemas/UserChangeResponse"),
     *     )
     * )
     *
     * @param ChangeLoginRequest $request
     * @return JsonResponse
     */
    public function changeCredentials(ChangeLoginRequest $request)
    {

        $user = $request->user();
        $validated = $request->validated();
        if($user->phone !== $validated["old_phone"]) {
            return $this->errorResponse([],"User old phone must match");
        }
        $authRequest = $this->checkOrCreateAuthRequest($validated["new_phone"], AuthRequest::TYPE_CHANGE, $user->id);
        $this->smsService->sendTextMessage($authRequest);

        $response = [
            "code_type" => AuthRequest::TYPE_CHANGE,
        ];
        return $this->successResponse($response, "Code sent successfully");
    }


    protected function validateCodeResponse(User $user): array
    {
        return [
            'user' => new UserResource($user),
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addMonths(1)->timestamp,
        ];
    }

}
