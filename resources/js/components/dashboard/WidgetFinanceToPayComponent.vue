<script>
export default {
    components: {
    },
    data: function () {
        return {
            amount: 0,  
            transactions: 0,  
            range: 'week',
            dataTable: null,
        };
    },
    mounted() {
        this.loadData();
    },
    methods: {      
        showLoading: function() {
            $(this.$refs.loading).stop().show();
        },
        
        hideLoading: function() {
            $(this.$refs.loading).stop().hide();
        },

        loadData: function() {
            this.showLoading();
            axios.get('/api/finance/topay/'+this.range).then((response)=>{
                this.transactions = response.data.transactions;
                this.amount = response.data.amount;
                this.updateDataTable();
            }).finally(()=>{
                this.hideLoading();
            });
        },

        updateDataTable: function(){     
            if(this.dataTable){
                this.dataTable.destroy();
            };

            
            this.$nextTick(() => {
                this.dataTable = $(this.$refs.table).DataTable({
                    autoWidth: false,
                    responsive: true,
                    scrollX: true,
                    ordering: false,
                    info: false,
                    paging: true,
                    bLengthChange: false,
                    pageLength: 10,
                    searching: false,
                    language: {
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhuma conta a pagar",
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
        },
    },
};
</script>


<template>
    <div id="WidgetFinancePaysToBillComponent" class="col-lg-6 col-md-12">
        <div class="card border-right">
            <div class="card-body">
                <div class="componentLoading" ref="loading">
                    <div class="lds-ripple">
                        <div class="lds-pos"></div>
                        <div class="lds-pos"></div>
                    </div>
                </div>
                    

                <div class="row">
                    <div class="col-6 align-self-center">
                        <h4 class="card-title">Contas a pagar</h4>
                    </div>
                    <div class="col-6 align-self-center">
                        <div class="customize-input float-right">
                            <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow" v-model="range" @change="loadData">
                                <option value="week">esta semana</option>
                                <option value="month">este mês</option>
                                <option value="nextmonth">próximo mês</option>
                            </select>
                        </div>
                    </div>
                </div>          
                        
                <table class="table col-12" ref="table">
                    <thead>
                        <tr>
                            <th scope="col">Conta</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Dia</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>                      
                        <tr v-bind:key="transaction.id" v-for="transaction in transactions">
                            <td scope="row">{{transaction.account}}</td>
                            <td scope="row">{{transaction.description}}</td>
                            <td scope="row">{{ transaction.formatedDate }}</td>
                            <td scope="row" class="fsize14"><money-text :value="parseFloat(transaction.type == 'C' ? transaction.amount : -transaction.amount)" :colored="false" :ignoreSignal="true" /></td>
                            <td scope="row" v-if="transaction.status == 'OK'">Pago</td>
                            <td scope="row" v-else>Pendente</td>
                        </tr>     
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-7 align-self-center">
                        Total: <money-text class="fsize-14" :value="amount" :colored="false"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
