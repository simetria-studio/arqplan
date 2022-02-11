@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('task') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="col-12">
            <h1>Atividades</h1>

            <div class="card">
                <div class="card-body">
                    <task-component :projects="{{$projects}}"></task-component>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush


@section('js') 
@endsection