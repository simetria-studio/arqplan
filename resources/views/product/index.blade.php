@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('products') }}
@endsection
@php
$total = 0;
@endphp
@section('content')
    <div class="container">
        <div class="mt-5">
            <a href="{{ route('products.create') }}"><button class="btn btn-dark">Adicionar Novo Produto</button></a>
        </div>
        <div class="mt-3 mb-5">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Imagem</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Valor</th>
                        {{-- <th scope="col">Quantidade</th> --}}
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <th>
                                <img style="width: 50px; height: 50px; object-fit: cover;"
                                    src="{{ asset('storage/product_image/' . $product->image) }}" alt="">
                            </th>
                            <td class="capitalize">{{ $product->name }}</td>
                            <td class="capitalize">{{ $product->tipo }}</td>
                            <td>{{ 'R$ ' . number_format($product->price, 2, ',', '.') }} </td>
                            {{-- <td>{{ $product->quantidade }}</td> --}}
                            <td>
                                <div class="d-flex">
                                    <div class="mx-3">
                                       <a href="{{ route('products.delete', $product->id) }}"> <button class="btn btn-primary">Deletar</button></a>
                                    </div>
                                    <div>
                                        <a href="{{ route('products.show', $product->id) }}"> <button class="btn btn-cyan">Editar</button></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $total += $product->price;
                        @endphp
                    @endforeach


                </tbody>
            </table>

        </div>

    </div>

@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('[data-target="#exampleModal"]').on('click', function() {
                var dados = $(this).data('dados');
                $('#divDados').empty();
                $.each(dados, (key, value) => {
                    $('#divDados').append(`<input type="hidden" name="product_${key}" value="${value}">`);
                });

                // console.log(dados.map(function(key, value){
                //     return `<input type="hidden" name="product_${key}" value="${value}">`;
                // }))
            });

        });
    </script>

@endsection
