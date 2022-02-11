@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('admin.user') }}
@endsection

@section('content') 
    <div class="container-fluid">
        <div class="col-12">
            <h1>Usuários</h1>
            <div class="card">
                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Sobrenome</th>
                                <th scope="col">Empresa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)                            
                            <tr>
                                <th scope="row"><a href="{{route('admin.user.edit', $user->id)}}">{{$loop->index+1}}</a></th>
                                <td>@if ($user->isAdmin()) <i data-feather="award" class="feather-16"></i> @endif<a href="{{route('admin.user.edit', $user->id)}}">{{$user->name}}</a></td>
                                <td><a href="{{route('admin.user.edit', $user->id)}}">{{$user->lastname}}</a></td>
                                <td><a href="{{route('admin.user.edit', $user->id)}}">{{$user->company->name ?? "-"}}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="float-right">
                <a href="{{route('admin.user.new')}}" class="btn btn-primary">Adicionar novo usuário</a>
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