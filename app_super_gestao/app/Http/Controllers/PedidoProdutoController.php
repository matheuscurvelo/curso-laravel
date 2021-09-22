<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\PedidoProduto;
use App\Produto;
use Illuminate\Http\Request;

class PedidoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pedido_id)
    {
        $pedido = Pedido::find($pedido_id);

        $produtos = Produto::all();

        return view('app.pedido_produto.create',compact('pedido','produtos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $pedido_id)
    {
        $regras = [
            'produto_id' => 'exists:produtos,id',
            'quantidade' => 'required'
        ];

        $request->validate($regras);



        /* $pedidoProduto = new PedidoProduto();
        $pedidoProduto->pedido_id = $pedido_id;
        $pedidoProduto->produto_id = $request->input('produto_id');
        $pedidoProduto->save(); */

        $pedido = Pedido::find($pedido_id);

        $pedido->produtos()->attach([
            $request->input('produto_id') => ['quantidade' => $request->input('quantidade')]
        ]);

        return redirect()->route('pedido-produto.create', compact('pedido_id'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pedido_produto_id,$pedido_id)
    {
        //$pedido = Pedido::find($pedido_id);
        //$produto = Produto::find($produto_id);
        $pedidoProduto = PedidoProduto::find($pedido_produto_id);


        //convencional
        /* PedidoProduto::where([
            'pedido_id' => $pedido_id,
            'produto_id' => $produto_id
        ])->delete(); */

        //detach (delete pelo relacionamento)
        //$pedido->produtos()->detach([$produto_id]);
        $pedidoProduto->delete();

        return redirect()->route('pedido-produto.create', compact('pedido_id'));

    }
}
