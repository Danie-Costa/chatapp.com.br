<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\PersonalAccessToken;


class SessionTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = session('api_token');

        if (!$token) {
            return  redirect()->route('login');
        }
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return redirect()->route('login');
        }

        // Checa expiração caso você tenha coluna expires_at
        if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return redirect()->route('login');
        }

        // Recupera o usuário dono do token
        // $user = $accessToken->tokenable;
        
        // Opcional: definir o usuário “fake” autenticado
        // auth()->login($userModel);
        
        return $next($request);
    }

}
