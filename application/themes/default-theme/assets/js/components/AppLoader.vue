<template>
  <transition name="fade">
    <div class="app-loader" v-if="value"><span>L O A D I N G</span></div>
  </transition>
</template>

<script>
  export default {
    name: 'app-loader',
    props: {
      value: {
        type: Boolean,
        default: false,
      }
    }
  }
</script>