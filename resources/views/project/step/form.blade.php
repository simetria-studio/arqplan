@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.step') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($projectStep->id > 0)
        <h1>Atualizar etapa de projetos</h1>
        <form method="POST" action="{{route('project.step.edit', $projectStep->id)}}">
        @else
        <h1>Nova etapa de projetos</h1>
        <form method="POST" action="{{route('project.step.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$projectStep->name}}">
                    </div>
                </div>
            </div>

            <div class="float-right">
                @if ($projectStep->company != null)
                <a href="{{route('project.step.delete', $projectStep->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('project.step')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection