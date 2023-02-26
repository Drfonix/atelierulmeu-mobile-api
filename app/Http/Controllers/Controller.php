<?php

namespace App\Http\Controllers;

use App\Models\AuthRequest;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use  DispatchesJobs, ValidatesRequests;

    public function checkOrCreateAuthRequest(string $phoneNr, string $type, int $userId)
    {
        $params = [
            "phone" => $phoneNr,
            "type" => $type,
            "confirmed" => false,
            "user_id" => $userId
        ];
        $authRequest = $this->getAuthRequest($params);
        if(!$authRequest) {
            $authRequest = AuthRequest::create([
                'phone' => $phoneNr,
                'code' => AuthRequest::generateUniqueCode(),
                'type' => $type,
                'user_id' => $userId,
                'confirmed' => false,
                'created_at' => now()
            ]);
        } else {
            $authRequest->update([
                "code" => AuthRequest::generateUniqueCode()
            ]);
        }
        return $authRequest->fresh();
    }

    protected function noUserRequestResponse()
    {
        return response()->json([
            "status" => "error",
            "message" => "User not found."
        ],Response::HTTP_BAD_REQUEST);
    }

    protected function wrongUserOldPhoneResponse()
    {
        return response()->json([
            "status" => "error",
            "message" => "User old phone must match."
        ],Response::HTTP_BAD_REQUEST);
    }

    protected function noAuthRequestResponse()
    {
        return response()->json([
            "status" => "error",
            "message" => "Code not found."
        ],Response::HTTP_BAD_REQUEST);
    }

    protected function getUserByPhoneOrThrowError(string $phone)
    {
        $user = User::query()->where(["phone" => $phone])->first();
        if(!$user) {
            throw new HttpResponseException(
                $this->noUserRequestResponse()
            );
        }
        return $user;
    }

    protected function getAuthRequestByParams(array $params)
    {
        $authRequest = AuthRequest::query()->where($params)->orderByDesc('created_at')->first();
        if(!$authRequest || $authRequest->confirmed) {
            throw new HttpResponseException(
                $this->noAuthRequestResponse()
            );
        }
        return $authRequest;
    }

    protected function getAuthRequest(array $params)
    {
        return AuthRequest::query()->where($params)->orderByDesc('created_at')->first();
    }

    protected function successResponse()
    {
        return response()->json(["status" => "success"]);
    }

}
