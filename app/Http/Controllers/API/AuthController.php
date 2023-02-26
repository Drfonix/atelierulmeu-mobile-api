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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    private SmsService $smsService;

    public function __construct()
    {
        $this->smsService = new SmsService();
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated["uuid"] = Str::uuid();
        $user = User::create($validated);
        $authRequest = $this->checkOrCreateAuthRequest($validated["phone"], AuthRequest::TYPE_REGISTRATION, $user->id);

        $responseData = [
            "status" => "success",
            "message" => "Code sent successfully.",
            "code_type" => AuthRequest::TYPE_REGISTRATION
        ];
        $this->smsService->sendTextMessage($authRequest);

        return response()->json($responseData);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $phoneNr = $request->get("phone");
        $user = $this->getUserByPhoneOrThrowError($phoneNr);
        $newAuthRequest = $this->checkOrCreateAuthRequest($phoneNr, AuthRequest::TYPE_LOGIN, $user->id);

        $responseData = [
            "status" => "success",
            "message" => "Code sent successfully.",
            "code_type" => AuthRequest::TYPE_LOGIN,
        ];

        $this->smsService->sendTextMessage($newAuthRequest);

        return response()->json($responseData);
    }

    public function logout(LogoutRequest $request)
    {
        $user = $request->user();
        if(!$user) {
            return $this->noUserRequestResponse();
        }
        $user->tokens()->delete();

        return $this->successResponse();
    }

    public function validateCode(ValidationRequest $request): JsonResponse
    {
        $validated = $request->validated(); //tNs8JSE2
        $authRequest = $this->getAuthRequestByParams($validated);
        $user = $this->getUserByPhoneOrThrowError($validated["phone"]);

        $authRequest->update(["confirmed" => true, "user_id" => $user->id]);

        return response()->json($this->validateCodeResponse($user));
    }

    public function validateNewCredentials(ValidateCredentialChangeRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['user_id'] = $user->id;
        $authRequest = $this->getAuthRequestByParams($validated);

        $authRequest->update(["confirmed" => true, "user_id" => $user->id]);

        $user->update(["phone" => $validated["phone"]]);

        $response = new UserResource($user->fresh());

        return response()->json($response);
    }

    public function changeCredentials(ChangeLoginRequest $request)
    {

        $user = $request->user();
        $validated = $request->validated();
        if($user->phone !== $validated["old_phone"]) {
            return $this->wrongUserOldPhoneResponse();
        }
        $authRequest = $this->checkOrCreateAuthRequest($validated["new_phone"], AuthRequest::TYPE_CHANGE, $user->id);

        $responseData = [
            "status" => "success",
            "message" => "Code sent successfully",
            "code_type" => AuthRequest::TYPE_CHANGE,
        ];

        $this->smsService->sendTextMessage($authRequest);

        return response()->json($responseData);
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
