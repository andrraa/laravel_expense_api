<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        $userData = Auth::user();

        $categoryData = $request->validated();
        $categoryData['name'] = ucwords($categoryData['name']);
        $categoryData['user_id'] = $userData->id;
        $categoryData['is_active'] = 1;

        try {
            $checkData = Category::query()
                ->where('name', $categoryData['name'])
                ->where('user_id', $userData->id)
                ->first();

            if ($checkData) {
                return response()->json([
                    "errors" => [
                        "message" => "Category already exists"
                    ]
                ], 400);
            }

            $result = Category::query()->create($categoryData);

            if ($result) {
                return (new CategoryResource($result))->response()->setStatusCode(201);
            }

            return response()->json([
                "errors" => [
                    "message" => "Category not created"
                ]
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}
