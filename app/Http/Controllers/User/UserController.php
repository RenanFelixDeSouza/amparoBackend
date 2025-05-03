<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserSimpleResource;
use App\Models\Address\Address;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ChangePasswordRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser()
    {
        $result = $this->userService->getUser();
        return response()->json([
            'user' => new UserResource($result['user']),
        ]);
    }

    public function getUsers(Request $request)
    {
        $filters = [
            'name' => $request->input('name', ''),
            'email' => $request->input('email', ''),
            'search' => $request->input('search', ''),
        ];

        $pagination = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort_column' => $request->input('sort_column', 'id'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $users = $this->userService->getAllUsers($filters, $pagination);

        return UserSimpleResource::collection($users);
    }

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

        // Verifica e trata os dados do endereço
        $addressData = $request->only([
            'city_id',
            'complement',
            'district_name',
            'number',
            'street',
            'zip_code'
        ]);

        // Só atualiza/cria endereço se houver dados válidos
        if (!empty(array_filter($addressData))) {
            if ($user->address) {
                // Atualiza o endereço existente
                $user->address->update($addressData);
            } else {
                // Cria um novo endereço
                $address = Address::create($addressData);
                $user->address_id = $address->id;
            }
        }

        if ($request->hasFile('photo')) {
            // Remove a foto antiga, se existir
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Salva a nova foto
            $path = $request->file('photo')->store('uploads/photos', 'public');
            $user->photo = $path;
        }

        // Atualiza os dados do usuário
        $user->fill($request->only(['name', 'email', 'phone', 'reason']));
        $user->save();

        return response()->json([
            'message' => 'Dados atualizados com sucesso!',
            'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
            'user' => new UserResource($user->load(['address.city'])), // Carrega o endereço e cidade
        ]);
    }

    public function delete()
    {
        $user = auth()->user();

        if ($user->photo) {
            // Remove a foto do storage
            Storage::disk('public')->delete($user->photo);

            // Remove a referência da foto no banco de dados
            $user->photo = null;
            $user->save();

            return response()->json([
                'message' => 'Foto removida com sucesso!',
            ]);
        }

        return response()->json([
            'message' => 'Nenhuma foto encontrada para remover.',
        ]);
    }

    public function active($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $user->is_active = 1;
        $user->save();

        return response()->json(['message' => 'Status do usuário atualizado com sucesso']);
    }

    public function inactive($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $user->is_active = 0;
        $user->save();

        return response()->json(['message' => 'Status do usuário atualizado com sucesso']);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $result = $this->userService->changePassword($request->validated());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }
}