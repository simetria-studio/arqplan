@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('products') }}
@endsection

@section('content')
    <div class="container">
        <div class="mt-5">
            <a href="{{ route('unidades.create') }}"><button class="btn btn-dark">Adicionar Nova Unidade</button></a>
        </div>
        <div class="mt-3 mb-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unidades as $uni)
                        <tr>
                            <th scope="row">{{$uni->id}}</th>
                            <td>{{ $uni->name }}</td>
                            <td>
                                <button class="btn btn-primary">Deletar</button>
                            </td>

                        </tr>

                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endsection
