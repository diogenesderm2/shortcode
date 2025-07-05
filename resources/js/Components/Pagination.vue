<template>
<h1>Paginacao</h1>


  </template>

<script setup>
import { computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: {
    type: Array,
    required: false,
    default: () => []
  },
  meta: {
    type: Object,
    required: false,
    default: null
  }
})

// Debug: Log apenas os links
onMounted(() => {
  console.log('🔗 Posts Links:', props.links)
})


</script>
