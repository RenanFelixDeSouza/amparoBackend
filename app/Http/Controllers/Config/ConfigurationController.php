<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Services\Config\ConfigurationService;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    protected $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    public function listAll()
    {
        try {
            $configs = $this->configService->getAll();
            return response()->json($configs);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar configurações',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $data = $this->configService->save($request->all());
            return response()->json([
                'message' => 'Configurações salvas com sucesso',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar configurações',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}