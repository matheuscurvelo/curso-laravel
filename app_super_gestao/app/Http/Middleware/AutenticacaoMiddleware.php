<?php

namespace App\Http\Middleware;

use Closure;

class AutenticacaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $metodo_autenticacao, $perfil)
    {
        //verifica se o usuario possui acesso a rota
        echo "$metodo_autenticacao - $perfil <br>";

        if ($metodo_autenticacao == "padrao") {
            //verificar usuario e senha no banco
            echo "Verificar usuario e senha no banco de dados $perfil <br>";
        }elseif ($metodo_autenticacao == "ldap") {
            //verificar usuario e senha no AD
            echo "Verificar usuario e senha no Active Directory (AD) $perfil <br>";
        }else {
            echo "Exibir apenas alguns recursos <br>";
        }
        
        if (false) {
            return $next($request);
        } else {
            return Response('Acesso Negado! Rota exige autenticação!!!');
        }
    }
}
