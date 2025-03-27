<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function uploadPhoto(Request $request)
    {

        $request->validate([
            'photo' => 'required|image|mimes:png|max:2048',
        ]);

        $user = auth()->user();

        // Remove a foto antiga, se existir
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Gera um nome amigável para a foto
        $timestamp = now()->format('YmdHis');
        $fileName = str_replace(' ', '_', strtolower($user->name)) . "_{$timestamp}.png";

        // Salva a nova foto no diretório 'public/uploads/users'
        $path = $request->file('photo')->storeAs('uploads/users', $fileName, 'public');
        $user->photo = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto enviada com sucesso!',
            'photo_url' => asset('storage/' . $path),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Captura todos os dados enviados
        $data = $request->all();

        // Log para depuração
        \Log::info('Dados recebidos para atualização:', $data);

        // Validação dos campos
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Atualiza os dados do usuário
            $updated = $user->update(array_intersect_key($data, array_flip(['name', 'email', 'phone', 'address'])));

            $user->save();


            DB::commit();

            if (!$updated) {
                return response()->json([
                    'message' => 'Nenhuma alteração foi feita nos dados do usuário.',
                ], 200);
            }

            return response()->json([
                'message' => 'Dados atualizados com sucesso!',
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar os dados do usuário:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erro ao atualizar os dados do usuário.',
            ], 500);
        }
    }
}