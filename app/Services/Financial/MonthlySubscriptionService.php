<?php

namespace App\Services\Financial;

use App\Models\Financial\MonthlySubscription;
use App\Models\Financial\MonthlySubscriptionInstallment;
use Illuminate\Support\Facades\DB;
use Exception;

class MonthlySubscriptionService
{
    public function getAllSubscriptions($request)
    {
        $query = MonthlySubscription::query();
        return $query->orderBy('id', 'desc')->get();
    }
    public function createSubscription($data)
    {
        try {
            DB::beginTransaction();

            $subscription = MonthlySubscription::create([
                'user_id' => $data['user_id'],
                'subscription_plan_id' => $data['plan_id'],
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

    public function generateInstallments($subscriptionId)
    {
        try {
            DB::beginTransaction();
            
            $subscription = MonthlySubscription::with('subscriptionPlan')->findOrFail($subscriptionId);
            $plan = $subscription->subscriptionPlan;
            $currentDate = date('Y-m-d');
            
            for ($i = 1; $i <= $plan->duration_months; $i++) {
                $dueDate = date('Y-m-d', strtotime($subscription->start_date . " +{$i} months"));
                
                MonthlySubscriptionInstallment::create([
                    'monthly_subscription_id' => $subscription->id,
                    'installment_number' => $i,
                    'total_installments' => $plan->duration_months,
                    'value' => $plan->value,
                    'due_date' => $dueDate,
                    'status' => $currentDate > $dueDate ? 'overdue' : 'pending'
                ]);
            }

            DB::commit();
            return $subscription->fresh()->installments;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}