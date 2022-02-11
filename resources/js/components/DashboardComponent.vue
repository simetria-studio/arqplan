<script>

export default {
    components: {
    },
    props: {
      finance: {
        type: Boolean,
        default: false
      },
    },
    data: function () {
        return {
        };
    },
    mounted() {
    },
    methods: {      
    },
};
</script>


<template>
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group cards">
            <widget-projects-component/>
            <widget-clients-component/>
            <widget-providers-component/>
        </div>
        <!-- *************************************************************** -->
        <!-- End First Cards -->
        <!-- *************************************************************** -->


        <div class="card-group cards">
            <widget-finance-to-pay-component v-if="finance"/>
            <widget-finance-to-receive-component v-if="finance"/>
            <widget-calendar-component/>
        </div>
    </div>
</template>

<style>
    .componentLoading .lds-ripple {
        top: calc(50% - 32px);
        left: calc(50% - 32px);
    }
</style>  
