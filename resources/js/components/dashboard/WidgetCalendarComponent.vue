<script>

import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrapPlugin from '@fullcalendar/bootstrap';
import localePlugin from '@fullcalendar/core/locales/pt-br';
import moment from 'moment';
import EventModel  from '../model/Event';

window.jQuery = $;
export default {
    components: {
        FullCalendar,
    },
    data: function () {
        return {
            events: [],
            calendar : null,
            calendarOptions: {
                plugins: [dayGridPlugin, listPlugin, timeGridPlugin, interactionPlugin, bootstrapPlugin],
                handleWindowResize: true,
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                selectable: false,
                events :  [],
                initialView: 'listWeek',
                headerToolbar: false,
                locale: localePlugin,
            },
        };
    },
    mounted() {
        document.onreadystatechange = () => { this.ready(); };
    },
    methods: {      
        ready: function() {
            this.calendarApi = this.$refs.fullCalendar.getApi();
            this.loadData();
        },

        showLoading: function() {
            $(this.$refs.loading).stop().show();
        },
        
        hideLoading: function() {
            $(this.$refs.loading).stop().hide();
        },

        loadData: function() {
            this.showLoading();
            axios.get('/api/calendar/lastevents').then((response)=>{
                this.calendarOptions.events = response.data.events;  
                this.calendarApi.render();
                $(".fullCalendar").fadeTo(0, 1).show();
            }).finally(()=>{
                this.hideLoading();
            });
        },
    },
};
</script>


<template>
    <div class="col-lg-6 col-md-12">
        <div class="card border-right">
            <div class="card-body">
                <div class="componentLoading" ref="loading">
                    <div class="lds-ripple">
                        <div class="lds-pos"></div>
                        <div class="lds-pos"></div>
                    </div>
                </div>
                    

                <div class="row">
                    <h4 class="card-title">Eventos dessa semana</h4>
                </div>
                    

                <div>                        
                    <FullCalendar class="fullCalendar none" ref="fullCalendar" :options="calendarOptions"/>
                </div>   
            </div>
        </div>
    </div>
</template>
