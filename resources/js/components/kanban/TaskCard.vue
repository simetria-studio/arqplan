<template>
  <div class="bg-white shadow p-2 border border-white rounded-2xl">
    <div class="flex justify-between">
      <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{task.title}}</p>
      <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm" v-if="task.responsible">
        <v-gravatar :email="task.responsible ? task.responsible.email : '1'" alt="user" class="w-6 h-6 rounded-full ml-3 ml-auto" width="50"/>
        <span>{{task.responsible ? task.responsible_name : ''}}</span>
      </p>
  </div>
    <div class="justify-between">
      <p class="text-sm text-gray-600 mb-1" v-if="task.project">Projeto: {{task.project}}</p>
      <p class="text-sm text-gray-600 mb-1">Data início: {{task.start | formatDate('MM/DD/YYYY hh:mm')}}</p>
      <p class="text-sm text-gray-600 mb-1">Data fim: {{task.end | formatDate('MM/DD/YYYY hh:mm')}}</p>
    </div>
  </div>
</template>
<script>
import Badge from "./Badge.vue";

import Vue from 'vue';
import moment from 'moment';

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment.utc(String(value)).format('DD/MM/YYYY hh:mm')
  }
})

export default {
  components: {
    Badge
  },
  props: {
    task: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    badgeColor() {
      const mappings = {
        Design: "purple",
        "Feature Request": "teal",
        Backend: "blue",
        QA: "green",
        default: "teal"
      };
      return mappings[this.task.type] || mappings.default;
    }
  }
};
</script>
