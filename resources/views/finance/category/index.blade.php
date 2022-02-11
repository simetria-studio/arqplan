@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('finance.category') }}
@endsection

@section('content') 
<div class="container-fluid">    
    <div class="col-12 clearfix">
        <h1>Categorias</h1>
        <div class="card">
            <div class="card-body">

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col" style="width: 50px"> </th>
                            <th scope="col" style="width: 50px"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        
                        <tr>
                            <td>{{$category->name}}</td>    
                            <td>@if ($category->company_id)<a href="{{route('finance.category.edit', $category->id)}}">Editar</a>@else @endif</td>   
                            <td>@if ($category->company_id)<a href="{{route('finance.category.delete', $category->id)}}">Excluir</a>@else @endif</td>     
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="float-right">
            <a href="{{route('finance.category.new')}}" class="btn btn-primary">Adicionar nova categoria</a>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('table').DataTable({            
            language: {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                },
                "buttons": {
                    "copy": "Copiar para a área de transferência",
                    "copyTitle": "Cópia bem sucedida",
                    "copySuccess": {
                        "1": "Uma linha copiada com sucesso",
                        "_": "%d linhas copiadas com sucesso"
                    }
                }
            },
        });
    });
</script>
@endsection