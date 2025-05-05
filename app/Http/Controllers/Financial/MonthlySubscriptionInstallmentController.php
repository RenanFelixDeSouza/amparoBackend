<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Services\Financial\MonthlySubscriptionInstallmentService;
use Illuminate\Http\Request;

class MonthlySubscriptionInstallmentController extends Controller
{
    protected $installmentService;

    public function __construct(MonthlySubscriptionInstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    public function index(Request $request)
    {
        try {
            $installments = $this->installmentService->getAllInstallments($request);
            return response()->json($installments);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function pay($id)
    {
        try {
            $installment = $this->installmentService->payInstallment($id);
            return response()->json($installment);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}