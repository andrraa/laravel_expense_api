<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
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
}
