<?php

namespace App\Services\Config;

use App\Models\Config;
use App\Models\Financial\ChartOfAccount;
use App\Models\Financial\Wallet;
use Illuminate\Support\Facades\DB;

class ConfigurationService
{
    public function getAll()
    {
        $config = Config::getFinancialConfigs();
        
        $monthly = ChartOfAccount::find($config['monthly_chart_id']);
        $donation = ChartOfAccount::find($config['donation_chart_id']);
        $sponsorship = ChartOfAccount::find($config['sponsorship_chart_id']);
        $wallet = Wallet::find($config['default_wallet_id']);
        
        return [
            'monthly_chart' => [
                'id' => $monthly->id,
                'name' => $monthly->name
            ],
            'donation_chart' => [
                'id' => $donation->id,
                'name' => $donation->name
            ],
            'sponsorship_chart' => [
                'id' => $sponsorship->id,
                'name' => $sponsorship->name
            ],
            'default_wallet' => [
                'id' => $wallet->id,
                'bank_name' => $wallet->bank_name
            ]
        ];
    }

    public function save(array $data)
    {
        try {
            DB::beginTransaction();

            foreach ($data as $key => $value) {
                $description = $this->getDescriptionForKey($key);
                Config::setConfigValue($key, $value, 'financial', $description);
            }

            DB::commit();
            return $this->getAll();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getDescriptionForKey(string $key): ?string
    {
        return match($key) {
            'monthly_chart_id' => 'ID do plano de contas das mensalidades',
            'monthly_chart_name' => 'Nome do plano de contas das mensalidades',
            'donation_chart_id' => 'ID do plano de contas das doações',
            'donation_chart_name' => 'Nome do plano de contas das doações',
            'sponsorship_chart_id' => 'ID do plano de contas dos patrocínios',
            'sponsorship_chart_name' => 'Nome do plano de contas dos patrocínios',
            'default_wallet_id' => 'ID da carteira padrão',
            default => null
        };
    }
}