<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id','nome','imagem','numero_portas','lugares','air_bag','abs'];

    public function rules(): Array
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => 'required|min:3|unique:modelos,nome,'.$this->id,
            'imagem' => 'required|file|mimes:png,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean', //true, false, 1, 0, "1", "0"
        ];
    }

    public function feedback(): Array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'imagem.mimes' => 'A imagem precisa ser do tipo PNG ou JPG',
        ];
    }

    /**
     * Get the marca that owns the Modelo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
}
