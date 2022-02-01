<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);


        if ($request->has('atributos_marca')) {
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        } else {
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }
        
        if ($request->has('atributos')) {
            $modeloRepository->selectAtributos($request->atributos);
        }
        
        return response()->json( $modeloRepository->getResultado() );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(),$this->modelo->feedback());

        $image = $request->file('imagem');

        //store aceita como argumentos: path e disco
        $image_urn = $image->store('imagens/modelos','public');
        
        $modelo = $this->modelo->create([
            'nome'=>$request->nome, 
            'imagem'=>$image_urn,
            'marca_id' => $request->marca_id, 
            'numero_portas' =>  $request->numero_portas, 
            'lugares' => $request->lugares, 
            'air_bag' => $request->air_bag, 
            'abs' => $request->abs, 
        ]);
        return response()->json( $modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json( ['erro'=>'Recurso pesquisado não existe'], 404);
        }

        return response()->json( $modelo );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            //percorre as regras do model
            foreach ($modelo->rules() as $input => $regra) {

                //coleta apenas as regras aplicaveis aos parametros parciais da requisição
                if (array_key_exists($input,$request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }                
            }

            $request->validate($regrasDinamicas);

            
        } else {

            $request->validate($this->modelo->rules(),$this->modelo->feedback());
        
        }


        if ($modelo === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        $modelo->fill($request->all());

        //remove o arquivo antigo caso um novo arquivo tiver sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);

            $image = $request->file('imagem');

            $image_urn = $image->store('imagens/modelos','public');
            $modelo->imagem = $image_urn;
        }

        
        $modelo->save();
        /* $modelo->update([
            'nome'=>$request->nome, 
            'imagem'=>$image_urn,
            'marca_id' => $request->marca_id, 
            'numero_portas' =>  $request->numero_portas, 
            'lugares' => $request->lugares, 
            'air_bag' => $request->air_bag, 
            'abs' => $request->abs, 
        ]); */

        return response()->json( $modelo );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();

        return ['mensagem'=>'A modelo foi removida com sucesso!'];
    }
}
