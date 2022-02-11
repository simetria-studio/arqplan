<template>
  <div id="toast-container" class="toast-container toast-top-center" v-show="stacks.length > 0">
    <div
      class="toast toast-success"
      aria-live="polite"
      style="display: block;"
      v-for="stack in stacks"
      :key="stack._uid"
    >
      <div class="toast-title">{{ stack.title }}</div>
      <div class="toast-message">{{ stack.body }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: [ "title", "body" ],
  data() {
    return {
      stacks: [],
      timeoutStarted: false
    };
  },
  created() {
    if (this.title) {
      this.insertToStack(this.title, this.body);
    }
    window.events.$on("toast-stack", (title, body) => {
      this.insertToStack(title, body);
    });
  },
  mounted() {
    this.startTimeout();
  },
  methods: {
    insertToStack(title, body) {
      this.stacks.push({
        title: title,
        body: body
      });
      this.timeoutStarted == false ? this.startTimeout() : null;
    },
    startTimeout() {
      this.timeoutStarted = true;
      setTimeout(() => {
        this.removeFirstItem();
      }, 3000);
    },
    removeFirstItem() {
      this.stacks.splice(0, 1);
      if (this.stacks.length > 0) {
        this.startTimeout();
      } else {
        this.timeoutStarted = false;
      }
    }
  }
};
</script>