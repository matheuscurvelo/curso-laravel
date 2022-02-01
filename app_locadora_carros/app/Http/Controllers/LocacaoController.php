<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);


        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }
        
        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json( $locacaoRepository->getResultado() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());
       
        $locacao = $this->locacao->create($request->all());

        return response()->json( $locacao, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json( ['erro'=>'Recurso pesquisado não existe'], 404);
        }

        return response()->json( $locacao, 200 );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);
        

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            //percorre as regras do model
            foreach ($locacao->rules() as $input => $regra) {

                //coleta apenas as regras aplicaveis aos parametros parciais da requisição
                if (array_key_exists($input,$request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }                
            }

            $request->validate($regrasDinamicas);

            
        } else {
            $request->validate($locacao->rules());
        }


        if ($locacao === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $locacao->fill($request->all());

        $locacao->save();
        
        return response()->json( $locacao, 200 );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $locacao->delete();

        return ['mensagem'=>'A locacao foi removida com sucesso!'];
    }
}
