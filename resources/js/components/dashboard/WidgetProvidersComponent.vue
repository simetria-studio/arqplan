<script>
export default {
    components: {
    },
    data: function () {
        return {
            value: 0,  
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
            axios.get('/api/providers/count').then((response)=>{
                this.value = response.data.providers;
            }).finally(()=>{
                this.hideLoading();
            });
        },
    },
};
</script>


<template>
    <div class="card border-right">
        <div class="card-body">
            <div class="componentLoading" ref="loading">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>
            <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                    <div class="d-inline-flex align-items-center">
                        <h2 class="text-dark mb-1 font-weight-medium">
                            {{value}}
                        </h2>
                    </div>
                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Fornecedores</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                    <span class="opacity-7 text-muted"><i data-feather="user"></i></span>
                </div>
            </div>
        </div>
    </div>
</template>