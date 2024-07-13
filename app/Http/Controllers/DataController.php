<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function getTotalExpense(): JsonResponse
    {
        $userData = Auth::user();

        try {
            $income = Expense::query()
                ->where('type_id', 1)
                ->where('user_id', $userData->id)
                ->get()
                ->sum(function ($item) {
                    return (float)$item->amount;
                });

            $outcome = Expense::query()
                ->where('type_id', 2)
                ->where('user_id', $userData->id)
                ->get()
                ->sum(function ($item) {
                    return (float)$item->amount;
                });

            return response()->json([
                "data" => [
                    "total_income" => $income,
                    "total_outcome" => $outcome
                ]
            ])->setStatusCode(200);

        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function getTotalExpenseByCategory(Request $request): JsonResponse
    {
        $userData = Auth::user();

        $requestType = $request->query('type');

        if ($requestType === 'income') {
            $typeId = 1;
        } else if ($requestType === 'outcome') {
            $typeId = 2;
        } else {
            return response()->json([
                "errors" => [
                    "message" => "Missing expense type"
                ]
            ]);
        }

        try {
            $outcomes = Expense::query()
                ->where('type_id', $typeId)
                ->where('user_id', $userData->id)
                ->get()
                ->groupBy('category_id')
                ->map(function ($items) {
                    return $items->sum(function ($item) {
                        return (float)$item->amount;
                    });
                })->sortByDesc(function ($total) {
                    return $total;
                });

            return response()->json($outcomes);
        } catch (Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }
}
