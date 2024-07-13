<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;
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

        }
    }
}
