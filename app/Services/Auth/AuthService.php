<?php

namespace App\Services\Auth;

use App\Models\User;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Realiza login do usuário
     * @param array $request
     * @return array
     */
    public function login(array $request)
    {
        if (empty($request['email']) || empty($request['password'])) {
            return response()->json(['message' => 'Email and password are required'], 400);
        }

        $user = User::with('typeUser')->where('email', $request['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request['password'], $user->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;
        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'type' => $user->typeUser->description,
            ],
        ];
    }

    /**
     * Realiza logout do usuário
     * @param int $userId
     */
    public function logout($userId)
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * Registra um novo usuário
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function register(array $data)
    {
        try {
            if (User::where('email', $data['email'])->exists()) {
                throw new \Exception('User already exists');
            }

            if ($data['password'] !== $data['confirmPassword']) {
                throw new \Exception('Passwords do not match');
            }

            DB::beginTransaction();

            $data['type_user_id'] = 1;
            $data['user_name'] = $data['name'];

            $hashedPassword = Hash::make($data['password']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $hashedPassword,
                'type_user_id' => $data['type_user_id'],
                'user_name' => $data['user_name'],
            ]);

            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
