<?php

namespace App\Services\Financial;

use App\Models\Financial\MonthlySubscriptionInstallment;
use App\Models\Financial\WalletMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class MonthlySubscriptionInstallmentService
{
    public function getAllInstallments($request)
    {
        $query = MonthlySubscriptionInstallment::with(['monthlySubscription.subscriptionPlan', 'walletMovement']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_start')) {
            $query->whereDate('due_date', '>=', $request->date_start);
        }

        if ($request->has('date_end')) {
            $query->whereDate('due_date', '<=', $request->date_end);
        }

        $sortColumn = $request->input('sort_column', 'due_date');
        $sortOrder = $request->input('sort_order', 'asc');
        $page = $request->input('page', 1);
        $limit = $request->input('per_page', 5);

        return $query->orderBy($sortColumn, $sortOrder)
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function payInstallment($id, array $data)
    {
        try {
            DB::beginTransaction();

            $installment = MonthlySubscriptionInstallment::findOrFail($id);

            if ($installment->status === 'paid') {
                throw new Exception('Esta parcela já está paga');
            }

            $walletMovement = WalletMovement::create([
                'wallet_id' => $data['wallet_id'],
                'movement_type' => 'inflow',
                'description' => 'Pagamento de mensalidade #' . $installment->installment_number,
                'status' => 'confirmed',
                'date' => $data['payment_date'] ?? now(),
                'type' => 'entrada',
                'value' => $installment->value,
                'chart_of_account_id' => $installment->monthlySubscription->subscriptionPlan->chart_of_account_id,
                'user_id' => auth()->user()->id,
                'observation' => $data['observation'] ?? null,
                'account_name' => $data['account_name'] ?? null
            ]);

            $installment->update([
                'status' => 'paid',
                'wallet_movement_id' => $walletMovement->id,
                'payment_date' => $data['payment_date'] ?? now()
            ]);

            DB::commit();
            return $installment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}