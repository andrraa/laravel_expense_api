<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCreateRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpenseType;
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

    public function update(int $id, ExpenseUpdateRequest $request): JsonResponse
    {
        $userData = Auth::user();

        $expenseData = $request->validated();

        try {
            $data = Expense::query()
                ->where('id', $id)
                ->where('user_id', $userData->id)
                ->first();

            if (!$data) {
                return response()->json([
                    "errors" => [
                        "message" => "Expenses not found"
                    ]
                ])->setStatusCode(404);
            }

            $data->type_id = $expenseData['type_id'];
            $data->category_id = $expenseData['category_id'];
            $data->name = ucwords($expenseData['name']);
            $data->amount = $expenseData['amount'];

            if ($data->save()) {
                return (new ExpenseResource($data))->response()->setStatusCode(200);
            }

            return response()->json([
                "errors" => [
                    "message" => "Failed to update expense"
                ]
            ])->setStatusCode(400);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function view(int $id): JsonResponse
    {
        $userData = Auth::user();

        try {
            $expenseData = Expense::query()
                ->where('id', $id)
                ->where('user_id', $userData->id)
                ->first();

            if (!$expenseData) {
                return response()->json([
                    "errors" => [
                        "message" => "Expense not found"
                    ]
                ])->setStatusCode(404);
            }

            return (new ExpenseResource($expenseData))->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        $userData = Auth::user();

        try {
            $expenseData = Expense::query()
                ->where('id', $id)
                ->where('user_id', $userData->id)
                ->first();

            if (!$expenseData) {
                return response()->json([
                    "errors" => [
                        "message" => "Expense not found"
                    ]
                ])->setStatusCode(404);
            }

            if ($expenseData->delete()) {
                return response()->json()->setStatusCode(204);
            }

            return response()->json([
                "errors" => [
                    "message" => "Failed to delete expense"
                ]
            ])->setStatusCode(400);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        $userData = Auth::user();

        try {
            $expenses = $userData->expenses;

            if (!$expenses) {
                return response()->json([
                    "errors" => [
                        "messaage" => "Expense not found"
                    ]
                ])->setStatusCode(404);
            }

            $expenseCollection = ExpenseResource::collection($expenses);

            return $expenseCollection->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function expenseType(): JsonResponse
    {
        Auth::user();

        try {
            $expense = ExpenseType::all();

            if (!$expense) {
                return response()->json([
                    "errors" => [
                        "message" => "Expense type not found"
                    ]
                ], 404);
            }

            return response()->json([
                "data" => $expense
            ]);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}
