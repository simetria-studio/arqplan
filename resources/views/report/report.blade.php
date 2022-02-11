@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('report') }}
@endsection

@section('content') 
<div class="container-fluid">    
    <report-component :report="{{json_encode($report, TRUE)}}"></report-component>
</div>
@endsection
