@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover projeto</h1>
        <form method="POST" action="{{route('project.delete', $project->code)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover o projeto {{$project->name}}</p>
                    <div class="float-right">
                        <a href="{{route('project')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection