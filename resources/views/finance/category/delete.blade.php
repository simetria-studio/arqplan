@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('finance.category') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover categoria</h1>
        <form method="POST" action="{{route('finance.category.delete', $financeTransactionCategory->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover a categoria de transações {{$financeTransactionCategory->name}}</p>
                    <div class="float-right">
                        <a href="{{route('finance.category')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection