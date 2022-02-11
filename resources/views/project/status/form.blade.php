@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.status') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($projectStatus->id > 0)
        <h1>Atualizar status de projetos</h1>
        <form method="POST" action="{{route('project.status.edit', $projectStatus->id)}}">
        @else
        <h1>Nova status de projetos</h1>
        <form method="POST" action="{{route('project.status.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$projectStatus->name}}">
                    </div>
                </div>
            </div>

            <div class="float-right">
                @if ($projectStatus->company != null)
                <a href="{{route('project.status.delete', $projectStatus->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('project.status')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection