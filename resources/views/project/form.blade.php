@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('project') }}
@endsection

@section('content') 
    <div class="container-fluid">
        @if ($project->id > 0)
        <h1>Atualizar projeto</h1>
        <form method="POST" action="{{route('project.edit', $project->code)}}">
        @else
        <h1>Novo projeto</h1>
        <form method="POST" action="{{route('project.create')}}">
        @endif

            @csrf
            <div class="row card-deck my-3 ">
                <div class="card text-nowrap">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="name" class="form-control" value="{{$project->name}}">
                        </div>
                        <div class="form-group">
                            <label>Categoria</label>
                            
                            <select name="category" class="form-control">
                                <option value="">-</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}"@if(($project->category->id ?? "0") == $category->id) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cliente</label>
                            
                            <select name="client" class="form-control">
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}"@if(($project->client->id ?? "0") == $client->id) selected @endif>{{$client->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Início</label>
                                <input type="date" class="form-control" name="startDate" data-date-format="DD-MM-YYYY" value="{{$project->startDate->format('Y-m-d')??""}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Término</label>
                                <input type="date" class="form-control" name="endDate" data-date-format="DD-MM-YYYY" value="{{$project->endDate->format('Y-m-d')??""}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Responsável</label>
                            <select name="responsibles[]" class="form-control">
                                @foreach ($users as $user)
                                <option value="{{$user->id}}"@if($project->isResponsible($user->id)) selected @endif>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Escopo</label>
                            <textarea type="text" name="scope" class="form-control" rows="6">{{$project->scope}}</textarea>
                        </div>
                    </div>  
                </div>
                
                <div class="card text-nowrap">
                    <div class="card-body">
                        <div class="form-group">
                            
                            <table class="tab2wle ">
                                <thead>
                                    <tr>
                                        <td class="font-size-18"><strong>Etapas</strong></td> 
                                        <td class="px-5 font-size-18"><strong>Status</strong></td> 
                                        <td class="font-size-18"><strong>Data Finalização</strong></td> 
                                    </tr>
                                </thead>
                                <tbody class="sortableTable">
                                @foreach ($allSteps as $step)
                                <tr class="ui-state-default">
                                    <td class="text-nowrap"><input name="position_{{$loop->index}}" value="{{$project->getStepStatusPosition($step->id)}}" type="hidden" name_item="{{$step->name}}"/><input name="step_{{$loop->index}}" value="{{$step->id}}" type="checkbox" @if($project->hasStep($step->id)) checked @endif/> <strong>{{$step->name}}</strong></td> 
                                    <td class="px-5">
                                        <select name="status_{{$loop->index}}" class="form-control">
                                            @foreach ($allStatus as $status)
                                            <option value="{{$status->id}}"@if($project->getStepStatusId($step->id) == $status->id) selected @endif>{{$status->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input name="endDate_{{$loop->index}}" type="date" class="form-control" data-date-format="DD-MM-YYYY" @if($project->hasStep($step->id))value="{{strtotime($project->getStepStatusEndDate($step->id)) > 0 ? date('Y-m-d', strtotime($project->getStepStatusEndDate($step->id))) : ""}}"@endif></td> 
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-right">*Pode ordenar as etapas como desejar</p>
                    </div>  
                </div>
            </div>

            <div class="float-right">
                @if ($project->id > 0)
                <a href="{{route('project.delete', $project->code)}}" class="btn btn-warning">Excluir</a>
                <a href="{{route('project.show', $project->code)}}" class="btn btn-secondary">Cancelar</a>
                @else
                <a href="{{route('project')}}" class="btn btn-secondary">Cancelar</a>
                @endif
                <button type="submit" class="btn btn-primary">Gravar</button>
            </div>
        </form>
        
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $(".sortableTable").sortable({
            placeholder: "ui-state-highlight",
            stop: function() {
                $(".sortableTable tr input[type=hidden]").each(function( index ) {
                    $( this ).val(index+1);
                });
            }
        });

        $.fn.moveTo = function(i) {
            var p = this.parent()
            var c = this.detach()
            var cs = p.children()
            cs = [].concat(cs.slice(0,i), c.get(), cs.slice(i))
            p.html(cs)
        }

        for (let index = 0; index < 10; index++) {                
            $(".sortableTable tr").each(function( index ) {
                var pos = $( this ).find("input[type=hidden]").val();
                if(pos > 0 && pos < 99){
                    $(this).moveTo(pos);
                }
            });
            $(".sortableTable tr").each(function( index ) {
                var pos = $( this ).find("input[type=hidden]").val();
                if(pos == 99){
                    $( this ).insertAfter($(".sortableTable tr:last"));
                }
            });
        }
});
</script>
@endsection
