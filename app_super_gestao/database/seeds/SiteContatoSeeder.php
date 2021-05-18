<?php

use App\SiteContato;
use Illuminate\Database\Seeder;

class SiteContatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$fornecedor = new SiteContato();
        $fornecedor->nome = "Contato sistema SG";
        $fornecedor->motivo_contato = 3;
        $fornecedor->telefone = "(21) 98888-8888";
        $fornecedor->email = "contato@contato.com.br";
        $fornecedor->mensagem = "Bem vindo ao nosso sistema";
        $fornecedor->save();
        */

        factory(SiteContato::class,100)->create();
    }
}
