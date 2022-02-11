@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.status') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover status</h1>
        <form method="POST" action="{{route('project.status.delete', $projectStatus->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover o status de projeto {{$projectStatus->name}}</p>
                    <div class="float-right">
                        <a href="{{route('project.status')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection