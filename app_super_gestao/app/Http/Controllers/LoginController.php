<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $erro = $request->get('erro');
        switch ($erro) {
            case '1':
                $erro = 'Usuário e/ou Senha não existe';
                break;
            
            case '2':
                $erro = 'Necessário realizar login para ter acesso a página';
                break;

            default:
                break;
        }
        return view('site.login',['titulo'=>'login', 'erro'=>$erro]);
    }

    public function autenticar(Request $request)
    {
        $regras = [
            'usuario'=>'email',
            'senha'=>'required'
        ];
        $feedback = [
            'usuario.email' => 'O email do usuário é obrigatório',
            'senha.required'=> 'O campo senha é obrigatório'
        ];

        //retorna para a ultima tela caso não passe pelo validate
        $request->validate($regras,$feedback);

        //recupera os parametros do formulario
        $email = $request->get('usuario');
        $password = $request->get('senha');

        //iniciar o model
        $user = new User();
        $usuario = $user->where('email',$email)->where('password',$password)->get()->first();
        
        if (isset($usuario->name)) {
            
            session_start();
            $_SESSION['nome'] = $usuario->nome;
            $_SESSION['email'] = $usuario->email;
            return redirect()->route('app.home');

        }else{
            return redirect()->route('site.login',['erro'=>1]);
        }
        //var_dump($email,$password);
    }

    public function sair()
    {
        session_destroy();
        return redirect()->route('site.index');
    }
}
