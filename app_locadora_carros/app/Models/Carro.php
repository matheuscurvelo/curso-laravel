<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id','placa','disponivel','km'];

    public function rules(): Array
    {
        return [
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required',
        ];
    }

    /**
     * Get all of the modelos for the Marca
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modelo(): BelongsTo
    {
        return $this->belongsTo(Modelo::class);
    }

}
