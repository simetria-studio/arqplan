@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('products') }}
@endsection

@section('content')
    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                Cadastro de produtos
            </div>
            <div class="card-body">
                <div>
                    <form id="msform" action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- progressbar -->

                        <fieldset>
                            <div class="form-card">
                                <div class="row">


                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Nome</label>
                                        <input type="text" name="name" class="form-control" placeholder="nome do produto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Observação</label>
                                        <input type="text" name="obs" class="form-control"
                                            placeholder="observação sobre o produto">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Valor</label>
                                        <input type="text" id="valor" name="price" class="form-control"
                                            placeholder="Valor do produto">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Fornecedor</label>
                                        <select class="form-control" name="fornecedor" id="exampleFormControlSelect1">
                                            <option>- Selecione uma opção -</option>
                                            @foreach ($fornecedores as $fornecedor)
                                                <option value="{{ $fornecedor->name }}">{{ $fornecedor->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleFormControlSelect1">Unidade de Venda</label>
                                        <select class="form-control" name="unidade" id="exampleFormControlSelect1">
                                            <option>- Selecione uma opção -</option>
                                            <option value="Unidade">Unidade</option>
                                            <option value="Metro">Metro</option>
                                            <option value="M²">M²</option>
                                            <option value="M³">M³</option>
                                            <option value="CM">CM</option>
                                            <option value="Horas">Horas</option>
                                            <option value="KG">KG</option>
                                            <option value="Gr">Gr</option>
                                            <option value="Ton">Ton</option>
                                            <option value="Barra">Barra</option>
                                            <option value="Bloco">Bloco</option>
                                            <option value="Bobina">Bobina</option>
                                            <option value="Caixa">Caixa</option>
                                            <option value="Cento">Cento</option>
                                            <option value="Folha">Folha</option>
                                            <option value="Galão">Galão</option>
                                            <option value="Kit">Kit</option>
                                            <option value="Lata Litro">Lata Litro</option>
                                            <option value="Milheiro">Milheiro</option>
                                            <option value="Par">Par</option>
                                            <option value="Palete">Palete</option>
                                            <option value="Resma">Resma</option>
                                            <option value="Saco">Saco</option>
                                            <option value="Tambor">Tambor</option>
                                            @foreach ($unidades as $unidade)
                                                <option value="{{ $unidade->name }}">{{ $unidade->name }}</option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleFormControlSelect1">Tipo de Produto</label>
                                        <select class="form-control" name="tipo" id="type">
                                            <option>- Selecione uma opção -</option>
                                            <option value="produto">Produto</option>
                                            <option value="serviço">Serviço</option>
                                        </select>
                                    </div>
                                    <div id="atributo" class="row d-none">
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Peso (Kg)</label>
                                            <input type="text" name="peso" id="teste" class="form-control"
                                                placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Altura (cm)</label>
                                            <input type="text" name="altura" class="form-control"
                                                placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Largura (cm)</label>
                                            <input type="text" name="largura" class="form-control"
                                                placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Comprimento (cm)</label>
                                            <input type="text" name="comprimento" class="form-control"
                                                placeholder="Fornecedor do produto">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleFormControlSelect1">Categoria</label>
                                        <select class="form-control selectpicker" name="categoria" data-live-search="true"
                                            id="type">
                                            {{-- <option>- Selecione uma opção -</option> --}}
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                            <option value="Cadeiras">Cadeiras</option>
                                            <option value="Mesas de Jantar">Mesas de Jantar</option>
                                            <option value="Mesas de Centro">Mesas de Centro</option>
                                            <option value="Mesas Laterais">Mesas Laterais</option>
                                            <option value="Utensílios">Utensílios</option>
                                            <option value="Estofados">Estofados</option>
                                            <option value="Bancos e Banquetas">Bancos e Banquetas</option>
                                            <option value="Componentes Elétricos">Componentes Elétricos</option>
                                            <option value="Componentes Hidráulicos">Componentes Hidráulicos</option>
                                            <option value="Tintas e Solventes">Tintas e Solventes</option>
                                            <option value="Serviço Interno">Serviço Interno</option>
                                            <option value="Serviço Terceirizado">Serviço Terceirizado</option>
                                            <option value="Serviço de Marcenaria">Serviço de Marcenaria</option>
                                            <option value="Serviço de Hidráulica">Serviço de Hidráulica</option>
                                            <option value="Serviço de Elétrica">Serviço de Elétrica</option>
                                            <option value="Pinturas e Reparos">Pinturas e Reparos</option>
                                            <option value="Terraplanagem">Terraplanagem</option>
                                            <option value="Refrigeração">Refrigeração</option>

                                        </select>
                                    </div>

                                </div>
                            </div> <input type="button" name="next" class="next action-button btn btn-dark float-right"
                                value="Próximo" />
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="input-group mb-3 col-md-6">

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" id="inputGroupFile01"
                                                aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01">Foto do produto</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" name="next" class="btn btn-dark next action-button float-right"
                                value="Next">Próximo</button>
                            <button type="button" name="previous"
                                class="btn btn-cyan previous action-button-previous float-right mr-3"
                                value="Previous">Anterior</button>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="exampleFormControlTextarea1">Descrição do produto</label>
                                        <textarea class="form-control" name="observacao" id="exampleFormControlTextarea1"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="next" class="btn btn-success next action-button float-right"
                                value="Next">Finalizar</button>
                            <button type="button" name="previous"
                                class="btn btn-cyan previous action-button-previous float-right mr-3"
                                value="Previous">Anterior</button>

                        </fieldset>


                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#type').on('change', function() {
                $('#atributo').addClass('d-none');
                if ($(this).val() == 'produto') {
                    $('#atributo').removeClass('d-none');
                }
            });
            $('.selectpicker').selectpicker();

        });
    </script>
@endsection
