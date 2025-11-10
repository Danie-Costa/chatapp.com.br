<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\AuthController;

class AuthWebController extends Controller
{
    public function showLogin() {
            session()->forget('api_token');
        session()->forget('user');
        Auth::logout();
         return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request)
    {
     
        // validação básica
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Chama o AuthController da API diretamente
        $api = new AuthController();
        $response = $api->login($request);

        // Se o controller retornar uma Response JSON, podemos tratar aqui
        $data = $response->getData(true);

        if (isset($data['error']) || !isset($data['token'])) {
            return back()->with('error', 'Credenciais inválidas');
        }

        // Salva o token na sessão
        session(['api_token' => $data['token']]);
        session(['user' => $data['user']]);


        return redirect()->route('rooms.index');
    }


    public function register(Request $request)
    {
        // validação básica
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // chama o AuthController da API
        $api = new AuthController();
        $response = $api->register($request);

        $data = $response->getData(true);

        if (!isset($data['token'])) {
            return back()->with('error', 'Erro ao registrar, tente novamente!');
        }

        // salva token e usuário na sessão
        session(['api_token' => $data['token']]);
        session(['user' => $data['user']]);

        return redirect()->route('rooms.index');
    }

    public function logout()
    {
        session()->forget('api_token');
        session()->forget('user');
        Auth::logout();
        return redirect()->route('showLogin');
    }
}
