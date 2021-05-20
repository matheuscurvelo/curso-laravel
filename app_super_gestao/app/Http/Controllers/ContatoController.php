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
        $regras = [
            'nome' => 'required|min:3|max:40',
            'telefone' => 'required',
            'email' => 'email',
            'motivo_contatos_id' => 'required',
            'mensagem' => 'required'
        ];
        $feedback = [
            'required' => 'O campo :attribute deve ser preenchido',
            'nome.min' => 'O campo nome precisa ter pelo menos 3 caracteres',
            'nome.max' => 'O campo nome pode ter até 40 caracteres',
            'nome.unique' => 'O nome informado ja está em uso',
            'email.email' => 'O email informado não é valido',
            'mensagem.max' => 'A mensagem pode ter até 2000 caracteres',
        ];

        $request->validate($regras,$feedback);
        
        SiteContato::create($request->all());
        return redirect()->route('site.index');
    }
}
