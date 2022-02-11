@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('company') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <h1>Dados da empresa</h1>
        <div class="col-12">
            <form method="POST" action="{{route('company.update')}}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Informações Gerais</h4>
                        <div class="row">
                            <div class="col-12 col-sm-7">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" name="name" class="form-control" value="{{$company->name}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <input type="text" name="description" class="form-control" value="{{$company->description}}">
                                </div> 
                                
                                <div class="form-group">
                                    <label>CNPJ/CPF</label>
                                    <the-mask :mask="['###.###.###-##','##.###.###/####-##']" type="text" name="cnpj" class="form-control" value="{{$company->cnpjcpf}}">                            
                                </div> 
                                
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <the-mask :mask="['(##) ####-####','(##) #####-####']" type="phone" name="phone" class="form-control" value="{{$company->phone}}">                            
                                </div> 
                                
                                <div class="form-group">
                                    <label>Celular</label>
                                    <the-mask :mask="['(##) ####-####','(##) #####-####']" type="phone" name="mobile" class="form-control" value="{{$company->mobile}}">                           
                                </div>   
                            </div>
                            <div class="col-12 col-sm-5">                                                        
                                <div class="form-group text-center">
                                    <company-logo-uploader-component :img="'{{route('company.logo', $company->logo)}}'" :button-text="'Atualizar'"
                                        :urlupload="'{{route('company.logo.upload').'?_token=' . csrf_token() }}'"></company-logo-uploader-component>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row card-deck">
                    <div class="card col-12 col-sm-6">
                        <div class="card-body">
                            <h4 class="card-title">Endereço</h4>   
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" name="address" class="form-control" value="{{$company->address}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Número</label>
                                    <input type="text" name="addressnumber" class="form-control" value="{{$company->addressnumber}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <input type="text" name="addresscomplement" class="form-control" value="{{$company->addresscomplement}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Bairro</label>
                                    <input type="text" name="neighborhood" class="form-control" value="{{$company->neighborhood}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name="city" class="form-control" value="{{$company->city}}">
                                </div>
                                
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" name="state" class="form-control" value="{{$company->state}}">
                                </div>
                        </div>
                    </div>
                    <div class="card col-12 col-sm-6">
                        <div class="card-body">
                            <h4 class="card-title">Responsáveis</h4>   
                            <div class="col-12">                    
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Sobrenome</th>
                                            <th scope="col">E-mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($company->responsibles() as $user)                            
                                        <tr>
                                            <th scope="row"><a href="{{route('company.users.edit', $user->id)}}">{{$loop->index+1}}</a></th>
                                            <td><a href="{{route('company.users.edit', $user->id)}}">{{$user->name}}</a></td>
                                            <td><a href="{{route('company.users.edit', $user->id)}}">{{$user->lastname}}</a></td>
                                            <td><a href="{{route('company.users.edit', $user->id)}}">{{$user->email}}</a></td>                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="float-right mt-4">
                    <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
