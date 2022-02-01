<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);


        /* if ($request->has('atributos_locacoes')) {
            $atributos_locacoes = 'locacoes:id,'.$request->atributos_locacoes;
            $clienteRepository->selectAtributosRegistrosRelacionados($atributos_locacoes);
        } else {
            $clienteRepository->selectAtributosRegistrosRelacionados('locacoes');
        } */

        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }
        
        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json( $clienteRepository->getResultado() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());
       
        $cliente = $this->cliente->create($request->all());

        return response()->json( $cliente, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->with('modelo')->find($id);
        if ($cliente === null) {
            return response()->json( ['erro'=>'Recurso pesquisado não existe'], 404);
        }

        return response()->json( $cliente, 200 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);
        

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            //percorre as regras do model
            foreach ($cliente->rules() as $input => $regra) {

                //coleta apenas as regras aplicaveis aos parametros parciais da requisição
                if (array_key_exists($input,$request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }                
            }

            $request->validate($regrasDinamicas);

            
        } else {
            $request->validate($cliente->rules());
        }


        if ($cliente === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $cliente->fill($request->all());

        $cliente->save();
        
        return response()->json( $cliente, 200 );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $cliente->delete();

        return ['mensagem'=>'O cliente foi removido com sucesso!'];
    }
}
