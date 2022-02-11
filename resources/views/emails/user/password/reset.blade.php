<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Solicitação de nova senha</h2>
    <p>Olá {{$user->name}}!</p>
    <p>Sua senha de acesso ao sistema foi alterado, <a href="{{ url('/') }}">clique aqui</a> ou copie e cole no seu navegador {{ url('/') }}</p>
    <p>Sua senha nova é <strong>{{$password}}</strong></p>
  </body>
</html>