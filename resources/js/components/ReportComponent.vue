<script>

import Vue from 'vue';
import VueFeather from 'vue-feather';

import moment from 'moment';
Vue.use(VueFeather);

import VueHtmlToPaper from 'vue-html-to-paper';

const optionsPrinter = {
  name: '_blank',
  specs: [
    'fullscreen=no',
    'titlebar=no',
    'scrollbars=no'
  ],
  styles: [
    '/css/app.css'
  ]
}
Vue.use(VueHtmlToPaper, optionsPrinter);

window.jQuery = $;
export default {
    props: [
        'report', 'fullData'
    ],
    data() {
        return {
            currentFilter: {account:0, state:0, user:0, project:0, client:0, provider:0, people:0, owner:0, category:0, step:0},
            filtredData: [],
            transactions: [],
            projects: [],
            events: [],
            showProjectStep: false,
            projectStep: null,
        }
    },
    mounted() {
        this.hideLoading();
        this.currentFilter.startDate = this.report.startDate;
        this.currentFilter.endDate = this.report.endDate;

        if(this.report.mode == "finance.topay"){
            this.currentFilter.type = 'D';
            this.currentFilter.peopletype = 'provider';
        }

        if(this.report.mode == "finance.toreceive"){
            this.currentFilter.type = 'C';
            this.currentFilter.peopletype = 'client';
        }


        this.loadData();
    },
    methods: {        
	    moment,
        showLoading: function() {
            $(".componentLoading").stop().show();
        },
        
        hideLoading: function() {
            $(".componentLoading").stop().hide();
        },
        
        loadData: function() {
            this.showLoading();

            if(this.report.mode == "finance.topay" || this.report.mode == "finance.toreceive"){
                this.transactions = [];
                axios.post('/api/finance/report', this.currentFilter).then((response)=>{
                    this.transactions = response.data.transactions;
                }).finally(()=>{
                    this.hideLoading();
                });
            }

            if(this.report.mode == "projects"){
                this.projects = [];
                axios.post('/api/projects/report', this.currentFilter).then((response)=>{
                    this.showProjectStep = this.currentFilter.step > 0;
                    this.projectStep = this.report.steps.find(item =>{ return item.id == this.currentFilter.step});
                    this.projects = response.data.projects;
                }).finally(()=>{
                    this.hideLoading();
                });
            }

            if(this.report.mode == "calendar"){
                this.events = [];
                axios.post('/api/calendar/report', this.currentFilter).then((response)=>{
                    this.events = response.data.events;
                }).finally(()=>{
                    this.hideLoading();
                });
            }
        },  

        printReport() {
            this.$htmlToPaper('report', null);
        },

    },
}
</script>

<template>
    <div>
        <div class="componentLoading">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="col-12" id="report" ref="content">
            <h1>{{report.title}}</h1>

            <div class="card" v-if="this.report.mode == 'finance.topay' || this.report.mode == 'finance.toreceive'">
                <div class="card-body col-12 row d-flex">
                    <div class="form-group col-3">
                        <label v-if="this.report.mode == 'finance.topay'">Fornecedor:</label>
                        <label v-if="this.report.mode == 'finance.toreceive'">Cliente:</label>
                        <select class="form-control" v-model="currentFilter.people">
                            <option value="0" selected>Todas</option>
                            <option v-for="people in report.peoples" :value="people.id">{{ people.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Data inicial:</label>
                            <input type="date" class="form-control" v-model="currentFilter.startDate" data-date-format="DD-MM-YYYY">
                    </div>
                    <div class="form-group col-3">
                        <label>Data final:</label>
                            <input type="date" class="form-control" v-model="currentFilter.endDate" data-date-format="DD-MM-YYYY">
                    </div>
                    <div class="form-group col-3">
                        <label>Conta:</label>
                        <select class="form-control" v-model="currentFilter.account">
                            <option value="0" selected>Todas</option>
                            <option v-for="account in report.accounts" :value="account.id">{{ account.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Situação:</label>
                        <select class="form-control" v-model="currentFilter.state">
                            <option value="0" selected>Todas</option>
                            <option value="NOK">Pendente</option>
                            <option value="OK">Pago</option>
                        </select>
                    </div>

                </div>
                <div class="form-group col-12 text-right">
                    <button class="btn btn-primary" @click="loadData">Atualizar resultados</button>
                </div>
                <div class="card-body col-12 row d-flex">
                    <table class="table col-12">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Conta</th>
                                <th scope="col" v-if="this.report.mode == 'finance.topay'">Fornecedor</th>
                                <th scope="col" v-if="this.report.mode == 'finance.toreceive'">Cliente</th>
                                <th scope="col">Description</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Situação</th>
                            </tr>
                        </thead>
                        <tbody>                   
                            <tr v-bind:key="transaction.id" v-for="transaction in transactions">
                                <td>{{moment(transaction.date).format('DD/MM/YYYY')}}</td>

                                <td v-if="transaction.people">{{transaction.people.name}}</td>
                                <td v-else>-</td>
                                
                                <td v-if="transaction.account">{{transaction.account.name}}</td>
                                <td v-else>-</td>

                                <td>{{transaction.description}}</td>
                                <td><money-text :value="parseFloat(transaction.amount)" /></td>   
                                <td>{{transaction.status == 'OK' ? 'Pago' : 'Pendente'}}</td>                      
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



            

            <div class="card" v-if="this.report.mode == 'projects'">
                <div class="card-body col-12 row d-flex">
                    <div class="form-group col-3">
                        <label>Cliente:</label>
                        <select class="form-control" v-model="currentFilter.client">
                            <option value="0" selected>Todas</option>
                            <option v-for="client in report.clients" :value="client.id">{{ client.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Categoria:</label>
                        <select class="form-control" v-model="currentFilter.category">
                            <option value="0" selected>Todas</option>
                            <option v-for="category in report.categories" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Etapas:</label>
                        <select class="form-control" v-model="currentFilter.step">
                            <option value="0" selected>Todas</option>
                            <option v-for="step in report.steps" :value="step.id">{{ step.name }}</option>
                        </select>
                    </div>

                </div>
                <div class="form-group col-12 text-right">
                    <button class="btn btn-primary" @click="loadData">Atualizar resultados</button>
                </div>
                <div class="card-body col-12 row d-flex">                  

                        <table class="table col-12">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Responsável</th>
                                    <th scope="col" v-if="showProjectStep == true">Status - {{projectStep.name}}</th>
                                    <th scope="col">Início</th>
                                    <th scope="col">Prazo</th>
                                </tr>
                            </thead>
                            <tbody>                   
                                <tr v-bind:key="project.id" v-for="project in projects">
                                    <td>{{project.name}}</td>

                                    <td v-if="project.client">{{project.client.name}}</td>
                                    <td v-else>-</td>                                    

                                    <td v-if="project.category">{{project.category.name}}</td>
                                    <td v-else>-</td>                                

                                    <td v-if="project.owner">{{project.owner}}</td>
                                    <td v-else>-</td>                                    

                                    <td v-if="showProjectStep == true">{{project.stepStatus.name}}</td>

                                    <td>{{moment(project.startDate).format('DD/MM/YYYY')}}</td>
                                    <td>{{moment(project.endDate).format('DD/MM/YYYY')}}</td>                
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>



            

            <div class="card" v-if="this.report.mode == 'calendar'">
                <div class="card-body col-12 row d-flex">
                    <div class="form-group col-3">
                        <label>Projeto:</label>
                        <select class="form-control" v-model="currentFilter.project">
                            <option value="0" selected>Todos</option>
                            <option v-for="project in report.projects" :value="project.id">{{ project.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Usuário:</label>
                        <select class="form-control" v-model="currentFilter.user">
                            <option value="0" selected>Todos</option>
                            <option v-for="user in report.users" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Status:</label>
                        <select class="form-control" v-model="currentFilter.state">
                            <option value="0" selected>Todos</option>
                            <option value="N">Novo</option>
                            <option value="A">Ativo</option>
                            <option value="P">Pendente</option>
                            <option value="D">Concluído</option>
                        </select>
                    </div>
                    <div class="form-group col-3">
                        <label>Data inicial:</label>
                            <input type="date" class="form-control" v-model="currentFilter.startDate" data-date-format="DD-MM-YYYY">
                    </div>
                    <div class="form-group col-3">
                        <label>Data final:</label>
                            <input type="date" class="form-control" v-model="currentFilter.endDate" data-date-format="DD-MM-YYYY">
                    </div>

                </div>
                <div class="form-group col-12 text-right">
                    <button class="btn btn-primary no-print" @click="loadData">Atualizar resultados</button>
                </div>
                <div class="card-body col-12 row d-flex">                  

                        <table class="table col-12">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Projeto</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Início</th>
                                    <th scope="col">Fim</th>
                                </tr>
                            </thead>
                            <tbody>                   
                                <tr v-bind:key="event.id" v-for="event in events">
                                    <td>{{event.title}}</td>       

                                    <td v-if="event.project">{{event.project.name}}</td>
                                    <td v-else>-</td>  

                                    <td v-if="event.type == 'E'">Evento</td>
                                    <td v-if="event.type == 'R'">Lembrete</td>
                                    <td v-if="event.type == 'T'">Atividade</td>

                                    <td v-if="event.state == 'N'">Novo</td>
                                    <td v-if="event.state == 'A'">Ativo</td>
                                    <td v-if="event.state == 'P'">Pendente</td>
                                    <td v-if="event.state == 'D'">Concluído</td>

                                    <td>{{moment(event.start).format('DD/MM/YYYY HH:mm')}}</td>
                                    <td>{{moment(event.end).format('DD/MM/YYYY HH:mm')}}</td>          
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>






            <div class="form-group col-12 text-right no-print">
                <button class="btn btn-primary" @click="printReport">Imprimir</button>
            </div>
        </div>
    </div>
</template>