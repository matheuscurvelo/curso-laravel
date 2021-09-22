<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //protected $fillable = ['cliente_id'];

    public function produtos()
    {
        //nomes padronizados
        return $this->belongsToMany('App\Produto','pedidos_produtos')->withPivot('created_at','quantidade','id');

        //nomes não padronizados
        // return $this->belongsToMany('App\Produto','pedidos_produtos','pedido_id','produto_id');
    }
}
