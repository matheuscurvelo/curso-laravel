<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);


        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }
        
        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json( $carroRepository->getResultado() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());
       
        $carro = $this->carro->create($request->all());

        return response()->json( $carro, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json( ['erro'=>'Recurso pesquisado não existe'], 404);
        }

        return response()->json( $carro, 200 );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);
        

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            //percorre as regras do model
            foreach ($carro->rules() as $input => $regra) {

                //coleta apenas as regras aplicaveis aos parametros parciais da requisição
                if (array_key_exists($input,$request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }                
            }

            $request->validate($regrasDinamicas);

            
        } else {
            $request->validate($carro->rules());
        }


        if ($carro === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $carro->fill($request->all());

        $carro->save();
        
        return response()->json( $carro, 200 );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $carro->delete();

        return ['mensagem'=>'O carro foi removido com sucesso!'];
    }
}
