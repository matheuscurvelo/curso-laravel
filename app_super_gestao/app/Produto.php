<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome','descricao','peso','unidade_id'];

    public function pdodutoDetalhe()
    {
        return $this->hasOne('App\ProdutoDetalhe');

        //produto tem 1 produtoDetalhe
        
        //ele deve procurar um registro relacionado em produto_detalhes
    }
}
