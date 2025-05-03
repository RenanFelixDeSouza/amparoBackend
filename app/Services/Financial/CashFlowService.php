<?php

namespace App\Services\Financial;

use App\Models\Financial\CashFlow;
use App\Models\Financial\WalletMovement;
use Illuminate\Support\Facades\DB;
use Exception;

class CashFlowService
{
    public function getAll($filters = [])
    {
        $query = CashFlow::with(['user', 'walletMovement']);
        
        if (isset($filters['flow_type'])) {
            $query->where('flow_type', $filters['flow_type']);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_start'])) {
            $query->whereDate('date', '>=', $filters['date_start']);
        }

        if (isset($filters['date_end'])) {
            $query->whereDate('date', '<=', $filters['date_end']);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function getById($id)
    {
        return CashFlow::with(['user', 'walletMovement'])->findOrFail($id);
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $walletMovement = WalletMovement::create([
                'type' => $data['flow_type'] === 'inflow' ? 'credit' : 'debit',
                'value' => $data['value'],
                'wallet_id' => $data['wallet_id'],
                'user_id' => $data['user_id'],
                'comments' => $data['comments'] ?? null
            ]);

            $cashFlow = CashFlow::create([
                'flow_type' => $data['flow_type'],
                'description' => $data['description'],
                'value' => $data['value'],
                'date' => $data['date'],
                'status' => $data['status'] ?? 'pending',
                'comments' => $data['comments'] ?? null,
                'user_id' => $data['user_id'],
                'wallet_movement_id' => $walletMovement->id
            ]);

            DB::commit();
            return $cashFlow;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            
            $cashFlow = CashFlow::findOrFail($id);
            $cashFlow->update($data);

            if ($cashFlow->walletMovement && isset($data['value'])) {
                $cashFlow->walletMovement->update([
                    'value' => $data['value'],
                    'comments' => $data['comments'] ?? $cashFlow->walletMovement->comments
                ]);
            }

            DB::commit();
            return $cashFlow;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $cashFlow = CashFlow::findOrFail($id);
            
            if ($cashFlow->walletMovement) {
                $cashFlow->walletMovement->delete();
            }
            
            $cashFlow->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}