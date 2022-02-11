@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('home') }}
@endsection

@section('content')   

<div class="container-fluid">  
    
    <div class="row mb-3">
        <div class="col-7 align-self-center">
            <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">{{ $greetings }} {{ Auth::user()->fullname() }}!</h3>
        </div>
    </div>

    @if ( Auth::user()->hasProfile('FINANCE_VIEW') || Auth::user()->hasProfile('FINANCE_EDIT') )
    <dashboard-component :finance="true"/>
    @else
    <dashboard-component/>
    @endif

    
</div>
@endsection

@section('js')
<script>
</script>
@endsection