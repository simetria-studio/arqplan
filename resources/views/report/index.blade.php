@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('report') }}
@endsection

@section('content') 
<div class="container-fluid">        
    <div class="col-12">
        <h1>Relatórios</h1>        
        
        <div class="row my-3">
            <div class="card col-12">
                @if ( Auth::user()->hasProfile('FINANCE_VIEW') || Auth::user()->hasProfile('FINANCE_EDIT') )
                <div class="card-body col-3">
                    <h4 class="card-title">Financeiro</h4>
                    <a href="{{route('report.finance.topay')}}">Contas à Pagar</a><br/>
                    <a href="{{route('report.finance.toreceive')}}">Contas à Receber</a><br/>
                </div>
                @endif
                <div class="card-body col-3">
                    <h4 class="card-title">Projetos</h4>
                    <a href="{{route('report.projects')}}">Lista de projetos</a><br/>
                </div>
                <div class="card-body col-3">
                    <h4 class="card-title">Calendário</h4>
                    <a href="{{route('report.calendar')}}">Lista de agendamentos</a><br/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
