<script setup lang="ts">
import { inject, computed, type Ref } from 'vue'

const props = withDefaults(
  defineProps<{
    first?: boolean
  }>(),
  {
    first: false,
  },
)

const control = inject('control') as { control: Ref<boolean> }

const isVisible = computed(() => {
  return !props.first ? control.control.value : !control.control.value
})
</script>

<template>
  <div v-if="isVisible">
    <slot />
  </div>
</template>
