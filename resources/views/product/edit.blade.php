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
                    <form id="msform" action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <!-- progressbar -->

                        <fieldset>
                            <div class="form-card">
                                <div class="row">


                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Nome</label>
                                        <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="nome do produto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Observação</label>
                                        <input type="text" name="obs" value="{{ $product->obs }}" class="form-control" placeholder="observação sobre o produto">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Valor</label>
                                        <input type="text" id="valor" value="{{ $product->price }}" name="price" class="form-control" placeholder="Valor do produto">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Fornecedor</label>
                                        <select class="form-control" name="fornecedor" id="exampleFormControlSelect1">
                                            <option>- Selecione uma opção -</option>
                                            @foreach ($fornecedores as $fornecedor)
                                            <option value="{{ $fornecedor->name }}" @if( $fornecedor->name == $product->fornecedor) selected @endif>{{ $fornecedor->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleFormControlSelect1">Unidade de Venda</label>
                                        <select class="form-control" name="unidade" id="exampleFormControlSelect1">
                                            @foreach ($unidades as $unidade)
                                            <option value="{{ $unidade->name }}" @if($unidade->name == $product->unidade) selected @endif>{{ $unidade->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleFormControlSelect1">Tipo de Produto</label>
                                        <select class="form-control" name="tipo" id="type">
                                            <option>- Selecione uma opção -</option>
                                            <option value="produto" @if ($product->tipo == 'produto' )
                                                selected
                                            @endif>Produto</option>
                                            <option value="serviço" @if ($product->tipo == 'serviço' )
                                                selected
                                            @endif>Serviço</option>
                                        </select>
                                    </div>
                                    <div id="atributo" class="row d-none">
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Peso (Kg)</label>
                                            <input type="text" name="peso" id="teste" value="{{ $product->peso }}" class="form-control"
                                                placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Altura (cm)</label>
                                            <input type="text" name="altura" value="{{ $product->altura }}" class="form-control" placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Largura (cm)</label>
                                            <input type="text" name="largura" value="{{ $product->largura }}" class="form-control" placeholder="Fornecedor do produto">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1">Comprimento (cm)</label>
                                            <input type="text" name="comprimento" value="{{ $product->comprimento }}" class="form-control" placeholder="Fornecedor do produto">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleFormControlSelect1">Categoria</label>
                                        <select class="form-control selectpicker" data-live-search="true" name="categoria" id="type">

                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" @if ($cat->id == $product->categoria )
                                                    selected
                                                @endif>{{ $cat->name }}</option>
                                            @endforeach


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
                                            rows="4">{{ $product->observacao }}</textarea>
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
            })

        });
    </script>
@endsection
