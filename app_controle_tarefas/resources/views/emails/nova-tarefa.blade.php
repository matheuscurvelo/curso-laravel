@component('mail::message')
# {{ $tarefa }}

Data limite conclusao: {{ $data_limite_conclusao }}

@component('mail::button', ['url' => $url])
Clique para vizualizar
@endcomponent

Att,<br>
{{ config('app.name') }}
@endcomponent
