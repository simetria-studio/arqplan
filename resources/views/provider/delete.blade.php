@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('provider') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover fornecedor</h1>
        <form method="POST" action="{{route('provider.delete', $provider->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover o fornecedor {{$provider->name}}</p>
                    <div class="float-right">
                        <a href="{{route('provider')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection