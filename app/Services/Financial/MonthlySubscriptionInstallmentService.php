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
        $query = MonthlySubscriptionInstallment::with(['monthlySubscription', 'walletMovement']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_start')) {
            $query->whereDate('due_date', '>=', $request->date_start);
        }

        if ($request->has('date_end')) {
            $query->whereDate('due_date', '<=', $request->date_end);
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    public function payInstallment($id)
    {
        try {
            DB::beginTransaction();

            $installment = MonthlySubscriptionInstallment::findOrFail($id);
            
            if ($installment->status === 'paid') {
                throw new Exception('Esta parcela já está paga');
            }

            // Criar movimento na carteira
            $walletMovement = WalletMovement::create([
                'wallet_id' => 1, // Definir carteira padrão ou passar por parâmetro
                'movement_type' => 'credit',
                'description' => 'Pagamento de mensalidade #' . $installment->installment_number,
                'status' => 'confirmed',
                'date' => now(),
                'type' => 'monthly_subscription',
                'value' => $installment->value,
                'user_id' => auth()->id()
            ]);

            // Atualizar parcela
            $installment->update([
                'status' => 'paid',
                'wallet_movement_id' => $walletMovement->id
            ]);

            DB::commit();
            return $installment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}