<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Bem vindo!</h2>
    <p>Olá {{$user->name}}!</p>
    <p>Para acessar ao sistema, <a href="{{ url('/') }}">clique aqui</a> ou copie e cole no seu navegador {{ url('/') }}</p>
    <p>Sua senha é <strong>{{$password}}</strong></p>
  </body>
</html>