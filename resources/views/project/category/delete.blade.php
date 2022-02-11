@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project.category') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover categoria</h1>
        <form method="POST" action="{{route('project.category.delete', $projectCategory->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover a categoria de projeto {{$projectCategory->name}}</p>
                    <div class="float-right">
                        <a href="{{route('project.category')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection