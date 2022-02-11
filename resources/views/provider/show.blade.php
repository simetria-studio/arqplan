@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('provider') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="row col-12">
            <div class="mr-auto">
                <h1>Fornecedor {{$provider->name}}</h1>
            </div>
            @if ( Auth::user()->hasProfile("PROVIDER_EDIT"))
            <div class="float-right">
                <a href="{{route('provider.edit', $provider->id)}}" class="btn btn-primary">editar</a>
            </div>
            @endif
        </div>

        

        <div class="row card-deck my-3 ">
            <div class="card col-12">
                <div class="card-body">
                    <div>
                        <strong>Nome:</strong> {{$provider->name ?? "-"}}
                        <br>
                        <strong>Descrição:</strong> {{$provider->description?? "-"}}
                        <br>
                        <strong>CNPJ/CPF:</strong> {{$provider->cnpjcpf ?? "-"}}
                    </div>
                </div>  
            </div>
        </div>

        <div class="row card-deck">
            <div class="card col-6">
                <div class="card-body">
                    <h5 class="card-title">Endereço</h5>
                    <div>
                        <strong>Endereço:</strong> {{$provider->address ?? "-"}}
                        <br>
                        <strong>Número:</strong> {{$provider->addressnumber ?? "-"}}
                        <br>
                        <strong>Complemento:</strong> {{$provider->complement ?? "-"}}
                        <br>
                        <strong>Bairro:</strong> {{$provider->neighborhood ?? "-"}}
                        <br>
                        <strong>CEP:</strong> {{$provider->zipcode ?? "-"}}
                        <br>
                        <strong>Cidade:</strong> {{$provider->city ?? "-"}}
                        <br>
                        <strong>Estado:</strong> {{$provider->state ?? "-"}}
                        <br>
                        <strong>País:</strong> {{$provider->country ?? "-"}}
                    </div>
                </div>  
            </div>
            <div class="card col-6">
                <div class="card-body">
                    <h5 class="card-title">Contato</h5>
                    <div>
                        <strong>E-mail:</strong> {{$provider->email ?? "-"}}
                        <br>
                        <strong>Telefone:</strong> {{$provider->phone}}
                        <br>
                        <strong>Celular:</strong> {{$provider->mobile ?? "-"}}
                    </div>
                </div>  
            </div>
        </div>

        <div class="row col-12 mt-2">
            <div class="float-right ml-auto">
                <a href="{{route('provider')}}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
        
    </div>
@endsection