<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCreateRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function create(ExpenseCreateRequest $request): JsonResponse
    {
        $expenseData = $request->validated();

        $userData = Auth::user();
        
        $expenseData['user_id'] = $userData->id;
        $expenseData['name'] = ucwords($expenseData['name']);
        $expenseData['date'] = now();

        try {
            $result = Expense::query()->create($expenseData);

            if ($result) {
                return (new ExpenseResource($result))->response()->setStatusCode(201);
            }

            return response()->json([
                "errors" => [
                    "message" => "Failed to create expense"
                ]
            ])->setStatusCode(400);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}
