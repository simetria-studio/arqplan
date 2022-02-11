@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('company.users') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Remover usuário</h1>
        <form method="POST" action="{{route('company.users.delete', $user->id)}}">
            @csrf
            <div class="card">
                <div class="card-body">
                    <p> Deseja realmente remover o usuário {{$user->name}} ({{$user->email}})?</p>
                    <div class="float-right">
                        <a href="{{route('company.users')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Excluir</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection