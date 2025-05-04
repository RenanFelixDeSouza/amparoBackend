<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use DB;

class UserService
{
    public function getUser()
    {
        $user = auth()->user();
        $user->load('typeUser', 'address.city');

        return [
            'user' => $user,
        ];
    }


    public function getAllUsers(array $filters, array $pagination)
    {
        $query = User::with('typeUser');

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy($pagination['sort_column'], $pagination['sort_order'])
            ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);
    }
    public function uploadPhoto($request)
    {
        $user = auth()->user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $timestamp = now()->format('YmdHis');
        $fileName = str_replace(' ', '_', strtolower($user->name)) . "_{$timestamp}.png";

        $path = $request->file('photo')->storeAs('uploads/users', $fileName, 'public');
        $user->photo = $path;
        $user->save();

        return [
            'message' => 'Foto enviada com sucesso!',
            'photo_url' => asset('storage/' . $path),
        ];
    }

    public function update($request)
    {
        $user = auth()->user();
        $data = $request->all();

        DB::beginTransaction();

        try {
            $user->update(array_intersect_key($data, array_flip(['name', 'email', 'phone', 'user_name'])));

            if (isset($data['zip_code'], $data['street'], $data['number'], $data['district_name'], $data['city_id'])) {
                $address = $user->address()->updateOrCreate(
                    ['id' => $user->address_id],
                    array_intersect_key($data, array_flip(['zip_code', 'street', 'number', 'complement', 'district_name', 'city_id']))
                );

                // Atualiza o address_id do usuário
                $user->address_id = $address->id;
                $user->save();
            }

            DB::commit();

            return [
                'message' => 'Dados atualizados com sucesso!',
                'user' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete()
    {
        $user = auth()->user();

        DB::beginTransaction();

        try {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->photo = null;
            $user->save();

            DB::commit();

            return ['message' => 'foto do usuario deletado com sucesso!'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function changePassword($data)
    {
        $user = auth()->user();

        DB::beginTransaction();
        try {
            if (!Hash::check($data['admin_password'], $user->password)) {
                throw new \Exception('A senha atual está incorreta');
            }

            $user->password =    Hash::make($data['new_password']);
            $user->save();

            DB::commit();
            return ['message' => 'Senha alterada com sucesso!'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
