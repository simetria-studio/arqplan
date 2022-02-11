@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('company.users') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($user->id > 0)
        <h1>Atualizar usuário</h1>
        <form method="POST" action="{{route('company.users.edit', $user->id)}}">
        @else
        <h1>Novo usuário</h1>
        <form method="POST" action="{{route('company.users.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
                    </div>
                    <div class="form-group">
                        <label>Sobrenome</label>
                        <input type="text" name="lastname" class="form-control" value="{{$user->lastname}}" required>
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{$user->email}}" required>
                    </div>
                    <div class="form-group">
                        <label>CPF</label>
                        <the-mask :mask="'###.###.###-##'" type="text" name="cpf" class="form-control" value="{{$user->cpf}}" required>
                    </div>
                    <div class="form-group">
                        <label>Celular</label>
                        <the-mask :mask="['(##) ####-####','(##) #####-####']" type="phone" name="mobile" class="form-control" value="{{$user->mobile}}">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Permissões de acesso</h4>
                    @foreach ($profiles as $profile)
                    <div class="form-check form-check-inline">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" @if($user->hasProfile($profile->code)) checked @endif name="profile[]" value="{{$profile->code}}" class="custom-control-input" id="customCheck{{$profile->code}}">
                            <label class="custom-control-label" for="customCheck{{$profile->code}}">{{$profile->name}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="float-right">
                @if ($user->id > 0)
                <a href="{{route('company.users.delete', $user->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('company.users')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection