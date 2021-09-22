<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome','descricao','peso','unidade_id','fornecedor_id'];

    public function produtoDetalhe()
    {
        return $this->hasOne('App\ProdutoDetalhe');
        //produto tem 1 produtoDetalhe
        
        //ele deve procurar um registro relacionado em produto_detalhes
    }

    public function fornecedor()
    {
        return $this->belongsTo('App\Fornecedor');
    }

    public function pedidos()
    {
        return $this->belongsToMany('App\Pedido','pedidos_produtos');
    }
}
