<?php

namespace App\Services\Financial;

use App\Models\Financial\MonthlySubscription;
use App\Models\Financial\WalletMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class MonthlySubscriptionService
{
    public function getAllSubscriptions($request)
    {
        $query = MonthlySubscription::with(['user', 'walletMovement', 'subscriptionPlan']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_start')) {
            $query->whereDate('start_date', '>=', $request->date_start);
        }

        if ($request->has('date_end')) {
            $query->whereDate('start_date', '<=', $request->date_end);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getSubscriptionById($id)
    {
        return MonthlySubscription::with(['user', 'walletMovement', 'subscriptionPlan'])->findOrFail($id);
    }

    public function createSubscription($data)
    {
        try {
            DB::beginTransaction();

            $walletMovement = WalletMovement::create([
                'type' => 'debit',
                'value' => $data['value'],
                'wallet_id' => $data['wallet_id'],
                'user_id' => $data['user_id'],
                'comments' => $data['comments'] ?? 'Monthly subscription payment'
            ]);

            $subscription = MonthlySubscription::create([
                'user_id' => $data['user_id'],
                'wallet_movement_id' => $walletMovement->id,
                'subscription_plan_id' => $data['subscription_plan_id'],
                'start_date' => $data['start_date'],
                'next_due_date' => date('Y-m-d', strtotime('+1 month', strtotime($data['start_date']))),
                'status' => 'active'
            ]);

            DB::commit();
            return $subscription;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateSubscription($id, $data)
    {
        try {
            DB::beginTransaction();
            
            $subscription = MonthlySubscription::findOrFail($id);
            $subscription->update($data);

            DB::commit();
            return $subscription;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSubscription($id)
    {
        try {
            DB::beginTransaction();

            $subscription = MonthlySubscription::findOrFail($id);
            
            if ($subscription->walletMovement) {
                $subscription->walletMovement->delete();
            }
            
            $subscription->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}