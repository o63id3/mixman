<script setup lang="ts">
import { inject, type Ref } from 'vue'

const control = inject('control') as { control: Ref<boolean> }
</script>

<template>
  <div @click="control.control.value = !control.control.value">
    <slot />
  </div>
</template>
