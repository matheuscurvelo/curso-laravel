<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function rules(): Array
    {
        return [
            'nome' => 'required|unique:clientes,nome,'.$this->id,
        ];
    }

    /**
     * Get all of the locacoes for the Locacao
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locacoes(): HasMany
    {
        return $this->hasMany(Locacao::class, 'foreign_key', 'local_key');
    }
}
