@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.category') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($projectCategory->id > 0)
        <h1>Atualizar categoria de projetos</h1>
        <form method="POST" action="{{route('project.category.edit', $projectCategory->id)}}">
        @else
        <h1>Nova categoria de projetos</h1>
        <form method="POST" action="{{route('project.category.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$projectCategory->name}}">
                    </div>
                </div>
            </div>

            <div class="float-right">
                @if ($projectCategory->id > 0)
                <a href="{{route('project.category.delete', $projectCategory->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('project.category')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection