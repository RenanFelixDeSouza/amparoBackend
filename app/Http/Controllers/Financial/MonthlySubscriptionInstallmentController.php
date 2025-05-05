<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Resources\Financial\MonthlySubscriptionInstallmentResource;
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
        $installments = $this->installmentService->getAllInstallments($request);
        return MonthlySubscriptionInstallmentResource::collection($installments);
    }

    public function pay($id, Request $request)
    {
        try {
            $data = $request->only(['payment_date', 'observation', 'wallet_id', 'account_name']);
            $installment = $this->installmentService->payInstallment($id, $data);
            return new MonthlySubscriptionInstallmentResource($installment);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}