@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('products') }}
@endsection

@section('content')
    <div class="container">
        <div class="card mt-4">
            <div class="card-header">
                Cadastro de unidade
            </div>
            <div class="card-body">
                <div>
                    <form id="msform" action="{{ route('unidades.store') }}" method="post">
                        <!-- progressbar -->
                        @csrf
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                            <label for="exampleFormControlInput1">Unidade</label>
                                            <input type="text" name="name" class="form-control" placeholder="Nome da Unidade">
                                      </div>
                                </div>
                            </div>

                            <button type="submit" name="next" class="btn btn-success next action-button float-right"
                                value="Next">Finalizar</button>

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

        });
    </script>
@endsection
