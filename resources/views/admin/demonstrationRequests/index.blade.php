@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('admin.demonstration_requests') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="col-12">
            <h1>Solicitações de demonstração</h1>
            <div class="card">
                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">Número de funcionários</th>
                                <th scope="col">Utiliza outro Software?</th>
                                <th scope="col">Maior dificuldade</th>
                                <th scope="col">Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demonstrationRequests as $demonstrationRequest)                            
                            <tr>
                                <th scope="row">{{$loop->index+1}}</th>
                                <td>{{$demonstrationRequest->name}}</td>
                                <td>{{$demonstrationRequest->email}}</td>
                                <td>{{$demonstrationRequest->phone}}</td>
                                <td>{{$demonstrationRequest->employeesNumber}}</td>
                                <td>{{$demonstrationRequest->otherSoftware}}</td>
                                <td>{{$demonstrationRequest->mainDifficulty}}</td>
                                <td>{{$demonstrationRequest->created_at->format('d/m/Y h:i')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
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