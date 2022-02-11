<script>
window.jQuery = $;

export default {
    props: [
        'project', 'img', 'urlupload'
    ],
    data() {
        return {
            errorReason: '',
        }
    },
    mounted() {
        this.hideLoading();
    },
    methods: {        
        showLoading: function() {
            $(".componentLoading").stop().show();
        },
        
        hideLoading: function() {
            $(".componentLoading").stop().hide();
        },

        uploadShowMessage(vue, message){
            this.hideLoading();
            this.errorReason = "Ocorreu um erro, tente novamente";
        },
        uploadDone(files){
            this.hideLoading();
        },
        uploadStart(){
            this.errorReason = ''
            this.showLoading();
        },
    },
}
</script>

<template>
  <div class="container CompanyLogoUploaderComponent">
    <div class="componentLoading">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="large-12 medium-12 small-12 cell py-3">
        <v-uploader :preview-img="img"
                    :button-text="'Atualizar'"
                    :upload-file-url="urlupload"
                    :show-message="uploadShowMessage"
                    :before-upload="uploadStart"
                    @done="uploadDone"></v-uploader>
        <div v-if="errorReason != ''" class="alert alert-danger" role="alert">{{ errorReason }}</div>        
    </div>
  </div>
</template>

<style>
    .CompanyLogoUploaderComponent img {
        image-rendering: -webkit-optimize-contrast;
        height: auto;
    }
</style>   