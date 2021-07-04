<?php

namespace App\Http\Controllers;

use App\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        return view('app.fornecedor.index');
    }

    public function listar(Request $request)
    {
        $fornecedores = Fornecedor::where('nome','like',"%".$request->input('nome')."%")
            ->where('email','like',"%".$request->input('email')."%")
            ->where('uf','like',"%".$request->input('uf')."%")
            ->where('site','like',"%".$request->input('site')."%")
            ->paginate(2);
        
        $request = $request->all();

        return view('app.fornecedor.listar',compact('fornecedores','request'));
    }

    public function adicionar(Request $request)
    {
        $msg = "";

        if ($request->input('_token') != '' && $request->input('id') == '') {
            
            //validacao
            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required',
                'uf' => 'required|min:2|max:2',
                'email' => 'email'
            ];

            $feedback = [
                'required' => "O campo :attribute deve ser preenchido",
                'nome.min' => "O campo :attribute deve ter no minimo 3 caracteres",
                'nome.max' => "O campo :attribute deve ter no minimo 40 caracteres",
                'uf.min' => "O campo :attribute deve ter no minimo 2 caracteres",
                'uf.max' => "O campo :attribute deve ter no maximo 2 caracteres",
                'email' => "O campo :attribute não foi preenchido corretamente",
            ];

            $request->validate($regras,$feedback);
            
            Fornecedor::create($request->all());

            $msg = "Cadastro Feito com sucesso";

        }elseif ($request->input('_token') != '' && $request->input('id') != '') {
            $id = $request->input('id');
            $update = Fornecedor::find($id)->update($request->all());
            if ($update) {
                $msg = 'Update realizado com sucesso';
            }else{
                $msg = 'Update Falhou';
            }

            return redirect()->route('app.fornecedor.editar',compact('msg','id'));
        }

        return view('app.fornecedor.adicionar',compact('msg'));

    }

    public function editar($id,$msg = '')
    {
        $fornecedor = Fornecedor::find($id);

        return view('app.fornecedor.adicionar', compact('fornecedor','msg'));
    }

    public function excluir($id)
    {
        Fornecedor::find($id)->delete();
        
        return redirect()->route('app.fornecedor');
    }
}
