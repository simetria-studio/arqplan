<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Novo contato solicitado pelo site arqplann.com.br!</h2>    

    <p>
      <strong>Nome: </strong>{{$data->name}}<br/>
      <strong>E-mail: </strong>{{$data->email}}<br/>
      <strong>Telefone: </strong>{{$data->phone}}<br/>
      <strong>Número de colaboradores: </strong>{{$data->employeesNumber}}<br/>
      <strong>Utiliza outro software? </strong>{{$data->otherSoftware}}<br/>
      <strong>Maior dificuldade em gerir: </strong>{{$data->mainDifficulty}}<br/>
    </p>
  </body>
</html>