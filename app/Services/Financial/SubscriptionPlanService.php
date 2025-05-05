<?php

namespace App\Services\Financial;

use App\Models\Financial\SubscriptionPlan;
use Exception;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanService
{
    public function getAll($filters = [])
    {
        $query = SubscriptionPlan::query();
        
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $plan = SubscriptionPlan::create($data);    
            DB::commit();
            return $plan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            $plan = SubscriptionPlan::findOrFail($id);
            $plan->update($data);
            DB::commit();
            return $plan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}