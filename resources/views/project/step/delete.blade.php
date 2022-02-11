@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.step') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover etapa</h1>
        <form method="POST" action="{{route('project.step.delete', $projectStep->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover a etapa de projeto {{$projectStep->name}}</p>
                    <div class="float-right">
                        <a href="{{route('project.step')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection