@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('admin.company') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($company->id > 0)
        <h1>Atualizar empresa</h1>
        <form method="POST" action="{{route('admin.company.edit', $company->id)}}">
        @else
        <h1>Novo empresa</h1>
        <form method="POST" action="{{route('admin.company.create')}}">
        @endif

            @csrf
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informações Gerais</h4>
                    <div class="row">
                        <div class="col-12 col-sm-7">
                            <div class="form-group">
                                <label>Ativo</label>
                                <select name="enabled" class="form-control">
                                    <option value="1" @if($company->enabled) selected @endif>Sim</option>
                                    <option value="0" @if(!$company->enabled) selected @endif>Não</option>
                                </select>
                            </div>

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
                                <the-mask :mask="['###.###.###-##','##.###.###/####-##']" type="text" name="cnpjcpf" class="form-control" value="{{$company->cnpjcpf}}">                            
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
                        @if ($company->id > 0)
                        <div class="col-12 col-sm-5">                                                        
                            <div class="form-group text-center">
                                <company-logo-uploader-component :img="'{{route('admin.company.logo', $company->id ? $company->id : 0)}}'" :button-text="'Atualizar'"
                                    :urlupload="'{{route('admin.company.logo.upload', $company->id ? $company->id : 0).'?_token=' . csrf_token() }}'"></company-logo-uploader-component>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
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
            <div class="float-right">
                @if ($company->id > 0)
                <a href="{{route('admin.company.delete', $company->id)}}" class="btn btn-warning">Excluir</a>
                @endif
                <a href="{{route('admin.company')}}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection