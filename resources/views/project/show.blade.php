@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('project') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row col-12">
            <div class="mr-auto">
                <h1>Projeto {{ $project->name }}</h1>
            </div>
            @if (Auth::user()->hasProfile('PROJECT_EDIT'))
                <div class="float-right">
                    <a href="{{ route('project.edit', $project->code) }}" class="btn btn-primary">editar</a>
                </div>
            @endif
        </div>

        <div class="row card-deck my-3">
            <div class="card col-lg-6">
                <div class="card-body">
                    <div>
                        <strong>Cliente:</strong> {{ $project->client->name ?? '-' }}
                        <br>
                        <strong>Escopo:</strong> {{ $project->scope }}
                    </div>
                </div>
            </div>
            <div class="card col-lg-6">
                <div class="card-body">
                    <table class="tab2wle">
                        <tbody>
                            <thead>
                                <td class="font-size-18"><strong>Etapas</strong></td>
                                <td class="px-5 font-size-18"><strong>Status</strong></td>
                                <td class="font-size-18"><strong>Prazo</strong></td>
                            </thead>
                            @foreach ($project->steps as $step)
                                <tr>
                                    <td>{{ $step->project_step->name }}</td>
                                    <td class="px-5">{{ $step->project_status->name ?? '-' }}</td>
                                    <td>{{ strtotime($step->endDate) > 0 ? date('d/m/Y', strtotime($step->endDate)) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row card-deck">
            <div class="card col-lg-6">
                <div class="card-body">
                    <h5 class="card-title">Próximos Eventos</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nextEvents as $nextEvent)
                                <tr>
                                    <td>{{ $nextEvent->title }}</td>
                                    <td>{{ date('d-m-Y H:i', strtotime($nextEvent->start)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-6">
                <div class="card-body">
                    <h5 class="card-title">Arquivos</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">&nbsp;</th>
                                @if (Auth::user()->hasProfile('PROJECT_EDIT'))
                                    <th scope="col">&nbsp;</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->files() as $file)
                                <tr>
                                    <td>{{ $file->name }}</td>
                                    <td><a href="{{ route('project.files.open', ['code' => $project->code, 'file_id' => $file->id]) }}"
                                            target="_blank">Abrir</a></td>
                                    <td><a href="{{ route('project.files.download', ['code' => $project->code, 'file_id' => $file->id]) }}"
                                            download>Download</a></td>
                                    @if (Auth::user()->hasProfile('PROJECT_EDIT'))
                                        <td><a href="{{ route('project.files.remove', ['code' => $project->code, 'file_id' => $file->id]) }}"
                                                onclick="return confirm('Tem certeza que deseja excluir esse arquivo?');">Excluir</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <Project-File-Upload-Component :project="{{ $project->code }}" />
                </div>
            </div>
        </div>
        <div class="row col-12 mt-2 mb-2">
            <div class="float-left ml-auto">
                <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-dark">Adicionar
                    Produto/Serviço</a>
            </div>
            <div class="float-right ml-auto">
                <a href="{{ route('project') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
        @php
            $valorTotal = 0;
            $valorServico = 0;
            $valorProduto = 0;
            $valorCpe = 0;
        @endphp
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Imagem</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">UN</th>
                    <th scope="col">Total</th>
                    <th scope="col">Ações</th>
                    <th scope="col">CPE %</th>
                    <th scope="col">Valor cpe</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projandprods as $product)
                    @php
                        $tempCpe = $product->total - (($product->total * $product->cpe) / 100);
                        $valorCpe += $tempCpe;
                    @endphp
                    <tr>
                        <th>
                            <img style="width: 50px; height: 50px; object-fit: cover;"
                                src="{{ asset('storage/product_image/' . $product->products->image) }}" alt="">
                        </th>
                        <td class="capitalize">{{ $product->products->name }}</td>
                        <td class="capitalize">{{ $product->products->tipo }}</td>
                        <td class="inputEdit" data-product="{{ $product->id }}" data-collum="total"
                            data-mascara="true">{{ 'R$ ' . number_format($product->total, 2, ',', '.') }} </td>
                        <td class="inputEdit" data-product="{{ $product->id }}" data-collum="quantidade">
                            {{ $product->quantidade }}</td>
                        <td>{{ $product->products->unidade }}</td>
                        <td>{{ 'R$ ' . number_format($product->total * $product->quantidade, 2, ',', '.') }}</td>

                        <td>
                            <div class="d-flex">

                                <div>
                                    <button class="btn btn-cyan" data-toggle="modal"
                                        data-dados="{{ json_encode($product) }}" data-target="#modalCpe">CPE</button>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->cpe }}%</td>
                        <td>@if($product->cpe) {{ 'R$ ' . number_format($tempCpe, 2, ',', '.') }} @endif</td>
                        <td>
                            <div class="d-flex">
                                <div class="mx-3">
                                    <a href="{{ route('product.project.delete', $product->id) }}"> <button
                                            class="btn btn-primary">Deletar</button></a>
                                </div>

                            </div>
                        </td>
                    </tr>

                @endforeach


            </tbody>
        </table>
        <div class="d-flex justify-content-around">
            <div>
                <h6>Total: {{ 'R$ ' . number_format($valorTotal, 2, ',', '.') }}</h6>
            </div>
            <div>
                <h6>Total de Serviço: {{ 'R$ ' . number_format($valorServico, 2, ',', '.') }}</h6>
            </div>
            <div>
                <h6>Total de Produtos: {{ 'R$ ' . number_format($valorProduto, 2, ',', '.') }}</h6>
            </div>
            <div>
                <h6>Valor total CPE: {{ 'R$ ' . number_format($valorCpe, 2, ',', '.') }}</h6>
            </div>

        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CPE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.project') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleFormControlSelect1">Selecione o Produto</label>
                                <select class="form-control selectpicker" data-live-search="true" name="product_id"
                                    id="prod">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-tipo="{{ $product->tipo }}">
                                            {{ $product->name }}
                                            @if ($product->tipo == 'produto')-->Produto @else-->Serviço @endif</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="prodDiv" class="form-group col-md-12 ">
                                <label for="exampleFormControlSelect1">Selecione a quantidade</label>
                                <input type="number" class="form-control" name="quantidade">
                            </div>
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCpe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CPE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.update.cpe') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">CPE</label>
                                <input type="text" name="cpe" class="form-control">
                            </div>
                            <div id="divDados">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.inputEdit', function() {
                $(this).removeClass('inputEdit');
                $(this).html(
                    `<input type="text" class="form-control inputedit" value="${$(this).text().replace('R$ ', '')}">`
                    );
                if ($(this).data('mascara') == 'true') {
                    $(".inputedit").maskMoney({
                        allowNegative: true,
                        thousands: '.',
                        decimal: ',',
                        affixesStay: false
                    });
                }
            });

            $(document).on('blur', '.inputedit', function() {
                var valor = $(this).val();
                var collum = $(this).parent().data('collum');
                var id = $(this).parent().data('product');


                $(this).parent().addClass('inputEdit');
                $(this).parent().html($(this).val());

                $.ajax({
                    url: '/product/update',
                    type: 'POST',
                    data: {
                        collum: collum,
                        id: id,
                        value: valor
                    },
                    success: function() {
                        window.location.reload();
                    }
                });
            });

            $('[data-target="#modalCpe"]').on('click', function() {
                var dados = $(this).data('dados');
                console.log(dados);
                $('#divDados').empty();
                $.each(dados, (key, value) => {
                    $('#divDados').append(
                        `<input type="hidden" name="pj_${key}" value="${value}">`);
                });

                // console.log(dados.map(function(key, value){
                //     return `<input type="hidden" name="product_${key}" value="${value}">`;
                // }))
            });
            $('select').selectpicker();
        });
    </script>

@endsection
