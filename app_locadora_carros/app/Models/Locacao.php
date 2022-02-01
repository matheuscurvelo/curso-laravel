<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';

    protected $fillable = ['cliente_id','carro_id','data_inicio_periodo','data_final_previsto_periodo','data_final_realixado_periodo','valor_diaria','km_inicial','km_final'];

    public function rules()
    {
        return [];
    }
}
