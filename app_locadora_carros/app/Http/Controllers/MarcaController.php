<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);


        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }
        
        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json( $marcaRepository->getResultado() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());

        $image = $request->file('imagem');

        //store aceita como argumentos: path e disco
        $image_urn = $image->store('imagens','public');
        
        $marca = $this->marca->create([
            'nome'=>$request->nome, 
            'imagem'=>$image_urn
        ]);
        return response()->json( $marca, 201);

        //stateless - cada requisicao é unica
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if ($marca === null) {
            return response()->json( ['erro'=>'Recurso pesquisado não existe'], 404);
        }

        return response()->json( $marca );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        

        if ($request->method() === 'PATCH') {

            $regrasDinamicas = [];

            //percorre as regras do model
            foreach ($marca->rules() as $input => $regra) {

                //coleta apenas as regras aplicaveis aos parametros parciais da requisição
                if (array_key_exists($input,$request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }                
            }

            $request->validate($regrasDinamicas,$marca->feedback());

            
        } else {
            $request->validate($marca->rules(),$marca->feedback());
        }


        if ($marca === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        // Usar este metodo quando precisar alterar alguns dos campos (pode ser mais util em APIs)
        $marca->fill($request->all());

        //remove o arquivo antigo caso um novo arquivo tiver sido enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);

            $image = $request->file('imagem');

            $image_urn = $image->store('imagens','public');
            $marca->imagem = $image_urn;
        }

        $marca->save();
        
        /* Usar este metodo quando precisar altarar todos os campos
        
        $marca->update([
            'nome'=>$request->nome, 
            'imagem'=>$image_urn
        ]); */

        return response()->json( $marca );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json( ['erro'=>'Recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        return ['mensagem'=>'A marca foi removida com sucesso!'];
    }
}