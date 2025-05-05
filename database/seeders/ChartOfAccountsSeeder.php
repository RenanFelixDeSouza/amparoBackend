<?php

namespace Database\Seeders;

use App\Models\Financial\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. RECEITAS
        $receitas = $this->createAccount(null, '1', 'RECEITAS', 'synthetic', 1);
        
        // 1.1 DOAÇÕES
        $doacoes = $this->createAccount($receitas->id, '1.1', 'DOAÇÕES', 'synthetic', 2);
        $this->createAccount($doacoes->id, '1.1.01', 'Doações', 'analytical', 3);
        $this->createAccount($doacoes->id, '1.1.02', 'Doações Recorrentes', 'analytical', 3);
        $this->createAccount($doacoes->id, '1.1.03', 'Mensalidades', 'analytical', 3);
        
        // 1.2 EVENTOS
        $eventos = $this->createAccount($receitas->id, '1.2', 'EVENTOS', 'synthetic', 2);
        $this->createAccount($eventos->id, '1.2.01', 'Eventos Beneficentes', 'analytical', 3);
        $this->createAccount($eventos->id, '1.2.02', 'Bazares', 'analytical', 3);
        $this->createAccount($eventos->id, '1.2.03', 'Outros Eventos', 'analytical', 3);
        // 1.3 PARCERIAS
        $parcerias = $this->createAccount($receitas->id, '1.3', 'PARCERIAS', 'synthetic', 2);
        $this->createAccount($parcerias->id, '1.3.01', 'Patrocínios', 'analytical', 3);

        // 2. DESPESAS
        $despesas = $this->createAccount(null, '2', 'DESPESAS', 'synthetic', 1);
        
        // 2.1 ASSISTÊNCIA ANIMAL
        $assistenciaAnimal = $this->createAccount($despesas->id, '2.1', 'ASSISTÊNCIA ANIMAL', 'synthetic', 2);
        $this->createAccount($assistenciaAnimal->id, '2.1.01', 'Ração', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.02', 'Medicamentos', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.03', 'Vacinas', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.04', 'Veterinário', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.05', 'Higiene Animal', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.06', 'Produto Animal', 'analytical', 3);
        $this->createAccount($assistenciaAnimal->id, '2.1.07', 'Outro', 'analytical', 3);
        
        // 2.2 INFRAESTRUTURA
        $infraestrutura = $this->createAccount($despesas->id, '2.2', 'INFRAESTRUTURA', 'synthetic', 2);
        $this->createAccount($infraestrutura->id, '2.2.01', 'Aluguel', 'analytical', 3);
        $this->createAccount($infraestrutura->id, '2.2.02', 'Água', 'analytical', 3);
        $this->createAccount($infraestrutura->id, '2.2.03', 'Energia', 'analytical', 3);
        $this->createAccount($infraestrutura->id, '2.2.04', 'Internet', 'analytical', 3);
        $this->createAccount($infraestrutura->id, '2.2.05', 'Outro', 'analytical', 3);
        
        // 2.3 RECURSOS HUMANOS
        $rh = $this->createAccount($despesas->id, '2.3', 'RECURSOS HUMANOS', 'synthetic', 2);
        $this->createAccount($rh->id, '2.3.01', 'Salários', 'analytical', 3);
        $this->createAccount($rh->id, '2.3.02', 'Benefícios', 'analytical', 3);
        $this->createAccount($rh->id, '2.3.03', 'Encargos', 'analytical', 3);

        // 2.4 CUSTOS OPERACIONAIS
        $custosOperacionais = $this->createAccount($despesas->id, '2.4', 'CUSTOS OPERACIONAIS', 'synthetic', 2);
        
        // 2.4.1 CUSTOS DE SISTEMA
        $custosSistema = $this->createAccount($custosOperacionais->id, '2.4.1', 'CUSTOS DE SISTEMA', 'synthetic', 3);
        $this->createAccount($custosSistema->id, '2.4.1.01', 'Hospedagem', 'analytical', 4);
        $this->createAccount($custosSistema->id, '2.4.1.02', 'Dominio', 'analytical', 4);
        $this->createAccount($custosSistema->id, '2.4.1.03', 'Manutenção de Sistema', 'analytical', 4);
        $this->createAccount($custosSistema->id, '2.4.1.04', 'Outro', 'analytical', 4);
        
        // 2.4.2 CUSTOS ADMINISTRATIVOS
        $custosAdm = $this->createAccount($custosOperacionais->id, '2.4.2', 'CUSTOS ADMINISTRATIVOS', 'synthetic', 3);
        $this->createAccount($custosAdm->id, '2.4.2.01', 'Serviços de Contabilidade', 'analytical', 4);
        $this->createAccount($custosAdm->id, '2.4.2.02', 'Taxas e Impostos', 'analytical', 4);
    }

    private function createAccount($parentId, $code, $name, $type, $level)
    {
        $path = $this->generatePath($parentId, $code);
        
        return ChartOfAccount::create([
            'user_id' => 2, 
            'parent_id' => $parentId,
            'name' => $name,
            'type' => $type,
            'account_code' => $code,
            'level' => $level,
            'path' => $path
        ]);
    }

    private function generatePath($parentId, $code)
    {
        if (!$parentId) {
            return $code;
        }

        $parent = ChartOfAccount::find($parentId);
        return $parent->path . '/' . $code;
    }
}