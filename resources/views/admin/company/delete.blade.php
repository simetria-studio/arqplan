@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('admin.company') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover empresa</h1>
        <form method="POST" action="{{route('admin.company.delete', $company->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover a empresa {{$company->name}}?</p>
                    <div class="float-right">
                        <a href="{{route('admin.company')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection