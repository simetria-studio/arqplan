@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('client') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($client->id > 0)
        <h1>Atualizar cliente</h1>
        <form method="POST" action="{{route('client.edit', $client->id)}}">
        @else
        <h1>Novo cliente</h1>
        <form method="POST" action="{{route('client.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$client->name}}">
                    </div>
                    <div class="form-group">
                        <label>Descrição</label>
                        <input type="text" name="description" class="form-control" value="{{$client->description}}">
                    </div>
                    <div class="form-group">
                        <label>CNPJ/CPF</label>
                        <the-mask :mask="['###.###.###-##','##.###.###/####-##']" type="text" name="cnpjcpf" class="form-control" value="{{$client->cnpjcpf}}">                            
                    </div>  
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Contato</h4>   
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{$client->email}}">
                    </div>
                    <div class="form-group">
                        <label>Celular</label>                        
                        <the-mask :mask="['(##) ####-####','(##) #####-####']" type="tel" name="mobile" class="form-control" value="{{$client->mobile}}">  
                    </div>
                    <div class="form-group">
                        <label>Fixo</label>
                        <the-mask :mask="['(##) ####-####','(##) #####-####']" type="tel" name="phone" class="form-control" value="{{$client->phone}}">  
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Endereço</h4>   
                        <div class="form-group">
                            <label>Endereço</label>
                            <input type="text" name="address" class="form-control" value="{{$client->address}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="addressnumber" class="form-control" value="{{$client->addressnumber}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="addresscomplement" class="form-control" value="{{$client->addresscomplement}}">
                        </div>

                        <div class="form-group">
                            <label>CEP</label>
                            <the-mask :mask="['#####-###']" type="text" name="zipcode" class="form-control" value="{{$client->zipcode}}">                            
                        </div>  
                        
                        <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" name="neighborhood" class="form-control" value="{{$client->neighborhood}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="city" class="form-control" value="{{$client->city}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" name="state" class="form-control" value="{{$client->state}}">
                        </div>
                </div>
            </div>

            <div class="float-right">
                @if ($client->id > 0)
                <a href="{{route('client.delete', $client->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('client')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection