@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('client') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="row col-12">
            <div class="mr-auto">
                <h1>Cliente {{$client->name}}</h1>
            </div>
            @if ( Auth::user()->hasProfile("CLIENT_EDIT"))
            <div class="float-right">
                <a href="{{route('client.edit', $client->id)}}" class="btn btn-primary">editar</a>
            </div>
            @endif
        </div>

        

        <div class="row card-deck my-3 ">
            <div class="card col-12">
                <div class="card-body">
                    <div>
                        <strong>Nome:</strong> {{$client->name ?? "-"}}
                        <br>
                        <strong>Descrição:</strong> {{$client->description?? "-"}}
                        <br>
                        <strong>CNPJ/CPF:</strong> {{$client->cnpjcpf ?? "-"}}
                    </div>
                </div>  
            </div>
        </div>

        <div class="row card-deck">
            <div class="card col-6">
                <div class="card-body">
                    <h5 class="card-title">Endereço</h5>
                    <div>
                        <strong>Endereço:</strong> {{$client->address ?? "-"}}
                        <br>
                        <strong>Número:</strong> {{$client->addressnumber ?? "-"}}
                        <br>
                        <strong>Complemento:</strong> {{$client->complement ?? "-"}}
                        <br>
                        <strong>CEP:</strong> {{$client->zipcode ?? "-"}}
                        <br>
                        <strong>Bairro:</strong> {{$client->neighborhood ?? "-"}}
                        <br>
                        <strong>Cidade:</strong> {{$client->city ?? "-"}}
                        <br>
                        <strong>Estado:</strong> {{$client->state ?? "-"}}
                        <br>
                        <strong>País:</strong> {{$client->country ?? "-"}}
                    </div>
                </div>  
            </div>
            <div class="card col-6">
                <div class="card-body">
                    <h5 class="card-title">Contato</h5>
                    <div>
                        <strong>E-mail:</strong> {{$client->email ?? "-"}}
                        <br>
                        <strong>Telefone:</strong> {{$client->phone}}
                        <br>
                        <strong>Celular:</strong> {{$client->mobile ?? "-"}}
                    </div>
                </div>  
            </div>
        </div>

        <div class="row col-12 mt-2">
            <div class="float-right ml-auto">
                <a href="{{route('client')}}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
        
    </div>
@endsection