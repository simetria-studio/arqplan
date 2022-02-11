@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('client') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover cliente</h1>
        <form method="POST" action="{{route('client.delete', $client->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover o cliente {{$client->name}}</p>
                    <div class="float-right">
                        <a href="{{route('client')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection