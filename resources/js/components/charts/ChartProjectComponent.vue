<script>
import Vue from 'vue'
import VueChartist from 'vue-chartist';

Vue.use(require('vue-chartist'), {
    messageNoData: "You have not enough data",
    classNoData: "empty"
})

export default {
    components: {
        'VueChartist': VueChartist
    },
    data: function () {
        return {
            data: {
                labels: ['Bananas', 'Apples', 'Grapes'],
                series: [20, 15, 40]            
            },
            options: {
                labelInterpolationFnc: function(value) {
                    return value[0]
                },
                fullWidth: true,
                lineSmooth: true,
                plugins: [
                ]//End of plugins
            },
            responsiveOptions: [
                ['screen and (min-width: 640px)', {
                    chartPadding: 30,
                    labelOffset: 100,
                    labelDirection: 'explode',
                    labelInterpolationFnc: function(value) {
                        return value;
                    }
                }],
                ['screen and (min-width: 1024px)', {
                    labelOffset: 30,
                    chartPadding: 20
                }]
            ],
        };
    },
    mounted() {
    },
};
</script>


<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Total Sales</h4>
            <VueChartist type="Pie" :data="data" :options="options" :responsiveOptions="responsiveOptions" />
        </div>
    </div>
</template>