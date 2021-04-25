<h3>Fornecedor</h3>

{{-- Comentario --}}

@if (count($fornecedores) == 0)
    <h3>Nao existem fornecedores cadastrados</h3>
@elseif (count($fornecedores) < 10)
    <h3>Existem alguns fornecedores cadastrados</h3>
@else
    <h3>Existem varios fornecedores cadastrados</h3>    
@endif