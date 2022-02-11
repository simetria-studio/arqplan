@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('task') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="col-12">
            <h1>Atividades - Kanban</h1>

            <div class="card">
                <div class="card-body">
                    <kanban-component :projects="{{$projects}}"></kanban-component>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush


@section('js') 
@endsection