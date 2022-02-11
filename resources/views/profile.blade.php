@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('profile') }}
@endsection

@section('content') 
<div class="container-fluid">        
    <div class="col-12">
        <h1>Meus dados</h1>
        <form method="POST" action="{{route('profile.edit')}}">
            @csrf
            
            <div class="row card-deck my-3 ">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Sobrenome</label>
                            <input type="text" name="lastname" class="form-control" value="{{Auth::user()->lastname}}" required>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{Auth::user()->email}}" required>
                        </div>
                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" name="cpf" class="form-control" value="{{Auth::user()->cpf}}" required>
                        </div>
                        <div class="form-group">
                            <label>Celular</label>
                            <the-mask :mask="['(##) ####-####','(##) #####-####']" type="phone" name="mobile" class="form-control" value="{{Auth::user()->mobile}}">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Senha de acesso</h4>
                        <div class="form-group">
                            <label>Senha anterior</label>
                            <input type="password" name="old_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Senha nova</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Confirme a senha nova</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
    </div>
</div>
@endsection
