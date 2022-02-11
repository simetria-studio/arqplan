@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('finance') }}
@endsection

@section('content') 
    <div class="container-fluid">        
        <div class="col-12">
            <h1>Financeiro</h1>            
            <finance-component></finance-component>
        </div>     
     
        
    </div>
@endsection
