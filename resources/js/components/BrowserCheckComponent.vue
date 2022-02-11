<template>
  <div v-if="isInternetExplorer" id="browsercheck" class="row auth-inner m-0" variant="primary">
    <feather-icon icon="AlertTriangleIcon" size="21" />
    <span class="c4 c5 bg d3">O seu browser está desatualizado.</span><br/>
    <span class="bx by bg">Para visualizar esta página aconselhamos a utilização dos seguintes browsers: Google Chrome, Apple Safari, Mozilla Firefox ou Microsoft Edge.</span>
  </div>
</template>

<script>
export default {
  computed: {
    isInternetExplorer() {
      return window.document.documentMode
    },
  },
}
</script>

<style lang="scss" scoped>
    #browsercheck {
      display: block;
      width: 100%;
      text-align: center;
      padding: 24px 48px;
      background-color: #666;
      color: white;
      span {
        margin-left: 10px;
      }
    }
</style>
