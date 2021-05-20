<?php

namespace App\Http\Controllers;

use App\MotivoContato;
use Illuminate\Http\Request;
use App\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request)
    {
        $motivo_contatos = MotivoContato::all();

        return view('site.contato',compact('motivo_contatos'));
    }

    public function salvar(Request $request)
    {
        //validar os dados
        $request->validate(
            [
                'nome' => 'required|min:3|max:40',
                'telefone' => 'required',
                'email' => 'email',
                'motivo_contatos_id' => 'required',
                'mensagem' => 'required',
            ],[
                'nome.required' =>  
            ]
        );
        SiteContato::create($request->all());
        return redirect()->route('site.index');
    }
}
