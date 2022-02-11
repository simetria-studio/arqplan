@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('calendar') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="col-12">
            <h1>Agenda</h1>
            <calendar-component :projects="{{$projects}}"></calendar-component>
        </div>
    </div>
@endsection

@push('css')
@endpush


@section('js') 
@endsection