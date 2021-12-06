<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id','nome','imagem','numero_portas','lugares','air_bag','abs'];

    public function rules()
    {
        return [
            'marca_id' => 'exists:marca,id',
            'nome' => 'required|min:3|unique:modelos,nome,'.$this->id,
            'imagem' => 'required|file|mimes:png,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean', //true, false, 1, 0, "1", "0"
        ];
    }
}
