<script setup lang="ts">
import { provide } from 'vue'

const model = defineModel<boolean>()

provide('control', {
  control: model,
})
</script>

<template>
  <div>
    <slot />
  </div>
</template>
