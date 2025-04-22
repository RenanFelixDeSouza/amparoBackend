<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserSimpleResource;
use App\Services\User\UserService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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

        $user->update($request->only(['name', 'email', 'phone', 'address']));



        if ($request->hasFile('photo')) {
            // Remove a foto antiga, se existir
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Salva a nova foto
            $path = $request->file('photo')->store('uploads/photos', 'public');
            $user->photo = $path;
        }

        // Atualiza os outros campos
        $user->fill($request->only(['name', 'email', 'phone', 'reason', 'address']));
        $user->save();

        return response()->json([
            'message' => 'Dados atualizados com sucesso!',
            'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
        ]);
    }
}