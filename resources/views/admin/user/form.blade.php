@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('admin.user') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($user->id > 0)
        <h1>Atualizar usuário</h1>
        <form method="POST" action="{{route('admin.user.edit', $user->id)}}">
        @else
        <h1>Novo usuário</h1>
        <form method="POST" action="{{route('admin.user.create')}}">
        @endif

            @csrf
            <div class="row card-deck my-3 ">
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
                            <label>Empresa</label>
                            <select name="company" class="form-control">
                                <option value="0">Sem empresa vinculada</option>
                                @foreach ($companies as $company)
                                <option value="{{$company->id}}"@if(($user->company->id ?? "0") == $company->id) selected @endif>{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Celular</label>
                            <the-mask :mask="['(##) ####-####','(##) #####-####']" type="phone" name="mobile" class="form-control" value="{{$user->mobile}}">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Administrador do sistema</h4>
                        <p><strong>Cuidado!</strong> Ao ativar essa opção, o usuário poderá realizar a gestão de todos os elementos do sistema.
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" @if($user->isAdmin()) checked @endif name="isAdmin" value="true" class="custom-control-input" id="isAdminCheck">
                                <label class="custom-control-label" for="isAdminCheck">Super Admin</label>
                            </div>
                        </div>

                        <br/>
                        <br/>
                        <br/>
                        <h4 class="card-title">Permissões de acesso</h4>
                        @foreach ($profiles as $profile)
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" @if($user->hasProfile($profile->code)) checked @endif name="profile[]" value="{{$profile->code}}" class="custom-control-input" id="customCheck{{$profile->code}}">
                                <label class="custom-control-label" for="customCheck{{$profile->code}}">{{$profile->name}}</label>
                            </div>
                        </div>
                        @endforeach

                        <br/>
                        <br/>
                        <br/>
                        @if ($user->id > 0)
                        <h4 class="card-title mt-3">Senha de acesso</h4>
                        <a href="{{route('admin.user.reset_password', $user->id)}}" onclick="return confirm('Tem certeza que deseja resetar a senha do usuário?');" class="btn btn-warning">Resetar senha</a>
                        @endif
                    </div>
                </div>
            </div>
                <div class="float-right">
                    @if ($user->id > 0)
                    <a href="{{route('admin.user.delete', $user->id)}}" class="btn btn-warning">Excluir</a>
                    @endif
                    <a href="{{route('admin.user')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
        </form>
        
    </div>
@endsection