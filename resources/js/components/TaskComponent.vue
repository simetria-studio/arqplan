<script>
import dayjs from "dayjs";
import GanttElastic from "gantt-elastic";

import moment from 'moment';
import TaskModel  from './model/Task';

var bootbox = require('bootbox');

let that;

window.jQuery = $;
export default {
    components: {
        GanttElastic,
    },
    props: [
        'projects',
        'flags'
    ],
    data() {
        return {            
            currentTask : new TaskModel(),
            projectsEnableds: [],
            currentProject: 0,
            currentTasks: [
                {
                id: 1,
                label: "Project One",
                nums:'Expected to be delayed by 3 days',
                user:'Molina',
                start: this.getTody(new Date(),4),
                end:this.getTody(new Date(),-30),
                duration: 1  * 24 * 60 * 60 * 1000,
                percent: 85,
                type: "project"
                },
            ],
            showGantt: false,
            tasks: [],
            responsibles: [],
            dynamicStyle: {},
            successStyle: {
                base: {
                    fill: "#1EBC61",
                    stroke: "#0EAC51"
                }
            },
            unfinishedStyle:{
                base: {
                    fill: "#dfe6ec",
                    stroke: "#dfe6ec"
                }
            },
            states: [{title:'Novo', value:'N'}, {title:'Ativo', value:'A'}, {title:'Pendente', value:'P'}, {title:'Concluído', value:'D'}],
            
            options: {
                taskMapping: {
                    progress: "percent"
                },
                maxRows: 10, 
                maxHeight: 500,
                row: {
                    height: 18,//Set line height
                },
                times: {
                    timeScale: 20 * 1000,
                    timeZoom: 20, 
                },
                chart: {
                    grid: {
                        horizontal: {
                        gap: 6 ,//*
                        }
                    },
                    text: {
                        offset: 4,
                        xPadding: 10,
                        display: true
                    },
                    
                    expander: {
                        type: 'chart',
                        display: true,
                        displayIfTaskListHidden: true,
                        offset: 4,
                        size: 20   
                    }
                },

                taskList: {
                    // expander: {
                    //   straight: true,
                    // },
                    columns: [
                        {
                            id: 1,
                            label: "ID",
                            value: "id",
                            width: 0
                        },
                        {
                            id: 2,
                            label: "Atividade",
                            value: "title",
                            width: 150,
                            expander: true,
                            html: true,
                            events: {
                                click({ data, column }) {
                                    that.updateTask(data, column);                        
                                }
                            }
                        },
                        {
                            id: 3,
                            label: "Responsável",
                            value: "responsible_name",
                            width: 110,
                            expander: true,
                            html: true,
                            events: {
                                click({ data, column }) {
                                    that.updateTask(data, column);                        
                                }
                            }
                        },
                        {
                            id: 4,
                            label: "Status",
                            value: "state_name",
                            width: 80,
                            // expander: true,
                            // html: true,                    
                        },
                        {
                            id: 4,
                            label: "Início",
                            value: task => dayjs(task.start).format("DD/MM/YYYY HH:mm"),
                            width: 150,
                            expander: true,
                        },
                        {
                            id: 5,
                            label: "Término",
                            value: task => dayjs(task.end).format("DD/MM/YYYY HH:mm"),
                            width: 150,
                            expander: true,
                        },
                    ],
                },
                calendar: {
                    workingDays: [1, 2, 3, 4, 5, 6],
                    gap: 0, 
                    strokeWidth: 5,
                    hour: {
                        display: true
                    },
                },
                locale: {
                    weekdays:["Domingo","Segunda-Feira","Terça-Feira","Quarta-Feira","Quinta-feira","Sexta-Feira","Sábado"],
                    months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
                },
            },
        }
    },
    mounted() {
        that = this;
        document.onreadystatechange = () => { this.ready(); };
        this.showGantt = true;
    },
    methods: {
        ready: function() {
            this.resetForms();
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
            
            this.$nextTick(() => {
                this.$refs.gantt.scrollToTime(new Date());
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
        
        updateTask: function(taskData) {
            var self = this;
            this.resetForms();

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

        postCreate: function(){
            this.showLoading();
            
            var data = JSON.parse(JSON.stringify(this.currentTask));
            data.start = moment.utc(this.currentTask.start + " " + this.currentTask.startTime).toDate(),
            data.end = moment.utc(this.currentTask.end + " " + this.currentTask.endTime).toDate(),

            axios.post('/api/calendar/new', data).then((response)=>{
                $('.createModal').modal('hide');                    
                this.loadData();
                this.resetForms();
            });
        },        

        postUpdate: function(){
            this.showLoading();
            
            var data = JSON.parse(JSON.stringify(this.currentTask));
            data.start = moment.utc(this.currentTask.start + " " + this.currentTask.startTime).toDate(),
            data.end = moment.utc(this.currentTask.end + " " + this.currentTask.endTime).toDate(),

            axios.post('/api/calendar/update', data).then((response)=>{       
                $('.updateModal').modal('hide');         
                this.loadData();
                this.resetForms();
            });
        },       

        postRemove: function(){
            this.showLoading();
            axios.post('/api/calendar/remove',
            { 
                id: this.currentTask.id,
            }).then((response)=>{       
                $('.updateModal').modal('hide');         
                this.loadData();
                this.resetForms();
            });
        },

           
            
            






            
            handleClose: function(){
            this.dialogVisible = false;
            },
            getTody: function(n,ds=0,ys=0){
                var now = new Date(n);
                        var time = now-24 * 60 * 60 * 1000 * ds; //Get the previous N days
                var d = new Date(time);
                        var year = d.getFullYear()-ys;//Get the time of the previous N years
                var mon = d.getMonth() + 1;
                var day = d.getDate();
                var week = d.getDay();
                var hour =d.getHours();
                var secd = d.getMinutes()
                var week = d.getDay();
                var times = '';
                if (mon == 0) {
                    times = 12 + "-" + (day < 10 ? ("0" + day) : day);
                } else if (mon < 10) {
                    times = (mon < 10 ? ('0' + mon) : mon) + "-" + (day < 10 ? ("0" + day) : day);
                } else {
                    times = mon + "-" + (day < 10 ? ("0" + day) : day);
                }
                var today = year + '-' + times;
            
            return today
                
            },
            //Calculate the time difference
            datedifference: function(date1, date2) {//sDate1 and sDate2 are in 2006-12-18 format  
                var dateSpan, tempDate, iDays;
                var sDate1 = this.getTody(date1)
                sDate1 = Date.parse(sDate1)
                var sDate2 = this.getTody(date2)
                sDate2 = Date.parse(sDate2)
                dateSpan = sDate2 - sDate1;
                dateSpan = Math.abs(dateSpan);
                iDays = Math.floor(dateSpan / (24 * 3600 * 1000));
                return iDays
            },
                //retrieve data---------------------------------------------- -------
            getTaskList: function(id){            
                var  params = {}
                params['projectId'] =id;
                this.$ajax
                .post("/ipm/index.php?a=default.taskList", params)
                .then(function(res) {
                    if (res.data.success) {
                        // that.showGantt = true;
                        that.TaskData.length = []
                        var list = res.data.datas;
                        var startTime = list[0].startTime
                        var endTime= list[list.length-1].endTime
                        var diffTime = that.datedifference(endTime,startTime)
                        var temp1 = {
                            id: Number(id),
                            label: that.gantData.name,
                            nums:'',
                                                user:'None',
                            start: startTime,
                            end:endTime,
                            duration: diffTime  * 24 * 60 * 60 * 1000,
                            percent: 85,
                            type: "project"
                        }
                        that.TaskData.push(temp1)
                        for(var i=0;i<list.length;i++){
                            var temp=  {
                            id: Number(list[i].id )+1,
                            code:list[i].code,
                            label: '<span style="color:#0077c0;">'+list[i].name+'</span>',
                            text:list[i].name,
                            nums:list[i].statusText,
                            user:list[i].operatePerson,
                                                    parentId: Number(list[i].projectId), //parentId should correspond to the id of the parent
                            start: list[i].startTime,
                            end:list[i].endTime,
                            duration: that.datedifference(list[i].endTime,list[i].startTime) * 24 * 60 * 60 * 1000, //Get the time difference The time period when the task is completed
                            percent: 50,
                            type: "milestone",
                            // collapsed: true,
                            style: {
                                base: {
                                fill: "#1EBC61",
                                stroke: "#0EAC51"
                                }
                            }
                            }
                            that.TaskData.push(temp)
                        }
                    
                    
                        if(that.TaskData.length>0){
                        that.showGantt=true;
                        that.tasksUpdates(that.TaskData,2)
                        that.optionsUpdate(that.options,2)

                        }
                
                    } else {
                        that.showGantt = false;
                        that.$message.warning(res.data.message);
                    }
                });
            },
        
            
            tasksUpdates: function(tasks,num) {
            if( num == 2){
                // this.tasks = [];
                this.tasks = tasks;
                
            }else{
                return
            }
            
            
            },
            optionsUpdate: function(options,num ) {
            if(num == 2){

                this.options = options;
            }
            
            },
            styleUpdate: function(style) {
                this.dynamicStyle = style;
            }

    },
}
</script>

<template>

    <div>
        <div class="row">
            <div class="form-group ml-auto">
                <div class="row">
                    <label for="staticEmail" class="col-form-label mr-2">Projetos </label>
                    <div>
                        <select class="form-control w-auto" v-model="currentProject" @change="updateProjectFilter">
                            <option value="0" selected>Todos</option>
                            <option v-for="project in projectsEnableds" :value="project" :key="project.id">{{ project.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <gantt-elastic
                ref="gantt"
                v-show="showGantt"
                :options="options"
                :tasks="currentTasks"
                >
            </gantt-elastic>

            <div class="row p-3">
                <div class="mr-auto">
                    <button @click="createTask" type="submit" class="btn btn-primary">Novo</button>
                </div>
            </div>
            
            <div class="componentLoading" ref="loading">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
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
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    <button @click="postCreate" type="submit" class="btn btn-primary">Gravar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style module>
select {
  max-width: 300px;
}
</style>