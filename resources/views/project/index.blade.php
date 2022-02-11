@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project') }}
@endsection

@section('content') 
<div class="container-fluid">
    <div class="col-12 clearfix">
        <h1>Projetos</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px">Código</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)                        
                        <tr>
                            <td><a href="{{route('project.show', $project->code)}}">{{$project->code}}</a></td> 
                            <td><a href="{{route('project.show', $project->code)}}">{{$project->name}}</a></td> 
                            <td><a href="{{route('project.show', $project->code)}}">{{$project->category->name ?? ""}}</a></td>    
                            <td><a href="{{route('project.show', $project->code)}}">{{$project->client->name ?? ""}}</a></td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        @if ( Auth::user()->hasProfile("PROJECT_EDIT"))
        <div class="float-right">
            <a href="{{route('project.new')}}" class="btn btn-primary">Adicionar novo projeto</a>
        </div>
        @endif
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