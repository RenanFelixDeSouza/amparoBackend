<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAddress
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user->address_id) {
            return response()->json([
                'success' => false,
                'message' => 'Para fazer essa ação, é necessário cadastrar um endereço em seu perfil.',
                'errors' => [
                    'address' => ['Endereço não cadastrado']
                ]
            ], 422);
        }

        return $next($request);
    }
}