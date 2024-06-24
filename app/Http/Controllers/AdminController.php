<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard/index');
    }

    public function login()
    {

        if (session('authenticated')) {
            return redirect()->route('dashboard');
        }

        return view('admin/login/index');
    }

    public function attempt(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email|max:255',
            'password' => 'bail|required|min:6'
        ]);
        // Quando você autenticar o usuário e salvar na sessão, certifique-se de carregar as notificações
        $user = User::with('unreadNotifications')->where('email', $request->email)->first(); // Carregar o usuário com as notificações não lidas
      
        if (!$user) {
            return back()->withErrors("E-mail e/ou senha inválidos.")->withInput();
        }

        if (!Utils::passwordIsValid($request->password, $user->password, $user->salt)) {
            return back()->withErrors("E-mail e/ou senha inválidos.")->withInput();
        }
        session([
            'authenticated' => true,
            'userData' => $user
        ]);

        if (session("userData")->role == "user") {
            return redirect('/formulario');
        } else {
            return redirect('/dashboard');
        }
    }

    public function sair(Request $request)
    {
        // Limpar todos os dados da sessão
        $request->session()->flush();

        // Redirecionar para a página de login ou para onde desejar
        return redirect('admin/login');
    }
}
