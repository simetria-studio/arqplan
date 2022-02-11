<script>

import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrapPlugin from '@fullcalendar/bootstrap';
import localePlugin from '@fullcalendar/core/locales/pt-br';
import moment from 'moment';
import EventModel  from './model/Event';

window.jQuery = $;
export default {
    components: {
        FullCalendar,
    },
    props: [
        'projects'
    ],
    data() {
        return {            
            events: [],
            states: [{title:'Novo', value:'N'}, {title:'Ativo', value:'A'}, {title:'Pendente', value:'P'}, {title:'Concluído', value:'D'}],
            calendar : null,
            calendarOptions: {
                plugins: [dayGridPlugin, listPlugin, timeGridPlugin, interactionPlugin, bootstrapPlugin],
                handleWindowResize: true,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                selectable: true,
                select: this.onSelectDay,
                eventClick: this.onSelectEvent,
                eventDrop: this.onDragEvent,
                eventAllow: function(dropLocation, draggedEvent) {
                    if (draggedEvent.extendedProps.type == "T") {
                        return false;
                    }
                    else {
                        return true; // or return false to disallow
                    }
                },
                events :  [],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'listMonth,dayGridMonth,timeGridWeek,dayGridDay'
                },
                locale: localePlugin,
            },

            currentEvent: new EventModel(),
            updateRecurrentMode: null,
            datePickerOptions: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            },   
            timePickerOptions: {
                format: 'HH:mm',
                useCurrent: false,
            },    
        }
    },
    mounted() {
        document.onreadystatechange = () => { this.ready(); };
    },
    methods: {
        ready: function() {
            this.calendarApi = this.$refs.fullCalendar.getApi();
            this.resetForms();
            this.loadData();
        },
        
        showLoading: function() {
            $(".componentLoading").stop().show();
        },
        
        hideLoading: function() {
            $(".componentLoading").stop().hide();
        },
        
        loadData: function() {
            $(".componentLoading").stop().show();
            axios.get('/api/calendar/events').then((response)=>{
                this.calendarOptions.events = response.data.events;
                this.calendarApi.render();
                $(".fullCalendar").fadeTo(0, 1).show();
                this.hideLoading();
            });
        },        
        
        resetForms: function() {      
            this.currentEvent = new EventModel();
            this.onEventTypeChanged();        
        },

        onEventTypeChanged:function(){
            switch (this.currentEvent.type) {
                case "T":
                    this.currentEvent.typeName = "atividade";
                    break;
                case "R":
                    this.currentEvent.typeName = "lembrete";
                    break;            
                case "E":
                default:
                    this.currentEvent.typeName = "evento";
                    break;
            }
        },
        
        createNewEvent: function() {
            this.resetForms();

            this.currentEvent.start = moment.format("YYYY-MM-DD");
            this.currentEvent.startTime = "10:00";
            this.currentEvent.end = moment.format("YYYY-MM-DD");
            this.currentEvent.endTime = "12:00";

            $('.createEventModal').modal('show');
        },
        
        onSelectEvent: function(eventData) {
            this.resetForms();
            this.updateRecurrentMode = null;

            if(eventData.event.extendedProps.type == "T") return;
            
            this.currentEvent.id = eventData.event.id;
            this.currentEvent.title = eventData.event.title;
            this.currentEvent.type = eventData.event.extendedProps.type;
            this.currentEvent.state = eventData.event.extendedProps.state;
            this.currentEvent.description = eventData.event.extendedProps.description;
            this.currentEvent.start = moment(eventData.event.start).format("YYYY-MM-DD");
            this.currentEvent.startTime = moment(eventData.event.start).format("HH:mm");
            this.currentEvent.end = moment(eventData.event.end).format("YYYY-MM-DD");
            this.currentEvent.endTime = moment(eventData.event.end).format("HH:mm");
            this.currentEvent.project = eventData.event.extendedProps.project_id;
            this.currentEvent.isPublic = eventData.event.extendedProps.is_public;
            
            this.currentEvent.recurrent = eventData.event.extendedProps.recurrent;
            this.currentEvent.recurrentId = eventData.event.extendedProps.recurrentId;
            this.currentEvent.recurrentType = eventData.event.extendedProps.recurrentType;
            this.currentEvent.recurrentLimit = eventData.event.extendedProps.recurrentLimit;
            this.currentEvent.recurrentLimitTimes = eventData.event.extendedProps.recurrentLimitTimes;
            this.currentEvent.recurrentWeekday2 = eventData.event.extendedProps.recurrentWeekday2;
            this.currentEvent.recurrentWeekday3 = eventData.event.extendedProps.recurrentWeekday3;
            this.currentEvent.recurrentWeekday4 = eventData.event.extendedProps.recurrentWeekday4;
            this.currentEvent.recurrentWeekday5 = eventData.event.extendedProps.recurrentWeekday5;
            this.currentEvent.recurrentWeekday6 = eventData.event.extendedProps.recurrentWeekday6;
            this.currentEvent.recurrentWeekdayS = eventData.event.extendedProps.recurrentWeekdayS;
            this.currentEvent.recurrentWeekdayD = eventData.event.extendedProps.recurrentWeekdayD;
            this.currentEvent.recurrentLimitDate = moment(eventData.event.extendedProps.recurrentLimitDate).format("YYYY-MM-DD");

            this.onEventTypeChanged();

            $('.updateEventModal').modal('show');
        },
        
        onDragEvent: function(eventData) {

            if(eventData.event.extendedProps.type == "T") return;

            this.currentEvent.id = eventData.event.id;            
            this.currentEvent.title = eventData.event.title;
            this.currentEvent.type = eventData.event.extendedProps.type;
            this.currentEvent.state = eventData.event.extendedProps.state;
            this.currentEvent.description = eventData.event.extendedProps.description;
            this.currentEvent.start = moment(eventData.event.start).format("YYYY-MM-DD");
            this.currentEvent.startTime = moment(eventData.event.start).format("HH:mm");
            this.currentEvent.end = moment(eventData.event.end).format("YYYY-MM-DD");
            this.currentEvent.endTime = moment(eventData.event.end).format("HH:mm");
            this.currentEvent.project = eventData.event.extendedProps.project_id;
            this.currentEvent.isPublic = eventData.event.extendedProps.is_public;            
            
            this.currentEvent.recurrent = eventData.event.extendedProps.recurrent;
            this.currentEvent.recurrentId = eventData.event.extendedProps.recurrentId;
            this.currentEvent.recurrentType = eventData.event.extendedProps.recurrentType;
            this.currentEvent.recurrentLimit = eventData.event.extendedProps.recurrentLimit;
            this.currentEvent.recurrentLimitTimes = eventData.event.extendedProps.recurrentLimitTimes;
            this.currentEvent.recurrentWeekday2 = eventData.event.extendedProps.recurrentWeekday2;
            this.currentEvent.recurrentWeekday3 = eventData.event.extendedProps.recurrentWeekday3;
            this.currentEvent.recurrentWeekday4 = eventData.event.extendedProps.recurrentWeekday4;
            this.currentEvent.recurrentWeekday5 = eventData.event.extendedProps.recurrentWeekday5;
            this.currentEvent.recurrentWeekday6 = eventData.event.extendedProps.recurrentWeekday6;
            this.currentEvent.recurrentWeekdayS = eventData.event.extendedProps.recurrentWeekdayS;
            this.currentEvent.recurrentWeekdayD = eventData.event.extendedProps.recurrentWeekdayD;
            this.currentEvent.recurrentLimitDate = moment(eventData.event.extendedProps.recurrentLimitDate).format("YYYY-MM-DD");
            this.onEventTypeChanged();

            this.postUpdateEvent();
        },
        
        onSelectDay: function(eventData) {
            this.resetForms();

            this.currentEvent.start = moment(eventData.start).format("YYYY-MM-DD");
            this.currentEvent.startTime = "10:00";
            this.currentEvent.end = eventData.allDay ? moment(eventData.end).add(-1, 'days').format("YYYY-MM-DD") : moment(eventData.end).format("YYYY-MM-DD");
            this.currentEvent.endTime = "12:00";

            $('.createEventModal').modal('show');
        },

        postCreateEvent: function(){
            this.showLoading();
            
            var data = JSON.parse(JSON.stringify(this.currentEvent));
            data.start = moment.utc(this.currentEvent.start + " " + this.currentEvent.startTime).toDate(),
            data.end = moment.utc(this.currentEvent.end + " " + this.currentEvent.endTime).toDate(),

            axios.post('/api/calendar/new', data).then((response)=>{
                $('.createEventModal').modal('hide');                    
                this.loadData();
                this.resetForms();
            });
        },        

        postUpdateEvent: function(){
            this.showLoading();
            
            var data = JSON.parse(JSON.stringify(this.currentEvent));
            data.start = moment.utc(this.currentEvent.start + " " + this.currentEvent.startTime).toDate(),
            data.end = moment.utc(this.currentEvent.end + " " + this.currentEvent.endTime).toDate(),
            data.updateRecurrentMode = this.updateRecurrentMode;

            axios.post('/api/calendar/update', data).then((response)=>{       
                $('.updateEventModal').modal('hide');         
                this.loadData();
                this.resetForms();
            });
        },       

        postRemoveEvent: function(){
            this.showLoading();
            axios.post('/api/calendar/remove',
            { 
                id: this.currentEvent.id,
                updateRecurrentMode: this.updateRecurrentMode,
            }).then((response)=>{       
                $('.updateEventModal').modal('hide');         
                this.loadData();
                this.resetForms();
            });
        },

        updateAllEvents: function(){
            this.updateRecurrentMode = "A";
        },

        updateThisEventOnly: function(){
            this.updateRecurrentMode = "O";
        },

    },
}
</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <FullCalendar class="fullCalendar none" ref="fullCalendar" :options="calendarOptions"/>
                
                <div class="componentLoading">
                    <div class="lds-ripple">
                        <div class="lds-pos"></div>
                        <div class="lds-pos"></div>
                    </div>
                </div>



                <div class="createEventModal modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Adicionar {{currentEvent.typeName}}</h4>
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <div class="form-group col-8 px-0">
                                        <label>Tipo</label>
                                        <select name="eventType" class="form-control" v-model="currentEvent.type" @change="onEventTypeChanged">
                                            <option value="E">Evento</option>
                                            <option value="T">Atividade</option>
                                            <option value="R">Lembrete</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-4 px-0">
                                        <label>Privado?</label>
                                        <select name="eventIsPublic" class="form-control" v-model="currentEvent.isPublic">
                                            <option value="1">Não</option>
                                            <option value="0">Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" name="eventTitle" class="form-control" v-model="currentEvent.title">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>

                                    <select name="eventState" class="form-control" v-model="currentEvent.state">
                                        <option v-bind:value="state.value" v-for="state in states">{{ state.title }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Início</label>
                                    <div class="input-group input-group-sm my-2">
                                        <input type="date" class="form-control" name="eventStart" data-date-format="DD-MM-YYYY" v-model="currentEvent.start">
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text"> às </span>
                                        </div>
                                        <input type="time-local" class="form-control" name="eventStartTime" v-model="currentEvent.startTime">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Término</label>
                                    <div class="input-group input-group-sm my-2">
                                        <input type="date" class="form-control" name="eventEnd" data-date-format="DD-MM-YYYY" v-model="currentEvent.end">
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text"> às </span>
                                        </div>
                                        <input type="time-local" class="form-control" name="eventEndTime" v-model="currentEvent.endTime">
                                    </div>
                                </div>

                                <div class="form-group">                                    
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="true"
                                                v-model="currentEvent.recurrent">
                                                Recorrente?
                                        </label>
                                    </div>
                                </div>

                                <div v-if="currentEvent.recurrent">                                    
                                    <div class="form-group col-12 px-0">
                                        <label>Tipo de recorrência</label>
                                        <select class="form-control" v-model="currentEvent.recurrentType">
                                            <option value="D">Diariamente</option>
                                            <option value="W">Semanalmente</option>
                                            <option value="M">Mensalmente</option>
                                            <option value="Y">Anualmente</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="currentEvent.recurrentType == 'W'">                                    
                                    <div class="form-group col-12 px-0">
                                        Somente em                               
                                        <div class="form-group row px-0">
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekday2"> Segunda-feira
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekday3"> Terça-feira
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekday4"> Quarta-feira
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekday5"> Quinta-feira
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekday6"> Sexta-feira
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekdayS"> Sábado
                                            </label>
                                            <label class="col-4">
                                                <input type="checkbox" v-model="currentEvent.recurrentWeekdayD"> Domingo
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="currentEvent.recurrent" class="row">                                    
                                    <div class="form-group col-3 px-0">
                                        <div>Limite</div>
                                    </div>                                
                                    <div class="form-group col-9 px-0">
                                        <div class="row">
                                            <label>
                                                <input type="radio" value="A" v-model="currentEvent.recurrentLimit"> Para sempre
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label>
                                                <input type="radio" value="N" maxlength="1000" v-model="currentEvent.recurrentLimit"> Quantidade 
                                            </label>
                                            <div class="form-group row" v-if="currentEvent.recurrentLimit == 'N'">
                                                <input type="number" min="1" max="999" class="form-control mx-2 col" v-model="currentEvent.recurrentLimitTimes"> vez<span v-if="currentEvent.recurrentLimitTimes > 1">es</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label>
                                                <input type="radio" value="U" v-model="currentEvent.recurrentLimit"> Até
                                            </label>
                                            <div class="form-group row" v-if="currentEvent.recurrentLimit == 'U'">
                                                <div class="input-group input-group-sm mx-2">
                                                    <input type="date" class="form-control" data-date-format="DD-MM-YYYY" v-model="currentEvent.recurrentLimitDate">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Descrição</label>
                                    <textarea type="text" name="eventDescription" class="form-control" rows="4" v-model="currentEvent.description"/>
                                </div>

                                <div class="form-group">
                                    <label>Projeto</label>

                                    <select name="eventProject" class="form-control" v-model="currentEvent.project">
                                        <option v-bind:value="project.id" v-for="project in projects">{{ project.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <button @click="postCreateEvent" type="submit" class="btn btn-primary">Gravar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="updateEventModal modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Atualizar {{currentEvent.typeName}}</h4>
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="eventId" readonly class="form-control" v-model="currentEvent.id">

                                <div class="row" v-if="currentEvent.recurrent == true && this.updateRecurrentMode == null">
                                    Esse evento possui uma recorrência, escolha uma opção abaixo:
                                    <div class="form-group row mt-3">
                                        <div class="form-group col-12 text-center px-0">
                                            <button @click="updateThisEventOnly" class="btn btn-danger mr-auto">Alterar somente essa recorrência</button>                                        
                                        </div>
                                        <div class="form-group col-12 text-center px-0">
                                            <button @click="updateAllEvents" class="btn btn-danger mr-auto">Alterar todas as recorrências</button>                                        
                                        </div>
                                    </div>
                                </div>

                                <div v-if="currentEvent.recurrent != true || this.updateRecurrentMode != null">
                                    <div class="row">
                                        <div class="form-group col-8 px-0">
                                            <label>Tipo</label>
                                            <select name="eventType" class="form-control" v-model="currentEvent.type" @change="onEventTypeChanged">
                                                <option value="E">Evento</option>
                                                <option value="R">Lembrete</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-4">
                                            <label>Privado?</label>
                                            <select name="eventIsPublic" class="form-control" v-model="currentEvent.isPublic">
                                                <option value="1">Não</option>
                                                <option value="0">Sim</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" name="eventTitle" class="form-control" v-model="currentEvent.title">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>

                                        <select name="eventState" class="form-control" v-model="currentEvent.state">
                                            <option v-bind:value="state.value" v-for="state in states">{{ state.title }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Início</label>
                                        <div class="input-group input-group-sm my-2">
                                            <input type="date" class="form-control" name="eventStart" v-model="currentEvent.start">
                                            <div class="input-group-prepend input-group-append">
                                                <span class="input-group-text"> às </span>
                                            </div>
                                            <input type="time-local" class="form-control" name="eventStartTime" v-model="currentEvent.startTime">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Término</label>
                                        <div class="input-group input-group-sm my-2">
                                            <input type="date" class="form-control" name="eventEnd" v-model="currentEvent.end">
                                            <div class="input-group-prepend input-group-append">
                                                <span class="input-group-text"> às </span>
                                            </div>
                                            <input type="time-local" class="form-control" name="eventEndTime" v-model="currentEvent.endTime">
                                        </div>
                                    </div>

                                    <div v-if="updateRecurrentMode == 'A'">
                                        <div class="form-group">                                    
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="true"
                                                        v-model="currentEvent.recurrent">
                                                        Recorrente?
                                                </label>
                                            </div>
                                        </div>

                                        <div v-if="currentEvent.recurrent">                                    
                                            <div class="form-group col-12 px-0">
                                                <label>Tipo de recorrência</label>
                                                <select class="form-control" v-model="currentEvent.recurrentType">
                                                    <option value="D">Diariamente</option>
                                                    <option value="W">Semanalmente</option>
                                                    <option value="M">Mensalmente</option>
                                                    <option value="Y">Anualmente</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="currentEvent.recurrent == true && currentEvent.recurrentType == 'W'">                                    
                                            <div class="form-group col-12 px-0">
                                                Somente em                                         
                                                <div class="form-group row px-0">
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekday2"> Segunda-feira
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekday3"> Terça-feira
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekday4"> Quarta-feira
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekday5"> Quinta-feira
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekday6"> Sexta-feira
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekdayS"> Sábado
                                                    </label>
                                                    <label class="col-4">
                                                        <input type="checkbox" v-model="currentEvent.recurrentWeekdayD"> Domingo
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="currentEvent.recurrent == true && updateRecurrentMode == 'A'" class="row">                                    
                                            <div class="form-group col-3 px-0">
                                                <div>Limite</div>
                                            </div>                                
                                            <div class="form-group col-9 px-0">
                                                <div class="row">
                                                    <label>
                                                        <input type="radio" value="A" v-model="currentEvent.recurrentLimit"> Para sempre
                                                    </label>
                                                </div>
                                                <div class="row">
                                                    <label>
                                                        <input type="radio" value="N" v-model="currentEvent.recurrentLimit"> Quantidade 
                                                    </label>
                                                    <div class="form-group row" v-if="currentEvent.recurrentLimit == 'N'">
                                                        <input type="number" min="1" max="999" class="form-control mx-2 col" v-model="currentEvent.recurrentLimitTimes"> vez<span v-if="currentEvent.recurrentLimitTimes > 1">es</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label>
                                                        <input type="radio" value="U" v-model="currentEvent.recurrentLimit"> Até
                                                    </label>
                                                    <div class="form-group row" v-if="currentEvent.recurrentLimit == 'U'">
                                                        <div class="input-group input-group-sm mx-2">
                                                            <input type="date" class="form-control" data-date-format="DD-MM-YYYY" v-model="currentEvent.recurrentLimitDate">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Descrição</label>
                                        <textarea type="text" name="eventDescription" class="form-control" rows="4" v-model="currentEvent.description"/>
                                    </div>

                                    <div class="form-group">
                                        <label>Projeto</label>

                                        <select name="eventProject" class="form-control" v-model="currentEvent.project">
                                            <option v-bind:value="project.id" v-for="project in projects">{{ project.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button v-if="currentEvent.recurrent != true || this.updateRecurrentMode != null" @click="postRemoveEvent" class="btn btn-danger mr-auto">Excluir</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                                <button v-if="currentEvent.recurrent != true || this.updateRecurrentMode != null" @click="postUpdateEvent" type="submit" class="btn btn-primary">Gravar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row p-3">
                    <div class="mr-auto">
                        <button @click="createNewEvent" type="submit" class="btn btn-primary">Novo</button>
                    </div>
                    <div>
                        <a class="fc-daygrid-dot-event fc-event ">
                            <div class="fc-daygrid-event-dot" style="border-color: red;"></div>
                            <div class="fc-event-title">Eventos</div>
                        </a>
                        <a class="fc-daygrid-dot-event fc-event ">
                            <div class="fc-daygrid-event-dot" style="border-color: purple;"></div>
                            <div class="fc-event-title">Atividades</div>
                        </a>
                        <a class="fc-daygrid-dot-event fc-event ">
                            <div class="fc-daygrid-event-dot" style="border-color: blue;"></div>
                            <div class="fc-event-title">Lembretes</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>