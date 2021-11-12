@component('mail::message')
# Introdução

Corpo da mensagem

- opt1
- opt2
:page_facing_up:

@component('mail::button', ['url' => ''])
Texto do botão
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
