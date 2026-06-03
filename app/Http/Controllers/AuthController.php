<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function loginSubmit(Request $request){
        // Lógica de autenticação aqui
        $request->validate(
            [
                'email' => 'required|email',
                'senha' => 'required|string|min:6|max:16',
            ],
            [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
                'senha.required' => 'O campo senha é obrigatório.',
                'senha.string' => 'O campo senha deve ser uma string.',
                'senha.min' => 'O campo senha deve ter no mínimo :min caracteres.',
                'senha.max' => 'O campo senha deve ter no máximo :max caracteres.',
            ]
        );

        $email = $request->input('email');
        $senha = $request->input('senha');

        $user = User::where('email', $email)
                    ->first();


        if(!$user){
            return redirect()->back()->withErrors(['loginError' => 'Credenciais Inválidas.'])->withInput();
        }

        if(!password_verify($senha, $user->password)){
            return redirect()->back()->withErrors(['loginError'=> 'Credenciais Inválidas'])->withInput();
        }
        
        //Autenticação Bem Sucedida
        $user->updated_at = now();
        $user->save();
        

        //Criar sessão de usuário
        $approved = true;
        if ($user->role === 'recruiter') {
            $profile = \App\Models\RecruiterProfile::where('user_id', $user->id)->first();
            $approved = $profile ? $profile->companies()->wherePivot('approved', true)->exists() : false;
        }

        session([
            'user_id'=> $user->id, 
            'user_nome'=> $user->name, 
            'user_role' => $user->role,
            'user_approved' => $approved
        ]);

        return redirect('/');

    }

    public function logout(){
        // Destruir sessão de usuário
        session()->forget(['user_id', 'user_nome', 'user_role', 'user_approved']);
        return redirect('/login');
    }
}
