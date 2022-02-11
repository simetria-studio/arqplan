<script>

import Vue from 'vue';
import VueFeather from 'vue-feather';
import TransactionModel  from './model/Transaction';
import AccountModel  from './model/Account';
import Transaction from './model/Transaction';
import TransactionParcel from './model/TransactionParcel';

Vue.use(VueFeather);

var bootbox = require('bootbox');

window.jQuery = $;
export default {
    props: [
    ],
    data() {
        return {
            currentTransaction: new TransactionModel(),
            blockEventUpdateMode: false,
            dataTable: null,
            step: 'accountsList',            
            updateRecurrentMode: null,
            datePickerOptions: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            },   
            timePickerOptions: {
                format: 'HH:mm',
                useCurrent: false,
            },    
            currentAccount: {},
            accounts: [],
            categories: [],
            peoples: [],
            projects: [],
            lastTransactions: [],
            filtredTransactions: [],
            filtredYear: 2000,
            filtredMonth: 1,
            money: {
                decimal: ',',
                thousands: '.',
                precision: 2,
                masked: true
            },
            msgError: '',
        }
    },
    mounted() {
        this.showLoading();
        this.showAccountList();
    },
    methods: {        
        showLoading: function() {
            $(".componentLoading").stop().show();
        },
        
        hideLoading: function() {
            $(".componentLoading").stop().hide();
        },
        
        loadTransactions: function() {
            this.showLoading();
            this.currentAccount.transactions = [];
            this.updateTransactionsFilter();
            axios.get('/api/finance/account/'+this.currentAccount.id+'/transactions').then((response)=>{
                this.currentAccount.transactions = response.data.transactions;
            }).finally(()=>{
                this.updateTransactionsFilter();
                this.hideLoading();
            });
        },  

        loadAccounts: function() {
            this.showLoading();
            this.accounts = [];
            axios.get('/api/finance/accounts').then((response)=>{
                this.accounts = response.data.accounts;
                this.categories = response.data.categories;
                this.peoples = response.data.peoples;
                this.projects = response.data.projects;
            }).finally(()=>{
                this.hideLoading();
            });
        },

        loadLastTransactions: function() {
            this.showLoading();
            this.lastTransactions = [];
            axios.get('/api/finance/last_transactions').then((response)=>{
                this.lastTransactions = response.data.transactions;
            }).finally(()=>{
                this.hideLoading();
            });
        },

        updateTransactionsFilter(){
            this.filtredTransactions = this.currentAccount.transactions.filter(t => t.month == this.filtredMonth && t.year == this.filtredYear, this);
        },
        updateMonth(month){
            this.filtredMonth = this.setTwodigits(month);
            this.updateTransactionsFilter();
        },
        updateYear(year){
            this.filtredYear = year;
            this.updateTransactionsFilter();
        },
        setTwodigits(number){
            return number > 9 ? number : '0'+number;
        },

        formatPrice(value) {
            let val = (value/1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        
        getAmountAsFloat(number){
            var fnumber = parseFloat(number.toString().replace(".", "").replace(",", "."));
            return fnumber;
        }, 
        
        getFloatAsAmount(number){
            return number.toString().replace(".", ",");
        }, 
        
        
        resetForms: function() {       
        },

        updateDataTable: function(){     
            if(this.dataTable) this.dataTable.destroy();
            this.dataTable = $('#financeComponent table').DataTable({
                ordering: false,
                info: false,
                paging: false,
                searching: false,
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
        },


        // Account methods
        showAccountList: function() {
            this.step = "accountsList";
            this.loadAccounts();
            this.loadLastTransactions();
        },

        showAccount: function(account) {
            this.resetForms();

            var today = new Date();
            this.filtredYear = today.getFullYear();
            this.filtredMonth = this.setTwodigits(today.getMonth()+1);

            this.currentAccount = account;
            this.currentAccount.transactions = [];
            this.loadTransactions();
            this.step = "accountShow";
        },





        // Accounts Methods      
        createNewAccount: function() {
            var self = this;  
            self.msgError = '';
            this.form = $('div#accountForm');
            this.currentAccount = new AccountModel();
            self.currentAccount.initial = parseFloat(self.currentAccount.initial).toFixed(2);

            bootbox.dialog({
                centerVertical: true,
                title: "Adicionar nova Conta",
                message: self.form,
                buttons: {
                    confirm: {
                        label: 'Gravar',
                        className: 'btn-success',
                        callback: async function(result){
                            self.showLoading();

                            await axios.post('/api/finance/account/new', self.currentAccount)
                                .then(resp => {
                                    self.showAccountList();
                                }).finally(()=>{
                                    self.hideLoading();
                                });
                        },
                    },
                    cancel: {
                        label: 'Cancelar',
                        className: 'btn-danger',
                        callback: function(){ },
                    }
                },
            });
            
            $(".bootbox").unbind('hidden.bs.modal');
        },  

        updateAccount: function(account) {
            var self = this;  
            self.msgError = '';
            this.form = $('div#accountForm');
            this.currentAccount = JSON.parse(JSON.stringify(account));
            self.currentAccount.initial = parseFloat(self.currentAccount.initial).toFixed(2);

            var dialog = bootbox.dialog({
                centerVertical: true,
                title: "Atualizar Conta",
                message: self.form,
                buttons: {
                    confirm: {
                        label: 'Gravar',
                        className: 'btn-success',
                        callback: async function (result) {
                            if(result){
                                self.showLoading();
                                await axios.put('/api/finance/account/'+account.id, self.currentAccount)
                                    .then(resp => {
                                        self.showAccountList();
                                    }).finally(()=>{
                                        self.hideLoading();
                                        dialog.modal('hide');
                                    });
                            }
                        }
                    },
                    cancel: {
                        label: 'Cancelar',
                        className: 'btn-danger',
                        callback: function(){ },
                    }
                },
                
            });
            
            $(".bootbox").unbind('hidden.bs.modal');
        },
        
        removeAccount: function(account) {
            var self = this;  
            self.msgError = '';
            
            bootbox.confirm({
                centerVertical: true,
                title: "Tem certeza que deseja remover essa conta? Todas as transações serão removidas.",
                message: account.name+'<br/>'+account.description,
                buttons: {
                    confirm: {
                        label: 'Sim',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: 'Não',
                        className: 'btn-danger',
                    }
                },
                callback: async function(result){
                    if(result){                            
                        self.showLoading();

                        await axios.delete('/api/finance/account/'+account.id, account)
                            .then(resp => {
                                self.showAccountList();
                            }).finally(()=>{
                                self.hideLoading();
                            });
                    }
                },
            });
        },





        // Transactions Methods      
        createNewTransaction: function() {
            var self = this;  
            self.msgError = '';
            this.currentTransaction = new TransactionModel();
            this.form = $('div#transactionForm');

            bootbox.dialog({
                centerVertical: true,
                title: "Adicionar nova transação",
                message: self.form,
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Gravar',
                        className: 'btn-success',
                        callback: function(){ 
                            self.msgError = '';

                            if(self.currentTransaction.parceled){     
                                var total = self.currentTransaction.parcels.reduce((acc, item) => acc + self.getAmountAsFloat(item['amount']), 0);                                
                                if(total != self.getAmountAsFloat(self.currentTransaction.amount)){
                                    self.msgError = 'A soma de todas as parcelas devem ser o mesmo que o valor total da transação';
                                    return false;
                                }
                            }

                            self.showLoading();

                            async function run() {
                                return await axios.post('/api/finance/account/'+self.currentAccount.id+'/transactions/new', self.currentTransaction);                                
                            }

                            run().then(resp => {
                                self.loadTransactions();
                            }).finally(()=>{
                                self.updateRecurrentMode = null;
                                self.hideLoading();
                            });
                        },
                    },
                    cancel: {
                        label: 'Cancelar',
                        className: 'btn-danger',
                        callback: function(){ },
                    }
                },
            });
            
            $(".bootbox").unbind('hidden.bs.modal');
        },  
        updateTransaction: function(transaction) {
            var self = this;  
            self.msgError = '';
            $(".bootbox").modal('hide');
            this.blockEventUpdateMode = true;
            this.currentTransaction = new TransactionModel();
            
            if(transaction.parceled){
                var parcels = JSON.parse(JSON.stringify(transaction.parcels));
                var tempTransaction = JSON.parse(JSON.stringify(transaction))
                tempTransaction.parcels = [];
                this.currentTransaction = tempTransaction;
                this.currentTransaction.amount = parcels.reduce((acc, item) => acc + parseFloat(item['amount']), 0).toFixed(2);

                parcels.forEach(parcel => {
                    this.currentTransaction.parcels[parcel.parcelNumber - 1] = parcel;
                });
                //this.currentTransaction.amount = this.currentTransaction.parcels.reduce((acc, item) => acc + parseFloat(item['amount']), 0).toFixed(2);
            }else{                
                var tempTransaction = JSON.parse(JSON.stringify(transaction))
                tempTransaction.parcels = [];
                this.currentTransaction = tempTransaction;
            }

            if(self.currentTransaction.recurrent && self.updateRecurrentMode == null){
                this.form = $('div#transactionFormUpdateRecurrent');

                bootbox.dialog({
                    centerVertical: true,
                    title: "Atualizar transação",
                    message: self.form,
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: 'Cancelar',
                            className: 'btn-danger',
                            callback: function(){ },
                        }
                    },
                    onEscape: true,
                });
            }else{
                this.form = $('div#transactionForm');

                bootbox.dialog({
                    centerVertical: true,
                    title: "Atualizar transação",
                    message: self.form,
                    closeButton: false,
                    buttons: {
                        confirm: {
                            label: 'Gravar',
                            className: 'btn-success',
                            callback: function(){ 
                                self.msgError = '';

                                if(self.currentTransaction.parceled){   
                                    var total = self.currentTransaction.parcels.reduce((acc, item) => acc + self.getAmountAsFloat(item['amount']), 0);                                
                                    if(total != self.getAmountAsFloat(self.currentTransaction.amount)){
                                        self.msgError = 'A soma de todas as parcelas devem ser o mesmo que o valor total da transação';
                                        return false;
                                    }
                                }

                                self.showLoading();
                                self.currentTransaction.updateRecurrentMode = self.updateRecurrentMode;

                                async function run() {
                                    return await axios.put('/api/finance/account/'+self.currentAccount.id+'/transactions/'+self.currentTransaction.id, self.currentTransaction);
                                }

                                run().then(resp => {
                                    self.loadTransactions();
                                }).finally(()=>{
                                    self.updateRecurrentMode = null;
                                    self.hideLoading();
                                });
                            },
                        },
                        cancel: {
                            label: 'Cancelar',
                            className: 'btn-danger',
                            callback: function(){                                
                                self.updateRecurrentMode = null;
                            },
                        }
                    },
                    onEscape: true,
                });
            }
            
            this.blockEventUpdateMode = false;
            $(".bootbox").unbind('hidden.bs.modal');
        },
        
        removeTransaction: function(transaction) {
            var self = this;
            self.msgError = '';            
            this.currentTransaction = new TransactionModel();
            var tempTransaction = JSON.parse(JSON.stringify(transaction))
            tempTransaction.parcels = [];
            this.currentTransaction = tempTransaction;
            
            this.form = $('div#transactionRemoveForm');

            var dialogOptions = {
                centerVertical: true,
                title: "Tem certeza que deseja remover essa transação?",
                message: self.form,
                buttons: {
                    cancel: {
                        label: 'Cancelar',
                        className: 'btn-danger',
                    }
                },
                callback: async function(result){
                },
            };


            if(!this.currentTransaction.recurrent){
                dialogOptions.buttons.confirm = {
                    label: 'Remover',
                    className: 'btn-success',
                    callback: self.removeTransactionPost
                };
            }

            bootbox.dialog(dialogOptions);
            
            $(".bootbox").unbind('hidden.bs.modal');
        },
        
        removeTransactionPost: async function() {
            var self = this;
            $(".bootbox").modal('hide');
            self.showLoading();
            await axios.delete('/api/finance/account/'+self.currentAccount.id+'/transactions/'+self.currentTransaction.id+"/"+self.updateRecurrentMode)
                .then(resp => {
                    self.loadTransactions();
                }).finally(()=>{
                    self.hideLoading();
                    self.updateRecurrentMode = null;
                });
        },

        removeAllTransactions(){
            this.updateRecurrentMode = "A";
            this.removeTransactionPost();
        },

        removeThisTransactionOnly(){
            this.updateRecurrentMode = "O";
            this.removeTransactionPost();
        },

        updateAllTransactions(){
            this.updateRecurrentMode = "A";
            this.updateTransaction(this.currentTransaction);
        },

        updateThisTransactionOnly(){
            this.updateRecurrentMode = "O";
            this.updateTransaction(this.currentTransaction);
        },

        updatedAmount(){
            if(this.currentTransaction.parceled){
                this.updatedParceledTimes();
            }     
        },

        updatedType(type){
            if(this.blockEventUpdateMode) return;
            if(type == 'R' && this.currentTransaction.recurrent){
                this.currentTransaction.parceled = false;
            }else if(type == 'P' && this.currentTransaction.parceled){
                this.currentTransaction.recurrent = false;
            }            
        },

        updatedParceledTimes() {            
            if(!this.currentTransaction.parceled) return;
            if(!this.currentTransaction.parcels) this.currentTransaction.parcels = [];
            
            if(this.currentTransaction.parceledTimes < 2) this.currentTransaction.parceledTimes = 2;
            if(this.currentTransaction.parceledTimes > 12) this.currentTransaction.parceledTimes = 12;

            if(this.currentTransaction.parcels.length > this.currentTransaction.parceledTimes){
                const popTimes = this.currentTransaction.parcels.length - this.currentTransaction.parceledTimes;
                for (let index = 0; index < popTimes; index++) {
                    this.currentTransaction.parcels.pop();
                }

            }else if(this.currentTransaction.parcels.length < this.currentTransaction.parceledTimes){
                for (let nParcelNumber = 1; nParcelNumber <= this.currentTransaction.parceledTimes; nParcelNumber++) {
                    if(this.currentTransaction.parcels[nParcelNumber - 1] == undefined){
                        this.currentTransaction.parcels[nParcelNumber - 1] = new TransactionParcel();
                    }                    
                    this.currentTransaction.parcels[nParcelNumber - 1].parcelNumber = nParcelNumber;
                    this.currentTransaction.parcels[nParcelNumber - 1].parceledTimes = this.currentTransaction.parceledTimes;
                }
            }        

            this.validateParcels();
        },

        validateParcels() {
            if(!this.currentTransaction.parceled) return;
            if(!this.currentTransaction.parcels) this.currentTransaction.parcels = [];

            if(this.currentTransaction.parcels.filter(parcel => {return parcel.amount == 0}) || this.currentTransaction.parcels.reduce((acc, item) => acc + parseFloat(item['amount']), 0) != this.currentTransaction.amount){
                this.recalcParcels();
            }
        },

        recalcParcels() {
            var parcelAmount = parseFloat(this.getAmountAsFloat(this.currentTransaction.amount) / this.currentTransaction.parceledTimes).toFixed(2);

            for (let nParcelNumber = 1; nParcelNumber <= this.currentTransaction.parceledTimes; nParcelNumber++) {
                this.currentTransaction.parcels[nParcelNumber - 1].amount = parcelAmount;
                if(this.currentTransaction.parcels[nParcelNumber - 1].date == '') this.currentTransaction.parcels[nParcelNumber - 1].date = moment.utc(this.currentTransaction.date).add(nParcelNumber - 1, 'month').format('YYYY-MM-DD');
            }
            this.currentTransaction.parcels[this.currentTransaction.parceledTimes - 1].amount = (this.getAmountAsFloat(this.currentTransaction.amount) - parcelAmount * (this.currentTransaction.parceledTimes - 1)).toFixed(2);
        },

        updatedParcel(parcel){
            if(this.blockEventUpdateMode) return;
            this.currentTransaction.parcels[parcel.parcelNumber - 1] = parcel;
        },

    },
}
</script>

<template>
    <div id="financeComponent">
        <div class="componentLoading">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="row" v-if="this.step=='accountsList'">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Conta</th>
                                    <th scope="col">Saldo atual</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>                      
                                <tr v-bind:key="account.id" v-for="account in accounts">
                                    <td scope="row"><a href="#" @click="showAccount(account)">{{account.name}}</a></td>
                                    <td><money-text :value="parseFloat(account.balance)"/></td>
                                    <td><button class="btn btn-sm btn-primary float-right" @click="showAccount(account)">Abrir</button></td>
                                    <td><button class="btDefault2" v-on:click="updateAccount(account)"><feather type="edit"/></button><button class="btDefault2" v-on:click="removeAccount(account)"><feather type="trash"/></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="btn-group-fab" role="group" aria-label="FAB Menu">
                    <div>
                        <button type="button" class="btn btn-main btn-success has-tooltip" data-placement="left" title="Adicionar nova conta" v-on:click="createNewAccount()"> <feather type="plus"/> </button>
                    </div>
                </div>
            </div>     

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Próximos lançamentos</h4>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Conta</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Dia</th>
                                    <th scope="col">Valor</th>
                                </tr>
                            </thead>
                            <tbody>                      
                                <tr v-bind:key="transaction.id" v-for="transaction in lastTransactions">
                                    <td scope="row">{{transaction.account}}</td>
                                    <td scope="row">{{transaction.description}}</td>
                                    <td scope="row">{{ transaction.formatedDate }}</td>
                                    <td><money-text :value="parseFloat(transaction.type == 'C' ? transaction.amount : -transaction.amount)"/></td>
                                </tr>     
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




        
        <div class="row" v-if="this.step=='accountShow'">
            <div class="col-12">
                <button class="btn btn-sm btn-info float-right" @click="showAccountList()">Voltar</button>
                <h3 class="card-title">{{ currentAccount.name }}</h3>
                <div v-if="currentAccount.agency != null && currentAccount.agency != ''">Agência: {{ currentAccount.agency }}</div> <div v-if="currentAccount.account != null && currentAccount.account != ''">Conta: {{ currentAccount.account }}</div>
                <div class="card-body">
                        <div class="text-center yearNav mb-3">
                            <button v-on:click="updateYear(filtredYear-1)">{{ filtredYear-1 }}</button> 
                            <h1 class="d-inline">{{ filtredYear }}</h1> 
                            <button v-on:click="updateYear(filtredYear+1)">{{ filtredYear+1 }}</button>
                        </div>
                        <button class="btDefault" v-on:click="createNewTransaction()"><feather type="plus"/></button>
                        <div class="text-center monthNav">
                            <button v-on:click="updateMonth(1)" :class="{ active: filtredMonth === '01' }">Jan</button> | 
                            <button v-on:click="updateMonth(2)" :class="{ active: filtredMonth === '02' }">Fev</button> | 
                            <button v-on:click="updateMonth(3)" :class="{ active: filtredMonth === '03' }">Mar</button> | 
                            <button v-on:click="updateMonth(4)" :class="{ active: filtredMonth === '04' }">Abr</button> | 
                            <button v-on:click="updateMonth(5)" :class="{ active: filtredMonth === '05' }">May</button> | 
                            <button v-on:click="updateMonth(6)" :class="{ active: filtredMonth === '06' }">Jun</button> | 
                            <button v-on:click="updateMonth(7)" :class="{ active: filtredMonth === '07' }">Jul</button> | 
                            <button v-on:click="updateMonth(8)" :class="{ active: filtredMonth === '08' }">Ago</button> | 
                            <button v-on:click="updateMonth(9)" :class="{ active: filtredMonth === '09' }">Sep</button> | 
                            <button v-on:click="updateMonth(10)" :class="{ active: filtredMonth === '10' }">Oct</button> | 
                            <button v-on:click="updateMonth(11)" :class="{ active: filtredMonth === '11' }">Nov</button> | 
                            <button v-on:click="updateMonth(12)" :class="{ active: filtredMonth === '12' }">Dez</button>
                        </div>

                        
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Dia</th>
                                    <th>Fornecedor/Cliente</th>
                                    <th>Descrição</th>
                                    <th>Categoria</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Saldo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(transaction, i) in filtredTransactions" :key="i">
                                    <td>{{ transaction.day }}</td>
                                    <td>{{ transaction.people != null && transaction.people.name != null ? transaction.people.name : '-' }}</td>
                                    <td>{{ transaction.description }}</td>
                                    <td>{{ transaction.category != null ? transaction.category.name : '-' }}</td>
                                    <td><money-text :value="parseFloat(transaction.type == 'C' ? transaction.amount : -transaction.amount)" /></td>
                                    <td v-if="transaction.status == 'OK'">Pago</td>
                                    <td v-else>Pendente</td>
                                    <td><money-text :value="parseFloat(transaction.balance)" /></td>
                                    <td class="d-flex"><button class="btDefault2" v-on:click="updateTransaction(transaction)"><feather type="edit"/></button><button class="btDefault2" v-on:click="removeTransaction(transaction)"><feather type="trash"/></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btn-group-fab" role="group" aria-label="FAB Menu">
                        <div>
                            <button type="button" class="btn btn-main btn-success has-tooltip" data-placement="left" title="Add new Transaction" v-on:click="createNewTransaction()"> <feather type="plus"/> </button>
                        </div>
                    </div>
            </div>

        </div>






        <div style="display:none">
            <div id="accountForm">
                <input name="id" class="form-control" type="hidden" v-model="currentAccount.id"/>
                <div class="form-group">
                    <label>Nome</label>
                    <input name="name" class="form-control" type="text" v-model="currentAccount.name"/>
                </div>
                <div class="form-group">
                    <label>Agência</label>
                    <input name="agency" class="form-control" type="text" v-model="currentAccount.agency"/>
                </div>
                <div class="form-group">
                    <label>Conta</label>
                    <input name="account" class="form-control" type="text" v-model="currentAccount.account"/>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <input name="description" class="form-control" type="text" v-model="currentAccount.description"/>
                </div>
                <div class="form-group">
                    <label>Valor Inicial</label>             
                    <div class="input-group input-group-sm my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$ </span>
                        </div>
                        <input class="form-control" v-model.lazy="currentAccount.initial" v-money="money"/>
                    </div>   
                </div>
            </div>



            
            <div id="transactionFormUpdateRecurrent">
                <div class="row">
                    Essa transação possui uma recorrência, escolha uma opção abaixo:
                    <div class="form-group row mt-3">
                        <div class="form-group col-12 text-center px-0">
                            <button @click="updateThisTransactionOnly" class="btn btn-danger mr-auto">Alterar somente essa transação</button>                                        
                        </div>
                        <div class="form-group col-12 text-center px-0">
                            <button @click="updateAllTransactions" class="btn btn-danger mr-auto">Alterar todas as recorrências</button>                                        
                        </div>
                    </div>
                </div>
            </div>



            
            <div id="transactionForm">
                <input name="id" class="form-control" type="hidden" v-model="currentTransaction.id"/>
            
                <div class="form-group">
                    <label>Descrição</label>
                    <input name="description" class="form-control" type="text" v-model="currentTransaction.description"/>
                </div>

                <div class="form-group">
                    <label>Categoria</label>
                    <select name="category" class="form-control" v-model="currentTransaction.financecategory_id">
                        <option value="null"> - </option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fornecedor/Cliente</label>
                    <select name="people" class="form-control" v-model="currentTransaction.people.value">
                        <option value="null"> - </option>
                        <option v-for="people in peoples" :value="people.value">{{ people.name }}</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label>Projeto</label>
                    <select name="project" class="form-control" v-model="currentTransaction.project_id">
                        <option value="null"> - </option>
                        <option v-for="project in projects" :value="project.id" :key="project.id">{{ project.name }}</option>
                    </select>
                </div>

                <div class="form-group" v-if="!currentTransaction.parceled">
                    <label>Data</label>
                    <input name="date" class="form-control" type="date" v-model="currentTransaction.date"/>
                </div>

                <div class="form-group">
                    <label v-if="currentTransaction.parceled">Valor Total</label>  
                    <label v-if="!currentTransaction.parceled">Valor</label>                    
                    <div class="input-group input-group-sm my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$ </span>
                        </div>
                        <input class="form-control" v-model.lazy="currentTransaction.amount" v-money="money" @change="updatedAmount"/>
                    </div>                 
                </div>
                

                <div class="row">       
                    <div class="form-group col-6 px-0">                                    
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="true" v-model.lazy="currentTransaction.recurrent" @change="updatedType('R')">
                                    Recorrente?
                            </label>
                        </div> 
                    </div>   
                    <div class="form-group col-6 px-0">                         
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="true" v-model.lazy="currentTransaction.parceled" @change="updatedType('P')">
                                    Parcelado?
                            </label>
                        </div>
                    </div>
                </div>

                <div v-if="currentTransaction.recurrent">                                    
                    <div class="form-group col-12 px-0">
                        <label>Tipo de recorrência</label>
                        <select class="form-control" v-model="currentTransaction.recurrentType">
                            <option value="D">Diariamente</option>
                            <option value="W">Semanalmente</option>
                            <option value="M">Mensalmente</option>
                            <option value="Y">Anualmente</option>
                        </select>
                    </div>
                </div>

                <div v-if="currentTransaction.recurrentType == 'W'">                                    
                    <div class="form-group col-12 px-0">
                        Somente em                               
                        <div class="form-group row px-0">
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekday2"> Segunda-feira
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekday3"> Terça-feira
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekday4"> Quarta-feira
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekday5"> Quinta-feira
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekday6"> Sexta-feira
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekdayS"> Sábado
                            </label>
                            <label class="col-4">
                                <input type="checkbox" v-model="currentTransaction.recurrentWeekdayD"> Domingo
                            </label>
                        </div>
                    </div>
                </div>

                <div v-if="currentTransaction.recurrent" class="row">                                    
                    <div class="form-group col-3 px-0">
                        <div>Limite</div>
                    </div>                                
                    <div class="form-group col-9 px-0">
                        <div class="row">
                            <label>
                                <input type="radio" value="A" v-model="currentTransaction.recurrentLimit"> Para sempre
                            </label>
                        </div>
                        <div class="row">
                            <label>
                                <input type="radio" value="N" maxlength="1000" v-model="currentTransaction.recurrentLimit"> Quantidade 
                            </label>
                            <div class="form-group row" v-if="currentTransaction.recurrentLimit == 'N'">
                                <input type="number" min="1" max="999" class="form-control mx-2 col" v-model="currentTransaction.recurrentLimitTimes"> vez<span v-if="currentTransaction.recurrentLimitTimes > 1">es</span>
                            </div>
                        </div>
                        <div class="row">
                            <label>
                                <input type="radio" value="U" v-model="currentTransaction.recurrentLimit"> Até
                            </label>
                            <div class="form-group row" v-if="currentTransaction.recurrentLimit == 'U'">
                                <div class="input-group input-group-sm mx-2">
                                    <input type="date" class="form-control" data-date-format="DD-MM-YYYY" v-model="currentTransaction.recurrentLimitDate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="!currentTransaction.recurrent && currentTransaction.parceled">
                    <div class="row pl-0 pr-2">
                        <div class="form-group">
                            <label>Quantidade de Parcelas</label>
                            <input type="number" class="form-control" min="1" max="12" v-model="currentTransaction.parceledTimes" v-on:input="updatedParceledTimes"/>
                        </div>
                    </div>
                    <div class="row px-0">
                        <div class="form-group col-6 px-1" v-for="parcel in currentTransaction.parcels" v-bind:key="parcel.parcelNumber">
                            Parcela {{ parcel.parcelNumber }} / {{ currentTransaction.parceledTimes }}
                            <div class="input-group input-group-sm my-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Data </span>
                                </div>
                                <input name="date" class="form-control" type="date" v-model.lazy="parcel.date" v-on:input="updatedParcel(parcel)"/>
                            </div>           
                            <div class="input-group input-group-sm my-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Valor R$ </span>
                                </div>
                                <input class="form-control" v-model.lazy="parcel.amount" v-money="money" v-on:input="updatedParcel(parcel)"/>
                            </div>          
                            <div class="input-group input-group-sm my-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Status </span>
                                </div>
                                <select name="status" class="form-control" v-model.lazy="parcel.status" v-on:input="updatedParcel(parcel)">
                                    <option value="NOK">Pendente</option>
                                    <option value="OK">Pago</option>
                                </select>
                            </div>          
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="type" class="form-control" v-model="currentTransaction.type">
                        <option value="C">Crédito</option>
                        <option value="D">Débito</option>
                    </select>
                </div>
                <div class="form-group" v-if="!currentTransaction.parceled">
                    <label>Status</label>
                    <select name="status" class="form-control" v-model="currentTransaction.status">
                        <option value="NOK">Pendente</option>
                        <option value="OK">Pago</option>
                    </select>
                </div>
                              
                <div class="alert alert-danger" role="alert" v-if="msgError != ''">
                    <div>{{msgError}}</div>
                </div>                    

            </div>



            
            <div id="transactionRemoveForm">
                <input name="id" class="form-control" type="hidden" v-model="currentTransaction.id"/>

                <b>Dia: </b> {{currentTransaction.date}}<br/>
                <b>Fornecedor/Cliente: </b> {{(currentTransaction.people != null && currentTransaction.people.name != null ? currentTransaction.people.name : '-')}}<br/>
                <b>Projeto: </b> {{(currentTransaction.project != null && currentTransaction.project.name != null ? currentTransaction.project.name : '-')}}<br/>
                <b>Descrição: </b> {{currentTransaction.description}}<br/>
                <b>Valor: </b> <money-text :value="getAmountAsFloat(currentTransaction.amount)" />

                <br/><br/>

                <div class="row" v-if="currentTransaction.recurrent == true && this.updateRecurrentMode == null">
                    Essa transação possui uma recorrência, escolha uma opção abaixo:
                    <div class="form-group row mt-3">
                        <div class="form-group col-12 text-center px-0">
                            <button @click="removeThisTransactionOnly" class="btn btn-danger mr-auto">Remover somente essa transação</button>                                        
                        </div>
                        <div class="form-group col-12 text-center px-0">
                            <button @click="removeAllTransactions" class="btn btn-danger mr-auto">Remover todas as recorrências</button>                                        
                        </div>
                    </div>
                </div>

                <div class="row" v-if="currentTransaction.parceled == true">
                    Essa transação é parcelada, ao excluir será excluida todos os parcelamentos dessa transação
                </div>
            </div>
        </div>
    </div>
</template>