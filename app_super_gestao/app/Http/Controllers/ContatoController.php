<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request)
    {
        //1ª forma
        $contato = new SiteContato();
        $contato->nome = $request->input('nome');
        $contato->telefone = $request->input('telefone');
        $contato->email = $request->input('email');
        $contato->motivo_contato = $request->input('motivo_contato');
        $contato->mensagem = $request->input('mensagem');
        $contato->save();

        //2ª forma
        $contato2 = new SiteContato();

        $contato2->fill($request->all());
        //ou
        $contato2->create($request->all());

        $contato2->save();
        return view('site.contato');
    }
}
