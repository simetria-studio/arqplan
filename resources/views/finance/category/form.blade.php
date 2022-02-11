@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('finance.category') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($financeTransactionCategory->id > 0)
        <h1>Atualizar categoria de transações</h1>
        <form method="POST" action="{{route('finance.category.edit', $financeTransactionCategory->id)}}">
        @else
        <h1>Nova categoria de transações</h1>
        <form method="POST" action="{{route('finance.category.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$financeTransactionCategory->name}}">
                    </div>
                </div>
            </div>

            <div class="float-right">
                @if ($financeTransactionCategory->id > 0)
                <a href="{{route('finance.category.delete', $financeTransactionCategory->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('finance.category')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection