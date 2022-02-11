<script>

import draggable from "vuedraggable";
import TaskCard from "./kanban/TaskCard.vue";

import moment from 'moment';
import TaskModel  from './model/Task';

var bootbox = require('bootbox');

window.jQuery = $;
export default {
    components: {
        TaskCard,
        draggable
    },
    props: [
        'projects',
    ],
    data() {
        return { 
            tasks: [],
            responsibles: [],
            currentTask: {},        
            states: [{title:'Novo', value:'N'}, {title:'Ativo', value:'A'}, {title:'Pendente', value:'P'}, {title:'Concluído', value:'D'}],        
            columns: [
                {
                    id: 1,
                    title: "Novo",
                    tasks: [],
                    class: "colunm_1",
                },
                {
                    id: 2,
                    title: "Ativo",
                    tasks: [],
                    class: "colunm_2",
                },
                {
                    id: 3,
                    title: "Pendente",
                    tasks: [],
                    class: "colunm_3",
                },
                {
                    id: 4,
                    title: "Concluido",
                    tasks: [],
                    class: "colunm_4",
                }
            ],
      
        projectsEnableds: [],
            currentProject: 0,
        }
    },
    mounted() {
        document.onreadystatechange = () => { this.ready(); };
    },
    methods: {
        ready: function() {
            this.loadData();
        },
        
        showLoading: function() {
            $(this.$refs.loading).stop().show();
        },
        
        hideLoading: function() {
            $(this.$refs.loading).stop().hide();
        },
        
        loadData: function() {     
            var _this = this;       
            _this.showLoading();
            axios.get('/api/tasks').then((response)=>{
                _this.tasks = response.data.tasks;
                _this.responsibles = response.data.users;
                _this.updateTaskResponsibles();
            })["finally"](function () {
                _this.updateProjectFilter();
                _this.hideLoading();
            });
        },   
        
        resetForms: function() {      
            this.currentTask = new TaskModel();
        },

        updateTaskResponsibles: function(){
            this.tasks.forEach(task => {
                task.responsible = this.responsibles?.find(responsible => responsible.id == task.responsible_id);
                task.responsible_name = '-';
                if(task.responsible != null && task.responsible?.name != null && task.responsible?.lastname != null)
                task.responsible_name = task.responsible.name + ' ' + task.responsible.lastname;
                task.project = this.projects?.find(project => project.id == task.project_id)?.name;
            });
        },

        updateProjectFilter: function(){
            this.projectsEnableds = this.projects.filter((project) => { 
                return this.tasks.some(task=> task.project_id == project.id);
            });

            if(!this.projectsEnableds.some(p => p.id == this.currentProject.id)){
                this.currentProject = 0;
            }

            if(this.currentProject != null && this.currentProject.id > 0){
                this.currentTasks = this.tasks.filter((task) => { 
                    return task.project_id == this.currentProject.id;
                });
            }else{         
                this.currentTasks = this.tasks;
            }

            this.columns[0].tasks = this.currentTasks.filter( task => task.state == "N"); //Novo
            this.columns[1].tasks = this.currentTasks.filter( task => task.state == "A"); //Ativo
            this.columns[2].tasks = this.currentTasks.filter( task => task.state == "P"); //Pendente
            this.columns[3].tasks = this.currentTasks.filter( task => task.state == "D"); //Concluido
        },

        updateTask: function(columnId, evt) {
            var status;
            switch (columnId) {
                case 2:
                    status = 'A'
                    break;
                case 3:
                    status = 'P'
                    break;
                case 4:
                    status = 'D'
                    break;            
                default:
                    status = 'N'
                    break;
            }

            this.postUpdate(this.columns[columnId-1].tasks[evt.newIndex].id, status);
        },     

        postUpdate: function(taskId, status){
            this.showLoading();
            
            var data = {status:status};
            axios.put('/api/tasks/'+taskId+'/status', data).then((response)=>{
                this.loadData();
            });
        },

        
        
        createTask: function() {
            var self = this;
            this.currentTask = new TaskModel();
            this.form = $('div#createForm');

            bootbox.dialog({
                centerVertical: true,
                title: "Adicionar nova atividade",
                message: self.form,
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Gravar',
                        className: 'btn-success',
                        callback: async function(){ 
                            self.showLoading();
            
                            var data = JSON.parse(JSON.stringify(self.currentTask));
                            data.start = moment.utc(self.currentTask.start + " " + self.currentTask.startTime).toDate(),
                            data.end = moment.utc(self.currentTask.end + " " + self.currentTask.endTime).toDate(),

                            await axios.post('/api/tasks/new', data)
                            .then(resp => {
                                self.loadData();
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

        editTask: function(taskid, event){
            var self = this;
            this.resetForms();

            var taskData = this.tasks?.find(task => task.id == taskid);

            this.currentTask = JSON.parse(JSON.stringify(taskData));
            this.currentTask.start = moment.utc(taskData.start).format("YYYY-MM-DD");
            this.currentTask.startTime = moment.utc(taskData.start).format("HH:mm");
            this.currentTask.end = moment.utc(taskData.end).format("YYYY-MM-DD");
            this.currentTask.endTime = moment.utc(taskData.end).format("HH:mm");
            
            this.currentTask.recurrent = false;
            this.form = $('div#createForm');

            bootbox.dialog({
                centerVertical: true,
                title: "Editar atividade",
                message: self.form,
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Gravar',
                        className: 'btn-success',
                        callback: async function(){ 
                            self.showLoading();
            
                            var data = JSON.parse(JSON.stringify(self.currentTask));
                            data.start = moment.utc(self.currentTask.start + " " + self.currentTask.startTime).toDate(),
                            data.end = moment.utc(self.currentTask.end + " " + self.currentTask.endTime).toDate(),

                            await axios.put('/api/tasks/'+data.id, data)
                            .then(resp => {
                                self.loadData();
                            }).finally(()=>{
                                self.hideLoading();
                            });
                        },
                    },
                    remove: {
                        label: 'Excluir',
                        className: 'btn-danger',
                        callback: async function(){ 
                            self.showLoading();
            
                            var data = JSON.parse(JSON.stringify(self.currentTask));
                            data.start = moment.utc(self.currentTask.start + " " + self.currentTask.startTime).toDate(),
                            data.end = moment.utc(self.currentTask.end + " " + self.currentTask.endTime).toDate(),

                            await axios.delete('/api/tasks/'+data.id, data)
                            .then(resp => {
                                self.loadData();
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
    },
}
</script>

<template>
    <div>
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <label for="staticEmail" class="col-form-label mr-2">Projeto </label>
                    <div>
                        <select class="form-control w-auto" v-model="currentProject" @change="updateProjectFilter">
                            <option value="0" selected>Todos</option>
                            <option v-for="project in projectsEnableds" :value="project" :key="project.id">{{ project.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <button @click="createTask" type="submit" class="btn btn-primary">Novo</button>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="min-h-screen flex overflow-x-auto overflow-y-hidden py-2">
                <div
                    v-for="column in columns"
                    :key="column.title"
                    class="bg-gray-100 rounded-lg px-3 py-3 column-width rounded mr-4"
                    :class="column.class"
                >
                    <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{column.title}}</p>
                    <draggable :list="column.tasks" :animation="200" class="h-full" ghost-class="ghost-card" group="tasks" @add="updateTask(column.id, $event)">
                        <task-card
                            v-for="(task) in column.tasks"
                            v-on:dblclick.native="editTask(task.id, $event)"
                            :key="task.id"
                            :task="task"
                            class="mt-3 cursor-move"
                        ></task-card>
                    </draggable>
                </div>
            </div>

            <div style="display:none">
                <div id="createForm">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="taskTitle" class="form-control" v-model="currentTask.title">
                    </div>

                    <div class="form-group">
                        <label>Status</label>

                        <select class="form-control" v-model="currentTask.state">
                            <option v-bind:value="state.value" v-for="state in states">{{ state.title }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Responsável</label>

                        <select class="form-control" v-model="currentTask.responsible_id">
                            <option value="null" selected>-</option>
                            <option v-bind:value="responsible.id" v-for="responsible in responsibles">{{ responsible.name + ' ' + responsible.lastname }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Início</label>
                        <div class="input-group input-group-sm my-2">
                            <input type="date" class="form-control" name="taskStart" data-date-format="DD-MM-YYYY" v-model="currentTask.start">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text"> às </span>
                            </div>
                            <input type="time" class="form-control" name="taskStartTime" v-model="currentTask.startTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Término</label>
                        <div class="input-group input-group-sm my-2">
                            <input type="date" class="form-control" name="taskEnd" data-date-format="DD-MM-YYYY" v-model="currentTask.end">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text"> às </span>
                            </div>
                            <input type="time" class="form-control" name="taskEndTime" v-model="currentTask.endTime">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea type="text" name="taskDescription" class="form-control" rows="4" v-model="currentTask.description"/>
                    </div>

                    <div class="form-group">
                        <label>Projeto</label>

                        <select name="taskProject" class="form-control" v-model="currentTask.project_id">
                            <option value="null" selected>-</option>
                            <option v-bind:value="project.id" v-for="project in projects">{{ project.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.column-width {
  min-width: 320px;
  width: 320px;
}
/* Unfortunately @apply cannot be setup in codesandbox, 
but you'd use "@apply border opacity-50 border-blue-500 bg-gray-200" here */
.ghost-card {
  opacity: 0.5;
  background: #F7FAFC;
  border: 1px solid #4299e1;
}

.colunm_1 {
    background-color: rgb(226, 226, 226);
}
.colunm_2 {
    background-color: rgb(178, 216, 252);
}
.colunm_3 {
    background-color: rgb(252, 157, 157);
}
.colunm_4 {
    background-color: rgb(211, 255, 211);
}
</style>