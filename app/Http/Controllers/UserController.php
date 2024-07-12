<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();

        $userData['full_name'] = strtoupper($userData['full_name']);
        $userData['password'] = Hash::make($userData['password']);

        try {
            $result = User::query()->create($userData);

            return (new UserResource($result))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                "errors" => [
                    "message" => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $userRequestData = $request->validated();

        try {
            $searchUser = User::query()
                ->where('email', $userRequestData['username'])
                ->orWhere('username', $userRequestData['username'])
                ->first();

            if (!$searchUser ||
                !Hash::check($userRequestData['password'], $searchUser->password)) {
                return response()->json([
                    "errors" => [
                        "message" => "Invalid username or password"
                    ]
                ], 404);
            }

            $token = Str::uuid()->toString();
            $tokenExpired = Carbon::now()->addHour();

            $searchToken = Token::query()->firstwhere('user_id', $searchUser->id);

            if ($searchToken) {
                $searchToken->token = $token;
                $searchToken->expired_at = $tokenExpired;
                $searchToken->save();
            } else {
                $tokenData = new Token();
                $tokenData->user_id = $searchUser->id;
                $tokenData->token = $token;
                $tokenData->is_active = 1;
                $tokenData->expired_at = Carbon::now()->addHour();
                $tokenData->save();
            }

            return response()->json([
                "data" => [
                    "token" => $token,
                    "expired_at" => $tokenExpired->toDateTimeString()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                "errors" => [
                    "message" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
