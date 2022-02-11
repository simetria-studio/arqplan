<script>
window.jQuery = $;
export default {
    props: [
        'project'
    ],
    data() {
        return {
            files: '',
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
        
        submitFiles: function() {
            this.showLoading();
            let formData = new FormData();
            for( var i = 0; i < this.files.length; i++ ){
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }

            axios.post('/api/projetos/'+this.project+'/arquivos/upload', formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then(function(){
                location.reload();
            })
        },

        handleFilesUpload(){
            this.files = this.$refs.files.files;
        }

    },
}
</script>

<template>
  <div class="container">
    <div class="componentLoading">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="large-12 medium-12 small-12 cell">
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="files" ref="files" multiple v-on:change="handleFilesUpload()">
            <label class="custom-file-label" for="files">Adicionar arquivos</label>
            <button v-if="files" class="btn btn-primary" v-on:click="submitFiles()">Gravar</button>
        </div>
    </div>
  </div>
</template>