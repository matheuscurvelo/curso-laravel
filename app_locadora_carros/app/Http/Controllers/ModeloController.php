<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

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
    public function index()
    {
        return response()->json( $this->modelo->all() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

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
        $modelo = $this->modelo->find($id);
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
            $request->validate($modelo->rules());
        }


        if ($modelo === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo caso um novo arquivo tiver sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $image = $request->file('imagem');

        $image_urn = $image->store('imagens/modelos','public');
        
        $modelo->update([
            'nome'=>$request->nome, 
            'imagem'=>$image_urn,
            'marca_id' => $request->marca_id, 
            'numero_portas' =>  $request->numero_portas, 
            'lugares' => $request->lugares, 
            'air_bag' => $request->air_bag, 
            'abs' => $request->abs, 
        ]);

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
